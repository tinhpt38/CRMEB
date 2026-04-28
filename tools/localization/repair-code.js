#!/usr/bin/env node
/**
 * repair-code.js
 * --------------
 * Vá các JS keyword bị viết hoa do lần auto-capitalize cũ ăn nhầm vào khối <script>
 * của các file .vue (ví dụ: `Import ... from ...`, `Export default`, `Let x = ...`).
 *
 * Chiến lược an toàn:
 *  - Chỉ thao tác trong đoạn nằm giữa cặp thẻ <script ...> và </script>.
 *  - Stateful parser: bỏ qua string literals ('...', "...", `...`) và comments (// ..., /* ... *\/).
 *  - Chỉ thay keyword khi đứng ở vị trí của một statement (sau boundary: \n, {, ;, ,, ()
 *    và ký tự trước không phải identifier character.
 *
 * Usage:
 *   node tools/localization/repair-code.js                  # apply
 *   node tools/localization/repair-code.js --dry-run        # preview
 *   node tools/localization/repair-code.js --path <folder>  # custom root
 */

const fs = require('fs');
const path = require('path');
const lib = require('./repair-code-lib.js');

const args = process.argv.slice(2);
const DRY_RUN = args.includes('--dry-run');
const pathArgIdx = args.indexOf('--path');
const ROOTS = pathArgIdx >= 0
  ? [args[pathArgIdx + 1]]
  : ['template/admin/src', 'template/uni-app'];

function processFile(file) {
  const src = fs.readFileSync(file, 'utf8');
  const blocks = lib.findScriptBlocks(src);
  if (blocks.length === 0) return { changed: false, replacements: 0 };

  let out = '';
  let cursor = 0;
  let totalReplacements = 0;

  for (const block of blocks) {
    out += src.slice(cursor, block.start);
    const before = block.content;
    const after = lib.fixScriptBlock(before);
    if (after !== before) {
      totalReplacements += countReplacedLines(before, after);
    }
    out += after;
    cursor = block.end;
  }
  out += src.slice(cursor);

  if (out === src) return { changed: false, replacements: 0 };
  if (!DRY_RUN) fs.writeFileSync(file, out, 'utf8');
  return { changed: true, replacements: totalReplacements };
}

function countReplacedLines(a, b) {
  const la = a.split('\n');
  const lb = b.split('\n');
  let c = 0;
  const len = Math.min(la.length, lb.length);
  for (let i = 0; i < len; i++) if (la[i] !== lb[i]) c++;
  return c;
}

function walk(dir, out = []) {
  if (!fs.existsSync(dir)) return out;
  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    const full = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      if (['node_modules', 'dist', 'vendor', 'mp_view', '.git', '.cursor', '.idea'].includes(entry.name)) continue;
      walk(full, out);
    } else if (entry.isFile() && entry.name.endsWith('.vue')) {
      out.push(full);
    }
  }
  return out;
}

function main() {
  const files = [];
  for (const root of ROOTS) {
    const abs = path.resolve(root);
    walk(abs, files);
  }

  let changed = 0;
  let totalRepl = 0;
  const changedFiles = [];
  for (const f of files) {
    const r = processFile(f);
    if (r.changed) {
      changed++;
      totalRepl += r.replacements;
      changedFiles.push({ file: path.relative(process.cwd(), f), count: r.replacements });
    }
  }

  console.log(`Scanned: ${files.length} .vue files`);
  console.log(`${DRY_RUN ? 'Would change' : 'Changed'}: ${changed} files`);
  console.log(`${DRY_RUN ? 'Would touch' : 'Touched'}: ${totalRepl} lines inside <script>`);
  if (changedFiles.length && changedFiles.length <= 30) {
    for (const c of changedFiles) console.log(`  ${c.count.toString().padStart(3)}  ${c.file}`);
  }
}

main();
