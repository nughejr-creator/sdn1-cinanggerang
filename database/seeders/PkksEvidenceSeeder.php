<?php

namespace Database\Seeders;

use App\Models\PkksEvidence;
use App\Models\PkksIndikator;
use Illuminate\Database\Seeder;

class PkksEvidenceSeeder extends Seeder
{
    /**
     * Seed Dokumen Bukti Fisik & Eviden Digital E-PKKS
     * Berdasarkan 12 Indikator Permendikdasmen No. 23 Tahun 2026
     * Terintegrasi dengan Cloud Storage Google Drive SDN Cinanggerang I
     */
    public function run(): void
    {
        $driveFolderUrl = 'https://drive.google.com/drive/folders/14OBTBm-pTOgP-YJ7Wffzz6VCqxqwyxoG?usp=sharing';

        $evidences = [
            // INDIKATOR 1: Visi, Misi, & Tujuan Satuan Pendidikan
            [
                'nomor_indikator' => 1,
                'judul_dokumen' => 'Dokumen KOSP & SK Tim Pengembang Kurikulum TA 2025/2026',
                'deskripsi' => 'Kurikulum Operasional Satuan Pendidikan (KOSP) memuat rumusan visi, misi, profil lulusan, SK Tim Pengembang, dan berita acara sosialisasi kepada komite sekolah dan orang tua.',
                'link_eksternal' => $driveFolderUrl,
            ],
            // INDIKATOR 2: Perencanaan & Pelaksanaan Pembelajaran Berpusat pada Murid
            [
                'nomor_indikator' => 2,
                'judul_dokumen' => 'Modul Ajar Diferensiasi & Modul Projek Penguatan Profil Pelajar Pancasila (P5)',
                'deskripsi' => 'Perangkat perencanaan pembelajaran Kurikulum Merdeka (ATP, Modul Ajar Kelas I-VI) serta modul panduan tema Gelar Karya P5 (Gaya Hidup Berkelanjutan & Kearifan Lokal).',
                'link_eksternal' => $driveFolderUrl,
            ],
            // INDIKATOR 3: Iklim Belajar Aman, Inklusif & Anti Kekerasan
            [
                'nomor_indikator' => 3,
                'judul_dokumen' => 'SK Tim Pencegahan dan Penanganan Kekerasan (TPPK) & Tata Tertib Sekolah',
                'deskripsi' => 'Surat Keputusan TPPK resmi terdaftar Dapodik/Kemendikdasmen, SOP penanganan perundungan, dan dokumentasi deklarasi iklim belajar ramah anak.',
                'link_eksternal' => $driveFolderUrl,
            ],
            // INDIKATOR 4: Budaya Mutu Pembelajaran & Analisis Rapor Pendidikan
            [
                'nomor_indikator' => 4,
                'judul_dokumen' => 'Laporan Hasil Asesmen Nasional (ANBK) & Tindak Lanjut Rapor Pendidikan',
                'deskripsi' => 'Hasil evaluasi mutu pendidikan berbasis data (PBD), analisis indikator literasi numerasi rapor pendidikan, dan program intervensi peningkatan kompetensi belajar.',
                'link_eksternal' => $driveFolderUrl,
            ],
            // INDIKATOR 5: Pengembangan Keprofesian Berkelanjutan (PKB) Guru & Tendik
            [
                'nomor_indikator' => 5,
                'judul_dokumen' => 'Portofolio Sertifikat PMM & Laporan Rutin Komunitas Belajar (Kombel)',
                'deskripsi' => 'Bukti karya aksi nyata guru di Platform Merdeka Mengajar (PMM), sertifikat diklat/webinar kedinasan, serta laporan notulen kegiatan Komunitas Belajar internal sekolah.',
                'link_eksternal' => $driveFolderUrl,
            ],
            // INDIKATOR 6: Kepemimpinan Pembelajaran (Instructional Leadership)
            [
                'nomor_indikator' => 6,
                'judul_dokumen' => 'SK Pembagian Tugas Kependidikan & Dokumentasi Berbagi Praktik Baik (Peer Learning)',
                'deskripsi' => 'SK pembagian tugas mengajar guru & tugas tambahan, dokumentasi forum diseminasi metode pembelajaran inovatif, dan lembar refleksi pembelajaran berkala.',
                'link_eksternal' => $driveFolderUrl,
            ],
            // INDIKATOR 7: Kemitraan & Kolaborasi Pemangku Kepentingan Luar
            [
                'nomor_indikator' => 7,
                'judul_dokumen' => 'Naskah Perjanjian Kerja Sama (MoU) Lintas Sektor & Laporan Kemitraan',
                'deskripsi' => 'MoU kemitraan dengan Puskesmas Pamulihan (layanan imunisasi & UKS), Kepolisian sektor, serta kerja sama edukatif dengan Pemerintah Desa Cinanggerang.',
                'link_eksternal' => $driveFolderUrl,
            ],
            // INDIKATOR 8: Pengelolaan Sarana Prasarana & Lingkungan Sekolah Sehat
            [
                'nomor_indikator' => 8,
                'judul_dokumen' => 'Buku Inventaris Barang/Sarpras & Dokumentasi Revitalisasi Perpustakaan',
                'deskripsi' => 'Buku induk inventaris barang milik daerah (KIR), jadwal pemeliharaan fasilitas, serta dokumentasi penataan pojok baca dan ruang perpustakaan sekolah.',
                'link_eksternal' => $driveFolderUrl,
            ],
            // INDIKATOR 9: Supervisi Akademik Terprogram Kepala Sekolah
            [
                'nomor_indikator' => 9,
                'judul_dokumen' => 'Program, Instrumen Kunjungan Kelas & Laporan Rekap Supervisi Akademik',
                'deskripsi' => 'Jadwal supervisi kelas semester 1 & 2, lembar telaah modul ajar, instrumen observasi pembelajaran kelas, lembar coaching refleksi, dan rencana tindak lanjut pembinaan guru.',
                'link_eksternal' => $driveFolderUrl,
            ],
            // INDIKATOR 10: Tata Kelola Sumber Daya & Transparansi Anggaran BOS
            [
                'nomor_indikator' => 10,
                'judul_dokumen' => 'Dokumen Rencana Kegiatan dan Anggaran Sekolah (RKAS) & Laporan SPJ BOS',
                'deskripsi' => 'RKT/RKAS tahunan, lembar pengesahan dinas, pembukuan realisasi anggaran BOS reguler, dan bukti dokumentasi papan transparansi publik dana BOS.',
                'link_eksternal' => $driveFolderUrl,
            ],
            // INDIKATOR 11: Pelibatan Komite Sekolah & Kemitraan Orang Tua Murid
            [
                'nomor_indikator' => 11,
                'judul_dokumen' => 'SK Pengurus Komite Sekolah, Program Kerja & Notulen Musyawarah Paguyuban',
                'deskripsi' => 'Legalitas struktur kepengurusan komite sekolah, berita acara musyawarah penyusunan program tahunan bersama orang tua siswa, dan dokumentasi gotong royong paguyuban.',
                'link_eksternal' => $driveFolderUrl,
            ],
            // INDIKATOR 12: Inovasi Satuan Pendidikan, Kewirausahaan & Transformasi Digital
            [
                'nomor_indikator' => 12,
                'judul_dokumen' => 'Dokumentasi Inovasi Digital Sekolah & Produk Pemanfaatan Sampah P5',
                'deskripsi' => 'Inovasi transformasi digital: implementasi Portal Web Resmi, sistem Presensi Geofencing GPS guru, serta dokumentasi produk kewirausahaan siswa pemanfaatan sampah plastik.',
                'link_eksternal' => $driveFolderUrl,
            ],
        ];

        foreach ($evidences as $item) {
            $indikator = PkksIndikator::where('nomor', $item['nomor_indikator'])->first();

            if ($indikator) {
                PkksEvidence::updateOrCreate(
                    [
                        'pkks_indikator_id' => $indikator->id,
                        'judul_dokumen' => $item['judul_dokumen'],
                    ],
                    [
                        'tahun_ajaran' => '2025/2026',
                        'status_keterpenuhan' => 'Ada',
                        'deskripsi' => $item['deskripsi'],
                        'link_eksternal' => $item['link_eksternal'],
                    ]
                );
            }
        }
    }
}
