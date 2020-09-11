<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTUsersSocialNetworkTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 't_users_social_network';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('encrypt_id')->nullable();
            $table->foreignId('user_id');
            $table->string('user_social_network_id')->unique();
            $table->foreignId('social_network_id');
            $table->string('social_network_avatar')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                    ->references('id')
                    ->on('t_users')
                    ->onDelete('NO ACTION')
                    ->onUpdate('NO ACTION');

            $table->foreign('social_network_id')
                    ->references('id')
                    ->on('c_social_network')
                    ->onDelete('NO ACTION')
                    ->onUpdate('NO ACTION');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
