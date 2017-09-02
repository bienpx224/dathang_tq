<?php
//搜索
$so=(int)$_GET['so'];
if($so)
{
	$key=par($_GET['key']);
	if($key){$where.=" and (bgydh='{$key}' or kuaidi like '%{$key}%' or userid='".CheckNumber($key,-0.1)."' or useric='{$key}' or username like '%{$key}%' )";}

	$search.="&so={$so}&key={$key}";
}

include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页
$query="select * from baoguo where {$where} {$Xwh} {$order}";

//分页处理
//$line=1;$page_line=15;//不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
?>


<div class="tabbable tabbable-custom boxless" style="padding-bottom:180px;"><!--为显示会员菜单-->
	<ul class="nav nav-tabs">
		<li class="active"><a><?=warehouse($warehouse)?> <?=$listName?> (<?=$num?>个)</a></li>
	</ul>
	<div class="tab-content" style="padding:10px;"> 
	<div class="navbar navbar-default" role="navigation">
				
				<div class="collapse navbar-collapse navbar-ex1-collapse">
					<form class="navbar-form navbar-left"  method="get" action="?">
						<input name="so" type="hidden" value="1">
						<div class="form-group">
							<input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="快递单号/快递公司/会员ID/会员名/会员入库码" value="<?=$key?>" required>
						</div>
						<button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
					</form>
				</div>
			</div>
			
	<!---->
	<table class="table table-striped table-bordered table-hover" style="border:0px solid #ddd;">
	<thead>
	<tr>
	<th align="center">
	<a href="?<?=$search?>&orderby=bgydh&orderlx=" class="<?=orac('bgydh')?>" title="寄库快递单号">单号</a>/<a href="?<?=$search?>&orderby=kuaidi&orderlx=" class="<?=orac('kuaidi')?>"  title="寄库快递公司">公司</a>
	 (<a href="?<?=$search?>&orderby=special_bgydh&orderlx=" class="<?=orac('special_bgydh')?>" title="按快递单号最后位数排序">特排</a>)
	</th>
	<th align="center"><a href="?<?=$search?>&orderby=warehouse&orderlx=" class="<?=orac('warehouse')?>">仓库</a>/<a href="?<?=$search?>&orderby=whPlace&orderlx=" class="<?=orac('whPlace')?>">仓位</a></th>
	<th align="center"><a href="?<?=$search?>&orderby=weight&orderlx=" class="<?=orac('weight')?>">重量 </a>/<a href="?<?=$search?>&orderby=addSource&orderlx=" class="<?=orac('addSource')?>">来源</a></th>
	<th align="center"> <a href="?<?=$search?>&orderby=rukutime&orderlx=" class="<?=orac('rukutime')?>">入库</a>/<a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">预报</a></th>
	
	<th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('addtime')?>"><?=$LG['status']//状态?></a></th>
	<th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a>
	(<a href="?<?=$search?>&orderby=special_userid&orderlx=" class="<?=orac('special_userid')?>" title="按会员ID最后位数排序">特排</a>)
	</th>
	<th align="center">操作</th>
	</tr>
	</thead>
	<tbody>
	<?php
	while($rs=$sql->fetch_array())
	{
	//是否可发货
	$fahuo=baoguo_fahuo(1);
	
	//显示勾选框
	$checkbox=1;
	?>
	<tr class="odd gradeX <?=spr($rs['status'])==9||spr($rs['status'])==10?'gray2':''?>">
	
	<td align="center" valign="middle"><a href="show.php?bgid=<?=$rs['bgid']?>" target="_blank"><?=cadd($rs['bgydh'])?></a>
		<br>
		<font class="gray2">
		<?=cadd($rs['kuaidi'])?>
		</font></td>
	<td align="center" valign="middle"><?=warehouse($rs['warehouse'])?>
		<br>
		<font class="gray2">
		<?=cadd($rs['whPlace'])?>
		</font></td>
	<td align="center" valign="middle"><?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?>
		<br>
		<font class="gray2">
		<?=baoguo_addSource($rs['addSource'])?>
		</font></td>
	<td align="center" valign="middle">
	<font  title="入库时间">
		<?=DateYmd($rs['rukutime']);?>
		</font>
		<br>
	<font class="gray2" title="添加/预报时间"><?=DateYmd($rs['addtime']);?></font>
		
		
		
		</td>
	<td align="center" valign="middle">
	<?php  
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/status_show.php');
	?>	
	
	</td>
	<td align="center" valign="middle">
    <!--显示会员账号-->                            
    <?php 
	if($rs['userid']){
		echo showUsername($rs['username'],$rs['userid'],$showUseric='1');
	}else{
		if($rs['unclaimedContent']){
		?>
    		<font class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['unclaimedContent'])?>">待领包裹 <i class="icon-info-sign"></i> </font>
    <?php 
		}else{
			echo '待领包裹';
		}
	}
	?>
                               
	</td>
	<td align="center" valign="middle">
	<?php  
	//操作菜单
	$callFrom_op=1;
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/op_menu.php');
	?>	
	
		</td>
	</tr>
	<?php
	}
	?>
	</tbody>
	</table>
	<div class="row">
		<?=$listpage?>
	</div>
	<!---->
	</div>
</div>
