#优惠券
## 说明
- 该插件依赖dsshop项目，而非通用插件
- 支持版本:dshop v1.1.0及以上
- 已同步版本：dsshop v1.4

## 功能介绍
- 支持满减、随机（暂不支持后台生成领取地址，故随机无法正常使用）、折扣三种优惠券类型
- 优惠券支持设置开始时间、到期时间、领取数量、门槛设置
- 支持用户商品详情页直接领取满减、折扣优惠券
- 支持用户购买商品时，根据优惠券使用条件自动选取面额最大的优惠券
- 支持退款后返还优惠券
- 支持优惠券自动开始、结束
- 支持优惠券提前开始和提前结束

## 使用说明
#### 一、 下载coupon最新版
#### 二、 解压coupon到项目plugin目录下
#### 三、 登录dshop后台，进入插件列表
#### 四、 在线安装（请保持dshop的目录结构，如已部署到线上，请在本地测试环境安装，因涉及admin和uni-app，不建议在线安装）
#### 五、 进入api目录执行数据库迁移使用

```
php artisan migrate
```
#### 六、 进入数据库，导入 `coupon/coupon.sql` SQL文件
#### 七、 进入后台，为管理员分配权限
#### 八、 使用优惠券插件，如这是第一个插件，可直接按以下步骤替换目标文件即可，如有其它插件，请根据下图标记进行手动移动代码
- `coupon/example/admin/Detail.vue`->`admin/src/views/Indent/components/Detail.vue`
![](/image/23.png)
- `coupon/example/api/Admin/IndentController.php`->`api/app/Http/Controllers/v1/Admin/IndentController.php`
![](/image/19.png)
- `coupon/example/api/Element/GoodIndentController.php`->`api/app/Http/Controllers/v1/Element/GoodIndentController.php`
![](/image/20.png)
![](/image/21.png)
![](/image/22.png)
- `coupon/example/trade/order/createOrder.vue`->`trade/Dsshop/pages/order/createOrder.vue`
![](/image/11.png)
![](/image/12.png)
![](/image/13.png)
![](/image/14.png)
![](/image/15.png)
![](/image/16.png)
![](/image/17.png)
![](/image/18.png)
- `coupon/example/trade/order/showOrder.vue`->`trade/Dsshop/pages/order/showOrder.vue`
![](/image/25.png)
- `coupon/example/trade/product/product.vue`->`trade/Dsshop/pages/product/product.vue`
![](/image/5.png)
![](/image/6.png)
![](/image/7.png)
![](/image/8.png)
![](/image/9.png)
![](/image/10.png)
- `coupon/example/trade/user/user.vue`->`trade/Dsshop/pages/user/user.vue`
![](/image/1.png)
![](/image/2.png)
![](/image/3.png)
![](/image/4.png)
- `coupon/example/api/Models/GoodIndent.php`->`api/app/Models/v1/GoodIndent.php`
![](/image/24.png)
- `coupon/example/api/Console/Kernel.php`->`api/app/Console/Kernel.php`
![](/image/24.png)
#### 九、 测试创建优惠券、领取优惠券、使用优惠券、退款返还优惠券，如果功能都能正常使用，则说明你的插件安装成功
## 如何更新插件
- 首先请备份项目，升级可能产生问题（如自行修改了涉及到升级的文件、下载的文件不全等问题）
- 首先查看新版本支持的dshop的版本，如果符合，可通过后台直接升级，升级将会自动覆盖原有文件
- 如果升级涉及到手动修改代码部分，升级说明中会进行讲解
## 如何卸载插件
- 插件安装后不建议卸载，因为涉及到多处手动修改的代码
- 可以按以上安装方式反向操作，即可卸载