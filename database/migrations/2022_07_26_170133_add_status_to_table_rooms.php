<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->tinyInteger('light_status')->nullable();
            $table->tinyInteger('button_status')->nullable();
            $table->double('motion')->nullable();
            $table->timestamp('last_log_created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('light_status');
            $table->dropColumn('button_status');
            $table->dropColumn('motion');
            $table->dropColumn('last_log_created_at');
        });
    }
};
