<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_task_template', function (Blueprint $t) {
            $t->increments('uid');
            $t->string('taskId', 11);
            $t->string('taskName', 60);
            $t->string('taskNote', 120)->nullable();
            $t->timestamp('lastUpdated')->useCurrent();
            $t->index(['taskId']);
        });
    }
    public function down(): void { Schema::dropIfExists('tb_task_template'); }
};
