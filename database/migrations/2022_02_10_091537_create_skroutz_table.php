<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
     public  function up() {

         Schema::create('skroutz', function (Blueprint $table) {
             $table->integer('product')->index()->unique()->primary();
             $table->string('lang')->nullable()->default('el');
             $table->integer('show')->nullable()->default(0);
         });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public
        function down() {
            Schema::dropIfExists('skroutz');
        }
};
