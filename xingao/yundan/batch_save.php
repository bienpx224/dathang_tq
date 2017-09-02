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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
$pervar='yundan_st';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

set_time_limit(0);//批量处理时,速度慢,设为永不超时
//显示表单获取,处理
$lx=par($_POST['lx']);

//查询数量------------------------------------------------------------------------------------------------
if($lx=='num')
{
	//获取及验证修改条件---------------------------------
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/where_save.php');//输出:$where
	$num=mysqli_num_rows($xingao->query("select ydid from yundan where 1=1 ".whereCS()." {$where} " ));
	exit ('<div align="center" style="color:#FF0000">按选择条件,共有'.$num.'个运单</div>');
}	


//修改-开始------------------------------------------------------------------------------------------------
if($lx=='tj')
{
	//获取及验证修改条件---------------------------------
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/where_save.php');//输出:$where


	//获取及验证修改内容---------------------------------
	$status=par($_POST['status']);
	$options1=par($_POST['options1']);
	$options2=par($_POST['options2']);
	$options3=par($_POST['options3']);
	$classid_del=add($_POST['classid_del']);
	$lotno=add($_POST['lotno']);
	$statusauto=par($_POST['statusauto']);
	$kffs=par($_POST['kffs']);
	$hscode=par($_POST['hscode']);
	$gnkd=par($_POST['gnkd']);
	$gnkdydh=par($_POST['gnkdydh']);
	$cj=par($_POST['cj']);
	$gd_mosuda=par($_POST['gd_mosuda']);
	$dhl=par($_POST['dhl']);
	$warehouse=par($_POST['warehouse']);
	$channel=par($_POST['channel']);
	$country=par($_POST['country']);
	$reply=html($_POST['reply']);
	$reply_lx=par($_POST['reply_lx']);
	
	$classtag='save';//标记:同个页面,同个$classtype时,要有不同标记
	$classtype=3;//分类类型
	$classid=GetEndArr($_POST['classid'.$classtag.$classtype]);
	
	
	
	
	
	
	
	//批量修改-开始---------------------------------------
	$set='addtime=addtime';
	
	if(strtolower($classid_del)=='del')
	{
		$set.=",classid='0'";
	}else{
		if (CheckEmpty($classid)){$set.=",classid='{$classid}'";}
	}
	
	if (CheckEmpty($lotno))
	{
		if(strtolower($lotno)=='del'){$set.=",lotno=''";}else{$set.=",lotno='{$lotno}'";}
	}
	if (CheckEmpty($statusauto)){$set.=",statusauto='{$statusauto}'";}
	if (CheckEmpty($kffs)){$set.=",kffs='{$kffs}'";}
	if (CheckEmpty($warehouse)){$set.=",warehouse='{$warehouse}'";}
	if (CheckEmpty($country)){$set.=",country='{$country}'";}
	if (CheckEmpty($channel)){$set.=",channel='{$channel}'";}
	if (CheckEmpty($gnkd)){$set.=",gnkd='{$gnkd}'";}
	if (CheckEmpty($reply))
	{
		if($reply_lx){$set.=",reply=concat('{$reply}； 
		',reply)";}else{$set.=",reply='{$reply}'";}
		$set.=",replytime='".time()."'";
	}

	$xingao->query("update yundan set {$set} where 1=1 {$where} ");SQLError('修改');
	$rc=mysqli_affected_rows($xingao);
	//批量修改-结束---------------------------------------
	
	
	
	
	
	
	
	
	//单个修改:修改状态---------------------------------------
	if(CheckEmpty($status))
	{
		$where_now='';
		$i=0;$err_i=0;
		$query="select * from yundan where 1=1 ".whereCS()."  {$where} {$where_now}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$err=0;
			if(!$err&&$options1&&spr($rs['status'])>=$status){$err=1;}
			if(!$err&&$options2&&!trim($rs['lotno'])){$err=1;}
			if(!$err&&$options3&&channelPar($rs['warehouse'],$rs['channel'],'shenfenzheng')&&(!$rs['s_shenfenhaoma']||!$rs['s_shenfenimg_z']||!$rs['s_shenfenimg_b']) ){$err=1;}
			
			if(!$err)
			{
				yundan_updateStatus($rs,$status,0,1);
				$i+=1;
			}else{
				$err_i++;
			}
		}
		$ts.='\\n共更新'.$i.'个运单状态';
		if($err_i){$ts.='(另有'.$err_i.'单没符合更新要求)';}
	}
	
	
	
	
	
	//单个修改:HS/HG编码---------------------------------------
	if(CheckEmpty($hscode))
	{
		$where_now='';if($hscode==0){$where_now=" and hscode=''";}
		$i=0;
		$query="select ydid from yundan where 1=1  ".whereCS()." {$where} {$where_now}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$nu=NumberCode(1);
			if($nu['err']){$ts.=',HS/HG编码不足!';break;}else{$number=$nu['number'];}
			
			$xingao->query("update yundan set hscode='".add($number)."' where ydid='{$rs[ydid]}' ");SQLError('添加hscode');
			$i+=1;
		}
		if($i){$ts.='\\n共添加'.$i.'个HS/HG编码';}
	}
	
	
	
	//单个修改:快递单号 (快递公司在上面已批量修改) ---------------------------------------
	if(CheckEmpty($gnkdydh))
	{
		if(!$gnkd)
		{
			$ts.=',请选择快递公司!';
		}else{
			$where_now='';if($gnkdydh==0){$where_now=" and gnkdydh=''";}
			$i=0;
			$query="select ydid from yundan where 1=1  ".whereCS()." {$where} {$where_now}";
			$sql=$xingao->query($query);
			while($rs=$sql->fetch_array())
			{
				$nu=NumberCode($gnkd);
				if($nu['err']){$ts.=',快递单号不足!';break;}else{$number=$nu['number'];}
				
				$xingao->query("update yundan set gnkdydh='".add($number)."' where ydid='{$rs[ydid]}' ");SQLError('添加快递单号');
				$i+=1;
			}
			if($i){$ts.='\\n共添加'.$i.'个快递单号';}
		}
	}
	
	
	
	
	//单个修改:发送跨境翼清关资料---------------------------------------
	if($ON_gd_mosuda&&CheckEmpty($gd_mosuda))
	{
		$where_now=' and weight>0';if(!$gd_mosuda){$where_now.=" and sendApi='0'";}
		$i=0;$i_er=0;
		$query="select * from yundan where 1=1  ".whereCS()." {$where} {$where_now}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			//验证渠道所有清关资料
			$table=channelPar($rs['warehouse'],$rs['channel'],'customs');
			if($table=='gd_mosuda')
			{
				require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/gd_mosuda/function.php');
				
				
				//发送
				$ret=gd_mosuda_api($rs,$table);//返回:$api_ret

				//发送返回
				if($ret==1)
				{
					//发送成功
					$xingao->query("update yundan set sendApi='1' where ydid='{$rs['ydid']}'");
					SQLError('gd_mosuda:发送成功');
					$i++;
				}else{
					//发送失败
					exit("gd_mosuda_api发送失败:{$ret}");//显示错误内容
					$i_er++;
				}
				
			}
		}
		$ts.='\\n共发送'.$i.'个运单清关资料';
		if($i_er){$ts.='(另有'.$i_er.'个运单清关资料发送失败)';}
	}
	




	//单个修改:获取物流CJ公司数据---------------------------------------
	if(CheckEmpty($cj))
	{
		require_once($_SERVER['DOCUMENT_ROOT'].'/api/logistics/cj.php');//获取页
		$where_now='';if($cj==0){$where_now=" and (cj is null or cj='')";}
		$i=0;$i_er=0;
		$query="select * from yundan where 1=1 ".whereCS()." {$where} {$where_now}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			//获取CJ数据-开始----------------
			$address=RepEmpty(yundan_add_all($rs));
			if($address)
			{
				//按地址获取面单数据
				$p_address=iconv("utf-8","euc-kr",$address);//收货地址,不能有空格,如:대전서구괴정동73-4번지천연빌딩라블리[가장로 52]
				
				//失败时,按收货邮编获取面单数据
				$p_postnum=$rs['s_zip'];//邮编如35293
				
				unset($cj_data); //要加上否则$cj_data['p_errorcd'] 显示 PHP Warning: Illegal string offset
				$cj_data['p_errorcd']=1;$x=0;
				while($cj_data['p_errorcd']!=0)
				{
					$cj_data = f_juso_refine(oracle_open_juso_refine(),oracle_open_post(),$p_clntnum,$p_clnmgmcustcd,$p_prngdivcd,$p_cgsts,$p_address,$p_postnum);//发送获取,返回数组 ,其中有$cj_data['p_errorcd']
					
					$x++;if($x>=3){break;}//失败时最多重试3次
				}
				//print_r($juso_refine);
				//获取CJ数据-结束----------------
				
				
				
				//处理数据及保存
				if($cj_data['p_errorcd']==0)
				{
					$cj_data=json_encode($cj_data);//数组转直接转为json
					$xingao->query("update yundan set cj='".add($cj_data)."' where ydid='{$rs[ydid]}' ");SQLError('添加cj');//保存
					$i+=1;
				}else{
					$i_er++;
				}
			}else{
				$i_er++;
			}

		}
		$ts.='\\n共获取'.$i.'个CJ面单数据';
		if($i_er){$ts.='(另有'.$i_er.'单的获取失败,请检查韩国地址是否正确)';}
	}
	
	
	
	//单个修改:获取物流DHL公司数据---------------------------------------
	if(CheckEmpty($dhl))
	{
		$where_now='';if($dhl==0){$where_now=" and (dhl is null or dhl='')";}
		$i=0;$i_er=0;
		$query="select * from yundan where 1=1  ".whereCS()." {$where} {$where_now}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$ret_logistics=DHL($rs);
		}
		if($ret_logistics['succeed']){$ts.='\\n共获取'.$ret_logistics['succeed'].'个DHL面单数据';}
		if($ret_logistics['failure']){$ts.='(另有'.$ret_logistics['failure'].'单的发件人信息未填写完整而无法获取)';}
	}
	
	
	
	
	
	
	
	
	
	
	if($rc>0){$ts='共修改'.$rc.'个运单！'.$ts;}
	if($ts)
	{
		exit("<script>alert('".$ts."');goBack('c');</script>");
	}else{
		exit("<script>alert('没有符合的运单或没选择要修改的内容！');goBack('c');</script>");
	}
}

exit("<script>alert('无效操作！');goBack('c');</script>");
//修改-结束------------------------------------------------------------------------------------------------
?>