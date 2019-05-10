# 基于laravel5.8 + layuiadmin 构建
#### 环境
* PHP >= 7.2
* MySQL >= 5.5

#### 安装
* git clone https://github.com/sorshion/TinyLaraAdmin.git
* 复制.env.example 为.env
* 配置.env 里的数据库连接信息
* composer update
* php artisan migrate
* php artisan db:seed
* php artisan key:generate
* 登录后台：host/admin   帐号：root  密码：123456
