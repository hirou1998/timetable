<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('user_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('first_start_time');
            $table->time('first_end_time');
            $table->time('second_start_time');
            $table->time('second_end_time');
            $table->time('third_start_time');
            $table->time('third_end_time');
            $table->time('fourth_start_time');
            $table->time('fourth_end_time');
            $table->time('fifth_start_time');
            $table->time('fifth_end_time');
            $table->time('sixth_start_time');
            $table->time('sixth_end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
