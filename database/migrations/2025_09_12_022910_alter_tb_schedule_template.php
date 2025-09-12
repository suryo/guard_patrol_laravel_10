<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tb_schedule_template', function (Blueprint $table) {
            // rename start/end
            if (Schema::hasColumn('tb_schedule_template', 'templateStart')) {
                $table->renameColumn('templateStart', 'timeStart');
            }
            if (Schema::hasColumn('tb_schedule_template', 'templateEnd')) {
                $table->renameColumn('templateEnd', 'timeEnd');
            }

            // tambah personId (ikuti gaya tabel lain: varchar)
            if (!Schema::hasColumn('tb_schedule_template', 'personId')) {
                $table->string('personId', 50)->nullable()->after('templateName');
            }

            // buang kolom lama yang tidak dipakai
            foreach (['templatePhase','templateMapping','templatePerson','templateTask'] as $old) {
                if (Schema::hasColumn('tb_schedule_template', $old)) {
                    $table->dropColumn($old);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_schedule_template', function (Blueprint $table) {
            // rollback (opsional—kembalikan seperti semula)
            if (Schema::hasColumn('tb_schedule_template', 'timeStart')) {
                $table->renameColumn('timeStart', 'templateStart');
            }
            if (Schema::hasColumn('tb_schedule_template', 'timeEnd')) {
                $table->renameColumn('timeEnd', 'templateEnd');
            }
            if (Schema::hasColumn('tb_schedule_template', 'personId')) {
                $table->dropColumn('personId');
            }
            // kolom lama tidak saya buat ulang karena nilainya sudah deprecated
        });
    }
};
