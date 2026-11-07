-- ============================================================================
-- MIGRASI SUPABASE (PostgreSQL): FITUR PROGRAM KEGIATAN SEKOLAH SDN CINANGGERANG I
-- Modul: Ekstrakurikuler, Kokurikuler (P5), dan Pembiasaan Karakter
-- ============================================================================

-- 1. Buat Tabel `school_programs`
CREATE TABLE IF NOT EXISTS public.school_programs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name TEXT NOT NULL,
    category TEXT NOT NULL CHECK (category IN ('ekstrakurikuler', 'kokurikuler', 'pembiasaan')),
    description TEXT,
    created_by UUID REFERENCES auth.users(id) ON DELETE SET NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMPTZ DEFAULT now(),
    updated_at TIMESTAMPTZ DEFAULT now()
);

-- Index pada kolom category untuk optimasi filter query
CREATE INDEX IF NOT EXISTS idx_school_programs_category ON public.school_programs(category);

-- 2. Tambahkan Relasi ke Tabel `posts` / `articles`
ALTER TABLE public.posts 
    ADD COLUMN IF NOT EXISTS program_id UUID REFERENCES public.school_programs(id) ON DELETE SET NULL,
    ADD COLUMN IF NOT EXISTS program_category TEXT;

CREATE INDEX IF NOT EXISTS idx_posts_program_id ON public.posts(program_id);
CREATE INDEX IF NOT EXISTS idx_posts_program_category ON public.posts(program_category);

-- 3. Row Level Security (RLS) Policies
ALTER TABLE public.school_programs ENABLE ROW LEVEL SECURITY;

-- Policy SELECT: Publik / Pengunjung Tanpa Login dapat melihat program yang aktif
CREATE POLICY "Public & anon can view active school programs"
    ON public.school_programs
    FOR SELECT
    USING (is_active = true);

-- Policy INSERT: Hanya pengguna terautentikasi (Guru / Admin) yang bisa menambah program baru
CREATE POLICY "Authenticated users (teachers & admins) can create programs"
    ON public.school_programs
    FOR INSERT
    TO authenticated
    WITH CHECK (auth.uid() IS NOT NULL);

-- Policy UPDATE: Pengguna terautentikasi dapat memperbarui program
CREATE POLICY "Authenticated users can update school programs"
    ON public.school_programs
    FOR UPDATE
    TO authenticated
    USING (auth.uid() IS NOT NULL);

-- Policy DELETE: Pengguna terautentikasi (atau Admin) dapat menghapus program
CREATE POLICY "Authenticated users can delete school programs"
    ON public.school_programs
    FOR DELETE
    TO authenticated
    USING (auth.uid() IS NOT NULL);

-- 4. Seed Data Awal: 10 Program Kegiatan Resmi SDN Cinanggerang I
INSERT INTO public.school_programs (name, category, description)
VALUES 
    -- 1. Ekstrakurikuler
    ('Pramuka', 'ekstrakurikuler', 'Praja Muda Karana - Pembinaan kepanduan, kemandirian, kedisiplinan dan kepemimpinan siswa.'),
    ('Olahraga', 'ekstrakurikuler', 'Pengembangan bakat dan prestasi atletik, sepak bola, bola voli, dan bulutangkis.'),
    ('Seni', 'ekstrakurikuler', 'Pengembangan kreativitas seni rupa, seni musik, dan tari tradisional Sunda.'),
    ('Kewirausahaan', 'ekstrakurikuler', 'Pendidikan kewirausahaan sejak dini, market day, dan karya kreativitas daur ulang.'),

    -- 2. Kokurikuler
    ('G7KAIH', 'kokurikuler', 'Gerakan 7 Kebaikan Anak Indonesia Hebat - Pembiasaan budi pekerti luhur dan nilai Pancasila.'),
    ('Sekolah Ramah Lingkungan', 'kokurikuler', 'Program Adiwiyata, pemeliharaan pekarangan hijau sekolah, dan apotek hidup.'),
    ('Zero Waste', 'kokurikuler', 'Gerakan bebas sampah plastik, pemilahan sampah organik/anorganik, dan daur ulang.'),

    -- 3. Pembiasaan
    ('Sholat Dhuha', 'pembiasaan', 'Pembiasaan sholat dhuha bersama di sekolah untuk menumbuhkan karakter religius siswa.'),
    ('Senam', 'pembiasaan', 'Senam kesegaran jasmani ceria rutin setiap pagi untuk kesehatan jasmani seluruh warga sekolah.'),
    ('Literasi', 'pembiasaan', 'Gerakan literasi membaca buku bacaan 15 menit sebelum pembelajaran di pojok baca kelas.')
ON CONFLICT DO NOTHING;
