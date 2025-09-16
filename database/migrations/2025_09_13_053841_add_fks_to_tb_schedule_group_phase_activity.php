<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
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
        return $row->COLUMN_TYPE; // contoh: 'int(11)' atau 'int(11) unsigned'
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
        $tbl = 'tb_schedule_group_phase_activity';

        // pastikan tidak ada FK lama (aman untuk rerun)
        foreach (['phase_uid','task_group_uid','task_group_detail_uid','task_uid'] as $col) {
            $this->dropFkByColumn($tbl, $col);
        }

        // samakan tipe anak dengan parent (menghindari error 3780)
        $phaseType   = $this->colType('tb_phases',             'uid');
        $groupType   = $this->colType('tb_task_group',        'uid');
        $detailType  = $this->colType('tb_task_group_detail', 'uid');
        $taskType    = $this->colType('tb_task',              'uid'); // opsional

        DB::statement("
            ALTER TABLE `{$tbl}`
            MODIFY COLUMN `phase_uid`              {$phaseType} NOT NULL,
            MODIFY COLUMN `task_group_uid`         {$groupType} NOT NULL,
            MODIFY COLUMN `task_group_detail_uid`  {$detailType} NOT NULL,
            MODIFY COLUMN `task_uid`               {$taskType} NULL
        ");

        // tambah index jika belum ada (aman untuk rerun)
        try { DB::statement("ALTER TABLE `{$tbl}` ADD INDEX `idx_phase_uid`(`phase_uid`)"); } catch (\Throwable $e) {}
        try { DB::statement("ALTER TABLE `{$tbl}` ADD INDEX `idx_group_uid`(`task_group_uid`)"); } catch (\Throwable $e) {}
        try { DB::statement("ALTER TABLE `{$tbl}` ADD INDEX `idx_detail_uid`(`task_group_detail_uid`)"); } catch (\Throwable $e) {}
        try { DB::statement("ALTER TABLE `{$tbl}` ADD INDEX `idx_task_uid`(`task_uid`)"); } catch (\Throwable $e) {}

        // pasang FK (ON DELETE CASCADE)
        DB::statement("
            ALTER TABLE `{$tbl}`
            ADD CONSTRAINT `fk_phase_act_phase`
                FOREIGN KEY (`phase_uid`)
                REFERENCES `tb_phases`(`uid`)
                ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `fk_phase_act_group`
                FOREIGN KEY (`task_group_uid`)
                REFERENCES `tb_task_group`(`uid`)
                ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `fk_phase_act_group_detail`
                FOREIGN KEY (`task_group_detail_uid`)
                REFERENCES `tb_task_group_detail`(`uid`)
                ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `fk_phase_act_task`
                FOREIGN KEY (`task_uid`)
                REFERENCES `tb_task`(`uid`)
                ON DELETE CASCADE ON UPDATE CASCADE
        ");
    }

    public function down(): void
    {
        $tbl = 'tb_schedule_group_phase_activity';
        foreach (['fk_phase_act_phase','fk_phase_act_group','fk_phase_act_group_detail','fk_phase_act_task'] as $fk) {
            try { DB::statement("ALTER TABLE `{$tbl}` DROP FOREIGN KEY `{$fk}`"); } catch (\Throwable $e) {}
        }
    }
};
