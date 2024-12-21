<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerCredentialMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order_id;
    public $cust_mail;
    public $psw;
    public  $remember_token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_id, $cust_mail , $psw , $remember_token)
    {
        $this->order_id = $order_id;
        $this->cust_mail = $cust_mail;
        $this->psw = $psw;
        $this->remember_token = $remember_token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $customerPanelUrl = env('CUSTOMER_PANEL_URL' , 'https://customer.virtuouscarat.com/');
        $customerPanelUrl = rtrim($customerPanelUrl, '/');
        $reset_password_url = $customerPanelUrl . '/showPassword/' . $this->remember_token;
        return $this->view('emails.customer_credentials')
                    ->subject('Dashboard Credentials')
                    ->with([
                        'order_id' => $this->order_id,
                        'cust_mail' => $this->cust_mail,
                        'psw' => $this->psw,
                        'reset_password_url' => $reset_password_url
                    ]);
    }
}
