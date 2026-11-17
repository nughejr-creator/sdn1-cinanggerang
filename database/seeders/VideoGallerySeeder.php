<?php

namespace Database\Seeders;

use App\Models\VideoGallery;
use Illuminate\Database\Seeder;

class VideoGallerySeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            // ========================================================
            // KOLEKSI VIDEO RESMI KEGIATAN SDN CINANGGERANG I (2026)
            // Sumber: Google Drive (Stabil, Bebas Iklan, Full HD)
            // ========================================================
            [
                'judul' => 'Kegiatan MPLS Ramah Tahun Ajaran 2026/2027 (Bagian 1)',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1AP_FtrBc139kggxG6tJK04tD5eRUaqwi/view?usp=drive_link',
                'tanggal' => '2026-07-15',
                'is_featured' => true,
                'deskripsi' => 'Dokumentasi keceriaan dan kehangatan pelaksanaan Masa Pengenalan Lingkungan Sekolah (MPLS) Ramah Anak tahun ajaran 2026/2027 di SDN Cinanggerang I.',
            ],
            [
                'judul' => 'Kegiatan MPLS Ramah Tahun Ajaran 2026/2027 (Bagian 2)',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1kYKO-nYsM20TlVxhLtIeMKOFYoVzQact/view?usp=drive_link',
                'tanggal' => '2026-07-16',
                'is_featured' => true,
                'deskripsi' => 'Rangkaian aktivitas pengenalan lingkungan belajar, sarana prasarana sekolah, dan pembiasaan budaya positif yang ramah dan menyenangkan.',
            ],
            [
                'judul' => 'Kegiatan MPLS Ramah Tahun Ajaran 2026/2027 (Bagian 3)',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1DSyLkyrMF8eKPq7mM65DVXxtzJR6VHNn/view?usp=drive_link',
                'tanggal' => '2026-07-17',
                'is_featured' => true,
                'deskripsi' => 'Keseruan penutupan dan pengukuhan peserta didik baru dalam kegiatan MPLS Ramah Anak tahun ajaran 2026/2027 SDN Cinanggerang I.',
            ],
            [
                'judul' => 'Upacara Peringatan Hari Pramuka ke-65 Tahun 2026',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1pohWdm2eukikDpBcZD-0KYKjrBtwXD8k/view?usp=drive_link',
                'tanggal' => '2026-08-14',
                'is_featured' => true,
                'deskripsi' => 'Khidmatnya pelaksanaan upacara peringatan Hari Pramuka ke-65 Gudep SDN Cinanggerang I dengan penanaman nilai Dasa Darma dan kepemimpinan.',
            ],
            [
                'judul' => 'Keseruan Lomba-Lomba Memperingati HUT RI Tahun 2026',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1Ep77-qouGOF9wbkEUx9Q8vcsrh1a4-_o/view?usp=drive_link',
                'tanggal' => '2026-08-17',
                'is_featured' => true,
                'deskripsi' => 'Kemeriahan berbagai perlombaan edukatif dan ketangkasan siswa dan guru dalam rangka menyemarakkan HUT Kemerdekaan Republik Indonesia tahun 2026.',
            ],
            [
                'judul' => 'Ajang Fashion Show Busana Adat Peringatan Hari Kartini',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1bBcMStWr5pbtn96Cqzy7zGHF8EsWmBQb/view?usp=drive_link',
                'tanggal' => '2026-04-21',
                'is_featured' => true,
                'deskripsi' => 'Apresiasi seni dan unjuk rasa percaya diri siswa-siswi SDN Cinanggerang I dalam balutan busana adat nusantara memperingati Hari Kartini.',
            ],
            [
                'judul' => 'Kegiatan Pesantren Kilat (Sanlat) Ramadhan Tahun 2026',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1BHiIx9hiAZUoAz_0lb7jVHKjmh0WWB6J/view?usp=drive_link',
                'tanggal' => '2026-03-20',
                'is_featured' => true,
                'deskripsi' => 'Pembinaan akhlak mulia, tadarus Al-Qur\'an bersama, dan bimbingan ibadah praktis dalam program Pesantren Kilat Ramadhan 1447H / 2026M.',
            ],
            [
                'judul' => 'Screening & Pemeriksaan Kesehatan Berkala Bersama Puskesmas Haurngombong',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1KjtXaHzFoDZvRrHQegdJD1fNggDVJuD_/view?usp=drive_link',
                'tanggal' => '2026-03-12',
                'is_featured' => true,
                'deskripsi' => 'Pelayanan kesehatan preventif terpadu meliputi pemeriksaan gigi, mata, telinga, serta status gizi siswa bekerjasama dengan tim medis Puskesmas Haurngombong.',
            ],
            [
                'judul' => 'Perkemahan Jumat-Sabtu (Perjumsa) Gugus Depan SDN Cinanggerang I',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1vsCb4Kf5cg7gQ1RcavT0nr9nyMXOp7yo/view?usp=drive_link',
                'tanggal' => '2026-08-22',
                'is_featured' => true,
                'deskripsi' => 'Latihan ketangkasan, kemandirian, dan kerjasama tim regu pramuka dalam kegiatan Perkemahan Jumat-Sabtu (Perjumsa) Gugus Depan SDN Cinanggerang I.',
            ],

            // Koleksi Video Arsip Sebelumnya
            [
                'judul' => 'Pemanfaatan Sampah Plastik Ramah Lingkungan (Gelar Karya P5)',
                'platform' => 'Google Drive',
                'kategori' => 'Gelar Karya P5',
                'video_url' => 'https://drive.google.com/file/d/1uqy6MzPdlUnmNUivJm0VBIPE33JLdDyn/view?usp=drive_link',
                'tanggal' => '2024-08-20',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi kegiatan aksi nyata dan kreativitas peserta didik SDN Cinanggerang I dalam mengolah sampah plastik menjadi kreasi berguna dalam Proyek P5.',
            ],
            [
                'judul' => 'Praktik Pembelajaran Inovatif Self-Regulated Learning (SRL)',
                'platform' => 'Google Drive',
                'kategori' => 'Pembelajaran / E-Learning',
                'video_url' => 'https://drive.google.com/file/d/1dzftc8eVlyb4JN1_ot5hVKqCAVFUcNfw/view?usp=drive_link',
                'tanggal' => '2024-08-23',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi kegiatan pembelajaran mandiri (Self-Regulated Learning) untuk meningkatkan kemandirian dan nalar kritis siswa di kelas.',
            ],
            [
                'judul' => 'Transformasi Menuju Sekolah Juara: Video Perubahan SDN Cinanggerang I',
                'platform' => 'Google Drive',
                'kategori' => 'Profil Sekolah',
                'video_url' => 'https://drive.google.com/file/d/1YOdaiwG6GqM_H_ygoJ4k3HbAT1Zb--Ab/view?usp=drive_link',
                'tanggal' => '2024-08-24',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi langkah nyata transformasi sarana belajar, budaya positif, dan semangat baru civitas akademika SD Negeri Cinanggerang I.',
            ],
            [
                'judul' => 'Aksi Nyata Pembiasaan: Kampanye Sekolah Bersih & Bebas Sampah',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1d24UfCNR9V9CqYw-XQiIBID8H8K_bQW1/view?usp=drive_link',
                'tanggal' => '2024-08-26',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi kegiatan pembiasaan hidup bersih dan sehat: aksi kampanye bebas sampah plastik demi lingkungan sekolah yang asri.',
            ],
            [
                'judul' => 'Pendidikan Kepramukaan: Perkemahan Jumat-Sabtu (Perjumsa)',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1NmFmaJialfgiZwOV6myKQygLGjdcOXdb/view?usp=drive_link',
                'tanggal' => '2024-08-28',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi kegiatan kepramukaan dan latihan kemandirian siswa Gugus Depan SDN Cinanggerang I dalam Perjumsa.',
            ],
            [
                'judul' => 'Malam Keakraban & Upacara Api Unggun Perjumsa Pramuka',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1_VL0lQlFMpS2hsea3WvBcgHHrg0lBJv1/view?usp=drive_link',
                'tanggal' => '2024-08-29',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi kehangatan malam keakraban, unjuk seni kreasi siswa, dan khidmatnya upacara api unggun Perjumsa.',
            ],
            [
                'judul' => 'Pojok Literasi & Optimalisasi Sarana Perpustakaan Sekolah',
                'platform' => 'Google Drive',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://drive.google.com/file/d/1go4aSQES4HYD2NJ3IP8xBMWRCiH-sQaK/view?usp=drive_link',
                'tanggal' => '2024-10-09',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi kegiatan peningkatan mutu literasi dan optimalisasi ruang perpustakaan sekolah yang nyaman bagi anak-anak.',
            ],
        ];

        foreach ($videos as $video) {
            VideoGallery::updateOrCreate(
                ['judul' => $video['judul']],
                $video
            );
        }
    }
}
