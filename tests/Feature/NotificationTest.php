<?php

namespace Tests\Feature;

use App\Models\NotificationRead;
use App\Models\Publishing_request_informaitons;
use App\Models\Requestor;
use App\Models\Socmed_request_information;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * The whole point of this feature: read state belongs to the account, not to
 * the request. One account reading a notification must never clear it for
 * another account.
 *
 * Tables are built by hand instead of with RefreshDatabase because the existing
 * migrations contain MySQL-only SQL (ALTER TABLE ... DROP CHECK) that sqlite
 * cannot run.
 */
class NotificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->default('');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('user_type')->default('admin');
            $table->string('employee_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('requestors', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->unique();
            $table->string('email')->nullable();
            $table->string('f_name')->nullable();
            $table->string('l_name')->nullable();
            $table->string('middle')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();
        });

        Schema::create('socmed_request_informations', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('token')->default('0');
            $table->string('status')->default('0');
            $table->string('specification')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
        });

        Schema::create('publishing_request_informaitons', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('token')->default('0');
            $table->string('status')->default('0');
            $table->string('specification')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
        });

        Schema::create('photo_vid_request_informations', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('token')->default('0');
            $table->string('status')->default('0');
            $table->string('category')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('notification_key');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'notification_key']);
        });
    }

    private function makeUser(string $type = 'admin', string $email = 'a@uz.edu.ph'): User
    {
        return User::create([
            'fullname' => 'Test '.$type,
            'email' => $email,
            'password' => bcrypt('secret'),
            'user_type' => $type,
            'employee_id' => 'EMP-'.substr(md5($email), 0, 6),
        ]);
    }

    private function makeSocmedRequest(string $requestId = 'abc123'): Requestor
    {
        $requestor = Requestor::create([
            'request_id' => $requestId,
            'email' => 'req@uz.edu.ph',
            'f_name' => 'Jane',
            'l_name' => 'Doe',
            'department' => 'IT',
        ]);

        Socmed_request_information::create([
            'request_id' => $requestId,
            'status' => '0',
            'specification' => 'socmed',
            'category' => 'poster',
        ]);

        return $requestor;
    }

    private function makeLayoutRequest(string $requestId = 'lay123'): Requestor
    {
        $requestor = Requestor::create([
            'request_id' => $requestId,
            'email' => 'req2@uz.edu.ph',
            'f_name' => 'Mark',
            'l_name' => 'Reyes',
            'department' => 'HR',
        ]);

        Publishing_request_informaitons::create([
            'request_id' => $requestId,
            'status' => '0',
            'specification' => 'layout',
        ]);

        return $requestor;
    }

    /** Read state must be per account - the bug the user reported. */
    public function test_one_account_reading_does_not_clear_another_accounts_badge(): void
    {
        $this->makeSocmedRequest('abc123');
        $this->makeLayoutRequest('lay123');

        $first = $this->makeUser('admin', 'first@uz.edu.ph');
        $second = $this->makeUser('admin', 'second@uz.edu.ph');

        $service = app(NotificationService::class);

        $this->assertSame(2, $service->unreadCount($first));
        $this->assertSame(2, $service->unreadCount($second));

        // The first account reads everything.
        $service->markRead($first, 'socmed:abc123');
        $service->markRead($first, 'layout:lay123');

        $this->assertSame(0, $service->unreadCount($first));

        // The second account must still see both as unread.
        $this->assertSame(2, $service->unreadCount($second));

        foreach ($service->forUser($second) as $item) {
            $this->assertFalse($item->read, "{$item->key} should still be unread for the second account");
        }
    }

    public function test_unread_count_is_per_account(): void
    {
        $this->makeSocmedRequest('abc123');

        $first = $this->makeUser('admin', 'first@uz.edu.ph');
        $second = $this->makeUser('admin', 'second@uz.edu.ph');

        $service = app(NotificationService::class);
        $service->markRead($first, 'socmed:abc123');

        $this->assertSame(0, $service->unreadCount($first));
        $this->assertSame(1, $service->unreadCount($second));
    }

    public function test_feed_is_scoped_to_the_users_role(): void
    {
        $this->makeSocmedRequest('abc123');
        $this->makeLayoutRequest('lay123');

        $service = app(NotificationService::class);

        $content = $this->makeUser('content_team', 'content@uz.edu.ph');
        $creative = $this->makeUser('creative_team', 'creative@uz.edu.ph');
        $admin = $this->makeUser('admin', 'admin@uz.edu.ph');

        $this->assertSame(['socmed'], $service->forUser($content)->pluck('type')->unique()->values()->all());
        $this->assertSame(['layout'], $service->forUser($creative)->pluck('type')->unique()->values()->all());

        $adminTypes = $service->forUser($admin)->pluck('type')->unique()->sort()->values()->all();
        $this->assertSame(['layout', 'socmed'], $adminTypes);
    }

    public function test_opening_a_notification_redirects_to_its_own_dashboard_and_marks_it_read(): void
    {
        $this->makeSocmedRequest('abc123');
        $this->makeLayoutRequest('lay123');

        $user = $this->makeUser('admin', 'admin@uz.edu.ph');

        $socmed = $this->actingAs($user)->get('/notifications/socmed:abc123/open');
        $socmed->assertRedirect();
        $this->assertStringContainsString('/socmed/dashboard', $socmed->headers->get('Location'));
        $this->assertStringContainsString('open=abc123', $socmed->headers->get('Location'));

        $layout = $this->actingAs($user)->get('/notifications/layout:lay123/open');
        $layout->assertRedirect();
        $this->assertStringContainsString('/layoutservices/dashboard', $layout->headers->get('Location'));
        $this->assertStringContainsString('open=lay123', $layout->headers->get('Location'));

        $this->assertSame(
            2,
            NotificationRead::where('user_id', $user->id)->count(),
            'Opening a notification should store a read receipt for that account'
        );
    }

    public function test_out_of_role_notification_cannot_be_opened(): void
    {
        $this->makeLayoutRequest('lay123');

        // A content-team account has no business opening a layout request.
        $content = $this->makeUser('content_team', 'content@uz.edu.ph');

        $this->actingAs($content)->get('/notifications/layout:lay123/open')->assertNotFound();
    }

    public function test_mark_all_read_only_affects_the_current_account(): void
    {
        $this->makeSocmedRequest('abc123');
        $this->makeLayoutRequest('lay123');

        $first = $this->makeUser('admin', 'first@uz.edu.ph');
        $second = $this->makeUser('admin', 'second@uz.edu.ph');

        $this->actingAs($first)
            ->postJson('/notifications/read-all')
            ->assertOk()
            ->assertJson(['success' => true, 'unread' => 0]);

        $service = app(NotificationService::class);
        $this->assertSame(0, $service->unreadCount($first));
        $this->assertSame(2, $service->unreadCount($second));
    }

    public function test_feed_and_count_endpoints_report_the_current_accounts_numbers(): void
    {
        $this->makeSocmedRequest('abc123');

        $user = $this->makeUser('admin', 'admin@uz.edu.ph');

        $this->actingAs($user)->getJson('/notifications/count')
            ->assertOk()
            ->assertJson(['unread' => 1, 'total' => 1]);

        $feed = $this->actingAs($user)->getJson('/notifications/feed')->assertOk();
        $this->assertSame(1, $feed->json('unread'));
        $this->assertStringContainsString('data-notif-key', $feed->json('html'));

        $this->actingAs($user)->postJson('/notifications/socmed:abc123/read')
            ->assertOk()
            ->assertJson(['success' => true, 'unread' => 0]);
    }

    /** The token column now lives on the per-request child tables. */
    public function test_token_column_lives_on_request_tables_not_on_requestors(): void
    {
        $this->assertFalse(
            Schema::hasColumn('requestors', 'token'),
            'requestors is shared by every request type, so it must not carry read state'
        );

        $this->assertTrue(Schema::hasColumn('socmed_request_informations', 'token'));
        $this->assertTrue(Schema::hasColumn('publishing_request_informaitons', 'token'));
        $this->assertTrue(Schema::hasColumn('photo_vid_request_informations', 'token'));
    }
}
