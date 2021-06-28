# Configure directories
$rootFolder = (Get-Item -Path "./" -Verbose).FullName
$tempDir = Join-Path $rootFolder "temp"
$tempPackagesFolder = Join-Path $tempDir "packages"
$releaseFolder = Join-Path $rootFolder "release"

# Configure package paths
$packageManifestDir = "../src/"
$packagePaths = @(
    "../src/packages/com_ninox",
    "../src/packages/ninox_user",
    "../src/packages/ninox_vmshopper"
)