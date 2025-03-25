import glob from 'glob';
import fs from 'fs';

const mode = process.argv[2]; // "dev" or "prod"

if (!['dev', 'prod'].includes(mode)) {
  console.error('❌ Please specify a valid mode: dev or prod');
  process.exit(1);
}

console.log(`🚀  Switching to ${mode === 'dev' ? 'development' : 'production'} mode...`);

const files = glob.sync("**/plugin-entry.php");

if (files.length === 0) {
	console.log('⚠️  No plugin-entry.php file found.');
	process.exit(0);
}

files.forEach((file) => {
	const data = fs.readFileSync(file, 'utf8');

	const from = mode === 'dev' ? 'PLUGIN_CONST_PRODUCTION' : 'PLUGIN_CONST_DEVELOPMENT';
	const to   = mode === 'dev' ? 'PLUGIN_CONST_DEVELOPMENT' : 'PLUGIN_CONST_PRODUCTION';

	const result = data.replace(new RegExp(from, 'gi'), to);
	fs.writeFileSync(file, result, 'utf8');

	console.log(`✅  Updated ${file}: ${from} → ${to}`);
});