<?php if (!defined('THINK_PATH')) exit(); $carts=D('Cart')->cartinfo($myid); $tnum = $carts['total']; $carr = $carts['carts']; $tamt = $carts['tamt']; $payid = $carts['payid']; ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>清和商城</title>
	<link rel="stylesheet" href="/layui-v2.5.7/layui/css/layui.css">


	<script  src="/layui-v2.5.7/layer.mobile-v2.0/layer_mobile/layer.js"></script>

  
</head>

<body style="background:#f8f8f8;font-family:微软雅黑;">

<div style="height:44px;width:100%;background:white;border-bottom:1px solid #eee;display:flex;line-height:44px;align-items:center;text-align:center;font-weight:500;overflow:hidden;font-size:16px;color:#333;">
	<a href="/Mem" style="width:40px;"><i class="layui-icon layui-icon-left"  ></i></a>
	<div style="flex:1 1 60%;">购物车</div>
	<div style="width:50px;"></div>	
</div>

 


<div style="margin-top:5px;">
	
<?php if(empty($carr)): ?><div style="text-align:center;">
		<div>		
			<img src="/Public/index/gw.png" style="width:30%;margin-top:35px;">	
		</div>
		<div style="color:#999;margin:20px;font-size:14px;">购物车没有商品哦~</div>
		<div><a href="/Mem/index" style="border:1px solid #45C995;color:#45C995;background:white;border-radius:18px;font-size:15px;padding:6px 14px;">去逛逛</a></div>
	</div><?php endif; ?>

<?php if(is_array($carr)): $i = 0; $__LIST__ = $carr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$one): $mod = ($i % 2 );++$i;?><div  style="display:flex;justify-content:flex-start;widthaa:100%;background:white;margin:10px;align-items:middle;font-weight:normal;padding:10px 0;">
			<!-- <?php echo ($svo["img"]); ?>/Public/jinriyixing/b.png-->
			<img src="<?php echo ($one["img"]); ?>" alt="" style="width:90px;height:auto;max-height:100px;">
			<div style="flex:1 1 80%;padding-left:10px;padding-top:2px;margin-right: 5px;">
				<div style="text-align:right;position:relative;top:5px;"><span href="/Public/nui/vip.png?a=22" data-id="<?php echo ($one["id"]); ?>"  class="verimg delone" style="width:110px;background:#aaa;color:white;padding:4px 8px;border-radius:15px;font-size:12px">X</span></div>
				<div style="margin-top:-18px;">
					<div><span class="thetitle  aui-ellipsis-1" style="color:#1a1a1a;font-size:16px;font-weight:500; display:inline-block;"><?php echo ($one["title"]); ?></span></div>
				 	<div><span class="therank  aui-ellipsis-1" style="color:#aaa;font-size:13px;padding-top:4px;display:inline-block;"><?php echo ($one["intro"]); ?></span></div>

					<div class="theaddr  aui-ellipsis-1" style="color:#aaa;font-size:13px;width:200px;padding-top:2px;display:inline-block;">单价：<?php echo ($one['amt']); ?>元/<?php echo ($one['punit']); ?></div>

					<div class="theaddr  aui-ellipsis-1" style="color:#aaa;font-size:13px;width:200px;padding-top:2px;display:inline-block;">总价：<?php echo ($one['tamt']); ?>元</div>

						<div style="width:120px;display:flex;align-items:center;border:1px solid #eee;line-height:30px;text-align:center;float:right;">
							<i class="layui-icon layui-icon-subtraction subnum"   style="width:40px;background:#eee;" data-id="<?php echo ($one["id"]); ?>" ></i>
							<input style="flex:1 1 40px;width:42px;border-left:1px solid #eee;border-right:1px solid #eee;border-top:none;border-bottom:none;line-height:30px;text-align:center;"  value="<?php echo ($one['num']); ?>" readonly="" />	
							<i class="layui-icon layui-icon-addition addnum"  style="width:40px;background:#eee;" data-id="<?php echo ($one["id"]); ?>" ></i>							
						</div>
				</div>
			</div>			
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
</div>


	 


		


<br>


<?php if(($hasaddr) == "1"): ?><a href="/Mem/addr" style="padding:3px 15px;display:flex;align-items:center;justify-contentaa:space-between;background:white;">
			<i class="layui-icon layui-icon-location"  style="width:30px;flex-shrink:0;font-size:1.5em;" ></i>
			<div style="flex:1 1 60%;margin:8px 5px;">
				<div style="font-size:1.15em;margin:1px 0px 8px 0px;color:bold;">收货人地址：</div>
				<div style="color:#666;"><span style="font-size:1.05em;margin-right:10px;color:#333;"><?php echo ($maddr["realname"]); ?> </span> <?php echo ($maddr["tel"]); ?></div>
				<div style="color:#777;padding-top:4px;"><?php echo ($maddr["addr"]); ?></div>				
			</div>
			<i class="layui-icon layui-icon-right"  style="width:20px;flex-shrink:0;" ></i>

		</a>
<?php else: ?>

<br><br>
<a href="/Mem/addr" style="padding:3px 15px;display:flex;align-items:center;justify-contentaa:space-between;background:white;color:red;">
			<i class="layui-icon layui-icon-location"  style="width:30px;flex-shrink:0;font-size:1.5em;" ></i>
			<div style="flex:1 1 60%;margin:8px 5px;">
				<div style="font-size:1.15em;margin:1px 0px 1px 0px;color:bold;color:red;">请完善收货地址</div>
				<!-- <div style="color:#666;"><span style="font-size:1.05em;margin-right:10px;color:#333;">李工 </span> 13452233444</div> -->
				<!-- <div style="color:#777;padding-top:4px;">河南开封郑州北京5号</div>				 -->
			</div>
			<i class="layui-icon layui-icon-right"  style="width:20px;flex-shrink:0;" ></i>

		</a><?php endif; ?>




		<style type="text/css">
	#kform{font-size: 15px;}
	#kform .kdiv{display:flex;height:46px;background:white;border-bottom:1px solid #f3f3f3;align-items:center;padding:0px 10px;justify-content:space-between;}
	#kform .ktitle{width:70px;padding:0 15px;text-align:right;}
	#kform .kinput{flex:1 1 70%;background:none;border:none;}
</style>
<br>
<div id="kform">
	
	<!-- <div class="kdiv">
			<div class="ktitle">数量</div>

			<div style="width:150px;display:flex;align-items:center;border:1px solid #aaa;line-height:38px;text-align:center;">
				<i class="layui-icon layui-icon-subtraction"   style="width:40px;flex-shrink:0;" ></i>

				<input style="flex:1 1 40px;width:40px;border-left:1px solid #aaa;border-right:1px solid #aaa;border-top:none;border-bottom:none;line-height:38px;text-align:center;"  value="5" readonly="" />	

				<i class="layui-icon layui-icon-addition"  style="width:40px;flex-shrink:0;" ></i>
				
			</div>
		</div> -->


	 
	<!-- <div class="kdiv">
		<span class="ktitle">金额</span>
		<input placeholder="请联系手机号" value="" class="kinput"  type="text">
	</div> -->
	 <div class="kdiv">
		<span class="ktitle">备注</span>
		<input placeholder="如有附加备注请填写" value="" class="kinput"  type="text" id="rmk">
	</div>
</div>









<br><br><br>

<br><br><br>


<br><br><br>
	<style type="text/css">
		.mitem {
			width: 160px;
			text-align: center;
			display: flex;
			align-items:center;
			justify-content:center;


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
		#kmenuflexaa i {
			font-size: 22px;
			margin-bottom:2px;
			margin-top: 2px;
			color: #666;
			display: inline-block;
		}
	</style>

<?php if(($tamt) > "0"): if(($hasaddr) == "1"): ?><div id="kmenuflex" style="display:flex;position:fixed;bottom:0;left:0;right:0;height:54px;background:white;border-top:0px solid #f4f4f4;">
		<a href="/Mem/index" class="mitem">
			<!-- <img src="/Public/nui/1<?php if(($aflag) == "index"): ?>1<?php endif; ?>.png" class="mimg">
			<div style="<?php if(($aflag) == "index"): ?>color:#16B58F;<?php endif; ?>">首页</div> -->
			<!-- <i class="layui-icon layui-icon-rmb"  style="<?php if(($aflag) == "index"): ?>color:#16B58F;<?php endif; ?>"></i>   -->
			<div style="color:red;font-size:1.5em;">￥ <?php echo ($tamt); ?> </div>
		</a>
		
		 
		<div id="topay" hrefaa="/Mem/buyone/id/<?php echo ($one["id"]); ?>" onclickaa="showpg()" class="mitem" style="flex:1 1 40%;font-size:16px;display:flex;align-items:center;justify-content:space-around;background:red;color:white;">
			结算			 
		</div>
	</div><?php endif; endif; ?>



<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.addnum').click(function(){
		var id = $(this).data('id');
		$.post('/Mem/addnum',{
					id:id					
				},function(data){
					if(data.status == 1){
						// layer.open({
						//     content: '操作成功'
						//     ,skin:'msg'
						//     ,time:3
						//     // ,btn: '确定'
						//   });
						// layer.msg('操作成功', {time: 3000,icon: 1});
						location.reload();
						// window.location.href='/Agent/center';
						return;
					}
					location.reload();
					// layer.msg('操作失败', {time: 3000,icon: 2});
				},'json'); 

	});
	$('.subnum').click(function(){
		var id = $(this).data('id');
		$.post('/Mem/subnum',{
					id:id					
				},function(data){
					if(data.status == 1){
						// layer.open({
						//     content: '操作成功'
						//     ,skin:'msg'
						//     ,time:3
						//     // ,btn: '确定'
						//   });
						// layer.msg('操作成功', {time: 3000,icon: 1});
						location.reload();
						// window.location.href='/Agent/center';
						return;
					}
					location.reload();
					// layer.msg('操作失败', {time: 3000,icon: 2});
				},'json'); 

	});
	$('.delone').click(function(){
		var id = $(this).data('id');
		$.post('/Mem/delone',{
					id:id					
				},function(data){
					if(data.status == 1){
						// layer.open({
						//     content: '操作成功'
						//     ,skin:'msg'
						//     ,time:3
						//     // ,btn: '确定'
						//   });
						// layer.msg('操作成功', {time: 3000,icon: 1});
						location.reload();
						// window.location.href='/Agent/center';
						return;
					}
					location.reload();
					// layer.msg('操作失败', {time: 3000,icon: 2});
				},'json'); 
		
	});

	// $('#hidepg').click(function(){
	// 	$('#tobuy').hide();
	// });

	// $('#addnum').click(function(){
	// 	var num = $('#knum').val();
	// 	num = parseInt(num);
	// 	$('#knum').val(1 + num);
	// });

	// $('#subnum').click(function(){
	// 	// alert(44)
	// 	var num = $('#knum').val();
	// 	num = parseInt(num);
	// 	num = num - 1 > 0 ? num - 1 : 1;
	// 	$('#knum').val( num  );
	// });

	$('#topay').click(function(){
		var rmk = $('#rmk').val();

		$.post('/Mem/topay',{
					payid:<?php echo ($payid); ?>,rmk:rmk
				},function(data){
					if(data.status == 1){
						// layer.open({
						//     content: '操作成功'
						//     ,skin:'msg'
						//     ,time:3
						//     // ,btn: '确定'
						//   });
						// layer.msg('操作成功', {time: 3000,icon: 1});
						// location.reload();
						window.location.href='/Wxapp/wxpay';
						return;
					}

					layer.open({
						    content: data.info
						    ,skin:'msg'
						    ,time:5
						    // ,btn: '确定'
						  });
					// location.reload();
					// layer.msg('操作失败', {time: 3000,icon: 2});
				},'json'); 
	});
});
 	</script>







</body>

</html>