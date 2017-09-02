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
$pervar='mall';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle='';
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
if(!$off_mall)
{
	exit ("<script>alert('商城系统未开启,无法使用！');goBack('uc');</script>");
}

$classid=par($_GET['classid']);
$cr=ClassData($classid);
$headtitle=$cr['name'.$LT].' '.$headtitle;

//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$attr=par($_GET['attr']);
	$checked=par($_GET['checked']);
	$time=par($_GET['time']);
	$warehouse=par($_GET['warehouse']);


	if($key)
	{
		//语言字段处理--
		if(!$LGList){$LGList=languageType('',3);}
		if($LGList)
		{
			foreach($LGList as $arrkey=>$language)
			{
				$where_title.=" or title{$language} like '%{$key}%' or seokey{$language} like '%{$key}%'";
			}
		}
		
		$where.=" and (category like '%{$key}%' or coding='{$key}' or username='{$key}'  or userid='".CheckNumber($key,-0.1)."' or mlid='".CheckNumber($key,-0.1)."' {$where_title})";
	}
	
	
	switch($attr)
	{
		case "isgood":
			$where.=" and isgood>'0'";
			break;
		case "ishead":
			$where.=" and ishead>'0'";
			break;
		case "istop":
			$where.=" and istop>'0'";
			break;
		case "less":
			$where.=" and number<='10'";
			break;
	}
	
	if(CheckEmpty($checked)){$where.=" and checked='{$checked}'";}
	if(CheckEmpty($warehouse)){$where.=" and (warehouse like '%{$warehouse}%' or warehouse='' )";}
	if($time)
	{
		$nowtime=time()-$time;
		$where.=" and addtime>='$nowtime'";
	}
	if($classid)
	{
		$where.=" and classid='{$classid}'";
	}

	$search.="&so={$so}&key={$key}&attr={$attr}&checked={$checked}&time={$time}&classid={$classid}&warehouse={$warehouse}";
}

$order=' order by istop desc,isgood desc,ishead desc,edittime desc,addtime desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


$query="select * from mall where {$where} {$Xwh} {$order}";

//$line=20;$page_line=15;//分页处理，不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
      <h3 class="page-title"><a href="../class/list.php" class="gray">栏目列表</a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> 
		<small>(<a href="list.php?so=1&classid=0" class="gray">查看所有栏目商品</a>)</small>
		</h3>
		
      <ul class="page-breadcrumb breadcrumb">
        <button type="button" class="btn btn-info" onClick="location.href='form.php?classid=<?=$classid?>';"><i class="icon-plus"></i> <?=$LG['add']?> </button>
      </ul>
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="portlet tabbable">
    <div class="portlet-body" style="padding:10px;"> 
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <input name="classid" type="hidden" value="<?=$classid?>">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="标题/发布者/发布者id/商品id/SEO关键字/编号/类别 (可留空)" value="<?=$key?>">
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
                <select  class="form-control input-small select2me" name="attr" data-placeholder="属性">
                  <option></option>
                  <option value="isgood"  <?=$attr=='isgood'?' selected':''?>>推荐</option>
                  <option value="ishead"  <?=$attr=='ishead'?' selected':''?>>头条</option>
                  <option value="istop"  <?=$attr=='istop'?' selected':''?>>置顶</option>
                  <option value="less"  <?=$attr=='less'?' selected':''?>>库存低</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-medium select2me" name="warehouse" data-placeholder="仓库">
                  <option></option>
                  <?=warehouse($warehouse,1)?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="checked" data-placeholder="上架">
                  <option></option>
                  <option value="0"  <?=$checked=='0'?' selected':''?>>下架</option>
                  <option value="1"  <?=$checked=='1'?' selected':''?>>上架</option>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
          </form>
        </div>
      </div>
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <input name="classid" type="hidden" value="<?=$classid?>">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="全选/取消"/>
              </th>
              <th align="center"><a href="?<?=$search?>&orderby=price&orderlx=" class="<?=orac('price')?>">单价</a>/<a href="?<?=$search?>&orderby=mlid&orderlx=" class="<?=orac('mlid')?>">其他</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=number&orderlx=" class="<?=orac('number')?>">库存</a>/<a href="?<?=$search?>&orderby=number_sold&orderlx=" class="<?=orac('number_sold')?>">已售</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=weight&orderlx=" class="<?=orac('weight')?>">重量</a>/<a href="?<?=$search?>&orderby=number_limit&orderlx=" class="<?=orac('number_limit')?>">限量</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">发布者</a></th>
              <th align="center"> <a href="?<?=$search?>&orderby=edittime&orderlx=" class="<?=orac('edittime')?>" title="按修改时间排序">修改</a>/<a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>" title="按添加时间排序">添加</a> </th>
              <th align="center"><a href="?<?=$search?>&orderby=onclick&orderlx=" class="<?=orac('onclick')?>">浏览</a>/<a href="?<?=$search?>&orderby=plclick&orderlx=" class="<?=orac('plclick')?>">评论</a></th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
<?php
$tri=0;
while($rs=$sql->fetch_array())
{
		$tri++;
		$rs['unit']=classify($rs['unit'],2);
?>
            <tr class="odd gradeX <?=!$rs['checked']?'gray2':''?>">
              <td rowspan="2" align="center" valign="middle"><input type="checkbox" id="a" onClick="chkColor(this);" name="mlid[]" value="<?=$rs['mlid']?>" />
              <font class="tooltips gray2" data-container="body" data-placement="top" data-original-title="商品ID:<?=$rs['mlid']?> 本页排序号:<?=$tri?>"><?=$tri?></font>
                <?php EnlargeImg(ImgAdd($rs['titleimg'.$LT]),$rs['mlid'],2)?>
                
                <font class="tooltips" data-container="body" data-placement="top" data-original-title="商品编号"><?=cadd($rs['coding'])?></font> 
                
                </td>
              <td align="center" valign="middle"><?=spr($rs['price'])?> <?=$XAmc?><br>
                <font class="gray" title="<?=cadd($rs['price_otherwhy'])?>">
                <?=spr($rs['price_other'])?>
                <?=$XAmc?>
                </font></td>
              <td align="center" valign="middle">
			    <font <?php if($rs['number']<=10){echo 'class="red" title="库存低"';}?>><?=$rs['number']?>
                <?=cadd($rs['unit'])?></font>
                <br>
                <font class="gray" title="已售">
                <?=$rs['number_sold']?>
                <?=cadd($rs['unit'])?>
                </font></td>
              <td align="center" valign="middle"><?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?>
                <br>
                <font class="gray" title="限量出售">
                <?=$rs['number_limit']?>
                </font><font class="gray" title="已售">
                <?=cadd($rs['unit'])?>
                </font></td>
              <td align="center" valign="middle"><?=cadd($rs['username'])?>
                <br>
                <font class="gray">
                <?=$rs['userid']?>
                </font></td>
              <td align="center" valign="middle"><font  title="修改时间">
                <?=DateYmd($rs['edittime'],1)?>
                </font><br>
                <font class="gray" title="添加时间">
                <?=DateYmd($rs['addtime'],1)?>
                </font></td>
              <td align="center" valign="middle">
				<font  title="浏览次数">
				<?=$rs['onclick']?> 
				</font>
				<br>
				<font class="gray" title="评论条数">
				<?=$rs['plclick']?> 
				</font>
				</td>
              <td align="center" valign="middle"><a href="form.php?lx=edit&mlid=<?=$rs['mlid']?>&classid=<?=$classid?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> 编辑</a>
                <?php if($rs['checked']){?>
                <a href="save.php?lx=attr&checked=0&mlid=<?=$rs['mlid']?>" class="btn btn-xs btn-default"><i class="icon-check-empty"></i> 下架</a>
                <?php }else{ ?>
                <a href="save.php?lx=attr&checked=1&mlid=<?=$rs['mlid']?>" class="btn btn-xs btn-success"><i class="icon-check"></i> 上架</a>
                <?php }?>
                <a href="save.php?lx=del&mlid=<?=$rs['mlid']?>&classid=<?=$classid?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a></td>
            </tr>
            <!---->
            <tr>
              <td colspan="7" align="left">
              <div align="left" class="gray modal_border <?=!$rs['checked']?'gray2a':''?>"> 
              <!--显示标题-->
              <a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):'/mall/show.php?mlid='.$rs['mlid']?>" target="_blank"  class=" popovers" data-trigger="hover" data-placement="top"  data-content="
              <?php 
			    echo '<strong>';
				echo 'ID:'.$rs['mlid'];
				echo $rs['checked']?'':'<br>未上架';
				echo '</strong>';
				
				//语言字段处理--
				if(!$LGList){$LGList=languageType('',3);}
				if($LGList)
				{
					foreach($LGList as $arrkey=>$language)
					{
						if($language!=$LT){echo '<br>'.cadd($rs['title'.$language]);}
					}
				}
			  ?>
              " style="color:<?=cadd($rs['titlecolor'])?>">
              <?=cadd($rs['title'.$LT])?>
              </a>
             
                  
                 
                  
            <?php 
			if($rs['ensure']){echo '<span class="label label-sm label-default">正品保证</span>';}
			if(cadd($rs['selling'.$LT])){echo '<span class="label label-sm label-default" title="'.cadd($rs['selling'.$LT]).'">卖点</span>';}
			?>
           
            <span class="label label-sm label-default" title="<?php if($rs['integral_to']==1&&$rs['integral_use']==1){ echo '无消费积分时赠送积分,消费与赠送1:'.$integral_mall;}
            elseif($rs['integral_to']==1||$rs['integral_to']==2){ echo '赠送积分,消费与赠送1:'.$integral_mall;}
            elseif(!$rs['integral_to']){ echo '不送积分';}
            ?>"> <?php if(!$rs['integral_to']){ echo '不送积分';}else{echo '送积分';}?>
            </span>

            
                            <br>
                  <?php 
			   echo isgood($rs['isgood']);
			   echo ishead($rs['ishead']);
			   echo istop($rs['istop']);
			   ?>
                </div>
                <div class="gray modal_border">
                  <?php 
				  if(!$classid){ echo '栏目:'.ClassData($rs['classid'],'name'.$LT).'<span class="xa_sep"> | </span>';}
				  if($rs['warehouse']){ echo '仓库:'.warehouse($rs['warehouse']).'<span class="xa_sep"> | </span>';}
				  if($rs['brand']){ echo '品牌:'.classify($rs['brand'],2).'<span class="xa_sep"> | </span>';}
				  if($rs['size']){ echo '尺寸:'.cadd($rs['size']).'<span class="xa_sep"> | </span>';}
				  if($rs['color']){ echo '颜色:'.cadd($rs['color']).'<span class="xa_sep"> | </span>';}
				  if($rs['package']){ echo '套餐:'.cadd($rs['package']).'<span class="xa_sep"> | </span>';}
				  ?>
                </div>
                
                <!----></td>
            </tr>
            <?php
}
?>
          </tbody>
        </table>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<select  class="form-control input-small select2me" data-placeholder="推荐" name="isgood" style="margin-right:10px;" title="级别越大越排前">
            <option></option>
            <?php isgood('',1)?>
          </select>
          <select  class="form-control input-small select2me" data-placeholder="头条" name="ishead" style="margin-right:10px;" title="级别越大越排前">
            <option></option>
            <?php ishead('',1)?>
          </select>
          <select  class="form-control input-small select2me" data-placeholder="置顶" name="istop" style="margin-right:10px;" title="级别越大越排前">
            <option></option>
            <?php istop('',1)?>
          </select>
          <select  class="form-control input-small select2me" data-placeholder="上架" name="checked" style="margin-right:10px;">
            <option></option>
            <option value="0" >下架</option>
            <option value="1" >上架</option>
          </select>
          <!--btn-primary--><button type="submit" class="btn btn-grey" 
         onClick="
  document.XingAoForm.lx.value='attr';
  return confirm('确认修改所选商品的属性吗? ');
  "><i class="icon-signin"></i> 修改属性</button>
          <!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='del';
  return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');
  "><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>
        </div>
        <div class="row">
          <?=$listpage?>
        </div>
      </form>
    </div>
    <!--表格内容结束--> 
    
  </div>
</div>
<?php
$sql->free(); //释放资源
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
