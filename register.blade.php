<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Juliet || Registration</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/home_page/css/registration-page.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="assets/vertical-logo.png" type="image/x-icon">
</head>
<body>

    {{-- Top Header --}}
    <header class="reg-top-header">
        <div class="container">
            <div class="header-inner">
                <img src="assets/horizontal-logo.png" alt="logo">
                <!-- <span class="material-symbols-outlined">close</span> -->
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="reg-main">
        <div class="container">
            <div class="reg-grid">

                {{-- Sidebar --}}
                <aside class="reg-sidebar">

                    {{-- Benefits Card --}}
                    <div class="benefits-card">
                        <h2>Mengapa bergabung dengan jaringan institusi kami?</h2>
                        <ul class="benefits-list">
                            <li class="benefit-item">
                                <div class="benefit-icon">
                                    <span class="material-symbols-outlined">security</span>
                                </div>
                                <div class="benefit-content">
                                    <h4>Keamanan Data Terjamin</h4>
                                    <p>Setiap institusi memiliki sistem terisolasi dan terenkripsi untuk memastikan data siswa dan tenaga pendidik tetap aman dan tidak tercampur.</p>
                                </div>
                            </li>
                            <li class="benefit-item">
                                <div class="benefit-icon">
                                    <span class="material-symbols-outlined">account_tree</span>
                                </div>
                                <div class="benefit-content">
                                    <h4>Selaras dengan Kurikulum</h4>
                                    <p>Mudah menyesuaikan kurikulum nasional maupun internal sekolah dengan kerangka pembelajaran terstruktur kami.</p>
                                </div>
                            </li>
                            <li class="benefit-item">
                                <div class="benefit-icon">
                                    <span class="material-symbols-outlined">support_agent</span>
                                </div>
                                <div class="benefit-content">
                                    <h4>Dukungan Prioritas</h4>
                                    <p>Tim support khusus siap membantu kebutuhan teknis sekolah dan administrator secara responsif.</p>
                                </div>
                            </li>
                        </ul>

                        {{-- Notice Box --}}
                        <div class="notice-box">
                            <p class="notice-label">Pemberitahuan</p>
                            <p class="notice-text">
                                "Portal ini ditujukan khusus untuk pendaftaran institusi/sekolah. Pendaftaran siswa secara individu harus dilakukan melalui institusi masing-masing."
                            </p>
                        </div>
                    </div>

                    {{-- Image Card --}}
                    <div class="image-card">
                        <img src="assets/regis_page.png" alt="Tampilan sekolah modern">
                        <div class="image-overlay">
                            <p>Bergabung bersama 500+ sekolah yang telah mempercayai platform kami.</p>
                        </div>
                    </div>

                </aside>

                {{-- Form Section --}}
                <section class="reg-form-section">
                    <div class="form-card">

                        {{-- Form Header --}}
                        <div class="form-header">
                            <h2 class="form-header__title">Pendaftaran Institusi Sekolah</h2>
                            <p class="form-header__subtitle">Lengkapi formulir di bawah ini untuk memulai digitalisasi sekolah Anda.</p>
                        </div>

                        {{-- Form --}}
                        <form class="registration-form" action="{{ url('schools/registration') }}" method="POST">
                            @csrf

                            {{-- Section Header --}}
                            <div class="form-section-header">
                                <div class="section-accent"></div>
                                <h2>Create School</h2>
                            </div>

                            {{-- Form Fields --}}
                            <div class="form-grid">

                                <div class="form-group">
                                    <label>Name <span class="required">*</span></label>
                                    <input type="text" name="school_name" placeholder="Enter Your School Name" required>
                                </div>

                                <div class="form-group">
                                    <label>Email <span class="required">*</span></label>
                                    <input type="email" name="school_email" placeholder="Enter Your School Email" required>
                                    <p class="field-note">
                                        <span class="material-symbols-outlined" style="font-size:16px; vertical-align:middle;">info</span>
                                        Password akun akan dikirim ke email ini setelah proses pembayaran berhasil.
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label>Mobile <span class="required">*</span></label>
                                    <input type="tel" name="school_phone" placeholder="Enter Your School Mobile Number"
                                           maxlength="15" pattern="[0-9]{6,15}" required>
                                </div>

                                <div class="form-group">
                                    <label>Address <span class="required">*</span></label>
                                    <input type="text" name="school_address" placeholder="Enter Your School Address" required>
                                </div>

                                <div class="form-group full-width">
                                    <label>Tagline <span class="required">*</span></label>
                                    <input type="text" name="school_tagline" placeholder="Tagline" required>
                                </div>

                                <div class="form-group full-width">
                                    <label>Package <span class="required">*</span></label>
                                    <p class="field-note">
                                        <span class="material-symbols-outlined" style="font-size:16px; vertical-align:middle;">info</span>
                                        Silahkan pilih paket yang sesuai dengan kebutuhan sekolah Anda.

                                    </p>
                                    <div class="package-grid">

                                        <label class="package-card">
                                            <input type="radio" name="package_id" value="silver" required>
                                            <div class="package-card__inner">
                                                <span class="package-card__badge">Silver</span>
                                                <p class="package-card__price">Rp 500.000<span>/30Hari</span></p>
                                                <ul class="package-card__features">
                                                    <li>Akses semua Materi Kelas</li>
                                                    <li>Sistem Penilaian Otomatis</li>
                                                    <li>Laporan Hasil Belajar</li>
                                                </ul>
                                            </div>
                                        </label>

                                        <label class="package-card">
                                            <input type="radio" name="package_id" value="gold">
                                            <div class="package-card__inner">
                                                <span class="package-card__badge popular">Gold</span>
                                                <p class="package-card__price">Rp 3.000.000<span>/180Hari</span></p>
                                                <ul class="package-card__features">
                                                    <li>Semua fitur Silver </li>
                                                    <li>Monitoring Progres Belajar</li>
                                                    <li>Rekap nilai & laporan</li>
                                                </ul>
                                            </div>
                                        </label>

                                        <label class="package-card">
                                            <input type="radio" name="package_id" value="platinum">
                                            <div class="package-card__inner">
                                                <span class="package-card__badge">Platinum</span>
                                                <p class="package-card__price">Rp 6.000.000<span>/365Hari</span></p>
                                                <ul class="package-card__features">
                                                    <li>Semua fitur Gold</li>
                                                    <li>Analitik performa siswa</li>
                                                    <li>Dukungan teknis dedicated</li>
                                                </ul>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            {{-- Submit --}}
                            <div class="form-footer">
                                <button type="submit" class="submit-btn">
                                    <span class="material-symbols-outlined">check_circle</span>
                                    Submit
                                </button>
                            </div>
                            

                        </form>

                    </div>
                </section>

            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="reg-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <span class="material-symbols-outlined">school</span>
                    <span>EduCore</span>
                </div>
                <nav class="footer-nav">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Global Support</a>
                    <a href="#">Contact Admin</a>
                </nav>
                <p class="footer-copyright">© 2024 EduCore LMS. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        (function () {
            'use strict';

            const form = document.querySelector('.registration-form');

            // ── Validation on submit ──
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const inputs = form.querySelectorAll('input[required], select[required]');
                    let isValid = true;

                    inputs.forEach(function (input) {
                        if (!input.value.trim()) {
                            isValid = false;
                            input.style.borderColor = '#ef4444';
                        } else {
                            input.style.borderColor = '#E5C8E2';
                        }
                    });

                    if (isValid) {
                        form.submit();
                    } else {
                        alert('Please fill in all required fields.');
                    }
                });

                // Reset border on input
                form.querySelectorAll('input, select').forEach(function (input) {
                    input.addEventListener('input', function () {
                        this.style.borderColor = '#E5C8E2';
                    });
                });
            }

            // ── Phone: numbers only ──
            const phoneInput = document.querySelector('input[type="tel"]');
            if (phoneInput) {
                phoneInput.addEventListener('input', function () {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }

            // ── Scroll to top on load ──
            window.addEventListener('load', function () {
                window.scrollTo(0, 0);
            });

            // ── Close button confirmation ──
            const closeBtn = document.querySelector('.close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const nameInput = form && form.querySelector('input[name="school_name"]');
                    if (nameInput && nameInput.value) {
                        if (!confirm('Are you sure you want to leave? Your progress will be lost.')) {
                            return;
                        }
                    }
                    window.location.href = this.getAttribute('href');
                });
            }

        })();

        // ── Package Card Selection ──
        document.querySelectorAll('.package-card').forEach(function (card) {
            card.addEventListener('click', function () {
                document.querySelectorAll('.package-card').forEach(function (c) {
                    c.classList.remove('selected');
                });
                this.classList.add('selected');
            });
        });
    </script>

</body>
</html>