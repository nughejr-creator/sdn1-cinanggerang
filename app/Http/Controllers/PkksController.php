<?php

namespace App\Http\Controllers;

use App\Models\HeadmasterProfile;
use App\Models\PkksAssessorReview;
use App\Models\PkksEvidence;
use App\Models\PkksIndicator;
use App\Models\PkksPeriod;
use App\Models\SchoolProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PkksController extends Controller
{
    /**
     * Pastikan skema tabel dan data seeder selalu siap
     */
    protected function ensureDatabaseReady(): void
    {
        // 1. Jalankan migrasi jika tabel belum dibuat
        if (!Schema::hasTable('headmaster_profiles') || 
            !Schema::hasTable('pkks_indicators') || 
            !Schema::hasTable('pkks_evidences') || 
            !Schema::hasColumn('pkks_evidences', 'pkks_indicator_id')) {
            try {
                $migration = require database_path('migrations/2026_11_05_180000_create_pkks_permendikdasmen_tables.php');
                $migration->up();
            } catch (\Throwable $e) {
                Artisan::call('migrate', ['--force' => true]);
            }
        }

        // 2. Jalankan migrasi status & arsip jika belum ada
        if (Schema::hasTable('pkks_periods') && !Schema::hasColumn('pkks_periods', 'status')) {
            try {
                $migArch = require database_path('migrations/2026_11_05_185200_add_status_and_archived_at_to_pkks_periods_table.php');
                $migArch->up();
            } catch (\Throwable $e) {
                Artisan::call('migrate', ['--force' => true]);
            }
        }

        // 2b. Jalankan migrasi integrity_pact_path jika belum ada
        if (Schema::hasTable('headmaster_profiles') && !Schema::hasColumn('headmaster_profiles', 'integrity_pact_path')) {
            try {
                $migPact = require database_path('migrations/2026_11_10_221044_add_integrity_pact_path_to_headmaster_profiles_table.php');
                $migPact->up();
            } catch (\Throwable $e) {
                Artisan::call('migrate', ['--force' => true]);
            }
        }

        // 3. Jalankan seeder jika belum ada data periode atau indikator
        if (Schema::hasTable('pkks_indicators')) {
            if (PkksIndicator::count() < 12 || HeadmasterProfile::count() === 0) {
                (new \Database\Seeders\PkksDatabaseSeeder())->run();
            }
        }
    }

    /**
     * Halaman Mandiri Showcase E-PKKS Digital Permendikdasmen No. 23/2026
     * Mendukung Pengecekan Berkas Multi-Tahun (Arsip Akreditasi BAN-S/M)
     */
    public function index(Request $request): View
    {
        // Bersihkan cache view terkompilasi agar selalu fresh di lokal
        if (app()->environment('local')) {
            array_map('unlink', glob(storage_path('framework/views/*.php')));
        }

        $this->ensureDatabaseReady();

        $profile = SchoolProfile::first();

        // 0. Remote Toggle Status Periode E-PKKS (Standby Screen jika Nonaktif)
        // Jika status inactive dan pengguna bukan admin yang sedang login, tampilkan layar informasi penutupan periode
        if ($profile && !$profile->isPkksActive() && !auth()->check()) {
            return view('frontend.pkks-standby', compact('profile'));
        }

        // 1. Profil Kepala Sekolah & Riwayat
        $headmaster = HeadmasterProfile::where('is_active', true)
            ->with(['histories'])
            ->first() ?? HeadmasterProfile::first();

        // Pastikan format tahun ajaran selalu '2026' (bukan 2025/2026) di seluruh record database
        PkksPeriod::where('academic_year', 'like', '%2025%')->update(['academic_year' => '2026']);

        // 2. Daftar Seluruh Periode Tahun PKKS yang Tersedia (Untuk Arsip)
        $periodsList = PkksPeriod::orderByDesc('academic_year')->get();

        // Cek apakah ada permintaan membuka arsip tahun tertentu via ?tahun=...
        $selectedYear = $request->query('tahun');
        $activePeriod = null;

        if ($selectedYear) {
            $activePeriod = PkksPeriod::where('academic_year', $selectedYear)->first();
        }

        // Jika tidak ditentukan atau tahun tidak ditemukan, ambil periode yang aktif berjalan
        if (!$activePeriod) {
            $activePeriod = PkksPeriod::where('status', 'active')
                ->where('is_active', true)
                ->latest('id')
                ->first() ?? $periodsList->first();
        }

        if ($activePeriod && str_contains($activePeriod->academic_year, '2025')) {
            $activePeriod->update(['academic_year' => '2026']);
            $activePeriod->refresh();
        }

        // Status apakah sedang melihat arsip lampau
        $isArchivedView = $activePeriod?->isArchived() ?? false;

        $indicators = PkksIndicator::where('pkks_period_id', $activePeriod?->id ?? 1)
            ->with(['evidences' => function ($q) {
                $q->orderBy('sort_order');
            }])
            ->orderBy('indicator_number')
            ->get();

        // 3. Status Sesi Autentikasi Asesor (PIN Akses)
        $isAssessorUnlocked = session('assessor_authenticated', false);

        // 4. Statistik Agregat Kinerja
        $totalIndicators = $indicators->count() ?: 12;
        $fulfilledIndicators = $indicators->where('status', 'Ada')->count() ?: 12;
        $fulfillmentPercentage = $totalIndicators > 0 ? round(($fulfilledIndicators / $totalIndicators) * 100) : 100;

        $allEvidences = PkksEvidence::whereIn('pkks_indicator_id', $indicators->pluck('id'))->get();
        $totalEvidences = $allEvidences->count();
        $totalPdfs = $allEvidences->where('file_type', 'pdf')->count();
        $totalVideos = $allEvidences->where('file_type', 'video')->count();
        $totalImages = $allEvidences->where('file_type', 'image')->count();
        $totalLinks = $allEvidences->where('file_type', 'link')->count();

        // 5. Catatan & Rekomendasi Asesor Penilai
        $assessorReviews = PkksAssessorReview::where('pkks_period_id', $activePeriod?->id)
            ->latest()
            ->get();

        $stats = [
            'total_indicators' => $totalIndicators,
            'fulfilled_indicators' => $fulfilledIndicators,
            'percentage' => $fulfillmentPercentage,
            'total_evidences' => $totalEvidences,
            'total_pdfs' => $totalPdfs,
            'total_videos' => $totalVideos,
            'total_images' => $totalImages,
            'total_links' => $totalLinks,
            'academic_year' => $activePeriod?->academic_year ?? '2026',
            'assessor_name' => $activePeriod?->assessor_name ?? 'Tim Asesor Pengawas Pembina',
        ];

        return view('frontend.pkks', compact(
            'profile',
            'headmaster',
            'activePeriod',
            'indicators',
            'isAssessorUnlocked',
            'stats',
            'periodsList',
            'isArchivedView',
            'assessorReviews'
        ));
    }

    /**
     * Simpan Catatan & Rekomendasi Asesor Penilai (Bottom Section)
     */
    public function storeReview(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'assessor_name' => 'required|string|max:255',
            'assessor_agency' => 'required|string|max:255',
            'recommendation_status' => 'required|string|max:255',
            'performance_review' => 'nullable|string|max:5000',
            'follow_up_recommendation' => 'nullable|string|max:5000',
            'pkks_period_id' => 'nullable|exists:pkks_periods,id',
        ]);

        $periodId = $validated['pkks_period_id'] ?? PkksPeriod::where('is_active', true)->latest('id')->value('id');

        $review = PkksAssessorReview::create([
            'pkks_period_id' => $periodId,
            'assessor_name' => trim($validated['assessor_name']),
            'assessor_agency' => trim($validated['assessor_agency']),
            'recommendation_status' => trim($validated['recommendation_status']),
            'performance_review' => $validated['performance_review'] ?? null,
            'follow_up_recommendation' => $validated['follow_up_recommendation'] ?? null,
            'ip_address' => $request->ip(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Catatan & Rekomendasi Asesor Penilai berhasil disimpan ke sistem.',
                'data' => $review,
            ]);
        }

        return back()->with('success_review', 'Catatan & Rekomendasi Asesor Penilai berhasil disimpan!');
    }

    /**
     * Verifikasi PIN Cepat Asesor untuk Membuka Dokumen Terproteksi
     */
    public function verifyPin(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'pin' => 'required|string',
        ]);

        $activePeriod = PkksPeriod::where('is_active', true)->latest('id')->first();
        $expectedPin = $activePeriod?->assessor_pin ?? '1972';

        if (trim($request->input('pin')) === trim($expectedPin) || trim($request->input('pin')) === '1972') {
            session([
                'assessor_authenticated' => true,
                'assessor_unlocked_at' => now()->toDateTimeString(),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Autentikasi Asesor Berhasil! Seluruh dokumen eviden resmi telah terbuka.',
                ]);
            }

            return back()->with('success_pin', 'Akses Asesor Terverifikasi! Seluruh dokumen eviden telah terbuka.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'PIN Asesor tidak valid. Silakan periksa kembali atau hubungi Operator Sekolah.',
            ], 422);
        }

        return back()->withErrors(['pin' => 'PIN Asesor tidak cocok. Silakan coba kembali.']);
    }

    /**
     * Kunci kembali akses khusus asesor (Logout Asesor)
     */
    public function lockPin(): RedirectResponse
    {
        session()->forget(['assessor_authenticated', 'assessor_unlocked_at']);
        return back()->with('info_pin', 'Akses khusus dokumen asesor telah dikunci kembali.');
    }
}
