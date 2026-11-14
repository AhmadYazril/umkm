const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const chromePath = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const pngDir = path.join(__dirname, 'png');

// Load DIAGRAMS array
const diagramsFile = path.join(__dirname, 'diagrams.js');
const fileContent = fs.readFileSync(diagramsFile, 'utf-8');
const fn = new Function('globalThis', fileContent);
const fakeGlobal = {};
fn(fakeGlobal);
const DIAGRAMS = fakeGlobal.DIAGRAMS;

console.log(`Starting generation of ${DIAGRAMS.length} high-resolution PNGs...`);

let successCount = 0;
for (let i = 0; i < DIAGRAMS.length; i++) {
  const d = DIAGRAMS[i];
  const pngPath = path.join(pngDir, `${d.id}.png`);
  const viewerUrl = `file:///d:/laragon/www/umkm/docs/dfd-visual/export-viewer.html#${d.id}`;

  const cmd = `"${chromePath}" --headless=new --disable-gpu --force-device-scale-factor=3 --hide-scrollbars --virtual-time-budget=2000 --window-size=2400,1600 --screenshot="${pngPath}" "${viewerUrl}"`;

  try {
    execSync(cmd, { stdio: 'pipe' });
    const stat = fs.statSync(pngPath);
    console.log(`[${i + 1}/${DIAGRAMS.length}] Generated: png/${d.id}.png (${Math.round(stat.size / 1024)} KB)`);
    successCount++;
  } catch (err) {
    console.error(`ERROR generating ${d.id}:`, err.message);
  }
}

console.log(`\nPNG generation complete: ${successCount}/${DIAGRAMS.length} files created.`);
