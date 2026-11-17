<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\VideoGallery;
use App\Models\PkksEvidence;

return new class extends Migration
{
    public function up(): void
    {
        $mapping = [
            [
                'judul' => 'Kegiatan MPLS Ramah Tahun Ajaran 2026/2027 (Bagian 1)',
                'old_tiktok_id' => '7661914216118373653',
                'gdrive_url' => 'https://drive.google.com/file/d/1AP_FtrBc139kggxG6tJK04tD5eRUaqwi/view?usp=drive_link',
            ],
            [
                'judul' => 'Kegiatan MPLS Ramah Tahun Ajaran 2026/2027 (Bagian 2)',
                'old_tiktok_id' => '7663881708583882004',
                'gdrive_url' => 'https://drive.google.com/file/d/1kYKO-nYsM20TlVxhLtIeMKOFYoVzQact/view?usp=drive_link',
            ],
            [
                'judul' => 'Kegiatan MPLS Ramah Tahun Ajaran 2026/2027 (Bagian 3)',
                'old_tiktok_id' => '7662964146060741908',
                'gdrive_url' => 'https://drive.google.com/file/d/1DSyLkyrMF8eKPq7mM65DVXxtzJR6VHNn/view?usp=drive_link',
            ],
            [
                'judul' => 'Upacara Peringatan Hari Pramuka ke-65 Tahun 2026',
                'old_tiktok_id' => '7674611344506752277',
                'gdrive_url' => 'https://drive.google.com/file/d/1pohWdm2eukikDpBcZD-0KYKjrBtwXD8k/view?usp=drive_link',
            ],
            [
                'judul' => 'Keseruan Lomba-Lomba Memperingati HUT RI Tahun 2026',
                'old_tiktok_id' => '7676745078852963605',
                'gdrive_url' => 'https://drive.google.com/file/d/1Ep77-qouGOF9wbkEUx9Q8vcsrh1a4-_o/view?usp=drive_link',
            ],
            [
                'judul' => 'Ajang Fashion Show Busana Adat Peringatan Hari Kartini',
                'old_tiktok_id' => '7634746577797598485',
                'gdrive_url' => 'https://drive.google.com/file/d/1bBcMStWr5pbtn96Cqzy7zGHF8EsWmBQb/view?usp=drive_link',
            ],
            [
                'judul' => 'Kegiatan Pesantren Kilat (Sanlat) Ramadhan Tahun 2026',
                'old_tiktok_id' => '7617424080391654677',
                'gdrive_url' => 'https://drive.google.com/file/d/1BHiIx9hiAZUoAz_0lb7jVHKjmh0WWB6J/view?usp=drive_link',
            ],
            [
                'judul' => 'Screening & Pemeriksaan Kesehatan Berkala Bersama Puskesmas Haurngombong',
                'old_tiktok_id' => '7615459280682159381',
                'gdrive_url' => 'https://drive.google.com/file/d/1KjtXaHzFoDZvRrHQegdJD1fNggDVJuD_/view?usp=drive_link',
            ],
            [
                'judul' => 'Perkemahan Jumat-Sabtu (Perjumsa) Gugus Depan SDN Cinanggerang I',
                'old_tiktok_id' => '7544024476518812933',
                'gdrive_url' => 'https://drive.google.com/file/d/1vsCb4Kf5cg7gQ1RcavT0nr9nyMXOp7yo/view?usp=drive_link',
            ],
            [
                'judul' => 'Pemanfaatan Sampah Plastik Ramah Lingkungan (Gelar Karya P5)',
                'old_tiktok_id' => '7541363334671633720',
                'gdrive_url' => 'https://drive.google.com/file/d/1uqy6MzPdlUnmNUivJm0VBIPE33JLdDyn/view?usp=drive_link',
            ],
            [
                'judul' => 'Praktik Pembelajaran Inovatif Self-Regulated Learning (SRL)',
                'old_tiktok_id' => '7542449483452386565',
                'gdrive_url' => 'https://drive.google.com/file/d/1dzftc8eVlyb4JN1_ot5hVKqCAVFUcNfw/view?usp=drive_link',
            ],
            [
                'judul' => 'Transformasi Menuju Sekolah Juara: Video Perubahan SDN Cinanggerang I',
                'old_tiktok_id' => '7542480505107696901',
                'gdrive_url' => 'https://drive.google.com/file/d/1YOdaiwG6GqM_H_ygoJ4k3HbAT1Zb--Ab/view?usp=drive_link',
            ],
            [
                'judul' => 'Aksi Nyata Pembiasaan: Kampanye Sekolah Bersih & Bebas Sampah',
                'old_tiktok_id' => '7543173878864973061',
                'gdrive_url' => 'https://drive.google.com/file/d/1d24UfCNR9V9CqYw-XQiIBID8H8K_bQW1/view?usp=drive_link',
            ],
            [
                'judul' => 'Pendidikan Kepramukaan: Perkemahan Jumat-Sabtu (Perjumsa)',
                'old_tiktok_id' => '7543866076468104453',
                'gdrive_url' => 'https://drive.google.com/file/d/1NmFmaJialfgiZwOV6myKQygLGjdcOXdb/view?usp=drive_link',
            ],
            [
                'judul' => 'Malam Keakraban & Upacara Api Unggun Perjumsa Pramuka',
                'old_tiktok_id' => '7544232105719450885',
                'gdrive_url' => 'https://drive.google.com/file/d/1_VL0lQlFMpS2hsea3WvBcgHHrg0lBJv1/view?usp=drive_link',
            ],
            [
                'judul' => 'Pojok Literasi & Optimalisasi Sarana Perpustakaan Sekolah',
                'old_tiktok_id' => '7559429345354288395',
                'gdrive_url' => 'https://drive.google.com/file/d/1go4aSQES4HYD2NJ3IP8xBMWRCiH-sQaK/view?usp=drive_link',
            ],
        ];

        // 1. Update Galeri Video
        foreach ($mapping as $item) {
            VideoGallery::where('judul', $item['judul'])
                ->orWhere('video_url', 'LIKE', '%' . $item['old_tiktok_id'] . '%')
                ->update([
                    'platform' => 'Google Drive',
                    'video_url' => $item['gdrive_url'],
                ]);
        }

        // 2. Update Bukti Fisik E-PKKS
        foreach ($mapping as $item) {
            PkksEvidence::where('external_url', 'LIKE', '%' . $item['old_tiktok_id'] . '%')
                ->orWhere('external_url', $item['gdrive_url'])
                ->update([
                    'external_url' => $item['gdrive_url'],
                    'file_type' => 'video',
                ]);
        }
    }

    public function down(): void
    {
        // Reversible if needed
    }
};
