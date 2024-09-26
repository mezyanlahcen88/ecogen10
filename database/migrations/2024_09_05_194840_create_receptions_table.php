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
        Schema::create('receptions', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('ref')->unique();
            $table->double('HT',8,2);
            $table->integer('TVA');
            $table->double('TTTC',8,2);
            $table->double('total_payant',8,2);
            $table->double('total_restant',8,2);
            $table->string('status');
            $table->timestamp('status_date');
            $table->boolean('active')->default(1);
            $table->foreignUuid('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->softDeletes();
        });

        Schema::create('product_reception', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->foreignUuid('reception_id')->constrained('receptions')->onDelete('cascade');
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
        Schema::dropIfExists('receptions');
    }
};

