<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_task_group_detail', function (Blueprint $table) {
            // tetap INT agar FK kompatibel
            $table->increments('uid');

            // FK ke master group & task (keduanya INT unsigned)
            $table->unsignedInteger('group_uid');
            $table->unsignedInteger('task_uid');

            // optional: urutan task di dalam group
            $table->smallInteger('sortOrder')->default(0);

            // konsisten dengan pola existing
            $table->string('userName', 60)->nullable();
            $table->timestamp('lastUpdated')->useCurrent()->useCurrentOnUpdate();

            // constraints & index
            $table->unique(['group_uid', 'task_uid']); // cegah duplikasi pasangan
            $table->index(['group_uid', 'sortOrder']);
            $table->index('task_uid');

            $table->foreign('group_uid')
                  ->references('uid')->on('tb_task_group')
                  ->onDelete('cascade');

            $table->foreign('task_uid')
                  ->references('uid')->on('tb_task')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_task_group_detail');
    }
};
