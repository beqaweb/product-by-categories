<?php

namespace App\Mail;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewProductsNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $products;

    /**
     * Create a new message instance.
     *
     * @param array $products
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $super_admin_emails = array_map(function ($user) {
            return $user['email'];
        }, User::role('Super admin')->get()->toArray());

        return $this->to($super_admin_emails)
            ->view('mails.new-products-notification', [
                'products' => $this->products,
                'date' => [
                    'from' => Carbon::now()->subDays(1),
                    'to' => Carbon::now()
                ]
            ]);
    }
}
