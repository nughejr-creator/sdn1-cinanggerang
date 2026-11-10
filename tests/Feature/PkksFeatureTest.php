<?php

namespace Tests\Feature;

use App\Models\PkksAssessorReview;
use App\Models\PkksPeriod;
use App\Models\SchoolProfile;
use Tests\TestCase;

class PkksFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'test',
        ]);
    }

    public function test_pkks_page_loads_and_does_not_show_edit_buttons(): void
    {
        // Pastikan pkks_status active
        $profile = SchoolProfile::first();
        if ($profile) {
            $profile->update(['pkks_status' => 'active']);
        }

        $response = $this->get('/e-pkks');
        $response->assertStatus(200);

        // Tidak boleh ada tombol 'Mode Edit Admin' dan 'Login Editor'
        $response->assertDontSee('Mode Edit Admin');
        $response->assertDontSee('Login Editor');
        $response->assertDontSee('✏️ Edit Profil & Riwayat Pimpinan di Panel Admin');

        // Harus ada tombol Buka Dokumen dan Lembar Telaah Asesor
        $response->assertSee('Buka Dokumen');
        $response->assertSee('Lembar Telaah & Catatan Asesor Penilai');
    }

    public function test_store_assessor_review_saves_to_database(): void
    {
        $payload = [
            'assessor_name' => 'Dr. H. Ahmad Sudrajat, M.Pd',
            'assessor_agency' => 'Pengawas Pembina Disdik Wilayah Sumedang',
            'recommendation_status' => 'Sangat Baik / Terpenuhi Penuh',
            'performance_review' => 'Dokumen eviden sangat lengkap dan tertata secara sistematis.',
            'follow_up_recommendation' => 'Pertahankan inovasi digitalisasi sekolah.',
        ];

        $response = $this->postJson('/e-pkks/review', $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('pkks_assessor_reviews', [
            'assessor_name' => 'Dr. H. Ahmad Sudrajat, M.Pd',
            'recommendation_status' => 'Sangat Baik / Terpenuhi Penuh',
        ]);

        // Clean up test review
        PkksAssessorReview::where('assessor_name', 'Dr. H. Ahmad Sudrajat, M.Pd')->delete();
    }

    public function test_pkks_standby_screen_rendered_when_inactive(): void
    {
        $profile = SchoolProfile::first();
        if ($profile) {
            $profile->update(['pkks_status' => 'inactive']);
        }

        $response = $this->get('/e-pkks');
        $response->assertStatus(200);
        $response->assertSee('Periode Penilaian Belum Dibuka / Telah Berakhir');
        $response->assertSee('Mohon maaf, portal Penilaian Kinerja Kepala Sekolah (e-PKKS) SDN Cinanggerang I saat ini sedang ditutup');

        // Kembalikan ke active
        if ($profile) {
            $profile->update(['pkks_status' => 'active']);
        }
    }
}
