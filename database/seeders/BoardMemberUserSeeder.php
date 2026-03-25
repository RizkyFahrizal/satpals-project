<?php

namespace Database\Seeders;

use App\Models\BoardMember;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BoardMemberUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all board members without user account
        $boardMembers = BoardMember::whereNull('user_id')->with('member')->get();

        $created = 0;
        $skipped = 0;

        foreach ($boardMembers as $board) {
            try {
                // Generate username from nama_lengkap
                $nama = $board->member->nama_lengkap;
                $username = $this->generateUsername($nama);

                // Generate default password (nama_lengkap tanpa spasi)
                $defaultPassword = str_replace(' ', '', strtolower($nama));

                // Create user
                $user = User::create([
                    'name' => $nama,
                    'email' => "{$username}@satya-palapa.local",
                    'username' => $username,
                    'password' => Hash::make($defaultPassword),
                    'role' => 'pengurus',
                    'is_active' => true,
                ]);

                // Link user to board member
                $board->update(['user_id' => $user->id]);

                $created++;
            } catch (\Exception $e) {
                $skipped++;
                $this->command->warn("Skip {$board->member->nama_lengkap}: {$e->getMessage()}");
            }
        }

        $this->command->info("✅ User accounts created successfully!");
        $this->command->line("Created: $created");
        $this->command->line("Skipped: $skipped");
        $this->command->line("\n📝 Default Credentials:");
        $this->command->line("Username: generated from name (lowercase, no space)");
        $this->command->line("Password: nama_lengkap (lowercase, no space)");
        $this->command->line("\n💡 Admin harus reset password saat first login!");
    }

    /**
     * Generate unique username from name
     */
    private function generateUsername($name)
    {
        // Normalize: lowercase, remove special chars, replace space with underscore
        $username = strtolower($name);
        $username = preg_replace('/[^a-z0-9_]/', '', str_replace(' ', '_', $username));
        $username = substr($username, 0, 30); // Max 30 chars

        // Check if exists, add number if duplicate
        $originalUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
