<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ShippingFeeRequest;
use App\Models\SettingShipping;
use App\Models\ShippingFee;

class ShippingFeeController extends AbstractController
{
    public function __construct(SettingShipping $shipping, ShippingFee $shippingFee)
    {
        $this->shipping    = $shipping;
        $this->shippingFee = $shippingFee;
    }

    /**
     * list fee of shipping
     * @param  SettingShipping $shipping
     * @return view
     */
    public function index(SettingShipping $shipping)
    {
        $this->authorize('update', $shipping);
        $shippingFees = $this->shippingFee->getFeeListByShipping($shipping->id);
        return $this->render(compact('shipping', 'shippingFees'));
    }

    /**
     * create or update fee
     * @param  ShippingFeeRequest $req
     * @param  SettingShipping    $shipping
     * @param  integer|null       $shippingFeeId
     * @return view|redirect
     */
    public function create(ShippingFeeRequest $req, SettingShipping $shipping, $shippingFeeId = null)
    {
        $this->authorize('update', $shipping);
        if ($shippingFeeId) {
            $shippingFee = $this->shippingFee->findOrFail($shippingFeeId);
            if ($shipping->id != $shippingFee->shipping_id) {
                abort(404);
            }
        }
        if ($req->isMethod('GET')) {
            return $this->render(compact('shipping', 'shippingFee'));
        }
        $data                = $req->only($this->shippingFee->getFieldList());
        $data['shipping_id'] = $shipping->id;
        $this->shippingFee->updateOrCreate(['id' => $shippingFeeId], $data);
        return redirect()->back()->with([
            'message' => __('message.' . ($shippingFeeId ? 'update' : 'create') . '_shipping_fee_success'),
        ]);
    }

    /**
     * delete shipping fee action
     * @return redirect
     */
    public function delete(SettingShipping $shipping, $shippingFeeId)
    {
        $this->authorize('update', $shipping);
        $shippingFee = $this->shippingFee->findOrFail($shippingFeeId);
        if ($shipping->id != $shippingFee->shipping_id) {
            abort(404);
        }
        $shippingFee->delete();
        return redirect()->route('admin.shipping_fee.index', $shipping->id)->with([
            'message' => __('message.delete_shipping_id_success'),
        ]);
    }
}
