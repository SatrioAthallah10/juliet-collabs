<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Jurnal Lintas Elektronik Terpadu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/home_page/css/style.css') }}" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="shortcut icon" href="assets/vertical-logo.png" type="image/x-icon">
</head>
<body>

    {{-- ===== NAVBAR ===== --}}
    <nav class="navbar">
        <div class="container">
            <div class="navbar-inner">
                <div class="brand">
                    <img src="{{ asset('assets/landing_page_images/Logo.png') }}" width="100px" alt="logo">
                </div>
                <div class="nav-links">
                    <a href="{{ url('/') }}">Beranda</a>
                    <a href="{{ url('about-us') }}">About us</a>
                    <a href="#fitur">Fitur</a>
                    <a href="#harga">Harga</a>
                    <a href="#contact">Contact</a>
                </div>
                <div class="nav-actions">
                    <a class="btn-login" href="{{ route('login') }}">Masuk</a>
                    <a class="btn-login" href="{{ route('register') }}">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ===== HERO SECTION ===== --}}
    <section class="hero" id="beranda">
        <div class="hero-bg-right"></div>
        <div class="hero-bg-left"></div>
        <div class="container">
            <div class="hero-grid">
                <div class="hero-copy">
                    <!-- <div class="badge">
                        <span class="material-symbols-outlined">verified</span>
                        <span>Dipercaya oleh 500+ Institusi</span>
                    </div> -->
                    <h1>
                        Transformasi Manajemen Sekolah Bersama Juliet                     </h1>
                    <p class="hero-desc">
                        Wujudkan masa depan pendidikan dengan platform Juliet kami. Sederhanakan absensi, tugas, ujian, dan banyak lagi. Tingkatkan efisiensi dan keterlibatan sekolah Anda.
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ url('register') }}" class="btn-hero">
                            {{ __('Daftarkan sekolah anda') }}
                        </a>
                    </div>
                </div>
                <div class="hero-image-wrap">
                    <div class="hero-card">
                        <img src="assets/landing_page_images/hero.png"
                             alt="Siswa berkolaborasi di kelas modern"/>
                        <div class="attendance-badge">
                            <div class="attendance-icon">
                                <span class="material-symbols-outlined">check_circle</span>
                            </div>
                            <!-- <div>
                                <p class="attendance-label">Kehadiran Hari Ini</p>
                                <p class="attendance-value">98,5% Hadir</p>
                            </div> -->
                        </div>
                    </div>
                    <div class="blob blob-purple"></div>
                    <div class="blob blob-accent"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== INSTITUTIONAL INFO SECTION ===== --}}
    <section class="info-section" id="about-us">
        <div class="container text-center">
            <span class="section-label">Mengapa kami yang terbaik?
</span>
            <h2>Platform Terintegrasi untuk Pendidikan Formal</h2>
            <p class="info-desc">
              Juliet menghadirkan sistem digital terintegrasi yang membantu sekolah mengelola pembelajaran dan manajemen akademik secara lebih efektif dan terstruktur
            </p>
        </div>
    </section>

    {{-- ===== FEATURES SECTION ===== --}}
    <section class="features-section" id="fitur">
        <div class="container">
            <div class="section-header">
                <h2>Alat Akademik Komprehensif</h2>
                <p>Semua yang dibutuhkan fakultas dan administrasi Anda dalam satu tempat.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card group">
                    <div class="feature-icon icon-purple">
                        <span class="material-symbols-outlined">library_books</span>
                    </div>
                    <h3>Manajemen Kurikulum</h3>
                    <p>Repositori terpusat untuk perencanaan silabus, memastikan konsistensi di semua tingkat kelas dan departemen.</p>
                </div>
                <div class="feature-card group">
                    <div class="feature-icon icon-pink">
                        <span class="material-symbols-outlined">cast_for_education</span>
                    </div>
                    <h3>Materi yang Dipimpin Guru</h3>
                    <p>Berdayakan pendidik untuk mengunggah, mengelola, dan mendistribusikan rencana pelajaran, video, dan bahan bacaan dengan mudah.</p>
                </div>
                <div class="feature-card group">
                    <div class="feature-icon icon-indigo">
                        <span class="material-symbols-outlined">assignment_turned_in</span>
                    </div>
                    <h3>Tugas &amp; Penilaian</h3>
                    <p>Portal pengumpulan tugas yang efisien untuk siswa dan buku nilai terintegrasi bagi guru dengan alat umpan balik instan.</p>
                </div>
                <div class="feature-card group">
                    <div class="feature-icon icon-blue">
                        <span class="material-symbols-outlined">fact_check</span>
                    </div>
                    <h3>Pelacakan Kehadiran</h3>
                    <p>Pencatatan kehadiran digital yang secara otomatis memperbarui catatan siswa dan memberi tahu orang tua tentang ketidakhadiran.</p>
                </div>
                <div class="feature-card group">
                    <div class="feature-icon icon-amber">
                        <span class="material-symbols-outlined">insights</span>
                    </div>
                    <h3>Pemantauan Kinerja</h3>
                    <p>Dasbor analitik canggih bagi kepala sekolah dan kepala departemen untuk melacak kemajuan dan hasil akademik.</p>
                </div>
                <div class="feature-card group">
                    <div class="feature-icon icon-teal">
                        <span class="material-symbols-outlined">admin_panel_settings</span>
                    </div>
                    <h3>Akun Aman</h3>
                    <p>Kontrol akses berbasis peran dengan keamanan tingkat enterprise untuk melindungi data sensitif siswa dan staf.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== TOOLS CHIP CAROUSEL SECTION ===== --}}
    @php
        $tools = collect([
            ['label' => 'Manajemen Siswa',               'icon' => 'groups'],
            ['label' => 'Manajemen Akademik',             'icon' => 'menu_book'],
            ['label' => 'Manajemen Slider',               'icon' => 'view_carousel'],
            ['label' => 'Manajemen Guru',                 'icon' => 'cast_for_education'],
            ['label' => 'Manajemen Tahun Ajaran',         'icon' => 'calendar_month'],
            ['label' => 'Manajemen Hari Libur',           'icon' => 'beach_access'],
            ['label' => 'Manajemen Jadwal Pelajaran',     'icon' => 'schedule'],
            ['label' => 'Manajemen Absensi',              'icon' => 'fact_check'],
            ['label' => 'Manajemen Ujian',                'icon' => 'edit_document'],
            ['label' => 'Manajemen Pelajaran',            'icon' => 'library_books'],
            ['label' => 'Manajemen Tugas',                'icon' => 'assignment'],
            ['label' => 'Manajemen Pengumuman',           'icon' => 'campaign'],
            ['label' => 'Manajemen Staf',                 'icon' => 'badge'],
            ['label' => 'Manajemen Pengeluaran',          'icon' => 'receipt_long'],
            ['label' => 'Manajemen Cuti Staf',            'icon' => 'event_busy'],
            ['label' => 'Manajemen Biaya',                'icon' => 'payments'],
            ['label' => 'Manajemen Galeri Sekolah',       'icon' => 'photo_library'],
            ['label' => 'Pembuatan ID Card & Sertifikat', 'icon' => 'id_card'],
            ['label' => 'Manajemen Website',              'icon' => 'language'],
            ['label' => 'Modul Chat',                     'icon' => 'chat'],
            ['label' => 'Modul Transportasi',             'icon' => 'directions_bus'],
            ['label' => 'Manajemen Absensi Staf',         'icon' => 'how_to_reg'],
        ]);
        $track1 = $tools->filter(fn($t, $i) => $i % 2 === 0)->values();
        $track2 = $tools->filter(fn($t, $i) => $i % 2 !== 0)->values();
    @endphp

    <section class="tools-carousel-section">
        <div class="tools-carousel-header">
            <span class="section-label">Fitur Lengkap</span>
            <p>{{ count($tools) }} modul pengelolaan sekolah dalam satu platform</p>
        </div>
        <div class="tools-track-wrap">
            <div class="tools-track tools-track--right">
                @foreach ($track1 as $tool)
                    <div class="tool-chip"><span class="material-symbols-outlined">{{ $tool['icon'] }}</span>{{ $tool['label'] }}</div>
                @endforeach
                @foreach ($track1 as $tool)
                    <div class="tool-chip" aria-hidden="true"><span class="material-symbols-outlined">{{ $tool['icon'] }}</span>{{ $tool['label'] }}</div>
                @endforeach
            </div>
        </div>
        <div class="tools-track-wrap">
            <div class="tools-track tools-track--left">
                @foreach ($track2 as $tool)
                    <div class="tool-chip tool-chip--accent"><span class="material-symbols-outlined">{{ $tool['icon'] }}</span>{{ $tool['label'] }}</div>
                @endforeach
                @foreach ($track2 as $tool)
                    <div class="tool-chip tool-chip--accent" aria-hidden="true"><span class="material-symbols-outlined">{{ $tool['icon'] }}</span>{{ $tool['label'] }}</div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== HOW IT WORKS SECTION ===== --}}
    <section class="workflow-section" id="cara-kerja">
        <div class="container">
            <div class="section-header">
                <span class="section-label-accent">Alur Kerja</span>
                <h2>Cara Kerja</h2>
            </div>
            <div class="workflow-steps">
                <div class="workflow-line"></div>
                <div class="step">
                    <div class="step-icon step-outline"><span class="material-symbols-outlined">edit_note</span><span class="step-number step-number-outline">1</span></div>
                    <h4>Siapkan Materi</h4><p>Guru mengatur rencana pelajaran dan sumber daya.</p>
                </div>
                <div class="step">
                    <div class="step-icon step-outline"><span class="material-symbols-outlined">school</span><span class="step-number step-number-outline">2</span></div>
                    <h4>Akses Pelajaran</h4><p>Siswa masuk untuk melihat konten dengan aman.</p>
                </div>
                <div class="step">
                    <div class="step-icon step-outline"><span class="material-symbols-outlined">upload_file</span><span class="step-number step-number-outline">3</span></div>
                    <h4>Kumpulkan Tugas</h4><p>Tugas diunggah secara digital.</p>
                </div>
                <div class="step">
                    <div class="step-icon step-outline"><span class="material-symbols-outlined">grading</span><span class="step-number step-number-outline">4</span></div>
                    <h4>Evaluasi</h4><p>Guru menilai dan memberikan umpan balik.</p>
                </div>
                <div class="step">
                    <div class="step-icon step-outline"><span class="material-symbols-outlined">bar_chart</span><span class="step-number step-number-outline">5</span></div>
                    <h4>Catat Kemajuan</h4><p>Sistem memperbarui catatan akademik secara otomatis.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== PRICING SECTION ===== --}}
    <section class="pricing-section" id="harga">
        <div class="container">
            <div class="section-header">
                <h2>Paket Institusi yang Fleksibel</h2>
                <p>Pilih skala penerapan yang sesuai untuk sekolah Anda.</p>
            </div>
            <div class="pricing-grid">

                @php
                    // Map packages to static cards. 
                    // Try to finding by name first, otherwise fallback to rank order.
                    // Assuming $packages is ordered by rank ASC.
                    $silverPackage = $packages->first(function($p) { return stripos($p->name, 'Silver') !== false; }) ?? $packages->get(0);
                    $goldPackage = $packages->first(function($p) { return stripos($p->name, 'Gold') !== false; }) ?? $packages->get(1);
                    $platinumPackage = $packages->first(function($p) { return stripos($p->name, 'Platinum') !== false; }) ?? $packages->get(2);
                @endphp

                {{-- Kiri --}}
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Silver</h3>
                        <p>Akses LMS selama 30 hari</p>
                    </div>
                    <div class="pricing-price">Rp 500.000<span>/30 hari</span></div>
                    <ul class="pricing-features">
                        <li><span class="material-symbols-outlined">check</span>Akses semua materi kelas</li>
                        <li><span class="material-symbols-outlined">check</span>Upload & submit tugas</li>
                        <li><span class="material-symbols-outlined">check</span>Sistem penilaian otomatis</li>
                        <li><span class="material-symbols-outlined">check</span>Dukungan email</li>
                    </ul>
                    <a href="{{ $silverPackage ? url('register') . '?package=' . $silverPackage->id : '#' }}" class="pricingBtn">
                        {{ __('get_started') }}
                    </a>
                </div>


                {{-- Tengah --}}
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Gold</h3>
                        <p>Akses LMS selama 180 hari</p>
                    </div>
                    <div class="pricing-price">Rp 3.000.000<span>/180 hari</span></div>
                    <ul class="pricing-features">
                        <li><span class="material-symbols-outlined">check</span>Semua fitur Silver</li>
                        <li><span class="material-symbols-outlined">check</span>Monitoring progres belajar</li>
                        <li><span class="material-symbols-outlined">check</span>Rekap nilai & laporan akademik</li>
                        <li><span class="material-symbols-outlined">check</span>Dukungan prioritas</li>
                    </ul>
                    <a href="{{ $goldPackage ? url('register') . '?package=' . $goldPackage->id : '#' }}" class="pricingBtn">
                        {{ __('get_started') }}
                    </a>
                </div>


                {{-- Kanan --}}
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Platinum</h3>
                        <p>Akses LMS selama 365 hari</p>
                    </div>
                    <div class="pricing-price">Rp 6.000.000<span>/365 hari</span></div>
                    <ul class="pricing-features">
                        <li><span class="material-symbols-outlined">check</span>Semua fitur Gold</li>
                        <li><span class="material-symbols-outlined">check</span>Analitik performa siswa</li>
                        <li><span class="material-symbols-outlined">check</span>Export laporan akademik (PDF/Excel)</li>
                        <li><span class="material-symbols-outlined">check</span>Dukungan teknis dedicated</li>
                    </ul>
                    <a href="{{ $platinumPackage ? url('register') . '?package=' . $platinumPackage->id : '#' }}" class="pricingBtn">
                        {{ __('get_started') }}
                    </a>
                </div>




            </div>
        </div>
    </section>

    {{-- ===== CTA CONTACT SECTION ===== --}}
    <section class="cta-contact-section" id="contact">
        <div class="container">
            <div class="cta-contact-header">
                <span class="section-label-accent">Hubungi Kami</span>
                <h2>Siap Mendigitalkan Sekolah Anda?</h2>
                <p>Kirimkan pesan dan tim kami akan menghubungi Anda dalam 1&times;24 jam.</p>
            </div>
            <div class="cta-contact-grid">
                <div class="cta-info-col">
                    <a href="mailto:support@juliet.co.id" class="cta-info-card">
                        <div class="cta-info-icon icon-email"><span class="material-symbols-outlined">email</span></div>
                        <div class="cta-info-text">
                            <span class="cta-info-label">Email Kami</span>
                            <span class="cta-info-value">support@juliet.co.id</span>
                            <span class="cta-info-action">Klik untuk kirim email <span class="material-symbols-outlined">arrow_forward</span></span>
                        </div>
                    </a>
                    <a href="tel:+085700609999" class="cta-info-card">
                        <div class="cta-info-icon icon-phone"><span class="material-symbols-outlined">phone_in_talk</span></div>
                        <div class="cta-info-text">
                            <span class="cta-info-label">Telepon Kami</span>
                            <span class="cta-info-value">+62 857 0060 9999</span>
                            <span class="cta-info-action">Klik untuk menelepon <span class="material-symbols-outlined">arrow_forward</span></span>
                        </div>
                    </a>
                    <a href="Jl. Sono Kembang No.4-6, Embong Kaliasin, Kec. Genteng, Surabaya, Jawa Timur 60271" target="_blank" rel="noopener" class="cta-info-card">
                        <div class="cta-info-icon icon-location"><span class="material-symbols-outlined">location_on</span></div>
                        <div class="cta-info-text">
                            <span class="cta-info-label">Kunjungi Kantor</span>
                            <span class="cta-info-value">Jl. Sono Kembang No.4-6, Embong Kaliasin, Kec. Genteng, Surabaya, Jawa Timur 60271</span>
                            <span class="cta-info-action">Buka di Google Maps <span class="material-symbols-outlined">arrow_forward</span></span>
                        </div>
                    </a>
                    <a href="https://wa.me/6285700609999" target="_blank" rel="noopener" class="cta-info-card cta-info-card-wa">
                        <div class="cta-info-icon icon-wa">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div class="cta-info-text">
                            <span class="cta-info-label">WhatsApp</span>
                            <span class="cta-info-value">Chat langsung dengan tim</span>
                            <span class="cta-info-action">Buka WhatsApp <span class="material-symbols-outlined">arrow_forward</span></span>
                        </div>
                    </a>
                </div>
                <div class="cta-form-col">
                    <div class="cta-form-card">
                        <div class="cta-form-header">
                            <h3>Kirim Pesan</h3>
                            <p>Isi formulir di bawah ini dan kami akan segera merespons.</p>
                        </div>
                        <form class="cta-form" id="ctaForm" novalidate>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="nama">Nama Lengkap <span class="required">*</span></label>
                                    <div class="input-wrap"><span class="material-symbols-outlined input-icon">person</span><input type="text" id="nama" name="nama" placeholder="Contoh: Budi Santoso" required /></div>
                                    <span class="field-error">Nama wajib diisi.</span>
                                </div>
                                <div class="form-group">
                                    <label for="institusi">Nama Institusi <span class="required">*</span></label>
                                    <div class="input-wrap"><span class="material-symbols-outlined input-icon">school</span><input type="text" id="institusi" name="institusi" placeholder="Contoh: SMA Negeri 1 Jakarta" required /></div>
                                    <span class="field-error">Institusi wajib diisi.</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email <span class="required">*</span></label>
                                    <div class="input-wrap"><span class="material-symbols-outlined input-icon">email</span><input type="email" id="email" name="email" placeholder="email@sekolah.ac.id" required /></div>
                                    <span class="field-error">Email tidak valid.</span>
                                </div>
                                <div class="form-group">
                                    <label for="telepon">Nomor Telepon</label>
                                    <div class="input-wrap"><span class="material-symbols-outlined input-icon">phone</span><input type="tel" id="telepon" name="telepon" placeholder="+62 8xx xxxx xxxx" /></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="topik">Topik Pertanyaan</label>
                                <div class="input-wrap input-wrap-select"><span class="material-symbols-outlined input-icon">category</span>
                                    <select id="topik" name="topik">
                                        <option value="">-- Pilih topik --</option>
                                        <option value="demo">Permintaan Demo</option>
                                        <option value="harga">Informasi Harga</option>
                                        <option value="implementasi">Konsultasi Implementasi</option>
                                        <option value="teknis">Dukungan Teknis</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pesan">Pesan <span class="required">*</span></label>
                                <div class="input-wrap input-wrap-textarea"><span class="material-symbols-outlined input-icon input-icon-top">chat</span><textarea id="pesan" name="pesan" rows="4" placeholder="Tuliskan pesan atau pertanyaan Anda di sini..." required maxlength="500"></textarea></div>
                                <div class="char-counter"><span id="charCount">0</span> / 500 karakter</div>
                                <span class="field-error">Pesan wajib diisi.</span>
                            </div>
                            <button type="submit" class="btn-form-submit" id="submitBtn">
                                <span class="btn-label"><span class="material-symbols-outlined">send</span>Kirim Pesan</span>
                            </button>
                        </form>
                        <div class="form-success" id="formSuccess" hidden>
                            <div class="success-icon"><span class="material-symbols-outlined">check_circle</span></div>
                            <h4>Pesan Terkirim!</h4>
                            <p>Terima kasih, <strong id="successName"></strong>. Tim kami akan menghubungi Anda dalam 1&times;24 jam.</p>
                            <button class="btn-reset" id="resetForm">Kirim Pesan Lain</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
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

    {{-- ===== SCRIPTS ===== --}}
    <script>
        /* ---- Smooth scroll ---- */
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                const target = document.querySelector(targetId);
                if (!target) return;
                e.preventDefault();
                const navH = document.querySelector('.navbar').offsetHeight;
                window.scrollTo({ top: target.getBoundingClientRect().top + window.pageYOffset - navH - 12, behavior: 'smooth' });
            });
        });

        /* ---- Active nav on scroll ---- */
        const sections = document.querySelectorAll('section[id], footer[id]');
        const navLinks = document.querySelectorAll('.nav-links a');
        const onScroll = () => {
            const navH = document.querySelector('.navbar').offsetHeight;
            let current = '';
            sections.forEach(sec => { if (window.pageYOffset >= sec.offsetTop - navH - 32) current = sec.getAttribute('id'); });
            navLinks.forEach(link => link.classList.toggle('active', link.getAttribute('href') === '#' + current));
        };
        
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
</body>
</html>