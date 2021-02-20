<?php


namespace App\Observers\GoodIndent;


use App\Models\v1\Coupon;
use App\Models\v1\GoodIndent;
use App\Models\v1\GoodIndentUserCoupon;
use App\Models\v1\UserCoupon;
use Illuminate\Http\Request;

/**
 * create indent commodity
 * 用户使用优惠券
 * Class UserUseCouponObserver
 * @package App\Observers\GoodIndent
 */
class UserUseCouponObserver
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function created(GoodIndent $goodIndent)
    {
        // 当状态为待付款时触发
        if ($goodIndent->state == GoodIndent::GOOD_INDENT_STATE_PAY) {
            $couponMoney = 0;
            if ($this->request->user_coupon_id) {   //使用了优惠券
                $UserCoupon = UserCoupon::with(['Coupon'])->find($this->request->user_coupon_id);
                if ($UserCoupon) {
                    switch ($UserCoupon->Coupon->type) {
                        case Coupon::COUPON_TYPE_FULL_REDUCTION:
                        case Coupon::COUPON_TYPE_RANDOM:
                            $couponMoney = $UserCoupon->Coupon->cost / 100;
                            break;
                        case Coupon::COUPON_TYPE_DISCOUNT:  //折扣：商品总额*优惠券折扣/100
                            $couponMoney = $goodIndent->total * ($UserCoupon->Coupon->cost / 10000);
                            break;
                    }
                    $UserCoupon->state = UserCoupon::USER_COUPON_STATE_USED;
                    $UserCoupon->save();
                }
                $goodIndent->coupon_money = $couponMoney;
                $GoodIndentUserCoupon = new GoodIndentUserCoupon();
                $GoodIndentUserCoupon->good_indent_id = $goodIndent->id;
                $GoodIndentUserCoupon->user_coupon_id = $this->request->user_coupon_id;
                $GoodIndentUserCoupon->save();
            }
            $goodIndent->total = $goodIndent->total/100 - $couponMoney;
            $goodIndent->save();
        }
    }
}
