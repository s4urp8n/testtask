<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MailChimpMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_chimp_members', function (Blueprint $table) {

            $table->string('id', 36);

            $table->string('email_address', 180)
                  ->index();

            $table->string('status', 50)
                  ->index();

            $table->string('list_id', 36)
                  ->index();

            $table->primary('id');

            $table->foreign('list_id')
                  ->references('mail_chimp_id')
                  ->on('mail_chimp_lists')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_chimp_members');
    }
}
