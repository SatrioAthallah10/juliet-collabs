<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Juliet</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
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

    <main class="about-main">
        <div class="container">

            {{-- Hero Section --}}
            <section class="about-hero">
                <div class="about-hero-grid">
                    <div class="hero-content">
                        <div class="hero-badge">
                            <span class="material-symbols-outlined">verified</span>
                            Transformasi Digital Pendidikan
                        </div>
                        <h1>Visi & Misi Juliet: <span class="highlight">Solusi Pendidikan</span> Modern</h1>
                        <p>Memberdayakan ekosistem sekolah melalui transformasi digital yang inklusif, efisien, dan berkelanjutan. Kami percaya bahwa setiap institusi pendidikan layak mendapatkan alat terbaik untuk mencerdaskan generasi bangsa.</p>
                        <div class="hero-actions">
                            <button class="btn-learn-more">
                                <a href="{{ url('/') }}">Pelajari Selengkapnya</a>
                            </button>
                            <div class="hero-trust">
                                <div class="trust-avatars">
                                    <div class="avatar">
                                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDwE37dB9MY3klRJVGtkKvWsMuOwiB6oNkaXydj5NkQseqMU88ntoke4FMC6DIZzwQni8_XuF6X1vhaa22v5-sfFqoN6nOMpijqVK3YdniInS6wkTFhrsbfBSho_IF78O63ntgsAZo1k3diN9qRSjXX9xU_YfGjxW1OMaj9-qUy1FcKHnmjM__WfRFnUOh-AsBpTak8RhV3psQBo6-zX4amC1AfkLNoasatZpZ1aTPAaz26ypO1V0vbWRkysU1nEH_4iSX_gJbrAbVT" alt="Educator">
                                    </div>
                                    <div class="avatar">
                                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAjuVVuXZ6LN3tcK7smEWvPvdzNWQdJkEu6pWIoMthdD501jwWzIqW2dVnZfsRC0cPJGb_55RwZ6sAP2yTOWxwgZ7kzTLJJmv2DIu7fbeRKoL46nxH6EYN5duzNllVnMyxWxUXVysAkAWsML0l0TcnKJFgwo7-SY9QerjuW5NDPUQtiz4Mck4WLh01MfTv8M2gs0i0wafn3bBtZLUZOr2Cxc0gPPGK1TD7gxkmyYwFMYhVgFr2S1Et_wpje7Z6gXMhUKW_CTI9cA80r" alt="Principal">
                                    </div>
                                    <div class="avatar count">+500</div>
                                </div>
                                <span>Dipercaya oleh 500+ Sekolah</span>
                            </div>
                        </div>
                    </div>
                    <div class="hero-image">
                        <div class="image-bg"></div>
                        <div class="image-wrapper">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBHS_Qxp69YHdM7N3AdpIYpeUJe1FsabfwBl8j70NsZO7FUGbS9VEtaD8AbJug0pIH8HLT4f1m6tSeyVVB3v-elGOxfkW6n-BZNgsFIoj2AzdCS7beHXCehc1f5k-jVaksMiKdYKyfHR-gCZsPnDrsbcmeh9Ds-wwuF-HP6If8LqSfRiGmKt3TynZ9oD-0VTuRqlqqpbWsE57Q5TvYEiM2TIObrzLMH7isyNqXWjH5em0UgY40HLp1C_c6ZdOpV2C1G12565fTySJU8" alt="Classroom">
                        </div>
                    </div>
                </div>
            </section>

            {{-- Our Story --}}
            <section class="about-story">
                <div class="story-content">
                    <h2>Kisah Kami</h2>
                    <div class="story-divider"></div>
                    <p class="story-quote">"Juliet lahir dari kebutuhan mendalam sekolah formal untuk bertransformasi ke era digital. Kami menjembatani kesenjangan antara manajemen tradisional dan efisiensi teknologi modern untuk menciptakan lingkungan belajar yang lebih baik bagi siswa, guru, dan orang tua."</p>
                    <p class="story-desc">Kami berfokus pada pembangunan infrastruktur tertutup (closed ecosystem) yang mengedepankan privasi data sekolah dan kemudahan penggunaan tanpa fitur marketplace yang mengalihkan fokus akademik.</p>
                </div>
            </section>

            {{-- Core Values --}}
            <section class="about-values">
                <div class="section-header">
                    <h2>Nilai-Nilai Utama Kami</h2>
                    <p>Prinsip dasar yang membimbing setiap baris kode dan layanan yang kami berikan untuk kemajuan pendidikan.</p>
                </div>
                <div class="values-grid">
                    <div class="value-card">
                        <div class="value-icon">
                            <span class="material-symbols-outlined">work</span>
                        </div>
                        <h3>Profesionalisme</h3>
                        <p>Komitmen tinggi dalam memberikan layanan teknis dan edukasi yang terstandarisasi untuk kenyamanan sekolah.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <span class="material-symbols-outlined">security</span>
                        </div>
                        <h3>Keamanan Data</h3>
                        <p>Ekosistem tertutup yang menjamin kerahasiaan data siswa dan rekam medis akademik sekolah secara aman.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <span class="material-symbols-outlined">emoji_events</span>
                        </div>
                        <h3>Keunggulan Akademik</h3>
                        <p>Alat evaluasi dan kurikulum digital yang dirancang untuk meningkatkan standar prestasi akademik secara terukur.</p>
                    </div>
                </div>
            </section>

            {{-- Institutional Impact --}}
            <section class="about-impact">
                <div class="impact-bg"></div>
                <div class="impact-grid">
                    <div class="impact-stat">
                        <div class="stat-number">90%</div>
                        <div class="stat-label">Proses Administrasi Lebih Efisien</div>
                    </div>
                    <div class="impact-stat">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Data Sekolah Terkelola Terpusat</div>
                    </div>
                    <div class="impact-stat">
                        <div class="stat-number">85%</div>
                        <div class="stat-label">Pengurangan Penggunaan Sistem Manual</div>
                    </div>
                    <div class="impact-stat">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Akses Internal Khusus Institusi</div>
                    </div>
                </div>
            </section>

            {{-- Our Team --}}
            <section class="about-team">
                <div class="team-header">
                    <div>
                        <h2>Tim Kepemimpinan Kami</h2>
                        <p>Digerakkan oleh para ahli di bidang pendidikan dan teknologi yang berdedikasi tinggi.</p>
                    </div>
                    <button class="btn-view-all">
                        Lihat Semua Tim
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </div>
                <div class="team-grid">
                    <div class="team-card">
                        <div class="team-image">
                            <img src="assets/about-us-team.png" alt="CEO">
                            <div class="team-overlay"></div>
                        </div>
                        <h4>Anto Arifanto</h4>
                        <p>Founder / CEO</p>
                    </div>
                    <div class="team-card">
                        <div class="team-image">
                            <img src="assets/about-us-team.png" alt="Direktur">
                            <div class="team-overlay"></div>
                        </div>
                        <h4>Dadang Budi Setiyobudi</h4>
                        <p>Co-Founder / CTO</p>
                    </div>
                    <div class="team-card">
                        <div class="team-image">
                            <img src="assets/about-us-team.png" alt="CTO">
                            <div class="team-overlay"></div>
                        </div>
                        <h4>Arief Maulana</h4>
                        <p>CMO</p>
                    </div>
                    <div class="team-card">
                        <div class="team-image">
                            <img src="assets/about-us-team.png" alt="Kepala">
                            <div class="team-overlay"></div>
                        </div>
                        <h4>Arief Soetrisno</h4>
                        <p>CFO</p>
                    </div>
                </div>
            </section>

            {{-- CTA Section --}}
            <section class="about-cta">
                <div class="cta-card-about">
                    <h2>Siap Transformasi Sekolah Anda?</h2>
                    <p>Gabung dengan ekosistem Juliet dan mulai perjalanan digitalisasi pendidikan yang aman dan terarah hari ini.</p>
                    <div class="cta-buttons-about">
                        <button class="btn-cta-primary">
                             <a href="tel:+085700609999" class="cta-info-card">Hubungi Tim Sales
                             </a>
                        </button>
                        <!-- <button class="btn-cta-secondary">Lihat Demo</button> -->
                    </div>
                </div>
            </section>

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

</body>
</html>