<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name');
            $table->integer('age');
            $table->string('place');
            $table->string('tel');
            $table->text('intro');
            $table->text('adress');
            $table->string('socialFB')->nullable()->change();
            $table->string('socialLI')->nullable()->change();
            $table->string('socialIG')->nullable()->change();
            $table->string('socialTW')->nullable()->change();
            $table->string('socialLT')->nullable()->change();
            $table->string('socialTE')->nullable()->change();
            $table->string('notes')->nullable()->change();
	        $table->integer('level')->nullable()->change();
            $table->boolean('active');
            $table->timestamps();
        });  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
