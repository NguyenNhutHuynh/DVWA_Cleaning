@echo off
setlocal

cd /d "%~dp0"

set "PHP_EXE=E:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe"
set "HOST=127.0.0.1"
set "PORT=8000"
set "DOCROOT=public"
set "PORT_BUSY=0"

if not exist "%PHP_EXE%" (
  echo [ERROR] Khong tim thay PHP:
  echo %PHP_EXE%
  echo.
  echo Hay sua bien PHP_EXE trong file nay cho dung duong dan.
  pause
  exit /b 1
)

for /f "tokens=5" %%P in ('netstat -ano ^| findstr ":%PORT%" ^| findstr "LISTENING"') do (
  set "PORT_BUSY=1"
)

if "%PORT_BUSY%"=="1" (
  echo Cong %PORT% dang duoc su dung. Mo lai trinh duyet vao server hien co...
  start "" "http://%HOST%:%PORT%"
  exit /b 0
)

echo Dang chay local server tai http://%HOST%:%PORT%
echo Nhan Ctrl+C de dung server trong cua so nay, hoac dung file stop-localhost.bat.
echo.
start "" "http://%HOST%:%PORT%"
"%PHP_EXE%" -S %HOST%:%PORT% -t %DOCROOT%

if errorlevel 1 (
  echo.
  echo [ERROR] Khong the khoi dong local server. Kiem tra lai cong %PORT% va duong dan PHP.
  pause
)

endlocal
