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
            $table->boolean('edited')->default(false);
            $table->year('current_year')->nullable();
            $table->enum('current_semester', ['1学期', '2学期', '3学期'])->nullable();
            $table->time('first_start_time')->default('9:00');
            $table->time('first_end_time')->default('10:30');
            $table->time('second_start_time')->default('10:40');
            $table->time('second_end_time')->default('12:10');
            $table->time('third_start_time')->default('13:00');
            $table->time('third_end_time')->default('14:30');
            $table->time('fourth_start_time')->default('14:45');
            $table->time('fourth_end_time')->default('16:15');
            $table->time('fifth_start_time')->default('16:30');
            $table->time('fifth_end_time')->default('18:30');
            $table->time('sixth_start_time')->default('18:15');
            $table->time('sixth_end_time')->default('19:45');
            $table->year('enter_year')->nullable();
            $table->year('graduation_year')->nullable();
            $table->string('university')->nullable();
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
