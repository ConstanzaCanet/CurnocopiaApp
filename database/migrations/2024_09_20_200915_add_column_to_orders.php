<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('uuid');
            $table->string('preference');
            $table->text('api_response')->nullable();
            $table->integer('zip');
        });
    }


    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->dropColumn('preference');
            $table->dropColumn('api_response');
            $table->dropColumn('zip');
        });
    }
};
