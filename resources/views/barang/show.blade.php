@extends('layouts.app')

@section('title', 'Detail Barang')
@section('page-title', 'Detail Barang')

@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $barang->nama_barang }} <small class="text-muted">({{ $barang->kode_barang }})</small></h3>
                    <a href="{{ route('barang.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Satuan:</strong> {{ $barang->satuan ?? '-' }} — <strong>Stok Minimal:</strong> {{ $barang->stok_min ?? 5 }} — <strong>Status:</strong> {{ $barang->status ?? ($barang->stok >= ($barang->stok_min ?? 5) ? 'normal' : 'low') }}
                    </div>

                    <div class="mb-3">
                        <p><strong>Total Masuk:</strong> {{ $totalMasuk }}</p>
                        <p><strong>Total Keluar:</strong> {{ $totalKeluar }}</p>
                        <p><strong>Stok Opname (fisik):</strong> {{ $stokOpname }}</p>
                        <p><strong>Stok Sistem:</strong> {{ $barang->stok }}</p>
                    </div>

                    <h5>Per Cabang</h5>
                    @if($perCabang->isEmpty())
                        <div class="text-muted">Tidak ada distribusi atau aktivitas per cabang untuk barang ini.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Cabang</th>
                                        <th>Bawa</th>
                                        <th>Sisa</th>
                                        <th>Terpakai</th>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($perCabang as $pc)
                                        <tr>
                                            <td>{{ $pc['nama_cabang'] }}</td>
                                            <td>{{ $pc['jumlah_bawa'] }}</td>
                                            <td>{{ $pc['jumlah_sisa'] }}</td>
                                            <td>{{ $pc['jumlah_terpakai'] }}</td>
                                            <td>{{ $pc['masuk'] }}</td>
                                            <td>{{ $pc['keluar'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
