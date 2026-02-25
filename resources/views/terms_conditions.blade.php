<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Syarat dan Ketentuan | Juliet</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="{{ asset('assets/home_page/css/content-lp.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/vertical-logo.png') }}" type="image/x-icon">

</head>
<body>

<!-- Top Navigation Bar -->
<header class="reg-top-header">
        <div class="container">
            <div class="header-inner">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('assets/horizontal-logo.png') }}" alt="logo">
                </a>
                <div class="header-nav">
                    <a href="{{ url('/') }}">Beranda</a>
                    <a href="{{ url('about-us') }}">About us</a>
                    <a href="{{ url('/#fitur') }}">Fitur</a>
                    <a href="{{ url('/#harga') }}">Harga</a>
                    <a href="{{ url('/#contact') }}">Contact</a>
                </div>
                <div class="header-actions">
                    <a href="{{ route('login') }}" class="header-login-btn">Masuk</a>
                    <a href="{{ route('register') }}" class="header-login-btn">Daftar</a>
                </div>
            </div>
        </div>
    </header>


<main class="container main-content">

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-text">
            <nav class="breadcrumb">
                <a href="#">Beranda</a>
                <span class="material-symbols-outlined">chevron_right</span>
                <span class="breadcrumb-current">Syarat dan Ketentuan</span>
            </nav>
            <h1 class="hero-title">Syarat dan Ketentuan Layanan</h1>
            <!-- <p class="hero-meta">Terakhir Diperbarui: <span class="font-medium">24 Mei 2024</span></p> -->
        </div>
       
    </div>

    <div class="layout">

        <!-- Sidebar -->
        <aside class="sidebar no-print">
            <div class="sidebar-inner">
                <div class="sidebar-card">
                    <h3 class="sidebar-title">Daftar Isi</h3>
                    <nav class="sidebar-nav" id="sidebar-nav">
                        <a class="sidebar-link active" href="#penggunaan" data-section="penggunaan">
                            <span class="material-symbols-outlined">info</span>
                            <span>Ketentuan Penggunaan</span>
                        </a>
                        <a class="sidebar-link" href="#tanggung-jawab" data-section="tanggung-jawab">
                            <span class="material-symbols-outlined">account_balance</span>
                            <span>Tanggung Jawab Institusi</span>
                        </a>
                        <a class="sidebar-link" href="#perilaku" data-section="perilaku">
                            <span class="material-symbols-outlined">person_check</span>
                            <span>Perilaku Pengguna</span>
                        </a>
                        <a class="sidebar-link" href="#kewajiban" data-section="kewajiban">
                            <span class="material-symbols-outlined">gavel</span>
                            <span>Batasan Kewajiban</span>
                        </a>
                        <a class="sidebar-link" href="#kontak" data-section="kontak">
                            <span class="material-symbols-outlined">mail</span>
                            <span>Kontak Kami</span>
                        </a>
                    </nav>
                    <div class="sidebar-tip">
                        <p class="tip-title">Butuh bantuan hukum?</p>
                        <p class="tip-body">Hubungi departemen IT atau Administrasi Sekolah jika Anda memiliki pertanyaan spesifik.</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <article class="article">

            <!-- Section 1 -->
            <section class="section" id="penggunaan">
                <div class="section-header">
                    <div class="section-icon">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                    <h2 class="section-title">1. Ketentuan Penggunaan Platform</h2>
                </div>
                <div class="prose">
                    <p>Platform Learning Management System (LMS) ini disediakan khusus untuk keperluan internal akademik. Dengan mengakses atau menggunakan layanan kami, Anda setuju untuk terikat oleh ketentuan penggunaan berikut:</p>
                    <ul>
                        <li>Akses hanya diberikan kepada siswa, guru, dan staf administrasi yang terdaftar secara resmi di institusi.</li>
                        <li>Setiap pengguna bertanggung jawab untuk menjaga kerahasiaan kredensial login (username dan password).</li>
                        <li>Platform ini hanya boleh digunakan untuk tujuan pembelajaran, pengajaran, dan administrasi akademik yang sah.</li>
                        <li>Kami berhak menangguhkan akses jika ditemukan indikasi penyalahgunaan akun atau pelanggaran keamanan.</li>
                    </ul>
                </div>
            </section>

            <!-- Section 2 -->
            <section class="section" id="tanggung-jawab">
                <div class="section-header">
                    <div class="section-icon">
                        <span class="material-symbols-outlined">assured_workload</span>
                    </div>
                    <h2 class="section-title">2. Tanggung Jawab Institusi</h2>
                </div>
                <div class="card-section">
                    <div class="prose">
                        <p>Institusi berkomitmen untuk menyediakan lingkungan belajar digital yang aman dan andal:</p>
                        <div class="grid-2">
                            <div class="grid-item">
                                <h4 class="grid-item-title">
                                    <span class="material-symbols-outlined">check_circle</span>
                                    Keamanan Data
                                </h4>
                                <p>Menjamin perlindungan data pribadi siswa dan guru sesuai dengan kebijakan privasi yang berlaku di lingkungan sekolah.</p>
                            </div>
                            <div class="grid-item">
                                <h4 class="grid-item-title">
                                    <span class="material-symbols-outlined">check_circle</span>
                                    Ketersediaan Layanan
                                </h4>
                                <p>Berusaha menjaga sistem tetap aktif 24/7, kecuali untuk jadwal pemeliharaan rutin yang akan diumumkan sebelumnya.</p>
                            </div>
                            <div class="grid-item">
                                <h4 class="grid-item-title">
                                    <span class="material-symbols-outlined">check_circle</span>
                                    Integritas Konten
                                </h4>
                                <p>Memastikan semua materi pembelajaran yang diunggah oleh pihak sekolah memenuhi standar kualitas pendidikan.</p>
                            </div>
                            <div class="grid-item">
                                <h4 class="grid-item-title">
                                    <span class="material-symbols-outlined">check_circle</span>
                                    Dukungan Teknis
                                </h4>
                                <p>Menyediakan kanal bantuan bagi pengguna yang mengalami kesulitan teknis dalam mengakses materi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 3 -->
            <section class="section" id="perilaku">
                <div class="section-header">
                    <div class="section-icon">
                        <span class="material-symbols-outlined">diversity_3</span>
                    </div>
                    <h2 class="section-title">3. Perilaku Pengguna (Siswa/Guru)</h2>
                </div>
                <div class="prose">
                    <p>Integritas dan etika adalah kunci dalam lingkungan belajar kita. Semua pengguna wajib mematuhi kode etik berikut:</p>
                    <div class="bordered-items">
                        <div class="bordered-item">
                            <h4>Integritas Akademik</h4>
                            <p>Siswa dilarang keras melakukan plagiarisme, memberikan bantuan ilegal saat ujian, atau membagikan jawaban tugas kepada pengguna lain.</p>
                        </div>
                        <div class="bordered-item">
                            <h4>Etika Komunikasi</h4>
                            <p>Interaksi di forum diskusi atau chat harus tetap profesional. Dilarang menggunakan bahasa kasar, melakukan perundungan (cyberbullying), atau menyebarkan konten SARA.</p>
                        </div>
                        <div class="bordered-item">
                            <h4>Hak Kekayaan Intelektual</h4>
                            <p>Materi pembelajaran (video, PDF, slide) adalah milik institusi. Dilarang menggandakan atau mendistribusikan materi keluar dari platform tanpa izin tertulis.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 4 -->
            <section class="section" id="kewajiban">
                <div class="section-header">
                    <div class="section-icon">
                        <span class="material-symbols-outlined">warning</span>
                    </div>
                    <h2 class="section-title">4. Batasan Kewajiban</h2>
                </div>
                <div class="warning-card prose">
                    <p>Sejauh diizinkan oleh hukum yang berlaku:</p>
                    <ul>
                        <li>Institusi tidak bertanggung jawab atas kerugian tidak langsung atau kehilangan data akibat kegagalan perangkat keras pengguna atau koneksi internet pihak ketiga.</li>
                        <li>Kami tidak menjamin bahwa platform akan bebas sepenuhnya dari kesalahan teknis atau gangguan virus dari sumber eksternal.</li>
                        <li>Pengguna setuju untuk membebaskan institusi dari tuntutan hukum yang timbul akibat kelalaian atau pelanggaran syarat dan ketentuan yang dilakukan oleh pengguna sendiri.</li>
                    </ul>
                </div>
            </section>

            <!-- Section 5 -->
            <section class="section section-last" id="kontak">
                <div class="section-header">
                    <div class="section-icon">
                        <span class="material-symbols-outlined">support_agent</span>
                    </div>
                    <h2 class="section-title">5. Kontak Kami</h2>
                </div>
                <p class="prose-lead">Jika Anda memiliki pertanyaan mengenai Syarat dan Ketentuan ini, silakan hubungi kami melalui saluran berikut:</p>
                <div class="contact-grid">
                    <div class="contact-card">
                        <span class="material-symbols-outlined contact-icon">alternate_email</span>
                        <div>
                            <p class="contact-label">Email</p>
                            <p class="contact-value">support@juliet.co.id</p>                        </div>
                    </div>
                    <div class="contact-card">
                        <span class="material-symbols-outlined contact-icon">call</span>
                        <div>
                            <p class="contact-label">Telepon</p>
                            <p class="contact-value">(+62) 857-0060-9999</p>                        </div>
                    </div>
                </div>
            </section>

        </article>
    </div>
</main>

<!-- Footer -->
<footer class="about-footer">
        <div class="container">
            <div class="footer-grid-about">
                <div class="footer-brand-about">
                    <div class="footer-logo-about">
                        <img src="{{ asset('assets/horizontal-logo.png') }}" alt="Logo">
                    </div>
                    <p>Solusi LMS Modern untuk lebih efektif, adaptif dan cerdas.</p>
                </div>
                <div class="footer-links-about">
                    <h5>Navigasi</h5>
                    <ul>
                        <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li><a href="{{ url('about-us') }}">About us</a></li>
                        <li><a href="{{ url('/#fitur') }}">Fitur</a></li>
                        <li><a href="{{ url('/#harga') }}">Harga</a></li>
                        <li><a href="{{ url('/#contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-links-about">
                    <h5>Dukungan</h5>
                    <ul>
                        <!-- <li><a href="#">Pusat Bantuan</a></li>
                        <li><a href="#">Keamanan Data</a></li> -->
                        <li><a href="{{ url('page/type/terms-condition') }}">Syarat & Ketentuan</a></li>
                        <li><a href="{{ url('kebijakan-privasi') }}">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="footer-links-about">
                    <h5>Hubungi Kami</h5>
                    <ul>
                        <li><span class="material-symbols-outlined">mail</span> support@juliet.co.id</li>
                        <li><span class="material-symbols-outlined">call</span> +62 857 0060 9999</li>
                        <li><span class="material-symbols-outlined">location_on</span> Jl. Sono Kembang No.4-6, Embong Kaliasin, Kec. Genteng, Surabaya, Jawa Timur 60271</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom-about">
                <p>© 2026 Juliet. Seluruh hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

<!-- Back to Top -->
<button class="back-to-top no-print" id="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
    <span class="material-symbols-outlined">arrow_upward</span>
</button>

<!-- <script src="script.js"></script> -->
</body>
</html>