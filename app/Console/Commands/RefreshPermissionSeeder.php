<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RefreshPermissionSeeder extends Command
{
    protected $signature = 'permissions:refresh-seeder';
    protected $description = 'Delete and re-publish the permission seeder file';

    public function handle()
    {
        $seederPath = database_path('seeders/PermissionSeeder.php');

        if (File::exists($seederPath)) {
            File::delete($seederPath);
            $this->info("Old PermissionSeeder deleted.");
        } else {
            $this->warn("PermissionSeeder.php not found. Skipping delete.");
        }

        $this->info("Re-publishing permissions seeder...");

        // If you're using Spatie
        $this->callSilent('vendor:publish', [
            '--provider' => "Spatie\Permission\PermissionServiceProvider",
            '--tag' => "permission-seeder"
        ]);

        if (File::exists($seederPath)) {
            $this->info("PermissionSeeder published successfully.");
        } else {
            $this->error("Failed to publish PermissionSeeder.");
        }

        // Step 5 (optional): Ask to run it
        if ($this->confirm("Do you want to run the PermissionSeeder now?", true)) {
            $this->call('db:seed', [
                '--class' => 'PermissionSeeder'
            ]);
        }

        $this->info("Done.");
    }
}
