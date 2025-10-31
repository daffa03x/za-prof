@extends('component.layout.app')
<style>
    th,
    td {
        white-space: nowrap
    }
</style>
@section('content')
    <div class="container mt-4">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Menampilkan Notifikasi Sukses -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Menampilkan Notifikasi Error -->
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Menampilkan Pesan Validasi Error -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="py-4 d-flex justify-content-between align-items-center">
                    <div>
                        {{-- <a href="{{ route('transaksi.create') }}" class="btn btn-success">Add</a> --}}
                        <button type="button" class="btn btn-info" data-toggle="modal"
                            data-target="#exportTransaksi">Export</button>
                        <button type="button" class="btn btn-warning" data-toggle="modal"
                            data-target="#filterTransaksi">Filter</a>
                    </div>

                    <form class="d-flex ml-2" method="GET" action="{{ route('transaksi.search') }}">
                        <input class="form-control mr-2" type="search" id="search" name="search" placeholder="Search"
                            aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>

                <!-- Modal export campaign -->
                @include('component.modal.exportTransaksi')

                <!-- Modal filter campaign -->
                @include('component.modal.filterTransaksi')

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                {{-- <th>Id</th> --}}
                                <th>Invoice</th>
                                <th>Event</th>
                                <th>Nama Pembeli</th>
                                <th>Email Pembeli</th>
                                <th>Telepon Pembeli</th>
                                <th>Volunteer</th>
                                <th>Jumlah Tiket</th>
                                <th>Total Pembayaran</th>
                                <th>Tanggal Register</th>
                                <th>Status Pembayaran</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Metode Pembayaran</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    {{-- <td>{{ $item->id }}</td> --}}
                                    <td>{{ $item->invoice }}</td>
                                    <td>{{ $item->name_event }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->telepon }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($item->volunteers as $volunteer)
                                                <li>
                                                    {{ $volunteer->name }} - {{ $volunteer->telepon }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $item->jumlah_tiket }}</td>
                                    <td>@rupiah($item->total_pembayaran)</td>
                                    <td>{{ $item->tanggal_register }}</td>
                                    <td id="status-{{ $item->id }}">
                                        @if ($item->status_pembayaran === 'Success')
                                            <button class="btn btn-sm btn-success m-1">Y</button>
                                        @elseif($item->status_pembayaran === 'Failed')
                                            <button onclick="updateStatus('{{ $item->id }}');"
                                                class="btn btn-sm btn-danger m-1">N</button>
                                        @else
                                            <button onclick="updateStatus('{{ $item->id }}');"
                                                class="btn btn-sm btn-warning m-1">P</button>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($item->tanggal_pembayaran === null)
                                            Belum Bayar
                                        @else
                                            {{ $item->tanggal_pembayaran }}
                                        @endif
                                    </td>
                                    <td>{{ $item->name_payment }}</td>
                                    <td>
                                        <a href="{{ route('transaksi.show', $item->invoice) }}"
                                            class="btn btn-sm btn-info m-1 text-white">Lihat</a>
                                        {{-- <a href="{{ route('transaksi.edit', $item->invoice) }}"
                                            class="btn btn-sm btn-warning m-1">Edit</a> --}}
                                        <form action="{{ route('transaksi.destroy', $item->invoice) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger m-1">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    {!! $data->appends(request()->query())->links('pagination::bootstrap-4') !!}
                </div>

            </div>
        </div>
    </div>

    <script>
        async function updateStatus(id) {
            const confirmation = confirm("Apakah Anda yakin ingin memperbarui status transaksi ini?");
            if (!confirmation) {
                console.log("Pembaruan status dibatalkan oleh pengguna.");
                return;
            }

            try {
                // Kirim permintaan untuk memperbarui status transaksi
                const response = await fetch('/transaksi/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: id
                    })
                });

                const result = await response.json();
                console.info('Response:', result);

                // Menentukan elemen tombol berdasarkan ID
                const statusElement = document.querySelector(`#status-${id}`);

                // Mengupdate status tombol menjadi "Y" setelah update
                if (statusElement && result.message === 'Status berhasil diperbarui!') {
                    statusElement.innerHTML = `<button class="btn btn-sm btn-success m-1">Y</button>`;
                }

                alert(result.message);
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        }
    </script>
@endsection
