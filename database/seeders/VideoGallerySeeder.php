<?php

namespace Database\Seeders;

use App\Models\VideoGallery;
use Illuminate\Database\Seeder;

class VideoGallerySeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            [
                'judul' => 'Pemanfaatan Sampah Plastik Ramah Lingkungan (Gelar Karya P5)',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan Sekolah',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7541363334671633720',
                'tanggal' => '2024-08-20',
                'is_featured' => true,
                'deskripsi' => 'Dokumentasi kegiatan aksi nyata dan kreativitas peserta didik SDN Cinanggerang I dalam mengolah sampah plastik menjadi kreasi berguna dalam Proyek P5.',
            ],
            [
                'judul' => 'Praktik Pembelajaran Inovatif Self-Regulated Learning (SRL)',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan Sekolah',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7542449483452386565',
                'tanggal' => '2024-08-23',
                'is_featured' => true,
                'deskripsi' => 'Dokumentasi kegiatan pembelajaran mandiri (Self-Regulated Learning) untuk meningkatkan kemandirian dan nalar kritis siswa di kelas.',
            ],
            [
                'judul' => 'Transformasi Menuju Sekolah Juara: Video Perubahan SDN Cinanggerang I',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan Sekolah',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7542480505107696901',
                'tanggal' => '2024-08-24',
                'is_featured' => true,
                'deskripsi' => 'Dokumentasi langkah nyata transformasi sarana belajar, budaya positif, dan semangat baru civitas akademika SD Negeri Cinanggerang I.',
            ],
            [
                'judul' => 'Aksi Nyata Pembiasaan: Kampanye Sekolah Bersih & Bebas Sampah',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan Sekolah',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7543173878864973061',
                'tanggal' => '2024-08-26',
                'is_featured' => true,
                'deskripsi' => 'Dokumentasi kegiatan pembiasaan hidup bersih dan sehat: aksi kampanye bebas sampah plastik demi lingkungan sekolah yang asri.',
            ],
            [
                'judul' => 'Pendidikan Kepramukaan: Perkemahan Jumat-Sabtu (Perjumsa)',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan Sekolah',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7543866076468104453',
                'tanggal' => '2024-08-28',
                'is_featured' => true,
                'deskripsi' => 'Dokumentasi kegiatan kepramukaan dan latihan kemandirian siswa Gugus Depan SDN Cinanggerang I dalam Perjumsa.',
            ],
            [
                'judul' => 'Malam Keakraban & Upacara Api Unggun Perjumsa Pramuka',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan Sekolah',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7544232105719450885',
                'tanggal' => '2024-08-29',
                'is_featured' => true,
                'deskripsi' => 'Dokumentasi kehangatan malam keakraban, unjuk seni kreasi siswa, dan khidmatnya upacara api unggun Perjumsa.',
            ],
            [
                'judul' => 'Pojok Literasi & Optimalisasi Sarana Perpustakaan Sekolah',
                'platform' => 'TikTok',
                'kategori' => 'Dokumentasi Kegiatan Sekolah',
                'video_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7559429345354288395',
                'tanggal' => '2024-10-09',
                'is_featured' => true,
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
