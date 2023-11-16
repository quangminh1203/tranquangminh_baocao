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
        Schema::create('link', function (Blueprint $table) {
            $table->id();
            $table->string('link', 1000);
            $table->string('type', 1000);
            $table->unsignedInteger('table_id');
            //             `link` varchar(1000) NOT NULL,
            //   `type` varchar(255) NOT NULL,
            //   `table_id` int(11) NOT NULL
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link');
    }
};
