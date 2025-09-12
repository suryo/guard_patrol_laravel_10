<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tb_schedule_template', function (Blueprint $table) {
            $table->unique('templateId', 'uq_tb_schedule_template_templateId');
        });
    }
    public function down(): void
    {
        Schema::table('tb_schedule_template', function (Blueprint $table) {
            $table->dropUnique('uq_tb_schedule_template_templateId');
        });
    }
};
