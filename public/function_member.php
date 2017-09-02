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

if(!defined('InXingAo')){exit('No InXingAo');}

//会员语种
/*
	$config='' 返回语言包数组
	$config='' 返回config中变量的语种,如:$member_birthday_content 返回:$member_birthday_contentCN
	
	memberLT($rs['userid'],'member_birthday_content')
*/
function memberLT($userid,$config='')
{
	if($userid){$language=FeData('member','language',"userid='{$userid}'");}
	
	if(!$config)
	{
		//载入语种包
		if($language)
		{
			$loadFile='/Language/'.$language.'.php';
			if(HaveFile($loadFile)){require($_SERVER['DOCUMENT_ROOT'].$loadFile);	return $LG;}
		}
		global $LG;
		return $LG;
	}else{
		//获取变量
		if($language)
		{
			$joint=$config.$language;
			global $$joint;
			if($$joint){return $$joint;}
		}
		global $$config;
		return $$config;
	}
}




//各系统列表显示会员账号
/*
	$username 必须
	$userid 必须
	$showUseric=1 则显示useric,否则不显示
	$mr如果空则自动读取
*/
function showUsername($username,$userid,$showUseric='',$mr='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $member_per;
	if(!$mr){$mr=FeData('member','groupid,truename,enname,useric',"userid='{$userid}'");}
?>
    <font class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$member_per[$mr['groupid']]['groupname']?>"><?=cadd($username)?></font> 
    <font class="gray tooltips" data-container="body" data-placement="top" data-original-title="会员ID"><?=cadd($userid)?></font>
    
    <?php if($showUseric){?>
    <font class="gray tooltips" data-container="body" data-placement="top" data-original-title="会员入库码"><?=cadd($mr['useric'])?></font>
    <?php }?>
    
    <br>
    
    <font class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="姓名:<?=cadd($mr['truename'])?><?=$mr['enname']?'<br>拼音/英文:'.cadd($mr['enname']):''?>"><?=cadd($mr['truename'])?></font>
<?php 
}




//保存表单时,保存所属会员:获取会员账号资料并赋值到POST,验证账号是否正确
/*
	$typ=0; 只返回结果
	$typ=1; 停止并提示
	$useric 是否输出$useric
	
*/
function getMemberUser($typ=1,$useric=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $_POST;
	
	if(add($_POST['username'])){	
		$member=FeData('member','userid,useric,username',"username='".add($_POST['username'])."'");
	}elseif(add($_POST['userid'])){	
		$member=FeData('member','userid,useric,username',"userid='".spr($_POST['userid'])."'");
	}elseif(add($_POST['useric'])){	
		$member=FeData('member','userid,useric,username',"useric='".add($_POST['useric'])."'");
	}
	
	
	
	
	if($typ&&!$member['userid']){exit ("<script>alert('会员账号错误！');goBack();</script>");}
	elseif(!$member['userid']){return 1;}
	
	if($useric){$_POST['useric']=cadd($member['useric']);}
	$_POST['userid']=spr($member['userid']);
	$_POST['username']=cadd($member['username']);
}





//各系统显示会员账号操作菜单
/*
	$fromtable='',$fromid='',$title='' 充值,扣费,加分,减分时可传参数
*/
function memberMenu($username,$userid,$fromtable='',$fromid='',$title='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $manage_per;
	
	if(permissions('member_le,member_co,member_re,member_de,member_in','','manage',1)){
		?>
		<div class="btn-group"> <a class="btn btn-xs btn-default dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"> 会员 <i class="icon-angle-down"></i> </a>
		
			<ul class="dropdown-menu" style="min-width:70px;">
			
			<?php if(permissions('member_le','','manage',1)){?>
				 <li><a href="/xingao/member/send.php?userid=<?=$userid?>" style="padding-left:0px;" target="_blank"><i class="icon-comments-alt"></i> 信息</a></li>
			<?php }?>
			
			<?php if(permissions('member_co','','manage',1)){?>
				 <li><a data-toggle="modal" data-target="#ajaxModal<?=$userid?>" href="/public/ajaxModal.php?typ=memberContact&userid=<?=$userid?>"  style="padding-left:0px;" ><i class="icon-file-text"></i> 联系</a></li>
			<?php }?>
		
			<?php if(permissions('member_re','','manage',1)){?>
				<li><a href="/xingao/member/money_cz.php?username=<?=cadd($username)?>&fromtable=<?=$fromtable?>&fromid=<?=$fromid?>&title=<?=cadd($title)?>" style="padding-left:0px;" target="_blank"><i class="icon-share"></i> 充值</a></li>
			<?php }?>
			
			<?php if(permissions('member_de','','manage',1)){?>	
				<li><a href="/xingao/member/money_kf.php?username=<?=cadd($username)?>&fromtable=<?=$fromtable?>&fromid=<?=$fromid?>&title=<?=cadd($title)?>" style="padding-left:0px;" target="_blank"><i class="icon-share"></i> 扣费</a></li>
			<?php }?>
			
			<?php if(permissions('member_in','','manage',1)){?>	
				<li><a href="/xingao/member/integral_cz.php?username=<?=cadd($username)?>&fromtable=<?=$fromtable?>&fromid=<?=$fromid?>&title=<?=cadd($title)?>" style="padding-left:0px;" target="_blank"><i class="icon-share"></i> 加分</a></li>
		
				<li><a href="/xingao/member/integral_kf.php?username=<?=cadd($username)?>&fromtable=<?=$fromtable?>&fromid=<?=$fromid?>&title=<?=cadd($title)?>" style="padding-left:0px;" target="_blank"><i class="icon-share"></i> 减分</a></li>
			<?php }?>
		
			</ul>
		</div>
		
		<!--弹出载入-->
		<div class="modal fade" id="ajaxModal<?=$userid?>" tabindex="-1" role="basic" aria-hidden="true">
		<img src="/images/ajax-modal-loading.gif" class="loading">
		</div>
		<?php 
		}
}


//专属客服
/*
	$val=客服编号/会员ID
	$typ=0 返回该行数组
	$typ=1 返回下拉选择编号
	$typ=2 输出所有资料
	
	$sys=0 内部用
*/
function CustomerService($val='',$typ=0,$sys=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $CustomerService;
	$lineArr=ToArr(cadd($CustomerService),1);
	if(!$lineArr){return;}
	$val=cadd($val);
	
	if($val&&!$sys&&!CustomerService($val,0,1))
	{
		$val=FeData('member','CustomerService',"userid='{$val}'");
	}
	
	
	if($typ==1)
	{

		echo '<option value="" '.($zhi==''?'selected':'').'> </option>';
		foreach($lineArr as $key=>$line)
		{
			$fieldArr=ToArr($line,2);
			echo '<option value="'.$fieldArr[0].'" '.($val==$fieldArr[0]?'selected':'').'>'.$fieldArr[0].'('.$fieldArr[1].')</option>';
		}
		
	}else{
		
		foreach($lineArr as $key=>$line)
		{
			$fieldArr=ToArr($line,2);
			if($val==$fieldArr[0]){$r=$fieldArr;break;}
		}
	
		if($typ==0){
			return $r;
		}elseif($typ==2){
			//直接输出该行所有资料
			for($i=0; $i<=10; $i++)
			{
			   if($r[$i]&&$i!=6)
			   {
				   if($i){echo "<span class='xa_sep'> | </span>";}//data-content 鼠标提示内容不能有"号
				   echo $LG['CustomerService.'.$i].':'.$r[$i];
			   }
			}
		}
	}
}
?>