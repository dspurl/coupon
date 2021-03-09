<?php

namespace App\Console\Commands;

use App\Models\v1\Coupon;
use App\Models\v1\User;
use App\Models\v1\UserCoupon;
use Carbon\Carbon;
use Illuminate\Console\Command;

class VipCouponGrant extends Command
{
    /**
     * vip优惠券发放
     *
     * @var string
     */
    protected $signature = 'vipCoupon:grant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'VIP coupon grant。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @return void
     */
    public function handle()
    {
        $User = User::where('vip', User::USER_VIP_YES)->where('state', User::USER_STATE_NORMAL)->where('unsubscribe', User::USER_UNSUBSCRIBE_NO)->get();
        $Coupon = Coupon::where('vip', Coupon::COUPON_VIP_YES)->get();
        foreach ($User as $u) {
            foreach ($Coupon as $c) {
                if ($c->amount > 0) {
                    for ($i = 1; $i <= $c->amount; $i++) {
                        $UserCoupon = new UserCoupon();
                        $UserCoupon->user_id = $u->id;
                        $UserCoupon->coupon_id = $c->id;
                        $UserCoupon->ticket = orderNumber();
                        $UserCoupon->failure_time = Carbon::now()->toDateTimeString();
                        $UserCoupon->save();
                    }
                }
            }
        }
    }
}
