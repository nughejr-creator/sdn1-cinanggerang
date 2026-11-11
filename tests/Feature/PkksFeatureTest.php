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

        // Buka dengan session asesor unlocked
        $response = $this->withSession(['pkks_assessor_unlocked' => true])->get('/e-pkks');
        $response->assertStatus(200);

        // Tidak boleh ada tombol 'Mode Edit Admin' dan 'Login Editor'
        $response->assertDontSee('Mode Edit Admin');
        $response->assertDontSee('Login Editor');
        $response->assertDontSee('✏️ Edit Profil & Riwayat Pimpinan di Panel Admin');

        // Harus ada tombol Dokumen Eksekutif dan Lembar Telaah Asesor
        $response->assertSee('Unduh CV (PDF)');
        $response->assertSee('Pakta Integritas');
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

    public function test_pkks_evidence_embed_urls_support_tiktok_and_youtube_and_gdrive(): void
    {
        $tiktok = new \App\Models\PkksEvidence([
            'external_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7559429345354288395',
        ]);
        $this->assertEquals('https://www.tiktok.com/embed/v2/7559429345354288395', $tiktok->embed_url);

        $youtube = new \App\Models\PkksEvidence([
            'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);
        $this->assertEquals('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ?autoplay=1&rel=0', $youtube->embed_url);

        $gdrive = new \App\Models\PkksEvidence([
            'external_url' => 'https://drive.google.com/file/d/14OBTBm-pTOgP-YJ7Wffzz6VCqxqwyxoG/view?usp=sharing',
        ]);
        $this->assertEquals('https://drive.google.com/file/d/14OBTBm-pTOgP-YJ7Wffzz6VCqxqwyxoG/preview', $gdrive->embed_url);
    }

    public function test_headmaster_cv_and_integrity_pact_links_are_dynamic_on_pkks_page(): void
    {
        $headmaster = \App\Models\HeadmasterProfile::first();
        if ($headmaster) {
            $headmaster->update([
                'cv_pdf_path' => 'https://drive.google.com/file/d/15S9CtAGN42zF5RQWovowb1oAJb1raSeq/view?usp=sharing',
                'integrity_pact_path' => 'https://drive.google.com/file/d/TEST_PAKTA_INTEGRITAS_123/view?usp=sharing',
            ]);
        }

        $response = $this->get('/e-pkks');
        $response->assertStatus(200);
        $response->assertSee('15S9CtAGN42zF5RQWovowb1oAJb1raSeq');
        $response->assertSee('TEST_PAKTA_INTEGRITAS_123');
        $response->assertSee('Unduh CV (PDF)');
        $response->assertSee('Pakta Integritas');
    }

    public function test_headmaster_histories_rendered_dynamically_with_theater_view(): void
    {
        $headmaster = \App\Models\HeadmasterProfile::first();
        if ($headmaster) {
            $headmaster->histories()->delete();

            $headmaster->histories()->createMany([
                [
                    'type' => 'organization',
                    'title' => 'Ketua Kwartir Ranting Kecamatan Pamulihan',
                    'organizer' => 'Kwaran Pamulihan',
                    'year' => '2025 - Sekarang',
                ],
                [
                    'type' => 'education',
                    'title' => 'S1 Pendidikan Guru Sekolah Dasar (PGSD)',
                    'organizer' => 'Universitas Pendidikan Indonesia (UPI)',
                    'year' => '2008',
                ],
                [
                    'type' => 'training',
                    'title' => 'Bimtek Transformasi Digital Pendidikan',
                    'organizer' => 'Disdik Sumedang',
                    'year' => '2026',
                ],
            ]);
        }

        $response = $this->get('/e-pkks');
        $response->assertStatus(200);

        // Harus menampilkan riwayat yang baru saja dibuat di database
        $response->assertSee('Ketua Kwartir Ranting Kecamatan Pamulihan');
        $response->assertSee('S1 Pendidikan Guru Sekolah Dasar (PGSD)');
        $response->assertSee('Bimtek Transformasi Digital Pendidikan');

        // Harus ada tombol Theater View
        $response->assertSee('Buka Portofolio Lengkap (Theater View)');

        // Harus ada TMT Kepala Sekolah dan Pendidikan Terakhir
        $response->assertSee('TMT Kepala Sekolah');
        $response->assertSee('Pendidikan Terakhir');

        // Harus menampilkan nama kepala sekolah tanpa kata Bpk.
        $response->assertSee('Aep Yayat, S.Pd');
        $response->assertDontSee('Bpk. Aep Yayat, S.Pd');
    }
}
