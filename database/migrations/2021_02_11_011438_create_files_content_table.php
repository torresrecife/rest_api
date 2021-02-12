<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFilesContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_content', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('file_id');

            $table->foreign('file_id')
                ->references('id')
                ->on('files')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        DB::statement("ALTER TABLE files_content ADD content MEDIUMBLOB");

        Schema::table('files_content', function (Blueprint $table) {
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
        Schema::dropIfExists('files_content');
    }
}
