<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_users', function (Blueprint $t) {
            $t->increments('uid');
            $t->string('userId', 8)->unique();
            $t->string('userName', 60);
            $t->string('userPassword', 255);
            $t->string('userLevel', 2); // misal 'A','S','U'
            $t->string('hashMobile', 255)->nullable();
            $t->string('hashWeb', 255)->nullable();
            $t->string('userEmail', 120)->nullable();
            $t->timestamp('lastUpdated')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('tb_users'); }
};
