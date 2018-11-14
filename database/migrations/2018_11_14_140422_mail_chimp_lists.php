<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MailChimpLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_chimp_lists', function (Blueprint $table) {

            $table->string('id', 36);

            $table->string('mail_chimp_id', 36)
                  ->index();

            $table->string('name', 180)
                  ->index();

            $table->text('campaign_defaults');

            $table->text('contact');

            $table->boolean('email_type_option')
                  ->index();

            $table->string('notify_on_subscribe', 180)
                  ->nullable()
                  ->index();

            $table->string('notify_on_unsubscribe', 180)
                  ->nullable()
                  ->index();

            $table->string('permission_reminder', 180)
                  ->nullable()
                  ->index();

            $table->boolean('use_archive_bar')
                  ->nullable()
                  ->index();

            $table->string('visibility', 3)
                  ->nullable()
                  ->index();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_chimp_lists');
    }
}
