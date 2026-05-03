<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $barang = Barang::latest()->paginate(10);
        return view('barang.index', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|unique:barang|max:255',
            'satuan' => 'nullable|string|max:50',
            'stok_min' => 'nullable|integer|min:0',
        ]);

        Barang::create([
            'nama_barang' => $validated['nama_barang'],
            'satuan' => $validated['satuan'] ?? null,
            'stok_min' => $validated['stok_min'] ?? 5,
            'stok' => 0,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang): View
    {
        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang): View
    {
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang): RedirectResponse
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|unique:barang,nama_barang,' . $barang->id . '|max:255',
            'satuan' => 'nullable|string|max:50',
            'stok_min' => 'nullable|integer|min:0',
        ]);

        $barang->update([
            'nama_barang' => $validated['nama_barang'],
            'satuan' => $validated['satuan'] ?? $barang->satuan,
            'stok_min' => $validated['stok_min'] ?? $barang->stok_min,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang): RedirectResponse
    {
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
