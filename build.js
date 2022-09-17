import AdmZip from "adm-zip";
import fs from 'fs';
import fsExtra from 'fs-extra';

const outDir = './dist/bundle';
const pluginName = 'block_myspecialcourse';

const run = async () => {
	if (fs.existsSync(outDir)) fs.rmSync(outDir, {force: true, recursive: true});
	fs.mkdirSync(outDir)
	await fsExtra.copy('./src', `${outDir}/${pluginName}`, {overwrite: true});

	const zip = new AdmZip();
	zip.addLocalFolder(outDir);
	zip.writeZip('./dist/block_myspecialcourse.zip');

	fs.rmSync(outDir, {force: true, recursive: true});
};

run();
