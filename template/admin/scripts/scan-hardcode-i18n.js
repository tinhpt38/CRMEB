#!/usr/bin/env node
/* eslint-disable no-console */
const fs = require('fs');
const path = require('path');

const repoRoot = path.resolve(__dirname, '../../..');
const targets = [
  path.join(repoRoot, 'template/admin/src'),
  path.join(repoRoot, 'template/uni-app'),
];

const allowedPathParts = [
  `${path.sep}i18n${path.sep}`,
  `${path.sep}static${path.sep}`,
  `${path.sep}node_modules${path.sep}`,
  `${path.sep}dist${path.sep}`,
  `${path.sep}unpackage${path.sep}`,
];

const allowedFileNames = new Set(['zh-cn.js', 'zh-tw.js', 'en.js', 'en_us.php', 'zh_cn.php']);
const allowedExactPaths = new Set([path.join(repoRoot, 'template/admin/src/components/freightTemplate/provinces.js')]);
const allowedExtensions = new Set(['.vue', '.js', '.ts', '.php']);
const cjkRegex = /[\u3400-\u4DBF\u4E00-\u9FFF]/;
const i18nCallRegex = /\$t\(\s*(['"`])([\s\S]*?)\1\s*\)/g;
const maxResults = 200;
const violations = [];

function shouldSkip(filePath) {
  if (allowedExactPaths.has(filePath)) return true;
  if (allowedPathParts.some((part) => filePath.includes(part))) return true;
  return allowedFileNames.has(path.basename(filePath));
}

function walkDir(dirPath) {
  const entries = fs.readdirSync(dirPath, { withFileTypes: true });
  for (const entry of entries) {
    const fullPath = path.join(dirPath, entry.name);
    if (entry.isDirectory()) {
      if (!shouldSkip(fullPath + path.sep)) walkDir(fullPath);
      continue;
    }
    if (!allowedExtensions.has(path.extname(entry.name))) continue;
    if (shouldSkip(fullPath)) continue;
    inspectFile(fullPath);
    if (violations.length >= maxResults) return;
  }
}

function inspectFile(filePath) {
  const content = fs.readFileSync(filePath, 'utf8');
  const lines = content.split('\n');
  let inBlockComment = false;
  let inHtmlComment = false;
  lines.forEach((line, index) => {
    if (violations.length >= maxResults) return;

    const trimmedLine = line.trim();
    if (!trimmedLine) return;

    let normalized = trimmedLine;
    if (inHtmlComment) {
      const htmlEndIndex = normalized.indexOf('-->');
      if (htmlEndIndex === -1) return;
      normalized = normalized.slice(htmlEndIndex + 3).trim();
      inHtmlComment = false;
      if (!normalized) return;
    }

    if (normalized.startsWith('<!--')) {
      const htmlEndIndex = normalized.indexOf('-->');
      if (htmlEndIndex === -1) {
        inHtmlComment = true;
        return;
      }
      normalized = normalized.slice(htmlEndIndex + 3).trim();
      if (!normalized) return;
    }

    if (inBlockComment) {
      const endIndex = normalized.indexOf('*/');
      if (endIndex === -1) return;
      normalized = normalized.slice(endIndex + 2).trim();
      inBlockComment = false;
      if (!normalized) return;
    }

    if (normalized.startsWith('//') || normalized.startsWith('*')) return;
    if (normalized.startsWith('/*')) {
      const endIndex = normalized.indexOf('*/');
      if (endIndex === -1) {
        inBlockComment = true;
        return;
      }
      normalized = normalized.slice(endIndex + 2).trim();
      if (!normalized) return;
    }

    const contentWithoutInlineComments = normalized.replace(/\/\*.*?\*\//g, '').replace(/\/\/.*$/g, '').trim();
    if (!contentWithoutInlineComments) return;

    const i18nMatches = [...contentWithoutInlineComments.matchAll(i18nCallRegex)];
    for (const match of i18nMatches) {
      const key = match[2] || '';
      if (!key) continue;
      if (cjkRegex.test(key) || !key.includes('.')) {
        violations.push({
          file: path.relative(repoRoot, filePath),
          line: index + 1,
          content: contentWithoutInlineComments.slice(0, 180),
        });
        return;
      }
    }

    if (!cjkRegex.test(contentWithoutInlineComments)) return;
    if (
      contentWithoutInlineComments.includes('$t(') ||
      contentWithoutInlineComments.includes('this.$t(') ||
      contentWithoutInlineComments.includes('this.t(')
    )
      return;
    violations.push({
      file: path.relative(repoRoot, filePath),
      line: index + 1,
      content: contentWithoutInlineComments.slice(0, 180),
    });
  });
}

for (const target of targets) {
  if (fs.existsSync(target)) walkDir(target);
}

if (!violations.length) {
  console.log('i18n hardcode scan passed.');
  process.exit(0);
}

console.error(`Detected ${violations.length} potential hardcoded CJK strings:`);
violations.forEach((item) => {
  console.error(`${item.file}:${item.line} ${item.content}`);
});
console.error('Please replace hardcoded text with i18n keys or update whitelist intentionally.');
process.exit(1);
