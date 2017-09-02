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
$headtitle="批量修改";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//显示表单获取,处理
$lx=par($_POST['lx']);
$forList=par($_POST['forList']);
$id_name='Xydid';

$ydid=par(ToStr($_GET['ydid']));
if($forList&&(!$ydid||is_array($_GET['ydid']))){$ydid=$_SESSION[$id_name];}//如果是数组,说明是从底部点击的按钮,要用_SESSION才能获取分页里的勾选
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > 
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
        </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="batch_save.php" method="post" class="form-horizontal form-bordered" name="xingao" target="_blank">
  <input type="hidden" name="lx" value="tj">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
			<div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><font class="red2"><i class="icon-reorder"></i> 修改内容</font></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;">
                  <!--表单内容-->
                  
                  
               <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['status']//状态?></label>
                  <div class="col-md-10">
					<select  class="form-control input-medium select2me" data-placeholder="请选择" name="status">
					<option> </option>
                    <?php yundan_Status('')?>
                   </select>
					<span class="help-block">
					<input type="checkbox" name="options1" value="1" <?=$options1||!$lx?'checked':''?>>未超过该状态时才修改<br>
					<input type="checkbox" name="options2" value="1" <?=$options2||!$lx?'checked':''?>>有填写批次号时才修改<br>
					<input type="checkbox" name="options3" value="1" <?=$options3||!$lx?'checked':''?>>对于要上传证件的渠道，有完整证件时才修改<br>
					</span>
                  </div>
                </div>   
                
                <div class="form-group">
                  <label class="control-label col-md-2">批次号</label>
                  <div class="col-md-10">
					 <input type="text" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="填“del”则清除"  name="lotno" placeholder="留空则不修改">
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-2">托盘号</label>
                  <div class="col-md-10">
					<?php 
                        $classtag='save';//标记:同个页面,同个$classtype时,要有不同标记
                        $classtype=3;//分类类型
                        //$classid=$rs['classid'];//已保存的ID
                        require($_SERVER['DOCUMENT_ROOT'].'/public/classify.php');
                    ?>
                   <input type="text" class="form-control input-small" name="classid_del" placeholder="填“del”则清除" value="<?=$classid_del?>">
                  </div>
                </div>
               
				<div class="form-group">
                  <label class="control-label col-md-2">自动更新状态</label>
                  <div class="col-md-10">
                    <div class="radio-list">
					 <label class="radio-inline">
					 <input type="radio" name="statusauto" value="" checked> 不修改
					 </label>
					 <label class="radio-inline">
					 <input type="radio" name="statusauto" value="1"> 自动
					 </label>
					 <label class="radio-inline">
					 <input type="radio" name="statusauto" value="0"> 不自动
					 </label>  
                    </div>
                  </div>
                </div>
				<div class="form-group">
                  <label class="control-label col-md-2">扣费方式</label>
                  <div class="col-md-10">
                    <div class="radio-list">
					 <label class="radio-inline">
					 <input type="radio" name="kffs" value="" checked> 不修改
					 </label>
					 <label class="radio-inline">
					 <input type="radio" name="kffs" value="1"> 自动扣费
					 </label>
					 <label class="radio-inline">
					 <input type="radio" name="kffs" value="0"> 不自动扣费
					 </label>  
                    </div>
                  </div>
                </div>	
                 
                <div class="form-group">
                  <label class="control-label col-md-2">HS/HG编码</label>
                  <div class="col-md-10">
                    <div class="radio-list">
					 <label class="radio-inline">
					 <input type="radio" name="hscode" value="" checked> 不修改
					 </label>
					 <label class="radio-inline tooltips" data-container="body" data-placement="top" data-original-title="未添加过的则添加">
					 <input type="radio" name="hscode" value="0"> 自动添加
					 </label>
					 <label class="radio-inline tooltips" data-container="body" data-placement="top" data-original-title="已添加过的也重新添加">
					 <input type="radio" name="hscode" value="1"> 重新添加
					 </label>  
                    </div>
                  </div>
                </div>
                
               <?php if($ON_gd_mosuda){?>
                <div class="form-group">
                  <label class="control-label col-md-2">跨境翼清关资料</label>
                  <div class="col-md-10">
                    <div class="radio-list">
					 <label class="radio-inline">
					 <input type="radio" name="gd_mosuda" value="" checked> 不发送
					 </label>
					 <label class="radio-inline tooltips" data-container="body" data-placement="top" data-original-title="发送 未发送过的运单">
					 <input type="radio" name="gd_mosuda" value="0"> 自动发送
					 </label>
					 <label class="radio-inline tooltips" data-container="body" data-placement="top" data-original-title="不管是否发送过都重新发送">
					 <input type="radio" name="gd_mosuda" value="1"> 全部重发
					 </label>  
                    </div>
                    <span class="help-block">
                    &raquo; 运单需要计重后才能发送<br>
                    &raquo; 发件人资料用本公司,并且资料内容只能英文和数字(在【系统设置】中配置) <?=have($ONLanguage,'EN',0)?'(自动调用英文版内容)':''?><br>
                    </span>
                  </div>
                </div>
               <?php }?>

               <?php if($ON_cj){?>
                <div class="form-group">
                  <label class="control-label col-md-2">获取CJ面单数据</label>
                  <div class="col-md-10">
                    <div class="radio-list">
					 <label class="radio-inline">
					 <input type="radio" name="cj" value="" checked> 不修改
					 </label>
					 <label class="radio-inline tooltips" data-container="body" data-placement="top" data-original-title="获取 未获取过的运单 (从CJ平台获取数据)">
					 <input type="radio" name="cj" value="0"> 自动获取
					 </label>
					 <label class="radio-inline tooltips" data-container="body" data-placement="top" data-original-title="不管是否获取过都重新获取 (从CJ平台获取数据)">
					 <input type="radio" name="cj" value="1"> 全部获取
					 </label>  
                    </div>
                  </div>
                </div>
               <?php }?>
                
               <?php if($ON_dhl){?>
                <div class="form-group">
                  <label class="control-label col-md-2">获取DHL面单数据</label>
                  <div class="col-md-10">
                    <div class="radio-list">
					 <label class="radio-inline">
					 <input type="radio" name="dhl" value="" checked> 不修改
					 </label>
					 <label class="radio-inline tooltips" data-container="body" data-placement="top" data-original-title="获取 未获取过的运单 (从DHL平台获取数据)">
					 <input type="radio" name="dhl" value="0"> 自动获取
					 </label>
					 <label class="radio-inline tooltips" data-container="body" data-placement="top" data-original-title="不管是否获取过都重新获取 (从DHL平台获取数据)">
					 <input type="radio" name="dhl" value="1"> 全部获取
					 </label>  
                    </div>
                    <span class="help-block">
                   &raquo;  此操作速度较慢,请耐心等待。<br>


            <a onMouseOver="document.getElementById('hide_dhl').style.display='block'" onMouseOut="document.getElementById('hide_dhl').style.display='none'">
            &raquo; 提示错误,如:<b>Fatal error</b>:  Uncaught SoapFault exception: ...
            </a>

            <div id="hide_dhl" style="display:none;"> 
      ◆ 接口账号信息填写错误 (请在 系统设置>接口 中检查修正)<br>
      ◆ 发件人信息填写错误 (姓名、手机号码、城市、邮编、区镇(街道)、具体地址(街道号) <strong>必须有该地址</strong>并且不能为中文)<br>
      ◆ 当前网络不好<br>
      ◆ 确定以上都无问题后，过30分钟后再试 （DHL有时需要验证IP，需要等待通过验证）<br>
            </div>
                    
                        </span>
                  </div>
                </div>
               <?php }?>
               
               
                 
               <div class="form-group">
                  <label class="control-label col-md-2">派送快递</label>
                  <div class="col-md-10">
                   <select  class="form-control input-medium select2me" data-placeholder="留空则不修改" name="gnkd">
                    <option selected></option>
                   <?php yundan_gnkd('')?>
                  </select>
                   <div class="radio-list">
					 <label class="radio-inline">
					 <input type="radio" name="gnkdydh" value="" checked> 不修改单号
					 </label>

					 <label class="radio-inline tooltips" data-container="body" data-placement="top" data-original-title="未添加过的则添加 (需要选择快递公司)">
					 <input type="radio" name="gnkdydh" value="0"> 自动添加单号
					 </label>
					 <label class="radio-inline tooltips" data-container="body" data-placement="top" data-original-title="已添加过的也重新添加 (需要选择快递公司)">
					 <input type="radio" name="gnkdydh" value="1"> 重新添加单号
					 </label>  
                    </div>
                  </div>
                </div>
               			
				<div class="form-group">
                    <label class="control-label col-md-2">所在仓库</label>
                    <div class="col-md-10">
                     <select name="warehouse" class="form-control input-medium select2me" data-placeholder="留空则不修改" onChange="channel_show();">
						 <option></option>
						 <?php warehouse('',1);?>
					 </select>
                    </div>
                  </div>
                  
                  <?php if($ON_country){?>
                     <div class="form-group">
                       <label class="control-label col-md-2">寄往国家</label>
                      <div class="col-md-10">
                       <select  class="form-control input-medium select2me" name="country" data-placeholder="留空则不修改">
                            <option></option>
                            <?=Country('',2)?>
                        </select>
                     </div>
                     </div>
                  <?php }?>
                  
				<div class="form-group">
                  <label class="control-label col-md-2">运输渠道</label>
                  <div class="col-md-10">
					<span id='channel'></span>
                    <span class="help-block">以上是显示该仓库所有渠道 （是否可用还视国家和会员组设置而定，请自行检查）</span>
                  </div>
                </div>
                
				<div class="form-group">
					<label class="control-label col-md-2">回复</label>
					<div class="col-md-10">
						<textarea  class="form-control" rows="3" name="reply"  placeholder="留空则不修改"></textarea>
						<span class="help-block">
						 <div class="radio-list">
							<label class="radio-inline"><input type="radio" name="reply_lx" value="1" checked>增加方式 </label>
							<label class="radio-inline"><input type="radio" name="reply_lx" value="0">修改方式 </label>
						</div>
						</span>
					</div>
				</div>

                </div>
              </div>
			  <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/where_form.php');?>
			  			
          </div>
          
          </div>
        </div>
        
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">

      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled 
      onClick="
      document.xingao.lx.value='tj';
      document.xingao.target='_blank';
      "
      > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
      
      <button type="submit" class="btn btn-grey input-small"
      onClick="
      document.xingao.lx.value='num';
      document.xingao.target='iframe';
      ">显示运单数 </button>
      
      <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
      
      <iframe src="" id="iframe" name="iframe" width="250" height="0" frameborder="0" scrolling="auto"></iframe>
	  <script>        $(function(){ iframeHeight('iframe'); });        </script>
         
    </div>
    
    
    
      </div>
    </div>
  </form>
 <div class="xats"><br>
	<strong>提示:<br></strong>
	&raquo; 修改状态时发邮件通知速度较慢，操作完后请不要立即关闭本页面! (建议等待10秒后再关闭)<br />
	&raquo; 获外站获取数据时，速度较慢请耐心等待不要反复刷新!<br />
</div>
 
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
<script language="javascript">
//显示渠道下拉
function channel_show() 
{
	var warehouse=document.getElementsByName("warehouse")[0].value;
	var country=document.getElementsByName("country")[0].value;
	var xmlhttp_channel=createAjax(); 
	if (xmlhttp_channel) 
	{  
		xmlhttp_channel.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp_channel.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp_channel.send('lx=channel&callFrom=manage&channel=&warehouse='+warehouse+'&country='+country+'');

		xmlhttp_channel.onreadystatechange=function() 
		{  
			if (xmlhttp_channel.readyState==4 && xmlhttp_channel.status==200) 
			{ 
				document.getElementById('channel').innerHTML='<select  class="form-control input-medium select2me" data-placeholder="渠道" name="channel"><option value=""></option>'+unescape(xmlhttp_channel.responseText)+'</select>'; 
			}
		}
	}
}





//单独分开,要放在foot.php后面
$(function(){       
	 channel_show();//渠道输出
});
</script>
