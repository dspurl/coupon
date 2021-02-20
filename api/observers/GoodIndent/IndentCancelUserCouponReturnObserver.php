<?php


namespace App\Observers\GoodIndent;


use App\Models\v1\GoodIndent;
use App\Models\v1\UserCoupon;

/**
 * indent cancel user coupon return
 * 订单取消用户优惠券返还
 * Class IndentCancelStockProcessingObserver
 * @package App\Observers\GoodIndent
 */
class IndentCancelUserCouponReturnObserver
{

    public function updated(GoodIndent $goodIndent)
    {
        // 当状态为取消订单或状态为已退款并且退款金额等于订单金额时触发
        if ($goodIndent->state == GoodIndent::GOOD_INDENT_STATE_CANCEL || ($goodIndent->state == GoodIndent::GOOD_INDENT_STATE_REFUND && $goodIndent->refund_money == $goodIndent->total)) {
            // 优惠券返还
            $goodIndent = GoodIndent::with(['GoodIndentUserCoupon'])->find($goodIndent->id);
            if ($goodIndent->GoodIndentUserCoupon) {
                UserCoupon::where('id', $goodIndent->GoodIndentUserCoupon->user_coupon_id)->update(['state' => UserCoupon::USER_COUPON_STATE_UNUSED]);
            }
        }
    }
}
