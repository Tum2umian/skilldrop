@echo off
echo Running code analysis...
php run_analysis.php
if %ERRORLEVEL% NEQ 0 (
    echo Error: Analysis failed. Check the output above for details.
    exit /b %ERRORLEVEL%
)
echo Done! Check the HTML report in the 'reports' directory.
exit /b 0