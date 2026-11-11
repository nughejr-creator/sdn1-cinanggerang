<?php

namespace Database\Seeders;

use App\Models\HeadmasterHistory;
use App\Models\HeadmasterProfile;
use App\Models\PkksEvidence;
use App\Models\PkksIndicator;
use App\Models\PkksPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PkksDatabaseSeeder extends Seeder
{
    /**
     * Seed Data Lengkap E-PKKS Permendikdasmen No. 23/2026
     */
    public function run(): void
    {
        $driveFolderUrl = 'https://drive.google.com/drive/folders/14OBTBm-pTOgP-YJ7Wffzz6VCqxqwyxoG?usp=sharing';

        // 1. Profil Kepala Sekolah Resmi
        $headmaster = HeadmasterProfile::updateOrCreate(
            ['nip' => '197206102014111001'],
            [
                'name' => 'Aep Yayat, S.Pd',
                'nuptk' => '7442750652200022',
                'rank_group' => 'Penata Tk. I / IV/b',
                'photo_path' => 'headmaster/aep-yayat.png',
                'cv_pdf_path' => $driveFolderUrl,
                'leadership_quote' => 'Memimpin dengan keteladanan, melayani dengan keikhlasan, dan mengabdi untuk kemajuan generasi penerus bangsa yang berakhlak mulia, cerdas, mandiri, dan berwawasan lingkungan.',
                'bio_summary' => 'Pendidik dan pemimpin pembelajaran berdedikasi tinggi di bawah naungan Dinas Pendidikan Pemerintah Kabupaten Sumedang. Berkomitmen menghadirkan ekosistem sekolah ramah anak, berakar pada budaya luhur Sumedang Larang, serta adaptif terhadap transformasi Kurikulum Merdeka dan teknologi digital.',
                'appointment_date' => '2021-07-15',
                'is_active' => true,
            ]
        );

        // Akun Login Admin / Kepala Sekolah untuk Panel Filament (/admin)
        if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
            \App\Models\User::firstOrCreate(
                ['email' => 'admin@sdncinanggerang1.sch.id'],
                [
                    'name' => 'Aep Yayat, S.Pd',
                    'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                    'role' => 'admin',
                    'is_active' => true,
                ]
            );
        }

        // 2. Riwayat Diklat, Prestasi & Organisasi
        $histories = [
            [
                'type' => 'education',
                'title' => 'S1 Pendidikan Guru Sekolah Dasar (PGSD)',
                'organizer' => 'Universitas Pendidikan Indonesia (UPI)',
                'year' => '2008',
                'sort_order' => 1,
            ],
            [
                'type' => 'training',
                'title' => 'Program Pengembangan Keprofesian Berkelanjutan (PKB) Kepala Sekolah',
                'organizer' => 'BBGP Provinsi Jawa Barat',
                'year' => '2023',
                'sort_order' => 2,
            ],
            [
                'type' => 'training',
                'title' => 'Bimbingan Teknis Implementasi Kurikulum Merdeka & Supervisi Pembelajaran',
                'organizer' => 'Dinas Pendidikan Kabupaten Sumedang',
                'year' => '2024',
                'sort_order' => 3,
            ],
            [
                'type' => 'training',
                'title' => 'Pelatihan Perencanaan Berbasis Data (PBD) & Optimalisasi Rapor Pendidikan',
                'organizer' => 'Kemendikdasmen RI / BBGP Jabar',
                'year' => '2025',
                'sort_order' => 4,
            ],
            [
                'type' => 'achievement',
                'title' => 'Kepala Sekolah Penggerak Mutu Literasi & Numerasi Terbaik',
                'organizer' => 'Kecamatan Pamulihan',
                'year' => '2024',
                'sort_order' => 5,
            ],
            [
                'type' => 'achievement',
                'title' => 'Apresiasi Inovasi Pengelolaan Sekolah Adiwiyata Berkelanjutan',
                'organizer' => 'Kabupaten Sumedang',
                'year' => '2025',
                'sort_order' => 6,
            ],
            [
                'type' => 'organization',
                'title' => 'Pengurus Kelompok Kerja Kepala Sekolah (KKKS) Kecamatan Pamulihan',
                'organizer' => 'KKKS Pamulihan',
                'year' => '2023 - Sekarang',
                'sort_order' => 7,
            ],
            [
                'type' => 'organization',
                'title' => 'Anggota Aktif Persatuan Guru Republik Indonesia (PGRI)',
                'organizer' => 'PGRI Cabang Pamulihan',
                'year' => '2014 - Sekarang',
                'sort_order' => 8,
            ],
        ];

        foreach ($histories as $h) {
            HeadmasterHistory::updateOrCreate(
                [
                    'headmaster_profile_id' => $headmaster->id,
                    'title' => $h['title'],
                ],
                array_merge($h, ['headmaster_profile_id' => $headmaster->id])
            );
        }

        // 3. Periode Penilaian PKKS Aktif
        $period = PkksPeriod::updateOrCreate(
            ['id' => 1],
            [
                'academic_year' => '2026',
                'theme' => 'Optimalisasi Kinerja dan Kepemimpinan Pembelajaran Berkelanjutan Berbasis Kurikulum Merdeka Menuju Generasi Emas',
                'assessment_date' => '2026-11-20',
                'assessor_name' => 'Tim Pengawas Pembina Dinas Pendidikan Kab. Sumedang',
                'assessor_pin' => '1972', // Token/PIN asesor
                'is_active' => true,
            ]
        );

        // 4. Data 12 Indikator Resmi Permendikdasmen No. 23/2026
        $indicators = [
            // PILAR 1: KEPRIBADIAN (Indikator 1 s/d 3)
            [
                'indicator_number' => 1,
                'competence' => 'Kepribadian',
                'title' => 'Integritas dan Perilaku Etis',
                'description' => 'Melaksanakan tugas kepemimpinan dengan menjunjung tinggi norma moral, hukum, dan etika profesi pendidik; menunjukkan keteladanan kedisiplinan dan transparansi dalam pengelolaan satuan pendidikan.',
                'best_practice_notes' => 'Penerapan pembiasaan 5S (Senyum, Salam, Sapa, Sopan, Santun), kehadiran presensi digital tepat waktu, dan keterbukaan informasi publik kepada komite sekolah.',
                'status' => 'Ada',
                'sort_order' => 1,
                'evidences' => [
                    [
                        'title' => 'Pakta Integritas Kepala Sekolah & Seluruh GTK Tahun 2026',
                        'description' => 'Komitmen tertulis pencegahan maladministrasi, gratifikasi, dan pakta kesetiaan profesi pendidik.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Program Kerja Pembiasaan Karakter & Nilai Keagamaan Siswa',
                        'description' => 'Jadwal salat dhuha rutin, tadarus Al-Quran, dan literasi keagamaan setiap pagi sebelum KBM.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Rekapitulasi Presensi Digital GPS & Log Kinerja Kepala Sekolah',
                        'description' => 'Rekap data kehadiran digital tepat waktu (≤ 07.30 WIB) dan jurnal harian kepemimpinan.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => false, // Dokumen dinas khusus asesor
                    ],
                ],
            ],
            [
                'indicator_number' => 2,
                'competence' => 'Kepribadian',
                'title' => 'Pengembangan Diri Berkelanjutan',
                'description' => 'Menunjukkan kesadaran belajar mandiri, refleksi berkala atas kepemimpinan pembelajaran, dan keterlibatan aktif dalam forum peningkatan keprofesian guru dan kepala sekolah.',
                'best_practice_notes' => 'Mengikuti komunitas belajar (Kombel) mingguan, webinar bersertifikat di PMM, dan diseminasi praktik baik kepada rekan sejawat.',
                'status' => 'Ada',
                'sort_order' => 2,
                'evidences' => [
                    [
                        'title' => 'Rencana Pengembangan Keprofesian Berkelanjutan (RPKB) Kepala Sekolah',
                        'description' => 'Matriks target pelatihan kompetensi manajerial, supervisi, dan digitalisasi tahun 2026.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Portofolio Sertifikat Diklat, Seminar & Webinar Nasional PMM',
                        'description' => 'Kumpulan sertifikat resmi BBGP, Kemendikdasmen, dan aksi nyata Platform Merdeka Mengajar.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Jurnal Refleksi Diri Berkala Kinerja Kepemimpinan',
                        'description' => 'Catatan reflektif evaluasi keberhasilan dan tantangan manajemen sekolah setiap triwulan.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Bukti Keikutsertaan Aktif dalam Forum KKKS & Kombel Gugus',
                        'description' => 'Daftar hadir, dokumentasi, dan notulen rapat kerja KKKS Kecamatan Pamulihan.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                ],
            ],
            [
                'indicator_number' => 3,
                'competence' => 'Kepribadian',
                'title' => 'Memimpin Budaya Mutu & Kesejahteraan Murid',
                'description' => 'Membangun iklim pembelajaran yang berfokus pada kesejahteraan lahir batin murid (well-being), mutu akademik holistik, dan ketercapaian Profil Pelajar Pancasila.',
                'best_practice_notes' => 'Penyediaan sarana pojok istirahat ramah anak, program gizi sarapan bersama, dan iklim belajar yang menyenangkan tanpa tekanan.',
                'status' => 'Ada',
                'sort_order' => 3,
                'evidences' => [
                    [
                        'title' => 'Dokumen Analisis Peta Mutu Sekolah Berdasarkan Rapor Pendidikan',
                        'description' => 'Identifikasi capaian literasi, numerasi, iklim keamanan sekolah, dan rencana intervensi perbaikan.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Rencana Kerja Tahunan (RKT) Berbasis Peningkatan Mutu Belajar',
                        'description' => 'Program kerja sekolah yang selaras dengan rekomendasi Perencanaan Berbasis Data (PBD).',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Dokumen RKAS Berbasis Data Rapor Pendidikan (PBD) Tahun 2026',
                        'description' => 'Alokasi anggaran operasional yang terarah pada pengadaan buku literasi dan media interaktif.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => false, // Dokumen dinas khusus asesor
                    ],
                ],
            ],

            // PILAR 2: SOSIAL (Indikator 4 s/d 6)
            [
                'indicator_number' => 4,
                'competence' => 'Sosial',
                'title' => 'Komunikasi Efektif & Kolaborasi Harmonis',
                'description' => 'Membangun komunikasi dua arah yang terbuka, apresiatif, dan konstruktif dengan dewan guru, tendik, orang tua, peserta didik, serta warga masyarakat lingkungan sekolah.',
                'best_practice_notes' => 'Pemanfaatan website resmi, forum WhatsApp paguyuban kelas, serta musyawarah dewan guru bulanan yang inklusif.',
                'status' => 'Ada',
                'sort_order' => 4,
                'evidences' => [
                    [
                        'title' => 'Notulen Rapat Koordinasi Rutin Dewan Guru & Komite Sekolah',
                        'description' => 'Daftar hadir, dokumentasi pembahasan kurikulum, dan kesepakatan tata tertib sekolah.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Portal Komunikasi Digital & Publikasi Resmi SDN Cinanggerang I',
                        'description' => 'Website sekolah modern yang memuat warta, pengumuman kedinasan, dan galeri interaktif.',
                        'file_type' => 'link',
                        'external_url' => 'https://sdncinanggerang1.sch.id',
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Dokumentasi Video Kebersamaan Warga Sekolah (Kegiatan Perjumsa)',
                        'description' => 'Kegiatan perkemahan jumat sabtu yang mempererat solidaritas guru, siswa, dan pramuka pembina.',
                        'file_type' => 'video',
                        'external_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7543866076468104453',
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Hasil Evaluasi Instrumen Umpan Balik Kepemimpinan 360 Derajat',
                        'description' => 'Kuesioner evaluasi dari rekan sejawat guru, perwakilan komite, dan peserta didik.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => false,
                    ],
                ],
            ],
            [
                'indicator_number' => 5,
                'competence' => 'Sosial',
                'title' => 'Kemitraan Strategis Eksternal',
                'description' => 'Mengembangkan jejaring kemitraan yang produktif dengan puskesmas, aparat desa, tokoh masyarakat, dunia usaha, dan organisasi profesi kependidikan.',
                'best_practice_notes' => 'Pemeriksaan kesehatan berkala bersama Puskesmas Pamulihan dan penyuluhan edukatif bersama Pemerintah Desa Cinanggerang.',
                'status' => 'Ada',
                'sort_order' => 5,
                'evidences' => [
                    [
                        'title' => 'Naskah Perjanjian Kerja Sama (MoU) dengan Puskesmas Pamulihan',
                        'description' => 'Layanan imunisasi BIAS, pemeriksaan berkala gigi anak, dan pembinaan Usaha Kesehatan Sekolah (UKS).',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'MoU Kolaborasi Edukasi Lingkungan dengan Pemerintah Desa Cinanggerang',
                        'description' => 'Sinergi pengelolaan sampah lingkungan dan program kebersihan desa berkelanjutan.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Laporan Kemitraan & Keaktifan di Organisasi Profesi (PGRI/KKKS)',
                        'description' => 'Dokumentasi advokasi kesejahteraan guru honorer dan kegiatan sosial kemasyarakatan.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                ],
            ],
            [
                'indicator_number' => 6,
                'competence' => 'Sosial',
                'title' => 'Lingkungan Pendidikan Inklusif, Aman, Ramah & Resik',
                'description' => 'Mewujudkan lingkungan satuan pendidikan yang aman, bebas dari kekerasan fisik, psikis, perundungan, intoleransi, serta sarana fasilitas yang bersih, asri, dan sehat.',
                'best_practice_notes' => 'Pembentukan Tim TPPK terdaftar resmi, gerakan kampanye bebas sampah plastik, dan SOP tanggap kebencanaan.',
                'status' => 'Ada',
                'sort_order' => 6,
                'evidences' => [
                    [
                        'title' => 'SK Tim Pencegahan dan Penanganan Kekerasan (TPPK) Terdaftar Dapodik',
                        'description' => 'Surat Keputusan Kepala Sekolah nomor 421/012/SD-01/2025 tentang pembentukan TPPK resmi.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Program Sekolah Ramah Anak (SRA) & SOP Penanganan Perundungan',
                        'description' => 'Alur pelaporan insiden, kotak saran rahasia ramah anak, dan panduan mediasi konseling.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Dokumentasi Video Edukasi Kampanye Bebas Sampah Plastik Siswa',
                        'description' => 'Video kampanye anak-anak SDN Cinanggerang I menjaga kebersihan lingkungan tanpa sampah plastik.',
                        'file_type' => 'video',
                        'external_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7543173878864973061',
                        'is_public' => true,
                    ],
                    [
                        'title' => 'SOP Mitigasi Bencana Gempa Bumi & Peta Titik Kumpul Evakuasi',
                        'description' => 'Panduan evakuasi darurat bagi guru dan murid saat terjadi bencana alam.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Galeri Foto Sarana Prasarana Inklusif, Bersih & Ramah Anak',
                        'description' => 'Foto sanitasi terpisah pria/wanita, toilet bersih berstandar UKS, dan halaman asri.',
                        'file_type' => 'image',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                ],
            ],

            // PILAR 3: PROFESIONAL (Indikator 7 s/d 12)
            [
                'indicator_number' => 7,
                'competence' => 'Profesional',
                'title' => 'Pengembangan Visi, Misi & Budaya Belajar',
                'description' => 'Menyusun, menyosialisasikan, dan merefleksikan Kurikulum Satuan Pendidikan (KSP) berorientasi Kurikulum Merdeka yang mendorong penguatan budaya literasi dan numerasi.',
                'best_practice_notes' => 'Pojok literasi di setiap sudut kelas, 15 menit membaca buku non-pelajaran, dan pemutakhiran KSP bersama pengawas dinas.',
                'status' => 'Ada',
                'sort_order' => 7,
                'evidences' => [
                    [
                        'title' => 'Dokumen Kurikulum Satuan Pendidikan (KSP) Lengkap Tahun 2026',
                        'description' => 'KSP yang telah disahkan Pengawas Pembina dan Dinas Pendidikan Kab. Sumedang.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Berita Acara & Notulen Rapat Review Berkala Visi Misi Sekolah',
                        'description' => 'Musyawarah penyelarasan visi misi dengan Profil Pelajar Pancasila dan kearifan lokal.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Program Strategis Gerakan Literasi Sekolah (GLS) & Numerasi',
                        'description' => 'Jadwal pembiasaan membaca, pemanfaatan buku bacaan bermutu, dan rekap jurnal membaca siswa.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Dokumentasi Video Aktivitas Pojok Baca & Perpustakaan Sekolah',
                        'description' => 'Suasana anak-anak membaca dengan gembira di ruang perpustakaan sekolah.',
                        'file_type' => 'video',
                        'external_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7559429345354288395',
                        'is_public' => true,
                    ],
                ],
            ],
            [
                'indicator_number' => 8,
                'competence' => 'Profesional',
                'title' => 'Kepemimpinan Sarana & Budaya Positif',
                'description' => 'Mengoptimalkan pemeliharaan sarana prasarana serta menegakkan kesepakatan kelas dan disiplin positif tanpa kekerasan atau sanksi fisik.',
                'best_practice_notes' => 'Pembuatan keyakinan kelas bersama murid dan pengadaan perbaikan fasilitas kelas berskala prioritas.',
                'status' => 'Ada',
                'sort_order' => 8,
                'evidences' => [
                    [
                        'title' => 'Buku Inventaris Sarana Prasarana (KIR) & Rencana Pemeliharaan',
                        'description' => 'Data kondisi ruang kelas, mebeler, sarana digital proyektor, dan rencana perbaikan berkala.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Pedoman Disiplin Positif & Panduan Keyakinan Kelas Ramah Anak',
                        'description' => 'Dokumen penerapan segitiga restitusi dalam mendisiplinkan murid secara edukatif.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Laporan Rekap Penanganan Kasus & Konseling Siswa Terbimbing',
                        'description' => 'Dokumentasi penanganan kendala belajar dan pembinaan sikap murid secara rahasia.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => false,
                    ],
                ],
            ],
            [
                'indicator_number' => 9,
                'competence' => 'Profesional',
                'title' => 'Kurikulum & Supervisi Akademik',
                'description' => 'Merencanakan, melaksanakan, dan mengevaluasi supervisi akademik berbasis teknik coaching untuk memfasilitasi peningkatan kualitas pembelajaran berdiferensiasi para guru.',
                'best_practice_notes' => 'Supervisi terprogram semester 1 & 2 dengan instrumen telaah modul ajar, observasi kelas, dan coaching reflektif.',
                'status' => 'Ada',
                'sort_order' => 9,
                'evidences' => [
                    [
                        'title' => 'Program, Instrumen & Jadwal Supervisi Akademik Semester Ganjil & Genap',
                        'description' => 'Matriks jadwal kunjungan kelas, kisi-kisi penilaian modul ajar, dan lembar observasi.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Laporan Rekapitulasi Hasil Supervisi & Rencana Tindak Lanjut (RTL)',
                        'description' => 'Analisis hasil pengamatan KBM di 6 rombel dan rencana pelatihan peningkatan kompetensi guru.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Lembar Coaching & Refleksi Mandiri Pasca Observasi Pembelajaran',
                        'description' => 'Catatan umpan balik bersama para guru untuk mengembangkan diferensiasi konten & proses.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => false,
                    ],
                    [
                        'title' => 'Dokumentasi Video Pembelajaran Self Regulated Learning (SRL)',
                        'description' => 'Praktek pembelajaran berdiferensiasi dan kemandirian belajar siswa di kelas.',
                        'file_type' => 'video',
                        'external_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7542449483452386565',
                        'is_public' => true,
                    ],
                ],
            ],
            [
                'indicator_number' => 10,
                'competence' => 'Profesional',
                'title' => 'Pengelolaan Program, BOS & Sumber Daya',
                'description' => 'Mengelola anggaran Bantuan Operasional Satuan Pendidikan (BOSP) secara transparan, akuntabel, efisien, dan partisipatif sesuai peraturan perundang-undangan.',
                'best_practice_notes' => 'Pemasangan papan informasi realisasi dana BOS di lobi sekolah dan input aplikasi ARKAS tepat waktu.',
                'status' => 'Ada',
                'sort_order' => 10,
                'evidences' => [
                    [
                        'title' => 'Dokumen Rencana Kegiatan dan Anggaran Sekolah (RKAS) Tahun 2026',
                        'description' => 'Dokumen RKAS resmi yang telah diverifikasi Dinas Pendidikan Kab. Sumedang.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => false, // Dokumen dinas khusus asesor
                    ],
                    [
                        'title' => 'Laporan Realisasi Penggunaan Dana BOS Reguler Tahap 1 & 2',
                        'description' => 'Laporan pertanggungjawaban (SPJ) BOS lengkap dengan bukti kuitansi dan pajak.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => false, // Dokumen dinas khusus asesor
                    ],
                    [
                        'title' => 'Foto Dokumentasi Papan Transparansi Publik Informasi Dana BOS',
                        'description' => 'Papan transparansi di lingkungan sekolah yang dapat diakses oleh wali murid dan masyarakat.',
                        'file_type' => 'image',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Salinan Rapor Pendidikan Resmi SDN Cinanggerang I',
                        'description' => 'Unduhan profil rapor pendidikan dari Kemendikdasmen sebagai rujukan belanja sekolah.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                ],
            ],
            [
                'indicator_number' => 11,
                'competence' => 'Profesional',
                'title' => 'Kemitraan Orang Tua & Paguyuban',
                'description' => 'Membangun keterlibatan aktif komite sekolah, paguyuban kelas, dan orang tua dalam perencanaan, pendampingan, serta evaluasi program sekolah.',
                'best_practice_notes' => 'Forum musyawarah rutin, pelibatan orang tua pada pentas seni P5, dan kerja bakti bersama paguyuban.',
                'status' => 'Ada',
                'sort_order' => 11,
                'evidences' => [
                    [
                        'title' => 'SK Pengurus Komite Sekolah Masa Bakti 2024-2027 & AD/ART',
                        'description' => 'Legalitas struktur kepengurusan komite sekolah perwakilan tokoh pendidikan dan wali murid.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Notulen & Daftar Hadir Rapat Musyawarah Tahunan Paguyuban Kelas',
                        'description' => 'Berita acara kesepakatan dukungan wali murid terhadap program ekstrakurikuler sekolah.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Dokumentasi Partisipasi Orang Tua dalam Kegiatan Gelar Karya & Parenting',
                        'description' => 'Foto dan video pendampingan anak saat bazar P5 dan seminar parenting.',
                        'file_type' => 'image',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                ],
            ],
            [
                'indicator_number' => 12,
                'competence' => 'Profesional',
                'title' => 'Inovasi, Kewirausahaan & Digitalisasi',
                'description' => 'Mengembangkan inovasi digital layanan sekolah, projek kewirausahaan murid (P5), serta pemanfaatan teknologi informasi untuk efisiensi tata kelola sekolah.',
                'best_practice_notes' => 'Pengembangan portal website resmi, presensi digital GPS guru, galeri video kreasi daur ulang sampah plastik siswa.',
                'status' => 'Ada',
                'sort_order' => 12,
                'evidences' => [
                    [
                        'title' => 'Laporan Inovasi Digital Portal Informasi & Layanan SDN Cinanggerang I',
                        'description' => 'Sistem website mandiri dengan SEO-friendly, e-learning modul ajar, dan showcase E-PKKS.',
                        'file_type' => 'link',
                        'external_url' => 'https://sdncinanggerang1.sch.id',
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Video Proyek Kreatif Siswa: Pemanfaatan Sampah Plastik Menjadi Produk Bernilai',
                        'description' => 'Inovasi projek P5 mendaur ulang sampah plastik menjadi kerajinan ecobrick dan pot bunga.',
                        'file_type' => 'video',
                        'external_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7541363334671633720',
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Video Perubahan & Inovasi Transformasi Lingkungan Sekolah',
                        'description' => 'Dokumentasi perubahan wajah fisik dan budaya belajar di SDN Cinanggerang I.',
                        'file_type' => 'video',
                        'external_url' => 'https://www.tiktok.com/@sdn.cinanggerang1/video/7542480505107696901',
                        'is_public' => true,
                    ],
                    [
                        'title' => 'Panduan Operasional Presensi GPS Digital & Portal E-Learning',
                        'description' => 'Manual SOP penggunaan modul presensi berbasis titik koordinat radius sekolah dan bank materi ajar.',
                        'file_type' => 'pdf',
                        'external_url' => $driveFolderUrl,
                        'is_public' => true,
                    ],
                ],
            ],
        ];

        foreach ($indicators as $indData) {
            $evidences = $indData['evidences'];
            unset($indData['evidences']);

            $indicator = PkksIndicator::updateOrCreate(
                [
                    'pkks_period_id' => $period->id,
                    'indicator_number' => $indData['indicator_number'],
                ],
                array_merge($indData, ['pkks_period_id' => $period->id])
            );

            foreach ($evidences as $idx => $ev) {
                PkksEvidence::updateOrCreate(
                    [
                        'pkks_indicator_id' => $indicator->id,
                        'title' => $ev['title'],
                    ],
                    array_merge($ev, [
                        'pkks_indicator_id' => $indicator->id,
                        'sort_order' => $idx + 1,
                    ])
                );
            }
        }
    }
}
