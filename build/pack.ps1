.".\common.ps1"

Write-Host $packagePaths

New-Item -Path $tempPackagesFolder -ItemType "Directory" -Force

if (Test-Path $releaseFolder -PathType Container) {
    Remove-Item $releaseFolder\* -Recurse -Force
}
else {
    New-Item -Path $releaseFolder -ItemType "Directory" -Force
}

# Write-Host $rootFolder

foreach ($packagePath in $packagePaths) {

    $packageAbsPath = (Join-Path $rootFolder $packagePath)
    $packageName = Split-Path $packageAbsPath -Leaf

    Write-Host "Packaging " $packageName

    $compress = @{
        Path = Join-Path $packageAbsPath "/*"
        CompressionLevel = "Fastest"
        DestinationPath = Join-Path $tempPackagesFolder $packageName
    }

    Compress-Archive @compress
}

# Copy package manifest
Copy-Item (Join-Path $packageManifestDir "/*.xml") -Destination $tempDir

$compress = @{
    Path = Join-Path $tempDir "/*"
    CompressionLevel = "Fastest"
    DestinationPath = Join-Path $releaseFolder "pkg_ninox.zip"
}

Compress-Archive @compress

Remove-Item $tempDir -Recurse -Force

Set-Location $rootFolder