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
        Schema::table('sensors', function (Blueprint $table) {
            $table->dropForeign('sensors_room_id_foreign');
            $table->foreign('room_id')
                ->references('id')->on('rooms')
                ->onDelete('cascade');
        });
        Schema::table('sensor_logs', function (Blueprint $table) {
            $table->dropForeign('sensor_logs_sensor_id_foreign');
            $table->foreign('sensor_id')
                ->references('id')->on('sensors')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sensors', function (Blueprint $table) {
            //
        });
    }
};
