@echo off
title Temp + Prefetch Cleaner

echo Cleaning TEMP files...

del /f /s /q "%temp%\*" >nul 2>&1
for /d %%x in ("%temp%\*") do rd /s /q "%%x" >nul 2>&1

echo Cleaning Prefetch files...

del /f /s /q "C:\Windows\Prefetch\*" >nul 2>&1
for /d %%x in ("C:\Windows\Prefetch\*") do rd /s /q "%%x" >nul 2>&1

echo.
echo Done cleaning TEMP and Prefetch.
pause