<?php

use App\Models\PushInbox;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_push_inbox', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(PushInbox::class);
            $table->boolean('is_read')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->unique(['user_id', 'push_inbox_id']);
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('push_inbox_id');
            $table->index('is_read');
            $table->index('is_hidden');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_push_inbox');
    }
};
