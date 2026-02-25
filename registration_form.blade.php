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
    @if (config('services.recaptcha.key') ?? '')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</head>
<body>

    {{-- Top Header --}}
    <header class="reg-top-header">
        <div class="container">
            <div class="header-inner">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('assets/horizontal-logo.png') }}" alt="logo">
                </a>
                <a href="{{ route('login') }}" class="header-login-btn">Masuk</a>
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
                        <img src="assets/hero-regis.png" alt="Tampilan sekolah modern">
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
                                    <!-- <p class="field-note">
                                        <span class="material-symbols-outlined" style="font-size:16px; vertical-align:middle;">info</span>
                                        Password akun akan dikirim ke email ini setelah proses pembayaran berhasil.
                                    </p> -->
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
                                    <label>Password <span class="required">*</span></label>
                                    <div class="password-wrapper">
                                        <input type="password" name="school_password" id="school_password" placeholder="Enter Your School Password" required>
                                        <span class="material-symbols-outlined toggle-password" onclick="togglePassword('school_password', this)">visibility_off</span>
                                    </div>
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
                                 @foreach($packages as $package)
                                        <label class="package-card">
                                            <input type="radio" name="package_id" value="{{ $package->id }}" required {{ request()->get('package') == $package->id ? 'checked' : '' }}>
                                            <div class="package-card__inner">
                                                <span class="package-card__badge {{ $package->highlight ? 'popular' : '' }}">{{ $package->name }}</span>
                                                <p class="package-card__price">
                                                    
                                                     @if ($package->is_trial == 1)
                                                        {{ __('free') }}
                                                    @else
                                                        @if ($package->type == 1)
                                                            Idr {{ number_format($package->student_charge, 0) }} <span>/per student</span>
                                                        @else
                                                            Idr {{ number_format($package->charges, 0) }} <span>/{{ $package->days }} Days</span>
                                                        @endif
                                                    @endif
                                                </p>
                                                <ul class="package-card__features">
                                                    {{-- Improve: List specific features of package if available --}}
                                                    <li>{{ $package->days }} Days Validity</li>
                                                    @if($package->type == 0)
                                                        <li>{{ $package->no_of_students }} Students Limit</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <br>
                                @if(isset($extraFields) && count($extraFields))     
                                    <div class="row other-details mt-3">

                                        {{-- Loop the FormData --}}
                                        @foreach ($extraFields as $key => $data)
                                            {{-- Edit Extra Details ID --}}
                                            {{ Form::hidden('extra_fields['.$key.'][id]', '', ['id' => $data->type.'_'.$key.'_id']) }}

                                            {{-- Form Field ID --}}
                                            {{ Form::hidden('extra_fields['.$key.'][form_field_id]', $data->id, ['id' => $data->type.'_'.$key.'_id']) }}

                                            <div class='form-group col-md-12 col-lg-6 col-xl-6 col-sm-12'>

                                                {{-- Add lable to all the elements excluding checkbox --}}
                                                @if($data->type != 'radio' && $data->type != 'checkbox')
                                                    <label>{{$data->name}} @if($data->is_required)
                                                            <span class="required">*</span>
                                                        @endif</label>
                                                @endif

                                                {{-- Text Field --}}
                                                @if($data->type == 'text')
                                                    {{ Form::text('extra_fields['.$key.'][data]', '', ['class' => 'form-control text-fields', 'id' => $data->type.'_'.$key, 'placeholder' => $data->name, ($data->is_required == 1 ? 'required' : '')]) }}
                                                    {{-- Number Field --}}
                                                @elseif($data->type == 'number')
                                                    {{ Form::number('extra_fields['.$key.'][data]', '', ['min' => 0, 'class' => 'form-control number-fields', 'id' => $data->type.'_'.$key, 'placeholder' => $data->name, ($data->is_required == 1 ? 'required' : '')]) }}

                                                    {{-- Dropdown Field --}}
                                                @elseif($data->type == 'dropdown')
                                                    <select name="extra_fields[{{ $key }}][data]" id="{{ $data->type . '_' . $key }}" class="form-control select-fields" 
                                                            {{ $data->is_required == 1 ? 'required' : '' }}>
                                                        <option value="" disabled selected>Select {{ $data->name }}</option>
                                                        @foreach($data->default_values as $optionKey => $optionValue)
                                                            <option value="{{ $optionKey }}">{{ $optionValue }}</option>
                                                        @endforeach
                                                    </select>

                                                    {{-- Radio Field --}}
                                                @elseif($data->type == 'radio')
                                                    <label class="d-block">{{$data->name}} @if($data->is_required)
                                                            <span class="required">*</span>
                                                        @endif</label>
                                                    <div class="row col-md-12 col-lg-12 col-xl-6 col-sm-12">
                                                        @if(count($data->default_values))
                                                            @foreach ($data->default_values as $keyRadio => $value)
                                                                <div class="form-check mr-2">
                                                                    <label class="form-check-label">
                                                                        {{ Form::radio('extra_fields['.$key.'][data]', $value, null, ['id' => $data->type.'_'.$keyRadio, 'class' => 'radio-fields',($data->is_required == 1 ? 'required' : '')]) }}
                                                                        {{$value}}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>

                                                    {{-- Checkbox Field --}}
                                                @elseif($data->type == 'checkbox')
                                                    <label class="d-block">{{$data->name}} @if($data->is_required)
                                                            <span class="required">*</span>
                                                        @endif</label>
                                                    @if(count($data->default_values))
                                                        <div class="row col-lg-12 col-xl-6 col-md-12 col-sm-12 checkbox-group">
                                                            @foreach ($data->default_values as $chkKey => $value)
                                                                <div class="mr-2 form-check">
                                                                    <label class="form-check-label group-required">
                                                                        {{ Form::checkbox('extra_fields['.$key.'][data][]', $value, null, ['id' => $data->type.'_'.$chkKey, 'class' => 'form-check-input chkclass checkbox-fields checkbox-group']) }} {{ $value }}

                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @if($data->is_required)
                                                          <span class="text-danger d-none checkbox-error">{{ __('this field is required') }}</span>
                                                       @endif

                                                    @endif
                                             
                                                    {{-- Textarea Field --}}
                                                @elseif($data->type == 'textarea')
                                                    {{ Form::textarea('extra_fields['.$key.'][data]', '', ['placeholder' => $data->name, 'id' => $data->type.'_'.$key, 'class' => 'form-control textarea-fields', ($data->is_required ? 'required' : '') , 'rows' => 3]) }}

                                                    {{-- File Upload Field --}}
                                                @elseif($data->type == 'file')
                                                    <div class="input-group col-xs-12">
                                                        {{ Form::file('extra_fields['.$key.'][data]', ['class' => 'file-upload-default form-control', 'id' => $data->type.'_'.$key, 'style' => 'opacity: 1; position: relative; z-index: 1;', ($data->is_required ? 'required' : ''), 'aria-describedby' => 'file-help-'.$key]) }}
                                                    </div>
                                                    <div id="file_div_{{$key}}" class="mt-2 d-none file-div">
                                                        <a href="" id="file_link_{{$key}}" target="_blank">{{$data->name}}</a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                </div>
                            </div>
                            
                            {{-- reCAPTCHA --}}
                            @if (config('services.recaptcha.key') ?? '')
                                <div class="form-group full-width">
                                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
                                </div>
                            @endif

                            {{-- Submit --}}
                            <div class="form-footer" style="gap: 1rem;">
                                <a href="{{ url('/') }}" class="back-btn">
                                    <span class="material-symbols-outlined">arrow_back</span>
                                    Back
                                </a>
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
                    <img src="{{ asset('assets/landing_page_images/Logo.png') }}" width="100px" alt="logo">
                </div>  
                <nav class="footer-nav">
                   <a href="{{ url('kebijakan-privasi') }}">Kebijakan Privasi</a>
                   <a href="{{ url('page/type/terms-conditions') }}">Syarat Layanan</a>
                </nav>
                <p class="footer-copyright">© 2026 Juliet.</p>
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
                    
                    if (!isValid) {
                        return; // Prevent further execution if invalid
                    }

                    // AJAX Submission
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = 'Processing...';
                    }

                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        }

                        if (data.error) {
                            alert(data.message);
                        } else {
                            if (data.warning) {
                                alert(data.message); // Show warning
                            } else if (data.redirect) {
                                window.location.href = data.redirect; // Redirect immediately
                            } else {
                                alert(data.message); // Show success for Direct Registration
                                form.reset();
                            }
                        }
                    })
                    .catch(error => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        }
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
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

        // ── Password Toggle ──
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility_off';
            }
        }
    </script>

</body>
</html>