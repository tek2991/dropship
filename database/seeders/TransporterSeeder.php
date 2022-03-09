<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class TransporterSeeder extends Seeder
{
        /**
     * The current Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = Faker::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory(100)->create([
            'gender' => 'na',
            'name' => $this->faker->unique()->company(),
        ]);
        $users->each(function ($user) {
            $user->assignRole('transporter');
            $user->transporter()->create();
        });
    }
}
