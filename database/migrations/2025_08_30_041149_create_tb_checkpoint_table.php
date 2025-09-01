<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_checkpoint', function (Blueprint $t) {
            $t->increments('uid');
            $t->string('checkpointId', 20)->unique();
            $t->string('checkpointName', 60);
            $t->decimal('latitude', 10, 6)->nullable();
            $t->decimal('longitude', 10, 6)->nullable();
            $t->string('address', 255)->nullable();
            $t->text('note')->nullable();
            $t->timestamp('lastUpdated')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('tb_checkpoint'); }
};

