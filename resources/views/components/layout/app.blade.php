<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Zillenial Action</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <!-- Include stylesheet -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

    <link href="{{ asset('assets/img/Logo-ZA.png') }}" rel="icon">
    <link href="{{ asset('assets/img/Logo-ZA.png') }}" rel="Logo-ZA">
</head>

<body>
    <div id="app">

        <nav class="navbar navbar-expand-lg container">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Zillenial Action</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" aria-current="page"
                                href="{{ url('/dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('event') ? 'active' : '' }}" aria-current="page"
                                href="{{ url('/event') }}">Event</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('transaksi') ? 'active' : '' }}" aria-current="page"
                                href="{{ url('/transaksi') }}">Transaksi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('payment') ? 'active' : '' }}" aria-current="page"
                                href="{{ url('/payment') }}">Metode Pembayaran</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('voucher*') ? 'active' : '' }}" aria-current="page"
                                href="{{ url('/voucher') }}">Kode Voucher</a>
                        </li>
                    </ul>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            {{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu">
                            <div> <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>


        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

<script>
    const quill = new Quill('#editor', {
        theme: 'snow'
    });

    addEventListener('submit', function() {
        const editorContent = quill.root.innerHTML;
        document.getElementById('content').value = editorContent;
    });
</script>

<script>
    function confirmDelete() {
        return confirm("Apakah Anda yakin ingin menghapus data ini?");
    }
</script>


<script>
    function formatNumber(input) {
        // Menghapus semua tanda titik saat user mengetik
        let value = input.value.replace(/\./g, '');

        // Mengatur kembali format dengan titik ribuan
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    document.getElementById('harga').addEventListener('change', function() {
        // Simpan nilai asli tanpa titik saat form disubmit
        this.value = this.value.replace(/\./g, '');
    });
</script>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
    integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous">
</script>

</html>
