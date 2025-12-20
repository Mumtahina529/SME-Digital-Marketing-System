# Auto-convert PHP to HTML
$files = @("about.php", "services.php", "contact.php", "login.php", "register.php")

foreach ($file in $files) {
    $htmlFile = $file -replace '\.php$', '.html'
    Write-Host "Converting $file to $htmlFile..." -ForegroundColor Yellow
    
    $content = Get-Content $file -Raw -Encoding UTF8
    
    # Replace PHP includes
    $content = $content -replace '\<\?php include ''components/navbar\.php''; \?\>', '<div id="navbar"></div>'
    $content = $content -replace '\<\?php include ''components/footer\.php''; \?\>', '<div id="footer"></div>'
    
    # Replace .php with .html in links
    $content = $content -replace '="([^"]*?)\.php"', '="$1.html"'
    $content = $content -replace "='([^']*?)\.php'", "='$1.html'"
    $content = $content -replace '\.php\#', '.html#'
    
    # Add components.js before </body>
    if ($content -notmatch 'components\.js') {
        $content = $content -replace '\</body\>', '    <script src="js/components.js"></script>' + "`n" + '</body>'
    }
    
    # Save
    $content | Out-File "static-version\$htmlFile" -Encoding UTF8 -NoNewline
    Write-Host "✓ Created $htmlFile" -ForegroundColor Green
}

Write-Host "`nDone! All HTML files created successfully!" -ForegroundColor Green
