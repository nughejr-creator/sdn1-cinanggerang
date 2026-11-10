@echo off
title Sinkronisasi Dapodik ke Website SDN Cinanggerang 1
color 0B
echo ========================================================================
echo       MEMULAI SINKRONISASI DAPODIK KE WEBSITE SDN CINANGGERANG 1
echo ========================================================================
echo.
echo Menjalankan proses penarikan data Web Service Dapodik...
echo.

powershell -NoProfile -ExecutionPolicy Bypass -Command "& { [Console]::OutputEncoding = [System.Text.Encoding]::UTF8; Get-Content -LiteralPath '%~dp0sinkron_dapodik.ps1' -Raw | Invoke-Expression }"

pause
