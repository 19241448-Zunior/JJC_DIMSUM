<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $barangMasuk = BarangMasuk::with(['barang', 'user', 'voidRequester'])
            ->latest()
            ->paginate(10);

        return view('barang_masuk.index', compact('barangMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $barang = Barang::all();
        return view('barang_masuk.create', compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $validated['tanggal_masuk'] = $validated['tanggal'];
        unset($validated['tanggal']);

        $validated['user_id'] = auth()->id();
        $validated['created_at'] = now();
        $validated['updated_at'] = now();
        $validated['void_status'] = 'none';

        BarangMasuk::create($validated);

        // Increase barang stok
        $barang = Barang::find($validated['barang_id']);
        $barang->increment('stok', $validated['jumlah']);

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangMasuk $barangMasuk): View
    {
        return view('barang_masuk.show', compact('barangMasuk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangMasuk $barangMasuk): View
    {
        $barang = Barang::all();
        return view('barang_masuk.edit', compact('barangMasuk', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangMasuk $barangMasuk): RedirectResponse
    {
        $this->authorize('update', $barangMasuk);

        if ($barangMasuk->void_status === 'pending') {
            return redirect()->route('barang-masuk.index')->withErrors([
                'void' => 'Data dengan status Pending Void tidak dapat diubah.',
            ]);
        }

        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $validated['tanggal_masuk'] = $validated['tanggal'];
        unset($validated['tanggal']);

        // Calculate the difference in jumlah
        $jumlahDiff = $validated['jumlah'] - $barangMasuk->jumlah;

        // Update barang stok based on difference
        if ($jumlahDiff != 0) {
            $barang = Barang::find($barangMasuk->barang_id);
            $barang->increment('stok', $jumlahDiff);
        }

        $barangMasuk->update($validated);

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangMasuk $barangMasuk): RedirectResponse
    {
        $this->authorize('delete', $barangMasuk);

        if ($barangMasuk->void_status === 'pending') {
            return redirect()->route('barang-masuk.index')->withErrors([
                'void' => 'Data Pending Void hanya boleh diproses melalui persetujuan void.',
            ]);
        }

        // Decrease barang stok
        $barang = Barang::find($barangMasuk->barang_id);
        $barang->decrement('stok', $barangMasuk->jumlah);

        $barangMasuk->delete();

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil dihapus.');
    }

    /**
     * Karyawan request void untuk transaksi yang salah input.
     */
    public function requestVoid(Request $request, BarangMasuk $barangMasuk): RedirectResponse
    {
        $this->authorize('requestVoid', $barangMasuk);

        $validated = $request->validate([
            'void_reason' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $barangMasuk->update([
            'void_status' => 'pending',
            'void_reason' => $validated['void_reason'],
            'void_requested_by' => auth()->id(),
            'void_requested_at' => now(),
            'void_approved_by' => null,
            'void_approved_at' => null,
        ]);

        return redirect()->route('barang-masuk.index')->with('success', 'Request void berhasil dikirim ke owner.');
    }

    /**
     * Owner menyetujui void lalu transaksi dihapus (soft delete) dan stok dikoreksi.
     */
    public function approveVoid(BarangMasuk $barangMasuk): RedirectResponse
    {
        $this->authorize('approveVoid', $barangMasuk);

        DB::transaction(function () use ($barangMasuk) {
            $barang = Barang::find($barangMasuk->barang_id);
            $barang->decrement('stok', $barangMasuk->jumlah);

            $barangMasuk->update([
                'void_status' => 'approved',
                'void_approved_by' => auth()->id(),
                'void_approved_at' => now(),
            ]);

            $barangMasuk->delete();
        });

        return redirect()->route('barang-masuk.index')->with('success', 'Void disetujui. Data transaksi berhasil dihapus.');
    }
}
