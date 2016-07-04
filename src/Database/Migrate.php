<?php

namespace Montopolis\MagicAuth\Database;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Migrate extends Migration
{
    /**
     *
     */
    public function up()
    {
        \Schema::create('montopolis_magic_auth_keys', function (Blueprint $table) {
            $table->increments('id');

            $table->string('email', 255);
            $table->string('token', 100);
            $table->string('ip_address', 50);
            $table->string('key', 100);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_valid')->default(0);
            $table->unsignedInteger('attempts')->default(0);

            $table->timestamps();
        });
    }

    /**
     *
     */
    public function down()
    {
        \Schema::drop('montopolis_magic_auth_keys');
    }
}
