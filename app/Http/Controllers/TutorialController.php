<?php

namespace App\Http\Controllers;

use App\Services\TutorialRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TutorialController extends Controller
{
    public function __construct(public TutorialRepository $tutorials) {}

    /**
     * Display the main portal page with all available tutorial themes.
     */
    public function home(): View
    {
        return view('tutorial.home', [
            'courses' => $this->tutorials->allCourses(),
        ]);
    }

    /**
     * Display the curriculum overview page for a specific tutorial theme.
     */
    public function course(string $slug): View
    {
        $course = $this->tutorials->findCourse($slug);

        if ($course === null || ($course['status'] ?? '') !== 'published') {
            abort(404);
        }

        return view('tutorial.index', [
            'course' => $course,
            'babs' => $this->tutorials->allBabs($slug),
            'totalDays' => $this->tutorials->totalDays($slug),
        ]);
    }

    /**
     * Display a single tutorial day page for a specific tutorial theme.
     */
    public function show(string $slug, int $day): View
    {
        $course = $this->tutorials->findCourse($slug);

        if ($course === null || ($course['status'] ?? '') !== 'published') {
            abort(404);
        }

        $currentDay = $this->tutorials->findDay($day, $slug);

        if ($currentDay === null) {
            abort(404);
        }

        $totalDays = $this->tutorials->totalDays($slug);
        $previousDay = $day > 1 ? $this->tutorials->findDay($day - 1, $slug) : null;
        $nextDay = $day < $totalDays ? $this->tutorials->findDay($day + 1, $slug) : null;

        return view('tutorial.show', [
            'course' => $course,
            'babs' => $this->tutorials->allBabs($slug),
            'currentDay' => $currentDay,
            'previousDay' => $previousDay,
            'nextDay' => $nextDay,
            'totalDays' => $totalDays,
        ]);
    }

    /**
     * Redirect legacy /hari/{day} URLs to the default Laravel 30 Hari course.
     */
    public function legacyDayRedirect(int $day): RedirectResponse
    {
        return redirect()->route('tutorial.show', [
            'slug' => 'laravel-30-hari',
            'day' => $day,
        ]);
    }
}
