<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->string('slug')
                ->nullable()
                ->unique();
            $table->foreignId('user_id');
            $table->foreignId('channel_id');
            $table->foreignId('best_reply_id')->nullable()->constrained('replies')->onDelete('set null');
            $table->unsignedInteger('replies_count')->default(0);
            $table->string('title');
            $table->text('body');
            $table->boolean('locked')->default(false);
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
        Schema::dropIfExists('threads');
    }
}
