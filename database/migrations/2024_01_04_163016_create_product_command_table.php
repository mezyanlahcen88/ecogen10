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
        Schema::create('product_command', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->foreignUuid('command_id')->constrained('commands')->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained('products')->onDelete('cascade');
            $table->string('designation');
            $table->integer('quantity');
            $table->integer('qty_livred');
            $table->integer('qty_reste');
            $table->double('price',8,2);
            $table->integer('remise');
            $table->integer('total_remise');
            $table->double('TOTAL_HT',8,2);
            $table->integer('TVA');
            $table->integer('TOTAL_TVA');
            $table->double('TOTAL_TTC',8,2);
            $table->string('unite');
            $table->timestamps();
            $table->softDeletes();
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_command');
    }
};

