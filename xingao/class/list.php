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
$pervar='qita';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="栏目列表";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

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
        <button type="button" class="btn btn-info" onClick="location.href='form.php';"><i class="icon-plus"></i> 添加栏目 </button>
      </ul>
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div>
    <div> 
      <!--搜索-->
      
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
栏目菜单
LevelClass_list($bclassid,$level);//$bclassid=0，$level=0为根目录
*/
function LevelClass_list($bclassid,$level)
{   
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $hide_classid;

	if($bclassid!=-1)
	{
		$level+=1;
/*		if($level==1)
		{
			$ico='icon-th-large';
		}elseif($level==2){
			$ico='icon-list-alt';
		}elseif($level==3){
			$ico='icon-columns';
		}elseif($level==4){
			$ico='icon-copy';
		}else{
			$ico='icon-file';
		}
*/		
		if($level==1)
		{
			echo '<div class="tree-folder" style="display: block;">';
			$bold='font-weight: bold;';
		}else{
			echo '<div class="tree-folder-content">';
			$bold='';
		}
			
		$query="select * from class where bclassid='".$bclassid."' and classid not in ({$hide_classid}) order by myorder desc,classid desc";
		$bclassid=-1;
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{

			switch($rs['classtype'])
			{
				case "1":
					$ico='icon-copy'; 
					$type=ClassType(1); 
					$add='../article/form.php?classid='.$rs['classid'];
					$list='../article/list.php?so=1&classid='.$rs['classid'];
					$target='';
					break;
				case "2":
					$ico='icon-picture'; 
					$type=ClassType(2); 
					$add='../article/form.php?classid='.$rs['classid'];
					$list='../article/list.php?so=1&classid='.$rs['classid'];
					$target='';
					break;
				case "3":
					$ico='icon-shopping-cart'; 
					$type=ClassType(3); 
					$add='../mall/form.php?classid='.$rs['classid'];
					$list='../mall/list.php?so=1&classid='.$rs['classid'];
					$rs['path']='/mall/list.php?classid='.$rs['classid'];
					$target='';
					break;
				case "4":
					$ico='icon-file'; 
					$type=ClassType(4); 
					$add='';
					$list='form.php?lx=edit&classid='.$rs['classid'].'#tab_2';	
					$target='_blank';
					break;
			}

?>
                  <!------------------------->
                  <div class="tree-item"> <i class="tree-dot"></i>
                    <div class="tree-item-name"><i class="<?=$ico?>"></i> 
                    
              <a href="<?=$list?>" target="<?=$target?>"  class="<?=$rs['checked']?'':'gray';?> popovers" data-trigger="hover" data-placement="top"  data-content="
              <?php 
			    echo '<strong>';
				echo 'ID:'.$rs['classid'];
				echo $rs['checked']?'':'<br>不显示在前台';
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
              " style="<?=$bold?>">
              <?=cadd($rs['name'.$LT])?>
              </a>
              
			 <font class="gray_prompt2">(<?=$type?> | 浏览<?=$rs['onclick']?>次 | ID<?=$rs['classid']?>)</font>
                      
                     
                      <div class="tree-actions"><?php if($add){?> <a href="<?=$add?>" target="_blank"><i class="icon-plus"> 添加信息</i></a><?php }?>
                      <a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):pathLT($rs['path'])?>" target="_blank"><i class="icon-eye-open"> 浏览</i></a>
                      <a href="form.php?lx=edit&classid=<?=$rs['classid']?>"><i class="icon-pencil"> <?=$LG['edit']?></i></a>
                      
                      <?php if (!delclass_yz($rs['classid'])){?>
                      <a href="save.php?lx=del&classid=<?=$rs['classid']?>" onClick="return confirm('警告:栏目里信息也将删除! 确认要删除吗?此操作不可恢复! ');"><i class="icon-remove"> 删除</i></a>
                      <?php }else{ echo '<font title="重要栏目，不可删除，不可改原意">禁删  </font>';}?>
                     
                       <i>
                        <input type="hidden" name="myorderid[]" value="<?=$rs['classid']?>">
                        <input type="text"  name="myorder[]" value="<?=$rs['myorder']?>" size="5" title="栏目排序(越小排越前)" style="height:20px;">
                        </i> <i>
                        <input type="checkbox" name="classid[]" value="<?=$rs['classid']?>" />
                        </i> </div>
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

LevelClass_list(0,0);
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
  document.XingAoForm.lx.value='editorder'; 
  "><i class="icon-signin"></i> 修改排序</button>
  
   <!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='del';
  return confirm('警告:栏目里信息也将删除! 确认要删除所选吗?此操作不可恢复! ');
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