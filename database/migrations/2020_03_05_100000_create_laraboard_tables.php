<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLaraboardTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('laraboard.table_prefix') . 'posts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');

            $table->integer('parent_id')->nullable();
            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
            $table->integer('depth')->nullable();

            $table->enum('type', ['Category','Board','Thread','Reply']);
            $table->enum('status', ['Open','Closed','Deleted'])->default('Open');
            $table->boolean('private')->default(false);
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->text('body');

            $table->ipAddress('ip');

            $table->timestamps();

            $table->softDeletes();

            $table->integer('updated_by')->nullable()->unsigned();
        });

        Schema::create(config('laraboard.table_prefix') . 'subscriptions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');

            $table->integer('post_id');

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
        Schema::dropIfExists(config('laraboard.table_prefix') . 'posts');
        Schema::dropIfExists(config('laraboard.table_prefix') . 'subscriptions');
    }
}
