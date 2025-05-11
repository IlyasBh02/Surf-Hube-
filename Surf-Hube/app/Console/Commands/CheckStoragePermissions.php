<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckStoragePermissions extends Command
{
    protected $signature = 'storage:check-permissions';
    protected $description = 'Check storage directories permissions and make them writable if needed';

    public function handle()
    {
        $this->info('Checking storage permissions...');

        $directories = [
            storage_path('app'),
            storage_path('app/public'),
            storage_path('app/public/course_thumbnails'),
            storage_path('logs'),
            public_path('storage'),
        ];

        foreach ($directories as $directory) {
            if (!file_exists($directory)) {
                $this->warn("Creating directory: {$directory}");
                mkdir($directory, 0755, true);
            }

            if (!is_writable($directory)) {
                $this->error("Directory is not writable: {$directory}");
                $this->warn("Attempting to make writable...");
                
                // Try to change permissions
                if (chmod($directory, 0755)) {
                    $this->info("Successfully made writable: {$directory}");
                } else {
                    $this->error("Failed to make writable: {$directory}");
                    $this->line("Please manually ensure this directory is writable by the web server user.");
                }
            } else {
                $this->info("Directory is writable: {$directory}");
            }
        }

        $this->info('Storage permission check completed.');
        
        // Ensure the symbolic link exists
        $this->info('Checking if storage link exists...');
        
        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
        } else {
            $this->info('Storage link exists.');
        }
    }
} 