import glob from 'glob';
import fs from 'fs';

const files = glob.sync("**/plugin-entry.php");

if (files.length === 0) {
	console.log('⚠️  Nessun file plugin-entry.php trovato.');
}

files.forEach((item) => {
	const data = fs.readFileSync(item, 'utf8');
	const result = data.replace(/PLUGIN_CONST_DEVELOPMENT/gi, 'PLUGIN_CONST_PRODUCTION');
	fs.writeFileSync(item, result, 'utf8');
	console.log('🚀  Production mode activated!');
});
