<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_phase', function (Blueprint $t) {
            $t->increments('uid');
            $t->string('phaseId', 20)->unique();
            $t->date('phaseDate')->nullable();
            $t->timestamp('lastUpdated')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('tb_phase'); }
};

