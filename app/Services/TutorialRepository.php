<?php

namespace App\Services;

class TutorialRepository
{
    /**
     * Get all BAB metadata along with their days.
     *
     * @return array<int, array<string, mixed>>
     */
    public function allBabs(): array
    {
        $babs = [];

        for ($i = 1; $i <= 6; $i++) {
            $path = resource_path("data/babs/bab-{$i}.php");

            if (file_exists($path)) {
                $babs[] = require $path;
            }
        }

        return $babs;
    }

    /**
     * Get a flat map of all days keyed by day number (1-30).
     *
     * @return array<int, array<string, mixed>>
     */
    public function allDays(): array
    {
        $days = [];

        foreach ($this->allBabs() as $bab) {
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
     * Find a specific tutorial day by number.
     *
     * @return array<string, mixed>|null
     */
    public function findDay(int $day): ?array
    {
        $days = $this->allDays();

        return $days[$day] ?? null;
    }

    /**
     * Get total number of tutorial days.
     */
    public function totalDays(): int
    {
        return count($this->allDays());
    }
}
