#!/usr/bin/env node

/**
 * i18n Auto Translate Tool
 * Tự động dịch và thay thế các hardcoded Chinese strings bằng i18n keys
 * 
 * Usage:
 *   node scripts/i18n-auto-translate.js --report <report-file> [--dry-run]
 */

const fs = require('fs');
const path = require('path');

// Đọc báo cáo
function readReport(reportPath) {
  const content = fs.readFileSync(reportPath, 'utf-8');
  return JSON.parse(content);
}

// Tạo key i18n từ text
function generateI18nKey(text, context) {
  // Loại bỏ dấu câu và ký tự đặc biệt
  let key = text
    .replace(/[：:，。！？、；""''（）()【】\[\]]/g, '')
    .replace(/\s+/g, '')
    .trim();
  
  // Giới hạn độ dài
  if (key.length > 40) {
    key = key.substring(0, 40);
  }
  
  return key;
}

// Tạo replacement code
function generateReplacement(originalText, i18nKey, context) {
  // Xác định cách thay thế dựa trên context
  if (context === 'label' && originalText.includes('label=')) {
    return originalText.replace(
      /label="([^"]*)"/,
      `:label="$t('${i18nKey}')"`
    );
  } else if (context === 'title' && originalText.includes('title=')) {
    return originalText.replace(
      /title="([^"]*)"/,
      `:title="$t('${i18nKey}')"`
    );
  } else if (context === 'placeholder' && originalText.includes('placeholder=')) {
    return originalText.replace(
      /placeholder="([^"]*)"/,
      `:placeholder="$t('${i18nKey}')"`
    );
  } else if (context === 'button') {
    // Thay thế text trong button
    const match = originalText.match(/>([^<]+)</);
    if (match) {
      return originalText.replace(
        />([^<]+)</,
        `>{{ $t('${i18nKey}') }}<`
      );
    }
  } else if (context === 'table-header' && originalText.includes('title=')) {
    return originalText.replace(
      /title="([^"]*)"/,
      `:title="$t('${i18nKey}')"`
    );
  }
  
  // Default: thay thế trực tiếp
  return originalText.replace(
    new RegExp(originalText.match(/[\u4e00-\u9fff]+/)?.[0] || '', 'g'),
    `$t('${i18nKey}')`
  );
}

// Thay thế trong file
function replaceInFile(filePath, findings, translations) {
  let content = fs.readFileSync(filePath, 'utf-8');
  const lines = content.split('\n');
  let modified = false;
  
  // Xử lý từng finding (từ dưới lên để không ảnh hưởng đến line numbers)
  findings.sort((a, b) => b.line - a.line).forEach(finding => {
    const lineIndex = finding.line - 1;
    if (lineIndex >= 0 && lineIndex < lines.length) {
      const originalLine = lines[lineIndex];
      
      finding.suggestion.forEach(s => {
        const i18nKey = translations[s.originalText] || s.i18nKey;
        const replacement = generateReplacement(originalLine, i18nKey, finding.context);
        
        if (replacement !== originalLine) {
          lines[lineIndex] = replacement;
          modified = true;
        }
      });
    }
  });
  
  if (modified) {
    return lines.join('\n');
  }
  
  return null;
}

// Thêm translations vào file i18n
function addTranslationsToI18n(translations, module = 'system') {
  const i18nDir = path.join(process.cwd(), 'src/i18n/pages', module);
  const languages = ['zh-cn', 'en', 'zh-tw', 'vi'];
  
  languages.forEach(lang => {
    const filePath = path.join(i18nDir, `${lang}.js`);
    if (!fs.existsSync(filePath)) {
      console.log(`⚠️  File not found: ${filePath}`);
      return;
    }
    
    let content = fs.readFileSync(filePath, 'utf-8');
    
    // Parse và thêm translations
    // Tìm vị trí cuối cùng của object
    const lastBraceIndex = content.lastIndexOf('  },\n};');
    if (lastBraceIndex === -1) {
      console.log(`⚠️  Cannot find insertion point in ${filePath}`);
      return;
    }
    
    // Tạo translations mới
    const newTranslations = Object.entries(translations)
      .map(([key, value]) => {
        const translation = value[lang] || value['zh-cn'] || key;
        return `    ${key}: '${translation}',`;
      })
      .join('\n');
    
    // Chèn vào trước dấu đóng
    content = content.slice(0, lastBraceIndex) + 
              newTranslations + '\n' + 
              content.slice(lastBraceIndex);
    
    fs.writeFileSync(filePath, content, 'utf-8');
    console.log(`✅ Updated ${filePath}`);
  });
}

// Main function
function main() {
  const args = process.argv.slice(2);
  let reportPath = 'i18n-report.json';
  let dryRun = false;
  
  for (let i = 0; i < args.length; i++) {
    if (args[i] === '--report' && args[i + 1]) {
      reportPath = args[i + 1];
      i++;
    } else if (args[i] === '--dry-run') {
      dryRun = true;
    }
  }
  
  if (!fs.existsSync(reportPath)) {
    console.error(`❌ Report file not found: ${reportPath}`);
    console.log('💡 Run: node scripts/i18n-scanner.js first');
    process.exit(1);
  }
  
  console.log('📖 Reading report...\n');
  const report = readReport(reportPath);
  
  console.log(`📊 Found ${report.summary.totalFindings} texts to translate\n`);
  
  if (dryRun) {
    console.log('🔍 DRY RUN MODE - No files will be modified\n');
  }
  
  // Group by file
  const fileGroups = {};
  report.files.forEach(file => {
    if (!fileGroups[file.path]) {
      fileGroups[file.path] = [];
    }
    fileGroups[file.path].push(...file.findings);
  });
  
  // Collect all translations
  const allTranslations = {};
  
  Object.entries(fileGroups).forEach(([filePath, findings]) => {
    findings.forEach(finding => {
      finding.suggestion.forEach(s => {
        if (!allTranslations[s.originalText]) {
          allTranslations[s.originalText] = {
            key: generateI18nKey(s.originalText, finding.context),
            'zh-cn': s.originalText,
            'en': s.originalText, // TODO: Translate
            'zh-tw': s.originalText, // TODO: Translate
            'vi': s.originalText, // TODO: Translate
          };
        }
      });
    });
  });
  
  console.log(`📝 Collected ${Object.keys(allTranslations).length} unique translations\n`);
  
  if (!dryRun) {
    // Replace in files
    Object.entries(fileGroups).forEach(([filePath, findings]) => {
      const newContent = replaceInFile(filePath, findings, allTranslations);
      if (newContent) {
        fs.writeFileSync(filePath, newContent, 'utf-8');
        console.log(`✅ Updated ${path.relative(process.cwd(), filePath)}`);
      }
    });
    
    // Add to i18n files
    // TODO: Implement proper i18n file update
    console.log('\n⚠️  Auto-translation to i18n files needs manual review');
    console.log('💡 Please review and add translations manually to i18n files\n');
  } else {
    console.log('\n📋 Would update the following files:');
    Object.keys(fileGroups).forEach(filePath => {
      console.log(`   - ${path.relative(process.cwd(), filePath)}`);
    });
  }
  
  console.log('\n✅ Done!\n');
}

if (require.main === module) {
  main();
}

module.exports = { readReport, generateI18nKey, generateReplacement };

