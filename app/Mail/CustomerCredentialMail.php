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
    public $customerPanelUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_id, $cust_mail, $customerPanelUrl)
    {
        $this->order_id = $order_id;
        $this->cust_mail = $cust_mail;
        $this->customerPanelUrl = $customerPanelUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.customer_credentials')
                    ->subject('Dashboard Credentials')
                    ->with([
                        'order_id' => $this->order_id,
                        'cust_mail' => $this->cust_mail,
                        'customerPanelUrl' => $this->customerPanelUrl,
                    ]);
    }
}
