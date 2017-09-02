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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');

$lx='add';
$bg_zxyd=par($_GET['bg_zxyd']);

$headtitle=$LG['name.nav_14'];//包裹预报
$submit_name=$LG['submit'];
if($bg_zxyd){$headtitle=$LG['baoguo.add_form_1'];$submit_name=$LG['baoguo.add_form_23'];}
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

if(!$off_baoguo||!$member_per[$Mgroupid]['ON_Mbaoguo']){exit ("<script>alert('{$LG['baoguo.add_form_2']}');goBack();</script>");}
if($bg_zxyd&&!$off_baoguo_zxyd){exit ("<script>alert('{$LG['baoguo.add_form_3']}');goBack();</script>");}
elseif(!$off_baoguo_zxyd&&!$off_baoguo_yubao){exit ("<script>alert('{$LG['baoguo.add_form_4']}');goBack();</script>");}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("baoguo");
?>
<style>
.table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td
{border-top:0px;}
</style>
<div class="page_ny"> 
	<!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=kuwai" class="gray"><?=$LG['baoguo.add_form_24']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
				<?=$headtitle?>
				</a> </h3>
		</div>
	</div>
	<!-- END PAGE HEADER--> 
	
	<!-- BEGIN PAGE CONTENT-->
	
	<form action="add_save.php" method="post" class="form-horizontal form-bordered" name="xingao">
		<input name="bg_zxyd" type="hidden" value="<?=add($bg_zxyd)?>">
		<input name="lx" type="hidden" value="<?=add($lx)?>">
		<input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
		<div><!-- class="tabbable tabbable-custom boxless"-->
		<ul class="nav nav-tabs">
			<li class="active"><a href="javascript:void(0)"><?=$LG['baoguo.add_form_24']?></a></li>
			<li><a href="excel_import.php"><?=$LG['name.nav_20'];//批量导入?></a></li>
		</ul>			
	
	<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<div class="form">
						<div class="form-body">
							<div class="portlet">
<div class="portlet-body form" style="display: block;"> 
<!--表单内容-开始------------------------------------------------------------------------------------------------------>

<table width="100%" class="table">
  <tr>
    <td bgcolor="#DDDDDD"><!---->
      
      <table width="100%">
        <tr align="center" class="title">
          <td bgcolor='#ffffff'><?=$LG['baoguo.add_form_5'];//快递单号?></td>
          <td bgcolor='#ffffff'><?=$LG['baoguo.add_form_6'];//快递公司?></td>
          <td bgcolor='#ffffff'><?=$LG['baoguo.add_form_7'];//寄至仓库?></td>
          <td bgcolor='#ffffff'><?=$LG['baoguo.add_form_8'];//发货点?></td>
          <td bgcolor='#ffffff'><?=$LG['baoguo.add_form_9'];//购物网站?></td>
          <td bgcolor='#ffffff'><?=$LG['baoguo.add_form_10'];//发货/购物?></td>
        </tr>
        <tr align="center">
          <td bgcolor='#ffffff' class="has-error"><input name="wupin_id_b[]" type="hidden" value="1" size="1" id="wupin_id"/>
            
            <!--hidden用于判断是哪个包裹的物品-->
            
            <input name="bgydh[]"  type="text"   class="form-control" style="width:150px;" required id=""  title="<?=$LG['baoguo.add_form_11'];//如果没有运单号可填写购物的订单号?>"/></td>
          <td bgcolor='#ffffff' ><select name="kuaidi[]"  id="" class="form-control"  data-placeholder="<?=$LG['baoguo.add_form_12'];//选择?>"  style="width:120px;">
              <!--不能加select2me 复制行会失效并且不可点击--> 
              <!--ID空时就不复制数据--> 
              <?php baoguo_kuaidi('');?>
            </select></td>
          <td bgcolor='#ffffff' class="has-error"><select name="warehouse[]"  class="form-control"  data-placeholder="<?=$LG['baoguo.add_form_12'];//选择?>" required style="width:150px;">
              <!--不能加select2me 复制行会失效并且不可点击--> 
              <?php warehouse('',1,1);?>
            </select></td>
          <td bgcolor='#ffffff'><input name="fahuodiqu[]"  type="text"  style="width:100px;" class="form-control" ></td>
          <td bgcolor='#ffffff'><select name="wangzhan[]" style="width:100px;" onchange="change(this.value)"  class="form-control"  data-placeholder="<?=$LG['baoguo.add_form_12'];//选择?>">
              <!--不能加select2me 复制行会失效并且不可点击--> 
              <?php wangzhan('',1)?>
            </select>
            <input name="wangzhan_other[]" class="form-control" style="width:100px;" type="text" title="<?=$LG['baoguo.add_form_13'];//选择其他购物网站时请填写?>" ></td>
          <td bgcolor='#ffffff'><input name="fahuotime[]" type="text"  style="width:100px;"  class=" form-control form-control-inline date-picker"  data-date-format="yyyy-mm-dd"></td>
        </tr>
        <tr align="center">
          <td colspan="6" align="center" bgcolor='#ffffff'><textarea name="content[]" class="form-control" style="width:98%;" placeholder="<?=$LG['baoguo.add_form_14'];//请在此写入您的要求或者任何有利于区分货物、查询货物的信息，比如包装的重量长宽高等?>"></textarea></td>
        </tr>
        <tr>
          <td colspan="6" align="center" bgcolor='#FFFFFF'><table width="100%">
              <tr>
                <td>
				  <?php $wupin_id_s=1;?>
                  <input name="wupin_id_s[]" type="hidden" value="<?=$wupin_id_s?>" size="2" id="wupin_id"/>
                  <!--hidden用于判断是哪个包裹的物品--> 
                  <?php $bgForecast=1;wupin_from_general();?>
                  </td>
              </tr>
            </table></td>
        </tr>
      </table>
      
      <!----></td>
    <td bgcolor="#DDDDDD"><a href=" javascript:void(0)" onclick="addProductDetail2(this)" class="red tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['baoguo.add_form_15'];//增加包裹?>"><i class="icon-plus-sign"></i></a> <br />
      <br />
      <a href="javascript:void(0)" name="deleteHref" onclick="delProductDetail2(this)" style="display: none" class="red tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['baoguo.add_form_16'];//删除包裹?>"><i class="icon-minus-sign"></i></a></td>
  </tr>
</table>

			<div align="right" style="background-color:#DDDDDD; padding-right:50px;">
				<br />
				<?=$LG['baoguo.add_form_26'];//申报总价:?>
				<input type="hidden" id="declarevalue"/>
				<!--没使用到，数据没保存只是显示--> 
				<font class="show_price" id="lblinsureamounte" >0</font>
				<?=$XAsc?>
				<br /><br />
			</div>


<!--表单内容-结束------------------------------------------------------------------------------------------------------> 

</div>
							</div>
						</div>
					</div>
				</div>
				<div align="center">
					<button type="submit"  class="btn btn-primary"> <i class="icon-ok"></i> <?=$submit_name?> </button>
					<button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
				</div>
			</div>
		</div>
	</form>
	<div class="xats"><?=$LG['pptInfo']?>
    <br />
        
		<?php if($bg_zxyd){?><font class="red2">
		&raquo; <?=$LG['baoguo.add_form_18'];//必须是同一个仓库才能合箱发货?>
		<?php if($member_per[$Mgroupid]['baoguo_fh']>0){echo LGtag($LG['baoguo.add_form_19'],'<tag1>=='.$member_per[$Mgroupid]['baoguo_fh']);}?>
		</font><br /><?php }?>
		&raquo; <?=$LG['baoguo.add_form_21']?><br />
		&raquo; <?=$LG['baoguo.add_form_22']?><br />
	</div>
</div>

<script src="/js/AntongJQ.js" type="text/javascript"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/js/baoguoJS.php');?>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
<script language="javascript">
//单独分开,要放在foot.php后面
$(function(){       
     wangzhan_other();//包裹表单,选择其他购物网站时
});
//单独分开,要放在foot.php后面
$(function(){       
   CalcDeclareValue();//包裹表单,修改时自动计算总物品价值
});
</script>
