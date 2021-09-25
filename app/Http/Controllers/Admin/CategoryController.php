<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * 分类列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //接收查询传递的状态
        $status = $request->query('type');
        if ($status === 'all') {
            //使用分类列表函数（自定义）
            return cache_category_all();
        } else {
            return cache_category();
        }

    }

    /**
     * 添加分类
     *
     */
    public function store(Request $request)
    {
        //调用自定义的验证用户提交参数的函数
        $insertData = $this->checkInput($request);
        if (!is_array($insertData)) {
            return $insertData;
        }
        Category::create($insertData);
        return $this->response->created();
    }

    /**
     * 分类详情
     *
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     * 更新分类
     *
     */
    public function update(Request $request, Category $category)
    {
        //调用自定义的验证用户提交参数的函数
        $updateData = $this->checkInput($request);
        if (!is_array($updateData)) {
            return $updateData;
        }
        $category->update($updateData);
        return $this->response->noContent();
    }

    /*
     * 分类禁用和启用
     */
    public function status(Category $category)
    {
        $category->status = $category->status === 1 ? 0 : 1;
        $category->save();
        return $this->response->noContent();
    }

    /*
     *验证提交的参数
     */
    protected function checkInput($request)
    {
        //验证字段
        $request->validate(
            [
                'name' => 'required|max:16'
            ],
            [
                'name.required' => '分类名称不得为空',
                'name.max' => '分类名称不得大于16个字符',
            ]
        );
        //获取分组
        $group = $request->input('group', 'goods');
        //获取用户输入的pid,如果不存在就默认为顶级分类0
        $pid = $request->input('pid', 0);
        //获取分类id等级
        $level = $pid == 0 ? 1 : (Category::find($pid)->level + 1);
        //判断分类等级是否超过了三级
        if ($level > 3) {
            return $this->response->errorBadRequest('不能超过三级分类');
        }
        return [
            'pid' => $pid,
            'level' => $level,
            'name' => $request->input('name'),
            'group' => $group
        ];
    }
}
