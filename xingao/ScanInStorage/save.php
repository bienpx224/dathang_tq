<?php 
$typ=par($_POST['typ']);
$AutoSmt=$_REQUEST['AutoSmt'];//自动提交
$bgydh=par($_REQUEST['bgydh']);//外部获取
$weight=spr($_REQUEST['weight']);//外部获取
$alert_color='info';

//扫描－开始---------------------------------------------------------------------------------
if ($typ=="smt")
{
	if (!$bgydh){echo ("<script>alert('请输入或扫描运单号！');goBack();</script>");}
	
	$_SESSION['smbg']=par($_POST['smbg']);
	$_SESSION['smdg']=par($_POST['smdg']);
	$_SESSION['smbg_op1']=par($_POST['smbg_op1']);
	$_SESSION['smyd']=par($_POST['smyd']);
	$_SESSION['smyd_op1']=par($_POST['smyd_op1']);









	
	//扫描代购单========================================================================
	/*
		需要先扫描代购单,因为包裹会有分号段搜索,导致每次都能搜索到同一个包裹
	*/
	if(!$rsid&&$_SESSION['smdg']&&$ON_daigou)
	{
		//按入库码搜索
		$id='dgid';
		$table='daigou';
		$field='whcod';
		$where_search="";//有其他条件时,以空格 and 开头,如: and userid='{$userid}'
		$smlx=1;//1只精确搜索;0全部搜索
		$ts='未找到信息';
		$rsid=searchNumber($id,$table,$field,$bgydh,$smlx,$where_search);//搜索处理文件
		
		//按单号搜索
		if(!$rsid)
		{
			$id='dgid';
			$table='daigou';
			$field='dgdh';
			$smlx=0;//1只精确搜索;0全部搜索
			$where_search="";//有其他条件时,以空格 and 开头,如: and userid='{$userid}'
			$ts='未找到信息';
			$rsid=searchNumber($id,$table,$field,$bgydh,$smlx,$where_search);//搜索处理

		}
	
		if($rsid)
		{
			//验证
			$chk=1;
			//<!--2017.06.17:删除dgid-->
			$rs=FeData('daigou','dgid,status,username,userid',"dgid='{$rsid}'");
			if(have(spr($rs['status']),'9,10'))
			{
				$chk=0;
				$ts= '<strong>'.$bgydh.'(ID'.$rsid.')：</strong>该代购单处于【'.daigou_Status(spr($rs['status'])).'】,不能再操作！<br>';
			}

			if($chk)
			{
				$xssm=0;
				
				$url="/xingao/daigou/inStorage.php?dgid={$rsid}&weight={$weight}";
				echo '<script language=javascript>';
				echo "window.open('{$url}')";
				echo '</script>';
				$ts="<a href='{$url}' target='_blank' class='red2'>点击打开代购单</a> (如果没有自动打开新页面，请关闭浏览器的广告屏蔽功能)";
			}else{
				$ts.='<font class=gray_prompt>所属会员:'.$rs['username'].'('.$rs['userid'].')</font><br><br>';
				unset($rs);
			}

		}
	}
	











	//扫描包裹========================================================================
	if(!$rsid&&$_SESSION['smbg']&&$off_baoguo)
	{
		$id='bgid';
		$table='baoguo';
		$field='bgydh';
		$where_search="";//有其他条件时,以空格 and 开头,如: and userid='{$userid}'
		$smlx=0;//1只精确搜索;0全部搜索
		$ts='<font class="red">未找到包裹</font>:<a href="/xingao/baoguo/form.php" target="_blank">手工添加</a>';
		$rsid=searchNumber($id,$table,$field,$bgydh,$smlx,$where_search);//搜索处理

		
		if($rsid)
		{
			$ts='';$chk=1;
			
			$rs=FeData('baoguo','*',"bgid='{$rsid}'");
			//验证
			if(have(spr($rs['status']),'2,3,4,10'))
			{
				$chk=0;
				$ts= '<strong>'.$bgydh.'(ID'.$rsid.')：</strong>该包裹处于【'.baoguo_Status(spr($rs['status'])).'】,不能再操作！<br>';
			}
			
			if($chk)
			{
				$xssm=0;
				
				$url="/xingao/baoguo/inStorage.php?bgid={$rsid}&weight={$weight}";
				echo '<script language=javascript>';
				echo "window.open('{$url}')";
				echo '</script>';
				$ts="<a href='{$url}' target='_blank' class='red2'>点击打开包裹</a> (如果没有自动打开新页面，请关闭浏览器的广告屏蔽功能)";
			}else{
				$ts.='<font class=gray_prompt>所属会员:'.$rs['username'].'('.$rs['userid'].')</font><br><br>';
			}
			unset($rs);
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//扫描运单========================================================================
	if(!$rsid&&$_SESSION['smyd'])
	{
		//寄库快递单号
		$id='ydid';
		$table='yundan';
		$field='gwkdydh';
		$smlx=0;//1只精确搜索;0全部搜索
		$where_search="";//有其他条件时,以空格 and 开头,如: and userid='{$userid}'
		$ts='未找到信息';
		$rsid=searchNumber($id,$table,$field,$bgydh,$smlx,$where_search);//搜索处理
		
		//运单号
		if(!$rsid)
		{
			$id='ydid';
			$table='yundan';
			$field='ydh';
			$smlx=1;//1只精确搜索;0全部搜索
			$where_search="";//有其他条件时,以空格 and 开头,如: and userid='{$userid}'
			$ts='未找到信息';
			$rsid=searchNumber($id,$table,$field,$bgydh,$smlx,$where_search);//搜索处理
		}
	
		if($rsid)
		{
			$ts='';
			if($_SESSION['smyd_op1']==1){
				$url="/xingao/yundan/form.php?typ=edit&ydid={$rsid}&sm=1&weight={$weight}";
				echo "<script language=javascript>window.open('{$url}')</script>";
			}elseif($_SESSION['smyd_op1']==2){
				$url="/xingao/yundan/calc_fee.php?ydid={$rsid}&weight={$weight}";
				echo "<script language=javascript>window.open('{$url}')</script>";
			}elseif($_SESSION['smyd_op1']==3){
				$i_up=0;
				$query="select * from yundan where status='-1' and ydid in ({$rsid})";
				$sql=$xingao->query($query);
				while($rs=$sql->fetch_array())
				{
					yundan_updateStatus($rs,$update_status=0,0,1);
					$i_up+=1;
				}
				
				$ts='找到'.arrcount($rsid).'个运单,其中有'.$i_up.'个运单未入库,现已更新为已入库!';
			}
			if(!$ts){$ts="<a href='{$url}' target='_blank' class='red2'>点击打开运单</a> (如果没有自动打开新页面，请关闭浏览器的广告屏蔽功能)";}
							

			
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	//扫描不到时，转向新添加包裹表单========================================================================
	if(!$rsid&&$_SESSION['smbg'])
	{
		music('no');//播放提示声音
		
		if($_SESSION['smbg_op1'])
		{
			echo '<script language=javascript>';
			echo "window.open('/xingao/baoguo/kuaisu.php?bgydh={$bgydh}&sm=1&weight={$weight}')";
			echo '</script>';
		}
		$alert_color='danger';
	}

}
?>
