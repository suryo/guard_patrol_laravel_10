<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_schedule_template', function (Blueprint $t) {
            $t->increments('uid');
            $t->string('templateId', 11)->unique();
            $t->string('templateName', 60);
            $t->tinyInteger('templatePhase')->default(0);
            $t->tinyInteger('templateMapping')->default(0);
            $t->tinyInteger('templatePerson')->default(0);
            $t->dateTime('templateStart')->nullable();
            $t->dateTime('templateEnd')->nullable();
            $t->text('templateTask')->nullable();
            $t->string('userName', 60)->nullable();
            $t->timestamp('lastUpdated')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('tb_schedule_template'); }
};

