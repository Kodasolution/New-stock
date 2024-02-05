<?php

namespace App\Jobs;

use App\Mail\BackupMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class BackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $backFile;
    public $date;
    public function __construct($backFile, $date = null)
    {
        $this->backFile = $backFile;
        $this->date = $date;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to(["migos.fugo@gmail.com"])
            ->send(new BackupMail($this->backFile,$this->date));
    }
}
