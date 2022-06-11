<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            $table->string('chat_id')->unique();
            $table->string('username')->nullable();
            $table->text('first_name')->nullable();
            $table->text('last_name')->nullable();
            $table->text('bio')->nullable();


            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chats');
    }
};
