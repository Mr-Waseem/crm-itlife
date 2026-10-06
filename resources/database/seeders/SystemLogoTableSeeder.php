<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemLogo;

class SystemLogoTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $logo = new SystemLogo();
    $logo->image = 'logo.png';
    $logo->save();
  }
}
