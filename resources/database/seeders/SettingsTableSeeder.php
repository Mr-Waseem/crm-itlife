<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$setting = new Setting();
		//$setting->system_name = 'ACCOUNTS & STOCK SOLUTIONS';
		$setting->system_name = 'IT Life';
		//$setting->title = 'ACCOUNTS & STOCK SOLUTIONS';
		$setting->title = 'IT Life';
		//$setting->address = 'WAHDAT ROAD, MANSOORA DEGREE COLLEGE';
		$setting->address = 'Farid Kot Road, Lahore.';
		$setting->phone = '0321 4197290';
		$setting->email = 'info@itlife.com.pk';
		$setting->currency = 'PKR';
		$setting->city = '03-00-4281-958-11';
		$setting->state = '4281958-0';
		$setting->ntn = '4281958-0';
		$setting->country = 'Pakistan';
		$setting->footer_line = '© 2019 CLOUD ACCOUNTING ENTERPRISE. Developed By Itlife.com.pk';
		$setting->save();
	}
}
