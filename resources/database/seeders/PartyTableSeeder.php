<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Party;

class PartyTableSeeder extends Seeder
{
    public function run()
    {
        $party = new Party();
        $party->account_group_id = 7;
        $party->shop_id = 2;
        $party->account_type = 'Client';
        $party->code = '0';
        $party->party_name = 'CASH IN HAND';
        $party->phone = '03XXXXXXXXX';
        $party->ntn = '9876543-2';
        $party->strn = '1234567-8';
        $party->city = 'Lahore';
        $party->address = 'Walking Costomer';
        $party->save();

        $party = new Party();
        $party->account_group_id = 10;
        $party->shop_id = 2;
        $party->account_type = 'ASSET';
        $party->code = '0';
        $party->party_name = 'PURCHASE ACCOUNT';
        $party->phone = '0321 8080808';
        $party->ntn = '9876543-4';
        $party->strn = '1234567-0';
        $party->city = 'Lahore';
        $party->address = 'Kala Shah Kaku, Lahore.';
        $party->fatrate = '105';
        $party->save();

        $party = new Party();
        $party->account_group_id = 10;
        $party->shop_id = 2;
        $party->account_type = 'ASSET';
        $party->code = '0';
        $party->party_name = 'SALE ACCOUNT';
        $party->phone = '0321 8080808';
        $party->ntn = '9876543-4';
        $party->strn = '1234567-0';
        $party->city = 'Lahore';
        $party->address = 'Kala Shah Kaku, Lahore.';
        $party->fatrate = '105';
        $party->save();
    }
}
