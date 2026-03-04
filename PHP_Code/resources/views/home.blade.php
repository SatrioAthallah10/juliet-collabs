@extends('layouts.home_page.master')

@section('content')
    <style>
        :root {
            --primary-color:
                {{ $settings['theme_primary_color'] ?? '#56cc99' }}
            ;
            --secondary-color:
                {{ $settings['theme_secondary_color'] ?? '#215679' }}
            ;
            --secondary-color1:
                {{ $settings['theme_secondary_color_1'] ?? '#38a3a5' }}
            ;
            --primary-background-color:
                {{ $settings['theme_primary_background_color'] ?? '#f2f5f7' }}
            ;
            --text--secondary-color:
                {{ $settings['theme_text_secondary_color'] ?? '#5c788c' }}
            ;

        }
    </style>
    <script src="{{ asset('assets/home_page/js/jquery-1-12-4.min.js') }}"></script>

    <header class="navbar">
        <div class="container">
            <div class="navbarWrapper">
                <div class="navLogoWrapper">
                    <div class="navLogo">
                        <a href="{{ url('/') }}">
                            <img src="{{ $settings['horizontal_logo'] ?? asset('assets/landing_page_images/Logo1.png') }}"
                                class="logo" alt="">
                        </a>

                    </div>
                </div>
                <div class="menuListWrapper">
                    <ul class="listItems">
                        <li>
                            <a href="#home">{{ __('home') }}</a>
                        </li>
                        <li>
                            <a href="#features">{{ __('features') }}</a>
                        </li>
                        <li>
                            <a href="#about-us">{{ __('about_us') }}</a>
                        </li>
                        <li>
                            <a href="#pricing">{{ __('pricing') }}</a>
                        </li>
                        @if (count($faqs))
                            <li>
                                <a href="#faq">{{ __('faqs') }}</a>
                            </li>
                        @endif
                        <li>
                            <a href="#contact-us">{{ __('contact') }}</a>
                        </li>
                        {{-- @if (count($guidances))
                            <li>
                                <div class="dropdown">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ __('guidance') }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        @foreach ($guidances as $key => $guidance)
                                            <li><a class="dropdown-item" href="{{ $guidance->link }}">{{ $guidance->name }}</a></li>
                                            @if (count($guidances) > ($key + 1))
                                                <hr>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @endif
                        <li>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('language') }}
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @foreach ($languages as $key => $language)
                                        <li><a class="dropdown-item"
                                                href="{{ url('set-language') . '/' . $language->code }}">{{ $language->name }}</a>
                                        </li>
                                        @if (count($languages) > ($key + 1))
                                            <hr>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </li> --}}

                    </ul>
                    <div class="hamburg">
                        <span data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i
                                class="fa-solid fa-bars"></i></span>
                    </div>
                </div>

                <div class="loginBtnsWrapper">
                   <button class="commonBtn redirect-login" onclick="window.location.href='{{ route('login') }}'">
                        {{ __('Masuk') }}
                    </button>
                    {{-- <button class="commonBtn" id="trialBtn" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">{{ __('start_trial') }}</button> --}}
                    {{-- <a href="{{ url('school/registration') }}" class="commonBtn">{{ __('start_trial') }}</a> --}}
                </div>
            </div>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <div class="navLogoWrapper">
                        <div class="navLogo">
                            <img src="{{ $settings['horizontal_logo'] ?? asset('assets/landing_page_images/Logo1.png') }}"
                                alt="">
                        </div>
                    </div>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="listItems">
                        <li>
                            <a href="#home">{{ __('home') }}</a>
                        </li>
                        <li>
                            <a href="#features">{{ __('features') }}</a>
                        </li>
                        <li>
                            <a href="#about-us">{{ __('about_us') }}</a>
                        </li>
                        <li>
                            <a href="#pricing">{{ __('pricing') }}</a>
                        </li>
                        @if (count($faqs))
                            <li>
                                <a href="#faq">{{ __('faqs') }}</a>
                            </li>
                        @endif
                        <li>
                            <a href="#contact-us">{{ __('contact') }}</a>
                        </li>
                        {{-- @if (count($guidances))
                            <li>
                                <div class="dropdown">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ __('guidance') }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        @foreach ($guidances as $key => $guidance)
                                            <li><a class="dropdown-item" href="{{ $guidance->link }}">{{ $guidance->name }}</a></li>
                                            @if (count($guidances) > ($key + 1))
                                                <hr>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @endif
                        <li>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('language') }}
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @foreach ($languages as $key => $language)
                                        <li><a class="dropdown-item"
                                                href="{{ url('set-language') . '/' . $language->code }}">{{ $language->name }}</a>
                                        </li>
                                        @if (count($languages) > ($key + 1))
                                            <hr>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </li> --}}

                    </ul>

                    {{-- <div class="loginBtnsWrapper"> --}}
                    <button class="commonBtn redirect-login" onclick="window.location.href='{{ route('login') }}'">
                        {{ __('Masuk') }}
                    </button>                        {{-- <button class="commonBtn" data-bs-toggle="modal" data-bs-dismiss="offcanvas"
                            data-bs-target="#staticBackdrop">{{ __('start_trial') }}</button> --}}
                        {{--
                    </div> --}}
                </div>
            </div>
        </div>
    </header>

    <!-- navbar ends here  -->

    <div class="main">

        <section class="heroSection" id="home">
            <div class="linesBg">
                <div class="colorBg">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="flex_column_start">
                                    <span class="commonTitle">Juliet - The Next Gen Education</span>
                                    <span class="commonDesc">
                                        {{-- {{ $settings['tag_line'] }} --}}
                                        Transformasi Manajemen Sekolah Bersama Juliet
                                    </span>
                                    <span class="commonText">
                                        {{-- {{ $settings['hero_description'] }}</span> --}}
                                        Rasakan masa depan pendidikan dengan platform Juliet kami. Sederhanakan absensi, tugas, ujian, dan banyak lagi. Tingkatkan efisiensi dan keterlibatan sekolah Anda.
                                    <div class="d-flex">
                                        {{-- <a href="#pricing" class="commonBtn">
                                            {{ __('Daftarkan sekolah anda') }}
                                        </a> --}}

                                        <button class="commonBtn" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop">
                                            {{ __('Daftarkan sekolah anda') }}
                                        </button>
                                
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 heroImgWrapper">
                                <div class="heroImg">
                                    <img src="assets/landing_page_images/heroImg.png"
                                        alt="">
                                    {{-- <div class="topRated card">
                                        <div>
                                            <img src="{{ $settings['hero_title_2_image'] ?? asset('assets/landing_page_images/user.png') }}"
                                                alt="">
                                        </div>
                                        @if(!empty($settings['hero_title_2']))
                                            <div>
                                                <span>{{ $settings['hero_title_2'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    @if(!empty($settings['hero_title_1']))
                                        <div class="textWrapper">
                                            <span>{{ $settings['hero_title_1'] }}</span>
                                        </div>
                                    @endif --}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('registration_form')

        </section>
        <!-- heroSection ends here  -->

       <section class="features commonMT container" id="features">
    @php
        // TRANSLASI LANGSUNG - Tidak pakai Laravel translation
        $featureTranslations = [
            'Student Management' => 'Manajemen Siswa',
            'Academics Management' => 'Manajemen Akademik',
            'Slider Management' => 'Manajemen Slider',
            'Teacher Management' => 'Manajemen Guru',
            'Session Year Management' => 'Manajemen Tahun Ajaran',
            'Holiday Management' => 'Manajemen Hari Libur',
            'Timetable Management' => 'Manajemen Jadwal Pelajaran',
            'Attendance Management' => 'Manajemen Absensi',
            'Exam Management' => 'Manajemen Ujian',
            'Lesson Management' => 'Manajemen Pelajaran',
            'Assignment Management' => 'Manajemen Tugas',
            'Announcement Management' => 'Manajemen Pengumuman',
            'Staff Management' => 'Manajemen Staf',
            'Expense Management' => 'Manajemen Pengeluaran',
            'Staff Leave Management' => 'Manajemen Cuti Staf',
            'Fees Management' => 'Manajemen Biaya',
            'School Gallery Management' => 'Manajemen Galeri Sekolah',
            'ID Card - Certificate Generation' => 'Pembuatan ID Card & Sertifikat',
            'Website Management' => 'Manajemen Website',
            'Chat Module' => 'Modul Chat',
            'Transportation Module' => 'Modul Transportasi',
            'Staff Attendance Management' => 'Manajemen Absensi Staf',
        ];
    @endphp
    
    <div class="row">
        <div class="col-12">
            <div class="sectionTitle">
                <span>Fitur unggulan kami</span>
            </div>
        </div>
        <div class="col-12">
            <div class="row cardWrapper">
                @foreach ($features as $key => $feature)
                    @php
                        // Bersihkan nama fitur (trim spasi)
                        $featureName = trim($feature->name);
                        // Ambil translasi Indonesia, kalau ga ada pakai English
                        $displayName = $featureTranslations[$featureName] ?? $featureName;
                    @endphp
                    
                    @if ($key < 9)
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div>
                                    <img src="{{ asset('assets/landing_page_images/features/') }}/{{ $feature->name }}.svg"
                                        alt="{{ $displayName }}">
                                </div>
                                <div><span>{{ $displayName }}</span></div>
                            </div>
                        </div>
                    @else
                        <div class="col-sm-12 col-md-6 col-lg-4 default-feature-list" style="display: none">
                            <div class="card">
                                <div>
                                    <img src="{{ asset('assets/landing_page_images/features/') }}/{{ $feature->name }}.svg"
                                        alt="{{ $displayName }}">
                                </div>
                                <div><span>{{ $displayName }}</span></div>
                            </div>
                        </div>
                    @endif
                @endforeach
                
                <div class="col-12">
                    <button class="commonBtn view-more-feature" value="1">Lihat fitur lainnya</button>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- features ends here  -->
        <!-- features ends here  -->

        {{-- @if ($settings['display_school_logos'] ?? '1')
        <section class="swiperSect container commonMT">
            <div class="row">
                <div class="col-12">
                    <div class="commonSlider">
                        <div class="slider-content owl-carousel">
                            <!-- Example slide -->
                            @foreach ($schoolSettings as $school)
                            @if (Storage::disk('public')->exists($school->getRawOriginal('data')) && $school->data)
                            <div class="swiperDataWrapper">
                                <div class="card">
                                    <img src="{{ $school->data }}" class="normalImg" alt="">
                                </div>
                            </div>
                            @endif
                            @endforeach
                            <!-- Add more swiperDataWrapper elements here -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif --}}
        <!-- swiperSect ends here  -->
        {{-- @if ($settings['display_counters'] ?? '1')
        <section class="counterSect commonMT container">
            <div class="">
                <div class="row counterBG">
                    <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                        <div class="card">
                            <div><span class="numb" data-target="{{ $counter['school'] }}">0</span><span>+</span></div>
                            <div><span class="text">{{ __('schools') }}</span></div>
                        </div>
                    </div>
                    <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                        <div class="card">
                            <div><span class="numb" data-target="{{ $counter['teacher'] }}">0</span><span>+</span></div>
                            <div><span class="text">{{ __('teachers') }}</span></div>
                        </div>
                    </div>
                    <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                        <div class="card">
                            <div><span class="numb" data-target="{{ $counter['student'] }}">0</span><span>+</span></div>
                            <div><span class="text">{{ __('students') }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif --}}

        <!-- School logos section starts here -->
        @if ($settings['display_school_logos'] ?? '1')
            <input type="hidden" id="school-count" value="{{ count($allSchools) }}">
            <section class="container">
                <div class="col-12">
                    <div class="sectionTitle">
                        <span>{{ __('Sekolah') }}</span>
                    </div>
                </div>
                <div class="row py-3">
                    <div class="owl-carousel owl-theme school-logo-owl-carousel">

                        @foreach ($allSchools as $key => $school)
                            <div class="item">
                                <div class="card p-3 d-flex justify-content-center align-items-center">
                                    <img src="{{ $school->logo }}" style="border-radius: 50%; width: 100px; height: 100px;" alt=""
                                        onerror="onErrorImage(event)">
                                    <h6 class="mt-3">{{  Str::limit($school->name, 25, ' ...') }}</h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
        <!-- School logos section ends here -->

        @foreach ($featureSections as $key => $section)
            @if (($key + 1) % 2 != 0)

                <section class="left-section-{{ $section->id }} commonMT container">
                    <div class="row">
                        <div class="col-12">
                            <div class="sectionTitle">
                                <span class="greenText">{{ $section->title }}</span>
                                <span>
                                    {{ $section->heading }}
                                </span>

                            </div>
                        </div>
                        <div class="col-12 tabsContainer " style="word-break: break-word;">
                            <div class="row">
                                <div class="col-lg-6 tabsMainWrapper" style="word-break: break-all !important;">
                                    <div class="tabsWrapper">
                                        <div class="tabs">
                                            @foreach ($section->feature_section_list as $section_feature)
                                                <div class="tab tab-{{ $section_feature->id }}-{{ $key }}">
                                                    <span>{{ $section_feature->feature }}</span>
                                                    <span>
                                                        {{ $section_feature->description }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-6 contentWrapper">
                                    <div class="content-container">
                                        @foreach ($section->feature_section_list as $section_feature)
                                            <div class="content tab-{{ $section_feature->id }}-{{ $key }}">
                                                <img src="{{ $section_feature->image }}" alt="">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>

            @else

                <section class="right-section-{{ $section->id }} right-feature-section commonMT">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="sectionTitle">
                                    <span class="greenText">{{ $section->title }}</span>
                                    <span>
                                        {{ $section->heading }}
                                    </span>

                                </div>
                            </div>
                            <div class="col-12 tabsContainer">
                                <div class="row reverseWrapper">
                                    <div class="col-lg-6 contentWrapper">
                                        <div class="content-container">
                                            @foreach ($section->feature_section_list as $section_feature)
                                                <div class="content tab-{{ $section_feature->id }}-{{ $key }}">
                                                    <img src="{{ $section_feature->image }}" alt="">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-lg-6 tabsMainWrapper">
                                        <div class="tabsWrapper">
                                            <div class="tabs">
                                                @foreach ($section->feature_section_list as $section_feature)
                                                    <div class="tab tab-{{ $section_feature->id }}-{{ $key }}">
                                                        <span>{{ $section_feature->feature }}</span>
                                                        <span>
                                                            {{ $section_feature->description }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>

            @endif
        @endforeach

        <section class="whyBest container commonMT" id="about-us">
            <div class="row">
                <div class="col-lg-6">
                    <div class="whyBestTextWrapper">
                        {{-- <p>{{ $settings['about_us_title'] }}</p> --}}
                        <p>
                            {{-- {{ $settings['about_us_heading'] }} --}}
                            Mengapa kami yang terbaik?
                        </p>
                    </div>
                    <p class="whyBestPara">
                        {{-- {{ $settings['about_us_description'] }} --}}
                        Juliet menghadirkan sistem digital terintegrasi yang membantu sekolah mengelola pembelajaran dan manajemen akademik secara lebih efektif dan terstruktur.
                    </p>

                    <div class="listWrapper">
                        {{-- @foreach ($about_us_lists as $point)
                            <span>
                                <i class="fa-regular fa-circle-check"></i>
                                {{ $point }}
                            </span>
                        @endforeach --}}
                    </div>
                </div>

                <div class="col-lg-6">
                    <img src="assets/landing_page_images/whyBestImg.png"
                        alt="">
                </div>
            </div>
        </section>
        <!-- whyBest ends here  -->

        <section class="pricing" id="pricing">
            <div class="container commonMT">
                <div class="row">
                    <div class="col-12">
                        <div class="sectionTitle">
                            <span>{{ __('Paket Harga Fleksibel') }}</span>

                        </div>
                    </div>
                    @if($packages->isNotEmpty())
                        <div class="col-12 swiperWrapper h-full">
                            <div class="commonSlider">
                                <div class="slider-content owl-carousel">

                                    @foreach ($packages as $package)
                                        @if ($package->highlight)
                                            <div class="swiperDataWrapper flex items-stretch h-full">
                                                <div class="pricingBox premium flex flex-col justify-between h-full">
                                                    <div class="startUpWrapper flex flex-col h-full">
                                                        @if ($package->is_trial == 1)
                                                            <span class="badge postpaid">{{ __('free') }}</span>
                                                        @else
                                                            @if ($package->type == 1)
                                                                <span class="badge postpaid">{{ __('postpaid') }}</span>
                                                            @else
                                                                <span class="badge prepaid">{{ __('prepaid') }}</span>
                                                            @endif
                                                        @endif

                                                        <div class="textDiv">
                                                            <span class="title">{{ __($package->name) }}</span>
                                                            @if ($package->is_trial == 1)
                                                                <span>
                                                                    {{ $settings['student_limit'] ?? 0 }} {{ __('student_limit') }}
                                                                </span>
                                                                <span>
                                                                    {{ $settings['staff_limit'] ?? 0 }} {{ __('staff_limit') }}
                                                                </span>
                                                            @elseif($package->type == 0 && $package->is_trial == 0)
                                                                <span>
                                                                    {{ number_format($package->no_of_students, 0) }}
                                                                    {{ __('student_limit') }}
                                                                </span>
                                                                <span>
                                                                    {{ number_format($package->no_of_staffs, 0) }} {{ __('staff_limit') }}
                                                                </span>
                                                                <span>
                                                                    {{-- {{ $settings['currency_symbol'] ??  }} --}}
                                                                    Idr
                                                                    {{ number_format($package->charges, 2) }} {{ __('package_amount') }}
                                                                </span>
                                                            @elseif($package->type == 1 && $package->is_trial == 0)
                                                                <span>
                                                                    {{-- {{ $settings['currency_symbol'] ?? '$' }} --}}
                                                                    Idr
                                                                    {{ number_format($package->student_charge, 2) }}
                                                                    {{ __('per_student_charges') }}
                                                                </span>
                                                                <span>
                                                                    Idr
                                                                    {{-- {{ $settings['currency_symbol'] ?? '$' }} --}}
                                                                    {{ number_format($package->staff_charge, 2) }}
                                                                    {{ __('per_staff_charges') }}
                                                                </span>
                                                            @endif
                                                            <span class="days">{{ $package->days }} {{ __('days') }}</span>
                                                        </div>
                                                        <div class="listWrapper">
                                                            @foreach ($features as $feature)
                                                                @if (in_array($feature->id, $package->package_feature->pluck('feature_id')->toArray()))
                                                                    <span>
                                                                        <img src="{{ asset('assets/landing_page_images/right.svg') }}"
                                                                            class="rightTickImg" alt="">
                                                                        {{ __($feature->name) }}
                                                                    </span>
                                                                @else
                                                                    <span class="lineThrough">
                                                                        <img src="{{ asset('assets/landing_page_images/cross.svg') }}"
                                                                            class="wrongTickImg" alt="">
                                                                        {{ __($feature->name) }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        <button 
                                                            class="pricingBtn" 
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#staticBackdrop"
                                                            data-package-id="{{ $package->id }}"
                                                            data-package-name="{{ $package->name }}">
                                                            {{ __('get_started') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="swiperDataWrapper">
                                                <div class="pricingBox">
                                                    <div class="startUpWrapper">
                                                        @if ($package->is_trial == 1)
                                                            <span class="badge postpaid">{{ __('free') }}</span>
                                                        @else
                                                            @if ($package->type == 1)
                                                                <span class="badge postpaid">{{ __('postpaid') }}</span>
                                                            @else
                                                                <span class="badge prepaid">{{ __('prepaid') }}</span>
                                                            @endif
                                                        @endif
                                                        <div class="textDiv">
                                                            <span class="title">{{ __($package->name) }}</span>
                                                            @if ($package->is_trial == 1)
                                                                <span>
                                                                    {{ $settings['student_limit'] }} {{ __('student_limit') }}
                                                                </span>
                                                                <span>
                                                                    {{ $settings['staff_limit'] }} {{ __('staff_limit') }}
                                                                </span>
                                                            @elseif($package->type == 0 && $package->is_trial == 0)
                                                                <span>
                                                                    {{ number_format($package->no_of_students, 0) }}
                                                                    {{ __('student_limit') }}
                                                                </span>
                                                                <span>
                                                                    {{ number_format($package->no_of_staffs, 0) }} {{ __('staff_limit') }}
                                                                </span>
                                                                <span>
                                                                    {{-- {{ $settings['currency_symbol'] ?? '$' }} --}}
                                                                    Idr
                                                                    {{ number_format($package->charges, 2) }} {{ __('package_amount') }}
                                                                </span>
                                                            @elseif($package->type == 1 && $package->is_trial == 0)
                                                                <span>
                                                                    Idr
                                                                    {{-- {{ $settings['currency_symbol'] ?? '$' }} --}}
                                                                    {{ number_format($package->student_charge, 2) }}
                                                                    {{ __('per_student_charges') }}
                                                                </span>
                                                                <span>
                                                                    Idr
                                                                    {{-- {{ $settings['currency_symbol'] ?? '$' }} --}}
                                                                    {{ number_format($package->staff_charge, 2) }}
                                                                    {{ __('per_staff_charges') }}
                                                                </span>
                                                            @endif
                                                            <span class="days">{{ $package->days }} {{ __('days') }}</span>
                                                        </div>
                                                        <div class="listWrapper">
                                                            @foreach ($features as $feature)
                                                                @if (in_array($feature->id, $package->package_feature->pluck('feature_id')->toArray()))
                                                                    <span>
                                                                        <img src="{{ asset('assets/landing_page_images/right.svg') }}"
                                                                            class="rightTickImg" alt="">
                                                                        {{ __($feature->name) }}
                                                                    </span>
                                                                @else
                                                                    <span class="lineThrough">
                                                                        <img src="{{ asset('assets/landing_page_images/cross.svg') }}"
                                                                            class="wrongTickImg" alt="">
                                                                        {{ __($feature->name) }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        <button 
                                                            class="pricingBtn" 
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#staticBackdrop"
                                                            data-package-id="{{ $package->id }}"
                                                            data-package-name="{{ $package->name }}">
                                                            {{ __('get_started') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-12 text-center">
                            <span class="text-danger">{{ __('no_packages_available') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <!-- pricing ends here  -->

        {{-- @if (isset($settings['custom_package_status']) && $settings['custom_package_status'])
            <section class="customPack container commonMT">
                <div class="wrapper">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div>
                                <p class="title">{{ __('custom_package') }}</p>
                                <p class="desc">
                                    {{ $settings['custom_package_description'] ?? '' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <a href="#contact-us" class="commonBtn text-center">{{ __('get_in_touch') }}</a>
                        </div>

                    </div>
                </div>
            </section>
        @endif --}}

        @if (count($faqs))
            <section class="faqs commonMT" id="faq">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="sectionTitle">
                                <span>{{ __('frequently_asked_questions') }}</span>

                            </div>
                        </div>

                        <div class="col-12">
                            <div class="accordion" id="accordionExample">
                                @foreach ($faqs as $faq)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne-{{ $faq->id }}" aria-expanded="true"
                                                aria-controls="collapseOne-{{ $faq->id }}">
                                                <span>
                                                    {{ $faq->title }}
                                                </span>
                                            </button>
                                        </h2>
                                        <div id="collapseOne-{{ $faq->id }}" class="accordion-collapse collapse"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <span>
                                                    {!! nl2br(e($faq->description)) !!}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <!-- faqs ends here  -->

       <section class="getInTouch commonMT" id="contact-us">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="sectionTitle">
                    <span class="greenText">{{ __('Mari tetap terhubung') }}</span>
                    <span>{{ __('Punya pertanyaan atau sekadar ingin menyapa? Kami senang mendengar kabar dari Anda.') }}
                    </span>
                </div>
                <div class="col-12">
                    <div class="row wrapper">
                        <div class="col-lg-6">
                            <form action="{{ url('contact') }}" method="post" role="form"
                                class="php-email-form mb-5 create-form-with-captcha">
                                @csrf
                                <div class="card">
                                    <div>
                                        <input type="text" required name="name" id="name"
                                            placeholder="{{ __('enter_your_name') }}">
                                    </div>
                                    <div>
                                        <input type="email" required name="email" id="email"
                                            placeholder="{{ __('enter_your_email') }}">
                                    </div>
                                    <div>
                                        <textarea name="message" required id="message" cols="30" rows="6"
                                            placeholder="{{ __('send_your_message') }}"></textarea>
                                    </div>
                                    @if (config('services.recaptcha.key') ?? '')
                                        <div>
                                            <div class="g-recaptcha" data-sitekey={{config('services.recaptcha.key')}}>
                                            </div>
                                        </div>
                                    @endif
                                    <div>
                                        <input type="submit" class="commonBtn" value="{{ __('send') }}">
                                    </div>
                                    <div>
                                        <img src="{{ asset('assets/landing_page_images/GetInTouchDots.png') }}"
                                            class="sideImg dots" alt="">
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-lg-6 infoBox">
                            <!-- TELEPON - Click to Call -->
                            <a href="tel:+6285700609999" class="infoWrapper" style="text-decoration: none; color: inherit; cursor: pointer; transition: all 0.3s ease;">
                                <div>
                                    <span class="icon"><i class="fa-solid fa-phone-volume"></i></span>
                                </div>
                                <div>
                                    <span>{{ __('Telepon') }}</span>
                                    <span>{{ __('mobile') }} : 085700609999</span>
                                </div>
                            </a>

                            <!-- EMAIL - Click to Send Email -->
                            <a href="mailto:support@juliet.co.id" class="infoWrapper" style="text-decoration: none; color: inherit; cursor: pointer; transition: all 0.3s ease;">
                                <div>
                                    <span class="icon"><i class="fa-solid fa-envelope-open-text"></i></span>
                                </div>
                                <div>
                                    <span>{{ __('Email') }}</span>
                                    <span>support@juliet.co.id</span>
                                </div>
                            </a>

                            <!-- LOKASI - Click to Open Google Maps -->
                            <a href="https://maps.google.com/?q=Jl.+Sono+Kembang+No.4-6,+Embong+Kaliasin,+Kec.+Genteng,+Surabaya,+Jawa+Timur+60271" 
                               target="_blank" 
                               class="infoWrapper" 
                               style="text-decoration: none; color: inherit; cursor: pointer; transition: all 0.3s ease;">
                                <div>
                                    <span class="icon"><i class="fa-solid fa-location-dot"></i></span>
                                </div>
                                <div>
                                    <span>{{ __('Lokasi') }}</span>
                                    <span>Jl. Sono Kembang No.4-6, Embong Kaliasin, Kec. Genteng, Surabaya, Jawa Timur 60271</span>
                                </div>
                            </a>

                            <div>
                                <img src="{{ asset('assets/landing_page_images/lineCircle.png') }}"
                                    class="lineCircle sideImg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

        {{-- <section class="ourApp container commonMT">
            <div class="row">
                <div class="col-lg-6">
                    <img src="{{ $settings['download_our_app_image'] ?? asset('assets/landing_page_images/ourApp.png') }}"
                        class="ourAppImg" alt="">
                </div>
                <div class="col-lg-6 content">
                    <div class="text">
                        <span class="title">{{ __('Download aplikasi kami sekarang') }}</span>
                        <span> --}}
                            {{-- {{ $settings['download_our_app_description'] ?? '' }} --}}
                                {{-- Satu platform digital yang membantu sekolah mengelola administrasi dan pembelajaran secara terpusat, muda digunakan, dan sesuai kebutuhan operasional sehari-hari.

                        </span>
                    </div>
                    <div class="storeImgs">
                        <a href="{{ $settings['app_link'] ?? '' }}" target="_blank"> <img
                                src="{{ asset('assets/landing_page_images/Google play.png') }}" alt=""> </a>
                        <a href="{{ $settings['ios_app_link'] ?? ''}}" target="_blank"> <img
                                src="{{ asset('assets/landing_page_images/iOS app Store.png') }}" alt=""> </a>
                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection --}}

@section('script')
    <script async src="https://www.google.com/recaptcha/api.js"></script>
    @foreach ($featureSections as $key => $section)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const tabs = document.querySelectorAll('.left-section-{{ $section->id }} .tab');
                const contents = document.querySelectorAll('.left-section-{{ $section->id }} .content');

                function switchTab(event, tabNumber) {
                    tabs.forEach((tab) => {
                        tab.classList.remove('active');
                    });

                    event.target.classList.add('active');

                    contents.forEach((content) => {
                        content.classList.remove('active');
                    });

                    contents[tabNumber - 1].classList.add('active');
                }

                tabs.forEach((tab, index) => {
                    tab.addEventListener('click', (event) => {
                        switchTab(event, index + 1);
                    });
                });

                setTimeout(() => {
                    tabs[0].click();
                }, 1000);
            });

            document.addEventListener('DOMContentLoaded', () => {
                const tabs = document.querySelectorAll('.right-section-{{ $section->id }} .tab');
                const contents = document.querySelectorAll('.right-section-{{ $section->id }} .content');

                function switchTab(event, tabNumber) {
                    tabs.forEach((tab) => {
                        tab.classList.remove('active');
                    });

                    event.target.classList.add('active');

                    contents.forEach((content) => {
                        content.classList.remove('active');
                    });

                    contents[tabNumber - 1].classList.add('active');
                }

                tabs.forEach((tab, index) => {
                    tab.addEventListener('click', (event) => {
                        switchTab(event, index + 1);
                    });
                });

                setTimeout(() => {
                    tabs[0].click();
                }, 1000);
            });
        </script>
    @endforeach
    <script>
        @if (Session::has('success'))
            $.toast({
                text: '{{ Session::get('success') }}',
                showHideTransition: 'slide',
                icon: 'success',
                loaderBg: '#f96868',
                position: 'top-right',
                bgColor: '#20CFB5'
            });
        @endif

        @if (Session::has('error'))
            $.toast({
                text: '{{ Session::get('error') }}',
                showHideTransition: 'slide',
                icon: 'error',
                loaderBg: '#f2a654',
                position: 'top-right',
                bgColor: '#FE7C96'
            });
        @endif
    </script>
@endsection

<script>
// PACKAGE LOCK FUNCTIONALITY
// ============================================
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("staticBackdrop");
    const packageSelect = document.getElementById("package_id");

    // Validasi element ada
    if (!modal || !packageSelect) {
        console.warn("Modal atau package select tidak ditemukan");
        return;
    }

    // Event saat modal dibuka
    modal.addEventListener("show.bs.modal", function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        const packageId = button.getAttribute("data-package-id");
        const packageName = button.getAttribute("data-package-name");

        if (packageId && packageName) {
            // Lock package
            lockPackage(packageId, packageName);
        } else {
            // Unlock package
            unlockPackage();
        }
    });

    // Event saat modal ditutup
    modal.addEventListener("hidden.bs.modal", function () {
        unlockPackage();
    });

    // Function lock package
    function lockPackage(packageId, packageName) {
        // Set value
        packageSelect.value = packageId;
        
        // Disable dropdown
        packageSelect.disabled = true;
        packageSelect.style.backgroundColor = "#e9ecef";
        packageSelect.style.cursor = "not-allowed";
        packageSelect.style.opacity = "0.7";
        
        // Buat hidden input untuk submit
        let hiddenInput = document.getElementById("package_id_hidden");
        if (!hiddenInput) {
            hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = "package_id";
            hiddenInput.id = "package_id_hidden";
            packageSelect.parentElement.appendChild(hiddenInput);
        }
        hiddenInput.value = packageId;
        
        // Tampilkan badge
        showBadge(packageName);
    }

    // Function unlock package
    function unlockPackage() {
        packageSelect.disabled = false;
        packageSelect.style.backgroundColor = "";
        packageSelect.style.cursor = "";
        packageSelect.style.opacity = "";
        packageSelect.value = "";
        
        // Hapus hidden input
        const hiddenInput = document.getElementById("package_id_hidden");
        if (hiddenInput) {
            hiddenInput.remove();
        }
        
        // Hapus badge
        removeBadge();
    }

    // Function tampilkan badge
    function showBadge(packageName) {
        const wrapper = packageSelect.parentElement;
        let badge = wrapper.querySelector('.package-locked-badge');
        
        // if (!badge) {
        //     badge = document.createElement('div');
        //     badge.className = 'package-locked-badge';
        //     badge.style.cssText = `
        //         margin-top: 10px;
        //         padding: 12px 16px;
        //         background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        //         border: 1px solid #28a745;
        //         border-radius: 8px;
        //         color: #155724;
        //         font-size: 14px;
        //         font-weight: 500;
        //         display: flex;
        //         align-items: center;
        //         gap: 10px;
        //         box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        //     `;
        //     wrapper.appendChild(badge);
        // }
        
        // badge.innerHTML = `
        //     <i class="fa-solid fa-lock" style="color: #28a745;"></i>
        //     <div style="flex: 1;">
        //         <div style="font-size: 12px; opacity: 0.8;">Package terpilih:</div>
        //         <div style="font-weight: 600; color: #28a745;">${packageName}</div>
        //     </div>
        //     <i class="fa-solid fa-check-circle" style="color: #28a745;"></i>
        // `;
    }

    // Function hapus badge
    function removeBadge() {
        const wrapper = packageSelect.parentElement;
        const badge = wrapper.querySelector('.package-locked-badge');
        if (badge) {
            badge.remove();
        }
    }

    // Prevent manual change saat disabled
    packageSelect.addEventListener("mousedown", function(e) {
        if (this.disabled) {
            e.preventDefault();
            return false;
        }
    });

    // console.log("✅ Package Lock System ready");
    
});
</script>