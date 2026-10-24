// Starts the dewstick store on WordPress Playground at http://127.0.0.1:9400.
// The first run downloads WordPress into ./site (and WooCommerce, via blueprint.json); later runs reuse it,
// so products, orders and settings persist. The theme in ./theme is mounted live — edits show on reload.
import { existsSync, mkdirSync } from 'node:fs';
import { spawn } from 'node:child_process';

mkdirSync('site', { recursive: true });
const installed = existsSync('site/wp-includes/version.php');
const args = [
  'server',
  '--mount-dir-before-install', 'site', '/wordpress',
  '--mount-dir', 'theme', '/wordpress/wp-content/themes/dewstick',
  '--wordpress-install-mode', installed ? 'install-from-existing-files-if-needed' : 'download-and-install',
  '--blueprint', 'blueprint.json',
  '--login',
  '--port', '9400',
];
spawn(process.execPath, ['node_modules/@wp-playground/cli/wp-playground.js', ...args], { stdio: 'inherit' }).on('exit', (code) => process.exit(code ?? 0));
