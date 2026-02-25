<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi - Juliet</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <!-- <link href="{{ asset('assets/home_page/css/style.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('assets/home_page/css/content-lp.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="assets/vertical-logo.png" type="image/x-icon">


</head>
<body>

    {{-- Header Navigation --}}
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

    {{-- Main Content --}}
    <main class="privacy-main">
        <div class="container">
            
            {{-- Breadcrumb --}}
            <nav class="breadcrumb">
                <a href="#">Beranda</a>
                <span class="material-symbols-outlined">chevron_right</span>
                <span>Kebijakan Privasi</span>
            </nav>

            {{-- Hero Section --}}
            <div class="privacy-hero">
                <div class="hero-content">
                    <h1>Kebijakan Privasi</h1>
                    <p>Komitmen kami dalam melindungi data pribadi siswa, guru, dan seluruh staf institusi di lingkungan Juliet.</p>
                </div>
            </div>

            {{-- Content Grid --}}
            <div class="privacy-grid">
                
                {{-- Sidebar Navigation --}}
                <aside class="privacy-sidebar">
                    <div class="sidebar-sticky">
                        <h3>Daftar Isi</h3>
                        <ul class="sidebar-nav">
                            <li><a href="#pendahuluan" class="active">1. Pendahuluan</a></li>
                            <li><a href="#pengumpulan-data">2. Pengumpulan Data</a></li>
                            <li><a href="#penggunaan-data">3. Penggunaan Data</a></li>
                            <li><a href="#keamanan-data">4. Keamanan Data</a></li>
                            <li><a href="#hak-pengguna">5. Hak Pengguna</a></li>
                            <li><a href="#kontak">6. Kontak Kami</a></li>
                        </ul>
                    </div>
                </aside>

                {{-- Content Area --}}
                <div class="privacy-content">
                    
                    {{-- Section 1 --}}
                    <section id="pendahuluan" class="content-section">
                        <h2><span class="section-number">01</span> Pendahuluan</h2>
                        <div class="prose">
                            <p>Selamat datang di Juliet. Kami sangat menghargai privasi Anda dan berkomitmen untuk melindungi informasi pribadi yang Anda berikan kepada kami. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan menjaga keamanan data dalam lingkungan digital sekolah kami.</p>
                            <p>Kebijakan ini berlaku bagi seluruh pengguna Learning Management System (LMS), termasuk siswa, guru, orang tua, dan staf administrasi. Dengan menggunakan platform ini, Anda menyetujui praktik pengolahan data sebagaimana dijelaskan dalam dokumen ini.</p>
                            <div class="info-box">
                                <span class="material-symbols-outlined">info</span>
                                <p><strong>Penting:</strong> Data yang dikelola dalam platform ini adalah milik eksklusif institusi pendidikan dan digunakan hanya untuk keperluan operasional akademik.</p>
                            </div>
                        </div>
                    </section>

                    {{-- Section 2 --}}
                    <section id="pengumpulan-data" class="content-section">
                        <h2><span class="section-number">02</span> Pengumpulan Data</h2>
                        <div class="data-grid">
                            <div class="data-card">
                                <div class="data-card-header">
                                    <span class="material-symbols-outlined">person</span>
                                    <h3>Data Siswa</h3>
                                </div>
                                <ul>
                                    <li>Nama Lengkap & NISN</li>
                                    <li>Riwayat Akademik & Nilai</li>
                                    <li>Kehadiran & Partisipasi Kelas</li>
                                    <li>Tugas & Hasil Ujian Digital</li>
                                </ul>
                            </div>
                            <div class="data-card">
                                <div class="data-card-header">
                                    <span class="material-symbols-outlined">badge</span>
                                    <h3>Data Guru & Staf</h3>
                                </div>
                                <ul>
                                    <li>Nama Lengkap & NIP</li>
                                    <li>Materi Pembelajaran & Kurikulum</li>
                                    <li>Log Aktivitas Pengajaran</li>
                                    <li>Data Komunikasi Internal</li>
                                </ul>
                            </div>
                        </div>
                        <p class="prose">Selain data profil, sistem kami juga mengumpulkan informasi teknis secara otomatis, termasuk alamat IP, tipe perangkat, dan data penggunaan platform untuk tujuan optimasi performa sistem.</p>
                    </section>

                    {{-- Section 3 --}}
                    <section id="penggunaan-data" class="content-section">
                        <h2><span class="section-number">03</span> Penggunaan Data Akademik</h2>
                        <div class="prose">
                            <p>Kami menggunakan data yang dikumpulkan semata-mata untuk kepentingan pendidikan, antara lain:</p>
                        </div>
                        <div class="usage-grid">
                            <div class="usage-card">
                                <span class="material-symbols-outlined">analytics</span>
                                <h4>Analisis Belajar</h4>
                                <p>Memantau perkembangan akademis siswa secara real-time.</p>
                            </div>
                            <div class="usage-card">
                                <span class="material-symbols-outlined">assignment_turned_in</span>
                                <h4>Administrasi</h4>
                                <p>Otomatisasi raport, absensi, dan penjadwalan kelas.</p>
                            </div>
                            <div class="usage-card">
                                <span class="material-symbols-outlined">groups</span>
                                <h4>Komunikasi</h4>
                                <p>Memfasilitasi interaksi antara guru, siswa, dan orang tua.</p>
                            </div>
                        </div>
                        <p class="prose-note">Kami tidak akan pernah menjual atau membagikan data pribadi Anda kepada pihak ketiga untuk tujuan komersial atau periklanan.</p>
                    </section>

                    {{-- Section 4 --}}
                    <section id="keamanan-data" class="content-section">
                        <h2><span class="section-number">04</span> Keamanan Data Institusi</h2>
                        <div class="prose">
                            <p>Keamanan data adalah prioritas utama kami. Kami menerapkan standar keamanan tingkat industri untuk melindungi data Anda dari akses yang tidak sah, perubahan, pengungkapan, atau penghancuran.</p>
                        </div>
                        <div class="security-list">
                            <div class="security-item">
                                <div class="security-icon">
                                    <span class="material-symbols-outlined">encrypted</span>
                                </div>
                                <div>
                                    <h4>Enkripsi End-to-End</h4>
                                    <p>Seluruh transmisi data dilakukan melalui protokol HTTPS/TLS yang terenkripsi.</p>
                                </div>
                            </div>
                            <div class="security-item">
                                <div class="security-icon">
                                    <span class="material-symbols-outlined">storage</span>
                                </div>
                                <div>
                                    <h4>Penyimpanan Server Lokal</h4>
                                    <p>Data disimpan di pusat data institusi dengan akses fisik yang diawasi ketat 24/7.</p>
                                </div>
                            </div>
                            <div class="security-item">
                                <div class="security-icon">
                                    <span class="material-symbols-outlined">key</span>
                                </div>
                                <div>
                                    <h4>Kontrol Akses Berlapis</h4>
                                    <p>Hanya staf yang berwenang dengan otentikasi dua faktor (2FA) yang dapat mengakses database sensitif.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Section 5 --}}
                    <section id="hak-pengguna" class="content-section">
                        <h2><span class="section-number">05</span> Hak Pengguna</h2>
                        <div class="prose">
                            <p>Berdasarkan regulasi perlindungan data yang berlaku, Anda memiliki hak-hak berikut:</p>
                            <ul class="rights-list">
                                <li><strong>Hak Akses:</strong> Meminta salinan data pribadi yang kami simpan.</li>
                                <li><strong>Hak Perbaikan:</strong> Meminta koreksi atas data yang tidak akurat atau tidak lengkap.</li>
                                <li><strong>Hak Penghapusan:</strong> Meminta penghapusan data setelah siswa lulus atau mengundurkan diri (sesuai aturan retensi sekolah).</li>
                                <li><strong>Hak Portabilitas:</strong> Meminta pemindahan data ke institusi pendidikan lain jika diperlukan.</li>
                            </ul>
                        </div>
                    </section>

                    {{-- Section 6 --}}
                    <section id="kontak" class="content-section contact-section">
                        <h2>Hubungi Tim Data Kami</h2>
                        <p>Jika Anda memiliki pertanyaan mengenai Kebijakan Privasi ini atau pengelolaan data Anda, silakan hubungi Tim Data Protection Officer (DPO) kami.</p>
                        <div class="contact-grid">
                            <div class="contact-item">
                                <span class="contact-label">Email</span>
                                <a href="support@juliet.co.id">support@juliet.co.id</a>
                            </div>
                            <div class="contact-item">
                                <span class="contact-label">Telepon</span>
                                <span>(+62) 857-0060-9999</span>
                            </div>
                            <div class="contact-item">
                                <span class="contact-label">Lokasi</span>
                                <span>Jl. Sono Kembang No.4-6, Embong Kaliasin, Kec. Genteng, Surabaya, Jawa Timur 60271</div>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}

     <footer class="about-footer">
        <div class="container">
            <div class="footer-grid-about">
                <div class="footer-brand-about">
                    <div class="footer-logo-about">
                        <img src="assets/horizontal-logo.png" alt="Logo">
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

    <script >
        /* ============================================================
   privacy-policy.js
   Smooth scroll & active sidebar navigation
   ============================================================ */

(function() {
    'use strict';

    // ══════════════════════════════════════════════════════════
    // SMOOTH SCROLL TO SECTIONS
    // ══════════════════════════════════════════════════════════
    const sidebarLinks = document.querySelectorAll('.sidebar-nav a');
    
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                const offset = 100; // Header height + padding
                const targetPosition = targetSection.offsetTop - offset;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ══════════════════════════════════════════════════════════
    // HIGHLIGHT ACTIVE SECTION ON SCROLL
    // ══════════════════════════════════════════════════════════
    const sections = document.querySelectorAll('.content-section');
    
    function highlightActiveSection() {
        const scrollPos = window.pageYOffset + 150;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                // Remove active from all links
                sidebarLinks.forEach(link => {
                    link.classList.remove('active');
                });
                
                // Add active to current section link
                const activeLink = document.querySelector(`.sidebar-nav a[href="#${sectionId}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }
        });
    }

    // Debounce scroll event
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            window.cancelAnimationFrame(scrollTimeout);
        }
        scrollTimeout = window.requestAnimationFrame(highlightActiveSection);
    }, { passive: true });

})();
    </script>
</body>
</html>