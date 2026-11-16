<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Belajar Laravel 30 Hari')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/tutorial.css') }}">
</head>
<body>
    @yield('topbar')
    @yield('content')

    <script>
        (function () {
            const slugEl = document.querySelector('[data-course-slug]');
            const courseSlug = slugEl ? slugEl.getAttribute('data-course-slug') : 'default';
            const STORAGE_KEY = 'tutorial_completed_' + courseSlug;

            function getCompletedDays() {
                try {
                    const raw = localStorage.getItem(STORAGE_KEY);
                    const parsed = raw ? JSON.parse(raw) : [];
                    return Array.isArray(parsed) ? parsed : [];
                } catch (e) {
                    return [];
                }
            }

            function saveCompletedDays(days) {
                try {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(days));
                } catch (e) {
                    // Ignore storage errors
                }
            }

            function renderProgressState() {
                const completed = getCompletedDays();

                document.querySelectorAll('[data-day-badge]').forEach(function (el) {
                    const day = Number(el.getAttribute('data-day-badge'));
                    el.textContent = completed.includes(day) ? '✓' : '';
                });

                const countEl = document.getElementById('completed-count');
                if (countEl) {
                    countEl.textContent = completed.length;
                }

                const toggleBtn = document.getElementById('complete-toggle-btn');
                if (toggleBtn) {
                    const day = Number(toggleBtn.getAttribute('data-day'));
                    const isDone = completed.includes(day);
                    toggleBtn.classList.toggle('is-completed', isDone);
                    toggleBtn.textContent = isDone ? '✓ Materi Selesai' : 'Tandai Selesai';
                }
            }

            const toggleBtn = document.getElementById('complete-toggle-btn');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    const day = Number(this.getAttribute('data-day'));
                    let completed = getCompletedDays();
                    if (completed.includes(day)) {
                        completed = completed.filter(function (item) {
                            return item !== day;
                        });
                    } else {
                        completed.push(day);
                    }
                    saveCompletedDays(completed);
                    renderProgressState();
                });
            }

            document.querySelectorAll('.bab-toggle').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const group = this.closest('.bab-group');
                    if (group) {
                        group.classList.toggle('is-open');
                    }
                });
            });

            const sidebar = document.getElementById('tutorial-sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle-btn');
            if (sidebar && sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('is-open');
                });
            }

            document.querySelectorAll('.copy-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const wrapper = this.closest('.code-wrapper');
                    const codeEl = wrapper ? wrapper.querySelector('code') : null;
                    if (!codeEl) {
                        return;
                    }

                    navigator.clipboard.writeText(codeEl.textContent).then(() => {
                        const original = this.textContent;
                        this.textContent = 'Tersalin!';
                        setTimeout(() => {
                            this.textContent = original;
                        }, 1500);
                    });
                });
            });

            renderProgressState();
        })();
    </script>
</body>
</html>
