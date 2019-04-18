<?php

namespace App\Jobs;

use App\Mail\NewProductsNotification;
use App\Product;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class NotifySuperAdminAboutNewProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $productsCreatedForADay = Product::query()->where('created_at', Carbon::now()->subDays(1))->get();
        Mail::send(new NewProductsNotification($productsCreatedForADay));
    }
}
