#!/usr/bin/env node
/**
 * apply-glossary.js
 * -----------------
 * Áp dụng từ điển `vi-glossary.json` lên source code:
 *   - `.vue`: text trong <template>, attribute values (trừ Vue binding), string literals trong <script>.
 *   - `.js`:  chỉ string literals (' " `).
 *   - `.php`: chỉ string literals (' ").
 *
 * Không touch:
 *   - Identifier/keyword JS/PHP
 *   - Vue binding (`:attr`, `v-*`, `@event`)
 *   - `{{ ... }}` interpolation
 *   - Comment (// /*  <!-- # để an toàn)
 *   - Thư mục: node_modules, dist, vendor, public/statics/mp_view
 *
 * Usage:
 *   node tools/localization/apply-glossary.js --dry-run
 *   node tools/localization/apply-glossary.js --report tools/localization/out/apply-report.json
 *   node tools/localization/apply-glossary.js --root template/admin/src
 *   node tools/localization/apply-glossary.js --include-comments
 */

const fs = require('fs');
const path = require('path');

const args = process.argv.slice(2);
const DRY_RUN = args.includes('--dry-run');
const INCLUDE_COMMENTS = args.includes('--include-comments');
const rootIdx = args.indexOf('--root');
const reportIdx = args.indexOf('--report');
const ROOTS = rootIdx >= 0
  ? [args[rootIdx + 1]]
  : ['template/admin/src', 'crmeb/app', 'f-chan/src'];
const REPORT_PATH = reportIdx >= 0 ? args[reportIdx + 1] : null;

const glossaryPath = path.join(__dirname, 'vi-glossary.json');
const glossary = JSON.parse(fs.readFileSync(glossaryPath, 'utf8'));
const allEntries = glossary.entries.concat(glossary.menuEntriesExact || []);

// --------------------- Replacer ---------------------

function escapeRegExp(s) { return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }

// Sort longest-first để cụm dài match trước.
const sortedEntries = allEntries.slice().sort((a, b) => b.bad.length - a.bad.length);
const lookup = Object.create(null);
for (const e of sortedEntries) if (!(e.bad in lookup)) lookup[e.bad] = e.good;
const masterRe = new RegExp(sortedEntries.map(e => escapeRegExp(e.bad)).join('|'), 'g');

// Thống kê toàn cục các lần thay.
const stats = { perEntry: Object.create(null), perFile: [] };

function replaceText(text, context) {
  if (!text) return text;
  let count = 0;
  const out = text.replace(masterRe, match => {
    count++;
    stats.perEntry[match] = (stats.perEntry[match] || 0) + 1;
    return lookup[match];
  });
  if (count > 0 && context) context.count += count;
  return out;
}

// --------------------- Vue parsing ---------------------

function segmentVue(src) {
  // Trả về mảng blocks theo thứ tự: { type: 'outer'|'template'|'script'|'style', open, content, close }.
  // Chỉ cắt block ở top-level (column 0) để tránh nhầm với `<template slot-scope>` lồng bên trong.
  const blocks = [];
  const re = /^<(template|script|style)\b[^>]*>/gmi;
  let lastEnd = 0;
  let m;
  while ((m = re.exec(src))) {
    const tagName = m[1].toLowerCase();
    const openStart = m.index;
    const openEnd = openStart + m[0].length;
    const closeStart = findMatchingClose(src, openEnd, tagName);
    if (closeStart === -1) break;
    if (openStart > lastEnd) {
      blocks.push({ type: 'outer', content: src.slice(lastEnd, openStart) });
    }
    const closeTag = `</${tagName}>`;
    blocks.push({
      type: tagName,
      open: src.slice(openStart, openEnd),
      content: src.slice(openEnd, closeStart),
      close: closeTag,
    });
    lastEnd = closeStart + closeTag.length;
    re.lastIndex = lastEnd;
  }
  if (lastEnd < src.length) blocks.push({ type: 'outer', content: src.slice(lastEnd) });
  return blocks;
}

// Tìm vị trí `</tag>` khớp với `<tag>` mở ở `from`, đếm nesting level.
// Bỏ qua self-closing `<tag ... />` không làm tăng depth.
function findMatchingClose(src, from, tagName) {
  const openRe = new RegExp(`<${tagName}\\b[^>]*?>`, 'gi');
  const closeRe = new RegExp(`</${tagName}\\s*>`, 'gi');
  let depth = 1;
  let i = from;
  while (i < src.length) {
    openRe.lastIndex = i;
    closeRe.lastIndex = i;
    const o = openRe.exec(src);
    const c = closeRe.exec(src);
    if (!c) return -1;
    if (o && o.index < c.index) {
      // Bỏ qua self-closing `<tag .../>`
      if (!/\/\s*>\s*$/.test(o[0])) depth++;
      i = o.index + o[0].length;
    } else {
      depth--;
      if (depth === 0) return c.index;
      i = c.index + c[0].length;
    }
  }
  return -1;
}

// Xử lý text trong <template>: thay trong text-node + attribute value.
// Bỏ qua <!-- ... -->, {{ ... }}, attribute dạng `:x=`/`v-x`/`@x`.
function processTemplate(src, context) {
  let out = '';
  let i = 0;
  const n = src.length;

  while (i < n) {
    // HTML comment
    if (src.startsWith('<!--', i)) {
      const end = src.indexOf('-->', i + 4);
      if (end === -1) { out += src.slice(i); break; }
      const inner = src.slice(i + 4, end);
      out += '<!--' + (INCLUDE_COMMENTS ? replaceText(inner, context) : inner) + '-->';
      i = end + 3;
      continue;
    }
    // Tag
    if (src[i] === '<') {
      const tagEnd = findTagEnd(src, i);
      if (tagEnd === -1) { out += src.slice(i); break; }
      out += processTag(src.slice(i, tagEnd + 1), context);
      i = tagEnd + 1;
      continue;
    }
    // Text node (đến dấu < tiếp theo)
    const nextTag = src.indexOf('<', i);
    const end = nextTag === -1 ? n : nextTag;
    out += processTextNode(src.slice(i, end), context);
    i = end;
  }
  return out;
}

// Tìm vị trí kết thúc của thẻ, có tính đến attribute value có dấu >.
function findTagEnd(src, start) {
  let i = start + 1;
  let quote = null;
  while (i < src.length) {
    const ch = src[i];
    if (quote) {
      if (ch === '\\') { i += 2; continue; }
      if (ch === quote) quote = null;
      i++;
      continue;
    }
    if (ch === '"' || ch === "'") { quote = ch; i++; continue; }
    if (ch === '>') return i;
    i++;
  }
  return -1;
}

function processTag(tagSrc, context) {
  // tagSrc bao gồm cả `<` đầu và `>` cuối. Tìm attribute dạng name="..." hoặc name='...'.
  // - Attribute thường: process as text (with {{ }} interpolation awareness).
  // - Vue binding (`:attr`, `v-bind:attr`, `v-model`, `v-if`, ...) và event (`@event`):
  //   value là biểu thức JS → chỉ thay text trong string literals bên trong.
  return tagSrc.replace(
    /([a-zA-Z_@:][\w\-.:]*)\s*=\s*("([^"]*)"|'([^']*)')/g,
    (full, name, valuePart, dq, sq) => {
      const isDQ = dq !== undefined;
      const val = isDQ ? dq : sq;
      const isJsExpr = name.startsWith(':') || name.startsWith('@') || name.startsWith('v-');
      const newVal = isJsExpr ? processScript(val, context) : processInterpolatedText(val, context);
      if (newVal === val) return full;
      return `${name}=${isDQ ? `"${newVal}"` : `'${newVal}'`}`;
    }
  );
}

function processTextNode(text, context) {
  return processInterpolatedText(text, context);
}

// Thay text; với `{{ ... }}` thì coi phần bên trong là biểu thức JS nên chỉ thay
// trong string literals bên trong, giữ nguyên phần code còn lại.
function processInterpolatedText(text, context) {
  let out = '';
  let i = 0;
  while (i < text.length) {
    const open = text.indexOf('{{', i);
    if (open === -1) {
      out += replaceText(text.slice(i), context);
      break;
    }
    out += replaceText(text.slice(i, open), context);
    const close = text.indexOf('}}', open + 2);
    if (close === -1) { out += text.slice(open); break; }
    const inner = text.slice(open + 2, close);
    out += '{{' + processScript(inner, context) + '}}';
    i = close + 2;
  }
  return out;
}

// --------------------- JS (script) parsing ---------------------

function processScript(src, context) {
  let out = '';
  let i = 0;
  const n = src.length;
  let state = 'code'; // code | sq | dq | bt | sline | mline | regex
  let buf = '';
  let commentBuf = '';
  let lastCodeChar = '\n'; // để heuristic regex vs chia

  const flushBuf = () => { if (buf) { out += replaceText(buf, context); buf = ''; } };
  const flushComment = () => {
    if (!commentBuf) return;
    out += INCLUDE_COMMENTS ? replaceText(commentBuf, context) : commentBuf;
    commentBuf = '';
  };

  while (i < n) {
    const ch = src[i];
    const next = src[i + 1];

    if (state === 'code') {
      if (ch === '/' && next === '/') { out += '//'; i += 2; state = 'sline'; commentBuf = ''; continue; }
      if (ch === '/' && next === '*') { out += '/*'; i += 2; state = 'mline'; commentBuf = ''; continue; }
      if (ch === '"')  { out += '"'; state = 'dq'; buf = ''; i++; continue; }
      if (ch === "'")  { out += "'"; state = 'sq'; buf = ''; i++; continue; }
      if (ch === '`')  { out += '`'; state = 'bt'; buf = ''; i++; continue; }
      out += ch;
      if (!/\s/.test(ch)) lastCodeChar = ch;
      i++;
      continue;
    }
    if (state === 'sline') {
      if (ch === '\n') {
        flushComment();
        out += ch;
        state = 'code';
      } else {
        commentBuf += ch;
      }
      i++;
      continue;
    }
    if (state === 'mline') {
      if (ch === '*' && next === '/') {
        flushComment();
        out += '*/';
        i += 2;
        state = 'code';
      } else {
        commentBuf += ch;
      }
      i++;
      continue;
    }
    if (state === 'sq' || state === 'dq') {
      const quote = state === 'sq' ? "'" : '"';
      if (ch === '\\') { buf += ch + (next || ''); i += 2; continue; }
      if (ch === quote) {
        flushBuf();
        out += quote;
        state = 'code';
        lastCodeChar = quote;
        i++;
        continue;
      }
      buf += ch;
      i++;
      continue;
    }
    if (state === 'bt') {
      if (ch === '\\') { buf += ch + (next || ''); i += 2; continue; }
      if (ch === '$' && next === '{') {
        // ${ ... } bên trong template literal: flush buf hiện tại, sau đó parse như code tới `}`.
        flushBuf();
        out += '${';
        i += 2;
        // Đọc biểu thức code cho tới `}` tương ứng (đơn giản: cân đối `{}`)
        let depth = 1;
        while (i < n && depth > 0) {
          const c = src[i];
          out += c;
          if (c === '{') depth++;
          else if (c === '}') depth--;
          i++;
        }
        continue;
      }
      if (ch === '`') {
        flushBuf();
        out += '`';
        state = 'code';
        i++;
        continue;
      }
      buf += ch;
      i++;
      continue;
    }
  }
  flushBuf();
  return out;
}

// --------------------- JSON parsing ---------------------

// JSON: chỉ thay trong string literals (giá trị chuỗi). Stateful parser đơn giản.
function processJson(src, context) {
  let out = '';
  let i = 0;
  const n = src.length;
  let state = 'code'; // code | dq
  let buf = '';

  const flushBuf = () => { if (buf) { out += replaceText(buf, context); buf = ''; } };

  while (i < n) {
    const ch = src[i];
    const next = src[i + 1];
    if (state === 'code') {
      if (ch === '"') { out += '"'; state = 'dq'; buf = ''; i++; continue; }
      out += ch; i++; continue;
    }
    if (state === 'dq') {
      if (ch === '\\') { buf += ch + (next || ''); i += 2; continue; }
      if (ch === '"') { flushBuf(); out += '"'; state = 'code'; i++; continue; }
      buf += ch; i++; continue;
    }
  }
  flushBuf();
  return out;
}

// --------------------- PHP parsing ---------------------

function processPhp(src, context) {
  // Tương tự JS, nhưng có thêm comment dạng # và nowdoc/heredoc ta bỏ qua cho đơn giản.
  let out = '';
  let i = 0;
  const n = src.length;
  let state = 'code';
  let buf = '';
  let commentBuf = '';

  const flushBuf = () => { if (buf) { out += replaceText(buf, context); buf = ''; } };
  const flushComment = () => {
    if (!commentBuf) return;
    out += INCLUDE_COMMENTS ? replaceText(commentBuf, context) : commentBuf;
    commentBuf = '';
  };

  while (i < n) {
    const ch = src[i];
    const next = src[i + 1];

    if (state === 'code') {
      if (ch === '/' && next === '/') { out += '//'; i += 2; state = 'sline'; commentBuf = ''; continue; }
      if (ch === '#')                  { out += '#';  i += 1; state = 'sline'; commentBuf = ''; continue; }
      if (ch === '/' && next === '*')  { out += '/*'; i += 2; state = 'mline'; commentBuf = ''; continue; }
      if (ch === '"') { out += '"'; state = 'dq'; buf = ''; i++; continue; }
      if (ch === "'") { out += "'"; state = 'sq'; buf = ''; i++; continue; }
      out += ch; i++; continue;
    }
    if (state === 'sline') {
      if (ch === '\n') {
        flushComment();
        out += ch;
        state = 'code';
      } else {
        commentBuf += ch;
      }
      i++;
      continue;
    }
    if (state === 'mline') {
      if (ch === '*' && next === '/') {
        flushComment();
        out += '*/';
        i += 2;
        state = 'code';
      } else {
        commentBuf += ch;
      }
      i++;
      continue;
    }
    if (state === 'sq' || state === 'dq') {
      const quote = state === 'sq' ? "'" : '"';
      if (ch === '\\') { buf += ch + (next || ''); i += 2; continue; }
      if (ch === quote) {
        flushBuf();
        out += quote;
        state = 'code';
        i++;
        continue;
      }
      buf += ch;
      i++;
      continue;
    }
  }
  flushBuf();
  return out;
}

// --------------------- File handling ---------------------

function isSkipDir(name) {
  return ['node_modules', 'dist', 'vendor', 'mp_view', '.git', '.cursor', '.idea', 'out'].includes(name);
}

function walk(dir, out = []) {
  if (!fs.existsSync(dir)) return out;
  const st = fs.statSync(dir);
  if (st.isFile()) {
    const ext = path.extname(dir).toLowerCase();
    if (['.vue', '.js', '.php', '.ts', '.tsx'].includes(ext)) out.push(dir);
    return out;
  }
  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    if (entry.isDirectory()) {
      if (isSkipDir(entry.name)) continue;
      walk(path.join(dir, entry.name), out);
    } else if (entry.isFile()) {
      const ext = path.extname(entry.name).toLowerCase();
      if (['.vue', '.js', '.php', '.ts', '.tsx'].includes(ext)) {
        out.push(path.join(dir, entry.name));
      } else if (ext === '.json') {
        // Chỉ quét JSON config có chứa text UI (pages.json của UniApp).
        const base = path.basename(entry.name);
        if (['pages.json', 'manifest.json'].includes(base)) out.push(path.join(dir, entry.name));
      }
    }
  }
  return out;
}

function processFile(file) {
  const src = fs.readFileSync(file, 'utf8');
  const ctx = { count: 0 };
  let out;
  const ext = path.extname(file).toLowerCase();

  if (ext === '.vue') {
    const blocks = segmentVue(src);
    out = '';
    for (const b of blocks) {
      if (b.type === 'template') {
        out += b.open + processTemplate(b.content, ctx) + b.close;
      } else if (b.type === 'script') {
        out += b.open + processScript(b.content, ctx) + b.close;
      } else if (b.type === 'style') {
        out += b.open + b.content + b.close;
      } else {
        out += b.content;
      }
    }
  } else if (ext === '.js' || ext === '.ts' || ext === '.tsx') {
    out = processScript(src, ctx);
  } else if (ext === '.php') {
    out = processPhp(src, ctx);
  } else if (ext === '.json') {
    out = processJson(src, ctx);
  } else {
    return { changed: false, count: 0 };
  }

  if (out === src) return { changed: false, count: 0 };
  if (!DRY_RUN) fs.writeFileSync(file, out, 'utf8');
  return { changed: true, count: ctx.count };
}

function main() {
  const files = [];
  for (const root of ROOTS) walk(path.resolve(root), files);

  let changed = 0, total = 0;
  for (const f of files) {
    const r = processFile(f);
    if (r.changed) {
      changed++;
      total += r.count;
      stats.perFile.push({ file: path.relative(process.cwd(), f), count: r.count });
    }
  }

  console.log(`Scanned: ${files.length} files (${ROOTS.join(', ')})`);
  console.log(`${DRY_RUN ? 'Would change' : 'Changed'}: ${changed} files`);
  console.log(`${DRY_RUN ? 'Would replace' : 'Replaced'}: ${total} occurrences across ${Object.keys(stats.perEntry).length} unique terms`);

  // Top terms
  const sortedTerms = Object.entries(stats.perEntry).sort((a, b) => b[1] - a[1]).slice(0, 15);
  if (sortedTerms.length) {
    console.log('Top terms:');
    for (const [term, cnt] of sortedTerms) console.log(`  ${cnt.toString().padStart(5)}  ${term}`);
  }

  if (REPORT_PATH) {
    const reportDir = path.dirname(REPORT_PATH);
    if (!fs.existsSync(reportDir)) fs.mkdirSync(reportDir, { recursive: true });
    fs.writeFileSync(REPORT_PATH, JSON.stringify({
      dryRun: DRY_RUN,
      roots: ROOTS,
      summary: { scanned: files.length, changedFiles: changed, totalReplacements: total },
      stats,
    }, null, 2), 'utf8');
    console.log(`Report written to ${REPORT_PATH}`);
  }
}

main();
