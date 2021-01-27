<?php if (!defined('THINK_PATH')) exit();?><html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>我的订单</title>
	<link rel="stylesheet" href="/layui-v2.5.7/layui/css/layui.css">
	<script  src="/layui-v2.5.7/layer.mobile-v2.0/layer_mobile/layer.js"></script>  
</head>

<body style="background:#f8f8f8;font-family:微软雅黑;">

<div style="height:44px;width:100%;background:white;border-bottom:1px solid #eee;display:flex;line-height:44px;align-items:center;text-align:center;font-weight:500;overflow:hidden;font-size:16px;color:#333;">
	<a href="/Mem/my" style="width:40px;"><i class="layui-icon layui-icon-left"  ></i></a>
	<div style="flex:1 1 60%;">我的订单</div>
	<div style="width:50px;"></div>	
</div>

 
<style type="text/css">
  #flexmenu2 a{flex:1 1 20%;display: block;text-align: center;color: #1a1a1a;font-size:14px; border-bottom:1px solid #f3f3f3;padding:12px 0;}
</style>
<div id="flexmenu2" style="display: flex;justify-content:space-between;flex-wrap:wrap;background:white;border-radius:0px;margin:5px 0px 6px 0px;">
    <a href="/Mem/orders?tp=0" class="flink">
      <div>全部</div>
    </a><a href="/Mem/orders?tp=1" class="flink">
      <div>待付款</div>
    </a><a href="/Mem/orders?tp=2" class="flink">
      <div>已付款</div>
    </a><a href="/Mem/orders?tp=3" class="flink">
      <div>已完成</div>
    </a> 
    
  </div>


<?php if(empty($allarr)): ?><div style="text-align:center;">
		<div>		
			<img src="/Public/index/gw.png" style="width:30%;margin-top:35px;">	
		</div>
		<div style="color:#999;margin:20px;font-size:14px;">暂无订单~</div>
		<div><a href="/Mem/index" style="border:1px solid #45C995;color:#45C995;background:white;border-radius:18px;font-size:15px;padding:6px 14px;">去逛逛</a></div>
	</div><?php endif; ?>


  	<?php if(is_array($allarr)): $i = 0; $__LIST__ = $allarr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$one): $mod = ($i % 2 );++$i;?><div style="background:white;">  	
<?php if(is_array($one['cart'])): $i = 0; $__LIST__ = $one['cart'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$two): $mod = ($i % 2 );++$i;?><div style="display:flex;align-items:flex-start;padding:10px;border-bottom:1px solid #eee;">
		<img src="<?php echo ($two["img"]); ?>" style="width:100px;height:80px;">
		<div style="flex:1 1 70%;padding:2px 8px;color:#343434;font-size:15px;"><?php echo ($two["title"]); ?></div>
		<div style="width:80px;text-align:right;">
			<div>￥<?php echo ($two["amt"]); ?></div>
			<div>x <?php echo ($two["num"]); ?></div>
			<div>= ￥<?php echo ($two["tamt"]); ?></div>
		</div>
	</div><?php endforeach; endif; else: echo "" ;endif; ?>
	<div style="text-align:right;font-size:16px;padding:12px 10px;">总金额：￥<?php echo ($one["amt"]); ?></div>
	<div style="text-align:right;font-size:16px;padding:12px 10px;">状态：<?php echo ($one["states_name"]); ?></div>

<?php if(($one['okstates']) == "3"): ?><div style="text-align:right;font-size:16px;padding:12px 10px;">
		<a href="/Mem/gopay/id/<?php echo ($one["id"]); ?>" style="border:1px solid #F53B3F;color:#F53B3F;background:white;border-radius:18px;font-size:15px;padding:6px 14px;">去付款</a>
	</div><?php endif; ?>

</div>

<br>
<a href="#" style="padding:3px 15px;display:flex;align-items:center;justify-contentaa:space-between;background:white;display:none;">
			<!-- <i class="layui-icon layui-icon-rmb"  style="width:30px;flex-shrink:0;font-size:1.5em;" ></i> -->




			<div style="flex:1 1 60%;margin:8px 5px;">
				<div style="font-size:1.15em;margin:1px 0px 8px 0px;color:bold;">金额：<?php echo ($one["amt"]); ?></div>
				<!-- <div style="color:#666;"><span style="font-size:1.05em;margin-right:10px;color:#333;"><?php echo ($maddr["realname"]); ?>aa </span> <?php echo ($maddr["tel"]); ?>bb</div> -->
				<div style="color:#777;padding-top:4px;">时间：<?php echo (date('Y-m-d H:i',$one['addtime'])); ?></div>	
				<div style="color:#777;padding-top:4px;"><?php echo ($one["title"]); ?></div>	
				<div style="color:#777;padding-top:4px;">收货人：<?php echo ($one["useraddr"]); ?></div>	
<div style="color:#777;padding-top:4px;">状态：<?php echo ($one["states_name"]); ?></div>
					<!-- <div style="color:#777;padding-top:4px;">状态：<?php echo ($one['states_name']); ?></div>			 -->
			</div>
			<!-- <i class="layui-icon layui-icon-right"  style="width:20px;flex-shrink:0;" ></i> -->

		</a><?php endforeach; endif; else: echo "" ;endif; ?>





	<?php if(is_array($allarraaa)): $i = 0; $__LIST__ = $allarraaa;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$one): $mod = ($i % 2 );++$i; ?>
<br>
<a href="#" style="padding:3px 15px;display:flex;align-items:center;justify-contentaa:space-between;background:white;">
			<i class="layui-icon layui-icon-rmb"  style="width:30px;flex-shrink:0;font-size:1.5em;" ></i>
			<div style="flex:1 1 60%;margin:8px 5px;">
				<div style="font-size:1.15em;margin:1px 0px 8px 0px;color:bold;">金额：<?php echo ($one["amt"]); ?></div>
				<!-- <div style="color:#666;"><span style="font-size:1.05em;margin-right:10px;color:#333;"><?php echo ($maddr["realname"]); ?>aa </span> <?php echo ($maddr["tel"]); ?>bb</div> -->
				<div style="color:#777;padding-top:4px;">时间：<?php echo (date('Y-m-d H:i',$one['addtime'])); ?></div>	
				<div style="color:#777;padding-top:4px;"><?php echo ($one["title"]); ?></div>	
				<div style="color:#777;padding-top:4px;">收货人：<?php echo ($one["useraddr"]); ?></div>	
<div style="color:#777;padding-top:4px;">状态：<?php echo ($one["states_name"]); ?></div>
					<!-- <div style="color:#777;padding-top:4px;">状态：<?php echo ($one['states_name']); ?></div>			 -->
			</div>
			<!-- <i class="layui-icon layui-icon-right"  style="width:20px;flex-shrink:0;" ></i> -->

		</a><?php endforeach; endif; else: echo "" ;endif; ?>
		
 


<br><br><br>

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


</body>

</html>