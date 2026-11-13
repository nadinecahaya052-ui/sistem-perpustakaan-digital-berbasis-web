@echo off
title SMA NEGERI 1 KAMAL - Start XAMPP
echo Memulai Apache dan MySQL XAMPP...
if exist "C:\xampp\xampp-control.exe" (
  start "" "C:\xampp\xampp-control.exe"
  echo XAMPP Control Panel dibuka. Silakan klik Start pada Apache dan MySQL.
) else (
  echo XAMPP tidak ditemukan di C:\xampp.
  echo Install XAMPP atau ubah lokasi sesuai komputer Anda.
)
pause
