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
$pervar='yundan_se';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="查看数据";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
?>
<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=all" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="?" class="gray">
        重新扫描
        </a> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 

  <!-- BEGIN PAGE CONTENT-->
<?php 
//--------------------------------------------------------------------------------------------------------------------
//提交操作--------------------------------------------------------------------------------
$type=par($_POST['type']);
$number=$_POST['number'];

$classtag='so';//标记:同个页面,同个$classtype时,要有不同标记
$classtype=3;//分类类型

if ($type=='smt')
{
	$ok=1;
	$op_proportion=par($_POST['op_proportion']);
	$op_number=par($_POST['op_number']);
	     
	$classid=GetEndArr($_POST['classid'.$classtag.$classtype]);
	if(!CheckEmpty($classid)){$classid=par($_GET['classid']);}
	
	if (!$number&&!$classid){$ppt='请输入批次号或选择航班/船运/托盘号！';$ok=0;}	
	
	if($ok)
	{
		$ppt='';
	
		//查询条件
		$sql_number=TextareaToCo($number,"','");//行转为','

		//合并显示----------------------------------------------------------------------
		if($op_number)
		{
			if(CheckEmpty($sql_number)){$where=" and lotno in ('{$sql_number}')";}
			if(CheckEmpty($classid))
			{
				$classid_all=$classid.SmallClassID($classid,'classify');
				$where.=" and classid in ({$classid_all})";
			}
	
			$sql_number=str_ireplace("','",',',$sql_number);//为了输出好看替换 
			$ppt=count_view();
		}
		//分开显示----------------------------------------------------------------------
		else
		{
			$arr=$sql_number;
			if($arr)
			{
				if(!is_array($arr)){$arr=explode("','",$arr);}//转数组
				foreach($arr as $arrkey=>$value)
				{
					$sql_number=$value;
				    if(CheckEmpty($sql_number)){$where=" and lotno in ('{$sql_number}')";}
					if(CheckEmpty($classid))
					{
						$classid_all=$classid.SmallClassID($classid,'classify');
						$where.=" and classid in ({$classid_all})";
					}
	
					$ppt=count_view();
				}
			}
		}
		
		
		
		
		
	}
}//if ($type=='smt')



//处理函数
function count_view()
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $where,$sql_number,$ppt,$op_proportion,$ok;

	if($op_proportion)
	{
		$fromtable='yundan';$fromid='';
	
		//获取要查询的运单ID
		$query="select ydid from yundan where 1=1 {$where}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$fromid.=$rs['ydid'].',';
		}
		$fromid=DelStr($fromid);
		
		if($ppt){$ppt.='<br>';}
		$ppt.='<strong>'.$sql_number;
		
		
		if (!$fromid){$ppt.='未找到任何运单！';$ok=0;}else{$ppt.='共有'.(arrcount($fromid)).'个运单';}



		//显示:统计该批号的物品的百分比
		if($ok)
		{
			
			if($op_proportion==1)
			{
				//查询总数:为显示百分比
				$t=FeData('wupin','count(*) as num,sum(`wupin_number`) as wupin_number'," fromtable='{$fromtable}' and fromid in ($fromid)");
				$total=$t['wupin_number'];
				
				$ppt.='，共有'.$total.'个物品，物品分类 (按物品所填数量统计)：</strong><br>';
				
				
				//查询数量
				$i=0;
				$query="select wupin_type,count(*) as num,sum(`wupin_number`) as wupin_number  from wupin where fromtable='{$fromtable}' and fromid in ($fromid)  group by wupin_type";
				$sql=$xingao->query($query);
				while($rs=$sql->fetch_array())
				{
					$i++;
					$proportion=spr(($rs['wupin_number']/$total*100),1);//数量÷总数×100%=百分比
					$rs['wupin_type']=is_numeric($rs['wupin_type'])?classify($rs['wupin_type'],2):cadd($rs['wupin_type']);if(!$rs['wupin_type']){$rs['wupin_type']='未选分类';}
					$ppt.= $i.'. '.$rs['wupin_type'].'：占'.$proportion.'%  &nbsp;&nbsp; 数量'.$rs['wupin_number'].'<br>';//$rs['num'] wupin_type本身数量
				}
				
			}elseif($op_proportion==2){
				
				
				//查询总数:为显示百分比
				if($op_proportion==2)
				{
					$t=FeData('wupin','count(DISTINCT wupin_type,fromid) as total'," fromtable='{$fromtable}' and fromid in ($fromid)");
					$total=$t['total'];
					$ppt.='，共有'.$total.'类物品，物品分类 (按运单所含数量统计)：</strong><br>';
				}elseif($op_proportion==3){
					$total=arrcount($fromid);
					$ppt.='，物品分类 (按运单数量计算百分比)：</strong><br>';
				}

				//查询数量
				$i=0;
				$query="select wupin_type,count(DISTINCT wupin_type,fromid) as num from wupin where fromtable='{$fromtable}' and fromid in ($fromid) group by wupin_type";
				$sql=$xingao->query($query);
				while($rs=$sql->fetch_array())
				{
					$i++;
					$proportion=spr(($rs['num']/$total*100),1);//数量÷总数×100%=百分比
					$rs['wupin_type']=is_numeric($rs['wupin_type'])?classify($rs['wupin_type'],2):cadd($rs['wupin_type']);if(!$rs['wupin_type']){$rs['wupin_type']='未选分类';}
					$ppt.= $i.'. '.$rs['wupin_type'].'：占'.$proportion.'%  &nbsp;&nbsp; 数量'.$rs['num'].'<br>';//$rs['num'] wupin_type本身数量
				}
				
			}
			$_SESSION['alert_color']='success';
		}
		
		
	}
	return $ppt;
}

?>

<?php 
//--------------------------------------------------------------------------------------------------------------------
//输出各种处理的提示--------------------------------------------------------------------------------------------------
if($ppt)
{
	$alert_color=$_SESSION['alert_color'];$_SESSION['alert_color']='';
	if(!$alert_color){$alert_color='danger';}
	XAalert($ppt,$alert_color);
}
?>

<?php 
//--------------------------------------------------------------------------------------------------------------------
//显示扫描框--------------------------------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/view_form.php');
?>

</div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
