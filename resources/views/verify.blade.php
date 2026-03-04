<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Konfirmasi Pembayaran - Juliet</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --pink:      #e8257d;
            --magenta:   #c2185b;
            --violet:    #7b1fa2;
            --deep:      #4a148c;
            --grad:      linear-gradient(135deg, #f72585 0%, #9c1ab1 50%, #4a148c 100%);
            --grad2:      linear-gradient(135deg, #fe6daeff 0%, #9c1ab1 50%, #340969ff 100%);
            --grad-soft: linear-gradient(135deg, rgba(247,37,133,.12) 0%, rgba(74,20,140,.12) 100%);
            --surface:   #ffffff;
            --muted:     #9e7ab5;
            --border:    #f0e8f7;
            --text:      #1a0933;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--grad2);
            min-height: 100vh;
            padding: 24px 16px 48px;
            position: relative;
            overflow-x: hidden;
        }

        /* ── Ambient blobs ── */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }
        body::before {
            width: 500px; height: 500px;
            background: rgba(247,37,133,.35);
            top: -120px; right: -120px;
        }
        body::after {
            width: 400px; height: 400px;
            background: rgba(74,20,140,.4);
            bottom: -100px; left: -100px;
        }

        /* ── Container ── */
        .payment-container {
            max-width: 780px;
            margin: 32px auto;
            background: var(--surface);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(74,20,140,.45), 0 0 0 1px rgba(255,255,255,.1);
            position: relative;
            z-index: 1;
        }

        /* ── Header ── */
        .payment-header {
            background: var(--grad);
            color: white;
            padding: 48px 36px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .payment-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='30' cy='30' r='28' fill='none' stroke='rgba(255,255,255,.06)' stroke-width='1'/%3E%3C/svg%3E") center / 60px 60px;
        }
        .payment-header .logo {
            width: 52px; height: 52px;
            background: rgba(255,255,255,.2);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 22px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.25);
        }
        .payment-header h1 {
            font-size: 26px; font-weight: 800;
            letter-spacing: -.3px; margin-bottom: 10px;
        }
        .payment-header p { font-size: 15px; opacity: .88; }
        .status-badge {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,.18);
            padding: 8px 20px; border-radius: 50px;
            font-size: 13px; font-weight: 600; margin-top: 18px;
            border: 1px solid rgba(255,255,255,.25);
            backdrop-filter: blur(6px);
        }

        /* ── Timer ── */
        .timer-section {
            background: linear-gradient(90deg, #fdf2ff 0%, #fce4ff 100%);
            padding: 18px 30px;
            text-align: center;
            border-bottom: 2px solid #f0d6ff;
        }
        .timer-label { font-size: 12px; color: var(--violet); font-weight: 600; letter-spacing: .5px; text-transform: uppercase; margin-bottom: 6px; }
        .timer { font-size: 34px; font-weight: 800; color: var(--deep); letter-spacing: 2px; }

        /* ── Alert ── */
        .alert-section {
            padding: 24px 30px;
            background: linear-gradient(135deg, #fff8fe 0%, #fdf0ff 100%);
            border-left: 4px solid var(--pink);
        }
        .alert-icon { font-size: 22px; color: var(--pink); margin-right: 14px; flex-shrink: 0; margin-top: 2px; }
        .alert-section h5 { color: var(--deep); font-weight: 700; margin-bottom: 14px; font-size: 15px; }
        .alert-section li { color: var(--violet); margin-bottom: 9px; line-height: 1.65; font-size: 14px; }
        .alert-section li strong { color: var(--deep); }

        /* ── Info Section ── */
        .info-section { padding: 28px; }

        .info-card {
            background: #faf6ff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 18px;
            border: 1.5px solid var(--border);
        }
        .info-card h6 {
            color: var(--pink);
            font-weight: 700;
            margin-bottom: 18px;
            font-size: 15px;
            display: flex; align-items: center; gap: 9px;
        }
        .info-card h6 i {
            width: 32px; height: 32px;
            background: var(--grad-soft);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; color: var(--violet);
        }

        .info-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 13px 0; border-bottom: 1px solid var(--border);
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: var(--muted); font-weight: 500; font-size: 14px; }
        .info-value { color: var(--text); font-weight: 600; text-align: right; font-size: 14px; }

        /* ── Amount Card ── */
        .amount-card {
            background: var(--grad);
            color: white;
            border-radius: 18px;
            padding: 32px 24px;
            text-align: center;
            margin-bottom: 0;
            box-shadow: 0 10px 30px rgba(232,37,125,.35);
            position: relative;
            overflow: hidden;
        }
        .amount-card::before {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            background: rgba(255,255,255,.07);
            border-radius: 50%;
            top: -60px; right: -40px;
        }
        .amount-card .label { font-size: 13px; opacity: .9; letter-spacing: 1px; text-transform: uppercase; font-weight: 600; }
        .amount-card .amount { font-size: 40px; font-weight: 800; margin: 10px 0; letter-spacing: -.5px; }
        .amount-card .tax-info { font-size: 12px; opacity: .82; margin-top: 6px; }

        /* ── Actions ── */
        .action-section {
            padding: 28px;
            background: #faf6ff;
            border-top: 1.5px solid var(--border);
        }

        .btn-payment {
            background: var(--grad);
            color: white;
            border: none;
            padding: 17px 40px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all .3s ease;
            width: 100%;
            margin-bottom: 14px;
            box-shadow: 0 6px 20px rgba(232,37,125,.35);
            letter-spacing: .2px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-payment:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(232,37,125,.45);
        }
        .btn-payment:active { transform: translateY(0); }

        .btn-cancel {
            background: white;
            color: var(--muted);
            border: 1.5px solid var(--border);
            padding: 17px 40px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all .3s ease;
            width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-cancel:hover {
            border-color: var(--pink);
            color: var(--pink);
            background: #fff5fa;
        }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            .payment-header h1 { font-size: 22px; }
            .amount-card .amount { font-size: 32px; }
            .info-row { flex-direction: column; align-items: flex-start; gap: 4px; }
            .info-value { text-align: left; }
        }
    </style>
</head>
<body>
    <div class="payment-container">

        {{-- Header --}}
        <div class="payment-header">
            <div class="logo"><i class="fas fa-check"></i></div>
            <h1>Registrasi Berhasil!</h1>
            <p>Silakan selesaikan pembayaran untuk mengaktifkan akun sekolah Anda</p>
            <div class="status-badge">
                <i class="fas fa-clock"></i> Menunggu Pembayaran
            </div>
        </div>

        {{-- Timer --}}
        <div class="timer-section">
            <div class="timer-label"><i class="fas fa-hourglass-half"></i> &nbsp;Batas Waktu Pembayaran</div>
            <div class="timer" id="countdown">24:00:00</div>
        </div>

        {{-- Alert --}}
        <div class="alert-section">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-triangle alert-icon"></i>
                <div>
                    <h5>⚠️ Penting untuk Diperhatikan:</h5>
                    <ul>
                        <li>Pembayaran akan <strong>expired dalam 24 jam</strong></li>
                        <li>Pastikan <strong>nominal pembayaran sesuai</strong> dengan yang tertera</li>
                        <li>Akun sekolah akan <strong>aktif otomatis</strong> setelah pembayaran terverifikasi</li>
                        <li>Pilihan <strong>metode pembayaran</strong> tersedia di halaman berikutnya</li>
                        <li>Jika mengalami kendala, hubungi <strong>customer service kami</strong></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- School Info --}}
        <div class="info-section">
            <div class="info-card">
                <h6>
                    <i class="fas fa-school"></i>
                    Informasi Sekolah
                </h6>
                <div class="info-row">
                    <span class="info-label">Nama Sekolah</span>
                    <span class="info-value">{{ $inquiry->school_name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $inquiry->school_email ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Telepon</span>
                    <span class="info-value">{{ $inquiry->school_phone ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Alamat</span>
                    <span class="info-value">{{ $inquiry->school_address ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="info-card">
                <h6>
                    <i class="fas fa-box"></i>
                    Paket Berlangganan
                </h6>
                <div class="info-row">
                    <span class="info-label">Paket</span>
                    <span class="info-value">{{ $package->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Durasi</span>
                    <span class="info-value">{{ $package->days ?? 0 }} Hari</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Limit Siswa</span>
                    <span class="info-value">{{ number_format($package->no_of_students ?? 0) }} Siswa</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Limit Staff</span>
                    <span class="info-value">{{ number_format($package->no_of_staffs ?? 0) }} Staff</span>
                </div>
            </div>

            {{-- Amount --}}
            <div class="amount-card">
                <div class="label">💰 Total Pembayaran</div>
                <div class="amount">Rp {{ number_format($inquiry->price ?? 0, 0, ',', '.') }}</div>
                <div class="tax-info">Sudah termasuk PPN 11%</div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="action-section">
            <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
                @csrf
                <input type="hidden" name="inquiry_id" value="{{ $inquiry->id }}">
                <input type="hidden" name="package_id" value="{{ $package->id ?? '' }}">
                <input type="hidden" name="amount" value="{{ $inquiry->price }}">
                <button type="submit" class="btn-payment">
                    <i class="fas fa-credit-card"></i> &nbsp;Lanjutkan ke Pembayaran
                </button>
            </form>
            <button type="button" class="btn-cancel" onclick="confirmCancel()">
                <i class="fas fa-times"></i> &nbsp;Batalkan Registrasi
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ── Payment Status Polling & Auto-Redirect ──
        const currentPaymentStatus = "{{ $inquiry->payment_status ?? 'pending' }}";
        const inquiryId = "{{ $inquiry->id }}";
        let pollingInterval = null;

        function showSuccessAndRedirect() {
            if (pollingInterval) { clearInterval(pollingInterval); pollingInterval = null; }
            Swal.fire({
                icon: 'success', title: 'Pembayaran Berhasil! 🎉',
                html: '<p>Pembayaran Anda telah terverifikasi.</p><p class="mt-2">Akun sekolah Anda sedang disiapkan.</p><p class="mt-2"><strong>Anda akan diarahkan ke halaman login...</strong></p>',
                confirmButtonColor: '#e8257d',
                confirmButtonText: '<i class="fas fa-sign-in-alt"></i> Ke Halaman Login',
                allowOutsideClick: false, allowEscapeKey: false,
                timer: 5000, timerProgressBar: true
            }).then(() => { window.location.href = '/login'; });
        }

        // If already paid on page load → redirect immediately
        if (currentPaymentStatus === 'success' || currentPaymentStatus === 'paid') {
            showSuccessAndRedirect();
        }

        // Poll every 5 seconds
        function startPaymentPolling() {
            if (pollingInterval) return;
            pollingInterval = setInterval(function() {
                fetch('/payment/check-status/' + inquiryId, { method: 'GET', headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success' || data.status === 'paid') showSuccessAndRedirect();
                })
                .catch(err => console.error('Polling error:', err));
            }, 5000);
        }

        if (currentPaymentStatus !== 'success' && currentPaymentStatus !== 'paid') {
            startPaymentPolling();
        }

        // ── Countdown Timer ──
        function startCountdown() {
            const countdownElement = document.getElementById('countdown');
            const expiryTime = new Date("{{ $expires_at ?? '' }}").getTime();
            if (!expiryTime || isNaN(expiryTime)) { countdownElement.innerHTML = "ERROR"; return; }

            const timer = setInterval(function() {
                const distance = expiryTime - new Date().getTime();
                if (distance < 0) {
                    clearInterval(timer);
                    if (pollingInterval) { clearInterval(pollingInterval); pollingInterval = null; }
                    countdownElement.innerHTML = "EXPIRED";
                    Swal.fire({ icon: 'error', title: 'Waktu Habis!', text: 'Batas waktu pembayaran telah berakhir.', confirmButtonColor: '#e8257d', allowOutsideClick: false })
                        .then(() => window.location.href = '/');
                    return;
                }
                const h = Math.floor((distance % (1000*60*60*24)) / (1000*60*60));
                const m = Math.floor((distance % (1000*60*60)) / (1000*60));
                const s = Math.floor((distance % (1000*60)) / 1000);
                countdownElement.innerHTML = String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
                if (distance < 3600000 && distance > 3540000) {
                    Swal.fire({ icon: 'warning', title: 'Segera Expired!', text: 'Pembayaran akan expired dalam 1 jam', timer: 3000, showConfirmButton: false });
                }
            }, 1000);
        }
        startCountdown();

        // ── Proceed Payment ──
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Lanjutkan ke Pembayaran?',
                html: `<p>Anda akan diarahkan ke halaman pembayaran DOKU</p><p class="mt-3"><strong>Total: Rp {{ number_format($inquiry->price ?? 0, 0, ',', '.') }}</strong></p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e8257d',
                cancelButtonColor: '#9e7ab5',
                confirmButtonText: '<i class="fas fa-arrow-right"></i> Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', text: 'Menghubungi payment gateway', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading() });
                    fetch(form.action, {
                        method: 'POST', body: new FormData(form),
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    })
                    .then(r => { if (!r.ok) throw new Error('Network error'); return r.json(); })
                    .then(data => {
                        if (data.success && data.payment_url) window.location.href = data.payment_url;
                        else throw new Error(data.message || 'Gagal memproses pembayaran');
                    })
                    .catch(err => Swal.fire({ icon: 'error', title: 'Gagal Memproses', text: err.message, confirmButtonColor: '#e8257d' }));
                }
            });
        });

        // ── Cancel Registration ──
        function confirmCancel() {
            Swal.fire({
                title: 'Batalkan Registrasi?',
                text: 'Semua data registrasi akan dihapus dan Anda harus mendaftar ulang',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e8257d',
                cancelButtonColor: '#9e7ab5',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (pollingInterval) { clearInterval(pollingInterval); pollingInterval = null; }
                    Swal.fire({ title: 'Memproses...', text: 'Membatalkan registrasi', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading() });
                    fetch('{{ route("registration.cancel", ["id" => $inquiry->id]) }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                    })
                    .then(() => {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Registrasi berhasil dibatalkan.', confirmButtonColor: '#e8257d', allowOutsideClick: false })
                            .then(() => window.location.href = '/');
                    })
                    .catch(() => Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat membatalkan registrasi.', confirmButtonColor: '#e8257d' }));
                }
            });
        }

        // ── Prevent accidental close ──
        window.addEventListener('beforeunload', function(e) { e.preventDefault(); e.returnValue = ''; });
    </script>
</body>
</html>