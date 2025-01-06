@extends('component.layout.portal')


@section('content')
<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.html" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="assets/img/Logo-ZA.png" alt="">
        <h1 class="sitename">Zillenial Action</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home<br></a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#services">Program</a></li>
          <li><a href="#portfolio">Galery</a></li>
          <li><a href="#team">Team</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
</header>


<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
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
       src="https://www.facebook.com/tr?id={your-pixel-id-goes-here}&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->

<!-- TikTok Pixel Code Start -->
<script>
  !function (w, d, t) {
    w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(
  var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script")
  ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};


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
              <h1>Content View</h1>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Content View</li>
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
                <div class="card-body bg-light">
                <p class="fw-bold text-muted">Sisa Waktu Pembayaran</p>
                <h2 class="display-5 text-primary fw-bold">
                    00 : 43 : 48
                </h2>
                <small class="text-muted">jam &nbsp; menit &nbsp; detik</small>
                <p class="mt-3 text-muted">
                    Selesaikan pembayaranmu sebelum <br>
                    <strong>Mon, 16 Dec 2024 10:01 AM</strong>
                </p>
                </div>
            </div>

            <!-- Metode Pembayaran -->
            <div class="shadow-sm mb-4">
                <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Gopay_Logo.png/320px-Gopay_Logo.png" alt="GoPay" width="30" height="30">
                    <span class="ms-2 fw-bold">GoPay</span>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="mb-1 text-muted">Kode Pesanan</p>
                    <p class="mb-1 fw-bold">{{ $data->invoice }}</p>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="mb-1 text-muted">Total Pembayaran</p>
                    <p class="mb-1 fw-bold text-primary">@rupiah( $data->total_pembayaran )</p>
                </div>
                </div>
            </div>

            <!-- QR Code -->
            <div class="text-center mb-4">
                <div class="card-body">
                <p>Scan kode QR di bawah</p>
                <img src="https://via.placeholder.com/200x200" alt="QR Code" class="img-fluid mb-3">
                <a href="#" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-shield-check"></i> Cek Status Pembayaran
                </a>
                </div>
            </div>

            <!-- Petunjuk Pembayaran -->
            <div class="shadow-sm">
                <div class="card-body">
                <p class="fw-bold text-muted mb-0">Petunjuk Pembayaran</p>
                </div>
            </div>
        
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
                        <p class="text-muted mb-0">Kode Pesanan <span class="fw-bold">{{ $data->invoice }}</span></p>
                        <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($data->tanggal_register)->format('D, d M Y h:i A') }}</p>
                    </div>

                    <!-- Detail Konten -->
                    <div class="row align-items-center">
                        <!-- Gambar -->
                        <div class="col-md-4">
                        <img src="{{ $data->event->image }}" alt="HOLIMOON 2024" class="img-fluid rounded">
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
                            <p class="mb-0">{{ $waktuMulai->format('H:i') }} : {{ $waktuBerakhir->format('H:i') }}</p>
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
@endsection