# ==============================================================================
# SINKRONISASI DATA DAPODIK KE WEBSITE SDN CINANGGERANG I
# ==============================================================================
# Script ini secara otomatis:
# 1. Menarik data Peserta Didik & Rombel dari Web Service Dapodik lokal (port 5774)
# 2. Mengirimkan data ke server database website (https://sdncinanggerang1.onrender.com)
# ==============================================================================

param(
    [string]$TargetServer = "https://sdncinanggerang1.onrender.com",
    [string]$TokenDapodik = "Cu7bJNdcgqTI42V",
    [string]$NPSN = "20208801",
    [string]$LocalDapodikUrl = "http://127.0.0.1:5774"
)

$Host.UI.RawUI.WindowTitle = "Sinkronisasi Dapodik -> Website SDN Cinanggerang 1"
Clear-Host

Write-Host "========================================================================" -ForegroundColor Cyan
Write-Host "     SINKRONISASI OTOMATIS DAPODIK -> WEBSITE SDN CINANGGERANG 1        " -ForegroundColor Yellow
Write-Host "========================================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Target Server Web  : $TargetServer" -ForegroundColor Gray
Write-Host "Dapodik Lokal      : $LocalDapodikUrl" -ForegroundColor Gray
Write-Host "NPSN Sekolah       : $NPSN" -ForegroundColor Gray
Write-Host "Token Web Service  : $(($TokenDapodik.Substring(0, 4)) + '***' + ($TokenDapodik.Substring($TokenDapodik.Length - 3)))" -ForegroundColor Gray
Write-Host ""

# 1. Cek Konektivitas ke Dapodik Lokal
Write-Host "[1/3] Memeriksa koneksi ke Web Service Dapodik..." -ForegroundColor White -NoNewline
try {
    $test = Test-NetConnection -ComputerName 127.0.0.1 -Port 5774 -WarningAction SilentlyContinue
    if (-not $test.TcpTestSucceeded) {
        Write-Host " [GAGAL]" -ForegroundColor Red
        Write-Host ""
        Write-Host "ERROR: Web Service Dapodik di port 5774 tidak terdeteksi!" -ForegroundColor Red
        Write-Host "Pastikan aplikasi Dapodik di komputer ini sudah dibuka/berjalan." -ForegroundColor Yellow
        Write-Host ""
        Read-Host "Tekan Enter untuk keluar..."
        exit 1
    }
    Write-Host " [OK]" -ForegroundColor Green
} catch {
    Write-Host " [Lewati pengecekan port]" -ForegroundColor Yellow
}

$headers = @{
    "Authorization" = "Bearer $TokenDapodik"
}

# 2. Ambil Data dari Web Service Dapodik
Write-Host "[2/3] Menarik data dari Dapodik lokal..." -ForegroundColor White
try {
    Write-Host "      - Mengambil data Rombongan Belajar... " -NoNewline
    $rombelRes = Invoke-RestMethod -Uri "$LocalDapodikUrl/WebService/getRombonganBelajar?npsn=$NPSN" -Headers $headers -TimeoutSec 15
    $rombels = if ($rombelRes.rows) { $rombelRes.rows } else { @() }
    Write-Host "OK ($($rombels.Count) rombel ditemukan)" -ForegroundColor Green

    Write-Host "      - Mengambil data Peserta Didik... " -NoNewline
    $pdRes = Invoke-RestMethod -Uri "$LocalDapodikUrl/WebService/getPesertaDidik?npsn=$NPSN" -Headers $headers -TimeoutSec 30
    $students = if ($pdRes.rows) { $pdRes.rows } else { @() }
    Write-Host "OK ($($students.Count) siswa ditemukan)" -ForegroundColor Green

    if ($students.Count -eq 0) {
        Write-Host ""
        Write-Host "PERINGATAN: Tidak ada data peserta didik yang ditarik dari Dapodik." -ForegroundColor Yellow
        Read-Host "Tekan Enter untuk keluar..."
        exit 1
    }
} catch {
    Write-Host " [GAGAL]" -ForegroundColor Red
    Write-Host ""
    Write-Host "ERROR saat mengambil data dari Dapodik: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Pastikan Token Web Service '$TokenDapodik' sesuai dengan yang ada di menu Pengaturan Dapodik." -ForegroundColor Yellow
    Write-Host ""
    Read-Host "Tekan Enter untuk keluar..."
    exit 1
}

# 3. Kirim ke Server Database Website
Write-Host "[3/3] Mengirimkan data ke Server Website ($TargetServer)..." -ForegroundColor White
$apiUrl = "$TargetServer/api/dapodik/sync-students"

$payloadObj = @{
    students = $students
    rombels = $rombels
    token = $TokenDapodik
}

# Convert payload ke format JSON UTF-8
$payloadJson = $payloadObj | ConvertTo-Json -Depth 5

$webHeaders = @{
    "Content-Type" = "application/json; charset=utf-8"
    "X-Dapodik-Token" = $TokenDapodik
}

try {
    $syncRes = Invoke-RestMethod -Uri $apiUrl -Method Post -Body $payloadJson -Headers $webHeaders -TimeoutSec 90

    if ($syncRes.success) {
        Write-Host ""
        Write-Host "========================================================================" -ForegroundColor Green
        Write-Host "              🎉 SINKRONISASI KE WEBSITE BERHASIL!                       " -ForegroundColor Green
        Write-Host "========================================================================" -ForegroundColor Green
        Write-Host ""
        Write-Host "Pesan Server        : $($syncRes.message)" -ForegroundColor Cyan
        Write-Host "Total Siswa Diproses: $($syncRes.data.total)" -ForegroundColor Yellow
        Write-Host "Siswa Baru          : $($syncRes.data.created)" -ForegroundColor Green
        Write-Host "Siswa Diperbarui    : $($syncRes.data.updated)" -ForegroundColor White
        Write-Host "Jumlah Rombel/Kelas : $($syncRes.data.classes_synced)" -ForegroundColor White
        Write-Host ""
        Write-Host "Data siswa di website resmi SDN Cinanggerang 1 telah diperbarui." -ForegroundColor Gray
    } else {
        Write-Host ""
        Write-Host "========================================================================" -ForegroundColor Red
        Write-Host "Gagal: $($syncRes.message)" -ForegroundColor Red
    }
} catch {
    Write-Host ""
    Write-Host "========================================================================" -ForegroundColor Red
    Write-Host "ERROR KONEKSI KE SERVER WEB: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Pastikan komputer terhubung dengan internet untuk mengirim ke server." -ForegroundColor Yellow
}

Write-Host "========================================================================" -ForegroundColor Cyan
Write-Host ""
Read-Host "Tekan Enter untuk menutup jendela ini..."
