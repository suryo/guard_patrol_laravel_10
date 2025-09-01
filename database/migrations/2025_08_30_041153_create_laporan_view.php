<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        DB::statement(<<<SQL
        CREATE OR REPLACE VIEW laporan AS
        SELECT
          r.reportDate        AS reportDate,
          r.lastUpdated       AS lastUpdated,
          p.personName        AS personName,
          s.scheduleId        AS scheduleId,
          s.checkpointName    AS checkpointName,
          s.scheduleDate      AS scheduleDate,
          s.scheduleStart     AS scheduleStart,
          s.scheduleEnd       AS scheduleEnd,
          a.checkpointStart   AS checkpointStart,
          a.checkpointEnd     AS checkpointEnd,
          a.activityStart     AS activityStart,
          a.activityEnd       AS activityEnd
        FROM tb_report r
        LEFT JOIN tb_person   p ON p.personId = r.personId
        LEFT JOIN tb_activity a ON a.activityId = r.activityId
        LEFT JOIN tb_schedule s ON s.scheduleId = a.scheduleId
        SQL);
    }
    public function down(): void {
        DB::statement('DROP VIEW IF EXISTS laporan');
    }
};

