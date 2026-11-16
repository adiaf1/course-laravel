<?php

namespace Tests\Feature;

use Tests\TestCase;

class TutorialTest extends TestCase
{
    public function test_home_page_displays_tutorial_themes(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Daftar Tema Tutorial');
        $response->assertSee('Belajar Laravel 30 Hari: Dari Dasar Hingga Deployment GitHub');
        $response->assertSee('/kursus/laravel-30-hari');
    }

    public function test_course_curriculum_page_displays_all_babs_and_days(): void
    {
        $response = $this->get('/kursus/laravel-30-hari');

        $response->assertStatus(200);
        $response->assertSee('BAB 1: Fondasi Dasar Laravel, Routing, Blade & Controller');
        $response->assertSee('BAB 6: Ekspor/Impor Data CSV & Deployment Portofolio GitHub');
        $response->assertSee('/kursus/laravel-30-hari/hari/1');
        $response->assertSee('/kursus/laravel-30-hari/hari/30');
    }

    public function test_all_thirty_tutorial_days_render_successfully(): void
    {
        for ($day = 1; $day <= 30; $day++) {
            $response = $this->get("/kursus/laravel-30-hari/hari/{$day}");

            $response->assertStatus(200);
            $response->assertSee("Hari {$day}:");
        }
    }

    public function test_rest_api_course_and_fourteen_days_render_successfully(): void
    {
        $this->get('/kursus/laravel-rest-api')
            ->assertStatus(200)
            ->assertSee('Membangun RESTful API dengan Laravel 13 & Sanctum');

        for ($day = 1; $day <= 14; $day++) {
            $response = $this->get("/kursus/laravel-rest-api/hari/{$day}");

            $response->assertStatus(200);
            $response->assertSee("Hari {$day}:");
        }

        $this->get('/kursus/laravel-rest-api/hari/15')->assertStatus(404);
    }

    public function test_legacy_day_url_redirects_to_course_day(): void
    {
        $this->get('/hari/1')->assertRedirect('/kursus/laravel-30-hari/hari/1');
    }

    public function test_invalid_day_or_course_returns_404(): void
    {
        $this->get('/kursus/laravel-30-hari/hari/0')->assertStatus(404);
        $this->get('/kursus/laravel-30-hari/hari/31')->assertStatus(404);
        $this->get('/kursus/tidak-ada')->assertStatus(404);
    }
}
