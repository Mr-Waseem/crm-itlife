<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('parties', 'party_email')) {
            Schema::table('parties', function (Blueprint $table) {
                $table->string('party_email')->nullable()->after('party_name');
            });
        }

        if (!Schema::hasColumn('parties', 'phone_normalized')) {
            Schema::table('parties', function (Blueprint $table) {
                $table->string('phone_normalized', 30)->nullable()->after('phone')->index();
            });
        }

        // Customer login emails are the best source for the new optional party email.
        // Do not add a unique constraint: contacts can legitimately share an email.
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

        DB::table('parties')
            ->whereNotNull('phone')
            ->orderBy('id')
            ->select(['id', 'phone'])
            ->chunkById(200, function ($parties) {
                foreach ($parties as $party) {
                    $normalized = preg_replace('/\D+/', '', (string) $party->phone);
                    if (str_starts_with($normalized, '0092')) {
                        $normalized = substr($normalized, 2);
                    } elseif (str_starts_with($normalized, '03') && strlen($normalized) === 11) {
                        $normalized = '92'.substr($normalized, 1);
                    }

                    DB::table('parties')->where('id', $party->id)->update([
                        'phone_normalized' => $normalized ?: null,
                    ]);
                }
            });
    }

    public function down()
    {
        if (Schema::hasColumn('parties', 'phone_normalized')) {
            Schema::table('parties', function (Blueprint $table) {
                $table->dropIndex(['phone_normalized']);
                $table->dropColumn('phone_normalized');
            });
        }

        // party_email existed in some installations before this migration;
        // retain it (and its backfilled contact data) on rollback.
    }
};
