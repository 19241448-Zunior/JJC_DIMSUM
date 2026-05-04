<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Cabang;
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
        $stokRealTotal = (int) ($laporan->last()['stok_real_saat_ini'] ?? 0);

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
        $distribusiQuery = CabangDistribusi::query();
        $barangMasukQuery = BarangMasuk::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $distribusiQuery->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            $barangMasukQuery->whereBetween('tanggal_masuk', [$tanggalMulai, $tanggalSelesai]);
        } elseif ($tanggalMulai) {
            $distribusiQuery->whereDate('tanggal', '>=', $tanggalMulai);
            $barangMasukQuery->whereDate('tanggal_masuk', '>=', $tanggalMulai);
        } elseif ($tanggalSelesai) {
            $distribusiQuery->whereDate('tanggal', '<=', $tanggalSelesai);
            $barangMasukQuery->whereDate('tanggal_masuk', '<=', $tanggalSelesai);
        }

        $distribusiDates = $distribusiQuery->pluck('tanggal')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'));
        $barangMasukDates = $barangMasukQuery->pluck('tanggal_masuk')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'));

        $allDates = $distribusiDates
            ->merge($barangMasukDates)
            ->unique()
            ->sort()
            ->values();

        $stokBerjalan = 0;

        return $allDates->map(function (string $tanggal) use (&$stokBerjalan) {
            $dailyRecords = CabangDistribusi::with(['cabang', 'items.barang'])
                ->whereDate('tanggal', $tanggal)
                ->orderBy('cabang_id', 'asc')
                ->get();

            $cabangHeaders = $this->buildCabangHeaders($dailyRecords);

            $barangMasukData = BarangMasuk::with('barang')
                ->whereDate('tanggal_masuk', $tanggal)
                ->get();

            $dailyItems = $dailyRecords->flatMap(fn (CabangDistribusi $record) => $record->items);
            $barangIds = $dailyItems->pluck('barang_id')
                ->merge($barangMasukData->pluck('barang_id'))
                ->filter()
                ->unique()
                ->sort()
                ->values();

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

            $detailBarang = $barangIds->map(function ($barangId) use ($dailyRecords, $barangMasukData, $cabangHeaders, $tanggal) {
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

                $perCabangMap = collect($cabangHeaders)->mapWithKeys(function (array $cabang) use ($perCabang) {
                    $cabangData = $perCabang->firstWhere('cabang_id', $cabang['cabang_id']);

                    return [
                        (string) $cabang['cabang_id'] => [
                            'cabang_id' => $cabang['cabang_id'],
                            'nama_cabang' => $cabang['nama_cabang'],
                            'kode_cabang' => $cabang['kode_cabang'],
                            'jumlah_bawa' => (int) ($cabangData['jumlah_bawa'] ?? 0),
                            'jumlah_sisa' => (int) ($cabangData['jumlah_sisa'] ?? 0),
                            'jumlah_terpakai' => (int) ($cabangData['jumlah_terpakai'] ?? 0),
                        ],
                    ];
                });

                // Only count manual restock as "barang masuk (belanja)" per new algorithm
                $barangMasuk = (int) $barangMasukData
                    ->where('barang_id', $barangId)
                    ->where('sumber', 'manual')
                    ->sum('jumlah');

                // compute historical stock up to this date (include all masuk sources)
                $masukUpTo = (int) \App\Models\BarangMasuk::where('barang_id', $barangId)
                    ->whereDate('tanggal_masuk', '<=', $tanggal)
                    ->sum('jumlah');

                $keluarUpTo = (int) \App\Models\BarangKeluar::where('barang_id', $barangId)
                    ->whereDate('tanggal_keluar', '<=', $tanggal)
                    ->sum('jumlah');

                $stokRealAtDate = max(0, $masukUpTo - $keluarUpTo);

                // include barang rows when there is activity or there is a real stock at date
                if ($perCabang->isEmpty() && $barangMasuk === 0 && $stokRealAtDate === 0) {
                    return null;
                }

                return [
                    'barang_id' => $barangId,
                    'kode_barang' => $barang->kode_barang,
                    'nama_barang' => $barang->nama_barang,
                    'total_bawa' => (int) $perCabang->sum('jumlah_bawa'),
                    'total_sisa' => (int) $perCabang->sum('jumlah_sisa'),
                    'total_terpakai' => (int) $perCabang->sum('jumlah_terpakai'),
                    'barang_masuk' => $barangMasuk,
                    // stok real at the report date (cumulative masuk - keluar)
                    'stok_real' => $stokRealAtDate,
                    'per_cabang' => $perCabang,
                    'per_cabang_map' => $perCabangMap,
                ];
            })->filter()->values();

            $totalBarangKeluar = (int) $dailyItems->sum('jumlah_bawa');
            $totalBarangKembali = (int) $dailyItems->sum('jumlah_sisa');
            // Terpakai = keluar - kembali
            $totalBarangTerpakai = (int) max(0, $totalBarangKeluar - $totalBarangKembali);
            // Total barang masuk (belanja) should count only manual restock entries
            $totalBarangMasuk = (int) $barangMasukData->where('sumber', 'manual')->sum('jumlah');

            // Saldo berjalan dihitung dari stok masuk manual dikurangi terpakai secara kronologis.
            $stokBerjalan += $totalBarangMasuk - $totalBarangTerpakai;
            $saldoHarian = $stokBerjalan;
            $stokRealSaatIni = $stokBerjalan;

            return [
                'tanggal' => Carbon::parse($tanggal)->format('d M Y'),
                'tanggal_raw' => $tanggal,
                'total_cabang' => $dailyRecords->count(),
                'total_barang_keluar' => $totalBarangKeluar,
                'total_barang_kembali' => $totalBarangKembali,
                'total_barang_terpakai' => $totalBarangTerpakai,
                'total_barang_masuk' => $totalBarangMasuk,
                'stok_real_saat_ini' => $stokRealSaatIni,
                'saldo_harian' => $saldoHarian,
                'detail_cabang' => $detailCabang ?: '-',
                'detail_barang' => $detailBarang,
                'cabang_headers' => $cabangHeaders,
            ];
        })->values();
    }

    /**
     * Build the cabang header list used by report PDF so columns stay consistent.
     */
    private function buildCabangHeaders(?Collection $dailyRecords = null): array
    {
        $preferredOrder = [
            'Cab 1 pawarengan',
            'Cab 2 regency',
            'Cab 3 Angkringan sukaseri',
            'Cab 4 Angkringan pawarengan',
            'Cab 5 Stand HK Kamojing',
            'Cab 6 Cikopak purwakarta',
            'Cab 7 Munjul purwakarta',
            'Cab 8 Telor gulung niceso senopati',
            'Cab 9 O!save sukaseri',
            'Cab 10 Maracang purwakarta',
        ];

        $cabangs = $dailyRecords
            ? $dailyRecords->map(fn (CabangDistribusi $record) => $record->cabang)->filter()
            : Cabang::where('aktif', true)->get();

        $orderedCabangs = collect($preferredOrder)->map(function (string $preferredName) use ($cabangs) {
            $found = $cabangs->first(function (Cabang $cabang) use ($preferredName) {
                return strcasecmp(trim($cabang->nama_cabang), trim($preferredName)) === 0;
            });

            return $found;
        })->filter();

        $otherCabangs = $cabangs->reject(function (Cabang $cabang) use ($preferredOrder) {
            foreach ($preferredOrder as $preferredName) {
                if (strcasecmp(trim($cabang->nama_cabang), trim($preferredName)) === 0) {
                    return true;
                }
            }

            return false;
        })->values();

        return $orderedCabangs
            ->concat($otherCabangs)
            ->values()
            ->map(function (Cabang $cabang) {
                return [
                    'cabang_id' => $cabang->id,
                    'kode_cabang' => $cabang->kode_cabang ?: '-',
                    'nama_cabang' => $cabang->nama_cabang ?: '-',
                ];
            })
            ->all();
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
