<?php

namespace Tests;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Lararole\Models\Role;
use PHPUnit\Framework\TestCase as Base;
use Tests\Models\User;
use Faker\Factory as Faker;

abstract class TestCase extends Base
{
    protected $users;

    protected function setUp(): void
    {
        parent::setUp();

        $config = require __DIR__ . '/config/database.php';

        $db = new DB;
        $db->addConnection($config[getenv('DB') ?: 'mysql']);
        $db->setAsGlobal();
        $db->bootEloquent();

        $this->migrate();

        $this->seed();
    }

    /**
     * Migrate the database.
     *
     * @return void
     */
    protected function migrate()
    {
        DB::schema()->dropAllTables();

        DB::schema()->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->softDeletes();
        });
    }

    /**
     * Seed the database.
     *
     * @return void
     */
    protected function seed()
    {
        Model::unguard();

        $faker = Faker::create();

        User::create(['email' => $faker->unique()->safeEmail]);

        Role::create(['name' => 'supper_admin']);
        Role::create(['name' => $faker->jobTitle]);
        Role::create(['name' => $faker->jobTitle]);
        Role::create(['name' => $faker->jobTitle]);
        Role::create(['name' => $faker->jobTitle]);
        Role::create(['name' => $faker->jobTitle]);

        Model::reguard();
    }
}
