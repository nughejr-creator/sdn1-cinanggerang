<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolProfile;
use App\Models\Teacher;
use App\Models\StudentClass;
use App\Models\PkksKomponen;
use App\Models\PkksIndikator;
use App\Models\Course;

class SchoolDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Profil Sekolah SDN Cinanggerang I
        SchoolProfile::updateOrCreate(
            ['npsn' => '20208801'],
            [
                'name' => 'SD Negeri Cinanggerang I',
                'jenjang' => 'SD',
                'status_sekolah' => 'Negeri',
                'akreditasi' => 'B',
                'alamat' => 'Jln. Desa Cinanggerang, RT 03 / RW 04',
                'kelurahan' => 'Cinanggerang',
                'kecamatan' => 'Kec. Pamulihan',
                'kabupaten' => 'Kab. Sumedang',
                'provinsi' => 'Prov. Jawa Barat',
                'kode_pos' => '45362',
                'telepon' => '081321021585',
                'email' => 'sdncinanggerang1@gmail.com',
                'website' => 'https://sdncinanggerang1.sch.id',
                'kepala_sekolah' => 'Aep Yayat, S.Pd',
                'nip_kepala_sekolah' => '197206102014111001',
                'visi' => 'Terwujudnya Peserta Didik yang Berakhlak Mulia, Cerdas, Terampil, Mandiri, dan Berwawasan Lingkungan.',
                'misi' => "1. Menanamkan keimanan dan ketakwaan melalui pengamalan ajaran agama.\n2. Mengembangkan pembelajaran aktif, kreatif, inovatif, dan menyenangkan (Kurikulum Merdeka).\n3. Meningkatkan profesionalisme pendidik dan tenaga kependidikan.\n4. Membangun kemitraan yang harmonis dengan orang tua dan masyarakat.",
                'sambutan_kepala_sekolah' => 'Selamat datang di Website Resmi SD Negeri Cinanggerang I. Media ini kami dedikasikan sebagai sarana transparansi informasi, publikasi mutu pendidikan, layanan PPDB digital, dan pusat portofolio digital sekolah.',
                'latitude' => -6.89437395,
                'longitude' => 107.87197894,
            ]
        );

        // 2. Data Guru & Tenaga Kependidikan Resmi (11 Orang)
        $teachersData = [
            [
                'nama' => 'Aep Yayat',
                'gelar' => 'S.Pd',
                'nuptk' => '8942750652200042',
                'nip' => '197206102014111001',
                'jenis_kelamin' => 'L',
                'jabatan' => 'Kepala Sekolah',
                'status_kepegawaian' => 'PNS',
                'pendidikan_terakhir' => 'S1',
                'jurusan' => 'Guru Kelas SD/MI',
                'mapel_diajarkan' => 'Manajerial & Supervisi',
                'no_hp' => '085224400031',
                'email' => 'aepyayat@gmail.com',
                'urutan' => 1,
            ],
            [
                'nama' => 'Agi Hamdani',
                'gelar' => 'M.Pd',
                'nuptk' => '1450766665200002',
                'nip' => '198801182019031003',
                'jenis_kelamin' => 'L',
                'jabatan' => 'Guru Kelas VI',
                'status_kepegawaian' => 'PNS',
                'pendidikan_terakhir' => 'S2',
                'jurusan' => 'Pendidikan Dasar',
                'mapel_diajarkan' => 'Guru Kelas SD/MI, Bahasa Inggris, Mulok Sunda',
                'no_hp' => '082118449525',
                'email' => 'nughejr@gmail.com',
                'urutan' => 2,
            ],
            [
                'nama' => 'Tita Rosita',
                'gelar' => 'S.Pd',
                'nuptk' => '4062760661300073',
                'nip' => '198207302023212003',
                'jenis_kelamin' => 'P',
                'jabatan' => 'Guru Kelas I (Bendahara BOS)',
                'status_kepegawaian' => 'PPPK',
                'pendidikan_terakhir' => 'S1',
                'jurusan' => 'FKIP Guru Kelas SD/MI',
                'mapel_diajarkan' => 'Guru Kelas I',
                'no_hp' => '085224472875',
                'email' => 'titarosita.cibogo@gmail.com',
                'urutan' => 3,
            ],
            [
                'nama' => 'Elis Kurniawati',
                'gelar' => 'S.Pd',
                'nuptk' => '5549765666230193',
                'nip' => '198712172023212007',
                'jenis_kelamin' => 'P',
                'jabatan' => 'Guru Kelas IIA',
                'status_kepegawaian' => 'PPPK',
                'pendidikan_terakhir' => 'S1',
                'jurusan' => 'Matematika',
                'mapel_diajarkan' => 'Guru Kelas IIA',
                'no_hp' => '082115578267',
                'email' => 'eliskurniawati.87.ek@gmail.com',
                'urutan' => 4,
            ],
            [
                'nama' => 'Nuretha Ayu Mudzrikah',
                'gelar' => 'S.Pd',
                'nuptk' => '3935780681230002',
                'nip' => null,
                'jenis_kelamin' => 'P',
                'jabatan' => 'Guru Kelas IIB',
                'status_kepegawaian' => 'Honor Daerah TK.II',
                'pendidikan_terakhir' => 'S1',
                'jurusan' => 'FKIP Guru Kelas SD/MI',
                'mapel_diajarkan' => 'Guru Kelas IIB',
                'no_hp' => '082118465028',
                'email' => 'nurethaayu03@gmail.com',
                'urutan' => 5,
            ],
            [
                'nama' => 'Asep Sudrajat',
                'gelar' => 'S.Pd',
                'nuptk' => '3553763665200012',
                'nip' => '198502212022211001',
                'jenis_kelamin' => 'L',
                'jabatan' => 'Guru Kelas IIIA',
                'status_kepegawaian' => 'PPPK',
                'pendidikan_terakhir' => 'S1',
                'jurusan' => 'Guru Kelas SD/MI',
                'mapel_diajarkan' => 'Guru Kelas IIIA',
                'no_hp' => '081220866321',
                'email' => 'asepakung1985@gmail.com',
                'urutan' => 6,
            ],
            [
                'nama' => 'Mohammad Ridwan',
                'gelar' => 'M.Pd',
                'nuptk' => '3959759660200012',
                'nip' => '198106272008011003',
                'jenis_kelamin' => 'L',
                'jabatan' => 'Guru Kelas IIIB',
                'status_kepegawaian' => 'PNS',
                'pendidikan_terakhir' => 'S2',
                'jurusan' => 'Pendidikan Dasar',
                'mapel_diajarkan' => 'Guru Kelas IIIB',
                'no_hp' => '082117769900',
                'email' => 'ridwandubezz1981@gmail.com',
                'urutan' => 7,
            ],
            [
                'nama' => 'Tatik Latipah',
                'gelar' => 'S.Pd.I',
                'nuptk' => '0544768670130053',
                'nip' => '199012122025212011',
                'jenis_kelamin' => 'P',
                'jabatan' => 'Guru PAI & Wali Kelas IV',
                'status_kepegawaian' => 'PPPK',
                'pendidikan_terakhir' => 'S1',
                'jurusan' => 'Pendidikan Agama Islam',
                'mapel_diajarkan' => 'Pendidikan Agama Islam & Budi Pekerti',
                'no_hp' => '082218393242',
                'email' => 'tatiklatipah1990@gmail.com',
                'urutan' => 8,
            ],
            [
                'nama' => 'Lia Afriliyanti',
                'gelar' => 'S.Pd',
                'nuptk' => '6748779680230042',
                'nip' => null,
                'jenis_kelamin' => 'P',
                'jabatan' => 'Guru Kelas V',
                'status_kepegawaian' => 'Honor Daerah TK.II',
                'pendidikan_terakhir' => 'S1',
                'jurusan' => 'Guru Kelas SD/MI',
                'mapel_diajarkan' => 'Guru Kelas V',
                'no_hp' => '085323696270',
                'email' => 'liaafriliyanti11234@gmail.com',
                'urutan' => 9,
            ],
            [
                'nama' => 'Ubay Sohibi',
                'gelar' => 'S.Pd',
                'nuptk' => '6141768669130113',
                'nip' => '199008092025211111',
                'jenis_kelamin' => 'L',
                'jabatan' => 'Guru Penjasorkes (PJOK)',
                'status_kepegawaian' => 'PPPK Paruh Waktu',
                'pendidikan_terakhir' => 'S1',
                'jurusan' => 'Pendidikan Jasmani dan Kesehatan',
                'mapel_diajarkan' => 'PJOK',
                'no_hp' => '081312852960',
                'email' => 'ubay.elcapitano.96@gmail.com',
                'urutan' => 10,
            ],
            [
                'nama' => 'Agus Juandi',
                'gelar' => null,
                'nuptk' => '3742755657200032',
                'nip' => '197704102014111001',
                'jenis_kelamin' => 'L',
                'jabatan' => 'Penjaga / Tenaga Kebersihan',
                'status_kepegawaian' => 'PNS',
                'pendidikan_terakhir' => 'SMA',
                'jurusan' => 'Umum',
                'mapel_diajarkan' => '-',
                'no_hp' => '082315232291',
                'email' => 'agusjuandi1977@gmail.com',
                'urutan' => 11,
            ],
        ];

        foreach ($teachersData as $tData) {
            Teacher::updateOrCreate(['nama' => $tData['nama']], $tData);
        }

        // 3. Rombongan Belajar (8 Rombel)
        $classes = [
            ['nama' => 'Kelas I', 'tingkat' => 1, 'wali_kelas' => 'Tita Rosita, S.Pd', 'ruangan' => 'Ruang Kelas I'],
            ['nama' => 'Kelas IIA', 'tingkat' => 2, 'wali_kelas' => 'Elis Kurniawati, S.Pd', 'ruangan' => 'Ruang Kelas II'],
            ['nama' => 'Kelas IIB', 'tingkat' => 2, 'wali_kelas' => 'Nuretha Ayu Mudzrikah, S.Pd', 'ruangan' => 'Ruang Kelas II'],
            ['nama' => 'Kelas IIIA', 'tingkat' => 3, 'wali_kelas' => 'Asep Sudrajat, S.Pd', 'ruangan' => 'Ruang Kelas III'],
            ['nama' => 'Kelas IIIB', 'tingkat' => 3, 'wali_kelas' => 'Mohammad Ridwan, M.Pd', 'ruangan' => 'Ruang Kelas III'],
            ['nama' => 'Kelas IV', 'tingkat' => 4, 'wali_kelas' => 'Tatik Latipah, S.Pd.I', 'ruangan' => 'Ruang Kelas IV'],
            ['nama' => 'Kelas V', 'tingkat' => 5, 'wali_kelas' => 'Lia Afriliyanti, S.Pd', 'ruangan' => 'Ruang Kelas V'],
            ['nama' => 'Kelas VI', 'tingkat' => 6, 'wali_kelas' => 'Agi Hamdani, M.Pd', 'ruangan' => 'Ruang Kelas VI'],
        ];

        foreach ($classes as $c) {
            $teacher = Teacher::where('nama', 'LIKE', explode(',', $c['wali_kelas'])[0] . '%')->first();
            StudentClass::updateOrCreate(
                ['nama' => $c['nama']],
                array_merge($c, ['teacher_id' => $teacher?->id])
            );
        }

        // 4. E-PKKS: 3 Kompetensi & 12 Indikator Resmi Permendikdasmen No. 23 Tahun 2026
        $komponens = [
            [
                'nama' => 'Kepribadian',
                'deskripsi' => 'Standar kompetensi integritas spiritual, moral, emosional, pola pikir berkembang, dan budaya mutu.',
                'urutan' => 1,
                'indikators' => [
                    [
                        'nomor' => 1,
                        'indikator' => 'Menunjukkan integritas dan perilaku etis yang mencerminkan kematangan spiritual, moral, dan emosional berdasarkan Ketuhanan Yang Maha Esa dan nilai-nilai keberagaman',
                        'bukti_fisik' => "1. Pakta integritas/surat pernyataan komitmen kode etik\n2. Dokumen program pembiasaan keagamaan dan karakter di sekolah\n3. Rekap kehadiran kepala sekolah",
                    ],
                    [
                        'nomor' => 2,
                        'indikator' => 'Mengembangkan diri secara terencana, menunjukkan pola pikir bertumbuh, reflektif, dan berkelanjutan',
                        'bukti_fisik' => "1. Rencana pengembangan diri/kompetensi individu\n2. Sertifikat/bukti keikutsertaan pelatihan, diklat, lokakarya, atau seminar\n3. Jurnal/catatan refleksi diri berkala\n4. Bukti keikutsertaan komunitas belajar (KKKS/MKKS, komunitas praktisi)",
                    ],
                    [
                        'nomor' => 3,
                        'indikator' => 'Memimpin pengembangan budaya mutu Satuan Pendidikan yang berorientasi pada kebutuhan, potensi, dan kesejahteraan Murid',
                        'bukti_fisik' => "1. Dokumen peta mutu/ RKT dan RKAS PBD",
                    ],
                ]
            ],
            [
                'nama' => 'Sosial',
                'deskripsi' => 'Standar komunikasi efektif, kolaborasi warga sekolah, kemitraan strategis, dan penciptaan lingkungan aman & ramah anak.',
                'urutan' => 2,
                'indikators' => [
                    [
                        'nomor' => 4,
                        'indikator' => 'Membangun komunikasi efektif dan kolaborasi harmonis antarwarga Satuan Pendidikan',
                        'bukti_fisik' => "1. Jadwal dan notulen rapat dengan guru, tenaga kependidikan, dan komite\n2. Dokumen forum komunikasi (grup resmi, website/googlesite)\n3. Dokumentasi kegiatan kebersamaan warga sekolah\n4. Penilaian 360",
                    ],
                    [
                        'nomor' => 5,
                        'indikator' => 'Menjalin kemitraan strategis dengan komunitas, jejaring profesi, dan pemangku kepentingan eksternal guna mendukung pengembangan Satuan Pendidikan dan partisipasi semesta',
                        'bukti_fisik' => "1. Naskah MoU/perjanjian kerja sama dengan mitra (DUDI, perguruan tinggi, puskesmas, komunitas, sekolah lain)\n2. Laporan pelaksanaan kegiatan kemitraan\n3. Dokumentasi kegiatan bersama mitra\n4. Bukti keikutsertaan dalam jejaring profesi (KKKS/MKKS), PGRI",
                    ],
                    [
                        'nomor' => 6,
                        'indikator' => 'Mewujudkan lingkungan pendidikan yang inklusif, aman, nyaman, sehat, resik, indah, ramah, kondusif, dan menghargai keragaman melalui kepemimpinan yang responsif dan transformatif',
                        'bukti_fisik' => "1. SK Tim Pencegahan dan Penanganan Kekerasan (TPPK) dan laporan kegiatannya\n2. Dokumen program sekolah ramah anak/inklusif (SRL) dan layanan bagi Murid disabilitas\n3. Program guru piket\n4. Mitigasi bencana\n5. Foto fasilitas keamanan dan kenyamanan sekolah",
                    ],
                ]
            ],
            [
                'nama' => 'Profesional',
                'deskripsi' => 'Standar kepemimpinan pembelajaran kurikulum KSP, supervisi kelas, tata kelola sumber daya RKS/RKAS/BOS, pelibatan masyarakat, dan inovasi.',
                'urutan' => 3,
                'indikators' => [
                    [
                        'nomor' => 7,
                        'indikator' => 'Mengembangkan visi, misi, dan tujuan serta budaya belajar Satuan Pendidikan',
                        'bukti_fisik' => "1. KSP dan SK Penetapan kurikulum\n2. Notulen/daftar hadir penyusunan visi-misi yang melibatkan pemangku kepentingan\n3. Dokumen program penguatan budaya belajar (literasi, numerasi, karakter, iklim kebinekaan, iklim keamanan, kualitas pembelajaran)\n4. Bukti sosialisasi visi-misi (banner, website, dokumentasi)",
                    ],
                    [
                        'nomor' => 8,
                        'indikator' => 'Memimpin pengembangan lingkungan pembelajaran dan Satuan Pendidikan yang inklusif, aman, nyaman, sehat, resik, indah, ramah, kondusif, dan menghargai keragaman secara positif',
                        'bukti_fisik' => "1. Rencana kerja dan anggaran pengembangan sarana-prasarana pembelajaran\n2. Laporan dan dokumentasi penataan lingkungan belajar\n3. Dokumen tata tertib dan komitmen budaya positif sekolah\n4. Laporan penanganan kasus (perundungan, intoleransi, dll.)",
                    ],
                    [
                        'nomor' => 9,
                        'indikator' => 'Memimpin pengembangan kurikulum dan pembelajaran yang berpusat pada Murid melalui supervisi, evaluasi, refleksi, dan pendampingan berbasis data secara terencana dan berkelanjutan untuk menciptakan pembelajaran yang berkesadaran, bermakna, dan menggembirakan yang relevan dengan konteks Murid dan lingkungan',
                        'bukti_fisik' => "1. Program, instrumen, jadwal, dan laporan supervisi akademik (kunjungan kelas)\n2. Laporan hasil evaluasi dan refleksi pembelajaran beserta tindak lanjut\n3. Bukti pendampingan guru (coaching/mentoring, komunitas belajar)\n4. Perangkat pembelajaran guru PM yang telah ditelaah",
                    ],
                    [
                        'nomor' => 10,
                        'indikator' => 'Mengelola program dan sumber daya Satuan Pendidikan dan menyelenggarakan tata kelola secara terencana, berbasis data, sistemik, transparan, efektif, efisien, dan akuntabel dalam perencanaan, pelaksanaan, dan evaluasi program',
                        'bukti_fisik' => "1. RKS dan RKAS\n2. Laporan realisasi anggaran/BOS dan bukti publikasi transparansi dana\n3. Dokumen perencanaan berbasis data (rapor pendidikan, evaluasi diri sekolah)\n4. Struktur organisasi, pembagian tugas, dan SK tim kerja\n5. Laporan pengelolaan PID\n6. Dokumen pengelolaan sumber daya (data guru/tendik, sarpras, inventaris)",
                    ],
                    [
                        'nomor' => 11,
                        'indikator' => 'Membangun kerja sama dengan orang tua/wali Murid dan masyarakat dalam pengembangan pembelajaran dan layanan pendidikan',
                        'bukti_fisik' => "1. SK pengurus dan program kerja komite sekolah\n2. Notulen dan daftar hadir pertemuan dengan orang tua/wali Murid\n3. Dokumentasi program pelibatan orang tua (parenting, kelas inspirasi, dll.)\n4. Laporan pelibatan masyarakat dalam kegiatan sekolah",
                    ],
                    [
                        'nomor' => 12,
                        'indikator' => 'Mendorong inovasi, entrepreneurship, dan kepemimpinan transformasional dalam pengembangan Satuan Pendidikan untuk menjawab dinamika zaman dan kebutuhan masa depan',
                        'bukti_fisik' => "1. Dokumen program inovasi sekolah (proposal, laporan, dampak)\n2. Bukti program kewirausahaan Murid/sekolah (produk, unit usaha, bazar)\n3. Bukti pemanfaatan teknologi dalam pembelajaran dan manajemen sekolah\n4. Prestasi/penghargaan, publikasi, atau praktik baik yang dibagikan",
                    ],
                ]
            ]
        ];

        foreach ($komponens as $kData) {
            $komponen = PkksKomponen::updateOrCreate(
                ['nama' => $kData['nama']],
                ['deskripsi' => $kData['deskripsi'], 'urutan' => $kData['urutan']]
            );

            foreach ($kData['indikators'] as $ind) {
                PkksIndikator::updateOrCreate(
                    [
                        'pkks_komponen_id' => $komponen->id,
                        'nomor' => $ind['nomor'],
                    ],
                    [
                        'indikator' => $ind['indikator'],
                        'bukti_fisik_usulan' => $ind['bukti_fisik'],
                    ]
                );
            }
        }

        // 5. Mata Pelajaran SD (E-Learning)
        $courses = [
            ['nama' => 'Semua Mata Pelajaran (Tematik / Seluruh Mapel)', 'kode' => 'SEMUA', 'warna' => 'success'],
            ['nama' => 'Pendidikan Agama Islam & Budi Pekerti', 'kode' => 'PAI', 'warna' => 'success'],
            ['nama' => 'Pendidikan Pancasila', 'kode' => 'PPKN', 'warna' => 'danger'],
            ['nama' => 'Bahasa Indonesia', 'kode' => 'BIN', 'warna' => 'primary'],
            ['nama' => 'Matematika', 'kode' => 'MAT', 'warna' => 'info'],
            ['nama' => 'Ilmu Pengetahuan Alam dan Sosial (IPAS)', 'kode' => 'IPAS', 'warna' => 'warning'],
            ['nama' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'kode' => 'PJOK', 'warna' => 'success'],
            ['nama' => 'Seni Budaya dan Prakarya', 'kode' => 'SBDP', 'warna' => 'secondary'],
            ['nama' => 'Bahasa Sunda (Muatan Lokal)', 'kode' => 'SUNDA', 'warna' => 'danger'],
            ['nama' => 'Bahasa Inggris', 'kode' => 'ENG', 'warna' => 'primary'],
        ];

        foreach ($courses as $crs) {
            Course::updateOrCreate(['nama' => $crs['nama']], $crs);
        }
    }
}
