<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('chat_challenges', function (Blueprint $table) {
            $table->boolean('is_skipped')->nullable();
            $table->string('did_like')->nullable()->change();
            $table->boolean('would_use')->nullable()->change();
            $table->text('commentary')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('chat_challenges', function (Blueprint $table) {
            $table->dropColumn('is_skipped');
        });
    }
};
