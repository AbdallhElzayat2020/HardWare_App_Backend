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
        Schema::table('hospital_group_staff', function (Blueprint $table) {
            $table->dropForeign('hospital_group_staff_hospital_group_id_foreign');
            $table->foreign('hospital_group_id')
                ->references('id')->on('hospital_groups')
                ->onDelete('cascade');
        });

        Schema::table('hospital_group_room', function (Blueprint $table) {
            $table->dropForeign('hospital_group_room_hospital_group_id_foreign');
            $table->foreign('hospital_group_id')
                ->references('id')->on('hospital_groups')
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
        
    }
};
