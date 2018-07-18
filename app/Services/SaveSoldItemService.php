<?php

namespace App\Services;

use App\Models\User;
use App\Models\SoldItem;
use SimpleXMLElement;
use Illuminate\Support\Facades\Auth;
use Goutte\Client;
use Browser\Casper;
use Illuminate\Support\Facades\Log;
use App\Models\Item;

class SaveSoldItemService extends CommonService
{
    protected $user;
    protected $soldItem;
    protected $product;

    public function __construct(
        User $user,
        Item $product,
        SoldItem $soldItem
    ) {
        $this->user     = $user;
        $this->soldItem = $soldItem;
        $this->product  = $product;
    }

    /**
     * save sold item
     * @return void
     */
    public function saveSoldItem()
    {
        $users = $this->user->getUserForFirstCrontab();
        $this->user->updateUserForFirstCrontab(['monitoring_flg' => 0]);
        foreach ($users as $user) {
            $soldList = $this->getMyEbaySelling($user);
            if (empty($soldList)) {
                $this->user->updateLastMonitoring($user);
                continue;
            }
            $this->saveToTableSlodItem($soldList);
            $this->user->updateLastMonitoring($user);
        }
        dd(1111);
    }

    /**
     * get my ebay selling
     * @param  array $user
     * @return array
     */
    public function getMyEbaySelling($user)
    {
        return $this->callApiGetMyEbaySelling($user);
    }

    /**
     * call api get my ebay selling
     * @param  array $user
     * @return array
     */
    public function callApiGetMyEbaySelling($user)
    {
        $token  = $user->ebay_access_token;
        $body   = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents"></GetMyeBaySellingRequest>');
        $body->addChild('RequesterCredentials')->addChild('eBayAuthToken', $token);
        $soldList = $body->addChild('SoldList');
        $soldList->addChild('Include', 'true');
        $soldList->addChild('DurationInDays', 7);
        $url    = config('api_info.api_common');
        $header = config('api_info.header_api_get_my_ebay_selling');
        $result = $this->callApi($header, $body->asXML(), $url, 'post');
        if (!empty($result['SoldList']) && is_array($result['SoldList'])) {
            return $this->formatDataApiGetMyEbaySelling($result['SoldList']);
        }
        return [];
    }

    /**
     * format data api get my ebay selling
     * @param  array $data
     * @return array
     */
    public function formatDataApiGetMyEbaySelling($data)
    {
        $result = [];
        if (!empty($data['OrderTransactionArray'])) {
            foreach ($data['OrderTransactionArray'] as $value) {
                if (!empty($value['Order'])
                    && !empty($value['Order']['TransactionArray']['Transaction']['Item']['ItemID'])) {
                    $itemId = $value['Order']['TransactionArray']['Transaction']['Item']['ItemID'];
                    $itemId = '110327763724';
                    $itemDetail = $this->product->findByItemId($itemId);
                    if ($itemDetail) {
                        if (!empty($value['Order']['OrderID'])
                            && !empty($value['Order']['TransactionArray']['Transaction']['OrderLineItemID'])
                            && !empty($value['Order']['TransactionArray']['Transaction']['TransactionID'])
                        ) {
                            $item['item_id']            = $itemDetail->id;
                            $item['order_id']           = $value['Order']['OrderID'];
                            $item['order_line_id']      = $value['Order']['TransactionArray']['Transaction']['OrderLineItemID'];
                            $item['transaction_id']     = $value['Order']['TransactionArray']['Transaction']['TransactionID'];
                            $soldItem = $this->soldItem->getByInputId($item);
                            if (!$soldItem) {
                                // $item['type']         = $itemDetail->original_type;
                                $item['ebay_item_id'] = $itemDetail->id;
                                $item['buyer_postal_code']  = !empty($value['Order']['TransactionArray']['Transaction']['Buyer']['BuyerInfo']['ShippingAddress']['PostalCode']) ? $value['Order']['TransactionArray']['Transaction']['Buyer']['BuyerInfo']['ShippingAddress']['PostalCode'] : '';
                                $item['buyer_email']        = !empty($value['Order']['TransactionArray']['Transaction']['Buyer']['Email']) ? $value['Order']['TransactionArray']['Transaction']['Buyer']['Email'] : '';
                                $item['buyer_static_alias'] = !empty($value['Order']['TransactionArray']['Transaction']['Buyer']['StaticAlias']) ? $value['Order']['TransactionArray']['Transaction']['Buyer']['StaticAlias'] : '';
                                $item['buyer_user_id']      = !empty($value['Order']['TransactionArray']['Transaction']['Buyer']['UserID']) ? $value['Order']['TransactionArray']['Transaction']['Buyer']['UserID'] : '';
                                $item['sold_price']         = !empty($value['Order']['TransactionArray']['Transaction']['Item']['SellingStatus']['CurrentPrice']) ? $value['Order']['TransactionArray']['Transaction']['Item']['SellingStatus']['CurrentPrice'] : '';
                                $item['sold_quantity']      = !empty($value['Order']['TransactionArray']['Transaction']['QuantityPurchased']) ? $value['Order']['TransactionArray']['Transaction']['QuantityPurchased'] : '';
                                $item['paid_time']          = !empty($value['Order']['TransactionArray']['Transaction']['PaidTime']) ? $this->createDate($value['Order']['TransactionArray']['Transaction']['PaidTime']) : null;
                                // $item['ship_cost']          = !empty($value['Order']['TransactionArray']['Transaction']['Item']['ShippingDetails']['ShippingServiceOptions']['ShippingServiceCost']) ? $value['Order']['TransactionArray']['Transaction']['Item']['ShippingDetails']['ShippingServiceOptions']['ShippingServiceCost'] : '';
                                $item['order_date']         = !empty($value['Order']['TransactionArray']['Transaction']['CreatedDate']) ? $this->createDate($value['Order']['TransactionArray']['Transaction']['CreatedDate']) : null;
                                $item['order_status']       = !empty($value['Order']['TransactionArray']['Transaction']['SellerPaidStatus']) ? $this->soldItem->getOrderStatus($value['Order']['TransactionArray']['Transaction']['SellerPaidStatus']) : 0;
                                $item['created_at'] = date('Y-m-d H:i:s');
                                $item['updated_at'] = date('Y-m-d H:i:s');
                                $result[] = $item;
                            } else {
                                $soldItem->order_status = $this->getOrderStatus($soldItem->order_status, $value['Order']['TransactionArray']['Transaction']['SellerPaidStatus']);
                                $soldItem->save();
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * save sold item
     * @param  array $soldList
     * @return boolean
     */
    public function saveToTableSlodItem($soldList)
    {
        return $this->soldItem->insert($soldList);
    }

    /**
     * get order status
     * @param  integer $status
     * @param  string $orderStatus
     * @return integer
     */
    public function getOrderStatus($status, $orderStatus)
    {
        if ($status == 1
            && in_array($orderStatus, ['PaymentPending', 'PaymentPendingWithEscrow', 'PaymentPendingWithPaisaPay', 'PaymentPendingWithPaisaPayEscrow', 'PaymentPendingWithPayPal'])
        ) {
            return 2;
        } elseif (in_array($status, [1, 2])
            && in_array($orderStatus, ['Paid', 'PaidCOD', 'PaidWithEscrow', 'PaidWithPaisaPay', 'PaidWithPaisaPayEscrow'])
        ) {
            return 3;
        } else {
            return $status;
        }
    }
}
