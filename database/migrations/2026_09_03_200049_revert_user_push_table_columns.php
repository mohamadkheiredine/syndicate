<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reverts the mistaken rename in 2026_09_03_155556 - that migration
     * matched an unrelated generic-scaffold controller's assumed columns,
     * not the real old CMS schema (App\Models\Api\UserPush in both old
     * source projects: $fillable = ['users_id','registration_id','language_id']).
     * Renaming (not drop+add) preserves the 1730+ real rows.
     *
     */
    public function up()
    {
        Schema::table('user_push', function (Blueprint $table) {
            $table->renameColumn('user_id', 'users_id');
            $table->renameColumn('player_id', 'registration_id');
            $table->dropColumn(['device_model', 'device_type', 'identifier', 'response', 'language']);
            $table->unsignedBigInteger('language_id')->nullable()->after('registration_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::table('user_push', function (Blueprint $table) {
            $table->dropColumn('language_id');
            $table->string('device_model')->nullable();
            $table->string('device_type')->nullable();
            $table->string('identifier')->nullable();
            $table->text('response')->nullable();
            $table->string('language')->nullable();
            $table->renameColumn('registration_id', 'player_id');
            $table->renameColumn('users_id', 'user_id');
        });
    }
};
