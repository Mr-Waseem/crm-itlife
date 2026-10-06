<?php

namespace Tests\Feature;

use App\Models\LeadDetail;
use App\Models\Party;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LeadManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'app.party_shop_id' => 2,
            'app.timezone' => 'Asia/Karachi',
        ]);
        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('showpassword')->default('');
            $table->unsignedInteger('shop_id')->nullable();
            $table->unsignedInteger('biller_id')->default(0);
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('role')->nullable();
            $table->integer('status')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->integer('party_id')->nullable();
            $table->string('type')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('parties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id');
            $table->string('account_type')->nullable();
            $table->integer('code')->nullable();
            $table->string('party_name');
            $table->string('party_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_normalized')->nullable();
            $table->string('address')->nullable();
            $table->integer('account_group_id')->nullable();
            $table->integer('account_group_id2')->nullable();
            $table->integer('account_group_id3')->nullable();
            $table->integer('status')->nullable();
            $table->string('type')->nullable();
            $table->integer('show_products')->nullable();
            $table->string('role')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('lead_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('party_id');
            $table->string('lead_status');
            $table->string('activity_type');
            $table->date('follow_up_date')->nullable();
            $table->time('follow_up_time')->nullable();
            $table->unsignedInteger('assigned_to')->nullable();
            $table->string('assigned_to_name')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedBigInteger('supersedes_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('voucher_rights', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('voucher_name');
            $table->string('right_name');
            $table->timestamps();
        });

        Schema::create('account_groups3', function (Blueprint $table) {
            $table->id();
            $table->integer('code');
            $table->string('name');
            $table->integer('account_group1_id');
            $table->integer('account_group2_id');
            $table->timestamps();
        });

        $this->withoutMiddleware([
            \App\Http\Middleware\CheckMenuAccess::class,
            \App\Http\Middleware\SubMenuMiddleware::class,
            \App\Http\Middleware\SubMenuMiddleware2::class,
        ]);
    }

    public function test_new_lead_creates_one_party_and_one_open_follow_up()
    {
        $user = $this->adminWithRights();

        $response = $this->actingAs($user)->postJson('/leads', [
            'party_name' => 'Test Lead',
            'phone' => '0300-1234567',
            'party_email' => 'lead@example.test',
            'address' => 'Lahore',
            'follow_up_date' => today()->addDay()->toDateString(),
            'assigned_to' => $user->id,
        ]);

        $response->assertOk()->assertJsonPath('message', 'Lead saved successfully.');
        $this->assertDatabaseHas('parties', [
            'party_name' => 'Test Lead',
            'role' => 'Lead',
            'type' => 'Lead',
            'phone_normalized' => '923001234567',
        ]);
        $this->assertDatabaseHas('lead_details', [
            'lead_status' => 'New',
            'activity_type' => 'Lead Created',
            'assigned_to' => $user->id,
        ]);
    }

    public function test_calendar_calculates_overdue_without_storing_it()
    {
        Carbon::setTestNow(Carbon::parse('2026-09-17 12:00:00', 'Asia/Karachi'));
        $user = $this->adminWithRights();
        $party = Party::create([
            'shop_id' => 2,
            'party_name' => 'Overdue Lead',
            'phone' => '03001234567',
            'role' => 'Lead',
            'type' => 'Lead',
            'status' => 1,
        ]);
        LeadDetail::create([
            'party_id' => $party->id,
            'lead_status' => 'Contacted',
            'activity_type' => 'Call',
            'follow_up_date' => '2026-09-16',
            'assigned_to' => $user->id,
            'assigned_to_name' => $user->name,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->getJson('/leads/events?start=2026-09-01&end=2026-10-01&scope=all');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.allDay', true)
            ->assertJsonPath('0.is_overdue', true)
            ->assertJsonPath('0.lead_status', 'Contacted');
        $this->assertFalse(Schema::hasColumn('lead_details', 'is_overdue'));
        Carbon::setTestNow();
    }

    public function test_duplicate_phone_requires_reuse_or_explicit_override()
    {
        $user = $this->adminWithRights();
        Party::create([
            'shop_id' => 2,
            'party_name' => 'Existing Party',
            'phone' => '03001234567',
            'phone_normalized' => '923001234567',
            'role' => 'Customer',
            'type' => 'Un Registered',
            'status' => 1,
        ]);

        $payload = [
            'party_name' => 'Possible Duplicate',
            'phone' => '+92 300 1234567',
            'address' => 'Lahore',
            'follow_up_date' => today()->addDay()->toDateString(),
            'assigned_to' => $user->id,
        ];

        $this->actingAs($user)->postJson('/leads', $payload)
            ->assertStatus(409)
            ->assertJsonCount(1, 'duplicates');

        $this->actingAs($user)->postJson('/leads', $payload + ['force_duplicate' => true])
            ->assertOk();
        $this->assertSame(2, Party::count());
    }

    public function test_follow_up_completes_the_old_event_and_keeps_only_one_open_event()
    {
        $user = $this->adminWithRights();
        $party = Party::create([
            'shop_id' => 2,
            'party_name' => 'Follow-up Lead',
            'phone' => '03001234567',
            'role' => 'Lead',
            'type' => 'Lead',
            'status' => 1,
        ]);
        LeadDetail::create([
            'party_id' => $party->id,
            'lead_status' => 'New',
            'activity_type' => 'Lead Created',
            'follow_up_date' => today()->toDateString(),
            'assigned_to' => $user->id,
            'assigned_to_name' => $user->name,
            'created_by' => $user->id,
        ]);

        $this->actingAs($user)->postJson('/leads/'.$party->id.'/follow-ups', [
            'lead_status' => 'Contacted',
            'activity_type' => 'Call',
            'follow_up_date' => today()->addDays(2)->toDateString(),
            'assigned_to' => $user->id,
            'remarks' => 'Call again in two days.',
        ])->assertOk();

        $this->assertSame(2, LeadDetail::where('party_id', $party->id)->count());
        $this->assertSame(1, LeadDetail::where('party_id', $party->id)->open()->count());
        $this->assertDatabaseHas('lead_details', [
            'party_id' => $party->id,
            'lead_status' => 'Contacted',
            'activity_type' => 'Call',
        ]);
    }

    public function test_guided_conversion_reuses_the_party_and_creates_customer_login()
    {
        $user = $this->adminWithRights();
        $groupId = DB::table('account_groups3')->insertGetId([
            'code' => 110,
            'name' => 'Customers',
            'account_group1_id' => 1,
            'account_group2_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $party = Party::create([
            'shop_id' => 2,
            'party_name' => 'Convert Me',
            'phone' => '03001234567',
            'party_email' => 'lead@example.test',
            'address' => 'Lahore',
            'role' => 'Lead',
            'type' => 'Lead',
            'status' => 1,
        ]);
        LeadDetail::create([
            'party_id' => $party->id,
            'lead_status' => 'Interested',
            'activity_type' => 'Call',
            'follow_up_date' => today()->addDay()->toDateString(),
            'assigned_to' => $user->id,
            'assigned_to_name' => $user->name,
            'created_by' => $user->id,
        ]);

        $this->actingAs($user)->postJson('/leads/'.$party->id.'/convert', [
            'account_group_id3' => $groupId,
            'registration_type' => 'Un Registered',
            'email' => 'customer@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'show_products' => true,
        ])->assertOk();

        $this->assertDatabaseHas('parties', [
            'id' => $party->id,
            'role' => 'Customer',
            'type' => 'Un Registered',
            'account_group_id3' => $groupId,
        ]);
        $this->assertDatabaseHas('users', [
            'party_id' => $party->id,
            'email' => 'customer@example.test',
            'type' => 'CUSTOMER',
        ]);
        $this->assertDatabaseHas('lead_details', [
            'party_id' => $party->id,
            'lead_status' => 'Converted',
            'activity_type' => 'Conversion',
        ]);
        $this->assertSame(0, LeadDetail::where('party_id', $party->id)->open()->count());
    }

    public function test_normal_user_only_receives_assigned_calendar_events()
    {
        $manager = $this->adminWithRights();
        $staff = User::create([
            'name' => 'Sales Staff',
            'email' => 'staff@example.test',
            'password' => bcrypt('password'),
            'showpassword' => '',
            'biller_id' => 0,
            'role' => 'Normal User',
            'status' => 1,
            'warehouse_id' => 1,
        ]);

        foreach ([$manager, $staff] as $assignee) {
            $party = Party::create([
                'shop_id' => 2,
                'party_name' => $assignee->name.' Lead',
                'phone' => '0300123456'.$assignee->id,
                'role' => 'Lead',
                'type' => 'Lead',
                'status' => 1,
            ]);
            LeadDetail::create([
                'party_id' => $party->id,
                'lead_status' => 'New',
                'activity_type' => 'Lead Created',
                'follow_up_date' => today()->addDay()->toDateString(),
                'assigned_to' => $assignee->id,
                'assigned_to_name' => $assignee->name,
                'created_by' => $manager->id,
            ]);
        }

        $this->actingAs($staff)
            ->getJson('/leads/events?start='.today()->toDateString().'&end='.today()->addWeek()->toDateString().'&scope=all')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.assigned_to', $staff->id);

        $managerParty = Party::where('party_name', 'Lead Manager Lead')->firstOrFail();
        $this->actingAs($staff)->getJson('/leads/'.$managerParty->id)->assertForbidden();
    }

    private function adminWithRights(): User
    {
        $user = User::create([
            'name' => 'Lead Manager',
            'email' => uniqid('manager').'@example.test',
            'password' => bcrypt('password'),
            'showpassword' => '',
            'biller_id' => 0,
            'role' => 'Admin',
            'status' => 1,
            'warehouse_id' => 1,
        ]);

        foreach (['ADD', 'EDIT'] as $right) {
            DB::table('voucher_rights')->insert([
                'user_id' => $user->id,
                'voucher_name' => 'LEADS',
                'right_name' => $right,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $user;
    }
}
