<?php

namespace App\Listeners;

use App\Events\SendSms;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\EasySms;

class SendSmsListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SendSms $event
     * @return void
     */
    public function handle(SendSms $event)
    {
        //发送验证码到手机
        $config = config('sms');
        $easySms = new EasySms($config);
        //随机生成验证码
        $code = mt_rand(1000, 9999);
        //缓存验证码
        Cache::put('phone_code_' . $event->phone, $code, now()->addMinutes(5));
        try {
            $easySms->send($event->phone, [
                'template' => $config['template'],
                'data' => [
                    'code' => $code,
                ],
            ]);
        } catch (\Exception $e) {
            return $e->getExceptions();
        }
    }
}
