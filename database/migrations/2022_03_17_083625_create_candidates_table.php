<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('email');
            $table->longText('mail_content_raw')->nullable();
            $table->longText('mail_content_html')->nullable();
            $table->string('year_of_experience')->default(0);
            $table->json('skills')->nullable();

            $table->unsignedBigInteger('language_id')->nullable();
            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidates');
    }
}
