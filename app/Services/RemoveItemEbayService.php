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
use App\Models\WithdrawalItem;

class RemoveItemEbayService extends CommonService
{
    protected $user;
    protected $soldItem;
    protected $product;
    protected $withdrawalItem;

    public function __construct(
        User $user,
        Item $product,
        WithdrawalItem $withdrawalItem,
        SoldItem $soldItem
    ) {
        $this->user           = $user;
        $this->soldItem       = $soldItem;
        $this->product        = $product;
        $this->withdrawalItem = $withdrawalItem;
    }

    /**
     * remove item ebay
     * @return void
     */
    public function removeItemEbay()
    {
        Log::info('--------------> Start remove item ebay <--------------');
        $withdrawalItem = $this->withdrawalItem->getItemMonitoring();
        $this->withdrawalItem->updateStatusProcessing();
        $withdrawalItemOfUser = $this->getWithDrawalItemOfUser($withdrawalItem);
        $this->removeItem($withdrawalItemOfUser);
        Log::info('--------------> Finish remove item ebay <--------------');
    }

    /**
     * get with drawal item of user
     * @param  array $withdrawalItem
     * @return array
     */
    public function getWithDrawalItemOfUser($withdrawalItem)
    {
        $result = [];
        foreach ($withdrawalItem as $key => $item) {
            $product = $this->product->findById($item['item_id'], false);
            if (!$product) {
                continue;
            }
            $userId = $product->user_id;
            $result[$userId]['id'][] = $product->id;
            $result[$userId]['item_id'][] = $product->item_id;
        }
        return $result;
    }

    /**
     * remove item
     * @param  array $withdrawalItemOfUser
     * @return void
     */
    public function removeItem($withdrawalItemOfUser)
    {
        foreach ($withdrawalItemOfUser as $userId => $value) {
            try {
                $user = $this->user->findById($userId);
                if (!$user) {
                    continue;
                }
                $token = $user->ebay_access_token;
                if (count($value['item_id']) > 10) {
                    $arrayItemsId = array_chunk($value['item_id'], 10);
                    $arrayId = array_chunk($value['id'], 10);
                    foreach ($arrayItemsId as $key => $value) {
                        try {
                            $item['item_id'] = $value;
                            $item['id'] = $arrayId[$key];
                            $this->sloveRemoveItem($item, $token);
                        } catch (Exception $ex) {
                            Log::info($ex);
                            Log::info('Rollback item');
                            Log::info($item['item_id']);
                            $this->rollbackItem($item['id']);
                        }
                    }
                } else {
                    $this->sloveRemoveItem($value, $token);
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
        $this->withdrawalItem->updateStatusProcessingWaiting($itemIds);
        $this->product->updateStatusSelling($itemIds);
    }

    /**
     * log error
     * @param  array $itemIds
     * @return void
     */
    public function logError($itemIds)
    {
        Log::info('Remove item error');
        Log::info($itemIds);
    }

    /**
     * log success
     * @param  array $itemIds
     * @return void
     */
    public function logSuccess($itemIds)
    {
        Log::info('Remove item success');
        Log::info($itemIds);
    }

    public function sloveRemoveItem($value, $token)
    {
        $result = $this->endItem($value['item_id'], $token);
        if ($result) {
            if ($this->withdrawalItem->updateDone($value['item_id']) == false) {
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
}
