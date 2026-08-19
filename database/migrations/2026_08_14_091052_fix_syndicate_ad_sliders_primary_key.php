<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * syndicate_ad_sliders shipped from the old CMS with `id` as a plain
     * NOT NULL int - no AUTO_INCREMENT, no PRIMARY KEY - and unlike splash
     * sliders it actually got used: all 3 real rows have id=0, making them
     * indistinguishable at the DB level. Renumber via a temporary
     * auto-increment column (preserving all 3 rows, just giving them real
     * unique ids), then promote id itself to a proper AUTO_INCREMENT
     * PRIMARY KEY.
     */
    public function up(): void
    {
        Schema::table('syndicate_ad_sliders', function (Blueprint $table) {
            $table->unsignedInteger('temp_id')->autoIncrement()->first();
        });

        DB::statement('UPDATE syndicate_ad_sliders SET id = temp_id');

        Schema::table('syndicate_ad_sliders', function (Blueprint $table) {
            $table->dropColumn('temp_id');
        });

        DB::statement('ALTER TABLE syndicate_ad_sliders MODIFY id INT NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE syndicate_ad_sliders DROP PRIMARY KEY, MODIFY id INT NOT NULL');
    }
};
