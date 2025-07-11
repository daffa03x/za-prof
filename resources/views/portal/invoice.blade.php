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
        fbq('init', '{your-pixel-id-goes-here}');
        fbq('track', 'PageView');

        // Menambahkan custom parameter
        fbq('track', 'Purchase', {
            content_name: "{{ $data->event->name }}"
            content_type: 'product'
            currency: 'IDR'
            value: "{{ $data->total_pembayaran }}"
        });
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={your-pixel-id-goes-here}&ev=PageView&noscript=1" />
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
            ttq.track('CompletePayment', {
                content_id: "{{ $data->event->id }}",
                content_name: "{{ $data->event->name }}",
                value: "{{ $data->total_pembayaran }}",
                currency: 'IDR'
            });

        }(window, document, 'ttq');
    </script>
    <!-- TikTok Pixel Code End -->

    <main class="main">

        <!-- Page Title -->
        <div class="page-title">
            <div class="heading">
                <div class="container">
                    <div class="row d-flex justify-content-center text-center">
                        <div class="col-lg-8">
                            <h1>Pembayaran</h1>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="breadcrumbs">
                <div class="container">
                    <ol>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li class="current">Pembayaran</li>
                    </ol>
                </div>
            </nav>
        </div><!-- End Page Title -->

        <!-- Portfolio Details Section -->
        <section id="portfolio-details" class="portfolio-details section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">


                    <div class="col-lg-4 mx-auto portfolio-info">
                        <!-- Countdown Timer -->
                        <div class="text-center mb-4">
                            <div class="card-body bg-light p-3">
                                <p class="fw-bold text-muted">Sisa Waktu Pembayaran</p>
                                <h2 class="display-5 fw-bold" style="color: #5a2d67" id="countdown-timer">
                                    00 : 00 : 00
                                </h2>
                                <small class="text-muted">jam &nbsp; menit &nbsp; detik</small>
                                <p class="mt-3 text-muted">
                                    Selesaikan pembayaranmu sebelum <br>
                                    <strong id="payment-deadline-display">Mon, 16 Dec 2024 10:01 AM</strong>
                                </p>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="shadow-sm mb-4">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset($data->payment->image) }}" alt="GoPay" width="110"
                                        height="40">
                                    <span class="ms-2 fw-bold">{{ $data->payment->name }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    {{-- <p class="mb-1 text-muted">Kode Pesanan</p> --}}
                                    {{-- <p class="mb-1 fw-bold">{{ $data->invoice }}</p> --}}
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1 text-muted">Total Pembayaran</p>
                                    <p class="mb-1 fw-bold" style="color: #5a2d67">@rupiah($data->total_pembayaran)</p>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code -->
                        <div class="text-center mb-4">
                            <div class="card-body">
                                @if ($data->payment->name == 'Qris')
                                    <p>Scan kode QR di bawah</p>
                                    <img src="https://via.placeholder.com/200x200" alt="QR Code" class="img-fluid mb-3">
                                @else
                                    <p>Setelah pembayaran Anda berhasil kami verifikasi, tiket akan segera dikirimkan ke
                                        email yang Anda cantumkan pada formulir.
                                    </p>
                                    <p class="text-center" style="color: #5a2d67;">
                                        <span id="accountNumberDisplay">{{ $data->payment->no_rek }}</span>
                                        <button class="btn btn-sm btn-outline-secondary ms-2"
                                            onclick="copyToClipboard('accountNumberDisplay')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                                                <path
                                                    d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
                                                <path
                                                    d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
                                            </svg>
                                            Salin
                                        </button>
                                        <br>
                                    </p>
                                    <p class="text-center" style="color: #5a2d67;">An/ YAYASAN ZILLENIAL ACTION INDONESIA
                                    </p>

                                    <script>
                                        function copyToClipboard(elementId) {
                                            // Mendapatkan elemen yang berisi teks nomor rekening
                                            const element = document.getElementById(elementId);
                                            let textToCopy = element.innerText || element.textContent; // Mengambil teks dari elemen

                                            // Membuat area teks sementara untuk proses penyalinan
                                            const tempTextArea = document.createElement('textarea');
                                            tempTextArea.value = textToCopy;
                                            document.body.appendChild(tempTextArea);

                                            // Memilih teks di area teks sementara
                                            tempTextArea.select();
                                            tempTextArea.setSelectionRange(0, 99999); // Untuk perangkat mobile

                                            // Menyalin teks ke clipboard
                                            try {
                                                document.execCommand('copy');
                                                alert('Nomor rekening berhasil disalin: ' + textToCopy);
                                            } catch (err) {
                                                console.error('Gagal menyalin: ', err);
                                                alert('Gagal menyalin nomor rekening. Silakan salin secara manual: ' + textToCopy);
                                            }

                                            // Menghapus area teks sementara
                                            document.body.removeChild(tempTextArea);
                                        }
                                    </script>
                                @endif
                                <a href="https://wa.me/6282121392363" class="btn btn-lg w-100 text-white"
                                    style="background-color: #5a2d67">
                                    <i class="bi bi-shield-check"></i> Hubungi Admin
                                </a>
                            </div>
                        </div>

                        <!-- Petunjuk Pembayaran -->
                        {{-- <div class="shadow-sm">
                            <div class="card-body">
                                <p class="fw-bold text-muted mb-0">Petunjuk Pembayaran</p>
                            </div>
                        </div> --}}

                    </div>


                    <div class="col-lg-8 mx-auto">
                        <!-- Kode Pesanan -->
                        <div class="portfolio-info">
                            @php
                                $waktuMulai = new DateTime($data->event->waktu_mulai);
                                $waktuBerakhir = new DateTime($data->event->waktu_berakhir);
                            @endphp
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p class="text-muted mb-0">Kode Pesanan <span
                                            class="fw-bold">{{ $data->invoice }}</span></p>
                                    <p class="text-muted mb-0">
                                        {{ \Carbon\Carbon::parse($data->tanggal_register)->format('D, d M Y h:i A') }}</p>
                                </div>

                                <!-- Detail Konten -->
                                <div class="row align-items-center">
                                    <!-- Gambar -->
                                    <div class="col-md-4">
                                        <img src="{{ asset($data->event->image) }}" alt="HOLIMOON 2024"
                                            class="img-fluid rounded">
                                    </div>

                                    <!-- Jadwal dan Lokasi -->
                                    <div class="col-md-8">
                                        <h5 class="fw-bold">{{ $data->event->name }}</h5>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-calendar3 me-2"></i>
                                            <p class="mb-0">{{ $waktuMulai->format('d-m-Y') }}</p>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-clock me-2"></i>
                                            <p class="mb-0">{{ $waktuMulai->format('H:i') }} :
                                                {{ $waktuBerakhir->format('H:i') }}</p>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-geo-alt me-2"></i>
                                            <p class="mb-0">{{ $data->event->nama_tempat }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Info Tiket -->
                                <hr>
                                <div class="mt-3">
                                    <h6 class="fw-bold mb-2">Info Tiket</h6>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-ticket-perforated me-2"></i>
                                        <p class="mb-0">&nbsp; {{ $data->jumlah_tiket }} Tiket</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>

        </section><!-- /Portfolio Details Section -->

    </main>
    @include('component.layout.footer')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil tanggal register dari PHP, lalu tambahkan 24 jam (atau deadline yang sudah dihitung)
            // Pastikan $data->tanggal_register adalah instance Carbon yang valid dan telah di-casts sebagai 'datetime' di model.
            // Asumsi $data->tanggal_register sudah ada dan merupakan tanggal mulai pembayaran.
            // Kita akan menambahkan 24 jam ke tanggal tersebut di JavaScript.
            const registerDateString = @json($data->tanggal_register->toIso8601String());
            console.log('registerDateString:', registerDateString); // Cek format ini
            const registerDate = new Date(registerDateString);
            console.log('registerDate (JS Object):', registerDate); // Cek apakah ini objek Date yang valid

            const paymentDeadline = new Date(registerDate.getTime() + (24 * 60 * 60 * 1000));
            console.log('paymentDeadline:', paymentDeadline); // Cek apakah deadline terhitung benar

            // Update tampilan tanggal deadline
            document.getElementById('payment-deadline-display').innerText = paymentDeadline.toLocaleString(
                'id-ID', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });

            const countdownElement = document.getElementById('countdown-timer');

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = paymentDeadline.getTime() - now;

                // Hitung waktu yang tersisa
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Format angka menjadi dua digit (misal: 05 daripada 5)
                const formattedHours = String(hours).padStart(2, '0');
                const formattedMinutes = String(minutes).padStart(2, '0');
                const formattedSeconds = String(seconds).padStart(2, '0');

                // Tampilkan sisa waktu
                countdownElement.innerHTML = `${formattedHours} : ${formattedMinutes} : ${formattedSeconds}`;

                // Jika hitungan mundur selesai
                if (distance < 0) {
                    clearInterval(countdownInterval); // Hentikan timer
                    countdownElement.innerHTML = "00 : 00 : 00";
                    alert("Waktu pembayaran telah berakhir!");
                    // Anda bisa menambahkan logika lain di sini, seperti menonaktifkan tombol pembayaran
                    // atau mengubah status transaksi di backend via AJAX request.
                }
            }

            // Panggil fungsi sekali segera untuk menghindari jeda awal
            updateCountdown();

            // Perbarui hitungan mundur setiap 1 detik
            const countdownInterval = setInterval(updateCountdown, 1000);
        });
    </script>
@endsection
