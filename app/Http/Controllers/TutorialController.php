<?php

namespace App\Http\Controllers;

use App\Services\TutorialRepository;
use Illuminate\View\View;

class TutorialController extends Controller
{
    public function __construct(public TutorialRepository $tutorials) {}

    /**
     * Display the curriculum overview page.
     */
    public function index(): View
    {
        return view('tutorial.index', [
            'babs' => $this->tutorials->allBabs(),
            'totalDays' => $this->tutorials->totalDays(),
        ]);
    }

    /**
     * Display a single tutorial day page.
     */
    public function show(int $day): View
    {
        $currentDay = $this->tutorials->findDay($day);

        if ($currentDay === null) {
            abort(404);
        }

        $totalDays = $this->tutorials->totalDays();
        $previousDay = $day > 1 ? $this->tutorials->findDay($day - 1) : null;
        $nextDay = $day < $totalDays ? $this->tutorials->findDay($day + 1) : null;

        return view('tutorial.show', [
            'babs' => $this->tutorials->allBabs(),
            'currentDay' => $currentDay,
            'previousDay' => $previousDay,
            'nextDay' => $nextDay,
            'totalDays' => $totalDays,
        ]);
    }
}
