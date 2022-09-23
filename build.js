import AdmZip from "adm-zip";
import fs from 'fs';
import fsExtra from 'fs-extra';

const outDir = './dist';
const pluginNames = [
	'block_academyresources',
	'block_calendar_upcoming_img',
	'block_myspecialcourse',
	'mod_vimeo',
]

const run = () => {
	pluginNames.forEach(async (pluginName) => {
		const tmpDir = `${outDir}/${pluginName}`;
		const srcDir = `${tmpDir}/${pluginName}`;
		const outFilename = `${outDir}/${pluginName}.zip`;
		if (fs.existsSync(outFilename)) fs.rmSync(outFilename, {force: true});
		if (fs.existsSync(tmpDir)) fs.rmSync(tmpDir, {force: true, recursive: true});
		await fsExtra.copy(`./src/${pluginName}`, srcDir, {overwrite: true});
		const zip = new AdmZip();
		zip.addLocalFolder(tmpDir);
		zip.writeZip(outFilename);
		fs.rmSync(tmpDir, {force: true, recursive: true});
	})
}

run();
