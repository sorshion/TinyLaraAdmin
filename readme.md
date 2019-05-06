# 基于laravel5.8 + layuiadmin 构建
#### 环境
* PHP >= 7.2

#### 安装
* git clone https://github.com/sorshion/TinyLaraAdmin.git
* 复制.env.example 为.env
* 配置.env 里的数据库连接信息
* composer update
* php artisan migrate
* php artisan db:seed
* php artisan key:generate
* 登录后台：host/admin   帐号：root  密码：123456



### 待完善
1. 剪切板
2. 数据表格，字段影响
3. 数据表格，刷新
4. 弹窗可放大
5. 数据表格可切换（不重要）

插件
1. image-select
2. fullavatareditor
3. jquery-multi-select
4. jquery-multiselect
5. jquery-treeMultiple
6. multiselect
7. My97DatePicker