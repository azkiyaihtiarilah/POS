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
            <button onclick="modalAction('{{ url('stok/import') }}')" class="btn btn-sm btn-info mt-1">Import Stok</button>
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('stok/create') }}">Tambah</a>
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
     <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data- backdrop="static"
         data-keyboard="false" data-width="75%" aria-hidden="true"></div>
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
        //  $(document).ready(function() {
        //      var dataBarang = $('#table_barang').DataTable({
        //          serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
        //          ajax: {
        //              "url": "{{ url('barang/list') }}",
        //              "dataType": "json",
        //              "type": "POST",
        //              "data": function (d) {
        //                  d.kategori_id = $('#kategori_id').val();
        //              }
        //          },
        //          columns: [
        //              {
        //                  data: "DT_RowIndex", // nomor urut dari laravel datatable addIndexColumn()
        //                  className: "text-center",
        //                  orderable: false,
        //                  searchable: false
        //              },
        //              {
        //                  data: "barang_kode",
        //                  className: "",
        //                  orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                  searchable: true // searchable: true, jika ingin kolom ini bisa dicari
        //              },
        //              {
        //                  data: "barang_nama",
        //                  className: "",
        //                  orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                  searchable: true // searchable: true, jika ingin kolom ini bisa dicari
        //              },
        //              {
        //                  data: "kategori.kategori_nama",
        //                  className: "",
        //                  orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                  searchable: true // searchable: true, jika ingin kolom ini bisa dicari
        //              },
        //              {
        //                  data: "harga_beli",
        //                  className: "",
        //                  orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                  searchable: true // searchable: true, jika ingin kolom ini bisa dicari
        //              },
        //              {
        //                  data: "harga_jual",
        //                  className: "",
        //                  orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                  searchable: true // searchable: true, jika ingin kolom ini bisa dicari
        //              },
        //              {
        //                  data: "aksi",
        //                  className: "",
        //                  orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                  searchable: false // searchable: true, jika ingin kolom ini bisa dicari
        //              }
        //          ]
        //      });
 
        //      $('#kategori_id').on('change', function() {
        //          dataBarang.ajax.reload();
        //      });
        //  });
     </script>
 @endpush 

