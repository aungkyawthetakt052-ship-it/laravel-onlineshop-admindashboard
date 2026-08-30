<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN user_type ENUM('user', 'admin', 'superadmin') DEFAULT 'user'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN user_type ENUM('user', 'admin') DEFAULT 'user'");
    }
};
