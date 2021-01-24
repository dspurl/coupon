#优惠券
## 说明
- 该插件依赖dsshop项目，而非通用插件
- 支持版本:dshop v1.1.0及以上
- 已同步版本：dsshop v1.5

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
#### 六、 进入后台，添加权限

| **权限名称** | **API**      | **分组**     | **菜单图标** | **显示在菜单栏** |
| ------------ | ------------ | ------------ | ------------ | ---------------- |
| 优惠券       | Coupon       | 工具         | 否           | 是               |
| 优惠券列表   | CouponList   | 工具->优惠券 | 否           | 是               |
| 添加优惠券   | CreateCoupon | 工具->优惠券 | 否           | 否               |
| 优惠券操作   | EditCoupon   | 工具->优惠券 | 否           | 否               |
| 优惠券删除   | DeleteCoupon | 工具->优惠券 | 否           | 否               |



#### 七、 进入后台，为管理员分配权限
#### 八、 优惠券插件示例代码，以下仅供参考，请根据自己的业务自行实现
- `admin/src/views/Indent/components/Detail.vue`
![](/image/23.png)
- `api/app/Http/Controllers/v1/Admin/IndentController.php`
![](/image/19.png)
- `api/app/Http/Controllers/v1/Element/GoodIndentController.php`
![](/image/20.png)
![](/image/21.png)
![](/image/22.png)
- `trade/Dsshop/pages/order/createOrder.vue`
![](/image/11.png)
![](/image/12.png)
![](/image/13.png)
![](/image/14.png)
![](/image/15.png)
![](/image/16.png)
![](/image/17.png)
![](/image/18.png)
- `trade/Dsshop/pages/order/showOrder.vue`
![](/image/25.png)
- `trade/Dsshop/pages/product/product.vue`
![](/image/5.png)
![](/image/6.png)
![](/image/7.png)
![](/image/8.png)
![](/image/9.png)
![](/image/10.png)
- `trade/Dsshop/pages/user/user.vue`
![](/image/1.png)
![](/image/2.png)
![](/image/3.png)
![](/image/4.png)
- `api/app/Models/v1/GoodIndent.php`
![](/image/24.png)
- >`api/app/Console/Kernel.php`
![](/image/24.png)
## 如何更新插件
- 将最新版的插件下载，并替换老的插件，后台可一键升级
## 如何卸载插件
- 后台可一键卸载