@extends('components.layout.app')
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
                        <!-- <button type="button" class="btn btn-info me-1 text-white" data-toggle="modal"
                                            data-target="#exportTransaksi">Export</button>
                                        <button type="button" class="btn btn-warning me-1 text-white" data-toggle="modal"
                                            data-target="#filterTransaksi">Filter</button> -->
                        <a href="{{ route('transaksi.trashed') }}" class="btn btn-danger">
                            Terhapus
                        </a>
                    </div>

                    <form class="d-flex ml-2" method="GET" action="{{ route('transaksi.search') }}">
                        <input class="form-control mr-2" type="search" id="search" name="search" placeholder="Search"
                            aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>

                <!-- Modal export campaign -->
                @include('components.modal.exportTransaksi')

                <!-- Modal filter campaign -->
                @include('components.modal.filterTransaksi')

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
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
                                <th>Kode Voucher</th>
                                <th>Potongan Voucher (Pertiket)</th>
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
                                    <td>{{ $item->event->name }}</td>
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
                                    <td>{{ $item->voucher->kode ?? '-' }}</td>
                                    <td>
                                        @isset($item->voucher)
                                            @rupiah($item->voucher->nilai_diskon)
                                        @else
                                            -
                                        @endisset
                                    </td>
                                    <td>@rupiah($item->total_pembayaran)</td>
                                    <td>{{ $item->tanggal_register }}</td>
                                    <td id="status-{{ $item->id }}">
                                        <select class="form-control form-control-sm {{ $item->status_pembayaran === 'Success' ? 'bg-success text-white' : ($item->status_pembayaran === 'Failed' ? 'bg-danger text-white' : 'bg-warning text-dark') }}" 
                                                style="width: auto; display: inline-block; cursor: pointer;"
                                                onfocus="this.setAttribute('data-prev', this.value)"
                                                onchange="updateStatus('{{ $item->id }}', this)">
                                            <option value="Pending" class="bg-white text-dark" {{ $item->status_pembayaran === 'Pending' ? 'selected' : '' }}>P</option>
                                            <option value="Success" class="bg-white text-dark" {{ $item->status_pembayaran === 'Success' ? 'selected' : '' }}>Y</option>
                                            <option value="Failed" class="bg-white text-dark" {{ $item->status_pembayaran === 'Failed' ? 'selected' : '' }}>N</option>
                                        </select>
                                    </td>

                                    <td id="tanggal-bayar-{{ $item->id }}">
                                        @if ($item->tanggal_pembayaran === null)
                                            Belum Bayar
                                        @else
                                            {{ $item->tanggal_pembayaran }}
                                        @endif
                                    </td>
                                    <td>{{ $item->payment->name ?? '-' }}</td>
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
        async function updateStatus(id, selectElement) {
            const confirmation = confirm("Apakah Anda yakin ingin memperbarui status transaksi ini?");
            const previousValue = selectElement.getAttribute('data-prev') || selectElement.value;
            const newValue = selectElement.value;

            if (!confirmation) {
                console.log("Pembaruan status dibatalkan oleh pengguna.");
                selectElement.value = previousValue; // Revert
                return;
            }

            // Disable select during request
            selectElement.disabled = true;

            try {
                // Kirim permintaan untuk memperbarui status transaksi
                const response = await fetch('/transaksi/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: id,
                        status: newValue
                    })
                });

                const result = await response.json();
                console.info('Response:', result);

                // Check if response was successful (status 200)
                if (response.ok) {
                    // Update classes based on new status
                    let newClass = 'form-control form-control-sm bg-warning text-dark';
                    if (newValue === 'Success') {
                        newClass = 'form-control form-control-sm bg-success text-white';
                    } else if (newValue === 'Failed') {
                        newClass = 'form-control form-control-sm bg-danger text-white';
                    }
                    selectElement.className = newClass;
                    selectElement.style.width = 'auto';
                    selectElement.style.display = 'inline-block';
                    selectElement.setAttribute('data-prev', newValue);
                    selectElement.disabled = false;

                    // Update payment date
                    const tanggalBayarElement = document.querySelector(`#tanggal-bayar-${id}`);
                    if (tanggalBayarElement) {
                        if (result.tanggal_pembayaran) {
                            tanggalBayarElement.textContent = result.tanggal_pembayaran;
                        } else {
                            tanggalBayarElement.textContent = 'Belum Bayar';
                        }
                    }

                    alert(result.message);
                } else {
                    // Restore original value on error
                    selectElement.value = previousValue;
                    selectElement.disabled = false;
                    alert(result.message || 'Gagal memperbarui status.');
                }
            } catch (error) {
                console.error('Error:', error);
                // Restore original value on error
                selectElement.value = previousValue;
                selectElement.disabled = false;
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        }
    </script>
@endsection
