<?php

namespace App\Services;

use App\Models\User;
use SimpleXMLElement;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Models\ChangeItem;

class UpdateTableChangeItemService extends CommonService
{
    protected $user;
    protected $product;
    protected $changeItem;

    public function __construct(
        User $user,
        Item $product,
        ChangeItem $changeItem
    ) {
        $this->user       = $user;
        $this->product    = $product;
        $this->changeItem = $changeItem;
    }

    /**
     * update table change item
     * @return void
     */
    public function updateTableChangeItem()
    {
        Log::info('--------------> Start update table change item command <--------------');
        $changeItem = $this->changeItem->getItemMonitoring();
        $this->changeItem->updateStatusProcessing();
        $changeItemOrUser = $this->getChangeItemOfUser($changeItem);
        $this->changeItem($changeItemOrUser);
        Log::info('--------------> Finish update table change item command <--------------');
    }

    /**
     * get change item of user
     * @param  array $changeItem
     * @return array
     */
    public function getChangeItemOfUser($changeItem)
    {
        $result = [];
        foreach ($changeItem as $key => $item) {
            $product = $this->product->findById($item['item_id'], false);
            if (!$product) {
                continue;
            }
            $userId                         = $product->user_id;
            $result[$userId]['id'][]        = $product->id;
            $result[$userId]['item_id'][]   = $product->item_id;
            $result[$userId]['new_price'][] = $item['new_price'];
            $result[$userId]['quantity'][]  = $product->quantity;
        }
        return $result;
    }

    /**
     * change item
     * @param  array $changeItemOrUser
     * @return void
     */
    public function changeItem($changeItemOrUser)
    {
        foreach ($changeItemOrUser as $userId => $value) {
            try {
                $user = $this->user->findById($userId);
                if (!$user) {
                    continue;
                }
                $token = $user->ebay_access_token;
                if (count($value['item_id']) > 4) {
                    $arrayItemsId = array_chunk($value['item_id'], 4);
                    $arrayId = array_chunk($value['id'], 4);
                    foreach ($arrayItemsId as $key => $value) {
                        try {
                            $item['item_id'] = $value;
                            $item['id'] = $arrayId[$key];
                            $this->sloveChangeItem($item, $token);
                        } catch (Exception $ex) {
                            Log::info($ex);
                            Log::info('Rollback item');
                            Log::info($item['item_id']);
                            $this->rollbackItem($item['id']);
                        }
                    }
                } else {
                    $this->sloveChangeItem($value, $token);
                }
            } catch (Exception $ex) {
                Log::info($ex);
                Log::info('Rollback item');
                Log::info($value['item_id']);
                $this->rollbackItem($value['id']);
            } 
        }
        return true;
    }

    /**
     * rollback item
     * @param  array $itemIds
     * @return void
     */
    public function rollbackItem($itemIds)
    {
        $this->changeItem->updateStatusProcessingWaiting($itemIds);
    }

    /**
     * log error
     * @param  array $itemIds
     * @return void
     */
    public function logError($itemIds)
    {
        Log::info('Change item error');
        Log::info($itemIds);
    }

    /**
     * log success
     * @param  array $itemIds
     * @return void
     */
    public function logSuccess($itemIds)
    {
        Log::info('Change item success');
        Log::info($itemIds);
    }

    /**
     * slove change item
     * @param  array $value
     * @param  string $token
     * @return void
     */
    public function sloveChangeItem($value, $token)
    {
        $result = $this->callApiChangeItem($value, $token);
        if ($result) {
            if ($this->changeItem->updateDone($value['item_id']) == false) {
                $this->rollbackItem($value['id']);
                $this->logError($value['item_id']);
            } else {
                $this->logSuccess($value['item_id']);
            }
        } else {
            $this->rollbackItem($value['id']);
            $this->logError($value['item_id']);
        }
    }

    /**
     * call api change item
     * @param  array $data
     * @param  string $token
     * @return boolean
     */
    public function callApiChangeItem($data, $token)
    {
        $body = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><ReviseInventoryStatusRequest xmlns="urn:ebay:apis:eBLBaseComponents"></ReviseInventoryStatusRequest>');
        $body->addChild('RequesterCredentials')->addChild('eBayAuthToken', $token);
        foreach ($data['id'] as $key => $value) {
            $endItem = $body->addChild('InventoryStatus');
            $endItem->addChild('ItemID', $data['item_id'][$key]);
            $endItem->addChild('Quantity', $data['quantity'][$key]);
            // $endItem->addChild('SKU', $item);
            $endItem->addChild('StartPrice', $data['new_price'][$key]);
        }
        $url    = config('api_info.api_common');
        $header = config('api_info.header_api_change_item');
        $result = $this->callApi($header, $body->asXML(), $url, 'post');
        if ($result['Ack'] == 'Failure') {
            return false;
        }
        if ($this->product->endListItem($data) !== false) {
            return true;
        }
        return false;
    }
}
