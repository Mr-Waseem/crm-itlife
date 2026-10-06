<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Older customer logins use the email column as a username/code. Preserve
        // that legacy value in parties; strict email validation applies only to
        // new lead emails and new customer-login conversion.
        $users = DB::table('users')
            ->whereNotNull('party_id')
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->orderByDesc('status')
            ->orderBy('id')
            ->get(['id', 'party_id', 'email']);

        $seenParties = [];
        foreach ($users as $user) {
            if (isset($seenParties[$user->party_id])) {
                continue;
            }

            $email = strtolower(trim($user->email));
            if ($email !== '') {
                DB::table('parties')
                    ->where('id', $user->party_id)
                    ->whereNull('party_email')
                    ->update(['party_email' => $email]);
            }

            $seenParties[$user->party_id] = true;
        }
    }

    public function down()
    {
        // Backfilled contact data is deliberately retained on rollback.
    }
};
