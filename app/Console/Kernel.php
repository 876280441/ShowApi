<?php

namespace App\Console;

use App\Models\Order;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * 定义应用中的命令调度
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //定时检测订单状态，超过十分钟未支付的，作废
        //在真实的项目中，不会这么做，真实的项目一般会使用长链接，订单过期，直接作废
        $schedule->call(function () {
            //查询状态为1并且时间不超过1分钟的订单
            $orders = Order::where('status', 1)
                ->where('created_at', '<', date('Y-m-d H:i:s', time() - 600))
                ->with('orderDetails.goods')
                ->get();
            //循环订单，修改订单状态，还原商品库存
            try {
                DB::beginTransaction();//开启事务
                foreach ($orders as $order) {
                    $order->status = 5;//修改订单状态
                    $order->save();
                    //还原商品库存
                    foreach ($order->orderDetails as $details) {
                        $details->goods->increment('stock', $details->num);
                    }
                }
                DB::commit();//提交事务
            } catch (\Exception $e) {
                Log::error($e);
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
