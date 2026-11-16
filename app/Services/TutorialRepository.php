<?php

namespace App\Services;

class TutorialRepository
{
    /**
     * Get all registered tutorial themes/courses.
     *
     * @return array<int, array<string, mixed>>
     */
    public function allCourses(): array
    {
        $path = resource_path('data/courses.php');

        if (! file_exists($path)) {
            return [];
        }

        $courses = require $path;

        return array_map(function (array $course): array {
            $babs = $this->allBabs($course['slug'], $course);
            $totalDays = 0;

            foreach ($babs as $bab) {
                $totalDays += count($bab['days'] ?? []);
            }

            $course['total_babs'] = count($babs);
            $course['total_days'] = $totalDays;

            return $course;
        }, $courses);
    }

    /**
     * Find a course by its slug.
     *
     * @return array<string, mixed>|null
     */
    public function findCourse(string $slug): ?array
    {
        foreach ($this->allCourses() as $course) {
            if ($course['slug'] === $slug) {
                return $course;
            }
        }

        return null;
    }

    /**
     * Get all BAB metadata along with their days for a course.
     *
     * @param  array<string, mixed>|null  $courseOverride
     * @return array<int, array<string, mixed>>
     */
    public function allBabs(string $courseSlug = 'laravel-30-hari', ?array $courseOverride = null): array
    {
        $course = $courseOverride;

        if ($course === null) {
            $path = resource_path('data/courses.php');
            $courses = file_exists($path) ? require $path : [];

            foreach ($courses as $item) {
                if ($item['slug'] === $courseSlug) {
                    $course = $item;
                    break;
                }
            }
        }

        $babsPath = $course['babs_path'] ?? null;

        if (! is_string($babsPath) || ! is_dir($babsPath)) {
            return [];
        }

        $files = glob($babsPath . DIRECTORY_SEPARATOR . 'bab-*.php') ?: [];
        natsort($files);

        $babs = [];
        foreach ($files as $file) {
            $babs[] = require $file;
        }

        return $babs;
    }

    /**
     * Get a flat map of all days keyed by day number for a course.
     *
     * @return array<int, array<string, mixed>>
     */
    public function allDays(string $courseSlug = 'laravel-30-hari'): array
    {
        $days = [];

        foreach ($this->allBabs($courseSlug) as $bab) {
            foreach ($bab['days'] as $day) {
                $day['bab'] = $bab['number'];
                $day['bab_title'] = $bab['title'];
                $day['bab_range'] = $bab['range'];
                $days[$day['day']] = $day;
            }
        }

        ksort($days);

        return $days;
    }

    /**
     * Find a specific tutorial day by number for a course.
     *
     * @return array<string, mixed>|null
     */
    public function findDay(int $day, string $courseSlug = 'laravel-30-hari'): ?array
    {
        $days = $this->allDays($courseSlug);

        return $days[$day] ?? null;
    }

    /**
     * Get total number of tutorial days for a course.
     */
    public function totalDays(string $courseSlug = 'laravel-30-hari'): int
    {
        return count($this->allDays($courseSlug));
    }
}
