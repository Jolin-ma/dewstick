# Opens the local dewstick WordPress store: starts it if it isn't running, then opens the browser.
# The desktop shortcut runs this. Closing the minimized "dewstick WordPress" window stops the server.
$ErrorActionPreference = 'Stop'
$here = $PSScriptRoot
$url = 'http://127.0.0.1:9400/'

# Playground answers 502 while WordPress is still booting, so only a real page counts as up.
function Test-Up {
	try { $null = Invoke-WebRequest "${url}wp-login.php" -UseBasicParsing -TimeoutSec 2; $true }
	catch { $r = $_.Exception.Response; [bool]($r -and [int]$r.StatusCode -lt 500) }
}

if (-not (Test-Up)) {
	if (-not (Test-Path "$here\node_modules")) {
		Write-Host 'Installing WordPress Playground (first run only)...'
		& npm.cmd install --prefix $here
	}
	Write-Host 'Starting WordPress - the first start takes a minute...'
	Start-Process cmd.exe -ArgumentList '/k', 'title dewstick WordPress - close this window to stop && npm.cmd start' -WorkingDirectory $here -WindowStyle Minimized
	$tries = 0
	while (-not (Test-Up)) {
		if (++$tries -gt 300) { Write-Host 'WordPress did not start - check the "dewstick WordPress" window.'; Read-Host 'Press Enter to close'; exit 1 }
		Start-Sleep -Seconds 1
	}
}

Start-Process $url
