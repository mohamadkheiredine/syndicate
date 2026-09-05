<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Renames match the corrected UserPush model: users_id -> user_id,
     * registration_id -> player_id (renaming preserves existing data,
     * unlike drop+add). New nullable columns match what the model's
     * $fillable now expects.
     *
     */
    public function up()
    {
        Schema::table('user_push', function (Blueprint $table) {
            $table->renameColumn('users_id', 'user_id');
            $table->renameColumn('registration_id', 'player_id');
            $table->string('device_model')->nullable()->after('player_id');
            $table->string('device_type')->nullable()->after('device_model');
            $table->string('identifier')->nullable()->after('device_type');
            $table->text('response')->nullable()->after('identifier');
            $table->string('language')->nullable()->after('response');
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::table('user_push', function (Blueprint $table) {
            $table->dropColumn(['device_model', 'device_type', 'identifier', 'response', 'language']);
            $table->renameColumn('player_id', 'registration_id');
            $table->renameColumn('user_id', 'users_id');
        });
    }
};
