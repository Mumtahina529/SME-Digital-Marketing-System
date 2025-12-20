# PowerShell script to convert PHP files to HTML
$ErrorActionPreference = "Stop"

# Define source and destination paths
$sourceDir = "c:\xampp7\htdocs\New folder"
$destDir = "c:\xampp7\htdocs\New folder\static-version"

# Files to convert (PHP to HTML)
$files = @(
    @{Source="about.php"; Dest="about.html"},
    @{Source="services.php"; Dest="services.html"},
    @{Source="contact.php"; Dest="contact.html"},
    @{Source="login.php"; Dest="login.html"},
    @{Source="register.php"; Dest="register.html"}
)

Write-Host "Starting PHP to HTML conversion..." -ForegroundColor Green

foreach ($file in $files) {
    $sourcePath = Join-Path $sourceDir $file.Source
    $destPath = Join-Path $destDir $file.Dest
    
    Write-Host "Converting $($file.Source) to $($file.Dest)..." -ForegroundColor Yellow
    
    # Read the PHP file
    $content = Get-Content $sourcePath -Raw -Encoding UTF8
    
    # Replace PHP includes with div containers
    $content = $content -replace '<\?php include ''components/navbar\.php''; \?>', '<div id="navbar"></div>'
    $content = $content -replace '<\?php include ''components/footer\.php''; \?>', '<div id="footer"></div>'
    
    # Replace .php links with .html
    $content = $content -replace '\.php"', '.html"'
    $content = $content -replace '\.php''', '.html'''
    $content = $content -replace '\.php#', '.html#'
    
    # Add components.js script before closing body tag if not already present
    if ($content -notmatch 'components\.js') {
        $content = $content -replace '</body>', "    <script src=`"js/components.js`"></script>`n</body>"
    }
    
    # Write to destination
    $content | Out-File -FilePath $destPath -Encoding UTF8
    
    Write-Host "✓ Converted $($file.Dest)" -ForegroundColor Green
}

Write-Host "`nAll files converted successfully!" -ForegroundColor Green
Write-Host "`nGenerated files:" -ForegroundColor Cyan
Write-Host "  - index.html (already created)" -ForegroundColor White
foreach ($file in $files) {
    Write-Host "  - $($file.Dest)" -ForegroundColor White
}

Write-Host "`nYour static website is ready for deployment!" -ForegroundColor Green
Write-Host "Location: $destDir" -ForegroundColor Yellow
