import React, { useState, useEffect } from 'react';
import { supabase } from '@/lib/supabaseClient';
import { SchoolProgram, ProgramCategory } from './AddProgramModal';
import { Sparkles, Calendar, ArrowRight, BookOpen, Filter, Search } from 'lucide-react';

interface PostItem {
  id: string;
  judul: string;
  slug: string;
  konten: string;
  kategori: string;
  program_category?: ProgramCategory;
  program_id?: string;
  program?: SchoolProgram;
  thumbnail?: string;
  published_at?: string;
}

export const PublicationPage: React.FC = () => {
  const [activeTab, setActiveTab] = useState<'semua' | ProgramCategory>('semua');
  const [selectedProgramId, setSelectedProgramId] = useState<string>('all');
  const [programs, setPrograms] = useState<SchoolProgram[]>([]);
  const [posts, setPosts] = useState<PostItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');

  // 1. Fetch Program Kegiatan & Postingan
  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      try {
        // Ambil data program kegiatan
        const { data: programData } = await supabase
          .from('school_programs')
          .select('*')
          .eq('is_active', true)
          .order('name');

        if (programData) setPrograms(programData as SchoolProgram[]);

        // Ambil data postingan publikasi beserta relasi program
        const { data: postData } = await supabase
          .from('posts')
          .select(`
            id,
            judul,
            slug,
            konten,
            kategori,
            program_category,
            program_id,
            thumbnail,
            published_at,
            program:school_programs(id, name, category)
          `)
          .eq('status', 'Publish')
          .order('published_at', { ascending: false });

        if (postData) setPosts(postData as any[]);
      } catch (err) {
        console.error('Gagal mengambil data warta:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  // Filter program sesuai tab yang aktif
  const currentCategoryPrograms = activeTab === 'semua'
    ? []
    : programs.filter((p) => p.category === activeTab);

  // Filter artikel berdasarkan Tab Kategori & Sub-Program Pills & Search Query
  const filteredPosts = posts.filter((post) => {
    // 1. Filter Kategori
    if (activeTab !== 'semua') {
      const postCat = post.program_category || post.program?.category;
      if (postCat !== activeTab) return false;
    }

    // 2. Filter Sub-Program
    if (selectedProgramId !== 'all') {
      if (post.program_id !== selectedProgramId && post.program?.id !== selectedProgramId) {
        return false;
      }
    }

    // 3. Pencarian Teks
    if (searchQuery.trim()) {
      const q = searchQuery.toLowerCase();
      const matchTitle = post.judul.toLowerCase().includes(q);
      const matchProgram = post.program?.name.toLowerCase().includes(q);
      if (!matchTitle && !matchProgram) return false;
    }

    return true;
  });

  return (
    <div className="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100">
      
      {/* 1. Header Banner */}
      <div className="bg-emerald-950 text-white py-12 px-4 sm:px-6 lg:px-8 border-b border-emerald-900">
        <div className="max-w-7xl mx-auto">
          <span className="inline-block px-3 py-1 rounded-full bg-emerald-800 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2">
            Publikasi & Kabar Sekolah
          </span>
          <h1 className="text-3xl sm:text-4xl font-black tracking-tight">
            Warta Program Kegiatan Siswa
          </h1>
          <p className="text-xs sm:text-sm text-emerald-200/80 mt-1.5 max-w-2xl">
            Liputan dinamis kegiatan ekstrakurikuler, kokurikuler P5, dan pembiasaan religius & karakter di SDN Cinanggerang I
          </p>
        </div>
      </div>

      {/* 2. Navigasi Tab & Filter Interaktif (Sticky & Responsif HP) */}
      <div className="sticky top-0 z-30 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 shadow-sm">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 space-y-3">
          
          {/* Bar Tab Utama & Search */}
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-3">
            {/* Tab Kategori */}
            <div className="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar text-xs sm:text-sm font-bold">
              <button
                type="button"
                onClick={() => {
                  setActiveTab('semua');
                  setSelectedProgramId('all');
                }}
                className={`shrink-0 px-4 py-2 rounded-xl transition flex items-center gap-1.5 ${
                  activeTab === 'semua'
                    ? 'bg-slate-900 text-white dark:bg-emerald-600 shadow-sm'
                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'
                }`}
              >
                <span>🌐 Semua Warta</span>
              </button>

              <button
                type="button"
                onClick={() => {
                  setActiveTab('ekstrakurikuler');
                  setSelectedProgramId('all');
                }}
                className={`shrink-0 px-4 py-2 rounded-xl transition flex items-center gap-1.5 ${
                  activeTab === 'ekstrakurikuler'
                    ? 'bg-amber-600 text-white shadow-sm ring-2 ring-amber-400'
                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'
                }`}
              >
                <span>🏆 Ekstrakurikuler</span>
              </button>

              <button
                type="button"
                onClick={() => {
                  setActiveTab('kokurikuler');
                  setSelectedProgramId('all');
                }}
                className={`shrink-0 px-4 py-2 rounded-xl transition flex items-center gap-1.5 ${
                  activeTab === 'kokurikuler'
                    ? 'bg-emerald-700 text-white shadow-sm ring-2 ring-emerald-400'
                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'
                }`}
              >
                <span>🌱 Kokurikuler (P5)</span>
              </button>

              <button
                type="button"
                onClick={() => {
                  setActiveTab('pembiasaan');
                  setSelectedProgramId('all');
                }}
                className={`shrink-0 px-4 py-2 rounded-xl transition flex items-center gap-1.5 ${
                  activeTab === 'pembiasaan'
                    ? 'bg-sky-700 text-white shadow-sm ring-2 ring-sky-400'
                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'
                }`}
              >
                <span>✨ Pembiasaan</span>
              </button>
            </div>

            {/* Input Pencarian Cepat */}
            <div className="relative shrink-0 w-full md:w-64">
              <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
              <input
                type="text"
                placeholder="Cari berita / program..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-9 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none"
              />
            </div>
          </div>

          {/* Sub-Program Filter Pills (Muncul saat tab kategori dipilih) */}
          {activeTab !== 'semua' && currentCategoryPrograms.length > 0 && (
            <div className="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar text-xs pt-2 border-t border-slate-100 dark:border-slate-800">
              <span className="text-slate-400 text-[11px] font-semibold shrink-0">Kegiatan:</span>
              
              <button
                type="button"
                onClick={() => setSelectedProgramId('all')}
                className={`shrink-0 px-3 py-1 rounded-full font-bold transition border ${
                  selectedProgramId === 'all'
                    ? 'bg-slate-900 text-white border-slate-900 dark:bg-white dark:text-slate-900'
                    : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700'
                }`}
              >
                Semua {activeTab}
              </button>

              {currentCategoryPrograms.map((prog) => (
                <button
                  key={prog.id}
                  type="button"
                  onClick={() => setSelectedProgramId(prog.id)}
                  className={`shrink-0 px-3 py-1 rounded-full font-semibold transition border ${
                    selectedProgramId === prog.id
                      ? activeTab === 'ekstrakurikuler'
                        ? 'bg-amber-100 text-amber-900 border-amber-400 font-black'
                        : activeTab === 'kokurikuler'
                        ? 'bg-emerald-100 text-emerald-900 border-emerald-400 font-black'
                        : 'bg-sky-100 text-sky-900 border-sky-400 font-black'
                      : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700'
                  }`}
                >
                  {prog.name}
                </button>
              ))}
            </div>
          )}

        </div>
      </div>

      {/* 3. Grid Kartu Postingan */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        <div className="flex items-center justify-between mb-6">
          <p className="text-xs font-semibold text-slate-500">
            Menampilkan <span className="font-bold text-slate-900 dark:text-white">{filteredPosts.length}</span> Warta Terpilih
          </p>
        </div>

        {loading ? (
          <div className="py-20 text-center text-slate-400 text-xs">
            Memuat postingan warta kegiatan...
          </div>
        ) : filteredPosts.length === 0 ? (
          <div className="py-20 p-8 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800">
            <span className="text-4xl">📰</span>
            <h3 className="mt-3 font-bold text-base text-slate-800 dark:text-white">
              Belum Ada Postingan pada Kategori Ini
            </h3>
            <p className="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
              Bapak/Ibu Guru dapat mendokumentasikan kegiatan belajar atau ekstrakurikuler melalui dashboard admin sekolah.
            </p>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            {filteredPosts.map((post) => {
              const progCat = post.program_category || post.program?.category;
              const progName = post.program?.name;

              return (
                <article
                  key={post.id}
                  className="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group transform hover:-translate-y-1"
                >
                  {/* Thumbnail & Badge Kegiatan */}
                  <div className="aspect-video w-full bg-slate-100 dark:bg-slate-800 relative overflow-hidden">
                    {post.thumbnail ? (
                      <img
                        src={post.thumbnail}
                        alt={post.judul}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                      />
                    ) : (
                      <div className="w-full h-full flex items-center justify-center bg-gradient-to-tr from-emerald-950 to-emerald-900 text-emerald-400/50">
                        <BookOpen className="w-12 h-12" />
                      </div>
                    )}

                    {/* BADGE KATEGORI & NAMA PROGRAM */}
                    {progName ? (
                      <span
                        className={`absolute top-3 left-3 px-3 py-1 backdrop-blur-md text-white text-[10px] font-extrabold rounded-lg uppercase tracking-wider shadow-sm border border-white/20 ${
                          progCat === 'ekstrakurikuler'
                            ? 'bg-amber-600/90'
                            : progCat === 'kokurikuler'
                            ? 'bg-emerald-600/90'
                            : 'bg-sky-600/90'
                        }`}
                      >
                        [{progCat === 'ekstrakurikuler' ? 'Ekstra' : progCat === 'kokurikuler' ? 'Kokurikuler' : 'Pembiasaan'}] {progName}
                      </span>
                    ) : (
                      <span className="absolute top-3 left-3 px-2.5 py-1 bg-slate-950/80 backdrop-blur-sm text-white text-[10px] font-bold rounded-lg uppercase tracking-wider border border-white/10">
                        {post.kategori}
                      </span>
                    )}
                  </div>

                  {/* Konten Ringkas Berita */}
                  <div className="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                      <div className="text-[11px] text-slate-400 mb-2 flex items-center gap-1.5">
                        <Calendar className="w-3.5 h-3.5" />
                        <span>
                          {post.published_at
                            ? new Date(post.published_at).toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric',
                              })
                            : 'Terbaru'}
                        </span>
                        <span>•</span>
                        <span>SDN Cinanggerang I</span>
                      </div>

                      <h3 className="font-extrabold text-base text-slate-900 dark:text-white leading-snug line-clamp-2 group-hover:text-emerald-600 transition">
                        {post.judul}
                      </h3>

                      <p className="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                        {post.konten ? post.konten.replace(/<[^>]*>?/gm, '').slice(0, 120) + '...' : ''}
                      </p>
                    </div>

                    <div className="pt-3 border-t border-slate-100 dark:border-slate-800">
                      <a
                        href={`/warta/${post.slug || post.id}`}
                        className="inline-flex items-center justify-between w-full text-xs font-bold text-emerald-700 dark:text-emerald-400 group-hover:text-emerald-600"
                      >
                        <span>Baca Selengkapnya</span>
                        <ArrowRight className="w-4 h-4 transform group-hover:translate-x-1 transition-transform" />
                      </a>
                    </div>
                  </div>
                </article>
              );
            })}
          </div>
        )}
      </div>
    </div>
  );
};
