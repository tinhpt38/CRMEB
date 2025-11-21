import json
import os

def generate_sql(json_file, output_file):
    with open(json_file, 'r', encoding='utf-8') as f:
        translations = json.load(f)

    with open(output_file, 'w', encoding='utf-8') as f:
        f.write("-- Insert Vietnamese Language Type\n")
        f.write("INSERT INTO `eb_lang_type` (`language_name`, `file_name`, `is_default`, `status`, `is_del`, `create_time`, `update_time`) \n")
        f.write("SELECT 'Vietnamese', 'vi-VN', 0, 1, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP() \n")
        f.write("WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_type` WHERE `file_name` = 'vi-VN');\n\n")
        
        f.write("-- Insert Translations\n")
        f.write("-- Using a variable to store type_id\n")
        f.write("SET @vi_type_id = (SELECT `id` FROM `eb_lang_type` WHERE `file_name` = 'vi-VN' LIMIT 1);\n\n")
        
        for code, explain in translations.items():
            # Escape single quotes
            code_safe = code.replace("'", "\\'")
            explain_safe = explain.replace("'", "\\'")
            
            f.write(f"INSERT INTO `eb_lang_code` (`type_id`, `code`, `lang_explain`, `remarks`, `is_admin`) \n")
            f.write(f"SELECT @vi_type_id, '{code_safe}', '{explain_safe}', 'Imported via script', 0 \n")
            f.write(f"WHERE NOT EXISTS (SELECT 1 FROM `eb_lang_code` WHERE `type_id` = @vi_type_id AND `code` = '{code_safe}');\n")

if __name__ == "__main__":
    generate_sql("crmeb/translations.json", "crmeb/vietnamese_translations.sql")
