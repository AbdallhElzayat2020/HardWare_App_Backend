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
        Schema::table('sensor_logs', function (Blueprint $table) {
            $table->double('unoccupied')->nullable() ;
            $table->dropColumn('unoccuipied');

            $table->double('fall_ave')->nullable() ;
            $table->double('stand_ave')->nullable() ;
            $table->double('unoccupied_ave')->nullable();
             $table->string('result')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sensor_logs', function (Blueprint $table) {
            $table->dropColumn('unoccupied');
            $table->dropColumn('result');
            $table->double('unoccuipied') ;

            $table->dropColumn('fall_ave');
            $table->dropColumn('stand_ave');
            $table->dropColumn('unoccupied_ave');

        });
    }
};
