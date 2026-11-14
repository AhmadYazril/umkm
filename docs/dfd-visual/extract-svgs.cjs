const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const chromePath = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const htmlUrl = 'file:///d:/laragon/www/umkm/docs/dfd-visual/generate-svgs.html';
const svgDir = path.join(__dirname, 'svg');

console.log('Running Chrome headless to render all 30 SVGs...');
const cmd = `"${chromePath}" --headless=new --disable-gpu --virtual-time-budget=30000 --dump-dom "${htmlUrl}"`;

const dom = execSync(cmd, { maxBuffer: 50 * 1024 * 1024, encoding: 'utf-8' });

// Check status
const statusMatch = dom.match(/<div id="status">([^<]+)<\/div>/);
console.log('Generator Status:', statusMatch ? statusMatch[1] : 'Unknown');

// Regex to find each diagram-svg-entry
const regex = /<div class="diagram-svg-entry" data-id="([^"]+)">([\s\S]*?<\/svg>)<\/div>/g;
let match;
let count = 0;

while ((match = regex.exec(dom)) !== null) {
  const id = match[1];
  const svgContent = match[2];
  const filePath = path.join(svgDir, `${id}.svg`);
  fs.writeFileSync(filePath, svgContent, 'utf-8');
  console.log(`[${++count}/30] Saved: svg/${id}.svg (${svgContent.length} bytes)`);
}

// Check for errors
const errRegex = /<div class="diagram-svg-error" data-id="([^"]+)">([^<]+)<\/div>/g;
let errMatch;
let errCount = 0;
while ((errMatch = errRegex.exec(dom)) !== null) {
  console.error(`ERROR in ${errMatch[1]}: ${errMatch[2]}`);
  errCount++;
}

console.log(`\nExtraction complete: ${count} SVGs saved, ${errCount} errors.`);
