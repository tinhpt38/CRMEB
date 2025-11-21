import os
import re

def extract_strings(directory):
    unique_strings = set()
    # Regex to match $t('string') or $t("string") or $t(`string`)
    # and ensure it contains at least one Chinese character
    pattern = re.compile(r"\$t\(['\"`](.*?)['\"`]\)")
    
    for root, dirs, files in os.walk(directory):
        for file in files:
            if file.endswith(".vue") or file.endswith(".js"):
                filepath = os.path.join(root, file)
                try:
                    with open(filepath, 'r', encoding='utf-8') as f:
                        content = f.read()
                        matches = pattern.findall(content)
                        for match in matches:
                            # Check if string contains Chinese characters
                            if any('\u4e00' <= char <= '\u9fa5' for char in match):
                                unique_strings.add(match)
                except Exception as e:
                    print(f"Error reading {filepath}: {e}")
    
    return sorted(list(unique_strings))

if __name__ == "__main__":
    directory = "/Users/tinhp/Workspace/Projects/CRMEB/template/uni-app"
    strings = extract_strings(directory)
    print(f"Found {len(strings)} unique strings.")
    with open("extracted_strings.txt", "w", encoding="utf-8") as f:
        for s in strings:
            f.write(s + "\n")
