<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_schedule_group_phase_activity', function (Blueprint $table) {
            $table->integer('uid', true);               // PK INT AUTO_INCREMENT
            $table->integer('phase_uid');               // -> tb_phase.uid
            $table->integer('task_group_uid');          // -> tb_task_group.uid
            $table->integer('task_group_detail_uid');   // -> tb_task_group_detail.uid
            $table->integer('task_uid')->nullable();    // -> tb_task.uid (opsional)
            $table->smallInteger('sortOrder')->nullable();
            $table->text('activityNote')->nullable();
            $table->string('userName', 100)->nullable();
            $table->timestamps();

            // index dasar
            $table->index('phase_uid');
            $table->index('task_group_uid');
            $table->index('task_group_detail_uid');
            $table->index('task_uid');

            // cegah duplikat detail di phase yang sama
            $table->unique(['phase_uid', 'task_group_detail_uid'], 'uniq_phase_detail');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_schedule_group_phase_activity');
    }
};
