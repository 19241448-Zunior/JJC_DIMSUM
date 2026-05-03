<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\CabangDistribusi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
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
        $stokRealTotal = (int) Barang::sum('stok');

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
                'stokRealTotal' => $stokRealTotal,
            ])->setPaper('a4', 'landscape')->setOption('defaultFont', 'Arial');

            return $pdf->download($filename);
        }

        return view('laporan.index', [
            'laporan' => $laporan,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'stokRealTotal' => $stokRealTotal,
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
     * Build daily report data with optional date filters.
     */
    private function buildLaporan(?string $tanggalMulai, ?string $tanggalSelesai): Collection
    {
        $query = CabangDistribusi::with(['cabang', 'items.barang'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('cabang_id', 'asc');

        if ($tanggalMulai && $tanggalSelesai) {
            $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        } elseif ($tanggalMulai) {
            $query->whereDate('tanggal', '>=', $tanggalMulai);
        } elseif ($tanggalSelesai) {
            $query->whereDate('tanggal', '<=', $tanggalSelesai);
        }

        return $query->get()
            ->groupBy(function (CabangDistribusi $record) {
                return $record->tanggal?->format('Y-m-d') ?? '-';
            })
            ->map(function (Collection $dailyRecords, string $tanggal) {
                $dailyItems = $dailyRecords->flatMap(fn (CabangDistribusi $record) => $record->items);
                $barangIds = $dailyItems->pluck('barang_id')->filter()->unique()->sort()->values();

                $detailCabang = $dailyRecords->map(function (CabangDistribusi $record) {
                    $items = $record->items;

                    $totalBawa = (int) $items->sum('jumlah_bawa');
                    $totalSisa = (int) $items->sum('jumlah_sisa');
                    $totalTerpakai = (int) $items->sum('jumlah_terpakai');
                    $kodeCabang = $record->cabang?->kode_cabang ?: ($record->cabang?->nama_cabang ?? '-');

                    return trim(
                        $kodeCabang .
                        ': keluar ' . $totalBawa .
                        ', kembali ' . $totalSisa .
                        ', terpakai ' . $totalTerpakai
                    );
                })->filter()->implode("\n");

                // Build detail per barang dengan breakdown per cabang
                $detailBarang = $barangIds->map(function ($barangId) use ($dailyRecords, $tanggal) {
                    $barang = Barang::find($barangId);
                    if (!$barang) {
                        return null;
                    }

                    $perCabang = $dailyRecords->map(function (CabangDistribusi $record) use ($barangId) {
                        $item = $record->items->firstWhere('barang_id', $barangId);
                        if (!$item) {
                            return null;
                        }

                        return [
                            'cabang_id' => $record->cabang_id,
                            'nama_cabang' => $record->cabang?->nama_cabang ?? '-',
                            'kode_cabang' => $record->cabang?->kode_cabang ?? '-',
                            'jumlah_bawa' => (int) $item->jumlah_bawa,
                            'jumlah_sisa' => (int) $item->jumlah_sisa,
                            'jumlah_terpakai' => (int) $item->jumlah_terpakai,
                        ];
                    })->filter()->values();

                    if ($perCabang->isEmpty()) {
                        return null;
                    }

                    $barangMasuk = BarangMasuk::where('barang_id', $barangId)
                        ->whereDate('tanggal_masuk', $tanggal)
                        ->sum('jumlah');

                    return [
                        'barang_id' => $barangId,
                        'kode_barang' => $barang->kode_barang,
                        'nama_barang' => $barang->nama_barang,
                        'total_bawa' => (int) $perCabang->sum('jumlah_bawa'),
                        'total_sisa' => (int) $perCabang->sum('jumlah_sisa'),
                        'total_terpakai' => (int) $perCabang->sum('jumlah_terpakai'),
                        'barang_masuk' => (int) $barangMasuk,
                        'per_cabang' => $perCabang,
                    ];
                })->filter()->values();

                $totalBarangKeluar = (int) $dailyItems->sum('jumlah_bawa');
                $totalBarangKembali = (int) $dailyItems->sum('jumlah_sisa');
                $totalBarangTerpakai = (int) $dailyItems->sum('jumlah_terpakai');
                $totalBarangMasuk = (int) BarangMasuk::whereDate('tanggal_masuk', $tanggal)->sum('jumlah');
                $stokRealSaatIni = $barangIds->isEmpty()
                    ? 0
                    : (int) Barang::whereIn('id', $barangIds)->sum('stok');

                return [
                    'tanggal' => Carbon::parse($tanggal)->format('d M Y'),
                    'tanggal_raw' => $tanggal,
                    'total_cabang' => $dailyRecords->count(),
                    'total_barang_keluar' => $totalBarangKeluar,
                    'total_barang_kembali' => $totalBarangKembali,
                    'total_barang_terpakai' => $totalBarangTerpakai,
                    'total_barang_masuk' => $totalBarangMasuk,
                    'stok_real_saat_ini' => $stokRealSaatIni,
                    'saldo_harian' => $totalBarangMasuk + $totalBarangKembali - $totalBarangTerpakai,
                    'detail_cabang' => $detailCabang ?: '-',
                    'detail_barang' => $detailBarang,
                ];
            })
            ->values();
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
            fputcsv($handle, ['No', 'Tanggal', 'Total Cabang', 'Keluar/Bawa', 'Kembali/Sisa', 'Terpakai', 'Barang Masuk', 'Saldo Harian', 'Stok Real Saat Ini', 'Detail Cabang']);

            foreach ($laporan as $index => $item) {
                fputcsv($handle, [
                    $index + 1,
                    $item['tanggal'],
                    $item['total_cabang'],
                    $item['total_barang_keluar'],
                    $item['total_barang_kembali'],
                    $item['total_barang_terpakai'],
                    $item['total_barang_masuk'],
                    $item['saldo_harian'],
                    $item['stok_real_saat_ini'],
                    str_replace("\n", ' | ', $item['detail_cabang']),
                ]);

                // Add detail barang rows
                if (!empty($item['detail_barang'])) {
                    foreach ($item['detail_barang'] as $barang) {
                        fputcsv($handle, [
                            '',
                            '  ' . $barang['kode_barang'] . ' - ' . $barang['nama_barang'],
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                        ]);

                        if (!empty($barang['per_cabang'])) {
                            foreach ($barang['per_cabang'] as $cabang) {
                                fputcsv($handle, [
                                    '',
                                    '    ' . $cabang['kode_cabang'],
                                    '',
                                    $cabang['jumlah_bawa'],
                                    $cabang['jumlah_sisa'],
                                    $cabang['jumlah_terpakai'],
                                    '',
                                    '',
                                    '',
                                    '',
                                ]);
                            }
                        }

                        fputcsv($handle, [
                            '',
                            '    Total Barang',
                            '',
                            $barang['total_bawa'],
                            $barang['total_sisa'],
                            $barang['total_terpakai'],
                            $barang['barang_masuk'],
                            '',
                            '',
                            '',
                        ]);
                    }

                    fputcsv($handle, []);
                }
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
