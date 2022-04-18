<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->engine="InnoDB";
            $table->increments('id');
            $table->integer('id_cliente')->unsigned();
            $table->string('tipo_boleta',20);
            $table->date('fecha');
            $table->string('numero_factura',20);
            $table->string('timbrado',20);
            $table->string('tipo_pago',20);
            $table->string('entregado',2);
            $table->string('activo',2);
            
            $table->timestamps();

            $table->foreign('id_cliente')->references('id')->on('cliente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
