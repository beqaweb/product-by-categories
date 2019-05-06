<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryFieldValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_field_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('value')->nullable();
            $table->bigInteger('category_field_id')->unsigned();
            $table->bigInteger('valuable_id')->unsigned();
            $table->string('valuable_type');
            $table->timestamps();

            $table->foreign('category_field_id')
                ->references('id')
                ->on('category_fields')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_field_values');
    }
}
