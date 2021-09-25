<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddressRequest;
use App\Models\Address;
use App\Transformers\AddressTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends BaseController
{
    /**
     * 我的所有地址
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $address = Address::where('user_id', auth('api')->id())->get();
        return $this->response->collection($address, new AddressTransformer());
    }

    /**
     * 添加地址
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressRequest $request)
    {
        $request->offsetSet('user_id', auth('api')->id());
        Address::create($request->all());
        return $this->response->created();
    }

    /**
     * 地址详情
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        return $this->response->item($address, new AddressTransformer());
    }

    /**
     * 更新地址
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request, Address $address)
    {
        $address->update($request->all());
        return $this->response->noContent();
    }

    /**
     * 删除地址
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        $address->delete();
        return $this->response->noContent();
    }

    /*
     * 是否默认地址
     */
    public function IsDefault(Address $address)
    {
        //如果当前是否已经处于置顶状态
        if ($address->is_default == 1)
            return $this->response->errorBadRequest('当前地址已是默认地址，不得重复设置');
        try {
            DB::beginTransaction();//开启事务
            //先把所有的地址都设置为非默认
            $default_address = Address::where('user_id', auth('api')->id())
                ->where('is_default', 1)
                ->first();
            if (!empty($default_address)) {
                $default_address->is_default = 0;
                $default_address->save();
            }
            //再设置当前请求的这个地址为默认地址
            $address->is_default = 1;
            $address->save();
            DB::commit();//提交数据
        } catch (\Exception $e) {
            DB::rollBack();//回滚异常
            throw $e;
        }
        return $this->response->noContent();
    }
}
