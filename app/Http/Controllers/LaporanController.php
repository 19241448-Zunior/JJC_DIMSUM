<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\CabangDistribusi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Display stock report
     */
    public function index(Request $request): View|StreamedResponse|Response
    {
        $validated = $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);

        $tanggalMulai = $validated['tanggal_mulai'] ?? null;
        $tanggalSelesai = $validated['tanggal_selesai'] ?? null;
        $export = $validated['export'] ?? null;

        $laporan = $this->buildLaporan($tanggalMulai, $tanggalSelesai);

        if ($export === 'excel') {
            return $this->exportExcel($laporan, $tanggalMulai, $tanggalSelesai);
        }

        if ($export === 'pdf') {
            $filename = 'laporan-stok-' . now()->format('Ymd_His') . '.pdf';
            
            $pdf = Pdf::loadView('laporan.pdf', [
                'laporan' => $laporan,
                'tanggalMulai' => $tanggalMulai,
                'tanggalSelesai' => $tanggalSelesai,
                'logoBase64' => $this->getLogoBase64(),
            ])->setPaper('a4', 'landscape')->setOption('defaultFont', 'Arial');

            return $pdf->download($filename);
        }

        return view('laporan.index', [
            'laporan' => $laporan,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ]);
    }

    private function getLogoBase64(): ?string
    {
        $logoPath = public_path('images/logo-login.png');

        if (!file_exists($logoPath)) {
            return null;
        }

        $logoContents = file_get_contents($logoPath);

        if ($logoContents === false) {
            return null;
        }

        return 'data:image/png;base64,' . base64_encode($logoContents);
    }

    /**
     * Build report data with optional date filters.
     */
    private function buildLaporan(?string $tanggalMulai, ?string $tanggalSelesai): Collection
    {
        $barang = Barang::orderBy('nama_barang')->get();

        return $barang->map(function ($item) use ($tanggalMulai, $tanggalSelesai) {
            $masukQuery = $item->barangMasuk();
            $keluarQuery = $item->barangKeluar();

            if ($tanggalMulai && $tanggalSelesai) {
                $masukQuery->whereBetween('tanggal_masuk', [$tanggalMulai, $tanggalSelesai]);
                $keluarQuery->whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalSelesai]);
            } elseif ($tanggalMulai) {
                $masukQuery->whereDate('tanggal_masuk', '>=', $tanggalMulai);
                $keluarQuery->whereDate('tanggal_keluar', '>=', $tanggalMulai);
            } elseif ($tanggalSelesai) {
                $masukQuery->whereDate('tanggal_masuk', '<=', $tanggalSelesai);
                $keluarQuery->whereDate('tanggal_keluar', '<=', $tanggalSelesai);
            }

            $barangMasuk = $masukQuery->sum('jumlah');
            $barangKeluar = $keluarQuery->sum('jumlah');

            $stokAwal = (int) $item->stok;
            $stokSaatIni = $stokAwal;
            $stokAkhir = max(0, $stokAwal + $barangMasuk - $barangKeluar);
            $balance = $stokAwal - $stokAkhir;

            $detailCabang = $this->buildDetailCabang($item->id, $tanggalMulai, $tanggalSelesai);

            return [
                'id' => $item->id,
                'kode_barang' => $item->kode_barang,
                'nama_barang' => $item->nama_barang,
                'stok_awal' => $stokAwal,
                'barang_masuk' => $barangMasuk,
                'barang_keluar' => $barangKeluar,
                'stok_akhir' => $stokAkhir,
                'stok_saat_ini' => $stokSaatIni,
                'balance' => $balance,
                'detail_cabang' => $detailCabang,
            ];
        });
    }

    private function buildDetailCabang(int $barangId, ?string $tanggalMulai, ?string $tanggalSelesai): string
    {
        $query = CabangDistribusi::with(['cabang', 'items'])
            ->whereHas('items', function ($itemQuery) use ($barangId) {
                $itemQuery->where('barang_id', $barangId);
            })
            ->latest();

        if ($tanggalMulai && $tanggalSelesai) {
            $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        } elseif ($tanggalMulai) {
            $query->whereDate('tanggal', '>=', $tanggalMulai);
        } elseif ($tanggalSelesai) {
            $query->whereDate('tanggal', '<=', $tanggalSelesai);
        }

        $lines = $query->get()->map(function (CabangDistribusi $record) use ($barangId) {
            $item = $record->items->firstWhere('barang_id', $barangId);

            if (!$item) {
                return null;
            }

            $kodeCabang = $record->cabang?->kode_cabang ?: ($record->cabang?->nama_cabang ?? '-');

            return trim(
                $kodeCabang .
                ': B' . (int) $item->jumlah_bawa .
                ' S' . (int) $item->jumlah_sisa .
                ' T' . (int) $item->jumlah_terpakai
            );
        })->filter()->values();

        return $lines->isEmpty() ? '-' : $lines->implode("\n");
    }

    /**
     * Export report to CSV (Excel compatible).
     */
    private function exportExcel(Collection $laporan, ?string $tanggalMulai, ?string $tanggalSelesai): StreamedResponse
    {
        $filename = 'laporan-stok-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($laporan, $tanggalMulai, $tanggalSelesai) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Laporan Stok Cikampek Jajanan']);
            fputcsv($handle, ['Periode', ($tanggalMulai ?: '-') . ' s/d ' . ($tanggalSelesai ?: '-')]);
            fputcsv($handle, []);
            fputcsv($handle, ['No', 'Kode Barang', 'Nama Barang', 'Stok Awal Periode', 'Barang Masuk', 'Barang Keluar', 'Stok Saat Ini', 'Stok Akhir', 'Balance', 'Detail Cabang']);

            foreach ($laporan as $index => $item) {
                fputcsv($handle, [
                    $index + 1,
                    $item['kode_barang'],
                    $item['nama_barang'],
                    $item['stok_awal'],
                    $item['barang_masuk'],
                    $item['barang_keluar'],
                    $item['stok_saat_ini'],
                    $item['stok_akhir'],
                    $item['balance'],
                    str_replace("\n", ' | ', $item['detail_cabang']),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
