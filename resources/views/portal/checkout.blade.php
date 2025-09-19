@extends('component.layout.portal')

@section('content')
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
                <img src="{{ asset('assets/img/za/Logo ZA_.png') }}" alt="">
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>


    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '824012039961095');
        fbq('track', 'PageView');

        // Menambahkan custom parameter
        fbq('track', 'AddToCart', {
            content_name: "{{ $data->name }}",
            content_type: 'product'
        });
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=824012039961095&ev=PageView&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->

    <!-- TikTok Pixel Code Start -->
    <script>
        ! function(w, d, t) {
            w.TiktokAnalyticsObject = t;
            var ttq = w[t] = w[t] || [];
            ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias",
                "group", "enableCookie", "disableCookie", "holdConsent", "revokeConsent", "grantConsent"
            ], ttq.setAndDefer = function(t, e) {
                t[e] = function() {
                    t.push([e].concat(Array.prototype.slice.call(arguments, 0)))
                }
            };
            for (var i = 0; i < ttq.methods.length; i++) ttq.setAndDefer(ttq, ttq.methods[i]);
            ttq.instance = function(t) {
                for (
                    var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++) ttq.setAndDefer(e, ttq.methods[n]);
                return e
            }, ttq.load = function(e, n) {
                var r = "https://analytics.tiktok.com/i18n/pixel/events.js",
                    o = n && n.partner;
                ttq._i = ttq._i || {}, ttq._i[e] = [], ttq._i[e]._u = r, ttq._t = ttq._t || {}, ttq._t[e] = +new Date,
                    ttq._o = ttq._o || {}, ttq._o[e] = n || {};
                n = document.createElement("script");
                n.type = "text/javascript", n.async = !0, n.src = r + "?sdkid=" + e + "&lib=" + t;
                e = document.getElementsByTagName("script")[0];
                e.parentNode.insertBefore(n, e)
            };


            ttq.load('{id pixel}');
            ttq.page();

            // Menambahkan parameter detail
            ttq.track('AddToCart', {
                content_id: "{{ $data->id }}",
                content_name: "{{ $data->name }}",
                content_type: "product"
            });

        }(window, document, 'ttq');
    </script>
    <!-- TikTok Pixel Code End -->

    <main class="main">

        <!-- Page Title -->
        <div class="page-title">
            <div class="heading">
                <div class="container">
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
                    <div class="row d-flex justify-content-center text-center">
                        <div class="col-lg-8">
                            <h1>Detail Pemesanan</h1>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="breadcrumbs">
                <div class="container">
                    <ol>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li class="current">Detail Pemesanan</li>
                    </ol>
                </div>
            </nav>
        </div><!-- End Page Title -->

        <!-- Portfolio Details Section -->
        <section id="portfolio-details" class="portfolio-details section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <form action="{{ route('transaksi.post', ['id' => $data->id]) }}" method="POST">
                    @csrf
                    <div class="row gy-4">

                        <div class="col-lg-8">
                            <div class="portfolio-details-slider swiper init-swiper">

                                <h3>Detail Pemesanan</h3>

                                <div class="swiper-wrapper align-items-center">
                                    <div class="w-100 p-3">
                                        <div class="form-group py-2">
                                            <label for="name">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Gunakan nama yang tertera di KTP/Paspor.">
                                        </div>
                                        <div class="form-group py-2">
                                            <label for="telepon">Nomor Ponsel</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">+62</span>
                                                </div>
                                                <input type="text" class="form-control" id="telepon" name="telepon"
                                                    placeholder="Nomor Ponsel" aria-label="Nomor Ponsel"
                                                    aria-describedby="basic-addon1">
                                            </div>
                                        </div>

                                        <div class="form-group py-2 mb-2">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                placeholder="E-tiket akan dikirim ke email kamu.">
                                        </div>
                                        {{-- <div class="row py-2 mb-2">
                                            <div class="form-group col-lg-8">
                                                <label for="inputState">Tanggal lahir</label>
                                                <input type="date" class="form-control" name="tanggal_lahir"
                                                    id="inputAddress2">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="inputZip">Jenis Kelamin</label>
                                                <select id="inputState" name="jenis_kelamin" class="form-control">
                                                    <option value="L">Laki Laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="card-price py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                @php
                                                    $waktuMulai = new DateTime($data->waktu_mulai);
                                                @endphp
                                                <div>
                                                    <h5 class="mb-0 font-weight-bold">NORMAL PRICE</h5>
                                                    <p class="text-muted mb-0">
                                                        <i class="fas fa-info-circle text-primary"></i> Berakhir
                                                        {{ $waktuMulai->format('d-m-Y / H:i') }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <h5 class="font-weight-bold mb-0">@rupiah($data['harga'])</h5>
                                                </div>
                                            </div>
                                            <div class="divider"></div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-circle text-white"
                                                        style="background-color: #5a2d67" type="button"
                                                        id="decrease-btn">-</button>
                                                    <span class="mx-3 font-weight-bold" id="counter">0</span>
                                                    <button class="btn btn-circle text-white"
                                                        style="background-color: #5a2d67" type="button"
                                                        id="increase-btn">+</button>
                                                    <input type="hidden" id="jumlah_tiket_input" name="jumlah_tiket"
                                                        value="0">
                                                </div>
                                                <div>
                                                    <!-- Total Harga -->
                                                    <h5 class="mb-0" id="total-price-display">@rupiah(0)</h5>
                                                    <input type="hidden" name="price"
                                                        class="form-control text-right mb-2" id="total-price-input"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="container mt-5">
                                    <h3 class="mb-4">Metode Pembayaran</h3>
                                    <div class="list-group">
                                        @foreach ($payment as $item)
                                            <label
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <!-- Elemen Radio di Kiri -->
                                                <input type="radio" name="payment" value="{{ $item->id }}"
                                                    class="form-check-input mx-3">
                                                <!-- Elemen Gambar dan Nama -->
                                                <div class="d-flex justify-content-end align-items-center flex-grow-1">
                                                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}"
                                                        class="me-2" style="width: 110px; height: 40px;">
                                                    <span class="fw-semibold">{{ $item->name }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">

                            <div class="portfolio-info mt-4" data-aos="fade-up" data-aos-delay="200">
                                <h3>{{ $data->name }}</h3>

                                @php
                                    $waktuMulai = new DateTime($data->waktu_mulai);
                                @endphp

                                <!-- Waktu -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <p class="mb-0">Waktu</p>
                                    <h5 class="mb-0">{{ $waktuMulai->format('H:i') }}</h5>
                                </div>

                                <!-- Tanggal -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <p class="mb-0">Tanggal</p>
                                    <h5 class="mb-0">{{ $waktuMulai->format('d-m-Y') }}</h5>
                                </div>


                                <!-- Tempat -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <p class="mb-0">Lokasi</p>
                                    <h5 class="mb-0">{{ $data->nama_tempat }}</h5>
                                </div>

                                <!-- Tempat -->
                                <div class="d-flex justify-content-between align-items-center border-top pt-5 mt-2 mb-3">
                                    <p class="mb-0">Oleh: </p>
                                    <h5 class="mb-0">{{ $data->mitra }}</h5>
                                </div>

                            </div>

                            <div class="portfolio-info mt-4" data-aos="fade-up" data-aos-delay="200">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="gridCheck"
                                            onclick="toggleButton()" onchange="toggleCheckboxColor(this)">
                                        <!-- Tambahkan event onclick -->
                                        <label class="form-check-label" for="gridCheck">
                                            Saya setuju dengan Syarat & Ketentuan yang berlaku.
                                        </label>
                                    </div>
                                </div>
                                <!-- Total Harga -->
                                <div class="d-flex justify-content-between align-items-center pt-3">
                                    <button type="submit" id="submitButton" class="btn btn-lg w-100 text-white"
                                        style="background-color: #5a2d67" disabled>
                                        <!-- Tombol awal dalam status disabled -->
                                        Bayar Tiket
                                    </button>
                                </div>
                            </div>

                            <script>
                                function toggleButton() {
                                    const checkbox = document.getElementById('gridCheck');
                                    const button = document.getElementById('submitButton');

                                    if (checkbox.checked) {
                                        // Jika checkbox dicentang
                                        button.disabled = false;
                                        button.style.backgroundColor = '#5a2d67'; // Kembalikan ke warna default Bootstrap
                                        button.style.borderColor = '';
                                    } else {
                                        // Jika checkbox tidak dicentang
                                        button.disabled = true;
                                        button.style.backgroundColor = '#5a2d67'; // Warna biru keputihan
                                        button.style.borderColor = '';
                                    }
                                }
                            </script>
                        </div>
                </form>

            </div>

        </section><!-- /Portfolio Details Section -->

    </main>
    @include('component.layout.footer')

    <script>
        // JavaScript untuk menangani jumlah
        let counter = 0;
        const jumlah_tiket = @json($data['jumlah_tiket']);
        const pricePerItem = @json($data['harga']);
        let jumlahTiketInput = document.getElementById('jumlah_tiket_input');
        const counterDisplay = document.getElementById('counter'); // Ambil referensi ke elemen counter

        // Fungsi untuk memperbarui total harga dan tampilan counter
        function updateTotalPriceAndCounter() {
            const totalPrice = counter * pricePerItem;

            // Perbarui nilai di input field (tanpa titik)
            document.getElementById('total-price-input').value = totalPrice;

            // Perbarui nilai di elemen <h5> (dengan format Rp XXX.XXX)
            document.getElementById('total-price-display').innerText = `Rp ${totalPrice.toLocaleString('id-ID')}`;

            // Perbarui nilai di elemen <span> counter
            counterDisplay.innerText = counter; // BARIS INI DITAMBAHKAN/DIPINDAHKAN KE SINI
        }

        // Tombol tambah
        document.getElementById('increase-btn').addEventListener('click', () => {
            if (counter < jumlah_tiket) {
                counter++;
                jumlahTiketInput.value = counter; // Update nilai hidden input
                updateTotalPriceAndCounter(); // Panggil fungsi update yang baru
            } else {
                alert('Sisa Tiket Hanya Ada ' + jumlah_tiket);
            }
        });

        // Tombol kurang
        document.getElementById('decrease-btn').addEventListener('click', () => {
            if (counter > 0) {
                counter--;
                jumlahTiketInput.value = counter; // Update nilai hidden input
                updateTotalPriceAndCounter(); // Panggil fungsi update yang baru
            }
        });

        function toggleCheckboxColor(checkbox) {
            if (checkbox.checked) {
                checkbox.style.backgroundColor = '#5a2d67';
                checkbox.style.borderColor = '#5a2d67';
                // Untuk ikon centang, ini sedikit lebih rumit dengan JavaScript inline
                // karena ikon adalah background-image. Anda mungkin perlu kelas CSS yang sudah didefinisikan.
                // Atau, jika Anda tidak peduli dengan ikon centang dan hanya backgroundnya.
            } else {
                checkbox.style.backgroundColor = ''; // Mengembalikan ke default Bootstrap
                checkbox.style.borderColor = ''; // Mengembalikan ke default Bootstrap
            }
        }
    </script>
@endsection
