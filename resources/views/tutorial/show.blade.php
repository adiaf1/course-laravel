@extends('layouts.tutorial')

@section('title', "Hari {$currentDay['day']}: {$currentDay['title']} — {$course['short_title']}")

@section('topbar')
<header class="topbar">
    <div class="topbar-left">
        <a href="{{ route('tutorial.course', $course['slug']) }}" class="topbar-back-btn" title="Kembali ke Daftar Kurikulum">
            &lsaquo;
        </a>
        <span class="topbar-brand-mini">{{ $course['badge'] }}</span>
        <button type="button" id="sidebar-toggle-btn" class="sidebar-toggle-btn">
            Daftar Materi
        </button>
    </div>

    <nav class="topbar-nav-split" aria-label="Navigasi Materi">
        @if($previousDay)
            <a href="{{ route('tutorial.show', ['slug' => $course['slug'], 'day' => $previousDay['day']]) }}" class="topbar-nav-btn">
                &larr; Previous (Hari {{ $previousDay['day'] }})
            </a>
        @else
            <span class="topbar-nav-btn is-disabled">&larr; Previous</span>
        @endif

        @if($nextDay)
            <a href="{{ route('tutorial.show', ['slug' => $course['slug'], 'day' => $nextDay['day']]) }}" class="topbar-nav-btn is-next">
                Next (Hari {{ $nextDay['day'] }}) &rarr;
            </a>
        @else
            <a href="{{ route('tutorial.course', $course['slug']) }}" class="topbar-nav-btn is-next">
                Selesai &bull; Daftar Kurikulum &#10003;
            </a>
        @endif
    </nav>
</header>
@endsection

@section('content')
<div class="lesson-shell" data-course-slug="{{ $course['slug'] }}">
    <aside id="tutorial-sidebar" class="sidebar">
        <div class="sidebar-header">
            <h2 class="sidebar-course-title">{{ $course['short_title'] }}</h2>
            <div class="sidebar-course-meta">
                <span>Hari {{ $currentDay['day'] }} dari {{ $totalDays }}</span>
                <a href="{{ route('tutorial.home') }}" class="badge-laravel">Semua Tema</a>
            </div>
        </div>

        @foreach($babs as $bab)
            @php
                $isCurrentBab = $bab['number'] === $currentDay['bab'];
            @endphp
            <div class="bab-group {{ $isCurrentBab ? 'is-open' : '' }}">
                <button type="button" class="bab-toggle">
                    <span>Bab {{ $bab['number'] }}: {{ $bab['title'] }} ({{ $bab['range'] }})</span>
                    <svg class="bab-chevron" width="16" height="16" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div class="bab-days">
                    @foreach($bab['days'] as $dayItem)
                        <a href="{{ route('tutorial.show', ['slug' => $course['slug'], 'day' => $dayItem['day']]) }}"
                           class="sidebar-day-link {{ $dayItem['day'] === $currentDay['day'] ? 'is-active' : '' }}">
                            <span>Hari {{ $dayItem['day'] }}: {{ $dayItem['title'] }}</span>
                            <span class="day-done-dot" data-day-badge="{{ $dayItem['day'] }}"></span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </aside>

    <main class="lesson-main">
        <article class="lesson-container">
            <div class="lesson-meta-bar">
                <span class="lesson-bab-pill">
                    BAB {{ $currentDay['bab'] }} &bull; {{ $currentDay['bab_range'] }}
                </span>
                <button type="button" id="complete-toggle-btn" class="complete-toggle-btn" data-day="{{ $currentDay['day'] }}">
                    Tandai Selesai
                </button>
            </div>

            <h1 class="lesson-title">Hari {{ $currentDay['day'] }}: {{ $currentDay['title'] }}</h1>

            <section class="section-block">
                <h2 class="section-heading">Tujuan Pembelajaran</h2>
                <ul class="clean-list">
                    @foreach($currentDay['objectives'] as $objective)
                        <li>{{ $objective }}</li>
                    @endforeach
                </ul>
            </section>

            @foreach($currentDay['blocks'] as $block)
                @if($block['type'] === 'text')
                    <section class="section-block">
                        <h2 class="section-heading">{{ $block['heading'] }}</h2>
                        @foreach($block['paragraphs'] as $paragraph)
                            <p class="section-text">{{ $paragraph }}</p>
                        @endforeach
                    </section>
                @elseif($block['type'] === 'list')
                    <section class="section-block">
                        <h2 class="section-heading">{{ $block['heading'] }}</h2>
                        <ul class="clean-list">
                            @foreach($block['items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </section>
                @elseif($block['type'] === 'table')
                    <section class="section-block">
                        <h2 class="section-heading">{{ $block['heading'] }}</h2>
                        <div class="data-table-wrap">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        @foreach($block['headers'] as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($block['rows'] as $row)
                                        <tr>
                                            @foreach($row as $cell)
                                                <td>{{ $cell }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @elseif($block['type'] === 'note')
                    <aside class="note-box">
                        @if(!empty($block['title']))
                            <strong>{{ $block['title'] }}</strong>
                        @endif
                        <span>{{ $block['content'] }}</span>
                    </aside>
                @elseif($block['type'] === 'step')
                    <section class="step-card">
                        <div class="step-header">
                            <span class="step-badge">{{ $block['label'] }}</span>
                            <h3 class="step-title">{{ $block['title'] }}</h3>
                        </div>
                        <p class="section-text">{{ $block['description'] }}</p>

                        @foreach($block['codes'] as $codeBlock)
                            <div class="code-wrapper">
                                <div class="code-header">
                                    <span class="code-filename">{{ $codeBlock['filename'] ?? strtoupper($codeBlock['language']) }}</span>
                                    <button type="button" class="copy-btn">Salin Kode</button>
                                </div>
                                <pre class="code-pre"><code>{{ $codeBlock['code'] }}</code></pre>
                            </div>
                        @endforeach
                    </section>
                @endif
            @endforeach

            @if(!empty($currentDay['tasks']))
                <aside class="tasks-box">
                    <h2 class="tasks-box-title">Tugas &amp; Catatan Penting Hari {{ $currentDay['day'] }}</h2>
                    <ul>
                        @foreach($currentDay['tasks'] as $task)
                            <li>{{ $task }}</li>
                        @endforeach
                    </ul>
                </aside>
            @endif

            <nav class="lesson-footer-nav" aria-label="Navigasi Lanjut Materi">
                @if($previousDay)
                    <a href="{{ route('tutorial.show', ['slug' => $course['slug'], 'day' => $previousDay['day']]) }}" class="nav-card-btn">
                        <span class="nav-card-label">&larr; Materi Sebelumnya</span>
                        <span class="nav-card-title">Hari {{ $previousDay['day'] }}: {{ $previousDay['title'] }}</span>
                    </a>
                @else
                    <a href="{{ route('tutorial.course', $course['slug']) }}" class="nav-card-btn">
                        <span class="nav-card-label">&larr; Kurikulum</span>
                        <span class="nav-card-title">Daftar Isi {{ $totalDays }} Hari</span>
                    </a>
                @endif

                @if($nextDay)
                    <a href="{{ route('tutorial.show', ['slug' => $course['slug'], 'day' => $nextDay['day']]) }}" class="nav-card-btn is-primary">
                        <span class="nav-card-label">Lanjutkan Materi &rarr;</span>
                        <span class="nav-card-title">Hari {{ $nextDay['day'] }}: {{ $nextDay['title'] }}</span>
                    </a>
                @else
                    <a href="{{ route('tutorial.course', $course['slug']) }}" class="nav-card-btn is-primary">
                        <span class="nav-card-label">Kurikulum Selesai &#10003;</span>
                        <span class="nav-card-title">Kembali ke Daftar Kurikulum</span>
                    </a>
                @endif
            </nav>
        </article>
    </main>
</div>
@endsection
