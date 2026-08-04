<?php

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
        Schema::create('user_push', function (Blueprint $table) {
            $table->id();
            $table->string('player_id');
            $table->string('device_model');
            $table->string('device_type');
            $table->string('identifier');
            $table->text('response')->nullable();
            $table->string('language', 2);
            $table->foreignIdFor(User::class)->constrained();
            $table->timestamps();

            // Indexes
            $table->index('player_id');
            $table->index('identifier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_push');
    }
};
