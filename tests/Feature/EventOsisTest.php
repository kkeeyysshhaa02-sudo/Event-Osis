<?php

use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can view events list and search filter', function () {
    $category = Category::create(['name' => 'Olahraga', 'slug' => 'olahraga']);
    $user = User::factory()->create(['role' => 'admin']);

    $event = Event::create([
        'category_id' => $category->id,
        'created_by' => $user->id,
        'name' => 'Turnamen Basket OSIS',
        'slug' => 'turnamen-basket-osis',
        'description' => 'Kompetisi basket antar kelas',
        'event_date' => now()->addDays(5),
        'location' => 'Lapangan Utama',
        'capacity' => 20,
        'status' => 'upcoming',
    ]);

    $response = $this->get('/events?search=Basket');
    $response->assertStatus(200);
    $response->assertSee('Turnamen Basket OSIS');
});

test('peserta cannot create events', function () {
    $peserta = User::factory()->create(['role' => 'peserta']);

    $response = $this->actingAs($peserta)->get('/events/create');
    $response->assertStatus(403);
});

test('admin and panitia can create events', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::create(['name' => 'Seni', 'slug' => 'seni']);

    $response = $this->actingAs($admin)->post('/events', [
        'name' => 'Pentas Seni Musik 2026',
        'category_id' => $category->id,
        'description' => 'Festival musik akustik',
        'event_date' => now()->addDays(10)->format('Y-m-d\TH:i'),
        'location' => 'Aula Utama',
        'capacity' => 100,
        'status' => 'upcoming',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('events', ['name' => 'Pentas Seni Musik 2026']);
});

test('peserta can register for event and prevents duplicate registration', function () {
    $peserta = User::factory()->create(['role' => 'peserta']);
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::create(['name' => 'Akademik', 'slug' => 'akademik']);

    $event = Event::create([
        'category_id' => $category->id,
        'created_by' => $admin->id,
        'name' => 'Webinar Coding',
        'slug' => 'webinar-coding',
        'description' => 'Belajar Laravel Dasar',
        'event_date' => now()->addDays(3),
        'location' => 'Lab Komputer',
        'capacity' => 10,
        'status' => 'upcoming',
    ]);

    // First registration attempt
    $response = $this->actingAs($peserta)->post('/registrations', [
        'event_id' => $event->id,
        'notes' => 'Perwakilan XI-2',
    ]);

    $response->assertRedirect(route('registrations.my'));
    $this->assertDatabaseHas('registrations', [
        'user_id' => $peserta->id,
        'event_id' => $event->id,
    ]);

    // Duplicate registration attempt
    $duplicateResponse = $this->actingAs($peserta)->post('/registrations', [
        'event_id' => $event->id,
    ]);

    $duplicateResponse->assertSessionHas('error');
});

test('prevents registration when event capacity is full', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::create(['name' => 'Umum', 'slug' => 'umum']);

    $event = Event::create([
        'category_id' => $category->id,
        'created_by' => $admin->id,
        'name' => 'Workshop E-Sport Limited',
        'slug' => 'workshop-esport-limited',
        'description' => 'Kapasitas 1 orang',
        'event_date' => now()->addDays(2),
        'location' => 'Ruang OSIS',
        'capacity' => 1,
        'status' => 'upcoming',
    ]);

    $user1 = User::factory()->create(['role' => 'peserta']);
    $user2 = User::factory()->create(['role' => 'peserta']);

    // User 1 registers
    $this->actingAs($user1)->post('/registrations', ['event_id' => $event->id]);

    // User 2 attempts to register when full
    $response = $this->actingAs($user2)->post('/registrations', ['event_id' => $event->id]);
    $response->assertSessionHas('error');
});

test('peserta can view e-ticket for approved registration', function () {
    $peserta = User::factory()->create(['role' => 'peserta', 'name' => 'Wyanet']);
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::create(['name' => 'Seni', 'slug' => 'seni-ticket']);

    $event = Event::create([
        'category_id' => $category->id,
        'created_by' => $admin->id,
        'name' => 'Konser Musik OSIS',
        'slug' => 'konser-musik-osis',
        'description' => 'Konser musik tahunan',
        'event_date' => now()->addDays(5),
        'location' => 'Lapangan Utama',
        'capacity' => 50,
        'status' => 'upcoming',
    ]);

    $registration = Registration::create([
        'user_id' => $peserta->id,
        'event_id' => $event->id,
        'status' => 'approved',
        'registration_date' => now(),
    ]);

    $response = $this->actingAs($peserta)->get(route('registrations.ticket', $registration));

    $response->assertStatus(200);
    $response->assertSee('E-TIKET RESMI OSIS');
    $response->assertSee('Wyanet');
    $response->assertSee('Konser Musik OSIS');
});
