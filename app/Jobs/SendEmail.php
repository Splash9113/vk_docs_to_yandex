<?php

namespace App\Jobs;

use App\UploadFile;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $file;

    public function __construct(UploadFile $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $data = [
            'title' => $this->file->title,
            'email' => $this->file->email
        ];
        Mail::send(['html' => 'emails.alert'], ['data' => $data], function ($message) use ($data) {
            $message->to($data['email']);
        });
    }
}
