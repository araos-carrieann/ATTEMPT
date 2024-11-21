<?php

namespace App\Console\Commands;

use App\Models\eBooks;
use Illuminate\Console\Command;

class HardDeleteEbooks extends Command
{
    // Define the name and signature of the console command.
    protected $signature = 'ebooks:hard-delete';

    // Define the description of the console command.
    protected $description = 'Force delete soft-deleted eBooks older than a specified number of days';

    // Define the handle method that will run when the command is executed.
    public function handle()
    {
        $days = 30; // The number of days after which the eBooks will be force deleted.

        // Find eBooks that have been soft-deleted and are older than the specified number of days
        $ebooks = eBooks::onlyTrashed()
                        ->where('deleted_at', '<', now()->subDays($days)) // Check if the soft delete was done more than $days ago
                        ->get();

        // Loop through the soft-deleted eBooks and force delete them
        foreach ($ebooks as $ebook) {
            $ebook->forceDelete(); // Force delete the eBook and its related data
            $this->info('Force deleted eBook ID: ' . $ebook->id); // Log the deletion
        }

        $this->info('Hard delete process completed.');
    }
}