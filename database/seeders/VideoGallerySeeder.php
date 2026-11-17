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
            // ========================================================
            [
                'judul' => 'Kegiatan MPLS Ramah Tahun Ajaran 2026/2027 (Bagian 1)',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7661914216118373653',
                'tanggal' => '2026-07-15',
                'is_featured' => true,
                'deskripsi' => 'Dokumentasi keceriaan dan kehangatan pelaksanaan Masa Pengenalan Lingkungan Sekolah (MPLS) Ramah Anak tahun ajaran 2026/2027 di SDN Cinanggerang I.',
            ],
            [
                'judul' => 'Kegiatan MPLS Ramah Tahun Ajaran 2026/2027 (Bagian 2)',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7663881708583882004',
                'tanggal' => '2026-07-16',
                'is_featured' => true,
                'deskripsi' => 'Rangkaian aktivitas pengenalan lingkungan belajar, sarana prasarana sekolah, dan pembiasaan budaya positif yang ramah dan menyenangkan.',
            ],
            [
                'judul' => 'Kegiatan MPLS Ramah Tahun Ajaran 2026/2027 (Bagian 3)',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7662964146060741908',
                'tanggal' => '2026-07-17',
                'is_featured' => true,
                'deskripsi' => 'Keseruan penutupan dan pengukuhan peserta didik baru dalam kegiatan MPLS Ramah Anak tahun ajaran 2026/2027 SDN Cinanggerang I.',
            ],
            [
                'judul' => 'Upacara Peringatan Hari Pramuka ke-65 Tahun 2026',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7674611344506752277',
                'tanggal' => '2026-08-14',
                'is_featured' => true,
                'deskripsi' => 'Khidmatnya pelaksanaan upacara peringatan Hari Pramuka ke-65 Gudep SDN Cinanggerang I dengan penanaman nilai Dasa Darma dan kepemimpinan.',
            ],
            [
                'judul' => 'Keseruan Lomba-Lomba Memperingati HUT RI Tahun 2026',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7676745078852963605',
                'tanggal' => '2026-08-17',
                'is_featured' => true,
                'deskripsi' => 'Kemeriahan berbagai perlombaan edukatif dan ketangkasan siswa dan guru dalam rangka menyemarakkan HUT Kemerdekaan Republik Indonesia tahun 2026.',
            ],
            [
                'judul' => 'Ajang Fashion Show Busana Adat Peringatan Hari Kartini',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7634746577797598485',
                'tanggal' => '2026-04-21',
                'is_featured' => true,
                'deskripsi' => 'Apresiasi seni dan unjuk rasa percaya diri siswa-siswi SDN Cinanggerang I dalam balutan busana adat nusantara memperingati Hari Kartini.',
            ],
            [
                'judul' => 'Kegiatan Pesantren Kilat (Sanlat) Ramadhan Tahun 2026',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7617424080391654677',
                'tanggal' => '2026-03-20',
                'is_featured' => true,
                'deskripsi' => 'Pembinaan akhlak mulia, tadarus Al-Qur\'an bersama, dan bimbingan ibadah praktis dalam program Pesantren Kilat Ramadhan 1447H / 2026M.',
            ],
            [
                'judul' => 'Screening & Pemeriksaan Kesehatan Berkala Bersama Puskesmas Haurngombong',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7615459280682159381',
                'tanggal' => '2026-03-12',
                'is_featured' => true,
                'deskripsi' => 'Pelayanan kesehatan preventif terpadu meliputi pemeriksaan gigi, mata, telinga, serta status gizi siswa bekerjasama dengan tim medis Puskesmas Haurngombong.',
            ],
            [
                'judul' => 'Perkemahan Jumat-Sabtu (Perjumsa) Gugus Depan SDN Cinanggerang I',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7544024476518812933',
                'tanggal' => '2026-08-22',
                'is_featured' => true,
                'deskripsi' => 'Latihan ketangkasan, kemandirian, dan kerjasama tim regu pramuka dalam kegiatan Perkemahan Jumat-Sabtu (Perjumsa) Gugus Depan SDN Cinanggerang I.',
            ],

            // Koleksi Video Arsip Sebelumnya
            [
                'judul' => 'Pemanfaatan Sampah Plastik Ramah Lingkungan (Gelar Karya P5)',
                'platform' => 'TikTok',
                'kategori' => 'Gelar Karya P5',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7541363334671633720',
                'tanggal' => '2024-08-20',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi kegiatan aksi nyata dan kreativitas peserta didik SDN Cinanggerang I dalam mengolah sampah plastik menjadi kreasi berguna dalam Proyek P5.',
            ],
            [
                'judul' => 'Praktik Pembelajaran Inovatif Self-Regulated Learning (SRL)',
                'platform' => 'TikTok',
                'kategori' => 'Pembelajaran / E-Learning',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7542449483452386565',
                'tanggal' => '2024-08-23',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi kegiatan pembelajaran mandiri (Self-Regulated Learning) untuk meningkatkan kemandirian dan nalar kritis siswa di kelas.',
            ],
            [
                'judul' => 'Transformasi Menuju Sekolah Juara: Video Perubahan SDN Cinanggerang I',
                'platform' => 'TikTok',
                'kategori' => 'Profil Sekolah',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7542480505107696901',
                'tanggal' => '2024-08-24',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi langkah nyata transformasi sarana belajar, budaya positif, dan semangat baru civitas akademika SD Negeri Cinanggerang I.',
            ],
            [
                'judul' => 'Aksi Nyata Pembiasaan: Kampanye Sekolah Bersih & Bebas Sampah',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7543173878864973061',
                'tanggal' => '2024-08-26',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi kegiatan pembiasaan hidup bersih dan sehat: aksi kampanye bebas sampah plastik demi lingkungan sekolah yang asri.',
            ],
            [
                'judul' => 'Pendidikan Kepramukaan: Perkemahan Jumat-Sabtu (Perjumsa)',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7543866076468104453',
                'tanggal' => '2024-08-28',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi kegiatan kepramukaan dan latihan kemandirian siswa Gugus Depan SDN Cinanggerang I dalam Perjumsa.',
            ],
            [
                'judul' => 'Malam Keakraban & Upacara Api Unggun Perjumsa Pramuka',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7544232105719450885',
                'tanggal' => '2024-08-29',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi kehangatan malam keakraban, unjuk seni kreasi siswa, dan khidmatnya upacara api unggun Perjumsa.',
            ],
            [
                'judul' => 'Pojok Literasi & Optimalisasi Sarana Perpustakaan Sekolah',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7559429345354288395',
                'tanggal' => '2024-10-09',
                'is_featured' => false,
                'deskripsi' => 'Dokumentasi kegiatan peningkatan mutu literasi dan optimalisasi ruang perpustakaan sekolah yang nyaman bagi anak-anak.',
            ],
        ];

        foreach ($videos as $video) {
            VideoGallery::updateOrCreate(
                ['video_url' => $video['video_url']],
                $video
            );
        }
    }
}
