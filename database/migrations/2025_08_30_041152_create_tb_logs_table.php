<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_logs', function (Blueprint $t) {
            $t->increments('uid');
            $t->string('activity', 30)->nullable();
            $t->string('category', 30)->nullable();
            $t->string('userName', 60)->nullable();
            $t->text('note')->nullable();
            $t->timestamp('lastUpdated')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('tb_logs'); }
};

