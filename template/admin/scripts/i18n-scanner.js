#!/usr/bin/env node

/**
 * i18n Scanner Tool
 * Tự động quét và tìm các hardcoded Chinese strings trong Vue files
 * 
 * Usage:
 *   node scripts/i18n-scanner.js [options]
 * 
 * Options:
 *   --path <path>     Quét trong thư mục cụ thể (default: src/pages)
 *   --output <file>   Xuất kết quả ra file JSON (default: i18n-report.json)
 *   --replace         Tự động thay thế (cần cẩn thận!)
 *   --help            Hiển thị help
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

// Cấu hình
const CONFIG = {
  scanPath: 'src/pages',
  outputFile: 'i18n-report.json',
  vueExtensions: ['.vue'],
  excludePatterns: [
    'node_modules',
    '.git',
    'dist',
    'build',
    'i18n',
  ],
  // Pattern để tìm Chinese text
  chinesePattern: /[\u4e00-\u9fff]+/g,
  // Pattern để bỏ qua (đã có $t hoặc :label="$t")
  ignorePatterns: [
    /\$t\(['"`][^'"`]+['"`]\)/g,
    /:label="\$t\(/g,
    /:title="\$t\(/g,
    /:placeholder="\$t\(/g,
    /label="\$t\(/g,
    /title="\$t\(/g,
    /placeholder="\$t\(/g,
    /v-text="\$t\(/g,
    /{{ \$t\(/g,
    /v-html="\$t\(/g,
  ],
};

// Tìm tất cả các file .vue
function findVueFiles(dir, fileList = []) {
  const files = fs.readdirSync(dir);
  
  files.forEach(file => {
    const filePath = path.join(dir, file);
    const stat = fs.statSync(filePath);
    
    // Bỏ qua các thư mục không cần thiết
    if (stat.isDirectory()) {
      const shouldExclude = CONFIG.excludePatterns.some(pattern => 
        filePath.includes(pattern)
      );
      if (!shouldExclude) {
        findVueFiles(filePath, fileList);
      }
    } else if (file.endsWith('.vue')) {
      fileList.push(filePath);
    }
  });
  
  return fileList;
}

// Kiểm tra xem text có nên bỏ qua không
function shouldIgnore(text) {
  return CONFIG.ignorePatterns.some(pattern => pattern.test(text));
}

// Tìm Chinese text trong file
function scanFile(filePath) {
  const content = fs.readFileSync(filePath, 'utf-8');
  const lines = content.split('\n');
  const findings = [];
  
  lines.forEach((line, index) => {
    // Bỏ qua comment và đã có i18n
    if (line.trim().startsWith('//') || shouldIgnore(line)) {
      return;
    }
    
    // Tìm Chinese characters
    const matches = line.match(CONFIG.chinesePattern);
    if (matches) {
      // Lọc ra các text thực sự cần dịch (không phải trong $t())
      const chineseTexts = matches.filter(text => {
        // Kiểm tra xem có nằm trong $t() không
        const beforeMatch = line.substring(0, line.indexOf(text));
        const afterMatch = line.substring(line.indexOf(text) + text.length);
        
        // Nếu có $t( trước đó và ) sau đó, thì đã được dịch rồi
        if (beforeMatch.includes('$t(') && afterMatch.includes(')')) {
          return false;
        }
        
        // Kiểm tra các pattern khác
        return !shouldIgnore(line);
      });
      
      if (chineseTexts.length > 0) {
        // Tìm context (label, title, placeholder, button text, etc.)
        let context = 'text';
        if (line.includes('label=') || line.includes(':label=')) {
          context = 'label';
        } else if (line.includes('title=') || line.includes(':title=')) {
          context = 'title';
        } else if (line.includes('placeholder=') || line.includes(':placeholder=')) {
          context = 'placeholder';
        } else if (line.includes('<el-button') || line.includes('v-db-click')) {
          context = 'button';
        } else if (line.includes('<el-table-column')) {
          context = 'table-header';
        } else if (line.includes('<el-dialog') || line.includes('el-dialog')) {
          context = 'modal';
        } else if (line.includes('el-form-item')) {
          context = 'form-item';
        } else if (line.includes('el-alert')) {
          context = 'alert';
        }
        
        findings.push({
          line: index + 1,
          text: line.trim(),
          chineseTexts: chineseTexts,
          context: context,
        });
      }
    }
  });
  
  return findings;
}

// Tạo báo cáo
function generateReport(findings) {
  const report = {
    summary: {
      totalFiles: findings.length,
      totalFindings: findings.reduce((sum, f) => sum + f.findings.length, 0),
      generatedAt: new Date().toISOString(),
    },
    files: findings.map(file => ({
      path: file.path,
      relativePath: path.relative(process.cwd(), file.path),
      findings: file.findings.map(f => ({
        line: f.line,
        context: f.context,
        chineseTexts: f.chineseTexts,
        originalLine: f.text,
        suggestion: generateSuggestion(f.chineseTexts, f.context),
      })),
    })),
  };
  
  return report;
}

// Tạo gợi ý key i18n
function generateSuggestion(texts, context) {
  const suggestions = texts.map(text => {
    // Tạo key từ text (đơn giản hóa)
    let key = text
      .replace(/[：:]/g, '')
      .replace(/\s+/g, '')
      .toLowerCase()
      .substring(0, 30); // Giới hạn độ dài
    
    return {
      originalText: text,
      suggestedKey: key,
      i18nKey: `message.${context}.${key}`,
      replacement: `$t('message.${context}.${key}')`,
    };
  });
  
  return suggestions;
}

// Xuất báo cáo ra console
function printReport(report) {
  console.log('\n📊 i18n Scanner Report\n');
  console.log(`📁 Total Files Scanned: ${report.summary.totalFiles}`);
  console.log(`🔍 Total Findings: ${report.summary.totalFindings}`);
  console.log(`⏰ Generated At: ${report.summary.generatedAt}\n`);
  console.log('═'.repeat(80));
  
  report.files.forEach(file => {
    if (file.findings.length === 0) return;
    
    console.log(`\n📄 ${file.relativePath}`);
    console.log('-'.repeat(80));
    
    file.findings.forEach(finding => {
      console.log(`\n  Line ${finding.line} [${finding.context}]:`);
      console.log(`  Original: ${finding.originalLine}`);
      console.log(`  Chinese Text: ${finding.chineseTexts.join(', ')}`);
      console.log(`  Suggestions:`);
      finding.suggestion.forEach(s => {
        console.log(`    - Key: ${s.suggestedKey}`);
        console.log(`      Replace: ${s.originalText} → ${s.replacement}`);
      });
    });
  });
  
  console.log('\n' + '═'.repeat(80));
  console.log('\n✅ Scan completed!\n');
}

// Main function
function main() {
  const args = process.argv.slice(2);
  
  // Parse arguments
  let scanPath = CONFIG.scanPath;
  let outputFile = CONFIG.outputFile;
  let shouldReplace = false;
  
  for (let i = 0; i < args.length; i++) {
    if (args[i] === '--path' && args[i + 1]) {
      scanPath = args[i + 1];
      i++;
    } else if (args[i] === '--output' && args[i + 1]) {
      outputFile = args[i + 1];
      i++;
    } else if (args[i] === '--replace') {
      shouldReplace = true;
    } else if (args[i] === '--help') {
      console.log(`
i18n Scanner Tool

Usage:
  node scripts/i18n-scanner.js [options]

Options:
  --path <path>     Scan directory (default: src/pages)
  --output <file>   Output JSON file (default: i18n-report.json)
  --replace         Auto-replace (use with caution!)
  --help            Show this help

Examples:
  node scripts/i18n-scanner.js
  node scripts/i18n-scanner.js --path src/pages/system
  node scripts/i18n-scanner.js --output custom-report.json
      `);
      process.exit(0);
    }
  }
  
  const fullPath = path.join(process.cwd(), scanPath);
  
  if (!fs.existsSync(fullPath)) {
    console.error(`❌ Error: Path not found: ${fullPath}`);
    process.exit(1);
  }
  
  console.log('🔍 Scanning for Chinese text...\n');
  console.log(`📂 Path: ${fullPath}\n`);
  
  // Tìm tất cả file .vue
  const vueFiles = findVueFiles(fullPath);
  console.log(`📄 Found ${vueFiles.length} Vue files\n`);
  
  // Quét từng file
  const findings = [];
  vueFiles.forEach(filePath => {
    const fileFindings = scanFile(filePath);
    if (fileFindings.length > 0) {
      findings.push({
        path: filePath,
        findings: fileFindings,
      });
    }
  });
  
  // Tạo báo cáo
  const report = generateReport(findings);
  
  // In ra console
  printReport(report);
  
  // Lưu vào file JSON
  fs.writeFileSync(
    path.join(process.cwd(), outputFile),
    JSON.stringify(report, null, 2),
    'utf-8'
  );
  
  console.log(`\n💾 Report saved to: ${outputFile}\n`);
  
  // Tạo file CSV để dễ import vào Excel
  const csvPath = outputFile.replace('.json', '.csv');
  generateCSV(report, csvPath);
  console.log(`📊 CSV report saved to: ${csvPath}\n`);
}

// Tạo file CSV
function generateCSV(report, outputPath) {
  const rows = [];
  rows.push(['File Path', 'Line', 'Context', 'Chinese Text', 'Suggested Key', 'i18n Key', 'Replacement']);
  
  report.files.forEach(file => {
    file.findings.forEach(finding => {
      finding.suggestion.forEach(s => {
        rows.push([
          file.relativePath,
          finding.line,
          finding.context,
          s.originalText,
          s.suggestedKey,
          s.i18nKey,
          s.replacement,
        ]);
      });
    });
  });
  
  const csvContent = rows.map(row => 
    row.map(cell => `"${cell}"`).join(',')
  ).join('\n');
  
  fs.writeFileSync(path.join(process.cwd(), outputPath), csvContent, 'utf-8');
}

// Chạy tool
if (require.main === module) {
  main();
}

module.exports = { scanFile, findVueFiles, generateReport };

