@extends('layouts.tutorial')

@section('title', 'Belajar Laravel 30 Hari — Laravel 13')

@section('topbar')
<header class="topbar">
    <div class="topbar-left">
        <span class="topbar-brand-mini">Laravel 13 • 30 Hari</span>
    </div>
    <div class="topbar-home-title">
        <span>Modul Pembelajaran & Praktikum Terstruktur</span>
        <a href="{{ route('tutorial.show', 1) }}" class="badge-laravel">Mulai Hari 1 →</a>
    </div>
</header>
@endsection

@section('content')
<main class="home-container">
    <section class="hero-card">
        <span class="hero-eyebrow">Kurikulum Lengkap • Laravel 13 & PHP 8.3+</span>
        <h1 class="hero-title">Belajar Laravel 30 Hari: Dari Dasar Hingga Deployment GitHub</h1>
        <p class="hero-subtitle">
            Studi kasus lengkap membangun Aplikasi Manajemen Catatan Pribadi berbasis Laravel 13, MySQL, Bootstrap 5, dan Laravel Breeze. Disusun sistematis dalam 6 BAB (Hari 1 sampai Hari 30).
        </p>
        <div class="hero-actions">
            <a href="{{ route('tutorial.show', 1) }}" class="btn-hero-primary">
                Mulai Belajar Hari 1 →
            </a>
            <div class="progress-pill">
                Progres Belajar: <span id="completed-count">0</span> / {{ $totalDays }} Hari Selesai
            </div>
        </div>
    </section>

    @foreach($babs as $bab)
        <section class="bab-section-card">
            <div class="bab-section-head">
                <h2 class="bab-section-title">BAB {{ $bab['number'] }}: {{ $bab['title'] }}</h2>
                <span class="bab-section-range">{{ $bab['range'] }}</span>
            </div>
            <p class="bab-section-focus">{{ $bab['focus'] }}</p>

            <div class="day-grid">
                @foreach($bab['days'] as $day)
                    <a href="{{ route('tutorial.show', $day['day']) }}" class="day-card">
                        <div>
                            <div class="day-card-top">
                                <span class="day-number">Hari {{ $day['day'] }}</span>
                                <span class="day-done-dot" data-day-badge="{{ $day['day'] }}"></span>
                            </div>
                            <h3 class="day-card-title">{{ $day['title'] }}</h3>
                            <p class="day-card-summary">{{ $day['summary'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endforeach
</main>
@endsection
