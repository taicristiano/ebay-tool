<?php

namespace App\Models;

class SoldItem extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_sold_items';

    protected $guarded = [];

    const AUTO_BY_FLG_NOT_YET = 0;
    const AUTO_BY_FLG_DONE = 1;
    const AUTO_BY_FLG_CAN_NOT_BUY = 2;

    /**
     * get flag auto done
     * @return integer
     */
    public function getFlagAutoByFlgDone()
    {
        return self::AUTO_BY_FLG_DONE;
    }

    /**
     * get flag auto can not buy
     * @return integer
     */
    public function getFlagAutoByFlgCanNotBuy()
    {
        return self::AUTO_BY_FLG_CAN_NOT_BUY;
    }

    public function getByInputId($input)
    {
        return $this->where('item_id', $input['item_id'])
            ->where('order_id', $input['order_id'])
            ->where('order_line_id', $input['order_line_id'])
            ->where('transaction_id', $input['transaction_id'])
            ->first();
    }

    public function getOrderStatus($orderStatus)
    {
        if (in_array($orderStatus, ['BuyerHasNotCompletedCheckout', 'NotPaid'])) {
            return 1;
        } elseif (in_array($orderStatus, ['PaymentPending', 'PaymentPendingWithEscrow', 'PaymentPendingWithPaisaPay', 'PaymentPendingWithPaisaPayEscrow', 'PaymentPendingWithPayPal'])) {
            return 2;
        } elseif (in_array($orderStatus, ['Paid', 'PaidCOD', 'PaidWithEscrow', 'PaidWithPaisaPay', 'PaidWithPaisaPayEscrow'])){
            return 3;
        } else {
            return 0;
        }
    }

    /**
     * get monitoring crontab second
     * @return array object
     */
    public function getForMonitoringCrontabSecond()
    {
        return $this->where('auto_buy_flg', 0)
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();
    }
}
