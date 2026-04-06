<?php

namespace Tests\Feature\Console;

use App\Jobs\SendTelegramMessageJob;
use App\Models\Surat;
use App\Models\TelegramLog;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExpireBookedSuratCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('telegram_chat_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('surats', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('jenis_surat_id')->nullable();
            $table->unsignedBigInteger('pembuat_id');
            $table->unsignedBigInteger('approver_id');
            $table->string('no_surat')->nullable();
            $table->string('perihal');
            $table->date('tanggal_surat')->nullable();
            $table->string('status')->default(Surat::STATUS_DRAFT);
            $table->timestamp('expired_at')->nullable();
            $table->string('surat_file_path')->nullable();
            $table->string('released_no_surat')->nullable();
            $table->string('verification_token')->nullable();
            $table->text('rejection_note')->nullable();
            $table->text('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('surat_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->text('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->timestamp('logged_at')->nullable();
            $table->timestamps();
        });

        Schema::create('telegram_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('surat_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('chat_id')->nullable();
            $table->text('message');
            $table->string('status')->default(TelegramLog::STATUS_PENDING);
            $table->unsignedInteger('retry_count')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('response_body')->nullable();
            $table->timestamps();
        });
    }

    private function createBookedSurat(string $noSurat, \DateTimeInterface $expiredAt): Surat
    {
        $user = User::query()->create([
            'name' => 'Test User',
            'email' => uniqid('user', true).'@example.test',
            'password' => Hash::make('password'),
            'telegram_chat_id' => '123456789',
        ]);

        return Surat::query()->create([
            'jenis_surat_id' => 1,
            'pembuat_id' => $user->id,
            'approver_id' => $user->id,
            'no_surat' => $noSurat,
            'perihal' => 'Pengujian Expired Booking',
            'tanggal_surat' => now()->toDateString(),
            'status' => Surat::STATUS_BOOKED,
            'expired_at' => $expiredAt,
            'surat_file_path' => null,
            'released_no_surat' => null,
            'verification_token' => null,
            'rejection_note' => null,
            'metadata' => null,
        ]);
    }

    public function test_command_expires_booked_surat_when_expired_at_has_passed(): void
    {
        Queue::fake();

        $surat = $this->createBookedSurat(
            '0001/KET/SMKN1-Krangkeng/2026',
            now()->subMinute(),
        );

        $this->artisan('surat:expire-bookings')
            ->assertSuccessful();

        $surat->refresh();

        $this->assertSame(Surat::STATUS_EXPIRED, $surat->status);
        $this->assertNull($surat->no_surat);
        $this->assertSame('0001/KET/SMKN1-Krangkeng/2026', $surat->released_no_surat);

        $this->assertDatabaseHas('audit_logs', [
            'surat_id' => $surat->id,
            'action' => 'booking_expired',
        ]);

        $this->assertDatabaseHas('telegram_logs', [
            'surat_id' => $surat->id,
            'status' => TelegramLog::STATUS_PENDING,
        ]);

        Queue::assertPushed(SendTelegramMessageJob::class, 1);
    }

    public function test_command_does_not_expire_booked_surat_when_expired_at_is_in_the_future(): void
    {
        Queue::fake();

        $surat = $this->createBookedSurat(
            '0002/KET/SMKN1-Krangkeng/2026',
            now()->addMinutes(30),
        );

        $this->artisan('surat:expire-bookings')
            ->assertSuccessful();

        $surat->refresh();

        $this->assertSame(Surat::STATUS_BOOKED, $surat->status);
        $this->assertSame('0002/KET/SMKN1-Krangkeng/2026', $surat->no_surat);
        $this->assertNull($surat->released_no_surat);

        $this->assertDatabaseMissing('audit_logs', [
            'surat_id' => $surat->id,
            'action' => 'booking_expired',
        ]);

        Queue::assertNothingPushed();
    }
}
