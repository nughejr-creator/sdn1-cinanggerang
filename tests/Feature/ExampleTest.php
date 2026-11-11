<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'test',
        ]);
    }

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Satuan Pendidikan Dasar Negeri • NPSN', false);
        $response->assertDontSee('Satuan Pendidikan Dasar Negeri Resmi');
        $response->assertSee('Pendidikan Unggul, Berkarakter', false);
        $response->assertSee('Presensi Digital', false);
    }

    public function test_developer_profile_page_loads_successfully(): void
    {
        $response = $this->get('/pengembang');

        $response->assertStatus(200);
        $response->assertSee('Agi Hamdani, M.Pd.', false);
        $response->assertSee('S2 Magister PGSD', false);
        $response->assertSee('Kampus Cibiru', false);
        $response->assertSee('S1 Pendidikan Guru Sekolah Dasar (PGSD)', false);
        $response->assertSee('Kampus Sumedang', false);
        $response->assertSee('sasipedia.my.id', false);
        $response->assertSee('Profil Pengembang Website', false);
        $response->assertSee('Sistem & Rekayasa Perangkat Lunak', false);
        $response->assertSee('SPESIFIKASI TEKNIS & ALAT PENGEMBANGAN', false);
        $response->assertSee('Modern Jamstack', false);
        $response->assertSee('offline-resilient', false);
        $response->assertSee('082118449525', false);
        $response->assertSee('@nughe_jr', false);
        $response->assertSee('Abdi Jalmi Alit', false);
        $response->assertSee('2019 – Sekarang', false);
    }
}
