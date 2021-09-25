<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCode extends Mailable
{
    use Queueable, SerializesModels;
    protected $email;//传递过来的邮箱
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //生成code
        $code = mt_rand(1000, 9999);
        //缓存邮箱对应的code,并设置15分钟过期
        cache(['email_code_'.$this->email => $code,now()->addMinute(15)]);
        return $this->view('emails.send-code', ['code' => $code]);
    }
}
