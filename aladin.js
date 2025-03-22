import readline from 'readline';
import { promisify } from 'util';
import { readFile, writeFile, unlink, access } from 'fs/promises';
import { createInterface } from 'readline';
import { glob as globCallback } from 'glob';
import { fileURLToPath } from 'url';
import path from 'path';

// Convert callback-based glob to promise-based
import { glob } from 'glob';

// Get current directory for ES Modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Create readline interface
const rl = createInterface({
    input: process.stdin,
    output: process.stdout
});

// Utility Functions
const convertToSlug = (text) => text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
const convertToLowercase = (text) => text.toLowerCase().replace(/ /g, '').replace(/[^\w-]+/g, '');
const convertToUppercase = (text) => text.toUpperCase().replace(/ /g, '').replace(/[^\w-]+/g, '');
const camalize = (str) => str.toLowerCase().replace(/[^a-zA-Z0-9]+(.)/g, (match, chr) => chr.toUpperCase());

async function processFiles(answer) {
    const Uppercase = convertToUppercase(answer);
    const Lowercase = convertToLowercase(answer);
    const Slug = convertToSlug(answer);
    const Camel = camalize(answer);

    const replacements = {
        PluginClassName: Camel,
        pluginlowercase: Lowercase,
        PLUGIN_CONST: Uppercase,
        PluginName: answer,
        pluginslug: Slug,
        YourPlugin: answer
    };

    try {
        console.log('🚀 Processing files...');
        const files = await glob("!(node_modules)/**/*.*");
        console.log("Found files:", files);

        if (files.length === 0) {
            console.log("⚠️ No files found. Exiting...");
            return;
        }
        console.log(glob, files, 'files')
        for (const file of files) {
            const data = await readFile(file, 'utf8');
            const updatedContent = data.replace(/YourPlugin|PluginClassName|pluginlowercase|pluginslug|PLUGIN_CONST|PluginName/gi, (match) => replacements[match]);
            await writeFile(file, updatedContent, 'utf8');
            console.log(`✅ File (${file}) updated successfully.`);
        }

        console.log(`
    _______ _______ _______ ________        _______________________ ______  
   (  ____ (  ___  (       (  ____ ( \\      (  ____  \\__   __(  ____ (  __  ) 
   | (    \\ | (   ) | () () | (    )| (     | (     \\/  ) (  | (     \\| (  )  )
   | |     | |   | | || || | (____)| |     | (__      | |  | (__   | |   ) |
   | |     | |   | | |(_)| |  _____| |     |  __)     | |  |  __)  | |   | |
   | |     | |   | | |   | | (     | |     | (        | |  | (     | |   ) |
   | (____/| (___) | )   ( | )     | (____/| (____/\\   | |  | (____/| (__/  )
   (_______(_______|/     (|/      (_______(_______/  )_(  (_______(______/ 
                                                                                                            
          🎉 All Files Processed Successfully!
          Now run "npm run watch" and activate your plugin.
          Thanks from https://www.hasanuzzaman.com
    `);
    } catch (error) {
        console.error("❌ Error processing files:", error);
    }
}

async function removeUnusedFile(filePath) {
    try {
        await access(filePath); // Check if file exists
        await unlink(filePath);
        console.log('✅ Unused file removed:', filePath);
    } catch {
        console.log('✅ No unused file found:', filePath);
    }
}

async function main() {
    rl.question("Please enter your plugin Name: ", async (answer) => {
        if (answer.includes("-")) {
            console.log(`⚠️ Warning: Please don't use hyphens. You may use "${answer.replace(/-/g, ' ')}" as your plugin name.`);
            console.log('⚠️ Please run again "node aladin" and enter a unique plugin name.');
            rl.close();
            return;
        }

        answer = answer.trim();
        console.log(answer, 'ans');
        await processFiles(answer);
        await removeUnusedFile('_config.yml');
        rl.close();
    });
}

main();
