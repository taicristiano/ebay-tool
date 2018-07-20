<?php

namespace App\Models;

class ChangeItem extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_change_items';

    protected $guarded = [];

    const STATUS_PROCESS_WAITING = 1;
    const STATUS_PROCESS         = 2;
    const STATUS_DONE            = 3;

    /**
     * get status process waiting
     * @return integer
     */
    public function getStatusProcessWaiting()
    {
        return self::STATUS_PROCESS_WAITING;
    }

    /**
     * get status process
     * @return integer
     */
    public function getStatusProcess()
    {
        return self::STATUS_PROCESS;
    }

    /**
     * get status done
     * @return integer
     */
    public function getStatusDone()
    {
        return self::STATUS_DONE;
    }

    /**
     * get item monitoring
     * @return array
     */
    public function getItemMonitoring()
    {
        return $this->select('id', 'item_id', 'new_price')
            ->where('status', $this->getStatusProcessWaiting())
            ->whereNull('deleted_at')
            ->get()
            ->toArray();
    }

    /**
     * update status processing
     * @return integer
     */
    public function updateStatusProcessing()
    {
        return $this->where('status', $this->getStatusProcessWaiting())
            ->whereNull('deleted_at')
            ->update(['status' => $this->getStatusProcess()]);
    }

    /**
     * update status done
     * @param array $itemIds
     * @return integer
     */
    public function updateDone($itemIds)
    {
        return $this->whereIn('item_id', $itemIds)
            ->whereNull('deleted_at')
            ->update(['status' => $this->getStatusDone()]);
    }

    /**
     * update status processing waiting
     * @param array $itemIds
     * @return integer
     */
    public function updateStatusProcessingWaiting($itemIds)
    {
        return $this->whereIn('item_id', $itemIds)
            ->whereNull('deleted_at')
            ->update(['status' => $this->getStatusProcessWaiting()]);
    }
}
