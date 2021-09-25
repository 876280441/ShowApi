<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SlideRequest;
use App\Models\Slide;
use App\Transformers\SlideTransformer;
use Illuminate\Http\Request;

class SlideController extends BaseController
{
    /**
     * 轮播图列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slides = Slide::paginate(2);
        return $this->response->paginator($slides, new SlideTransformer());
    }

    /**
     * 添加轮播图
     */
    public function store(SlideRequest $request)
    {
        //查询最大的seq
        $max_seq = Slide::max('seq') ?: 0;
        $max_seq++;
        $request->offsetSet('seq', $max_seq);
        $slide = Slide::create($request->all());
        return $this->response->created();
    }

    /**
     * 详情
     */
    public function show(Slide $slide)
    {
        return $this->response->item($slide, new SlideTransformer());
    }

    /**
     * 更新
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SlideRequest $request, Slide $slide)
    {
        $slide->update($request->all());
        return $this->response->noContent();
    }

    /**
     * 删除
     */
    public function destroy(Slide $slide)
    {
        $slide->delete();
        $this->response->noContent();
    }

    /*
     * 排序
     */
    public function seq(Request $request, Slide $slide)
    {
        $slide->seq = $request->input('seq');
        $slide->save();
        return $this->response->noContent();
    }

    /*
    * 禁用或启用
    */
    public function status(Slide $slide)
    {
        $slide->status = $slide->status == 0 ? 1 : 0;
        $slide->save();
        return $this->response->noContent();
    }
}
