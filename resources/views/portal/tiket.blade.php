<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Trip - Bersihkan Jejak</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
            /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 800px;
            /* Adjust as needed */
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        h1,
        h2 {
            color: #333;
            font-weight: bold;
            margin-top: 0;
        }

        p {
            color: #555;
            line-height: 1.6;
        }

        img.icon {
            width: 16px;
            /* Adjust icon size */
            height: 16px;
            margin-right: 8px;
            vertical-align: middle;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #5a2d67;
            /* Changed to #5a2d67 */
            color: white;
        }

        .header .logo img {
            height: 30px;
            /* Adjust logo size */
        }

        .e-voucher {
            font-size: 1.1em;
            font-weight: bold;
        }

        /* Event Info Section */
        .event-info {
            padding: 30px;
            border-bottom: 1px solid #eee;
        }

        .event-info .event-title h1 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #5a2d67;
            /* Changed to #5a2d67 */
        }

        .event-details {
            display: flex;
            gap: 20px;
            position: relative;
            /* For Zillenial Action logo positioning */
        }

        .event-details .event-image img {
            width: 250px;
            /* Fixed width for event image */
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .event-details .details-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 10px;
        }

        .zillenial-action {
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            /* Adjust size as needed */
            height: 80px;
            border-radius: 50%;
            background-color: #f0f2f5;
            /* Light background for the logo circle */
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .zillenial-action img {
            width: 80%;
            height: 80%;
            object-fit: contain;
        }

        /* Order Information Section */
        .order-information {
            padding: 30px;
            border-bottom: 1px solid #eee;
            position: relative;
            /* Keep this for ticket-count positioning */
        }

        .order-information h2 {
            font-size: 1.4em;
            margin-bottom: 10px;
            display: inline-block;
        }

        .order-information .ticket-count {
            font-size: 0.9em;
            color: #888;
            position: absolute;
            top: 30px;
            right: 30px;
        }

        /* NEW CONTAINER FOR ORDER DETAILS AND BARCODE */
        .order-section-content {
            display: flex;
            /* Use flexbox to align grid and barcode side-by-side */
            justify-content: space-between;
            /* Space out the two columns */
            align-items: flex-start;
            /* Align items to the top */
            gap: 30px;
            /* Gap between the two columns */
            flex-wrap: wrap;
            /* Allow wrapping on smaller screens */
            margin-top: 20px;
            /* Add a bit of space below the section title */
        }

        .order-details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px 30px;
            /* Row gap, column gap */
            /* margin-bottom: 25px; /* Removed, managed by flex container */
            /* padding-right: 180px; /* Removed, managed by flex container */
            flex-grow: 1;
            /* Allow grid to grow and take available space */
        }

        .order-details-grid .order-item {
            display: flex;
            flex-direction: column;
        }

        .order-details-grid .order-item .label {
            font-size: 0.85em;
            color: #888;
            margin-bottom: 3px;
        }

        .order-details-grid .order-item .value {
            font-size: 1em;
            color: #333;
            font-weight: bold;
        }

        .order-details-grid .order-item .invoice-code {
            color: #5a2d67;
            /* Changed to #5a2d67 */
        }

        /* Barcode Section */
        .barcode-section {
            /* Removed: position: absolute, top, right properties */
            width: 180px;
            /* Adjusted width for better fit within flexbox */
            flex-shrink: 0;
            /* Prevent barcode section from shrinking too much */
            text-align: center;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
            /* Removed: margin-top if it was specifically for absolute positioning */
        }

        .barcode-section .item-price {
            margin-bottom: 15px;
        }

        .barcode-section .item-price span:first-child {
            font-size: 0.9em;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        .barcode-section .item-price .price {
            font-size: 1.3em;
            font-weight: bold;
            color: #5a2d67;
            /* Changed to #5a2d67 */
        }

        .barcode-image img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 5px auto;
        }

        .barcode-number {
            font-family: 'Courier New', monospace;
            /* Monospace for barcode number */
            font-size: 0.8em;
            color: #333;
            word-break: break-all;
            /* Ensure long numbers wrap */
        }


        /* Terms & Conditions Section */
        .terms-conditions {
            padding: 30px;
            border-bottom: 1px solid #eee;
        }

        .terms-conditions h2 {
            font-size: 1.4em;
            margin-bottom: 15px;
        }

        .terms-conditions ul {
            list-style: none;
            /* Remove default bullet points */
            padding: 0;
            margin: 0;
        }

        .terms-conditions li {
            font-size: 0.95em;
            color: #555;
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }

        .terms-conditions li::before {
            content: '•';
            /* Custom bullet point */
            color: #5a2d67;
            /* Changed to #5a2d67 */
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
            position: absolute;
            left: 0;
            top: 0;
        }

        /* Footer */
        .footer {
            background-color: #f8f9fa;
            /* Light background for footer */
            padding: 20px 30px;
            display: flex;
            justify-content: flex-start;
            /* Align items to the start */
            align-items: center;
            flex-wrap: wrap;
            /* Allow items to wrap on smaller screens */
            gap: 20px;
            /* Add gap for spacing between the logo and first contact item, and between contact items */
        }

        .footer .footer-logo {
            height: 25px;
            /* Adjust logo size */
            /* margin-right: 20px; REMOVE THIS - using gap on parent */
        }

        /* Pastikan contact-item berada dalam satu baris */
        .footer-contact {
            /* This class seems to be the container for logo and contact items */
            display: flex;
            /* Make this a flex container */
            flex-wrap: wrap;
            /* Allow wrapping */
            align-items: center;
            /* Vertically align items */
            gap: 20px;
            /* Space between logo and contact items */
            /* Jika footer-contact ini adalah wadah untuk semua item kontak saja tanpa logo, */
            /* maka flex-direction: row; akan ada di sini. */
            /* Namun, berdasarkan HTML sebelumnya, footer-contact adalah keseluruhan div */
            /* yang berisi logo dan semua contact-item. Jadi, gap dan flex-wrap di parent .footer sudah cukup. */
            /* Mari kita pastikan .contact-item sendiri tidak memiliki flex-direction: column; */
        }

        .footer .contact-item {
            display: flex;
            align-items: center;
            font-size: 0.85em;
            color: #555;
            /* Pastikan tidak ada flex-direction: column; di sini */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .event-details {
                flex-direction: column;
                align-items: center;
            }

            .event-details .event-image {
                margin-bottom: 20px;
            }

            .zillenial-action {
                position: static;
                /* Reset positioning on small screens */
                margin-top: 20px;
            }

            /* Adjustments for order section on small screens */
            .order-section-content {
                flex-direction: column;
                /* Stack columns vertically on small screens */
                align-items: center;
                /* Center items when stacked */
                gap: 20px;
                /* Adjust gap for stacked layout */
            }

            .order-details-grid {
                width: 100%;
                /* Take full width when stacked */
                grid-template-columns: 1fr;
                /* Single column on small screens */
            }

            .barcode-section {
                width: 80%;
                /* Adjust width for barcode on small screens when stacked */
                margin-top: 0;
                /* Reset margin-top for barcode section in stacked layout */
            }

            .footer {
                flex-direction: column;
                /* Stack columns vertically on small screens */
                align-items: flex-start;
                /* Align items to the start when stacked */
            }

            .footer .footer-logo {
                margin-right: 0;
                margin-bottom: 15px;
                /* Add margin when stacked */
            }

            .footer-contact {
                /* If this is the main contact wrapper */
                flex-direction: column;
                /* Ensure contact items stack vertically on small screens */
                align-items: flex-start;
                gap: 10px;
                /* Smaller gap when stacked */
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <img src="{{ asset('assets/img/Logo-ZA.png') }}" alt="Loket Logo">
            </div>
            <div class="e-voucher">
                E-Voucher
            </div>
        </header>

        <section class="event-info">
            <div class="event-title">
                <h1>{{ $transaksi->event->name }}</h1>
            </div>
            <div class="event-details">
                <div class="event-image">
                    <img src="{{ asset($transaksi->event->image) }}" alt="Social Trip">
                </div>
                <div class="details-content">
                    <div class="detail-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-geo-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.3 1.3 0 0 0-.37.265.3.3 0 0 0-.057.09V14l.002.008.016.033a.6.6 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.6.6 0 0 0 .146-.15l.015-.033L12 14v-.004a.3.3 0 0 0-.057-.09 1.3 1.3 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465s-2.462-.172-3.34-.465c-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411" />
                        </svg>
                        <span style="margin-left: 5px">Loket Headquarter</span>
                    </div>
                    <div class="detail-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-calendar2-week-fill" viewBox="0 0 16 16">
                            <path
                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5M8.5 7a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM3 10.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z" />
                        </svg>
                        <span style="margin-left: 5px">{{ date('d F Y', strtotime($transaksi->event->waktu_mulai)) }}
                            Pukul
                            {{ date('H:i', strtotime($transaksi->event->waktu_mulai)) }} -
                            {{ date('H:i', strtotime($transaksi->event->waktu_berakhir)) }}</span>
                    </div>
                    <div class="detail-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                            <path
                                d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                        </svg>
                        <span style="margin-left: 5px">{{ $transaksi->event->nama_tempat }}</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="order-information">
            <h2>Informasi Pesanan / Order Information</h2>
            <span class="ticket-count">TICKET {{ $transaksi->jumlah_tiket }}</span>

            <div class="order-details-grid">
                <div class="order-item">
                    <span class="label">Nama / Name</span>
                    <span class="value">{{ $transaksi->name }}</span>
                </div>
                <div class="order-item">
                    <span class="label">Kode Tagihan / Invoice Code</span>
                    <span class="value invoice-code">{{ $transaksi->invoice }}</span>
                </div>
                <div class="order-item">
                    <span class="label">Tanggal Pembelian / Order Date</span>
                    <span class="value">{{ $transaksi->created_at }}</span>
                </div>
                <div class="order-item">
                    <span class="label">Referensi / Reference</span>
                    <span class="value">Online</span>
                </div>
                <div class="order-item empty-row"></div>
            </div>
        </section>

        <section class="terms-conditions">
            <h2>Syarat dan Ketentuan / Terms & Conditions</h2>
            <ul>
                <li>Mengisi formulir pendaftaran</li>
                <li>Memberikan kontribusi sesuai yang tertera (sudah termasuk bantuan untuk penerima manfaat)</li>
                <li>Tiket yang sudah dibeli tidak dapat dibatalkan</li>
                <li>Bersedia mengikuti aturan yang berlaku selama kegiatan Zillenial Action berlangsung</li>
                <li>Menjadi inspirasi kebaikan dengan membagikan pengalaman menjadi Sobat Zigi lewat media sosial yang
                    kamu miliki dan di kolaborasikan dengan akun instagram @zillenialaction</li>
            </ul>
        </section>

        <footer class="footer">
            <div class="footer-contact">
                <img src="{{ asset('assets/img/Logo-ZA.png') }}" alt="Loket Logo" class="footer-logo"
                    style="margin-right: 10px">
                <a href="https://wa.me/6282121392363" class="contact-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-whatsapp" viewBox="0 0 16 16">
                        <path
                            d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
                    </svg>
                    <span style="margin-left: 5px">+6282121392363</span>
                </a>
                <a href="https://zillenialaction.id" class="contact-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-globe" viewBox="0 0 16 16">
                        <path
                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855A8 8 0 0 0 5.145 4H7.5zM4.09 4a9.3 9.3 0 0 1 .64-1.539 7 7 0 0 1 .597-.933A7.03 7.03 0 0 0 2.255 4zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a7 7 0 0 0-.656 2.5zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5zM8.5 5v2.5h2.99a12.5 12.5 0 0 0-.337-2.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5zM5.145 12q.208.58.468 1.068c.552 1.035 1.218 1.65 1.887 1.855V12zm.182 2.472a7 7 0 0 1-.597-.933A9.3 9.3 0 0 1 4.09 12H2.255a7 7 0 0 0 3.072 2.472M3.82 11a13.7 13.7 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5zm6.853 3.472A7 7 0 0 0 13.745 12H11.91a9.3 9.3 0 0 1-.64 1.539 7 7 0 0 1-.597.933M8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855q.26-.487.468-1.068zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.7 13.7 0 0 1-.312 2.5m2.802-3.5a7 7 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7 7 0 0 0-3.072-2.472c.218.284.418.598.597.933M10.855 4a8 8 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4z" />
                    </svg>
                    <span style="margin-left: 5px">www.zillenialaction.id</span>
                </a>
                <a href="mailto:zillenialaction@gmail.com" class="contact-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-envelope-at-fill" viewBox="0 0 16 16">
                        <path
                            d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2zm-2 9.8V4.698l5.803 3.546zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.5 4.5 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586zM16 9.671V4.697l-5.803 3.546.338.208A4.5 4.5 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671" />
                        <path
                            d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791" />
                    </svg>
                    <span style="margin-left: 5px">zillenialaction@gmail.com</span>
                </a>
            </div>
        </footer>
    </div>
</body>

</html>
