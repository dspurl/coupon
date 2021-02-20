#优惠券
## 说明
- 该插件依赖dsshop项目，而非通用插件
- 支持版本:dshop v2.0及以上
- 已同步版本：dsshop v2.0

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

| **权限名称** | **API**       | **分组**     | **菜单图标** | **显示在菜单栏** |
| ------------ | ------------- | ------------ | ------------ | ---------------- |
| 优惠券       | Coupon        | 工具         | 否           | 是               |
| 优惠券列表   | CouponList    | 工具->优惠券 | 否           | 是               |
| 添加优惠券   | CouponCreate  | 工具->优惠券 | 否           | 否               |
| 优惠券操作   | CouponEdit    | 工具->优惠券 | 否           | 否               |
| 优惠券删除   | CouponDestroy | 工具->优惠券 | 否           | 否               |



#### 七、 进入后台，为管理员分配权限
#### 八、 优惠券使用说明

##### 管理员南

- 优惠券支持三种类型：满减、随机、折扣
- 优惠券可设置使用门槛，设置后，只有购买金额满足门槛金额后才可抵扣
- 优惠券可以设置发放数量，可以设置优惠券结束时间
- 优惠券可以设置每人限领数量

##### 运维指南

- 插件提供`coupon:expire`和`coupon:start`命令来实现优惠券的开始和结束，只需要将这2个命令加入到`coupon:start`中，即可实现优惠券的自动任务
```php
protected $commands = [
    ...
    CouponExpireDispose::class,
    CouponStartDispose::class
];
protected function schedule(Schedule $schedule){
    ...
    $schedule->command('coupon:expire')->dailyAt('00:00')->withoutOverlapping(10);
    $schedule->command('coupon:start')->dailyAt('00:00')->withoutOverlapping(10);
}
```
![](/image/1.png)

##### 开发指南

- 插件并不整合到业务代码中，所以当你安装插件后，需要根据以下步骤进行个性化的开发

###### 优惠券领取

- 插件提供了优惠券展示组件，只要在需要的地方引入组件即可使用，示例代码

``` vue
#product/product.vue
<template>
	<!-- 优惠券按钮 -->
	<view class="c-row b-b" @click="changeShow(true)">
        <text class="tit">优惠券</text>
        <text class="con t-r red"></text>
        <text class="yticon icon-you"></text>
    </view>
	<!-- 优惠券-模态层弹窗  -->
	<coupon :getList="couponList" :show="couponShow" @changeShow="changeShow"/>
</template>
<script>
import coupon from '@/components/coupon'
import CouponApi from '../../api/coupon'
export default {
	components: {
		coupon
	},
    data() {
		return {
			couponList: [],
			couponShow: false
		};
	},
    onLoad(options) {
		this.getCoupon()
	},
    methods: {
    	// 优惠券显示隐藏
		changeShow(val){
			if (!this.hasLogin){
				this.$api.msg('请先登录')
				return false
			}
			this.couponShow = val
		},
		// 获取优惠券列表
		getCoupon(){
			const that = this
			CouponApi.getList({}, function(res) {
				that.couponList = []
				res.data.forEach(item=>{
					let data = {
						id: item.id,
						money: item.cost/100,
						title: item.explain,
						type: item.type,
						time: item.starttime.split(' ')[0].replace(/-/g,".") + "-" + item.endtime.split(' ')[0].replace(/-/g,"."),
					}
					if(item.limit_get && item.user_coupon_count >= item.limit_get){
						data.state = "2"
					} else{
						data.state = "1"
					}
					that.couponList.push(data)
				})
			})
		}
    }
}
</script>
```

###### 已领取的优惠券

- 已领取的优惠券页面已经提供，在所需要的地方指向到优惠券列表页即可，示例代码

``` vue
#user/user.vue
<template>
	<!-- 优惠券按钮 -->
	<view class="tj-item" @click="navTo('/pages/coupon/index?state=1')">
        <text class="num">{{userCouponCount}}</text>
        <text>优惠券</text>
    </view>
</template>
<script>
import Coupon from '../../api/coupon'
export default {
    data() {
		return {
			userCouponCount: 0
		};
	},
    onShow() {
		this.getUserCouponCount()
	},
    methods: {
		// 可用优惠券数量
        getUserCouponCount(){
            const that = this
            Coupon.count(function(res){
                that.userCouponCount = res
            })
        }
    }
}
</script>
```

###### 使用优惠券

- 需要在订单生成页增加优惠券选择功能，以便用户选择可使用的优惠券进行抵扣

``` vue
#order/createOrder.vue
<template>
	<!-- 优惠明细 -->
    <view class="yt-list" v-if="couponMoney">
        <view class="yt-list-cell b-b" @click="toggleMask('show')">
            <view class="cell-icon">
                券
        </view>
            <text class="cell-tit clamp">优惠券</text>
            <text class="cell-tip active">
                {{couponList.length>0 ? couponList[couponIndex].title : '选择优惠券'}}
        </text>
            <text class="cell-more wanjia wanjia-gengduo-d"></text>
        </view>
	</view>
	<!-- 优惠券面板 -->
    <view class="mask" :class="maskState===0 ? 'none' : maskState===1 ? 'show' : ''" @click="toggleMask">
        <view class="mask-content" @click.stop.prevent="stopPrevent">
            <!-- 优惠券页面，仿mt -->
            <view class="coupon-item" v-for="(item,index) in couponList" :key="index" :class="couponIndex === index ? 'on' : ''" @tap="toggleCoupon(index)">
                <view class="con">
                    <view class="left">
                        <text class="title">{{item.title}}</text>
                        <text class="time">有效期至{{item.endTime}}</text>
                    </view>
                    <view class="right">
                        <text class="price">{{item.price}}</text>
                        <text>{{item.sill}}</text>
                    </view>

                    <view class="circle l"></view>
                    <view class="circle r"></view>
                </view>
                <text class="tips">{{item.type}}</text>
            </view>
        </view>
    </view>
</template>
<script>
import Coupon from '../../api/coupon'
export default {
    data() {
		return {
			maskState: 0,
			couponMoney: 0,
			couponIndex: null,
            data: {
                ...
                user_coupon_id: ''
            },
		};
	},
    onShow() {
		this.getCouponList()
	},
    methods: {
		//获取可用的优惠券
        getCouponList(money){
            let that = this
            let couponList = []
            let couponMoney = 0 //默认优惠金额
            let couponIndex = null //默认优惠券index
            Coupon.getUserList({
                money: money,
                limit: 100
            }, function(res) {
                res.data.forEach((item,index)=>{
                    let data = {
                        id: item.id,
                        title: item.coupon.name,
                        explain: item.coupon.explain,
                        endTime: item.coupon.endtime.split(' ')[0].replace(/-/g,".")
                    }

                    switch(item.coupon.type){
                        case 1:
                            data.type = '满减优惠券'
                            data.price = item.coupon.cost/100
                            if(data.price > couponMoney){
                                couponMoney = data.price
                                couponIndex = index
                            }

                            break
                        case 2:
                            data.type = '随机优惠券'
                            data.price = item.coupon.cost/100
                            if(data.price > couponMoney){
                                couponMoney = data.price
                                couponIndex = index
                            }

                            break
                        case 3:
                            data.type = '折扣优惠券'
                            data.price = that.total * item.coupon.cost/10000
                            if(data.price > couponMoney){
                                couponMoney = data.price
                                couponIndex = index
                            }
                            break
                    }
                    if(item.coupon.sill){
                        data.sill = '满' + (item.coupon.sill/100) + '可用'
                    }else{
                        data.sill = '无门槛'
                    }
                    couponList.push(data)
                })
                that.couponList = couponList
                that.couponMoney = couponMoney
                that.couponIndex = couponIndex
                that.outPocketTotal()
            })
        },
        //显示优惠券面板
        toggleMask(type){
            let timer = type === 'show' ? 10 : 300;
            let	state = type === 'show' ? 1 : 0;
            this.maskState = 2;
            setTimeout(()=>{
                this.maskState = state;
            }, timer)
        },
        // 切换优惠券
        toggleCoupon(index){
            this.couponIndex = index
            this.couponMoney = this.couponList[index].price
            this.outPocketTotal()
            this.toggleMask('hide')
        },
        //计算实付金额
        outPocketTotal(){
			let outPocket = 0
			outPocket = outPocket + this.total + this.carriage  - this.couponMoney
			this.outPocket = Number(outPocket.toFixed(2))
        },
        submit(){
            ...
            if(this.couponList.length>0){
                this.data.user_coupon_id = this.couponList[this.couponIndex].id
            }
            ...
		},
    }
}
</script>
```

###### 订单添加优惠信息

```vue
#order/showOrder.vue
<template>
	<!-- 金额明细 -->
	<view class="yt-list">
        ...
        <view class="yt-list-cell b-b" v-if="indentList.coupon_money>0">
            <text class="cell-tit clamp">优惠金额</text>
            <text class="cell-tip red">-￥{{indentList.coupon_money}}</text>
    	</view>
        ...
    </view>
</template>
<script>

</script>
```

###### 后台订单详情添加优惠信息

```vue
#admin/src/views/IndentManagement/Indent/components/Detail.vue
<template>
	<!-- 金额明细 -->
	<view class="yt-list">
        ...
        <el-row type="flex" style="line-height: 35px;font-size: 14px;">
            <el-col v-if="list.good_location" :span="10">收货地址：{{ list.good_location.location }} ({{ list.good_location.address }})</el-col>
            <el-col v-if="list.coupon_money" :span="10">优惠金额：¥ {{ list.coupon_money | 1000 }}</el-col>
         </el-row>
        ...
    </view>
</template>
<script>

</script>
```

###### 添加关联

```php
#api/app/Models/v1/GoodIndent.php
...
/**
  * 订单优惠券
  */
public function GoodIndentUserCoupon(){
    return $this->hasOne(GoodIndentUserCoupon::class,'good_indent_id','id');
}
```
## 如何更新插件
- 将最新版的插件下载，并替换老的插件，后台可一键升级
## 如何卸载插件
- 后台可一键卸载