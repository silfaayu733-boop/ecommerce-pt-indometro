import sqlite3, sys, os

root = r"d:\\project_skripsi_perancangan_e-commerce_bahan_bangunan_PT_indometro"
sqlite_path = os.path.join(root, 'database', 'database.sqlite')
backup_dir = os.path.join(root, 'backup')
os.makedirs(backup_dir, exist_ok=True)
dump_path = os.path.join(backup_dir, f"sqlite_dump_{(__import__('datetime').datetime.now().strftime('%Y%m%d_%H%M%S'))}.sql")

conn = sqlite3.connect(sqlite_path)
with open(dump_path, 'w', encoding='utf-8') as f:
    for line in conn.iterdump():
        f.write(f"{line}\n")
conn.close()
print(f"SQLite dump written to {dump_path}")
