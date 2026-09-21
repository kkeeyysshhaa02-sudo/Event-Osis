<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users for each Role (Admin: Keca, Panitia: Kayla)
        $admin = User::create([
            'name' => 'Keca (Admin OSIS)',
            'email' => 'admin@osis.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'kelas' => 'XI-2',
        ]);

        $panitia = User::create([
            'name' => 'Kayla (Panitia OSIS)',
            'email' => 'panitia@osis.sch.id',
            'password' => Hash::make('password'),
            'role' => 'panitia',
            'phone' => '082345678901',
            'kelas' => 'XI RPL 1',
        ]);

        $peserta1 = User::create([
            'name' => 'Wyanet In Nakeisha',
            'email' => 'peserta@osis.sch.id',
            'password' => Hash::make('password'),
            'role' => 'peserta',
            'phone' => '083456789012',
            'kelas' => 'XI-2',
        ]);

        $peserta2 = User::create([
            'name' => 'Ahmad Rizky',
            'email' => 'ahmad@osis.sch.id',
            'password' => Hash::make('password'),
            'role' => 'peserta',
            'phone' => '084567890123',
            'kelas' => 'X RPL 2',
        ]);

        // 2. Create Categories
        $catAkademik = Category::create([
            'name' => 'Akademik & Literasi',
            'slug' => 'akademik-literasi',
            'description' => 'Kegiatan yang menunjang kemampuan akademik, debat, dan budaya membaca siswa.',
        ]);

        $catOlahraga = Category::create([
            'name' => 'Olahraga & Kesehatan',
            'slug' => 'olahraga-kesehatan',
            'description' => 'Turnamen dan kompetisi kebugaran fisik antar kelas.',
        ]);

        $catSeni = Category::create([
            'name' => 'Seni & Budaya',
            'slug' => 'seni-budaya',
            'description' => 'Pentas seni, lomba menggambar, vokal, dan apresiasi budaya.',
        ]);

        $catKepemimpinan = Category::create([
            'name' => 'Organisasi & Kepemimpinan',
            'slug' => 'organisasi-kepemimpinan',
            'description' => 'Pelatihan kepemimpinan siswa dan musyawarah OSIS.',
        ]);

        // 3. Create Events
        $event1 = Event::create([
            'category_id' => $catSeni->id,
            'created_by' => $admin->id,
            'name' => 'Pentas Seni & Classmeeting 2026',
            'slug' => 'pentas-seni-classmeeting-2026-'.Str::random(4),
            'description' => 'Ajang pertunjukan bakat bermusik, tari, dan drama antar kelas setelah Sumatif Tengah Semester.',
            'event_date' => now()->addDays(7)->setHour(8)->setMinute(0),
            'location' => 'Aula Utama SMK Negeri OSIS',
            'capacity' => 100,
            'status' => 'upcoming',
        ]);

        $event2 = Event::create([
            'category_id' => $catOlahraga->id,
            'created_by' => $panitia->id,
            'name' => 'Turnamen Futsal OSIS Cup XI',
            'slug' => 'turnamen-futsal-osis-cup-xi-'.Str::random(4),
            'description' => 'Kompetisi futsal antar tim kelas dengan sistem gugur memperebutkan Piala Bergilir Ketua OSIS.',
            'event_date' => now()->addDays(14)->setHour(9)->setMinute(0),
            'location' => 'Lapangan Olahraga Kampus A',
            'capacity' => 16,
            'status' => 'upcoming',
        ]);

        $event3 = Event::create([
            'category_id' => $catAkademik->id,
            'created_by' => $panitia->id,
            'name' => 'Lomba Debat Bahasa Indonesia',
            'slug' => 'lomba-debat-bahasa-indonesia-'.Str::random(4),
            'description' => 'Uji gagasan kritis siswa mengenai isu-isu teknologi dan pendidikan terkini.',
            'event_date' => now()->subDays(2)->setHour(10)->setMinute(0),
            'location' => 'Ruang Lab Bahasa',
            'capacity' => 30,
            'status' => 'completed',
        ]);

        $event4 = Event::create([
            'category_id' => $catKepemimpinan->id,
            'created_by' => $admin->id,
            'name' => 'Latihan Dasar Kepemimpinan Siswa (LDKS)',
            'slug' => 'ldks-2026-'.Str::random(4),
            'description' => 'Pembekalan karakter kepemimpinan untuk calon pengurus OSIS dan Ekstrakurikuler.',
            'event_date' => now()->addDays(20)->setHour(7)->setMinute(30),
            'location' => 'Gedung Serbaguna & Halaman Utama',
            'capacity' => 50,
            'status' => 'upcoming',
        ]);

        // 4. Create Sample Registrations
        Registration::create([
            'user_id' => $peserta1->id,
            'event_id' => $event1->id,
            'registration_date' => now()->subHours(5),
            'status' => 'approved',
            'notes' => 'Perwakilan kelas XI-2',
        ]);

        Registration::create([
            'user_id' => $peserta2->id,
            'event_id' => $event1->id,
            'registration_date' => now()->subHours(2),
            'status' => 'pending',
            'notes' => 'Perwakilan kelas X RPL 2',
        ]);

        Registration::create([
            'user_id' => $peserta1->id,
            'event_id' => $event2->id,
            'registration_date' => now()->subDays(1),
            'status' => 'approved',
            'notes' => 'Tim Futsal XI-2',
        ]);
    }
}
