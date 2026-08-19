<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * syndicate_splash_sliders shipped from the old CMS with `id` as a plain
     * NOT NULL int - no AUTO_INCREMENT, no PRIMARY KEY. The table has always
     * been empty, so there's no data to renumber; just promote id to a
     * proper AUTO_INCREMENT PRIMARY KEY.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE syndicate_splash_sliders MODIFY id INT NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE syndicate_splash_sliders DROP PRIMARY KEY, MODIFY id INT NOT NULL');
    }
};
