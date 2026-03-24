<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;

class ConvertExpiredMembersToAlumni extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:convert-expired-to-alumni';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert members to alumni status when their period is 4+ years old';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredMembers = Member::getExpiredMembers();

        if ($expiredMembers->isEmpty()) {
            $this->info('No members to convert to alumni.');
            return 0;
        }

        $count = 0;
        foreach ($expiredMembers as $member) {
            $member->convertToAlumni();
            $count++;
            $this->line("✓ Converted: {$member->nama_lengkap} (Angkatan {$member->angkatan})");
        }

        $this->info("Successfully converted {$count} members to alumni status.");
        return 0;
    }
}
