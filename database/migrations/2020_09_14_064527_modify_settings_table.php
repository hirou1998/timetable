<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->time('first_start_time')->default('9:00')->change();
            $table->time('first_end_time')->default('10:30')->change();
            $table->time('second_start_time')->default('10:40')->change();
            $table->time('second_end_time')->default('12:10')->change();
            $table->time('third_start_time')->default('13:00')->change();
            $table->time('third_end_time')->default('14:30')->change();
            $table->time('fourth_start_time')->default('14:45')->change();
            $table->time('fourth_end_time')->default('14:45')->change();
            $table->time('fifth_start_time')->default('16:30')->change();
            $table->time('fifth_end_time')->default('18:00')->change();
            $table->time('sixth_start_time')->default('18:15')->change();
            $table->time('sixth_end_time')->default('19:45')->change();
            $table->string('enter_year')->nullable()->aftre('university');
            $table->string('graduation_year')->nullable()->aftre('enter_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->time('first_start_time')->default(null)->change();
            $table->time('first_end_time')->default(null)->change();
            $table->time('second_start_time')->default(null)->change();
            $table->time('second_end_time')->default(null)->change();
            $table->time('third_start_time')->default(null)->change();
            $table->time('third_end_time')->default(null)->change();
            $table->time('fourth_start_time')->default(null)->change();
            $table->time('fourth_end_time')->default(null)->change();
            $table->time('fifth_start_time')->default(null)->change();
            $table->time('fifth_end_time')->default(null)->change();
            $table->time('sixth_start_time')->default(null)->change();
            $table->time('sixth_end_time')->default(null)->change();
            $table->dropColumn('enter_year');
            $table->dropColumn('graduation_year');
        });
    }
}
