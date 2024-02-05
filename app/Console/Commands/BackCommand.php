<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Jobs\BackupJob;
use Illuminate\Console\Command;

class BackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'backup database paradise';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    
        $folder = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Paradise';
        $files = scandir($folder);
        foreach ($files as $backFile) {
            if (strstr($backFile, Carbon::now()->format("Y-m-d"))) {
                BackupJob::dispatch($backFile, Carbon::now()->format("Y-m-d-h"));
            }

        }  
        return $this->info("Backup sent with successfully");
    }
}
