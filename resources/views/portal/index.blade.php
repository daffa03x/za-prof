@extends('component.layout.portal')

@section('content')
    @include('component.layout.header')

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


            ttq.load('C4I4LTHCF95KKVVI6N7G}');
            ttq.page();

        }(window, document, 'ttq');
    </script>
    <!-- TikTok Pixel Code End -->

    <main class="main">
        <!-- Hero Section -->
        <section id="hero" class="hero section">

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
                        <h1 data-aos="fade-up">Misi</h1>
                        <p data-aos="fade-up" data-aos-delay="100">Berbagi Kebaikan bersama Volunteer dalam rangkaian
                            perjalanan yang seru !</p>
                        <div class="d-flex flex-column flex-md-row" data-aos="fade-up" data-aos-delay="200">
                            <a href="https://raihmimpi.id/profile/386" class="btn-get-started" target="_blank">Donate <i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out">
                        <img src="{{ asset('assets/img/za/home_za.jpg') }}" class="img-fluid animated rounded"
                            alt="">
                        <p class="text-center mt-2" data-aos="fade-up" data-aos-delay="100">#SOBAT ZIGI! SIAP AKSI!</p>
                    </div>
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- About Section -->
        <section id="about" class="about section">

            <div class="container" data-aos="fade-up">
                <div class="row gx-0">

                    <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="content">
                            <h2>About Us</h2>
                            <p>
                                Zillenial Action adalah sebuah yayasan yang didirikan oleh anak muda Indonesia dengan
                                semangat untuk mendorong perubahan positif dan berkelanjutan. Kami hadir sebagai wadah
                                pengembangan diri bagi pemuda-pemudi di seluruh Indonesia, berfokus pada pemberdayaan
                                masyarakat melalui empat pilar utama: pendidikan, sosial, lingkungan, dan budaya.
                            </p>
                            <div class="text-center text-lg-start">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
                        <img src="{{ asset('assets/img/za/about_us.jpg') }}" class="img-fluid" alt="About Us"
                            loading="lazy">
                    </div>

                </div>
            </div>


        </section><!-- /About Section -->

        <!-- Stats Section -->
        <section id="stats" class="stats section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100">
                            <i class="bi bi-emoji-smile color-blue flex-shrink-0"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="4000" data-purecounter-duration="1"
                                    class="purecounter"></span>
                                <p>Participants</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100">
                            <i class="bi bi-journal-richtext color-orange flex-shrink-0" style="color: #ee6c20;"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="68" data-purecounter-duration="1"
                                    class="purecounter"></span>
                                <p>Events</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100">
                            <i class="bi bi-headset color-green flex-shrink-0" style="color: #15be56;"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="60" data-purecounter-duration="1"
                                    class="purecounter"></span>
                                <p>Partners</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100">
                            <i class="bi bi-people color-pink flex-shrink-0" style="color: #bb0852;"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="2000" data-purecounter-duration="1"
                                    class="purecounter"></span>
                                <p>Beneficiaries</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                </div>

            </div>

        </section><!-- /Stats Section -->

        <!-- Services Section -->
        <section id="services" class="services section">

            <!-- Section Title -->
            <div class="container section-title " data-aos="fade-up">
                <p>Social Trip Events</p>
            </div>
            <!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    @foreach ($event as $item)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                            <div class="service-item item-cyan position-relative">
                                <img src="{{ asset($item->image) }}" alt="Social trip service image" height="200"
                                    width="200" class="img-fluid mb-4" loading="lazy" />
                                <h3>{{ $item->name }}</h3>
                                <p>
                                    {{ $item->deskripsi }}
                                </p>
                                <a href="{{ route('view_content', ['id' => $item->id]) }}" class="read-more stretched-link">
                                    <span>Lihat</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- Section Title -->
                <div class="container section-title " data-aos="fade-up">
                    <br><a href="{{ route('program') }}" class="more-button">Selengkapnya</a>
                </div>
                <!-- End Section Title -->

        </section><!-- /Services Section -->

        <!-- Values Section -->
        <section id="portfolio" class="values section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <p>Portofolio<br></p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card">
                            <img src="{{ asset('assets/img/za/about_us.jpg') }}" class="img-fluid rounded"
                                alt="mengajar desa ranca cangkuang" loading="lazy">
                            <h3>Mengajar Desa Ranca Cangkuang</h3>
                            <p> Bermain dan berbagi ceria sambil belajar dengan adik adik dari Desa Rancacangkuang. Bermain
                                games sederhana yang seru dan menggambar bersama tentang cita-cita adik gemas Desa
                                Rancacangkuang di masa depan.</p>
                        </div>
                    </div><!-- End Card Item -->

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card">
                            <img src="{{ asset('assets/img/portfolio/portfolio_4.jpg') }}" class="img-fluid rounded"
                                alt="posyandu" loading="lazy">
                            <h3>Zillenial Impact : Program Posyandu Lansia</h3>
                            <p>Merupakan program pemberdayaan masyarakat yang difokuskan pada peningkatan kualitas hidup
                                lanjut usia melalui pelayanan kesehatan preventif, edukasi gizi, serta penguatan peran
                                sosial lansia dalam komunitas. Program ini bertujuan menciptakan lansia yang sehat, mandiri,
                                dan berdaya.</p>
                        </div>
                    </div><!-- End Card Item -->

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="card">
                            <img src="{{ asset('assets/img/portfolio/portfolio_3.jpg') }}" class="img-fluid rounded"
                                alt="Clean up day" loading="lazy">
                            <h3>Clean Up Day : Dari Kata ke Aksi, Dari Aksi ke Inspirasi</h3>
                            <p>Bermain harta karun bersembunyi sambil jalan santai dan membersihkan lingkungan sekitar Tamah
                                Hutan Raya Ir Djuanda bersama adik panti. Tidak hanya itu, keseruan bertambah saat peserta
                                dan adik adik bermain dan memberi makan rusa bersama.</p>
                        </div>
                    </div><!-- End Card Item -->

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
                        <div class="card">
                            <img src="{{ asset('assets/img/portfolio/portfolio_2.jpg') }}" class="img-fluid rounded"
                                alt="idul fitri" loading="lazy">
                            <h3>Harmoni Berbagi : Belanja Bersama Adik Panti di Eid Al-Fitr</h3>
                            <p>Indahnya berbagi bersama adik panti di indahnya lebaran yang Fitri. Tidak hanya berbagi baju
                                baru, tertapi juga berbagi kebahagiaan dan kehangatan. Senyum manis mereka menambah warna di
                                akhir Ramadhan yang penuh berkah.</p>
                        </div>
                    </div><!-- End Card Item -->

                </div>

            </div>

        </section><!-- /Values Section -->

        <!-- Features Section -->
        <section id="features" class="features section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <p>Social Trip Programs<br></p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-5">

                    <div class="col-xl-6" data-aos="zoom-out" data-aos-delay="100">
                        <img src="{{ asset('assets/img/features.png') }}" class="img-fluid" alt="">
                    </div>

                    <div class="col-xl-6 d-flex">
                        <div class="row align-self-center gy-4">

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                                <div class="feature-box d-flex align-items-center">
                                    <i class="bi bi-check"></i>
                                    <h3>Mengajar Desa</h3>
                                </div>
                            </div><!-- End Feature Item -->

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                                <div class="feature-box d-flex align-items-center">
                                    <i class="bi bi-check"></i>
                                    <h3>Explore Desa</h3>
                                </div>
                            </div><!-- End Feature Item -->

                        </div>
                    </div>

                </div>

            </div>

        </section><!-- /Features Section -->

        <!-- Portfolio Section -->
        <section id="gallery" class="portfolio section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <p>Gallery</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

                    <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                            <div class="portfolio-content h-100">
                                <img src="{{ asset('assets/img/za/home_za.jpg') }}" class="img-fluid"
                                    alt="Deskripsi Gambar" loading="lazy">
                                <div class="portfolio-info">
                                    <h4>Gallery</h4>
                                    <a href="{{ asset('assets/img/za/home_za.jpg') }}" title=""
                                        data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i
                                            class="bi bi-zoom-in"></i></a>
                                </div>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
                            <div class="portfolio-content h-100">
                                <img src="{{ asset('assets/img/za/about_us.jpg') }}" class="img-fluid" alt="gallery">
                                <div class="portfolio-info">
                                    <h4>Gallery</h4>
                                    <a href="{{ asset('assets/img/za/about_us.jpg') }}" title=""
                                        data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                            class="bi bi-zoom-in"></i></a>
                                </div>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                            <div class="portfolio-content h-100">
                                <img src="{{ asset('assets/img/portfolio/portfolio_3.jpg') }}" class="img-fluid"
                                    alt="gallery">
                                <div class="portfolio-info">
                                    <h4>Gallery</h4>
                                    <a href="{{ asset('assets/img/portfolio/portfolio_3.jpg') }}" title=""
                                        data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i
                                            class="bi bi-zoom-in"></i></a>
                                </div>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
                            <div class="portfolio-content h-100">
                                <img src="{{ asset('assets/img/portfolio/portfolio_4.jpg') }}" class="img-fluid"
                                    alt="gallery">
                                <div class="portfolio-info">
                                    <h4>Gallery</h4>
                                    <a href="{{ asset('assets/img/portfolio/portfolio_4.jpg') }}" title=""
                                        data-gallery="portfolio-gallery-book" class="glightbox preview-link"><i
                                            class="bi bi-zoom-in"></i></a>
                                </div>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                            <div class="portfolio-content h-100">
                                <img src="{{ asset('assets/img/gallery/2016_0101_14534700.jpg') }}" class="img-fluid"
                                    alt="gallery">
                                <div class="portfolio-info">
                                    <h4>Gallery</h4>
                                    <a href="{{ asset('assets/img/gallery/2016_0101_14534700.jpg') }}" title=""
                                        data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i
                                            class="bi bi-zoom-in"></i></a>
                                </div>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
                            <div class="portfolio-content h-100">
                                <img src="{{ asset('assets/img/gallery/2016_0101_15564300.jpg') }}" class="img-fluid"
                                    alt="gallery">
                                <div class="portfolio-info">
                                    <h4>Gallery</h4>
                                    <a href="{{ asset('assets/img/gallery/2016_0101_15564300.jpg') }}" title=""
                                        data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                            class="bi bi-zoom-in"></i></a>
                                </div>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                            <div class="portfolio-content h-100">
                                <img src="{{ asset('assets/img/gallery/IMG_4768.JPG') }}" class="img-fluid"
                                    alt="gallery">
                                <div class="portfolio-info">
                                    <h4>Gallery</h4>
                                    <a href="{{ asset('assets/img/gallery/IMG_4768.JPG') }}" title=""
                                        data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i
                                            class="bi bi-zoom-in"></i></a>
                                </div>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
                            <div class="portfolio-content h-100">
                                <img src="{{ asset('assets/img/gallery/IMG_4791.JPG') }}" class="img-fluid"
                                    alt="gallery">
                                <div class="portfolio-info">
                                    <h4>Gallery</h4>
                                    <a href="{{ asset('assets/img/gallery/IMG_4791.JPG') }}" title=""
                                        data-gallery="portfolio-gallery-book" class="glightbox preview-link"><i
                                            class="bi bi-zoom-in"></i></a>
                                </div>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                            <div class="portfolio-content h-100">
                                <img src="{{ asset('assets/img/gallery/IMG_7921.JPG') }}" class="img-fluid"
                                    alt="gallery">
                                <div class="portfolio-info">
                                    <h4>Gallery</h4>
                                    <a href="{{ asset('assets/img/gallery/IMG_7921.JPG') }}" title=""
                                        data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i
                                            class="bi bi-zoom-in"></i></a>
                                </div>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
                            <div class="portfolio-content h-100">
                                <img src="{{ asset('assets/img/gallery/IMG-20241019-WA0029.jpg') }}" class="img-fluid"
                                    alt="gallery">
                                <div class="portfolio-info">
                                    <h4>Gallery</h4>
                                    <a href="{{ asset('assets/img/gallery/IMG-20241019-WA0029.jpg') }}" title=""
                                        data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                            class="bi bi-zoom-in"></i></a>
                                </div>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                            <div class="portfolio-content h-100">
                                <img src="{{ asset('assets/img/gallery/DSC01086.jpg') }}" class="img-fluid"
                                    alt="gallery">
                                <div class="portfolio-info">
                                    <h4>Gallery</h4>
                                    <a href="{{ asset('assets/img/gallery/DSC01086.jpg') }}" title=""
                                        data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i
                                            class="bi bi-zoom-in"></i></a>
                                </div>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
                            <div class="portfolio-content h-100">
                                <img src="{{ asset('assets/img/gallery/DSC01087.jpg') }}" class="img-fluid"
                                    alt="gallery">
                                <div class="portfolio-info">
                                    <h4>Gallery</h4>
                                    <a href="{{ asset('assets/img/gallery/DSC01087.jpg') }}" title=""
                                        data-gallery="portfolio-gallery-book" class="glightbox preview-link"><i
                                            class="bi bi-zoom-in"></i></a>
                                </div>
                            </div>
                        </div><!-- End Portfolio Item -->

                    </div><!-- End Portfolio Container -->

                </div>

            </div>

        </section><!-- /Portfolio Section -->

        <!-- Testimonials Section -->
        <section id="testimonials" class="testimonials section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <p>Testimoni<br></p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 40
                },
                "1200": {
                  "slidesPerView": 3,
                  "spaceBetween": 1
                }
              }
            }
          </script>
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Seru banget ketemu temen baru dan adik-adik yang lucu. Kegiatannya keren!
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img"
                                        alt="">
                                    <h3>Nayla Nur Adzima</h3>
                                    <h4>Volunteer</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Ketemu temen-temen baru dengan background bermacam-macam. tapi punya hobi yang sama.
                                    Trims Zillenial Action!
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img"
                                        alt="">
                                    <h3>Elsa Arinda</h3>
                                    <h4>Volunteer</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Kesekian kalinya ikut jadi volunteer bareng Zillenial Action. Seru, kegiatannya beraneka
                                    ragam banget!!
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img"
                                        alt="">
                                    <h3>Agniya Aulia</h3>
                                    <h4>Volunteer</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Ketemu banyak orang hebat yang punya semangat berbagi. Seneng plus hati puas banget!
                                    Rekomended banget buat kalian yang mau isi waktu dengan hal positif!
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img"
                                        alt="">
                                    <h3>Nabila Virla A.</h3>
                                    <h4>Volunteer</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Dapet Pengalaman baru, temen baru, dan adik adik baru juga! Ga pernah nyesel ikut
                                    volunteer bareng ZA!
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-5.jpg" class="testimonial-img"
                                        alt="">
                                    <h3>Aldira Aprilia</h3>
                                    <h4>Volunteer</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>

        </section><!-- /Testimonials Section -->

        <!-- Clients Section -->
        <section id="clients" class="clients section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <p>Mitra<br></p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 2,
                  "spaceBetween": 40
                },
                "480": {
                  "slidesPerView": 3,
                  "spaceBetween": 60
                },
                "640": {
                  "slidesPerView": 4,
                  "spaceBetween": 80
                },
                "992": {
                  "slidesPerView": 6,
                  "spaceBetween": 120
                }
              }
            }
          </script>
                    <div class="swiper-wrapper align-items-center">
                        <div class="swiper-slide"><img src="{{ asset('assets/img/mitra/1. logo-kitabisa.png') }}"
                                class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img
                                src="{{ asset('assets/img/mitra/logo diferensia baru (1) (1).png') }}" class="img-fluid"
                                alt=""></div>
                        <div class="swiper-slide"><img
                                src="{{ asset('assets/img/mitra/Logo Raihmimpi-2023-01 (1).png') }}" class="img-fluid"
                                alt=""></div>
                        <div class="swiper-slide"><img
                                src="{{ asset('assets/img/mitra/5. pt yooka LOGO UTAMA TANPA BG.png') }}"
                                class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="{{ asset('assets/img/mitra/Artboard 1SH.png') }}"
                                class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="{{ asset('assets/img/mitra/Logo FH_square.png') }}"
                                class="img-fluid" alt="">
                        </div>
                        <div class="swiper-slide"><img
                                src="{{ asset('assets/img/mitra/Logo Primary_Beri Dampak-01.png') }}" class="img-fluid"
                                alt="">
                        </div>
                        <div class="swiper-slide"><img src="{{ asset('assets/img/mitra/LOGO_RZ (1).png') }}"
                                class="img-fluid" alt="">
                        </div>
                        <div class="swiper-slide"><img src="{{ asset('assets/img/mitra/zis.png') }}" class="img-fluid"
                                alt="">
                        </div>
                        <div class="swiper-slide"><img src="{{ asset('assets/img/mitra/Main colored.png') }}"
                                class="img-fluid" alt="">
                        </div>
                        <div class="swiper-slide"><img src="{{ asset('assets/img/mitra/Logo RKP.png') }}"
                                class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>

        </section><!-- /Clients Section -->
    </main>

    @include('component.layout.footer')
@endsection
