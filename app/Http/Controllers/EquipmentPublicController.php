<?php

namespace App\Http\Controllers;

use App\Models\EquipmentRental;
use Illuminate\Http\Request;

class EquipmentPublicController extends Controller
{
    /**
     * Display equipment catalog
     */
    public function index(Request $request)
    {
        $query = EquipmentRental::where('is_available', true);

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filter by price range
        if ($request->has('price_min') && $request->price_min != '') {
            $query->where('price_per_day', '>=', $request->price_min);
        }
        
        if ($request->has('price_max') && $request->price_max != '') {
            $query->where('price_per_day', '<=', $request->price_max);
        }

        // Search by name
        if ($request->has('search') && $request->search != '') {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', $searchTerm)
                  ->orWhere('description', 'LIKE', $searchTerm);
            });
        }

        $equipments = $query->paginate(12);
        
        // Group by category for sidebar
        $categories = EquipmentRental::where('is_available', true)
            ->distinct('category')
            ->pluck('category');

        return view('equipment.index', [
            'equipments' => $equipments,
            'categories' => $categories,
            'selectedCategory' => $request->category ?? null,
            'searchQuery' => $request->search ?? null,
            'priceMin' => $request->price_min ?? null,
            'priceMax' => $request->price_max ?? null,
        ]);
    }

    /**
     * Display equipment detail
     */
    public function show($id)
    {
        try {
            $equipment = EquipmentRental::with('units', 'requestItems')
                ->where('is_available', true)
                ->findOrFail($id);

            return view('equipment.show', [
                'equipment' => $equipment,
                'units' => $equipment->units,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('equipment.index')
                ->with('error', 'Peralatan tidak ditemukan.');
        }
    }

    /**
     * Add equipment to cart (session)
     */
    public function addToCart(Request $request, $id)
    {
        try {
            $equipment = EquipmentRental::where('is_available', true)->findOrFail($id);

            $cart = session()->get('cart', []);

            // Check if equipment already in cart
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] += $request->quantity ?? 1;
            } else {
                $cart[$id] = [
                    'equipment_id' => $equipment->id,
                    'name' => $equipment->name,
                    'category' => $equipment->category,
                    'price_per_day' => $equipment->price_per_day,
                    'photo' => $equipment->photo,
                    'quantity' => $request->quantity ?? 1,
                ];
            }

            session()->put('cart', $cart);

            return redirect()->back()
                ->with('success', 'Peralatan berhasil ditambahkan ke keranjang!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan ke keranjang: ' . $e->getMessage());
        }
    }
}
