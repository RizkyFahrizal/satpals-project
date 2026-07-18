<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BoardMember;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MigratePengurusRolesToSpecificRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all users with role 'pengurus' (legacy role)
        $pengurusUsers = User::where('role', 'pengurus')->get();

        foreach ($pengurusUsers as $user) {
            // Find board member associated with this user
            $boardMember = $user->boardMember;
            
            if ($boardMember) {
                // Map jabatan to role and update user
                $newRole = BoardMember::jabatanToRole($boardMember->jabatan);
                $user->update(['role' => $newRole]);
                
                $this->command->info("User {$user->email} role updated from 'pengurus' to '{$newRole}' (jabatan: {$boardMember->jabatan})");
            } else {
                // If no board member found, assign to default subsie (kesekretariatan)
                $user->update(['role' => 'kesekretariatan']);
                
                $this->command->warn("User {$user->email} has no board member. Assigned default role 'kesekretariatan'");
            }
        }

        $this->command->info("Migration completed! All pengurus users have been updated to specific roles.");
    }
}
