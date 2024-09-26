<?php

namespace Database\Seeders;

use App\Models\Numerotation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class NumerotationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $path = database_path('sql/numerotations.sql');
        $sql = File::get($path);
        DB::unprepared($sql);
    }
}
