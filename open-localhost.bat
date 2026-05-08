@echo off
setlocal EnableExtensions EnableDelayedExpansion
chcp 65001 >nul

cd /d "%~dp0"

set "PHP_EXE=E:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe"
set "HOST=127.0.0.1"
set "PORT=8000"
set "MAX_PORT=8010"
set "DOCROOT=public"
set "ROUTER=public\router.php"
set "PORT_FOUND=0"

if not exist "%PHP_EXE%" (
  echo [ERROR] Could not find PHP:
  echo %PHP_EXE%
  echo.
  echo Please update PHP_EXE in this file to the correct path.
  pause
  exit /b 1
)

for /L %%P in (%PORT%,1,%MAX_PORT%) do (
  set "CURRENT_PORT=%%P"
  for /f "tokens=5" %%Q in ('netstat -ano ^| findstr ":%%P" ^| findstr "LISTENING"') do (
    set "PORT_BUSY=1"
  )

  if not defined PORT_BUSY (
    set "PORT=%%P"
    set "PORT_FOUND=1"
    goto :port_ready
  )

  set "PORT_BUSY="
)

:port_ready

if "%PORT_FOUND%"=="0" (
  echo [ERROR] No free port found between %PORT% and %MAX_PORT%.
  pause
  exit /b 1
)

echo Starting local server at http://%HOST%:%PORT%
echo Press Ctrl+C to stop this window, or use stop-localhost.bat.
echo.
start "" "http://%HOST%:%PORT%"
"%PHP_EXE%" -S %HOST%:%PORT% -t %DOCROOT% %ROUTER%

if errorlevel 1 (
  echo.
  echo [ERROR] Could not start the local server. Check port %PORT% and the PHP path.
  pause
)

endlocal
