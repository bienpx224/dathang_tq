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
$pervar='classify';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="分类管理";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


//搜索
$where=" and 1=1";
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$classtype=par($_GET['classtype']);
	$checked=par($_GET['checked']);
	$time=par($_GET['time']);

	if($key)
	{
		//语言字段处理--
		if(!$LGList){$LGList=languageType('',3);}
		if($LGList)
		{
			foreach($LGList as $arrkey=>$language)
			{
				$where_name.=" or name{$language} like '%{$key}%'";
			}
		}
		
		$where.=" and ( classid='".CheckNumber($key,-0.1)."'  {$where_name} )";
	}
	
	if(CheckEmpty($classtype)){$where.=" and classtype='{$classtype}'";}
	if(CheckEmpty($checked)){$where.=" and checked='{$checked}'";}
	if($time)
	{
		$nowtime=time()-$time;
		$where.=" and addtime>='$nowtime'";
	}
	$search.="&so={$so}&key={$key}&classtype={$classtype}&checked={$checked}&time={$time}";
}
?>

<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/fuelux/css/tree-conquer.css" />

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
      <h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
      <ul class="page-breadcrumb breadcrumb">
        <button type="button" class="btn btn-info" onClick="location.href='form_3.php';"><i class="icon-plus"></i> 添加<?=ClassifyType(3)?>分类 </button>
        <button type="button" class="btn btn-info" onClick="location.href='form.php';"><i class="icon-plus"></i> 添加其他分类 </button>
      </ul>
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div>
    <div> 
      <!--搜索-->
  <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <input name="classid" type="hidden" value="<?=$classid?>">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="大分类名称/大分类ID (可留空)" value="<?=$key?>">
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="classtype" data-placeholder="类型">
                  <option></option>
                  <?=ClassifyType($classtype,1)?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="time" data-placeholder="添加时间">
                  <option></option>
                  <option value="86400" <?=$time=='86400'?' selected':''?>>1天内</option>
                  <option value="172800" <?=$time=='172800'?' selected':''?>>2天内</option>
                  <option value="604800" <?=$time=='604800'?' selected':''?>>1周内</option>
                  <option value="2592000" <?=$time=='2592000'?' selected':''?>>1个月内</option>
                  <option value="7948800" <?=$time=='7948800'?' selected':''?>>3个月内</option>
                  <option value="15897600" <?=$time=='15897600'?' selected':''?>>6个月内</option>
                  <option value="31536000" <?=$time=='31536000'?' selected':''?>>1年内</option>
                </select>
              </div>
            </div>
            <div class="form-group">
                <div class="col-md-0">
                  <select  class="form-control input-small select2me" name="checked" data-placeholder="可用">
                    <option></option>
                    <option value="0"  <?=$checked=='0'?' selected':''?>>不可用</option>
                    <option value="1"  <?=$checked=='1'?' selected':''?>>可用</option>
                  </select>
               </div>
              </div>
              
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> 搜索大分类 </button>
          </form>
        </div>
      </div>
          
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<div class="portlet">
              <div class="portlet-title"> 
                <!--<div class="caption"><i class="icon-cogs"></i>Tree Style #5 - No line</div>-->
                <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="javascript:;" class="reload" onClick="javascript:window.location.href=window.location.href;"></a> </div>
              </div>
              <div class="portlet-body" style="padding: 10px;">
                <div classid="MyTree_new" class="tree">
                  <div class="tree-folder" style="display:none;">
                    <div class="tree-folder-header"> <i class="icon-folder-close"></i>
                      <div class="tree-folder-name"></div>
                    </div>
                    <div class="tree-folder-content"></div>
                    <div class="tree-loader" style="display: none;"></div>
                  </div>
<?php

/*
分类菜单
LevelClass_list($bclassid,$level);//$bclassid=0，$level=0为根目录
*/
function LevelClass_list($bclassid,$level,$where='')
{   
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');

	if($bclassid!=-1)
	{
		$level+=1;
		if($level==1)
		{
			echo '<div class="tree-folder" style="display: block;">';
			$bold='font-weight: bold;';
		}else{
			echo '<div class="tree-folder-content">';
			$bold='';
		}
			
		$query="select * from classify where  bclassid='".$bclassid."' {$where} order by myorder desc,classid asc";
		$bclassid=-1;
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{

			switch($rs['classtype'])
			{
				case '1':
					$ico='icon-barcode'; 
					$type=ClassifyType(1); 
					$form='';
					break;
				case '2':
					$ico='icon-tasks'; 
					$type=ClassifyType(2); 
					$form='';
					break;
				case '3':
					$ico='icon-plane'; 
					$type=ClassifyType(3); 
					$form='_3';
					break;
				case '4':
					$ico='icon-th-large'; 
					$type=ClassifyType(4); 
					break;
				case '5':
					$ico='icon-columns'; 
					$type=ClassifyType(5); 
					break;
				case '6':
					$ico='icon-adn'; 
					$type=ClassifyType(6); 
					break;
				case '7':
					$ico='icon-list-ol'; 
					$type=ClassifyType(7); 
					break;
				case '8':
					$ico='icon-dashboard'; 
					$type=ClassifyType(8); 
					break;
			}
?>
                  <!------------------------->
                  <div class="tree-item"> <i class="tree-dot"></i>
                    <div class="tree-item-name">
                    
                      <i class="<?=$ico?>"></i> 
                      
                      <span class="<?=$rs['checked']?'':'gray2';?> popovers" data-trigger="hover" data-placement="top"  data-content="
              <?php 
			    echo '<strong>';
				echo $rs['checked']?'':'不可用';
				echo '</strong>';
				
				//语言字段处理--
				if(!$LGList){$LGList=languageType('',3);}
				if($LGList)
				{
					foreach($LGList as $arrkey=>$language)
					{
						if($language!=$LT){echo '<br>'.cadd($rs['name'.$language]);}
					}
				}
			  ?>
              ">
              <?=cadd($rs['name'.$LT])?>
              </span>
  
  
              
             
 
  
  
                      
                      <font class="gray_prompt2">(ID:<font class="red"><?=$rs['classid']?></font> | <?=$type?>)</font>
                      
                     
                      <div class="tree-actions">
                      <a href="form<?=$form?>.php?lx=edit&classid=<?=$rs['classid']?>"><i class="icon-pencil"> <?=$LG['edit']?></i></a>
                      
                       <a href="print.php?classid=<?=$rs['classid']?>" target="_blank"><i class="icon-print"> 打印</i></a>
                     
                      <a href="save.php?lx=del&classid=<?=$rs['classid']?>" onClick="return confirm('警告:分类里信息也将删除(运单除外)! 确认要删除吗?此操作不可恢复! ');"><i class="icon-remove"> 删除</i></a>
                      
                     
                       <i>
                        <input type="hidden" name="myorderid[]" value="<?=$rs['classid']?>">
                        <input type="text"  name="myorder[]" value="<?=$rs['myorder']?>" size="5" title="分类排序(越小排越前)" style="height:20px;">
                        </i> 
                        
                        <i>
                        <input type="checkbox" name="classid[]" value="<?=$rs['classid']?>" />
                        </i> 
                        
                        </div>
                    </div>
                  </div>
<?php			
			$bclassid=$rs['classid'];
			LevelClass_list($bclassid,$level);
			
		}//while($rs=$sql->fetch_array())
		
		if($level!=1)
		{
			echo '</div>';
		}else{
			echo '</div>';
		}
		return $level;
	}
	
}

LevelClass_list(0,0,$where);
?>
                </div>
              </div>
            </div>
          </div>
        </div>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<button type="submit" class="btn btn-info" 
    onClick="
    document.XingAoForm.action='save.php';
    document.XingAoForm.lx.value='editorder'; 
    document.XingAoForm.target='';
    "><i class="icon-signin"></i> 修改排序</button>
    
    <button type="submit" class="btn btn-grey" 
    onClick="
    document.XingAoForm.action='print.php';
    document.XingAoForm.target='_blank';
    "><i class="icon-signin"></i> 打印所选</button>
  
    <!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
    document.XingAoForm.lx.value='del';
    return confirm('警告:分类里信息也将删除! 确认要删除所选吗?此操作不可恢复! ');
    "><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>

</div>
      </form>
    </div>
    <!--表格内容结束--> 
    
  </div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="/bootstrap/plugins/fuelux/js/tree.min.js"></script> 
<script src="/bootstrap/scripts/ui-tree.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 

<script>
$(function(){       
UITree.init();
});
</script> 