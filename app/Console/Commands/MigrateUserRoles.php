<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\BoardMember;
use Illuminate\Console\Command;

class MigrateUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:user-roles {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing user roles based on their board_members jabatan field';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting user role migration...');
        $this->newLine();

        // Confirm if not force flag
        if (!$this->option('force')) {
            $this->warn('WARNING: This will update all user roles based on their board_members.jabatan.');
            $this->warn('Super Admin roles will NOT be changed.');
            $this->newLine();
            
            if (!$this->confirm('Do you want to proceed?')) {
                $this->info('Migration cancelled.');
                return Command::SUCCESS;
            }
        }

        // Map jabatan to role
        $jabatanToRoleMap = [
            'ketua_umum' => User::ROLE_KETUA_UMUM,
            'wakil_ketua_umum' => User::ROLE_WAKIL_KETUA_UMUM,
            'bendahara' => User::ROLE_BENDAHARA,
            'sekretaris' => User::ROLE_SEKRETARIS,
            'mpa' => User::ROLE_MPA,
            'band' => User::ROLE_BAND,
            'peralatan' => User::ROLE_PERALATAN,
            'humas' => User::ROLE_HUMAS,
            'pdd' => User::ROLE_PDD,
            'kesekretariatan' => User::ROLE_KESEKRETARIATAN,
        ];

        $migratedCount = 0;
        $skippedCount = 0;
        $errors = [];

        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // Skip super_admin users
            if ($user->isSuperAdmin()) {
                $this->line("  ⊘ SKIP: {$user->name} ({$user->email}) - Super Admin (cannot change)");
                $skippedCount++;
                continue;
            }

            // Find active board member
            $boardMember = BoardMember::where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

            if (!$boardMember) {
                // No active board member, set to public
                if ($user->role !== User::ROLE_PUBLIC) {
                    $user->role = User::ROLE_PUBLIC;
                    $user->save();
                    $this->line("  ✓ MIGRATED: {$user->name} ({$user->email}) → Public (no active board member)");
                    $migratedCount++;
                } else {
                    $this->line("  - UNCHANGED: {$user->name} ({$user->email}) → Already Public");
                    $skippedCount++;
                }
                continue;
            }

            // Get role from jabatan
            $newRole = $jabatanToRoleMap[$boardMember->jabatan] ?? User::ROLE_PENGURUS;

            // Only update if different
            if ($user->role !== $newRole) {
                $oldRole = $user->role;
                $user->role = $newRole;
                $user->save();
                $this->line("  ✓ MIGRATED: {$user->name} ({$user->email}) → {$boardMember->jabatan} (was: {$oldRole})");
                $migratedCount++;
            } else {
                $this->line("  - UNCHANGED: {$user->name} ({$user->email}) → {$boardMember->jabatan}");
                $skippedCount++;
            }
        }

        $this->newLine();
        $this->info("Migration Summary:");
        $this->line("  • Migrated: {$migratedCount}");
        $this->line("  • Unchanged: {$skippedCount}");
        $this->line("  • Total Users: {$users->count()}");

        if (!empty($errors)) {
            $this->newLine();
            $this->error("Errors occurred:");
            foreach ($errors as $error) {
                $this->line("  • {$error}");
            }
        }

        $this->newLine();
        $this->info('User role migration completed successfully!');

        return Command::SUCCESS;
    }
}
