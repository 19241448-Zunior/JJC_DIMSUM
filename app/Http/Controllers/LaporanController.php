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
        $summaryByCabang = $this->buildSummaryByCabang($tanggalMulai, $tanggalSelesai);
        $opnameRows = $this->buildOpnameRows($tanggalMulai, $tanggalSelesai);
        $transaksiRows = $this->buildTransaksiLaporan($tanggalMulai, $tanggalSelesai);

        if ($export === 'excel') {
            return $this->exportExcel($laporan, $tanggalMulai, $tanggalSelesai);
        }

        if ($export === 'pdf') {
            $filename = 'laporan-stok-' . now()->format('Ymd_His') . '.pdf';

            $totals = [
                'masuk' => $transaksiRows->where('jenis', 'Masuk')->sum('jumlah'),
                'keluar' => $transaksiRows->where('jenis', 'Keluar')->sum('jumlah'),
                'net' => $transaksiRows->where('jenis', 'Masuk')->sum('jumlah') - $transaksiRows->where('jenis', 'Keluar')->sum('jumlah'),
            ];
            
            $pdf = Pdf::loadView('laporan.pdf', [
                'laporan' => $laporan,
                'transaksiRows' => $transaksiRows,
                'summaryByCabang' => $summaryByCabang,
                'opnameRows' => $opnameRows,
                'totals' => $totals,
                'tanggalMulai' => $tanggalMulai,
                'tanggalSelesai' => $tanggalSelesai,
                'logoBase64' => $this->getLogoBase64(),
            ])->setPaper('a4', 'landscape')->setOption('defaultFont', 'Arial');

            return $pdf->download($filename);
        }

        return view('laporan.index', [
            'laporan' => $laporan,
            'summaryByCabang' => $summaryByCabang,
            'opnameRows' => $opnameRows,
            'transaksiRows' => $transaksiRows,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ]);
    }

    /**
     * Build cabang summary from stok opname harian / distribusi data.
     */
    private function buildSummaryByCabang(?string $tanggalMulai, ?string $tanggalSelesai): Collection
    {
        $records = $this->buildCabangDistribusiRecords($tanggalMulai, $tanggalSelesai);

        return $records
            ->groupBy(function (CabangDistribusi $record) {
                return $record->cabang?->nama_cabang ?? '-';
            })
            ->map(function (Collection $group) {
                $items = $group->flatMap(function (CabangDistribusi $record) {
                    return $record->items;
                });

                return [
                    'kode_cabang' => $group->first()?->cabang?->kode_cabang ?? '-',
                    'nama_cabang' => $group->first()?->cabang?->nama_cabang ?? '-',
                    'total_transaksi' => $group->count(),
                    'total_bawa' => (int) $items->sum('jumlah_bawa'),
                    'total_sisa' => (int) $items->sum('jumlah_sisa'),
                    'total_terpakai' => (int) $items->sum('jumlah_terpakai'),
                ];
            })
            ->sortBy('nama_cabang')
            ->values();
    }

    /**
     * Build daily opname activity rows for report and PDF.
     */
    private function buildOpnameRows(?string $tanggalMulai, ?string $tanggalSelesai): Collection
    {
        $records = $this->buildCabangDistribusiRecords($tanggalMulai, $tanggalSelesai);

        return $records->map(function (CabangDistribusi $record) {
            $items = $record->items;

            return [
                'tanggal' => $record->tanggal,
                'waktu_input' => $record->created_at,
                'nama_cabang' => $record->cabang?->nama_cabang ?? '-',
                'kode_cabang' => $record->cabang?->kode_cabang ?? '-',
                'nama_penginput' => $record->user?->name ?? '-',
                'total_bawa' => (int) $items->sum('jumlah_bawa'),
                'total_sisa' => (int) $items->sum('jumlah_sisa'),
                'total_terpakai' => (int) $items->sum('jumlah_terpakai'),
                'jumlah_item' => $items->count(),
            ];
        })->sortByDesc(function (array $row) {
            return $row['waktu_input'];
        })->values();
    }

    /**
     * Shared CabangDistribusi query builder.
     */
    private function buildCabangDistribusiRecords(?string $tanggalMulai, ?string $tanggalSelesai): Collection
    {
        $query = CabangDistribusi::with(['cabang', 'user', 'items'])
            ->latest();

        if ($tanggalMulai && $tanggalSelesai) {
            $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        } elseif ($tanggalMulai) {
            $query->whereDate('tanggal', '>=', $tanggalMulai);
        } elseif ($tanggalSelesai) {
            $query->whereDate('tanggal', '<=', $tanggalSelesai);
        }

        return $query->get();
    }

    /**
     * Build detailed transaction rows for PDF output.
     */
    private function buildTransaksiLaporan(?string $tanggalMulai, ?string $tanggalSelesai): Collection
    {
        $masukQuery = BarangMasuk::with('barang', 'user')
            ->whereNull('deleted_at');

        $keluarQuery = BarangKeluar::with('barang', 'user')
            ->whereNull('deleted_at');

        if ($tanggalMulai && $tanggalSelesai) {
            $masukQuery = $masukQuery->whereBetween('tanggal_masuk', [$tanggalMulai, $tanggalSelesai]);
            $keluarQuery = $keluarQuery->whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalSelesai]);
        } elseif ($tanggalMulai) {
            $masukQuery = $masukQuery->whereDate('tanggal_masuk', '>=', $tanggalMulai);
            $keluarQuery = $keluarQuery->whereDate('tanggal_keluar', '>=', $tanggalMulai);
        } elseif ($tanggalSelesai) {
            $masukQuery = $masukQuery->whereDate('tanggal_masuk', '<=', $tanggalSelesai);
            $keluarQuery = $keluarQuery->whereDate('tanggal_keluar', '<=', $tanggalSelesai);
        }

        $masukRows = $masukQuery->get()->map(function (BarangMasuk $item) {
            return [
                'jenis' => 'Masuk',
                'nama_barang' => $item->barang?->nama_barang ?? '-',
                'jumlah' => $item->jumlah,
                'nama_penginput' => $item->user?->name ?? '-',
                'waktu_input' => $item->created_at,
            ];
        });

        $keluarRows = $keluarQuery->get()->map(function (BarangKeluar $item) {
            return [
                'jenis' => 'Keluar',
                'nama_barang' => $item->barang?->nama_barang ?? '-',
                'jumlah' => $item->jumlah,
                'nama_penginput' => $item->user?->name ?? '-',
                'waktu_input' => $item->created_at,
            ];
        });

        return $masukRows
            ->merge($keluarRows)
            ->sortByDesc(function (array $row) {
                return $row['waktu_input'];
            })
            ->values();
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

            $stokAwal = 0;
            $stokAkhir = $item->stok;
            $stokSaatIni = $item->stok;

            if ($tanggalMulai) {
                $masukSebelum = $item->barangMasuk()->whereDate('tanggal_masuk', '<', $tanggalMulai)->sum('jumlah');
                $keluarSebelum = $item->barangKeluar()->whereDate('tanggal_keluar', '<', $tanggalMulai)->sum('jumlah');
                $stokAwal = $masukSebelum - $keluarSebelum;
                $stokAkhir = $stokAwal + $barangMasuk - $barangKeluar;
            }

            $balance = $stokSaatIni - $stokAkhir;

            return [
                'id' => $item->id,
                'nama_barang' => $item->nama_barang,
                'stok_awal' => $stokAwal,
                'barang_masuk' => $barangMasuk,
                'barang_keluar' => $barangKeluar,
                'stok_akhir' => $stokAkhir,
                'stok_saat_ini' => $stokSaatIni,
                'balance' => $balance,
            ];
        });
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
            fputcsv($handle, ['No', 'Nama Barang', 'Stok Awal', 'Barang Masuk', 'Barang Keluar', 'Stok Saat Ini', 'Stok Akhir', 'Balance']);

            foreach ($laporan as $index => $item) {
                fputcsv($handle, [
                    $index + 1,
                    $item['nama_barang'],
                    $item['stok_awal'],
                    $item['barang_masuk'],
                    $item['barang_keluar'],
                    $item['stok_saat_ini'],
                    $item['stok_akhir'],
                    $item['balance'],
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
