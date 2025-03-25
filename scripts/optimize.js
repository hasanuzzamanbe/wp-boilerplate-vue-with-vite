import { execSync } from 'child_process';
import os from 'os';

const isWin = os.platform() === 'win32';
const run = (cmd) => execSync(cmd, {
	stdio: 'inherit',
	shell: isWin ? 'cmd.exe' : 'bash'
});

console.log('🧹  Cleaning development files...');

try {
	console.log('📦  Installing production PHP dependencies...');
	run('composer install --no-dev --optimize-autoloader --classmap-authoritative');
	console.log('✅  Composer dependencies installed');
} catch (err) {
	console.error('❌  Composer install failed:', err);
}