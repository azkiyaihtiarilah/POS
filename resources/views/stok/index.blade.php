@extends('layouts.template')

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Sukses!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row mb-3">
    <div class="col-md-12">
        <form method="GET" action="{{ url('stok') }}" class="form-inline">
            <label class="mr-2">Filter Kategori:</label>
            <select name="kategori_id" class="form-control mr-2" onchange="this.form.submit()">
                <option value="">- Semua -</option>
                @foreach($kategori as $item)
                    <option value="{{ $item->kategori_id }}" {{ request('kategori_id') == $item->kategori_id ? 'selected' : '' }}>
                        {{ $item->kategori_nama }}
                    </option>
                @endforeach
            </select>
            <noscript><button type="submit" class="btn btn-primary">Filter</button></noscript>
        </form>
    </div>
</div>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <!-- Tombol Import Stok -->
            <button onclick="modalAction('{{ url('stok/import') }}')" class="btn btn-sm btn-info mt-1"><i class="fa fa-file-excel"></i>Import Stok</button>
            <!-- Tombol Export PDF -->
            <a href="{{ url('stok/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export Stok</a>
            <!-- Tombol Export Excel -->
            <a href="{{ url('stok/export-excel') }}" class="btn btn-sm btn-success mt-1"><i class="fa fa-file-excel"></i> Export Stok</a>
            <!-- Tombol Tambah Ajax -->
            <button onclick="modalAction('{{ url('stok/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover table-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stok as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->stok_tanggal)->format('d-m-Y H:i') }}</td>
                        <td>{{ $item->barang->barang_nama ?? '-' }}</td>
                        <td>{{ $item->stok_jumlah }}</td>
                        <td>
                            <a href="{{ url('stok/' . $item->stok_id) }}" class="btn btn-info btn-sm">Detail</a>
                            <a href="{{ url('stok/' . $item->stok_id . '/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                            <!-- Tombol Hapus (commented out) -->
                            <!-- <form action="{{ url('stok/' . $item->stok_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form> -->
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Data tidak tersedia</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for AJAX Actions -->
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

@endsection

@push('css')
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
</script>
@endpush
