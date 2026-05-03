@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Barang</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('barang.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" 
                                   id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" required>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="satuan" class="form-label">Satuan</label>
                                <select id="satuan" name="satuan" class="form-select @error('satuan') is-invalid @enderror">
                                    <option value="" {{ old('satuan') == '' ? 'selected' : '' }}>Auto (deteksi dari nama)</option>
                                    <option value="pack" {{ old('satuan') == 'pack' ? 'selected' : '' }}>pack</option>
                                    <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>pcs</option>
                                </select>
                                @error('satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stok_min" class="form-label">Stok Minimal</label>
                                <input type="number" id="stok_min" name="stok_min" min="0" class="form-control @error('stok_min') is-invalid @enderror" value="{{ old('stok_min', 5) }}">
                                @error('stok_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Default: 5</div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            Stok awal otomatis diset ke <strong>0</strong>. Anda cukup isi nama barang.
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
