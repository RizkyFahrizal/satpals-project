<?php

namespace Tests\Feature;

use App\Models\StudioBooking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudioBookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create();
        $this->admin = User::factory()->create(['role' => 'pengurus']);
    }

    /** @test */
    public function can_access_studio_bookings_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.studio-bookings.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.studio-bookings.index');
    }

    /** @test */
    public function can_create_booking()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.studio-bookings.store'), [
                'user_npm' => $this->user->username,
                'tanggal_booking' => now()->addDay()->format('Y-m-d'),
                'sesi' => 1,
                'keperluan' => 'Latihan band untuk keperluan konser minggu depan',
            ]);

        $response->assertRedirect(route('admin.studio-bookings.index'));
        $this->assertDatabaseHas('studio_bookings', [
            'user_id' => $this->user->id,
            'sesi' => 1,
            'status' => StudioBooking::STATUS_PENDING,
        ]);
    }

    /** @test */
    public function can_approve_booking()
    {
        $booking = StudioBooking::factory()
            ->for($this->user)
            ->create(['status' => StudioBooking::STATUS_PENDING]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.studio-bookings.approve', $booking), [
                'catatan' => 'Disetujui',
            ]);

        $response->assertRedirect();
        $this->assertTrue($booking->fresh()->isApproved());
        $this->assertEquals($this->admin->id, $booking->fresh()->approved_by);
    }

    /** @test */
    public function can_reject_booking()
    {
        $booking = StudioBooking::factory()
            ->for($this->user)
            ->create(['status' => StudioBooking::STATUS_PENDING]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.studio-bookings.reject', $booking), [
                'catatan' => 'Tanggal sudah tidak tersedia',
            ]);

        $response->assertRedirect();
        $this->assertEquals(StudioBooking::STATUS_REJECTED, $booking->fresh()->status);
    }

    /** @test */
    public function cannot_double_book_same_sesi()
    {
        // Create first booking
        StudioBooking::factory()
            ->for($this->user)
            ->create([
                'tanggal_booking' => now()->addDay(),
                'sesi' => 1,
                'status' => StudioBooking::STATUS_APPROVED,
            ]);

        // Try to create second booking on same date/sesi
        $response = $this->actingAs($this->admin)
            ->post(route('admin.studio-bookings.store'), [
                'user_npm' => User::factory()->create()->username,
                'tanggal_booking' => now()->addDay()->format('Y-m-d'),
                'sesi' => 1,
                'keperluan' => 'Test booking yang seharusnya gagal',
            ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function cannot_book_past_date()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.studio-bookings.store'), [
                'user_npm' => $this->user->username,
                'tanggal_booking' => now()->subDay()->format('Y-m-d'),
                'sesi' => 1,
                'keperluan' => 'Ini seharusnya gagal karena tanggal di masa lalu',
            ]);

        $response->assertSessionHasErrors('tanggal_booking');
    }

    /** @test */
    public function sesi_available_scope_works()
    {
        // Create booking for sesi 1
        StudioBooking::factory()
            ->for($this->user)
            ->create([
                'tanggal_booking' => now()->addDay(),
                'sesi' => 1,
                'status' => StudioBooking::STATUS_APPROVED,
            ]);

        // Check that sesi 1 is not available
        $this->assertFalse(
            StudioBooking::isSesiAvailable(now()->addDay()->format('Y-m-d'), 1)
        );

        // Check that sesi 2 is available
        $this->assertTrue(
            StudioBooking::isSesiAvailable(now()->addDay()->format('Y-m-d'), 2)
        );
    }

    /** @test */
    public function get_available_sesi_returns_correct_sesi()
    {
        // Create booking for sesi 1 and 3
        $date = now()->addDay();
        StudioBooking::factory()
            ->for($this->user)
            ->create([
                'tanggal_booking' => $date,
                'sesi' => 1,
                'status' => StudioBooking::STATUS_APPROVED,
            ]);

        StudioBooking::factory()
            ->for(User::factory())
            ->create([
                'tanggal_booking' => $date,
                'sesi' => 3,
                'status' => StudioBooking::STATUS_APPROVED,
            ]);

        $available = StudioBooking::getAvailableSesi($date->format('Y-m-d'));
        
        $this->assertEquals([2, 4], array_values($available));
    }
}
