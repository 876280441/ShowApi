## 项目说明
laravel实战项目，商城API
## 安装
安装组件:
````
composer install
````

创建`.env`:

````
cp .env.example .env
````

修改`.env配置`，主要是数据库配置和各种发信配置

发布DingoApi配置:
````
php artisan vendor:publish --provider="Dingo\Api\Provider\LaravelServiceProvider"
````

发布jwt配置：
````
# 这条命令会在 config 下增加一个 jwt.php 的配置文件
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
````

生成jwt TOKEN:
````
# 这条命令会在 .env 文件下生成一个加密密钥，如：JWT_SECRET=foobar
php artisan jwt:secret
````
生成权限迁移文件
````
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
````

发布权限相关配置
````
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"
````

运行迁移并填充数据
````
php artisan migrate --seed
````

**使用的是mysql8.0**
如果执行迁移发送错误，请在**app\Providers\AppServiceProvider.php**的boot里面加上 Schema::defaultStringLength(191);
还有取消2021_09_09_081825_create_goods_table.php迁移文件里面的四个索引
