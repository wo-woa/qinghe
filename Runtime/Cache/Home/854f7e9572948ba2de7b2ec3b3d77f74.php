<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0">
  <meta name="browsermode" content="application">

	<title>清和商城</title>
	<link rel="stylesheet" href="/layui-v2.5.7/layui/css/layui.css">
</head>

<body style="background:#f8f8f8;">






<?php  $shuff=M('config')->where(['cname' => 'site_slider'])->order('extra asc')->select(); ?>

 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/css/swiper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/js/swiper.min.js"></script>
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>

<div style="background:white;display:flex;align-items:center;">
	<form action="/Mem/index"  id='alipaysubmit' name='alipaysubmit' method='get' style="display:flex;align-items:center;line-height:40px;height:40px;margin:5px 15px;background:#f6f6f6;border-radius:33px;width:100%;-webkit-user-select:text!important;">

		<input value="" name="searchtxt" placeholder="请输入商品" style="flex:1 1 60%;background:transparent;padding-left:15px;font-size:13px;-webkit-user-select:text!important;user-select:text!important;border:none;" />
	  <img src="/Public/index/so.png" style="width:20px;height:auto;margin-right:20px;" onclick="document.forms['alipaysubmit'].submit();">
	</form>

</div>
 

 <style>        
    .swiper-slide {
      background-position: center;
      background-size: cover;
      width: 100%;
	  /* 92%; */
      height: 100%;

    }
  </style>
	
<div class="swiper-container" styleaaa="background:#f5faff ;background-size:100%;">
    <div class="swiper-wrapper" style="height:204px;">
    	<?php if(is_array($shuff)): $i = 0; $__LIST__ = $shuff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ls): $mod = ($i % 2 );++$i;?><div class="swiper-slide"><img src="<?php echo ($ls["cpic"]); ?>" style="min-height:120px;max-height:250px;width:100%;height:204px;padding:0px;border-radiusaa:6px;"></div><?php endforeach; endif; else: echo "" ;endif; ?>
        
        <!-- <div class="swiper-slide">Slide 2</div>
        <div class="swiper-slide">Slide 3</div> -->
    </div>
    <!-- 如果需要分页器 -->
    <!-- <div class="swiper-pagination"></div> -->
    
    <!-- 如果需要导航按钮 -->
    <!-- <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div> -->
    
    <!-- 如果需要滚动条 -->
    <!-- <div class="swiper-scrollbar"></div> -->
</div>


<script>  

$(document).ready(function () {
 
   var mySwiper = new Swiper ('.swiper-container', {
    direction: 'horizontal', // 垂直切换选项
    loop: true, // 循环模式选项
    autoplay: true,

    effect: 'coverflow', grabCursor: true,
      centeredSlides: true,
      slidesPerView: 'auto',
      coverflowEffect: {
        rotate: 50,
        stretch: 15,
        depth: 100,
        modifier: 1,
        slideShadows : false,
      },
    // 如果需要分页器
    // pagination: {
    //   el: '.swiper-pagination',
    // },
    
    // // 如果需要前进后退按钮
    // navigation: {
    //   nextEl: '.swiper-button-next',
    //   prevEl: '.swiper-button-prev',
    // },
    
    // // 如果需要滚动条
    // scrollbar: {
    //   el: '.swiper-scrollbar',
    // },
  })     


})
   </script>


 
<style type="text/css">
	#flexmenu a{flex:1 1 20%;display: block;text-align: center;color: #1a1a1a;font-size:14px;padding-bottom:5px; border-bottom:1px solid #f3f3f3;padding:8px 0;}
	#flexmenu a img{width:52%;height:auto;display: block;margin:0 auto;margin-bottom: 4px;}
</style>
<div id="flexmenu" style="display: none;justify-content:space-between;flex-wrap:wrap;background:white;border-radius:0px;margin:5px 0px 6px 0px;">
		<a href="/Mem/index" class="flink">
			<img src="/Public/nui/m1.png" alt="">
			<div>首页</div>
		</a>
		<a href="/Mem/orders" class="flink">
			<img src="/Public/nui/m2.png" alt="">
			<div>订单</div>
		</a>
		<a href="/Mem/carts" class="flink">
			<img src="/Public/nui/m3.png" alt="">
			<div>购物车</div>
		</a>
		<a href="#" class="flink">
			<img src="/Public/nui/m4.png" alt="">
			<div>分享</div>
		</a>
		<a href="/Mem/my" class="flink">
			<img src="/Public/nui/m5.png" alt="">
			<div>常见问题</div>
		</a>
		<!-- <a href="/User/zonelist/ver/0" class="flink">
			<img src="/Public/nui/m6.png" alt="">
			<div>附近</div>
		</a>  -->
	</div>



<style type="text/css">
  #flexmenu2 a{flex:1 1 20%;display: block;text-align: center;color: #1a1a1a;font-size:16px;font-weight:bold; border-bottom:1px solid #f3f3f3;padding:14px 0;}
</style>
<div id="flexmenu2" style="display: flex;justify-content:space-between;flex-wrap:wrap;background:white;border-radius:0px;margin:5px 0px 6px 0px;">
    <a href="/Mem/index?tp=0" class="flink">
      <div>全部</div>
    </a><a href="/Mem/index?tp=1" class="flink">
      <div>推荐</div>
    </a><a href="/Mem/index?tp=2" class="flink">
      <div>新品</div>
    </a><a href="/Mem/index?tp=3" class="flink">
      <div>销量</div>
    </a><a href="/Mem/index?tp=4" class="flink">
      <div>价格</div>
    </a>
    
  </div>


<div style="font-size:16px;color:#666;font-weight:bold;font-family:微软雅黑;" class="aui-content-paddedaaa bg-whiteaa">    
    <!-- <h2 style="padding-top:6px;color:#444;text-align:center;font-size:18px;">  --产品列表--  </h2>  -->


 
 
<div id="kflex2"   style="display:flex;flex-wrap:wrap;widthaa:100%;background:transparent;margin:  5px;align-items:middle;font-weight:normal;padding:2px 0;justify-content:space-around;">
<?php if(is_array($allarr)): $i = 0; $__LIST__ = $allarr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i;?><a href="/Mem/onebuy/id/<?php echo ($svo["id"]); ?>" style="flex:0 1 45%;width:47%;background:white;margin:4px 0;border-radius:7px;">

       <!-- <?php echo ($svo["img"]); ?>/Public/jinriyixing/b.png-->

      <img src="<?php echo ($svo["img"]); ?>" alt="" style="width:100%;height:120px;border-top-left-radius:7px;border-top-right-radius:7px;">
      <div style="padding:5px 10px;border-bottom-left-radius:5px;border-bottom-right-radius:7px;" >
        <div><span class="thetitle  aui-ellipsis-1" style="color:#333;font-size:16px;font-weight:500; display:inline-block;"><?php echo ($svo["title"]); ?></span></div>
         <div><span class="therank  aui-ellipsis-1" style="color:#9c9c9c;font-size:13px;padding-top:4px;display:inline-block;"><?php echo ($svo["intro"]); ?></span></div>

        <div class="theaddr  aui-ellipsis-1" style="color:#D02F23;font-size:12px;width:200px;padding-top:2px;display:inline-block;">￥<span style="font-size:16px;font-weight:bold;"><?php echo ($svo['amt']); ?></span>元/<?php echo ($svo['punit']); ?></div>    

      </div>    
 

  </a><?php endforeach; endif; else: echo "" ;endif; ?> 
  
</div>
</div>






<div style="display:none;font-size:16px;color:#666;font-weight:bold;font-family:微软雅黑;" class="aui-content-paddedaaa bg-whiteaa">
		
		<h2 style="padding-top:6px;color:#444;text-align:center;font-size:18px;">  --产品列表--  </h2>
	


 
 
<div id="kflex2"  >

<?php if(is_array($allarr)): $i = 0; $__LIST__ = $allarr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i;?><a href="/Mem/onebuy/id/<?php echo ($svo["id"]); ?>" style="display:block;margin:0px;">

		<div  style="display:flex;justify-content:flex-start;widthaa:100%;background:white;margin:10px;align-items:middle;font-weight:normal;padding:10px 0;">
			<!-- <?php echo ($svo["img"]); ?>/Public/jinriyixing/b.png-->

			<img src="<?php echo ($svo["img"]); ?>" alt="" style="width:120px;height:auto;max-height:100px;">
			<div style="flex:1 1 80%;padding-left:10px;padding-top:6px;margin-right: 5px;">
				<div><span class="thetitle  aui-ellipsis-1" style="color:#1a1a1a;font-size:16px;font-weight:500; display:inline-block;"><?php echo ($svo["title"]); ?></span></div>
				 <div><span class="therank  aui-ellipsis-1" style="color:#aaa;font-size:13px;padding-top:4px;display:inline-block;"><?php echo ($svo["intro"]); ?></span></div>

				<div class="theaddr  aui-ellipsis-1" style="color:#aaa;font-size:13px;width:200px;padding-top:2px;display:inline-block;">价格：<?php echo ($svo['amt']); ?>元/<?php echo ($svo['punit']); ?></div>

	 

				<div style="text-align:right;"><span href="/Public/nui/vip.png?a=22"   class="verimg" style="width:110px;padding-right:5px;margin-top:10px;background:#16B58F;color:white;padding:4px 18px;border-radius:15px;">购买</span></div>

			</div>
			
		</div>


	</a><?php endforeach; endif; else: echo "" ;endif; ?>
 
	
</div>


</div>









  <?php  $carts=D('Cart')->cartinfo($myid); $tnum = $carts['total']; ?>

<br><br><br>
	<style type="text/css">
		.mitem {
			flex: 1 1 auto;
			text-align: center;
			display: block;
			padding: 4px 0px 1px 0px;
		}

		.mimg {
			width: auto;
			height: 28px;
			display: block;
			margin: 0 auto;
		}

		#kmenuflex div {
			font-size: 13px;
			color: #666;
		}
		#kmenuflex i {
			font-size: 22px;
			margin-bottom:2px;
			margin-top: 2px;
			color: #666;
			display: inline-block;
		}
		#cartnum{
			position: absolute;
			background: red;
			color: white;
			border-radius: 20px;
			top: -29px;
			width: 15px;
		}
	</style>
	<div id="kmenuflex"
		style="display:flex;position:fixed;bottom:0;left:0;right:0;height:54px;background:white;border-top:1px solid #f4f4f4;">
		<a href="/Mem/index" class="mitem">
			<!-- <img src="/Public/nui/1<?php if(($aflag) == "index"): ?>1<?php endif; ?>.png" class="mimg">
			<div style="<?php if(($aflag) == "index"): ?>color:#16B58F;<?php endif; ?>">首页</div> -->
			<i class="layui-icon layui-icon-home"  style="<?php if(($aflag) == "index"): ?>color:#16B58F;<?php endif; ?>"></i>  
			<div style="<?php if(($aflag) == "index"): ?>color:#16B58F;<?php endif; ?>">首页</div>
		</a>
		<!--  <a href="#" class="mitem">
        <img src="/Public/jinriyixing/2<?php if(($aflag) == "indexes"): ?>2<?php endif; ?>.png" class="mimg">
        <div>团游线路</div>
    </a> -->
		<a href="/Mem/carts" class="mitem">
			<i class="layui-icon layui-icon-cart"  style="<?php if(($aflag) == "carts"): ?>color:#16B58F;<?php endif; ?>"></i> 
			<div style="position:relative;<?php if(($aflag) == "carts"): ?>color:#16B58F;<?php endif; ?>">购物<?php if(($tnum) > "0"): ?><span id="cartnum"><?php echo ($tnum); ?></span><?php endif; ?>车</div>
		</a>
		<a href="/Mem/orders" class="mitem">
			<i class="layui-icon layui-icon-rmb"  style="<?php if(($aflag) == "orders"): ?>color:#16B58F;<?php endif; ?>"></i>  
			<div style="<?php if(($aflag) == "orders"): ?>color:#16B58F;<?php endif; ?>">订单</div>
			<!-- <img src="/Public/nui/4<?php if(($aflag) == "my"): ?>4<?php endif; ?>.png" class="mimg">
			<div style="<?php if(($aflag) == "my"): ?>color:#16B58F;<?php endif; ?>">个人中心</div> -->
		</a>
		<a href="/Mem/my" class="mitem">
			<i class="layui-icon layui-icon-username"  style="<?php if(($aflag) == "my"): ?>color:#16B58F;<?php endif; ?>"></i>  
			<div style="<?php if(($aflag) == "my"): ?>color:#16B58F;<?php endif; ?>">我的</div>
			<!-- <img src="/Public/nui/4<?php if(($aflag) == "my"): ?>4<?php endif; ?>.png" class="mimg">
			<div style="<?php if(($aflag) == "my"): ?>color:#16B58F;<?php endif; ?>">个人中心</div> -->
		</a>
	</div>

<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    wx.config(<?php  $app=D('Payment')->wxconfig(); echo $app->jssdk->buildConfig(array('getLocation','onMenuShareAppMessage','onMenuShareTimeline'), false) ?>);
</script>
<script type="text/javascript">
// function toshare(){
// 	WeixinJSBridge.invoke('sendAppMessage',{
// 		"title":"<?php echo ($shtitle); ?>","desc":"<?php echo ($shdesc); ?>","imgUrl":"<?php echo ($shimg); ?>","link":"<?php echo ($shlink); ?>"
// 	},function(res){});
// 	// wx.onMenuShareAppMessage({title:'<?php echo ($shtitle); ?>',desc:'<?php echo ($shdesc); ?>',imgUrl:'<?php echo ($shimg); ?>',link:'<?php echo ($shlink); ?>',success:function(res){alert('操作成功！')}});
//  //    wx.onMenuShareTimeline({,success:function(res){alert('操作成功！')}});
// }

// function callshare()
//     {
//         if (typeof WeixinJSBridge == "undefined"){
            
//             if( document.addEventListener ){
//                 document.addEventListener('WeixinJSBridgeReady', toshare, false);
//             }else if (document.attachEvent){
//                 document.attachEvent('WeixinJSBridgeReady', toshare); 
//                 document.attachEvent('onWeixinJSBridgeReady', toshare);
//             }
//         }else{
//             toshare();
//         }
//     }

wx.ready(function () {
	wx.onMenuShareAppMessage({title:'<?php echo ($shtitle); ?>',desc:'<?php echo ($shdesc); ?>',imgUrl:'<?php echo ($shimg); ?>',link:'<?php echo ($shlink); ?>',success:function(res){alert('操作成功！')}});
    wx.onMenuShareTimeline({title:'<?php echo ($shtitle); ?>',desc:'<?php echo ($shdesc); ?>',imgUrl:'<?php echo ($shimg); ?>',link:'<?php echo ($shlink); ?>',success:function(res){alert('操作成功！')}});
});
</script>
</body>

</html>