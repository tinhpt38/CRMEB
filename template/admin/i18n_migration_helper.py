#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Helper script để tự động hóa việc tìm và thay thế text tiếng Trung trong Vue files
"""

import re
import os
from pathlib import Path

def find_chinese_text_in_vue(file_path):
    """Tìm tất cả text tiếng Trung trong file Vue"""
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Pattern để tìm text tiếng Trung
    patterns = [
        # label="中文"
        (r'label="([^"]*[\u4e00-\u9fff][^"]*)"', 'label'),
        # placeholder="中文"
        (r'placeholder="([^"]*[\u4e00-\u9fff][^"]*)"', 'placeholder'),
        # title="中文"
        (r'title="([^"]*[\u4e00-\u9fff][^"]*)"', 'title'),
        # :label="'中文'"
        (r':label="\'([^\']*[\u4e00-\u9fff][^\']*)\'"', 'label'),
        # label: '中文'
        (r"label:\s*['\"]([^'\"]*[\u4e00-\u9fff][^'\"]*)['\"]", 'label'),
        # >中文<
        (r'>\s*([^<]*[\u4e00-\u9fff][^<]*)\s*<', 'text'),
    ]
    
    found = []
    for pattern, attr_type in patterns:
        matches = re.finditer(pattern, content)
        for match in matches:
            found.append({
                'text': match.group(1),
                'type': attr_type,
                'line': content[:match.start()].count('\n') + 1,
                'full_match': match.group(0)
            })
    
    return found

def scan_directory(directory):
    """Quét thư mục và tìm tất cả file Vue có text tiếng Trung"""
    results = {}
    for root, dirs, files in os.walk(directory):
        # Bỏ qua node_modules và dist
        dirs[:] = [d for d in dirs if d not in ['node_modules', 'dist', '.git']]
        
        for file in files:
            if file.endswith('.vue'):
                file_path = os.path.join(root, file)
                chinese_texts = find_chinese_text_in_vue(file_path)
                if chinese_texts:
                    results[file_path] = chinese_texts
    
    return results

if __name__ == '__main__':
    src_dir = 'src'
    if os.path.exists(src_dir):
        print(f"Scanning {src_dir} for Chinese text...")
        results = scan_directory(src_dir)
        
        print(f"\nFound {len(results)} files with Chinese text:\n")
        for file_path, texts in sorted(results.items()):
            print(f"{file_path}: {len(texts)} occurrences")
            for text_info in texts[:3]:  # Show first 3
                print(f"  - {text_info['type']}: {text_info['text'][:50]}")
            if len(texts) > 3:
                print(f"  ... and {len(texts) - 3} more")
            print()
    else:
        print(f"Directory {src_dir} not found")

