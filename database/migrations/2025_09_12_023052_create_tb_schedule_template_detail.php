<?php

// database/migrations/2025_09_11_000002_create_tb_schedule_template_detail.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_schedule_template_detail', function (Blueprint $table) {
            $table->bigIncrements('uid');

            // tipe sesuaikan dengan kolom sumber:
            // uid template = int → unsignedInteger
            $table->unsignedInteger('template_uid')->index();

            // asumsikan tb_task_group_detail pk = BIGINT (ubah jika perlu)
            $table->unsignedBigInteger('task_group_detail_uid')->index();

            $table->smallInteger('sortOrder')->nullable();
            $table->timestamps();

            $table->unique(['template_uid','task_group_detail_uid'], 'uq_template_taskdetail');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_schedule_template_detail');
    }
};
