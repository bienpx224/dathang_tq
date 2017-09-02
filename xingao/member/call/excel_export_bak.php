<?php 
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');

	//清空
	$excel_i=0;$ppt='';
	$arr=ToArr($openCurrency,',');
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			$toMoney[$value]=0;
			$fromMoney[$value]=0;
		}
	}
	
	
	$query="select * from {$table} where {$where} order by addtime asc";
	$sql=$xingao->query($query);
	
	
	//----------------------------------------------money_czbak--------------------------------------------------------
	if($table=='money_czbak')
	{
		while($rs=$sql->fetch_array())
		{ 
			$excel_i+=1;
			//读取内容-开始
			  $data[] = array(
				'list1'=>$rs['userid'],
				'list2'=>cadd($rs['username']),
				'list3'=>money_cz($rs['type']),
				'list4'=>$rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':'',
				'list5'=>BrToTextarea($rs['title']),
				'list6'=>spr($rs['toMoney']).cadd($rs['toCurrency']),
				'list7'=>spr($rs['fromMoney']).cadd($rs['fromCurrency']),
				'list8'=>spr($rs['remain']).cadd($rs['toCurrency']),
				'list9'=>cadd($rs['operator']),
				'list10'=>DateYmd($rs['addtime'],1),
			  );		
			//读取内容-结束
			$toMoney[$rs['toCurrency']]+=$rs['toMoney'];
			$fromMoney[$rs['fromCurrency']]+=$rs['fromMoney'];
		}
		
		//总计
		$toMoney_total='';$fromMoney_total='';
		$arr=ToArr($openCurrency,',');
		if($arr)
		{
			foreach($arr as $arrkey=>$value)
			{
                if(spr($toMoney[$value])){$toMoney_total.= $toMoney[$value].$value.'；';}
                if(spr($fromMoney[$value])){$fromMoney_total.= $fromMoney[$value].$value.'；';}
			}
		}
		
		$data[] = array(
		  'list1'=>'总计',
		  'list2'=>'',
		  'list3'=>'',
		  'list4'=>'',
		  'list5'=>'',
		  'list6'=>$toMoney_total,
		  'list7'=>$fromMoney_total,
		  'list8'=>'',
		  'list9'=>'',
		  'list10'=>$excel_i.'条记录',
		);		


		//正式导出
		$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10');//保存列数
		$cnTable=array(
			'会员ID',//list1
			'会员名',//list2
			'充值/退款类型',//list3
			'用途',//list4
			'来源说明',//list5
			'充值',//list6
			'转账',//list7
			'账户',//list8
			'操作员ID',//list9
			'充值时间'//list10
		);//列表名
	}









	//--------------------------------------------money_kfbak----------------------------------------------------------
	elseif($table=='money_kfbak')
	{
		while($rs=$sql->fetch_array())
		{ 
			$excel_i+=1;
			//读取内容-开始
			  $data[] = array(
				'list1'=>$rs['userid'],
				'list2'=>cadd($rs['username']),
				'list3'=>money_kf($rs['type']),
				'list4'=>$rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':'',
				'list5'=>BrToTextarea($rs['title']),
				'list6'=>spr($rs['toMoney']).cadd($rs['toCurrency']),
				'list7'=>spr($rs['remain']).cadd($rs['toCurrency']),
				'list8'=>cadd($rs['operator']),
				'list9'=>DateYmd($rs['addtime'],1),
			  );		
			//读取内容-结束
			$toMoney[$rs['toCurrency']]+=$rs['toMoney'];
			$fromMoney[$rs['fromCurrency']]+=$rs['fromMoney'];
		}
		//总计
		$toMoney_total='';$fromMoney_total='';
		$arr=ToArr($openCurrency,',');
		if($arr)
		{
			foreach($arr as $arrkey=>$value)
			{
                if(spr($toMoney[$value])){$toMoney_total.= $toMoney[$value].$value.'；';}
                if(spr($fromMoney[$value])){$fromMoney_total.= $fromMoney[$value].$value.'；';}
			}
		}
		
		$data[] = array(
		  'list1'=>'总计',
		  'list2'=>'',
		  'list3'=>'',
		  'list4'=>'',
		  'list5'=>'',
		  'list6'=>$toMoney_total,
		  'list7'=>'',
		  'list8'=>'',
		  'list9'=>$excel_i.'条记录',
		);		
			
		//正式导出
		$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9');//保存列数
		$cnTable=array(
			'会员ID',//list1
			'会员名',//list2
			'扣费类型',//list3
			'用途',//list4
			'使用说明',//list5
			'金额',//list6
			'账户',//list7
			'操作员ID',//list8
			'扣费时间'//list9
		);//列表名
	}









	//---------------------------------money_czbak money_kfbak-----------------------------------
	elseif($table=='money_bak')
	{
		$query="select * from 
		(
			select 'cz' as flag,id,userid,username,fromtable,fromid,fromMoney,fromCurrency,toMoney,toCurrency,exchange,remain,type,title,content,addtime from money_czbak
		UNION ALL
			select 'kf' as flag,id,userid,username,fromtable,fromid,fromMoney,fromCurrency,toMoney,toCurrency,exchange,remain,type,title,content,addtime from money_kfbak
		) 
			a where {$where}  order by addtime asc
		";
		$sql=$xingao->query($query);
		
		while($rs=$sql->fetch_array())
		{ 
			if($rs['flag']=='cz')
			{
				$rs['type']=money_cz($rs['type']);
				$rs['toMoney']=spr($rs['toMoney']);
				$i_cz++;
			}elseif($rs['flag']=='kf'){
				$rs['type']=money_kf($rs['type']);
				$rs['toMoney']='-'.spr($rs['fromMoney']);
				$i_kf++;
			}
			
			$excel_i+=1;
			//读取内容-开始
			  $data[] = array(
				'list1'=>$rs['userid'],
				'list2'=>cadd($rs['username']),
				'list3'=>$rs['type'],
				'list4'=>$rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':'',
				'list5'=>BrToTextarea($rs['title']),
				'list6'=>$rs['flag']=='cz'?spr($rs['toMoney']).$rs['toCurrency']:'-'.spr($rs['fromMoney']).$rs['fromCurrency'],
				'list7'=>spr($rs['remain']).cadd($rs['toCurrency']),
				'list8'=>cadd($rs['operator']),
				'list9'=>DateYmd($rs['addtime'],1),
			  );		
			//读取内容-结束
			$toMoney[$rs['toCurrency']]+=$rs['toMoney'];
			$fromMoney[$rs['fromCurrency']]+=$rs['fromMoney'];
		}
		//总计
		$toMoney_total='';$fromMoney_total='';
		$arr=ToArr($openCurrency,',');
		if($arr)
		{
			foreach($arr as $arrkey=>$value)
			{
                if(spr($toMoney[$value])){$toMoney_total.= $toMoney[$value].$value.'；';}
                if(spr($fromMoney[$value])){$fromMoney_total.= $fromMoney[$value].$value.'；';}
			}
		}
		
		
		//总计
		$data[] = array(
		  'list1'=>'总计',
		  'list2'=>'',
		  'list3'=>'',
		  'list4'=>'',
		  'list5'=>'',
		  'list6'=>$toMoney_total,
		  'list7'=>'',
		  'list8'=>'',
		  'list9'=>'增费'.spr($i_cz,2,1).'次; 扣费'.spr($i_kf,2,1).'次',
		);		


		//正式导出
		$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9');//保存列数
		$cnTable=array(
			'会员ID',//list1
			'会员名',//list2
			'类型',//list3
			'用途',//list4
			'说明',//list5
			'金额',//list6
			'账户',//list7
			'操作员ID',//list8
			'时间'//list9
		);//列表名
	}









	//----------------------------------------------integral_czbak--------------------------------------------------------
	elseif($table=='integral_czbak')
	{
		while($rs=$sql->fetch_array())
		{ 
			$excel_i+=1;
			//读取内容-开始
			  $data[] = array(
				'list1'=>$rs['userid'],
				'list2'=>cadd($rs['username']),
				'list3'=>integral_cz($rs['type']),
				'list4'=>$rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':'',
				'list5'=>BrToTextarea($rs['title']),
				'list6'=>$rs['integral'].'分',
				'list7'=>$rs['remain'].'分',
				'list8'=>cadd($rs['operator']),
				'list9'=>DateYmd($rs['addtime'],1),
			  );		
			//读取内容-结束
			$total_integral+=$rs['integral'];
		}
		
		//总计
		$data[] = array(
		  'list1'=>'总计',
		  'list2'=>'',
		  'list3'=>'',
		  'list4'=>'',
		  'list5'=>'',
		  'list6'=>$total_integral.'分',
		  'list7'=>'',
		  'list8'=>'',
		  'list9'=>$excel_i.'条记录',
		);		


		//正式导出
		$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9');//保存列数
		$cnTable=array(
			'会员ID',//list1
			'会员名',//list2
			'加积分类型',//list3
			'用途',//list4
			'来源说明',//list5
			'加积分',//list6
			'账户',//list7
			'操作员ID',//list8
			'加积分时间',//list9
		);//列表名
	}









	//----------------------------------------------integral_kfbak--------------------------------------------------------
	elseif($table=='integral_kfbak')
	{
		while($rs=$sql->fetch_array())
		{ 
			$excel_i+=1;
			//读取内容-开始
			  $data[] = array(
				'list1'=>$rs['userid'],
				'list2'=>cadd($rs['username']),
				'list3'=>integral_kf($rs['type']),
				'list4'=>$rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':'',
				'list5'=>BrToTextarea($rs['title']),
				'list6'=>'-'.$rs['integral'].'分',
				'list7'=>spr($rs['money']).$XAmc,
				'list8'=>$rs['remain'].'分',
				'list9'=>cadd($rs['operator']),
				'list10'=>DateYmd($rs['addtime'],1),
			  );		
			//读取内容-结束
			$total_integral+=$rs['integral'];
			$money_total+=$rs['money'];
		}
		
		
		
		//总计
		$data[] = array(
		  'list1'=>'总计',
		  'list2'=>'',
		  'list3'=>'',
		  'list4'=>'',
		  'list5'=>'',
		  'list6'=>'-'.$total_integral.'分',
		  'list7'=>$money_total.$XAmc,
		  'list8'=>'',
		  'list9'=>'',
		  'list10'=>$excel_i.'条记录',
		);	
		$money_total=0;	


		//正式导出
		$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10');//保存列数
		$cnTable=array(
			'会员ID',//list1
			'会员名',//list2
			'扣分类型',//list3
			'用途',//list4
			'扣分说明',//list5
			'扣分',//list6
			'抵消金额',//list7
			'账户',//list8
			'操作员ID',//list9
			'扣分时间'//list10
		);//列表名
	}




	//--------------------------------------------integral_czbak integral_kfbak-------------------------------------
	elseif($table=='integral_bak')
	{
		$query="select * from 
		(
			select 'cz' as flag,id,userid,username,fromtable,fromid,integral,type,title,content,addtime from integral_czbak
		UNION ALL
			select 'kf' as flag,id,userid,username,fromtable,fromid,integral,type,title,content,addtime from integral_kfbak
		) 
			a where {$where}  order by addtime asc
		";
		$sql=$xingao->query($query);
		
		while($rs=$sql->fetch_array())
		{ 
			if($rs['flag']=='cz')
			{
				$rs['type']=integral_cz($rs['type']);
				$rs['integral']=spr($rs['integral']);
				$i_cz++;
			}elseif($rs['flag']=='kf'){
				$rs['type']=integral_kf($rs['type']);
				$rs['integral']='-'.spr($rs['integral']);
				$i_kf++;
			}
			
			$excel_i+=1;
			//读取内容-开始
			  $data[] = array(
				'list1'=>$rs['userid'],
				'list2'=>cadd($rs['username']),
				'list3'=>$rs['type'],
				'list4'=>$rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':'',
				'list5'=>BrToTextarea($rs['title']),
				'list6'=>$rs['integral'].'分',
				'list7'=>$rs['remain'].'分',
				'list8'=>cadd($rs['operator']),
				'list9'=>DateYmd($rs['addtime'],1),
			  );		
			//读取内容-结束
			$total_integral+=$rs['integral'];
		}
		
		//总计
		$data[] = array(
		  'list1'=>'总计',
		  'list2'=>'',
		  'list3'=>'',
		  'list4'=>'',
		  'list5'=>'',
		  'list6'=>spr($total_integral).'分',
		  'list7'=>'',
		  'list8'=>'',
		  'list9'=>'加分'.spr($i_cz,2,1).'次; 扣分'.spr($i_kf,2,1).'次',
		);		


		//正式导出
		$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9');//保存列数
		$cnTable=array(
			'会员ID',//list1
			'会员名',//list2
			'类型',//list3
			'用途',//list4
			'说明',//list5
			'积分',//list6
			'账户',//list7
			'操作员ID',//list8
			'时间'//list9
		);//列表名
	}




	//------------------------------------------------------------------------------------------------------
	$excel-> getExcel($data,$cnTable,$enTable,'other',20);
	//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

if ($excel_i){
?>
<meta charset="utf-8">
<link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<br><br><br><br>

<div class="alert alert-block alert-info fade in alert_cs col-md-9">
  <h4 class="alert-heading">导出成功: 共导出约有<?=$excel_i?>条</h4>
	<p><br></p>
	<p><a class="btn btn-danger" href="?lx=del">删除服务器上文件并关闭页面 (防止他人下载,下载完后请删除)</a></p>
	<p>注意:导出时会删除之前的文件，因此如果之前文件没下载完，请下载完本次导出文件后再重新导出之前的信息!</p>
</div>
<?php
	echo '<script language=javascript>';
	echo 'location.href="'.$path.$xaname.'";';
	echo '</script>';
	XAtsto($path.$xaname);
}else{
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('uc');</script>");
}
?>