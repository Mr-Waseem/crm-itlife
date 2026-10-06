<?php

namespace App\Facades\Settings;

use App\Models\Setting;

class Settings {

     public function data()
     {
          return Setting::first();
     }
}