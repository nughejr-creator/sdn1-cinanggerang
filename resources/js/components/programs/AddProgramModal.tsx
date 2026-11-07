import React, { useState } from 'react';
import { supabase } from '@/lib/supabaseClient'; // Sesuaikan path client Supabase Anda
import { X, Plus, Sparkles, Loader2 } from 'lucide-react';

export type ProgramCategory = 'ekstrakurikuler' | 'kokurikuler' | 'pembiasaan';

export interface SchoolProgram {
  id: string;
  name: string;
  category: ProgramCategory;
  description?: string;
  is_active?: boolean;
}

interface AddProgramModalProps {
  isOpen: boolean;
  onClose: () => void;
  defaultCategory?: ProgramCategory;
  onProgramAdded: (newProgram: SchoolProgram) => void;
}

export const AddProgramModal: React.FC<AddProgramModalProps> = ({
  isOpen,
  onClose,
  defaultCategory = 'ekstrakurikuler',
  onProgramAdded,
}) => {
  const [name, setName] = useState('');
  const [category, setCategory] = useState<ProgramCategory>(defaultCategory);
  const [description, setDescription] = useState('');
  const [loading, setLoading] = useState(false);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);

  if (!isOpen) return null;

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!name.trim()) {
      setErrorMessage('Nama kegiatan wajib diisi.');
      return;
    }

    setLoading(true);
    setErrorMessage(null);

    try {
      // Dapatkan user aktif (Guru/Admin)
      const { data: { user } } = await supabase.auth.getUser();

      const { data, error } = await supabase
        .from('school_programs')
        .insert([
          {
            name: name.trim(),
            category,
            description: description.trim() || null,
            created_by: user?.id || null,
            is_active: true,
          },
        ])
        .select()
        .single();

      if (error) throw error;

      if (data) {
        onProgramAdded(data as SchoolProgram);
        // Reset form & tutup modal
        setName('');
        setDescription('');
        onClose();
      }
    } catch (err: any) {
      console.error('Gagal menambahkan program kegiatan:', err);
      setErrorMessage(err.message || 'Terjadi kesalahan saat menyimpan data.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-in fade-in duration-200">
      <div 
        className="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all"
        onClick={(e) => e.stopPropagation()}
      >
        {/* Header Modal */}
        <div className="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
          <div className="flex items-center gap-2">
            <span className="p-2 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
              <Sparkles className="w-5 h-5" />
            </span>
            <div>
              <h3 className="font-bold text-slate-900 dark:text-white text-base">
                Tambah Program Kegiatan Baru
              </h3>
              <p className="text-xs text-slate-500 dark:text-slate-400">
                Daftarkan kegiatan baru ke basis data sekolah
              </p>
            </div>
          </div>
          <button
            type="button"
            onClick={onClose}
            className="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg transition"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Form Modal */}
        <form onSubmit={handleSubmit} className="p-6 space-y-4">
          {errorMessage && (
            <div className="p-3 text-xs text-red-700 bg-red-50 border border-red-200 rounded-xl">
              {errorMessage}
            </div>
          )}

          {/* Input Kategori */}
          <div>
            <label className="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
              Kategori Program <span className="text-red-500">*</span>
            </label>
            <select
              value={category}
              onChange={(e) => setCategory(e.target.value as ProgramCategory)}
              className="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition"
              required
            >
              <option value="ekstrakurikuler">Ekstrakurikuler (Bakat, Minat & Seni)</option>
              <option value="kokurikuler">Kokurikuler (P5, Ramah Lingkungan & Karakter)</option>
              <option value="pembiasaan">Pembiasaan (Ibadah, Kebugaran & Literasi)</option>
            </select>
          </div>

          {/* Input Nama Kegiatan */}
          <div>
            <label className="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
              Nama Kegiatan <span className="text-red-500">*</span>
            </label>
            <input
              type="text"
              placeholder="Contoh: Panahan / Tari Jaipong / Sholat Ashar"
              value={name}
              onChange={(e) => setName(e.target.value)}
              className="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition"
              required
              autoFocus
            />
          </div>

          {/* Input Deskripsi (Opsional) */}
          <div>
            <label className="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
              Deskripsi Singkat (Opsional)
            </label>
            <textarea
              placeholder="Jelaskan ringkasan tujuan, jadwal, atau target peserta..."
              rows={3}
              value={description}
              onChange={(e) => setDescription(e.target.value)}
              className="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition resize-none"
            />
          </div>

          {/* Aksi Tombol */}
          <div className="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
            <button
              type="button"
              onClick={onClose}
              disabled={loading}
              className="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition"
            >
              Batal
            </button>
            <button
              type="submit"
              disabled={loading}
              className="inline-flex items-center gap-2 px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 active:scale-95 rounded-xl shadow-md transition disabled:opacity-50"
            >
              {loading ? (
                <>
                  <Loader2 className="w-4 h-4 animate-spin" />
                  <span>Menyimpan...</span>
                </>
              ) : (
                <>
                  <Plus className="w-4 h-4" />
                  <span>Simpan & Pilih</span>
                </>
              )}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};
