<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Konfirmasi Pembayaran - Juliet</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .payment-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        /* Header */
        .payment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .payment-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .payment-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .status-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 15px;
        }

        /* Timer */
        .timer-section {
            background: #fff3cd;
            padding: 20px 30px;
            text-align: center;
            border-bottom: 3px solid #ffc107;
        }

        .timer-label {
            font-size: 14px;
            color: #856404;
            margin-bottom: 8px;
        }

        .timer {
            font-size: 32px;
            font-weight: 700;
            color: #856404;
        }

        /* Alert */
        .alert-section {
            padding: 30px;
            background: #fff3cd;
            border-left: 5px solid #ffc107;
        }

        .alert-section .alert-icon {
            font-size: 24px;
            color: #ffc107;
            margin-right: 15px;
        }

        .alert-section h5 {
            color: #856404;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .alert-section ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert-section li {
            color: #856404;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        /* Info Section */
        .info-section {
            padding: 30px;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            border: 2px solid #e9ecef;
        }

        .info-card h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6c757d;
            font-weight: 500;
            font-size: 15px;
        }

        .info-value {
            color: #212529;
            font-weight: 600;
            text-align: right;
            font-size: 15px;
        }

        /* Amount Card */
        .amount-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin-bottom: 25px;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .amount-card .label {
            font-size: 15px;
            opacity: 0.9;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .amount-card .amount {
            font-size: 42px;
            font-weight: 700;
            margin: 10px 0;
        }

        .amount-card .tax-info {
            font-size: 13px;
            opacity: 0.85;
            margin-top: 8px;
        }

        /* Action Buttons */
        .action-section {
            padding: 30px;
            border-top: 2px solid #e9ecef;
        }

        .btn-payment {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 17px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-payment:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-cancel {
            background: white;
            color: #6c757d;
            border: 2px solid #e9ecef;
            padding: 18px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 17px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-cancel:hover {
            border-color: #dc3545;
            color: #dc3545;
            background: #fff5f5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .payment-container {
                margin: 20px auto;
            }

            .payment-header h1 {
                font-size: 24px;
            }

            .amount-card .amount {
                font-size: 34px;
            }

            .info-row {
                flex-direction: column;
                gap: 8px;
            }

            .info-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        {{-- Header --}}
        <div class="payment-header">
            <h1>
                <i class="fas fa-check-circle"></i>
                Registrasi Berhasil!
            </h1>
            <p>Silakan selesaikan pembayaran untuk mengaktifkan akun sekolah Anda</p>
            <div class="status-badge">
                <i class="fas fa-clock"></i> Menunggu Pembayaran
            </div>
        </div>

        {{-- Alert Important --}}
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

            {{-- Payment Amount --}}
            <div class="amount-card">
                <div class="label">💰 TOTAL PEMBAYARAN</div>
                <div class="amount">Rp {{ number_format($inquiry->price ?? 0, 0, ',', '.') }}</div>
                <div class="tax-info">Sudah termasuk PPN 11%</div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="action-section">
            <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
                @csrf
                <input type="hidden" name="inquiry_id" value="{{ $inquiry->id }}">
                <input type="hidden" name="package_id" value="{{ $package->id ?? '' }}">
                <input type="hidden" name="amount" value="{{ $inquiry->price }}">
                
                <button type="submit" class="btn-payment">
                    <i class="fas fa-credit-card"></i>
                    Lanjutkan ke Pembayaran
                </button>
            </form>
            
            <button type="button" class="btn-cancel" onclick="confirmCancel()">
                <i class="fas fa-times"></i>
                Batalkan Registrasi
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ============================================
        // COUNTDOWN TIMER (24 hours)
        // ============================================
        function startCountdown() {
        const countdownElement = document.getElementById('countdown');
    
        // ✅ Ambil expiry dari backend (pass via blade)
        const expiryTime = new Date("{{ $expires_at ?? '' }}").getTime();
        
        // ✅ Validasi expiry time
        if (!expiryTime || isNaN(expiryTime)) {
        countdownElement.innerHTML = "ERROR";
        console.error("Invalid expiry time");
        return;
        }
    
        const timer = setInterval(function() {
        const now = new Date().getTime();
        const distance = expiryTime - now;

        if (distance < 0) {
            clearInterval(timer);
            countdownElement.innerHTML = "EXPIRED";
            
            // ✅ Auto redirect after expired
            Swal.fire({
                icon: 'error',
                title: 'Waktu Habis!',
                text: 'Batas waktu pembayaran telah berakhir.',
                confirmButtonColor: '#dc3545',
                allowOutsideClick: false
            }).then(() => {
                window.location.href = '/';
            });
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownElement.innerHTML = 
            String(hours).padStart(2, '0') + ':' + 
            String(minutes).padStart(2, '0') + ':' + 
            String(seconds).padStart(2, '0');
            
        // ✅ TAMBAHAN: Alert kalo tinggal 1 jam
        if (distance < (60 * 60 * 1000) && distance > (59 * 60 * 1000)) {
            Swal.fire({
                icon: 'warning',
                title: 'Segera Expired!',
                text: 'Pembayaran akan expired dalam 1 jam',
                timer: 3000,
                showConfirmButton: false
            });
            }
        }, 1000);
    }

startCountdown();

        // ============================================
        // PROCEED TO PAYMENT
        // ============================================
        document.getElementById('payment-form').addEventListener('submit', function(e) {e.preventDefault();
    
        const form = this;  
    
        Swal.fire({
        title: 'Lanjutkan ke Pembayaran?',
        html: `
            <p>Anda akan diarahkan ke halaman pembayaran DOKU</p>
            <p class="mt-3"><strong>Total: Rp {{ number_format($inquiry->price ?? 0, 0, ',', '.') }}</strong></p>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#667eea',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-arrow-right"></i> Ya, Lanjutkan',
        cancelButtonText: 'Batal'}).then((result) => {
        if (result.isConfirmed) {
            // ✅ Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Menghubungi payment gateway',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // ✅ Submit via AJAX (untuk handle error)
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.payment_url) {
                    // ✅ Redirect ke DOKU
                    window.location.href = data.payment_url;
                } else {
                    throw new Error(data.message || 'Gagal memproses pembayaran');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memproses',
                    text: error.message || 'Terjadi kesalahan. Silakan coba lagi.',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
        });
    });

        // ============================================
        // CANCEL REGISTRATION
        // ============================================
        function confirmCancel() {
            Swal.fire({
                title: 'Batalkan Registrasi?',
                text: 'Semua data registrasi akan dihapus dan Anda harus mendaftar ulang',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Membatalkan registrasi',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('{{ route("registration.cancel", ["id" => $inquiry->id]) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        // Redirect berhasil (302 -> redirect ke homepage)
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Registrasi berhasil dibatalkan.',
                            confirmButtonColor: '#667eea',
                            allowOutsideClick: false
                        }).then(() => {
                            window.location.href = '/';
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat membatalkan registrasi.',
                            confirmButtonColor: '#dc3545'
                        });
                    });
                }
            });
        }

        // ============================================
        // PREVENT ACCIDENTAL PAGE CLOSE
        // ============================================
        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '';
        });
    </script>
</body>
</html>
