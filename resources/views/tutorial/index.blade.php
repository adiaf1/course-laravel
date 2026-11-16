@extends('layouts.tutorial')

@section('title', "{$course['title']} — Kurikulum")

@section('topbar')
<header class="topbar">
    <div class="topbar-left">
        <a href="{{ route('tutorial.home') }}" class="topbar-back-btn" title="Kembali ke Daftar Tema Tutorial">
            &lsaquo;
        </a>
        <span class="topbar-brand-mini">{{ $course['short_title'] }}</span>
    </div>
    <div class="topbar-home-title">
        <span>Kurikulum &amp; Daftar Isi ({{ $totalDays }} Hari)</span>
        <a href="{{ route('tutorial.show', ['slug' => $course['slug'], 'day' => 1]) }}" class="badge-laravel">
            Mulai Hari 1 &rarr;
        </a>
    </div>
</header>
@endsection

@section('content')
<main class="home-container" data-course-slug="{{ $course['slug'] }}">
    <section class="hero-card">
        <span class="hero-eyebrow">{{ $course['badge'] }} &bull; {{ $course['level'] }}</span>
        <h1 class="hero-title">{{ $course['title'] }}</h1>
        <p class="hero-subtitle">{{ $course['description'] }}</p>
        <div class="hero-actions">
            <a href="{{ route('tutorial.show', ['slug' => $course['slug'], 'day' => 1]) }}" class="btn-hero-primary">
                Mulai Belajar Hari 1 &rarr;
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
                    <a href="{{ route('tutorial.show', ['slug' => $course['slug'], 'day' => $day['day']]) }}" class="day-card">
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
