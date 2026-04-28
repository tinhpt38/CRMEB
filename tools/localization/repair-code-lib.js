/**
 * repair-code-lib.js - shared helpers for repair-code.js
 */

const KEYWORDS = [
  'Import', 'Export', 'Const', 'Let', 'Var', 'Function',
  'Return', 'New', 'Async', 'Await', 'Class',
];

function findScriptBlocks(src) {
  const blocks = [];
  const openRe = /<script\b[^>]*>/gi;
  let m;
  while ((m = openRe.exec(src))) {
    const contentStart = m.index + m[0].length;
    const closeIdx = src.indexOf('</script>', contentStart);
    if (closeIdx === -1) break;
    blocks.push({ start: contentStart, end: closeIdx, content: src.slice(contentStart, closeIdx) });
    openRe.lastIndex = closeIdx + '</script>'.length;
  }
  return blocks;
}

function fixScriptBlock(script) {
  let out = '';
  let i = 0;
  const n = script.length;

  let state = 'code';
  let boundary = true;

  while (i < n) {
    const ch = script[i];
    const next = script[i + 1];

    if (state === 'code') {
      if (ch === '/' && next === '/') { state = 'sline'; out += '//'; i += 2; continue; }
      if (ch === '/' && next === '*') { state = 'mline'; out += '/*'; i += 2; continue; }
      if (ch === '"')  { state = 'dq'; out += ch; i++; boundary = false; continue; }
      if (ch === "'")  { state = 'sq'; out += ch; i++; boundary = false; continue; }
      if (ch === '`')  { state = 'bt'; out += ch; i++; boundary = false; continue; }

      if (boundary) {
        let matched = false;
        for (const kw of KEYWORDS) {
          if (script.startsWith(kw, i)) {
            const afterIdx = i + kw.length;
            const after = script[afterIdx];
            const beforeIdx = i - 1;
            const before = beforeIdx >= 0 ? script[beforeIdx] : undefined;
            // Ký tự trước phải là ws/boundary (không phải identifier)
            const beforeOk = before === undefined || /[^A-Za-z0-9_$]/.test(before);
            const afterOk  = after === undefined || /[^A-Za-z0-9_$]/.test(after);
            if (beforeOk && afterOk) {
              out += kw[0].toLowerCase() + kw.slice(1);
              i += kw.length;
              matched = true;
              boundary = false;
              break;
            }
          }
        }
        if (matched) continue;
      }

      if (ch === '\n' || ch === '{' || ch === ';' || ch === ',' || ch === '(') {
        boundary = true;
      } else if (ch === ' ' || ch === '\t' || ch === '\r') {
        // giữ boundary
      } else {
        boundary = false;
      }

      out += ch;
      i++;
      continue;
    }

    if (state === 'sline') {
      out += ch;
      if (ch === '\n') { state = 'code'; boundary = true; }
      i++;
      continue;
    }

    if (state === 'mline') {
      out += ch;
      if (ch === '*' && next === '/') { out += '/'; i += 2; state = 'code'; boundary = false; continue; }
      i++;
      continue;
    }

    if (state === 'sq' || state === 'dq') {
      out += ch;
      if (ch === '\\') {
        if (next !== undefined) { out += next; i += 2; continue; }
      }
      const quote = state === 'sq' ? "'" : '"';
      if (ch === quote) { state = 'code'; boundary = false; }
      i++;
      continue;
    }

    if (state === 'bt') {
      out += ch;
      if (ch === '\\') {
        if (next !== undefined) { out += next; i += 2; continue; }
      }
      if (ch === '`') { state = 'code'; boundary = false; }
      i++;
      continue;
    }
  }

  return out;
}

module.exports = { KEYWORDS, findScriptBlocks, fixScriptBlock };
