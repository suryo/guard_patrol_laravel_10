<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_task_list', function (Blueprint $t) {
            $t->increments('uid');
            $t->string('taskId', 11);
            $t->string('scheduleId', 20);
            $t->string('phaseId', 20)->nullable();
            $t->string('taskStatus', 1)->default('0'); // 0/1
            $t->string('userName', 60)->nullable();
            $t->timestamp('lastUpdated')->useCurrent();
            $t->index(['taskId','scheduleId']);
        });
    }
    public function down(): void { Schema::dropIfExists('tb_task_list'); }
};

