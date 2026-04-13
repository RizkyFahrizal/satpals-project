<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentRental;
use App\Models\EquipmentRentalUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EquipmentController extends Controller
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
            $query = EquipmentRental::query();

            // Filter by category (paket/satuan)
            if ($request->has('category') && $request->category !== null && $request->category !== '') {
                $query->where('category', $request->category);
            }

            // Search by name
            if ($request->has('search') && $request->search !== '') {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Sort
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginate
            $equipments = $query->paginate(15)->appends($request->query());

            return view('admin.equipment.index', [
                'equipments' => $equipments,
                'selectedCategory' => $request->get('category'),
                'searchTerm' => $request->get('search'),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Gagal memuat data peralatan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('admin.equipment.create');
        } catch (\Exception $e) {
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Gagal membuka form tambah peralatan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|in:paket,satuan',
                'description' => 'nullable|string',
                'price_per_day' => 'required|numeric|min:0',
                'operator_crew_price' => 'nullable|numeric|min:0',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'notes' => 'nullable|string',
                'units' => 'required_if:category,paket|nullable|array',
                'units.*.unit_name' => 'required_with:units|string|max:255',
                'units.*.quantity' => 'required_with:units|integer|min:1',
                'units.*.description' => 'nullable|string',
            ]);

            DB::beginTransaction();

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('equipment', 'public');
            }

            // Create equipment
            $equipment = EquipmentRental::create([
                'name' => $validated['name'],
                'category' => $validated['category'],
                'description' => $validated['description'] ?? null,
                'photo' => $photoPath,
                'price_per_day' => $validated['price_per_day'],
                'operator_crew_price' => $validated['operator_crew_price'] ?? 0,
                'notes' => $validated['notes'] ?? null,
                'is_available' => true,
            ]);

            // Create units if category is paket
            if ($validated['category'] === 'paket' && isset($validated['units'])) {
                foreach ($validated['units'] as $unit) {
                    EquipmentRentalUnit::create([
                        'equipment_rental_id' => $equipment->id,
                        'unit_name' => $unit['unit_name'],
                        'quantity' => $unit['quantity'],
                        'description' => $unit['description'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.equipment.index')
                ->with('success', 'Peralatan berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan peralatan: ' . $e->getMessage())
                ->withInput();
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
            $equipment = EquipmentRental::with('units', 'requestItems')->findOrFail($id);

            return view('admin.equipment.show', [
                'equipment' => $equipment,
                'units' => $equipment->units,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Peralatan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Gagal membuka detail peralatan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $equipment = EquipmentRental::findOrFail($id);
            $units = $equipment->units()->get();

            return view('admin.equipment.edit', [
                'equipment' => $equipment,
                'units' => $units,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Peralatan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Gagal membuka form edit peralatan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $equipment = EquipmentRental::findOrFail($id);

            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|in:paket,satuan',
                'description' => 'nullable|string',
                'price_per_day' => 'required|numeric|min:0',
                'operator_crew_price' => 'nullable|numeric|min:0',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'notes' => 'nullable|string',
                'units' => 'required_if:category,paket|nullable|array',
                'units.*.id' => 'nullable|integer',
                'units.*.unit_name' => 'required_with:units|string|max:255',
                'units.*.quantity' => 'required_with:units|integer|min:1',
                'units.*.description' => 'nullable|string',
            ]);

            DB::beginTransaction();

            // Handle photo upload
            $photoPath = $equipment->photo;
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $request->file('photo')->store('equipment', 'public');
            }

            // Update equipment
            $equipment->update([
                'name' => $validated['name'],
                'category' => $validated['category'],
                'description' => $validated['description'] ?? null,
                'photo' => $photoPath,
                'price_per_day' => $validated['price_per_day'],
                'operator_crew_price' => $validated['operator_crew_price'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Handle units if category is paket
            if ($validated['category'] === 'paket') {
                // Delete existing units
                $equipment->units()->delete();

                // Create new units
                if (isset($validated['units'])) {
                    foreach ($validated['units'] as $unit) {
                        EquipmentRentalUnit::create([
                            'equipment_rental_id' => $equipment->id,
                            'unit_name' => $unit['unit_name'],
                            'quantity' => $unit['quantity'],
                            'description' => $unit['description'] ?? null,
                        ]);
                    }
                }
            } else {
                // If changing to satuan, delete all units
                $equipment->units()->delete();
            }

            DB::commit();

            return redirect()->route('admin.equipment.index')
                ->with('success', 'Peralatan berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Peralatan tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui peralatan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $equipment = EquipmentRental::findOrFail($id);

            DB::beginTransaction();

            // Delete photo if exists
            if ($equipment->photo) {
                Storage::disk('public')->delete($equipment->photo);
            }

            // Delete related units
            $equipment->units()->delete();

            // Delete equipment
            $equipment->delete();

            DB::commit();

            return redirect()->route('admin.equipment.index')
                ->with('success', 'Peralatan berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Peralatan tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Gagal menghapus peralatan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the availability status of the equipment.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function toggleAvailability($id)
    {
        try {
            $equipment = EquipmentRental::findOrFail($id);

            $equipment->update([
                'is_available' => !$equipment->is_available,
            ]);

            $status = $equipment->is_available ? 'tersedia' : 'tidak tersedia';

            return redirect()->route('admin.equipment.index')
                ->with('success', 'Status peralatan berhasil diubah menjadi ' . $status . '.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Peralatan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Gagal mengubah status peralatan: ' . $e->getMessage());
        }
    }
}
