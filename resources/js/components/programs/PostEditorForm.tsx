import React, { useState, useEffect } from 'react';
import { supabase } from '@/lib/supabaseClient';
import { AddProgramModal, SchoolProgram, ProgramCategory } from './AddProgramModal';
import { Plus, Tag, Sparkles, Loader2, Image as ImageIcon, Send } from 'lucide-react';

interface PostFormData {
  title: string;
  slug: string;
  content: string;
  category: string;
  program_category: ProgramCategory | '';
  program_id: string | '';
  status: 'Publish' | 'Draft';
  thumbnail_url?: string;
}

export const PostEditorForm: React.FC = () => {
  const [formData, setFormData] = useState<PostFormData>({
    title: '',
    slug: '',
    content: '',
    category: 'Berita Sekolah',
    program_category: '',
    program_id: '',
    status: 'Publish',
  });

  const [programs, setPrograms] = useState<SchoolProgram[]>([]);
  const [loadingPrograms, setLoadingPrograms] = useState(false);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [successMessage, setSuccessMessage] = useState<string | null>(null);

  // Ambil daftar program dari Supabase
  const fetchPrograms = async () => {
    setLoadingPrograms(true);
    try {
      const { data, error } = await supabase
        .from('school_programs')
        .select('*')
        .eq('is_active', true)
        .order('name', { ascending: true });

      if (error) throw error;
      if (data) setPrograms(data as SchoolProgram[]);
    } catch (err) {
      console.error('Gagal mengambil daftar program:', err);
    } finally {
      setLoadingPrograms(false);
    }
  };

  useEffect(() => {
    fetchPrograms();
  }, []);

  // Filter program sesuai kategori terpilih
  const filteredPrograms = formData.program_category
    ? programs.filter((p) => p.category === formData.program_category)
    : programs;

  // Callback saat guru selesai menambahkan kegiatan baru di modal
  const handleProgramAdded = (newProg: SchoolProgram) => {
    setPrograms((prev) => [...prev, newProg]);
    setFormData((prev) => ({
      ...prev,
      program_category: newProg.category,
      program_id: newProg.id,
    }));
  };

  const handleTitleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const title = e.target.value;
    const slug = title
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)+/g, '');
    setFormData((prev) => ({ ...prev, title, slug }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    setSuccessMessage(null);

    try {
      const { data: { user } } = await supabase.auth.getUser();

      const { data, error } = await supabase.from('posts').insert([
        {
          judul: formData.title,
          slug: formData.slug || `post-${Date.now()}`,
          konten: formData.content,
          kategori: formData.category,
          program_category: formData.program_category || null,
          program_id: formData.program_id || null,
          status: formData.status,
          user_id: user?.id || null,
          published_at: new Date().toISOString(),
        },
      ]);

      if (error) throw error;

      setSuccessMessage('Postingan kegiatan sekolah berhasil diterbitkan!');
      // Reset form
      setFormData({
        title: '',
        slug: '',
        content: '',
        category: 'Berita Sekolah',
        program_category: '',
        program_id: '',
        status: 'Publish',
      });
    } catch (err: any) {
      alert('Gagal menerbitkan postingan: ' + err.message);
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="max-w-4xl mx-auto p-6 bg-white dark:bg-slate-900 rounded-3xl shadow-lg border border-slate-200 dark:border-slate-800">
      <div className="mb-6">
        <h2 className="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
          <span>📝</span>
          <span>Tulis Warta & Kegiatan Sekolah</span>
        </h2>
        <p className="text-xs text-slate-500 dark:text-slate-400">
          Publikasikan liputan kegiatan pembelajaran, ekstrakurikuler, dan prestasi kelas ke portal resmi sekolah
        </p>
      </div>

      {successMessage && (
        <div className="mb-6 p-4 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-2xl text-xs font-bold flex items-center gap-2">
          <span>✅</span>
          <span>{successMessage}</span>
        </div>
      )}

      <form onSubmit={handleSubmit} className="space-y-6">
        {/* Judul & Slug */}
        <div className="space-y-4">
          <div>
            <label className="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
              Judul Berita / Postingan <span className="text-red-500">*</span>
            </label>
            <input
              type="text"
              placeholder="Contoh: Latihan Perdana Pramuka Penggalang SDN Cinanggerang I Penuh Antusias"
              value={formData.title}
              onChange={handleTitleChange}
              required
              className="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none"
            />
          </div>

          <div>
            <label className="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">
              Tautan Ramah SEO (Slug Otomatis)
            </label>
            <input
              type="text"
              value={formData.slug}
              readOnly
              className="w-full px-3.5 py-2 text-xs font-mono rounded-lg border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-600 dark:text-slate-400"
            />
          </div>
        </div>

        {/* KELOMPOK PROGRAM KEGIATAN SEKOLAH (DROPDOWN BERTINGKAT + OPSI CEPAT TAMBAH) */}
        <div className="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 space-y-4">
          <div className="flex items-center justify-between">
            <span className="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
              <Sparkles className="w-4 h-4 text-emerald-600" />
              <span>Program Kegiatan Terkait (Opsional)</span>
            </span>
            <span className="text-[11px] text-slate-500">Pilih kategori untuk memfilter kegiatan</span>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {/* 1. Kategori Kegiatan */}
            <div>
              <label className="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">
                Kategori Kegiatan
              </label>
              <select
                value={formData.program_category}
                onChange={(e) => {
                  setFormData((prev) => ({
                    ...prev,
                    program_category: e.target.value as ProgramCategory | '',
                    program_id: '', // Reset kegiatan saat kategori berubah
                  }));
                }}
                className="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none"
              >
                <option value="">-- Postingan Umum (Bukan Program) --</option>
                <option value="ekstrakurikuler">🏆 Ekstrakurikuler</option>
                <option value="kokurikuler">🌱 Kokurikuler (P5 & Lingkungan)</option>
                <option value="pembiasaan">✨ Pembiasaan Karakter</option>
              </select>
            </div>

            {/* 2. Nama Kegiatan + Tombol Tambah Cepat */}
            <div>
              <div className="flex items-center justify-between mb-1.5">
                <label className="text-xs font-semibold text-slate-600 dark:text-slate-300">
                  Nama Kegiatan
                </label>
                {/* Tombol Opsi Cepat Tambah Kegiatan Manual */}
                <button
                  type="button"
                  onClick={() => setIsModalOpen(true)}
                  className="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 hover:underline"
                >
                  <Plus className="w-3.5 h-3.5" />
                  <span>Tambah Kegiatan Baru</span>
                </button>
              </div>

              <select
                value={formData.program_id}
                onChange={(e) => setFormData((prev) => ({ ...prev, program_id: e.target.value }))}
                disabled={loadingPrograms || filteredPrograms.length === 0}
                className="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none disabled:bg-slate-100 disabled:opacity-60"
              >
                <option value="">
                  {loadingPrograms
                    ? 'Memuat kegiatan...'
                    : filteredPrograms.length === 0
                    ? 'Belum ada kegiatan (klik "+ Tambah Kegiatan Baru")'
                    : '-- Pilih Nama Kegiatan --'}
                </option>
                {filteredPrograms.map((prog) => (
                  <option key={prog.id} value={prog.id}>
                    {prog.name}
                  </option>
                ))}
              </select>
            </div>
          </div>
        </div>

        {/* Konten Lengkap Berita */}
        <div>
          <label className="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
            Isi Berita / Narasi Kegiatan <span className="text-red-500">*</span>
          </label>
          <textarea
            rows={8}
            placeholder="Tuliskan jalannya kegiatan, peserta yang terlibat, serta pesan edukatif dari kegiatan..."
            value={formData.content}
            onChange={(e) => setFormData((prev) => ({ ...prev, content: e.target.value }))}
            required
            className="w-full px-4 py-3 text-sm rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none"
          />
        </div>

        {/* Status & Tombol Submit */}
        <div className="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-200 dark:border-slate-800">
          <div className="flex items-center gap-3">
            <label className="text-xs font-bold text-slate-600 dark:text-slate-300">Status:</label>
            <select
              value={formData.status}
              onChange={(e) => setFormData((prev) => ({ ...prev, status: e.target.value as any }))}
              className="px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white"
            >
              <option value="Publish">Publish (Tayang ke Web)</option>
              <option value="Draft">Draft (Simpan Sementara)</option>
            </select>
          </div>

          <button
            type="submit"
            disabled={submitting}
            className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition disabled:opacity-50"
          >
            {submitting ? (
              <>
                <Loader2 className="w-4 h-4 animate-spin" />
                <span>Menerbitkan...</span>
              </>
            ) : (
              <>
                <Send className="w-4 h-4" />
                <span>Terbitkan Postingan</span>
              </>
            )}
          </button>
        </div>
      </form>

      {/* Modal Cepat Tambah Kegiatan Manual */}
      <AddProgramModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        defaultCategory={formData.program_category || 'ekstrakurikuler'}
        onProgramAdded={handleProgramAdded}
      />
    </div>
  );
};
