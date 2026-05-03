<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBarang = Barang::count();
        $totalMasuk = BarangMasuk::where('source', 'manual')->sum('jumlah');
        $totalKeluar = BarangKeluar::sum('jumlah');
        $totalStok = Barang::sum('stok');

        // Get data for chart (last 7 days)
        $chartData = $this->getChartData();
        $recentActivities = $this->getRecentActivities();
        
        // Get low stock notifications
        $lowStockItems = Barang::getLowStockNotifications();

        return view('dashboard.index', compact(
            'totalBarang',
            'totalMasuk',
            'totalKeluar',
            'totalStok',
            'chartData',
            'recentActivities',
            'lowStockItems'
        ));
    }

    /**
     * Search barang by nama or kode and return per-cabang breakdown
     */
    public function search(Request $request): View
    {
        $query = trim($request->query('q', ''));

        $results = collect();
        $matchedCount = 0;

        if ($query !== '') {
            $barangs = Barang::where('nama_barang', 'like', "%{$query}%")
                ->orWhere('kode_barang', 'like', "%{$query}%")
                ->get();
            $matchedCount = $barangs->count();

            foreach ($barangs as $barang) {
                $perCabang = $this->getPerCabangBreakdown($barang->id);

                $results->push([
                    'barang' => $barang,
                    'total_masuk' => (int) BarangMasuk::where('barang_id', $barang->id)->sum('jumlah'),
                    'total_keluar' => (int) BarangKeluar::where('barang_id', $barang->id)->sum('jumlah'),
                    'stok_opname' => (int) \App\Models\StokOpname::where('barang_id', $barang->id)->sum('jumlah_fisik'),
                    'per_cabang' => $perCabang,
                ]);
            }
        }

        return $this->renderSearchResults($query, $results, $matchedCount);
    }

    private function renderSearchResults(string $query, $results, int $matchedCount): View
    {
        $totalBarang = Barang::count();
        $totalMasuk = BarangMasuk::where('source', 'manual')->sum('jumlah');
        $totalKeluar = BarangKeluar::sum('jumlah');
        $totalStok = Barang::sum('stok');
        $chartData = $this->getChartData();
        $recentActivities = $this->getRecentActivities();
        $lowStockItems = Barang::getLowStockNotifications();

        return view('dashboard.search', compact(
            'totalBarang',
            'totalMasuk',
            'totalKeluar',
            'totalStok',
            'chartData',
            'recentActivities',
            'lowStockItems',
            'query',
            'results',
            'matchedCount'
        ));
    }

    private function getPerCabangBreakdown(int $barangId)
    {
        $cabangs = \App\Models\Cabang::all();

        return $cabangs->map(function ($cabang) use ($barangId) {
            $bawa = \App\Models\CabangDistribusiItem::where('barang_id', $barangId)
                ->whereHas('distribusi', function ($q) use ($cabang) {
                    $q->where('cabang_id', $cabang->id);
                })->sum('jumlah_bawa');

            $sisa = \App\Models\CabangDistribusiItem::where('barang_id', $barangId)
                ->whereHas('distribusi', function ($q) use ($cabang) {
                    $q->where('cabang_id', $cabang->id);
                })->sum('jumlah_sisa');

            $terpakai = \App\Models\CabangDistribusiItem::where('barang_id', $barangId)
                ->whereHas('distribusi', function ($q) use ($cabang) {
                    $q->where('cabang_id', $cabang->id);
                })->sum('jumlah_terpakai');

            $masukCabang = BarangMasuk::where('barang_id', $barangId)->where('cabang_id', $cabang->id)->sum('jumlah');
            $keluarCabang = BarangKeluar::where('barang_id', $barangId)->where('cabang_id', $cabang->id)->sum('jumlah');

            return [
                'cabang_id' => $cabang->id,
                'nama_cabang' => $cabang->nama_cabang,
                'jumlah_bawa' => (int) $bawa,
                'jumlah_sisa' => (int) $sisa,
                'jumlah_terpakai' => (int) $terpakai,
                'masuk' => (int) $masukCabang,
                'keluar' => (int) $keluarCabang,
            ];
        })->filter(function ($row) {
            return ($row['jumlah_bawa'] + $row['jumlah_sisa'] + $row['jumlah_terpakai'] + $row['masuk'] + $row['keluar']) > 0;
        })->values();
    }

    private function getChartData()
    {
        $days = 7;
        $labels = [];
        $masukData = [];
        $keluarData = [];
        $tanggalMasukColumn = $this->getTanggalMasukColumn();
        $tanggalKeluarColumn = $this->getTanggalKeluarColumn();

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');

            $masuk = BarangMasuk::where('source', 'manual')->whereDate($tanggalMasukColumn, $date)->sum('jumlah');
            $keluar = BarangKeluar::whereDate($tanggalKeluarColumn, $date)->sum('jumlah');

            $masukData[] = $masuk;
            $keluarData[] = $keluar;
        }

        return [
            'labels' => $labels,
            'masukData' => $masukData,
            'keluarData' => $keluarData,
        ];
    }

    private function getRecentActivities()
    {
        $masuk = DB::table('barang_masuk')
            ->join('users', 'users.id', '=', 'barang_masuk.user_id')
            ->join('barang', 'barang.id', '=', 'barang_masuk.barang_id')
            ->where('users.role', 'karyawan')
            ->whereNull('barang_masuk.deleted_at')
            ->selectRaw("'masuk' as tipe, barang_masuk.source as source, users.name as penginput, barang.nama_barang, barang_masuk.jumlah, barang_masuk.created_at");

        return DB::table('barang_keluar')
            ->join('users', 'users.id', '=', 'barang_keluar.user_id')
            ->join('barang', 'barang.id', '=', 'barang_keluar.barang_id')
            ->where('users.role', 'karyawan')
            ->whereNull('barang_keluar.deleted_at')
                ->selectRaw("'keluar' as tipe, null as source, users.name as penginput, barang.nama_barang, barang_keluar.jumlah, barang_keluar.created_at")
            ->unionAll($masuk)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
    }

    private function getTanggalMasukColumn(): string
    {
        return Schema::hasColumn('barang_masuk', 'tanggal_masuk') ? 'tanggal_masuk' : 'tanggal';
    }

    private function getTanggalKeluarColumn(): string
    {
        return Schema::hasColumn('barang_keluar', 'tanggal_keluar') ? 'tanggal_keluar' : 'tanggal';
    }
}
