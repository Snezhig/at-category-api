<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function __construct(
        private CategorySeeder $categorySeeder
    )
    {
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->categorySeeder->run();
    }
}
