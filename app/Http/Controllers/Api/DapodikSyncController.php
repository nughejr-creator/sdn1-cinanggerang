<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DapodikWebServiceSync;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DapodikSyncController extends Controller
{
    /**
     * Endpoint API penerima data sinkronisasi dari Dapodik lokal (One-Click Sync).
     */
    public function sync(Request $request): JsonResponse
    {
        // 1. Validasi Autentikasi / Token Keamanan
        $expectedToken = DapodikWebServiceSync::getDefaultToken();
        $providedToken = $request->header('X-Dapodik-Token') 
            ?: $request->bearerToken() 
            ?: $request->input('token');

        if (!$providedToken || $providedToken !== $expectedToken) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: Token Web Service Dapodik tidak valid.',
            ], 401);
        }

        // 2. Ambil payload siswa dan rombel
        $students = $request->input('students') 
            ?: $request->input('rows') 
            ?: [];

        $rombels = $request->input('rombels') 
            ?: $request->input('rombel_rows') 
            ?: [];

        if (empty($students)) {
            return response()->json([
                'success' => false,
                'message' => 'Data peserta didik kosong atau format payload tidak dikenali.',
            ], 422);
        }

        try {
            // 3. Olah data dan masukkan ke database server
            $stats = DapodikWebServiceSync::processSyncData($students, $rombels);

            Log::info("Dapodik Web Service Sync via API sukses", $stats);

            return response()->json([
                'success' => true,
                'message' => "Berhasil menyinkronkan {$stats['total']} siswa dari Dapodik.",
                'data' => $stats,
            ]);
        } catch (\Throwable $e) {
            Log::error("Dapodik Web Service Sync via API gagal: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server saat memproses data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
