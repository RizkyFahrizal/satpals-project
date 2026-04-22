<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingApprovedMail;
use App\Models\EquipmentRentalRequest;
use App\Models\EquipmentRentalRequestItem;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EquipmentRentalRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $query = EquipmentRentalRequest::query();

            // Filter by status
            if ($request->has('status') && $request->status !== null && $request->status !== '') {
                if ($request->status === 'completed') {
                    $query->whereIn('status', ['completed', 'done']);
                } else {
                    $query->where('status', $request->status);
                }
            }

            // Search by order number or renter name
            if ($request->has('search') && $request->search !== '') {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($searchQuery) use ($searchTerm) {
                    $searchQuery->where('order_number', 'like', $searchTerm)
                        ->orWhere('renter_name', 'like', $searchTerm);
                });
            }

            // Date range filter
            if ($request->has('start_date') && $request->start_date !== '') {
                $query->whereDate('start_date', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date !== '') {
                $query->whereDate('start_date', '<=', $request->end_date);
            }

            // Sort
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginate
            $requests = $query->paginate(15)->appends($request->query());

            $baseQuery = EquipmentRentalRequest::query();

            return view('admin.equipment-rental-requests.index', [
                'requests' => $requests,
                'selectedStatus' => $request->get('status'),
                'searchTerm' => $request->get('search'),
                'startDate' => $request->get('start_date'),
                'endDate' => $request->get('end_date'),
                'allCount' => (clone $baseQuery)->count(),
                'pendingCount' => (clone $baseQuery)->where('status', 'pending')->count(),
                'approvedCount' => (clone $baseQuery)->where('status', 'approved')->count(),
                'cancelledCount' => (clone $baseQuery)->where('status', 'cancelled')->count(),
                'completedCount' => (clone $baseQuery)->whereIn('status', ['completed', 'done'])->count(),
                'rejectedCount' => (clone $baseQuery)->where('status', 'rejected')->count(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Gagal memuat data permintaan rental: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $rentalRequest = EquipmentRentalRequest::with(['items.equipment', 'income', 'approvedBy'])->findOrFail($id);

            return view('admin.equipment-rental-requests.show', [
                'rentalRequest' => $rentalRequest,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Permintaan rental tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Gagal membuka detail permintaan rental: ' . $e->getMessage());
        }
    }

    /**
     * Approve a rental request.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        try {
            $rentalRequest = EquipmentRentalRequest::findOrFail($id);

            if ($rentalRequest->status !== 'pending') {
                return redirect()->route('admin.equipment-rental-requests.index')
                    ->with('error', 'Hanya permintaan yang pending yang dapat disetujui.');
            }

            DB::beginTransaction();

            $income = Income::create([
                'title' => 'Persewaan Alat - ' . $rentalRequest->order_number,
                'description' => 'Persewaan Alat - ' . $rentalRequest->order_number,
                'nominal' => $rentalRequest->total_price,
                'source' => 'Persewaan Alat',
                'status' => 'pending',
                'income_date' => now(),
                'created_by' => Auth::id(),
                'creator_name' => $rentalRequest->renter_name,
            ]);

            $rentalRequest->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'income_id' => $income->id,
                'admin_notes' => ($rentalRequest->admin_notes ? $rentalRequest->admin_notes . "\n" : '') .
                    "[Disetujui oleh " . Auth::user()->name . " pada " . now()->format('d-m-Y H:i:s') . "]",
            ]);

            // Send approval email
            $this->sendApprovalEmail($rentalRequest->load('items.equipment'));

            DB::commit();

            return redirect()->route('admin.equipment-rental-requests.show', $id)
                ->with('success', 'Permintaan rental berhasil disetujui. Income telah dibuat dan invoice dikirim ke email pelanggan.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Permintaan rental tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Gagal menyetujui permintaan rental: ' . $e->getMessage());
        }
    }

    /**
     * Reject a rental request.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        try {
            $rentalRequest = EquipmentRentalRequest::findOrFail($id);

            if ($rentalRequest->status !== 'pending') {
                return redirect()->route('admin.equipment-rental-requests.index')
                    ->with('error', 'Hanya permintaan yang pending yang dapat ditolak.');
            }

            $validated = $request->validate([
                'rejection_reason' => 'required|string|max:1000',
            ]);

            DB::beginTransaction();

            $rentalRequest->update([
                'status' => 'rejected',
                'admin_notes' => ($rentalRequest->admin_notes ? $rentalRequest->admin_notes . "\n" : '') .
                    "[Ditolak oleh " . Auth::user()->name . " pada " . now()->format('d-m-Y H:i:s') . "]\n" .
                    "Alasan: " . $validated['rejection_reason'],
            ]);

            // Send rejection email
            $this->sendRejectionEmail($rentalRequest, $validated['rejection_reason']);

            DB::commit();

            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('success', 'Permintaan rental berhasil ditolak. Email notifikasi telah dikirim.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Permintaan rental tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Gagal menolak permintaan rental: ' . $e->getMessage());
        }
    }

    /**
     * Mark a rental request as in progress.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function markInProgress($id)
    {
        try {
            $rentalRequest = EquipmentRentalRequest::findOrFail($id);

            if ($rentalRequest->status !== 'approved') {
                return redirect()->route('admin.equipment-rental-requests.index')
                    ->with('error', 'Hanya permintaan yang disetujui yang dapat dibatalkan.');
            }

            DB::beginTransaction();

            $rentalRequest->update([
                'status' => 'cancelled',
                'admin_notes' => ($rentalRequest->admin_notes ? $rentalRequest->admin_notes . "\n" : '') .
                    "[Dibatalkan oleh " . Auth::user()->name . " pada " . now()->format('d-m-Y H:i:s') . "]",
            ]);

            DB::commit();

            return redirect()->route('admin.equipment-rental-requests.show', $id)
                ->with('success', 'Permintaan rental berhasil dibatalkan.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Permintaan rental tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Gagal memulai permintaan rental: ' . $e->getMessage());
        }
    }

    /**
     * Mark a rental request as complete.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function complete($id)
    {
        try {
            $rentalRequest = EquipmentRentalRequest::findOrFail($id);

            if ($rentalRequest->status !== 'approved') {
                return redirect()->route('admin.equipment-rental-requests.index')
                    ->with('error', 'Hanya permintaan yang disetujui yang dapat diselesaikan.');
            }

            DB::beginTransaction();

            $rentalRequest->update([
                'status' => 'completed',
                'admin_notes' => ($rentalRequest->admin_notes ? $rentalRequest->admin_notes . "\n" : '') .
                    "[Diselesaikan oleh " . Auth::user()->name . " pada " . now()->format('d-m-Y H:i:s') . "]",
            ]);

            // Send completion email
            $this->sendCompletionEmail($rentalRequest);

            DB::commit();

            return redirect()->route('admin.equipment-rental-requests.show', $id)
                ->with('success', 'Permintaan rental berhasil diselesaikan.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Permintaan rental tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Gagal menyelesaikan permintaan rental: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage (only rejected requests).
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $rentalRequest = EquipmentRentalRequest::findOrFail($id);

            if ($rentalRequest->status !== 'rejected') {
                return redirect()->route('admin.equipment-rental-requests.index')
                    ->with('error', 'Hanya permintaan yang ditolak yang dapat dihapus.');
            }

            DB::beginTransaction();

            // Delete related items
            $rentalRequest->items()->delete();

            // Delete rental request
            $rentalRequest->delete();

            DB::commit();

            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('success', 'Permintaan rental berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Permintaan rental tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment-rental-requests.index')
                ->with('error', 'Gagal menghapus permintaan rental: ' . $e->getMessage());
        }
    }

    /**
     * Send approval email to renter.
     *
     * @param EquipmentRentalRequest $rentalRequest
     * @return void
     */
    private function sendApprovalEmail(EquipmentRentalRequest $rentalRequest)
    {
        try {
            // Send email with PDF invoice attachment
            Mail::to($rentalRequest->renter_email)
                ->send(new BookingApprovedMail($rentalRequest));
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }
    }

    /**
     * Send rejection email to renter.
     *
     * @param EquipmentRentalRequest $rentalRequest
     * @param string $rejectionReason
     * @return void
     */
    private function sendRejectionEmail(EquipmentRentalRequest $rentalRequest, $rejectionReason)
    {
        try {
            $data = [
                'order_number' => $rentalRequest->order_number,
                'renter_name' => $rentalRequest->renter_name,
                'rejection_reason' => $rejectionReason,
            ];

            // Send email using mail facade
            // Mail::send('emails.equipment-rental-rejected', $data, function ($message) use ($rentalRequest) {
            //     $message->to($rentalRequest->renter_email)
            //         ->subject('Permintaan Rental Peralatan Anda Telah Ditolak');
            // });
        } catch (\Exception $e) {
            \Log::error('Failed to send rejection email: ' . $e->getMessage());
        }
    }

    /**
     * Send completion email to renter.
     *
     * @param EquipmentRentalRequest $rentalRequest
     * @return void
     */
    private function sendCompletionEmail(EquipmentRentalRequest $rentalRequest)
    {
        try {
            $data = [
                'order_number' => $rentalRequest->order_number,
                'renter_name' => $rentalRequest->renter_name,
                'end_date' => $rentalRequest->end_date->format('d-m-Y'),
            ];

            // Send email using mail facade
            // Mail::send('emails.equipment-rental-completed', $data, function ($message) use ($rentalRequest) {
            //     $message->to($rentalRequest->renter_email)
            //         ->subject('Rental Peralatan Anda Telah Selesai');
            // });
        } catch (\Exception $e) {
            \Log::error('Failed to send completion email: ' . $e->getMessage());
        }
    }
}
