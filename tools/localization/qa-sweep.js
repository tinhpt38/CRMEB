#!/usr/bin/env node
/**
 * qa-sweep.js
 * -----------
 * Quét mã nguồn đã áp glossary để tìm các "mùi dịch máy" còn sót và Hán tự
 * hardcode. Mục đích: sinh báo cáo để bổ sung thêm vào `vi-glossary.json` rồi
 * chạy lại `apply-glossary.js`, lặp cho tới khi báo cáo rỗng.
 *
 * Các mẫu cảnh báo:
 *   - Hán tự còn hardcode (\u4e00-\u9fff) nằm trong text hiển thị / string.
 *   - Từ nghi ngờ dịch máy sai ngữ cảnh TMĐT: danh sách định nghĩa bên dưới.
 *   - Cụm pattern đặc trưng: "Hơn" đứng riêng, "Thứ tự ...", "Đánh vần ...".
 *
 * Usage:
 *   node tools/localization/qa-sweep.js
 *   node tools/localization/qa-sweep.js --report tools/localization/out/qa-report.json
 *   node tools/localization/qa-sweep.js --root template/admin/src
 */

const fs = require('fs');
const path = require('path');

const args = process.argv.slice(2);
const rootIdx = args.indexOf('--root');
const reportIdx = args.indexOf('--report');
const ROOTS = rootIdx >= 0
  ? [args[rootIdx + 1]]
  : ['template/admin/src', 'crmeb/app', 'f-chan/src'];
const REPORT_PATH = reportIdx >= 0 ? args[reportIdx + 1] : null;

// Các cụm đã biết chắc là dịch sai, chưa chắc đã có trong glossary.
const SUSPECT_TERMS = [
  'Cứu', 'cứu',
  'Băng hình', 'băng hình',
  'Viên thuốc',
  'cổ phiếu',
  'bầu trời',
  'mặt trăng',
  'vận hành',
  'cài lại',
  'Xuất khẩu',
  'Truy vấn',
  'Đánh vần',
  'Giây giết',
  'Ôn lại',
  'trưởng thành',
  'bật lên',
  'nam giới',
  'Sự cân bằng',
  'tích phân',
  'Tình bạn',
  'Ràng buộc',
  'Xuất người dùng',
  'người dùngID',
  'hàng hóa',
];

// Dòng chứa các cụm hợp lệ — bỏ qua cảnh báo (false positive).
const EXCLUDE_LINE_PATTERNS = [
  /Giao diện editor/i,
  /Thứ tự sắp xếp/i,
  /Chủ đề biên tập/i,
  /Chỉ mục biên tập hiện tại/i,
  /dễ chế biến/i, // "sáng tạo" trong mô tả sản phẩm demo
  /giàu chất/i,
  /hóa đơn/i,
];

const HAN_RE = /[\u4e00-\u9fff]/;

// Tách "Hơn" (More) đứng riêng — từ 3 ký tự dễ gây false positive cho mention "Hơn thế nữa" etc.
// Match "Hơn" khi là từ độc lập: whitespace hoặc dấu câu bao quanh.
const HON_RE = /(^|[\s>'"|,.;:!?\-—])Hơn([\s<'"|,.;:!?\-—]|$)/;

function isSkipDir(name) {
  return ['node_modules', 'dist', 'vendor', 'mp_view', '.git', '.cursor', '.idea', 'out', 'tools', '.nuxt'].includes(name);
}

// Bỏ qua các file data/demo biết trước không phải UI thực sự.
function isSkipFile(fileRel) {
  return fileRel.includes('template/admin/src/styles/font/demo_index.html')
    || fileRel.endsWith('template/admin/src/styles/font/mobile.json')
    || fileRel.endsWith('template/admin/src/styles/font/iconfont.json')
    || fileRel.endsWith('template/admin/src/assets/iconfontYI/iconfontYI.json')
    || fileRel.includes('crmeb/app/services/diy/ThemeServices.php') // legacy
    || fileRel.includes('crmeb/crmeb/utils/DiyHomeLabelMap.php') // zh→vi lookup table
    || fileRel.includes('f-chan/src/mock/'); // demo product copy
}

function walk(dir, out = []) {
  if (!fs.existsSync(dir)) return out;
  const st = fs.statSync(dir);
  if (st.isFile()) {
    const ext = path.extname(dir).toLowerCase();
      if (['.vue', '.js', '.php', '.json', '.ts', '.tsx'].includes(ext)) out.push(dir);
    return out;
  }
  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    if (entry.isDirectory()) {
      if (isSkipDir(entry.name)) continue;
      walk(path.join(dir, entry.name), out);
    } else if (entry.isFile()) {
      const ext = path.extname(entry.name).toLowerCase();
      if (['.vue', '.js', '.php', '.json', '.ts', '.tsx'].includes(ext)) {
        out.push(path.join(dir, entry.name));
      }
    }
  }
  return out;
}

// Phân loại vị trí mỗi ký tự trong source là 'code' | 'comment' | 'string' | 'template'
// cho .vue/.js/.php. Dùng để gắn nhãn "context" vào mỗi hit QA.
function classifyLines(src, ext) {
  // Đơn giản: trả về cho mỗi dòng 1 nhãn dominant context.
  // .php / .js: parse code/comment/string.
  // .vue: phân biệt <script>/<template>/<style>.
  const n = src.length;
  const lineClass = [];
  const lines = src.split('\n');
  for (let i = 0; i < lines.length; i++) lineClass.push(new Set());

  const addClassAt = (offset, label) => {
    // Tìm line index từ offset (tuyến tính đơn giản - đủ cho 1 file).
    let lineIdx = 0;
    let cursor = 0;
    while (lineIdx < lines.length) {
      const lineLen = lines[lineIdx].length + 1; // + \n
      if (offset < cursor + lineLen) break;
      cursor += lineLen;
      lineIdx++;
    }
    lineClass[lineIdx]?.add(label);
  };

  if (ext === '.vue') {
    // find <template>/<script>/<style>
    const re = /<(template|script|style)\b[^>]*>/gi;
    let m;
    while ((m = re.exec(src))) {
      const tag = m[1].toLowerCase();
      const start = m.index + m[0].length;
      const closeIdx = src.indexOf(`</${tag}>`, start);
      if (closeIdx === -1) break;
      // Đánh nhãn trong [start, closeIdx)
      // Cho đơn giản: mark theo line
      let cursor = 0;
      for (let i = 0; i < lines.length; i++) {
        const lineStart = cursor;
        const lineEnd = cursor + lines[i].length;
        if (lineStart >= start && lineEnd <= closeIdx) lineClass[i].add(tag);
        cursor = lineEnd + 1;
      }
    }
  }

  // Đánh nhãn comment (các line có // hoặc # ở đầu (sau whitespace), hoặc nằm trong /* ... */ / <!-- ... -->)
  let inBlockComment = false;
  let inHtmlComment = false;
  for (let i = 0; i < lines.length; i++) {
    const ln = lines[i];
    const trimmed = ln.trim();
    if (inBlockComment) {
      lineClass[i].add('comment');
      if (ln.includes('*/')) inBlockComment = false;
      continue;
    }
    if (inHtmlComment) {
      lineClass[i].add('comment');
      if (ln.includes('-->')) inHtmlComment = false;
      continue;
    }
    if (trimmed.startsWith('//') || trimmed.startsWith('#') || trimmed.startsWith('*')) {
      lineClass[i].add('comment');
    }
    if (trimmed.startsWith('/*') && !trimmed.includes('*/')) { inBlockComment = true; lineClass[i].add('comment'); }
    if (trimmed.startsWith('<!--') && !trimmed.includes('-->')) { inHtmlComment = true; lineClass[i].add('comment'); }
    // Inline HTML comment chiếm nguyên dòng: `<!-- ... -->` hoặc `  <!-- ... -->` sau whitespace
    if (trimmed.startsWith('<!--') && trimmed.endsWith('-->')) {
      lineClass[i].add('comment');
    }
    // Inline `//` comment ở giữa dòng (sau whitespace) - đánh dấu nếu nội dung trước đó chỉ là whitespace hoặc ký tự tag đóng
    // (đủ đơn giản; QA dùng nhãn này chỉ để deprioritize)
    if (/^\s*\/\//.test(ln)) lineClass[i].add('comment');
    // inline comment block start/end on same line not counted as pure comment
  }

  return lineClass;
}

function checkFile(file) {
  const rel = path.relative(process.cwd(), file);
  if (isSkipFile(rel)) return [];
  const src = fs.readFileSync(file, 'utf8');
  const ext = path.extname(file).toLowerCase();
  const lines = src.split('\n');
  const classes = classifyLines(src, ext);
  const hits = [];

  for (let i = 0; i < lines.length; i++) {
    const line = lines[i];
    if (!line) continue;
    if (EXCLUDE_LINE_PATTERNS.some((re) => re.test(line))) continue;
    const cls = classes[i] || new Set();
    const contextLabel = cls.has('comment') ? 'comment'
      : cls.has('style') ? 'style'
      : cls.has('script') ? 'script'
      : cls.has('template') ? 'template'
      : 'code';

    if (HAN_RE.test(line)) {
      const chars = (line.match(/[\u4e00-\u9fff]+/g) || []).join(' ');
      hits.push({ line: i + 1, kind: 'han', context: contextLabel, term: chars, preview: line.trim().slice(0, 160) });
    }

    for (const term of SUSPECT_TERMS) {
      if (line.includes(term)) {
        hits.push({ line: i + 1, kind: 'suspect', context: contextLabel, term, preview: line.trim().slice(0, 160) });
      }
    }

    if (HON_RE.test(line)) {
      hits.push({ line: i + 1, kind: 'hon', context: contextLabel, term: 'Hơn', preview: line.trim().slice(0, 160) });
    }
  }

  return hits;
}

function main() {
  const files = [];
  for (const root of ROOTS) walk(path.resolve(root), files);

  const perFile = [];
  const countByTerm = Object.create(null);
  const countByKind = { han: 0, suspect: 0, hon: 0 };
  const countByContext = { code: 0, comment: 0, template: 0, script: 0, style: 0 };
  let totalHits = 0;
  let visibleHits = 0; // hits không phải comment/style

  for (const f of files) {
    const hits = checkFile(f);
    if (hits.length) {
      perFile.push({ file: path.relative(process.cwd(), f), hits });
      totalHits += hits.length;
      for (const h of hits) {
        countByTerm[h.term] = (countByTerm[h.term] || 0) + 1;
        countByKind[h.kind]++;
        countByContext[h.context] = (countByContext[h.context] || 0) + 1;
        if (h.context !== 'comment' && h.context !== 'style') visibleHits++;
      }
    }
  }

  console.log(`Scanned: ${files.length} files (${ROOTS.join(', ')})`);
  console.log(`Files with hits: ${perFile.length}`);
  console.log(`Total hits: ${totalHits} (han=${countByKind.han}, suspect=${countByKind.suspect}, hon=${countByKind.hon})`);
  console.log(`Visible hits (exclude comment/style): ${visibleHits}`);
  console.log(`By context:`, countByContext);

  const sortedTerms = Object.entries(countByTerm).sort((a, b) => b[1] - a[1]).slice(0, 25);
  if (sortedTerms.length) {
    console.log('Top terms:');
    for (const [term, cnt] of sortedTerms) console.log(`  ${cnt.toString().padStart(5)}  ${term}`);
  }

  if (perFile.length && perFile.length <= 15) {
    console.log('\nDetails:');
    for (const fh of perFile) {
      console.log(`\n${fh.file}:`);
      for (const h of fh.hits.slice(0, 8)) {
        console.log(`  L${h.line} [${h.kind}] ${h.term} :: ${h.preview}`);
      }
      if (fh.hits.length > 8) console.log(`  ... và ${fh.hits.length - 8} hit nữa`);
    }
  }

  if (REPORT_PATH) {
    const reportDir = path.dirname(REPORT_PATH);
    if (!fs.existsSync(reportDir)) fs.mkdirSync(reportDir, { recursive: true });
    fs.writeFileSync(REPORT_PATH, JSON.stringify({
      roots: ROOTS,
      summary: { files: files.length, filesWithHits: perFile.length, totalHits, countByKind },
      countByTerm,
      perFile,
    }, null, 2), 'utf8');
    console.log(`\nReport written to ${REPORT_PATH}`);
  }

  if (totalHits === 0) console.log('\nQA sweep clean. ✓');
}

main();
