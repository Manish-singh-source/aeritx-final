<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('gst_number', 50)->nullable()->after('phone');
            $table->string('aadhar_card', 50)->nullable()->after('gst_number');
            $table->string('pan_card', 50)->nullable()->after('aadhar_card');
        });
    }

    public function down()
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn(['gst_number', 'aadhar_card', 'pan_card']);
        });
    }
};
