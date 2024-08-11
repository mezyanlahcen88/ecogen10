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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('deliverer');
            $table->string('delivery_method');
            $table->text('comment')->nullable();
            $table->date('delivery_date');
            $table->boolean('active')->default(1);
            $table->foreignUuid('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignUuid('command_id')->constrained('commands')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('delivery_details', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->integer('qty_livred');
            $table->foreignUuid('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignUuid('command_id')->constrained('commands')->onDelete('cascade');
            $table->foreignUuid('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignUuid('delivery_id')->constrained('deliveries')->onDelete('cascade');
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('delivery_details');
    }
};

