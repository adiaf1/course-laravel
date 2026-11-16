<?php

namespace Tests\Feature;

use Tests\TestCase;

class TutorialTest extends TestCase
{
    public function test_home_page_displays_all_babs_and_days(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Belajar Laravel 30 Hari');
        $response->assertSee('Laravel 13');
        $response->assertSee('BAB 1: Fondasi Dasar Laravel, Routing, Blade & Controller');
        $response->assertSee('BAB 6: Ekspor/Impor Data CSV & Deployment Portofolio GitHub');
        $response->assertSee('/hari/1');
        $response->assertSee('/hari/30');
    }

    public function test_all_thirty_tutorial_days_render_successfully(): void
    {
        for ($day = 1; $day <= 30; $day++) {
            $response = $this->get("/hari/{$day}");

            $response->assertStatus(200);
            $response->assertSee("Hari {$day}:");
        }
    }

    public function test_day_navigation_links_work_as_expected(): void
    {
        $day1 = $this->get('/hari/1');
        $day1->assertStatus(200);
        $day1->assertSee('Next (Hari 2)');
        $day1->assertSee('Lanjutkan Materi');
        $day1->assertSee(route('tutorial.show', 2), false);

        $day15 = $this->get('/hari/15');
        $day15->assertStatus(200);
        $day15->assertSee('Previous (Hari 14)');
        $day15->assertSee('Next (Hari 16)');

        $day30 = $this->get('/hari/30');
        $day30->assertStatus(200);
        $day30->assertSee('Previous (Hari 29)');
        $day30->assertSee('Kembali ke Daftar Kurikulum');
    }

    public function test_invalid_day_returns_404(): void
    {
        $this->get('/hari/0')->assertStatus(404);
        $this->get('/hari/31')->assertStatus(404);
    }
}
