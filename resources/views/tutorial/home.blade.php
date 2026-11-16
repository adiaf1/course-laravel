@extends('layouts.tutorial')

@section('title', 'Portal Belajar Pemrograman Web — Daftar Tema Tutorial')

@section('topbar')
<header class="topbar">
    <div class="topbar-left">
        <span class="topbar-brand-mini">Portal Tutorial</span>
    </div>
    <div class="topbar-home-title">
        <span>Pilih Tema Tutorial &amp; Mulai Belajar</span>
        <span class="badge-laravel">{{ count($courses) }} Tema Tersedia</span>
    </div>
</header>
@endsection

@section('content')
<main class="home-container">
    <section class="hero-card">
        <span class="hero-eyebrow">Portal Pembelajaran Terstruktur</span>
        <h1 class="hero-title">Pilih Tema Tutorial Sesuai Jalur Belajarmu</h1>
        <p class="hero-subtitle">
            Kumpulan modul praktikum langkah demi langkah yang disusun per hari dengan contoh kode terbaru, navigasi materi berurutan, dan pelacakan progres belajar.
        </p>
    </section>

    <div class="section-block">
        <h2 class="section-heading">Daftar Tema Tutorial</h2>
        <div class="courses-grid">
            @foreach($courses as $course)
                @php
                    $isPublished = ($course['status'] ?? '') === 'published';
                @endphp
                <article class="course-card {{ $isPublished ? '' : 'is-upcoming' }}">
                    <div>
                        <div class="course-card-top">
                            <span class="badge-laravel">{{ $course['badge'] }}</span>
                            @if($isPublished)
                                <span class="course-status is-ready">Tersedia</span>
                            @else
                                <span class="course-status is-soon">Segera Hadir</span>
                            @endif
                        </div>

                        <h3 class="course-card-title">{{ $course['title'] }}</h3>
                        <p class="course-card-desc">{{ $course['description'] }}</p>
                    </div>

                    <div class="course-card-footer">
                        <div class="course-meta-row">
                            <span>Level: <strong>{{ $course['level'] }}</strong></span>
                            <span>{{ $course['duration'] }}</span>
                        </div>

                        @if($isPublished)
                            <div class="course-card-actions">
                                <a href="{{ route('tutorial.course', $course['slug']) }}" class="btn-course-outline">
                                    Lihat Kurikulum
                                </a>
                                <a href="{{ route('tutorial.show', ['slug' => $course['slug'], 'day' => 1]) }}" class="btn-course-primary">
                                    Mulai Belajar &rarr;
                                </a>
                            </div>
                        @else
                            <div class="course-card-actions">
                                <span class="btn-course-disabled">Materi Sedang Disiapkan</span>
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</main>
@endsection
