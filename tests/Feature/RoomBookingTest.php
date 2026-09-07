<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomBookingTest extends TestCase
{
    use RefreshDatabase;

    private User $memberUser;
    private Member $member;
    private Room $room;

    protected function setUp(): void
    {
        parent::setUp();

        $this->memberUser = User::factory()->create(['role' => 'member']);
        $this->member = Member::create([
            'kode_anggota' => 'MBR001',
            'nama' => $this->memberUser->name,
            'email' => $this->memberUser->email,
            'password' => 'password',
            'telepon' => '-',
            'alamat' => '-',
            'role' => 'member',
        ]);
        $this->room = Room::create([
            'name' => 'Faculty Meeting Room',
            'location' => 'Building A',
            'capacity' => 20,
            'status' => 'available',
        ]);
    }

    public function test_member_can_book_an_available_room(): void
    {
        $response = $this->actingAs($this->memberUser)
            ->withSession(['member_id' => $this->member->id])
            ->post(route('rooms.book', $this->room), [
                'start_at' => now()->addDay()->setTime(9, 0)->format('Y-m-d H:i'),
                'end_at' => now()->addDay()->setTime(11, 0)->format('Y-m-d H:i'),
                'purpose' => 'Faculty meeting',
            ]);

        $response->assertRedirect(route('room-bookings.index'));
        $this->assertDatabaseHas('room_bookings', [
            'room_id' => $this->room->id,
            'user_id' => $this->memberUser->id,
            'status' => 'approved',
        ]);
        $this->assertEquals('booked', $this->room->fresh()->status);
    }

    public function test_cancelling_a_booking_automatically_releases_the_room(): void
    {
        $booking = RoomBooking::create([
            'room_id' => $this->room->id,
            'user_id' => $this->memberUser->id,
            'start_at' => now()->addDay(),
            'end_at' => now()->addDay()->addHour(),
            'purpose' => 'Faculty meeting',
            'status' => 'approved',
        ]);
        $this->room->markBooked();

        $this->actingAs($this->memberUser)
            ->withSession(['member_id' => $this->member->id])
            ->patch(route('room-bookings.cancel', $booking))
            ->assertRedirect();

        $this->assertEquals('available', $this->room->fresh()->status);
        $this->assertDatabaseHas('room_bookings', ['id' => $booking->id, 'status' => 'cancelled']);
    }

    public function test_member_cannot_book_unavailable_room_or_overlapping_time(): void
    {
        $this->room->update(['status' => 'unavailable']);
        $payload = [
            'start_at' => now()->addDay()->setTime(9, 0)->format('Y-m-d H:i'),
            'end_at' => now()->addDay()->setTime(11, 0)->format('Y-m-d H:i'),
            'purpose' => 'Faculty meeting',
        ];

        $response = $this->actingAs($this->memberUser)->withSession(['member_id' => $this->member->id])
            ->post(route('rooms.book', $this->room), $payload);
        $response->assertSessionHas('error', 'This room is currently unavailable.');

        $this->room->update(['status' => 'available']);
        RoomBooking::create(['room_id' => $this->room->id, 'user_id' => $this->memberUser->id, 'start_at' => $payload['start_at'], 'end_at' => $payload['end_at'], 'purpose' => 'Existing booking', 'status' => 'approved']);
        $response = $this->actingAs($this->memberUser)->withSession(['member_id' => $this->member->id])
            ->post(route('rooms.book', $this->room), $payload);
        $response->assertSessionHas('error', 'This room is already booked for that time.');
    }

    public function test_admin_can_create_room_and_update_booking_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->post(route('admin.rooms.store'), [
            'name' => 'New Seminar Room', 'location' => 'Building B', 'capacity' => 40, 'status' => 'available',
        ]);
        $response->assertRedirect(route('admin.rooms.index'));
        $newRoom = Room::where('name', 'New Seminar Room')->firstOrFail();
        $booking = RoomBooking::create(['room_id' => $newRoom->id, 'user_id' => $this->memberUser->id, 'start_at' => now()->addDay(), 'end_at' => now()->addDay()->addHour(), 'purpose' => 'Seminar', 'status' => 'approved']);

        $this->actingAs($admin)->patch(route('admin.room-bookings.status', $booking), ['status' => 'completed'])
            ->assertRedirect();
        $this->assertDatabaseHas('room_bookings', ['id' => $booking->id, 'status' => 'completed']);
    }
}
