<?php
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
$noper=1;$Xwh=1;$WHPerShow=1;
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

/*
	此页特殊性:
	所有参数不能带有可注入的非法参数,如:select
	bootstrap部分效果不支持 (如:tooltips)
	
	//单选按钮,样式会丢失,只通知用默认样式,增加分隔位置 <div class="radio-list" style="padding-left:20px;">

	
*/


//管理员可查看全部
if($_SESSION['manage']['userid']){$Mmy='';}

$lx=par($_POST['lx']);
$id=intval($_POST['id']);
$classid=intval($_POST['classid']) ;
?>












<?php

//生成绑定微信临时验证码----------------------------------------------------------
if ($lx=='wx_binding_tmp')
{ 	
	if(!$Muserid){exit('登录超时');}
	if(!$ON_WX){return $LG['function.22_1'];}
	$typ=par($_POST['typ']);
	
	$rs=FeData('member','userid,wx_openid,wx_binding_time',"userid='{$Muserid}' and checked=1");
	if(!$rs['userid']){exit($LG['data.form_44']);}
	
	if($typ=='add')
	{
		if($rs['wx_openid']){exit($LG['data.form_45']);}
		$wx_binding_tmp='AD'.strtoupper(make_NoAndPa(4));//长度在 api/mpWeixin/index.php 有设定,需要同步修改
		echo '<button type="button" class="btn btn-info" onClick="wx_binding_tmp(\'add\')" >'.$LG['data.form_42'].'</button><br>
		<img src="/images/wx_mp.jpg" width="150"><br>
		'.LGtag($LG['data.form_46'],'<tag1>=='.$wx_binding_tmp);
		
	}elseif($typ=='del'){
		if(!$rs['wx_openid'])
		{
			exit($LG['data.form_47'].'<br><button type="button" class="btn btn-info" onClick="wx_binding_tmp(\'add\')" >'.$LG['data.form_42'].'</button>');
		}
		$wx_binding_tmp='DE'.strtoupper(make_NoAndPa(4));//长度在 api/mpWeixin/index.php 有设定,需要同步修改
		echo '<button type="button" class="btn btn-default" onClick="wx_binding_tmp(\'del\')" >'.$LG['data.form_41'].'</button><br>'.
		LGtag($LG['data.form_48'],'<tag1>=='.$wx_binding_tmp).'<br>
		<img src="/images/wx_mp.jpg" width="150">';
	}

	$xingao->query("update member set wx_binding_tmp='{$wx_binding_tmp}',wx_binding_time='".time()."' where userid='{$rs['userid']}'");

	exit;//中止执行,节省资源
}


//充值时判断填写会员是否正确----------------------------------------------------------
if ($lx=='cz')
{ 	
	$username_member=par($_POST['username']);
	if($username_member)
	{
		$rs=FeData('member','userid,username,money,integral,currency',"(userid='".$username_member."' or username='".$username_member."') {$Mmy}");
		if($rs['userid'])
		{
			echo $LG['ajax.9'].$rs['username']."&nbsp;&nbsp;(".$rs['userid'].")";
			echo '<br>'.$LG['ajax.10'].spr($rs['money']).$rs['currency']."&nbsp;&nbsp;(".$rs['integral']."分)";
		}else{
			echo '<span style="color:red">'.$LG['ajax.1'].'</span>';//会员ID或会员名填写错误
		}
	}
		
	exit;//中止执行,节省资源

}

//获取会员名或ID----------------------------------------------------------
if ($lx=='getUsernameId')
{ 	
	//有风险,只限后台使用
	if(!$_SESSION['manage']['userid']){exit('无权操作');}//只有管理员可查看
	
	$typ=par($_POST['typ']);
	$val=par($_POST['val']);
	
	if($typ=='userid'){
		$where="userid='{$val}'";
	}else if($typ=='username'){
		$where="username='{$val}'";
	}else if($typ=='useric'){
		$where="useric='{$val}'";
	}
	
	if($where)
	{
		$rs=FeData('member','useric,userid,username',"{$where}");
		echo $rs['userid']?cadd($rs['useric']).','.cadd($rs['userid']).','.cadd($rs['username']):'所填错误,所填错误,所填错误';
	}

	
	exit;//中止执行,节省资源
}

//自动获取该会员的仓位----------------------------------------------------------
/*if ($lx=='cw')
{ 	
	$username=par($_POST['username']);
	$userid=par($_POST['userid']);
	$useric=par($_POST['useric']);
	if($username||$userid||$useric)
	{
		$whPlace=cadd(FeData('baoguo','whPlace',"whPlace<>'' and (username='{$username}' or userid='{$userid}' or useric='{$useric}') and status>1  {$Mmy} order by rukutime desc,bgid desc"));//查询
		
		//没有仓位时,自动生成
		if(!$whPlace)
		{
			if(!$userid){$userid=FeData('member','userid',"username='{$username}' or userid='{$userid}' or useric='{$useric}'");}
			$r=CustomerService($userid);
			$whPlace=$r[0].$userid;
		}
		
		echo $whPlace;
	}
	
	exit;//中止执行,节省资源
}

*///显示仓位----------------------------------------------------------
/*
	$Typ=1 该包裹单号仓位
*/
if ($lx=='show_whPlace')
{ 	
	$Typ=spr($_POST['Typ']);
	$bgydh=par($_POST['bgydh']);
	$getTyp=spr($_POST['getTyp']);
	
	if($Typ&&$bgydh)
	{
		$rs=FeData('baoguo','whPlace,bgid,userid',"bgydh='{$bgydh}'");
		$where=" and userid='{$rs['userid']}'";$userid=$rs['userid'];
	}elseif(!$Typ){
		$username=par($_POST['username']);
		$userid=par($_POST['userid']);
		$useric=par($_POST['useric']);
		if($username||$userid||$useric)
		{
			$where=" and (username='{$username}' or userid='{$userid}' or useric='{$useric}') ";
		}
	}
	
	
	if($getTyp==0){
		$whPlace=$rs['whPlace'];
	}elseif($getTyp==1&&$where){	
		$whPlace=FeData('baoguo','whPlace',"whPlace<>'' and status>1 {$where} order by rukutime desc,bgid desc");
	}elseif($getTyp==2){
		$whPlace=$_SESSION['shelves_whPlace'];
	}

		
		//没有仓位时,自动生成
		if(!$whPlace)
		{
			if(!$userid){$userid=FeData('member','userid',"username='{$username}' or userid='{$userid}' or useric='{$useric}'");}
			$r=CustomerService($userid);
			$whPlace=$r[0].$userid;
		}

	
	if($Typ&&!$rs['bgid']){exit("单号错误,");}
	exit(",{$whPlace}");
}


//分级联动-添加分类表单:显示所属分类----------------------------------------------------------
if ($lx=='bclass_show')
{ 	
	$classtype=par($_POST['classtype']);
	$bclassid=par($_POST['bclassid']);
	?>
	<select class="form-control input-medium select2me" data-placeholder="Select..." name="bclassid" >
	<option value="0"><?=$LG['ajax.11']?></option>
	<?=LevelClassify(0,0,$bclassid,'1,2,4,5,6,7,8,10',0)?><!--显示的分类类型:3有单独的表单,因此不显示3类型-->
	</select>
	<?php
}

//分级联动----------------------------------------------------------
if ($lx=='classify')
{ 	
	$classtag=par($_POST['classtag']);
	$classtype=spr($_POST['classtype']);
	$bclassid=par($_POST['bclassid']);//不能用spr
	$classid=par($_POST['classid']);
	$level=spr($_POST['level']);
	if($level>1&&!$bclassid&&!$classid){$err=1;}//不是第一级时必须有大分类ID
	if(!CheckEmpty($bclassid)){$err=1;}//大分类选择空时,不能显示子分类,否则会显示同0级分类
	
	if($classtype&&!$err)
	{
		$query="select classid,name{$LT} from classify where classtype='{$classtype}' and bclassid='".$bclassid."' order by myorder desc,classid asc";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$selected=$classid==$rs['classid']?'selected':''; 
			$data.= '<option value="'.$rs['classid'].'" '.$selected.'>'.cadd($rs['name'.$LT]).'</option>';
		}
		
		if(mysqli_affected_rows($xingao)>0)
		{
			//点击分类时不能更新,否则会一直显示
			$classify_show='classify_show'.$classtag.$classtype.'(this.value,'.$classid.','.$level.');';
			echo '<select  class="form-control input-msmall select2me" data-placeholder="Select..." name="classid'.$classtag.$classtype.'[]" onChange="'.$classify_show.'">';
			echo '<option value=""></option>';
			echo $data;
			echo '</select>';
		}
		$data='';//节省内存
	}
}








//------------------------------------------------------运单相关----------------------------------------------
//商城显示参考运费
if ($lx=='ReferFreight')
{ 	
	$groupid =$Mgroupid;//当前会员组ID
	if($groupid)
	{
		$warehouse=spr($_POST['warehouse']);
		$country=spr($_POST['country']);
		$weight=spr($_POST['weight']);
		
		for($i=1; $i<=20; $i++)
		{
			$channel=$i;
			require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/get_price.php');//获取价格
			if($channel_checked)
			{
				echo $channel_name.':';
				$phpcall=1;include($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/calc.php');
				echo $XAmc.'<br>';
				$ok=1;
			}
			
			if(!$ok&&!$first){echo $LG['front.109_2'];$first=1;}//'该仓库不支持寄往该国家';	
		}
	}else{
		echo $LG['front.102'];
	}
	exit;//中止执行,节省资源
}


//显示国家下拉
if ($lx=='country')
{ 	
	$groupid=spr($_POST['groupid']);
	$warehouse=spr($_POST['warehouse']);
	$country=spr($_POST['country']);
	
	$username=par($_POST['username']);
	$userid=spr($_POST['userid']);

	if(!$groupid&&!$username&&!$userid){exit('<option value="" selected>'.$LG['ajax.12'].'</option>');}
	if(!$warehouse){exit('<option value="" selected>'.$LG['ajax.13'].'</option>');}
	
	if(!$groupid&&$username){$groupid=FeData('member','groupid',"username='{$username}'");}
	elseif(!$groupid&&$userid){$groupid=FeData('member','groupid',"userid='{$userid}'");}
	if(!$groupid){$ppt=$LG['ajax.14'];}
	echo '<option value="">'.$ppt.'</option>'; 
	yundan_Country($country,1,$groupid,$warehouse);
	
	exit;//中止执行,节省资源
}




//显示渠道下拉
if ($lx=='channel')
{ 	
	$warehouse=spr($_POST['warehouse']);
	$username=par($_POST['username']);
	$userid=spr($_POST['userid']);
	$country=spr($_POST['country']);
	$channel=spr($_POST['channel']);
	$callFrom=par($_POST['callFrom']);

	if($callFrom=='member'&&!$country){exit('<option value="" selected>'.$LG['ajax.15'].'</option>');}
	if(!$warehouse){exit('<option value="" selected>'.$LG['ajax.13'].'</option>');}
	
	if(!$userid&&$username){$userid=FeData('member','userid',"username='{$username}'");}
	echo '<option value=""></option>'; 
	yundan_Channel($warehouse,$country,$userid,$channel,$callFrom);
	//因为默认不能自动计费费用,要点击一下,所有必须有一个空的
	
	exit;//中止执行,节省资源
}




//显示该渠道-保价计算
if ($lx=='insurance')
{ 	
	$whid=spr($_POST['whid']);
	$channel=spr($_POST['channel']);
	$insureamount=spr($_POST['insureamount']);
	echo insurance($whid,$channel,$insureamount,1);
	
	exit;//中止执行,节省资源
}



//验证渠道限重
if ($lx=='submit_chk')
{ 	
	$warehouse=spr($_POST['warehouse']);
	$channel=spr($_POST['channel']);
	$weight=spr($_POST['weight']);
	$typ=par($_POST['typ']);
	$Receive=0;//空时必须返回0
	
	//验证:预估限重
	if($typ=='forecast_weight_limit')
	{
		$limit=channelPar($warehouse,$channel,'weight_limit');
		$ppt=channelPar($warehouse,$channel,'weight_limit_ppt');
		if($limit>0&&$weight>$limit){$Receive=LGtag($LG['ajax.2'],'<tag1>=='.$limit.$XAwt).$ppt;}
	}
	
	//验证:商品资料限重
	elseif($typ=='customs_weight_limit')
	{
		$weightUnit=$XAwt;
		$limit=channelPar($warehouse,$channel,'customs_weight_limit');
		$customs=channelPar($warehouse,$channel,'customs');
		if($customs=='gd_mosuda'){$weightUnit='KG';		$weight*=$XAwtkg;}//该商品资料所用重量单位

		if($limit>0&&$weight>$limit){$Receive=LGtag($LG['yundan.26'],'<tag1>=='.$limit.$weightUnit);}//物品重量已经超过该渠道限重!
	}
	echo $Receive;//空时必须返回0
	
	exit;//中止执行,节省资源
}



//验证收件人资料
if ($lx=='receive_check')
{ 	
	$Receive=CheckReceive('address');
	if(!$Receive){echo 0;}else{echo $Receive;}//空时必须返回0
	
	exit;//中止执行,节省资源
}





//代替证件
if ($lx=='cardInstead')
{ 	
	$typ=par($_POST['typ']);
	$cardYdid=spr($_POST['cardYdid']);
	$country=spr($_POST['country']);
	$s_name=par($_POST['s_name']);
	
	if(!$_SESSION['manage']['userid']){exit($LG['ajax.3']);}
	if(!$ON_cardInstead){exit($LG['ajax.4']);}
	if(!$cardYdid&&!$typ){exit($LG['ajax.5']);}//旧版	

	
	function cardInstead_show($cardYdid)
	{
		require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
		$rs=FeData('yundan','ydid,userid,s_name,s_shenfenhaoma,s_shenfenimg_z,s_shenfenimg_b',"ydid='{$cardYdid}'");
		$img=$rs['s_shenfenimg_z'].','.$rs['s_shenfenimg_b'];
		?>
        <input type="hidden" name="cardYdid" value="<?=$cardYdid?>">
        
		<a href="/public/ShowImg.php?img=<?=urlencode($img)?>" target="_blank" class=" tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['ajax.6'];//查看图片?>">
		<img src="<?=$rs['s_shenfenimg_z']?>"  height="50"/> 
		<img src="<?=$rs['s_shenfenimg_b']?>"  height="50"/> 
		</a>
        
		<br>
		<a href="show.php?ydid=<?=$rs['ydid']?>#s_name" target="_blank">
		<?=cadd($rs['s_name'])?> <?=cadd($rs['s_shenfenhaoma'])?>
		</a>
		<?php
	}
	

	if($typ=='get')
	{
		$ydid=cardInstead($country,$s_name);
		if(!$ydid){exit($LG['ajax.7']);}
		cardInstead_show($ydid);

	}elseif($typ=='failure'&&$cardYdid){
		cardUseTime($cardYdid);
		$ydid=cardInstead($country,$s_name);
		if(!$ydid){exit($LG['ajax.8']);}
		cardInstead_show($ydid);
		
	}elseif($cardYdid){
		cardInstead_show($cardYdid);
	}
	
	exit;//中止执行,节省资源
}





//运单-显示该渠道参数
if ($lx=='channelPar')
{ 	
	$warehouse=spr($_POST['warehouse']);
	$channel=spr($_POST['channel']);
	//是否要上传证件,清关公司,渠道说明内容
	echo channelPar($warehouse,$channel,'shenfenzheng').':::'.channelPar($warehouse,$channel,'customs').':::'.channelPar($warehouse,$channel,'content');
	
	exit;//中止执行,节省资源
}




//运单-显示该渠道支持的附加服务
if ($lx=='yundan_service')
{ 	
	$warehouse=spr($_POST['warehouse']);
	$channel=spr($_POST['channel']);
	$addSource=spr($_POST['addSource']);
	$ydid=spr($_POST['ydid']);
	$formlx=par($_POST['formlx']);
	
	if($ydid){$rs=FeData('yundan','op_bgfee1,op_bgfee2,op_wpfee1,op_wpfee2,op_ydfee1,op_ydfee2,op_free,op_freearr,op_overweight',"ydid='{$ydid}'");}
	
	?>
    
    
	<?php 
	$field='op_bgfee1';	$name=yundan_service($field,'name');
	if($addSource==1&&channelPar($warehouse,$channel,'ON_op_bgfee1')){?>
    <div class="form-group">
        <label class="control-label col-md-2"><?=$name?></label>
        <div class="col-md-10">
            <div class="radio-list" style="padding-left:20px;">
 			<?php yundan_service($field,$rs[$field],1)?>
            </div>
            <span class="help-block"><?=yundan_service($field,'ppt')?></span>
        </div>
    </div>
    <?php }?>

    <?php 
	$field='op_bgfee2';	$name=yundan_service($field,'name');
	if($name&&$addSource==1&&channelPar($warehouse,$channel,'ON_op_bgfee2')){?>
    <div class="form-group">
        <label class="control-label col-md-2"><?=$name?></label>
        <div class="col-md-10">
            <div class="radio-list" style="padding-left:20px;">
            <?=yundan_service($field,$rs[$field],1)?>
            </div>
            <span class="help-block"><?=yundan_service($field,'ppt')?></span>
        </div>
    </div>
    <?php }?>

    
	<?php 
	$field='op_wpfee1';	$name=yundan_service($field,'name');
	if($name&&have($addSource,'1,7')&&channelPar($warehouse,$channel,'ON_op_wpfee1')){?>
    <div class="form-group">
        <label class="control-label col-md-2"><?=$name?></label>
        <div class="col-md-10">
            <div class="radio-list" style="padding-left:20px;">
 			<?php yundan_service($field,$rs[$field],1)?>
            </div>
            <span class="help-block"><?=yundan_service($field,'ppt')?></span>
        </div>
    </div>
    <?php }?>

    <?php 
	$field='op_wpfee2';	$name=yundan_service($field,'name');
	if($name&&have($addSource,'1,7')&&channelPar($warehouse,$channel,'ON_op_wpfee2')){?>
    <div class="form-group">
        <label class="control-label col-md-2"><?=$name?></label>
        <div class="col-md-10">
            <div class="radio-list" style="padding-left:20px;">
            <?php yundan_service($field,$rs[$field],1)?>
            </div>
            <span class="help-block"><?=yundan_service($field,'ppt')?></span>
        </div>
    </div>
    <?php }?>
    
    <?php 
	$field='op_ydfee1';	$name=yundan_service($field,'name');
	if($name&&have($addSource,'1,7')&&channelPar($warehouse,$channel,'ON_op_ydfee1')){?>
    <div class="form-group">
        <label class="control-label col-md-2"><?=$name?></label>
        <div class="col-md-10">
            <div class="radio-list" style="padding-left:20px;">
            <?php yundan_service($field,$rs[$field],1)?>
            </div>
            <span class="help-block"><?=yundan_service($field,'ppt')?></span>
       </div>
    </div>
    <?php }?>
  
	<?php 
	$field='op_ydfee2';	$name=yundan_service($field,'name');
	if($name&&have($addSource,'1,7')&&channelPar($warehouse,$channel,'ON_op_ydfee2')){?>
    <div class="form-group">
        <label class="control-label col-md-2"><?=$name?></label>
        <div class="col-md-10">
            <div class="radio-list" style="padding-left:20px;">
            <?php yundan_service($field,$rs[$field],1)?>
            </div>
            <span class="help-block"><?=yundan_service($field,'ppt')?></span>
        </div>
    </div>
    <?php }?>
  
    
    <?php 
	$field='op_free';	$name=op_free('name');
	if($name&&have($addSource,'1,7')&&channelPar($warehouse,$channel,'ON_op_free')){?>
    <div class="form-group">
        <label class="control-label col-md-2"><?=$name?></label>
        <div class="col-md-10">
            <div class="radio-list" style="padding-left:20px;">
            <?php op_free($rs[$field],1)?>
            </div>
            <span class="help-block"><?=op_free($field,'ppt')?></span>
       </div>
    </div>
    <?php }?>


    <?php 
	$field='op_freearr'; $name=op_freearr('name');
	if($name&&have($addSource,'1,7')&&channelPar($warehouse,$channel,'ON_op_freearr')){?>
    <div class="form-group">
        <label class="control-label col-md-2"><?=$name?></label>
        <div class="col-md-10">
            <div class="radio-list">
            <?php op_freearr($rs[$field],1)?>
            </div>
            <span class="help-block"><?=op_freearr($field,'ppt')?></span>
        </div>
    </div>
    <?php }?>
    
    <div class="form-group">
        <label class="control-label col-md-2"><?=op_overweight('name')?></label>
        <div class="col-md-10">
            <div class="radio-list" style="padding-left:20px;">
            <?php if($formlx=='add'){$rs['op_overweight']=1;}op_overweight($rs['op_overweight'],1)?>
            </div>
        </div>
    </div>
<?php 
	
	exit;//中止执行,节省资源
}
















//-----------------------同一个表输出筛选菜单-开始 (支持所有表)-----------------------------------
/*
	调用,搜索:调用同一个表输出筛选菜单
*/
if ($lx=='options_menu')
{ 	
	$warehouse=spr($_POST['warehouse']);
	$channel=spr($_POST['channel']);
	$table=par($_POST['table']);
	$field=par($_POST['field']);
	$id=spr($_POST['id']);
	$chkbig=spr($_POST['chkbig']);
	
	$customs_types_limit=channelPar($warehouse,$channel,'customs_types_limit');
	
	
	//安全验证
	if($table=='gd_mosuda')
	{
		$rs=FeData($table,'gdid,producer,types,brand,unit',"gdid='{$id}'");
		
		if($field=='producer'){$where="";	$field_next='types';}
		elseif($field=='types'){if($rs['gdid']){$where=" and producer='{$rs['producer']}'";}	$field_next='brand';}
		elseif($field=='brand'){if($rs['gdid']){$where=" and producer='{$rs['producer']}' and types='{$rs['types']}'";}	$field_next='unit';}
		elseif($field=='unit'){if($rs['gdid']){$where=" and producer='{$rs['producer']}' and types='{$rs['types']}' and brand='{$rs['brand']}'";}	$field_next='name';}
		elseif($field=='name'){if($rs['gdid']){$where=" and producer='{$rs['producer']}' and types='{$rs['types']}' and brand='{$rs['brand']}'  and unit='{$rs['unit']}'";}}
		
		if($customs_types_limit)
		{
			$customs_types_limit=str_ireplace(',',"','",$customs_types_limit);//中文
			$where.=" and (types in ('{$customs_types_limit}') or types='' )";
		}
		
	}else{
		exit("{$table}参数错误!");
	}
	
	//验证是否有大分类
	if($chkbig)
	{
		$num=options_menu($table,$where,$field,$chkbig);
		if($num>1){exit('1');}else{exit('0');}
	}
?>	
  
      <div class="col-md-0">
      <select  class="form-control select2me input-msmall" name="menu_<?=$field?>" onChange="<?php if($field_next){?>options_menu('options_<?=$field_next?>','<?=$table?>','<?=$field_next?>',this.value); <?php }else{?>gd_mosuda_list();<?php }?>">
          <option></option>
          <?=options_menu($table,$where,$field)?>
        </select>
      </div>
   
<?php
}


/*
	$table 查询表
	$where 多加条件:以and开头
	$field 查询字段
	
	$count=0 显示下拉
	$count=1 统计数量
*/
function options_menu($table,$where,$field,$count=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if($table=='gd_mosuda')
	{
		$where="checked='1' and record in (0,2) {$where}";
		$id_field='gdid';
		$order="order by myorder desc,onclick desc,brand asc,name asc,unit asc";
	}
	
	if($count)
	{
		return $num=NumData($table,"{$where} and {$field}<>'' group by {$field}");
	}else{
		$query="select {$id_field},{$field} from {$table} where  {$where}  group by {$field} {$order}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			echo '<option value="'.$rs[$id_field].'" >'.cadd($rs[$field]).'</option>';
		}
	}
}
//--------------------------------------同一个表输出筛选菜单-结束--------------------------------------






//增加点击----------------------------------------------------------------------------
/*
	调用,搜索:ajax更新点击
*/
if ($lx=='addOnClick')
{ 	
	$table=par($_POST['table']);
	$id_field=par($_POST['id_field']);
	$id=spr($_POST['id']);
	$xingao->query("update {$table} set onclick=onclick+1 where {$id_field}='{$id}'");
	
	exit;//中止执行,节省资源
}


//记录已加或已删----------------------------------------------------------------------------
/*
	调用,搜索://记录已加或已删
*/
if ($lx=='UPtag')
{ 	
	$tag=par($_POST['tag']);
	$typ=par($_POST['typ']);
	$id=spr($_POST['id']);
	if($tag&&$typ&&$id)
	{
		if($typ=='add'){$_SESSION[$tag].=','.$id;}elseif($typ=='del'){$_SESSION[$tag]=ArrDel($_SESSION[$tag],$id,1);}
		$_SESSION[$tag]=DelStr($_SESSION[$tag],',',1);
	}
	
	exit;//中止执行,节省资源
}





//汇率兑换----------------------------------------------------------
if ($lx=='exchangeTopup')
{ 	
	$fromMoney=spr($_POST['fromMoney']);
	$fromCurrency=par($_POST['fromCurrency']);
	$toCurrency=par($_POST['toCurrency']);
	
	//返回必须是数字
	if($fromMoney>0&&$fromCurrency&&$toCurrency)
	{
		$exchange=exchange($fromCurrency,$toCurrency);
		echo $exchange.','.spr($exchange*$fromMoney,5);
	}else{
		echo (-1).','.(-1);
	}
	
	exit;//中止执行,节省资源
}



//获取代购参数----------------------------------------------------------
if ($lx=='daigouPar')
{ 	
	$groupid=spr($_POST['groupid']);
	$brand=spr($_POST['brand']);
	$tmp=par($_POST['tmp']);
	$priceCurrency=par($_POST['priceCurrency']);
	$groupid=spr($_POST['groupid']);
	$freightFee=spr($_POST['freightFee']);

	
	//服务费率
	if($_POST['source']==1)
	{
		//线上网站==========================
		
		//品牌折扣
		$_SESSION["{$tmp}brandDiscount"]=0;
		
		//服务费率
		$_SESSION["{$tmp}serviceRate"]=$member_per[$_POST['groupid']]['dg_serviceRateWeb'];
	}elseif($_POST['source']==2){
		//线下专柜==========================
		//品牌折扣
		if($groupid>0&&$brand>0)
		{
			$brandDiscount=daigou_brandDiscount($groupid,$brand);//没有折扣是返回10,不支持返回文字提示或0
			$_SESSION["{$tmp}brandDiscount"]=$brandDiscount;//用于表单计算总计费用
		}
			
		if(!is_numeric($brandDiscount)||!$brandDiscount){
			$_SESSION["{$tmp}brandDiscount"]=0;
			$brandDiscount=-1;//不支持或选择错误时返回-1
		}
		
		//服务费率
		$_SESSION["{$tmp}serviceRate"]=$member_per[$_POST['groupid']]['dg_serviceRateShop'];
	}

	//运费
	if(!$freightFee||($_SESSION["{$tmp}priceCurrency"]<>$priceCurrency&&$_SESSION["{$tmp}priceCurrency"])){
		//空时获取默认运费
		$f=GetArrVar($priceCurrency,$member_per[$groupid]['dg_freightFee']); 	
		$freightFee=spr($f[1]);//获取运费
	}
	$_SESSION["{$tmp}freightFee"]=$freightFee;//用于表单计算总计费用
	
	
	//币种:不用输出
	$_SESSION["{$tmp}priceCurrency"]=$priceCurrency;//用于物品表单
	
	//返回
	echo "{$brandDiscount},{$freightFee},".$_SESSION["{$tmp}serviceRate"];
	
	exit;//中止执行,节省资源
}





//输出商品资料列表----------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/gd_mosuda/call/ajax_list.php');
?>