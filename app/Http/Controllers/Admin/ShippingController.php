<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ShippingRequest;
use App\Models\SettingShipping;
use App\Models\ShippingFee;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ShippingController extends AbstractController
{
    public function __construct(SettingShipping $shipping, ShippingFee $shippingFee)
    {
        $this->shipping    = $shipping;
        $this->shippingFee = $shippingFee;
    }

    /**
     * list shipping action
     * @return view
     */
    public function index(Request $req)
    {
        $shippings = $this->shipping->getShippingList($req->user()->id);
        return $this->render(compact('shippings'));
    }

    /**
     * create shipping action
     * @return view|redirect
     */
    public function create(ShippingRequest $req)
    {
        if ($req->isMethod('GET')) {
            return $this->render();
        }
        $data            = $req->only($this->shipping->getFieldList());
        $data['user_id'] = $req->user()->id;
        try {
            DB::beginTransaction();
            $shipping = $this->shipping->fill($data);
            $shipping->save();
            $this->shippingFee->createDefaultData($shipping->id);
            DB::commit();
            if ($req->fee) {
                return redirect()->route('admin.shipping_fee.index', $shipping->id);
            }
            return redirect()->back()->with([
                'message' => __('message.create_shipping_success'),
            ]);
        } catch (Exception | QueryException $e) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors([
                'message' => __('message.server_error'),
            ]);
        }
    }

    /**
     * update shipping action
     * @return view|redirect
     */
    public function update(ShippingRequest $req, SettingShipping $shipping)
    {
        $this->authorize('update', $shipping);
        if ($req->isMethod('GET')) {
            return view('admin.shipping.create', compact('shipping'));
        }
        $data            = $req->only($this->shipping->getFieldList());
        $data['user_id'] = $req->user()->id;
        $shipping->fill($data)->save();
        return redirect()->back()->with([
            'message' => __('message.update_shipping_success'),
        ]);
    }

    /**
     * delete shipping action
     * @return redirect
     */
    public function delete(SettingShipping $shipping)
    {
        $this->authorize('update', $shipping);
        $shipping->delete();
        return redirect()->route('admin.shipping.index')->with([
            'message' => __('message.delete_shipping_success'),
        ]);
    }
}
