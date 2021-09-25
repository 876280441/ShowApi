<?php


use App\Models\Category;

//如果还是不存在就创建（分类列表函数）
if (!function_exists('categoryTree')) {
    //$status是用户传的需要看的类型值，比如，只看启用的1
    function categoryTree($group = 'goods', $status = false)
    {
        $categories = Category::select(['id', 'pid', 'name', 'level', 'status'])
            ->when($status !== false, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->where('group', $group)
            ->where('pid', 0)//只查询顶级分类的，因为下级分类都让模型关联查询了
            ->with([
                'children' => function ($query) use ($status) {
                    $query->select(['id', 'pid', 'name', 'level', 'status'])
                        ->when($status !== false, function ($query) use ($status) {
                            $query->where('status', $status);
                        });
                },
                'children.children' => function ($query) use ($status) {
                    $query->select(['id', 'pid', 'name', 'level', 'status'])
                        ->when($status !== false, function ($query) use ($status) {
                            $query->where('status', $status);
                        });
                }
            ])//通过模型关联查询数据.children是嵌套查询
            ->get();
        return $categories;
    }
}

/*
 * 缓存没有被禁用的分类
 */
if (!function_exists('cache_category')) {
    function cache_category()
    {
        return cache()->rememberForever('cache_category', function () {
            return categoryTree('goods', 1);
        });
    }
}

/*
 * 缓存所有的分类
 */
if (!function_exists('cache_category_all')) {
    function cache_category_all()
    {
        return cache()->rememberForever('cache_category_all', function () {
            return categoryTree('goods');
        });
    }
}
/*
 * 拼接阿里云OSS地址
 */
if (!function_exists('oss_url')) {
    function oss_url($key)
    {
        //如果没有$key
        if (empty($key)) return '';
        //如果$key包含了http:等，是一个完整的地址，直接返回
        if (strpos($key, 'http://') !== false
            || strpos($key, 'https://') !== false
            || strpos($key, 'data:image') !== false) {
            return $key;
        }
        return config('filesystems')['disks']['oss']['bucket_url'] . '/' . $key;
    }
}
/*
 * 缓存没有被禁用的菜单
 */
if (!function_exists('cache_category_menu')) {
    function cache_category_menu()
    {
        return cache()->rememberForever('cache_category_menu', function () {
            return categoryTree('menu', 1);
        });
    }
}

/*
 * 缓存所有的菜单
 */
if (!function_exists('cache_category_menu_all')) {
    function cache_category_menu_all()
    {
        return cache()->rememberForever('cache_category_menu_all', function () {
            return categoryTree('menu');
        });
    }
}
/*
 * 清空分类缓存
 */
if (!function_exists('forget_cache_category')) {
    function forget_cache_category()
    {
        cache()->forget('cache_category');
        cache()->forget('cache_category_all');
        cache()->forget('cache_category_menu');
        cache()->forget('cache_category_menu_all');
    }
}
/*
 * 所有省份的缓存
 */
if (!function_exists('province')) {
    function province()
    {
        return cache()->rememberForever('province', function () {
            return \App\Models\City::where('level', 1)->get()->keyBy('id');
        });
    }
}
/*
 * 城市相关的缓存
 */
if (!function_exists('city_cache')) {
    function city_cache($level = 1)
    {
        return cache()->rememberForever('city_children' . $level, function () use ($level) {
            return \App\Models\City::where('level', $level)->get()->keyBy('id');
        });
    }
}

/*
 * 通过3,4级地区id，查询完整的省市区信息
 */
if (!function_exists('city_name')) {
    function city_name($city_id)
    {
        $city = \App\Models\City::where('id', $city_id)->with('parent.parent.parent')->first();
        $arr = [
            $city['parent']['parent']['parent']['areaname'] ?: '',
            $city['parent']['parent']['areaname'] ?: '',
            $city['parent']['areaname'] ?: '',
            $city['areaname'] ?: ''
        ];
        return trim(implode(' ', $arr));
    }
}
