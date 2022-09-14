import AdmZip from "adm-zip";
import fs from 'fs';

fs.renameSync('./src', './block_myspecialcourse');

const zip = new AdmZip();
zip.addLocalFolder('./block_myspecialcourse');
zip.writeZip('./dist/block_myspecialcourse.zip');

fs.renameSync('./block_myspecialcourse', './src');