<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private function colType(string $table, string $col): string {
        $db = DB::getDatabaseName();
        $row = DB::selectOne("
            SELECT COLUMN_TYPE
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
            LIMIT 1
        ", [$db, $table, $col]);

        if (!$row) {
            throw new RuntimeException("Kolom {$table}.{$col} tidak ditemukan.");
        }
        // Contoh hasil: 'int(11) unsigned' atau 'int(11)'
        return $row->COLUMN_TYPE;
    }

    private function dropFkByColumn(string $table, string $column): void {
        $db = DB::getDatabaseName();
        $rows = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME   = ?
              AND COLUMN_NAME  = ?
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$db, $table, $column]);

        foreach ($rows as $r) {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$r->CONSTRAINT_NAME}`");
        }
    }

    public function up(): void
    {
        $pivot = 'tb_schedule_template_detail';

        // 1) Drop FK lama jika ada
        $this->dropFkByColumn($pivot, 'template_uid');
        $this->dropFkByColumn($pivot, 'task_group_detail_uid');

        // 2) Ambil tipe aktual kolom parent
        $tplUidType  = $this->colType('tb_schedule_template',   'uid'); // ex: 'int(11) unsigned'
        $detUidType  = $this->colType('tb_task_group_detail',    'uid'); // ex: 'int(11)'

        // 3) Samakan tipe kolom anak dengan parent (persis, termasuk unsigned)
        DB::statement("
            ALTER TABLE `{$pivot}`
            MODIFY COLUMN `template_uid` {$tplUidType} NOT NULL,
            MODIFY COLUMN `task_group_detail_uid` {$detUidType} NOT NULL
        ");

        // 4) Tambah index jika belum ada (aman untuk dijalankan berulang)
        try { DB::statement("ALTER TABLE `{$pivot}` ADD INDEX `idx_tpl_uid`(`template_uid`)"); } catch (\Throwable $e) {}
        try { DB::statement("ALTER TABLE `{$pivot}` ADD INDEX `idx_task_detail_uid`(`task_group_detail_uid`)"); } catch (\Throwable $e) {}

        // 5) Pasang FK dengan ON DELETE CASCADE
        DB::statement("
            ALTER TABLE `{$pivot}`
            ADD CONSTRAINT `fk_tpl_detail_template`
                FOREIGN KEY (`template_uid`)
                REFERENCES `tb_schedule_template`(`uid`)
                ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `fk_tpl_detail_taskdetail`
                FOREIGN KEY (`task_group_detail_uid`)
                REFERENCES `tb_task_group_detail`(`uid`)
                ON DELETE CASCADE ON UPDATE CASCADE
        ");
    }

    public function down(): void
    {
        $pivot = 'tb_schedule_template_detail';
        // Lepas FK yang baru dibuat
        try { DB::statement("ALTER TABLE `{$pivot}` DROP FOREIGN KEY `fk_tpl_detail_template`"); } catch (\Throwable $e) {}
        try { DB::statement("ALTER TABLE `{$pivot}` DROP FOREIGN KEY `fk_tpl_detail_taskdetail`"); } catch (\Throwable $e) {}
        // (opsional) tidak mengembalikan tipe agar rollback lebih aman
    }
};
