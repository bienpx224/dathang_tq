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
$pervar='manage_sy';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="系统设置";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理

 $text_ts=' &raquo; 值必须唯一，并且保存后不要再修改值否则已添加的数据会错乱，名称可随便改<br />
 &nbsp;&nbsp;格式：名称=唯一值（以101开头，3位数）<br />  
 &nbsp;&nbsp;如：<br />
 &nbsp;&nbsp;苹果=101<br />  
 &nbsp;&nbsp;华为=102<br />';
 
$up_ts='
 <span class="help-block">如果设置大于php.ini中的配置，需要修改php.ini配置
(目前upload_max_filesize='.@get_cfg_var("upload_max_filesize").' / <font title="最大不要超过1.8G">post_max_size='. @get_cfg_var("post_max_size").'</font> ) </span>
';
	
//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("config_sys");
?>
  
<style>input,textarea{ margin-top:5px !important; margin-bottom:5px !important;}</style>
<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title">
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
      </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="edit">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">网站</a></li>
        <li><a href="#tab_2" data-toggle="tab">主要</a></li>
        <li><a href="#tab_4" data-toggle="tab">包裹</a></li>
        <li><a href="#tab_5" data-toggle="tab">运单</a></li>
        <li><a href="#tab_6" data-toggle="tab">代购</a></li>
        <li><a href="#tab_7" data-toggle="tab">商城</a></li>
        <li><a href="#tab_8" data-toggle="tab">会员</a></li>
        <li><a href="#tab_3" data-toggle="tab">积分</a></li>
        <li><a href="#tab_9" data-toggle="tab">接口</a></li>
        <li><a href="#tab_10" data-toggle="tab">安全</a></li>
        <li><a href="#tab_12" data-toggle="tab">后台</a></li>
        <li><a href="#tab_11" data-toggle="tab">其他</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>网站基本配置</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">网站地址：</td>
                    <td align="left"><input name="siteurl" type="text" class="form-control"  value="<?=cadd($siteurl)?>"/>
                      <span class="help-block">格式如：http://www.xxx.com/，如果无域名或备案中，请填写 /</span></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">网站名称：</td>
                    <td align="left">
<?php 
//语言字段处理++
$field='sitename';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <input name="<?=$field.$language?>" type="text" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" maxlength="40" value="<?=cadd($val)?>"><br>
		<?php 
	}
}
?>
                     </td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">SEO标题：</td>
                    <td align="left">
<?php 
//语言字段处理++
$field='sitetitle';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <input name="<?=$field.$language?>" type="text" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" maxlength="40" value="<?=cadd($val)?>"><br>
		<?php 
	}
}
?>
                      <span class="help-block"> 在前台网页标题显示，请填写网站主营关键词,可用英文(|)符号分开</span></td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">SEO关键字：</td>
                    <td align="left">
<?php 
//语言字段处理++
$field='sitekey';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <input name="<?=$field.$language?>" type="text" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" maxlength="50" value="<?=cadd($val)?>"><br>
		<?php 
	}
}
?>
                      <span class="help-block"> 填写网站主营关键词,必须用英文“,”符号分开</span></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">SEO简介：</td>
                    <td align="left">
<?php 
//语言字段处理++
$field='sitetext';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <textarea name="<?=$field.$language?>" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" rows="2"  maxlength="100"><?=cadd($val)?></textarea>
		<?php 
	}
}
?>
                      <span class="help-block"> 简要的概括网站的内容(也就是网站所提供的服务或者所经营的产品),在搜索引擎显示的内容简介</span></td>
                  </tr>
                  
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">流量统计查看链接：</td>
                    <td align="left"><input name="traffic" type="text"  class="form-control" value="<?=cadd($traffic)?>"/>
                      <span class="help-block"> 是查看的链接,不是统计代码(要以http://开头)</span></td>
                  </tr>
                  
                  
                  <tr class="odd gradeX">
                    <td colspan="2"></td>
                  </tr>
                  
<tr class="odd gradeX">
                    <td align="right"  width="200">关闭会员中心：</td>
                    <td align="left">
                    
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_site_member" value="1" <?=$off_site_member==1?'checked':''?> />
                      </div>
                      
                    </td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">关闭会员提示：</td>
                    <td align="left">
<?php 
//语言字段处理++
$field='site_member_ts';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <textarea name="<?=$field.$language?>" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" rows="2"  maxlength="100"><?=cadd($val)?></textarea>
		<?php 
	}
}
?>
                    </td>
                  </tr>
                  
             
           
                <tr class="odd gradeX">
                    <td align="right"  width="200">关闭后台管理：</td>
                    <td align="left">
                    
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_site_manage" value="<?=$Xusername?>" <?=$off_site_manage?'checked':''?> />
                      </div>
                      <span class="help-block"> 关闭后其他账号都无法登录,只要您的账号能登录开启</span>
                    </td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">关闭后台提示：</td>
                    <td align="left">
<?php 
//语言字段处理++
$field='site_manage_ts';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <textarea name="<?=$field.$language?>" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" rows="2"  maxlength="100"><?=cadd($val)?></textarea>
		<?php
	}
}
?>
                    </td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td colspan="2"></td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">会员中心导航下拉：</td>
                    <td align="left">
                     <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_member_nav" value="1" <?=$off_member_nav?'checked':''?> />
                      </div>
                      <span class="help-block">开启时会显示下拉，可能会经常档住会员操作</span>
                    
                    </td>
                  </tr>
                               

                <tr class="odd gradeX">
                    <td align="right"  width="200">界面颜色：</td>
                    <td align="left">
                    会员系统:
                    <select name="theme_member">
                  	  <?=theme_color($theme_member)?>
                    </select>
                    <input name="theme_member_ico" type="checkbox" value="1"  <?=$theme_member_ico?'checked':''?>/>显示子菜单三角图标
                    
                   <br><br>

                    后台系统:
                    <select name="theme_manage">
                  	  <?=theme_color($theme_manage)?>
                   </select>
                    <input name="theme_manage_ico" type="checkbox" value="1"  <?=$theme_manage_ico?'checked':''?>/>显示子菜单三角图标
                    </td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td colspan="2"></td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td  width="200" rowspan="3" align="right"><strong>语言：</strong></td>
                    <td align="left">

默认语种:
<select name="LGDefault">
<?php 
$languageList=languageType('',2);
if($languageList)
{
	foreach($languageList as $arrkey=>$value)
	{
		 $selected=$LGDefault==$value?'selected':''; echo '<option value="'.$value.'" '.$selected.'>'.languageType($value).'</option>';
	}
}
?>
</select>

                    </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="left">
支持语种：
<?php 
$languageList=languageType('',2);
if($languageList)
{
	foreach($languageList as $arrkey=>$value)
	{
		?>
		<input type="checkbox" name="ONLanguage[]" value="<?=$value?>" <?=have($ONLanguage,$value,1)?'checked':''?> ><?=languageType($value)?>
		<?php 
	}
}
?>
                    </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="left">
正式开放：
<?php 
$languageList=languageType('',2);
if($languageList)
{
	foreach($languageList as $arrkey=>$value)
	{
		?>
		<input type="checkbox" name="openLanguage[]" value="<?=$value?>" <?=have($openLanguage,$value,1)?'checked':''?> ><?=languageType($value)?>
		<?php 
	}
}
?>
<br>
<span class="gray2">开放后会员才能选择该语种(后台不限制)。必须是上面勾选支持的</span>
                    
                    
                    
                    </td>
                  </tr>
                 
                </tbody>
              </table>
            </div>
          </div>
          <!---->
          
		   <!---->
          
        </div>
        <div class="tab-pane" id="tab_2">
          <div class="form">
            <div class="form-body"> 
              
              
              <!--版块-->
              
              
 <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>系统/功能开关</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  <tr class="odd gradeX">
                    <td align="center" valign="middle">代购<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="ON_daigou" value="1" <?=$ON_daigou?'checked':''?> />
                      </div></td>

                    <td align="center" valign="middle">晒单<br>
                    	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                    		<input type="checkbox" class="toggle" name="off_shaidan" value="1" <?=$off_shaidan?'checked':''?> />
                    		</div></td>

                  
                  	<td align="center" valign="middle">商城系统<br>
<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                          <input type="checkbox" class="toggle" name="off_mall" value="1"  <?=$off_mall==1?'checked':''?> />
                        </div></td>
						

                  	<td align="center" valign="middle"> 积分系统<br>
<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                          <input type="checkbox" class="toggle" name="off_integral" value="1"  <?=$off_integral==1?'checked':''?> />
                        </div></td>
                  	<td align="center" valign="middle"></td>
						
                    </tr>
					<tr class="odd gradeX">
					 <td align="center" valign="middle">晒单审核<br>
<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="shaidan_checked" value="1" <?=$shaidan_checked?'checked':''?> />
                      </div>
                      <span class="help-block">晒单需要后台审核</span></td>
                    <td align="center" valign="middle">评论审核<br>

                  <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="comments_checked" value="1" <?=$comments_checked?'checked':''?> />
                      </div>
                      <span class="help-block">各种评论需要后台审核</span></td>	
						
                  	<td align="center" valign="middle"><a class="popovers" data-trigger="hover" data-placement="top"  data-content="关闭时，所有接入到本站的API功能都关闭">接入本站的API</a><br>

                       <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_api" value="1" <?=$off_api?'checked':''?> />
                      </div>
                      </td>
                  	<td align="center" valign="middle">
                    <a class="popovers" data-trigger="hover" data-placement="top"  data-content="开启后禁止删除已过期的关键记录，保留主要用于数据统计 (作用于：包裹、运单、优惠券/折扣券、理赔、提现、积分、账户等记录)">长期保存记录</a><br>

                       <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_delbak" value="1" <?=$off_delbak?'checked':''?> />
                      </div>
                    </td>
                  	<td align="center" valign="middle">&nbsp;</td>
                  	</tr>
					<tr class="odd gradeX">
					  <td align="center" valign="middle">日本清关资料<br>
<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                          <input type="checkbox" class="toggle" name="ON_gd_japan" value="1"  <?=$ON_gd_japan?'checked':''?> />
                        </div></td>
                        
<td align="center" valign="middle">仓储功能<br>
<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
<input type="checkbox" class="toggle" name="ON_ware" value="1" <?=$ON_ware?'checked':''?> />
</div>

</td>
					  <td align="center" valign="middle"><a class="popovers" data-trigger="hover" data-placement="top"  data-content="会员需要填写昵称">昵称为主要</a><br>
<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
<input type="checkbox" class="toggle" name="ON_nickname" value="1" <?=$ON_nickname?'checked':''?> />
</div>
                      </td>
					  <td align="center" valign="middle">&nbsp;</td>
					  <td align="center" valign="middle">&nbsp;</td>
					  </tr>
                  
					
                </tbody>
              </table>
              
     <br>
         
<table class="table table-striped table-bordered table-hover" >
  <tbody>
    <tr class="odd gradeX">
      <td valign="top" align="right" width="200">操作提示音：</td>
      <td align="left"><label>
          <input name="ON_MusicYes" type="checkbox" value="1"  <?=$ON_MusicYes?'checked':''?>/>
        </label>
        成功提示 &nbsp;&nbsp;
        <label>
          <input name="ON_MusicNo" type="checkbox" value="1"  <?=$ON_MusicNo?'checked':''?>/>
        </label>
        错误提示</td>
    </tr>
  </tbody>
</table>

<span class="help-block" style="padding:10px;">
&raquo; 其他功能开关在对应分类中设置，如果没见有则在<a href="/xingao/member_group/list.php" target="_blank">会员组</a>中设置。
</span>

			 
            </div>
          </div>
          <!---->             
              
              
              
              
              
              
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>基本设置 <font class="red">修改此页后必须先提交保存，再做其他页的修改，否则某些数据不能同步</font></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
 

                     <tr class="odd gradeX">
                        <td align="right"  width="200">主币种：</td>
                        <td align="left">
<select name="XAMcurrency" class="form-control input-small select2me" data-placeholder="请选择">
<?=openCurrency($XAMcurrency,1)?>
</select>
<input name="XAmc" type="text" value="<?=cadd($XAmc)?>" size="6"  class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="币种别名，如:点数,元宝,元,日元,美元 等可随便设置" />

<span class="help-block">
网站内部结算所用的币种；正式运营后不可再修改，可以修改别名；

</span>

                         
                         </td>
                      </tr>
                                           
                      
                      <tr class="odd gradeX">
                        <td align="right"  width="200">次币种：</td>
                        <td align="left">
<select name="XAScurrency" class="form-control input-small select2me" data-placeholder="请选择">
<?=openCurrency($XAScurrency,1)?>
</select>
<input name="XAsc" type="text" value="<?=cadd($XAsc)?>" size="6"  class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="币种别名，如:元,日元,美元 等可随便设置" />
 
<span class="help-block">商品外币 (如：包裹预报时所填写的包裹价值币种)；正式运营后不可再修改，可以修改别名；</span>

                          
						   </td>
                      </tr>
                      
<tr class="odd gradeX">
<td align="right"  width="200">网站支持币种：</td>
<td align="left">
    <select multiple="multiple" class="multi-select" id="my_multi_select3_1" name="openCurrency[]">
    <?=openCurrency($openCurrency,1)?>
    </select>
    
    <span class="help-block">
    &raquo; 必须选择 (左边表示未选择,右边表示已选择)<br>
    &raquo; 充值接口开通哪些币种，这里也要开通，否则无法充值兑换<br>
    </span>
</td>
</tr>  
 
                       <tr class="odd gradeX">
                        <td align="right"  width="200">前台汇率展示 原币：</td>
                        <td align="left">
<select name="JSCurrency" class="form-control input-small select2me" data-placeholder="请选择">
<option></option>
<?=openCurrency($JSCurrency,1)?>
</select>
<span class="help-block">只在前台顶部展示用，无实际功能作用。不选则不展示</span>

                          
						   </td>
                      </tr>

                                       
<tr class="odd gradeX">
<td  width="200" align="right">自动获取汇率：</td>
<td align="left">
    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
    <input type="checkbox" class="toggle" name="ON_exchange" value="1" <?=$ON_exchange?'checked':''?> />
    </div>
    <span class="help-block">开启时，每3小时自动获取更新一次。关闭时需要<a href="/xingao/exchange/list.php" target="_blank">手工设置汇率</a>。</span>
</td>
</tr>
                      
                                           
                     <tr class="odd gradeX">
                        <td colspan="2" align="right"></td>
                      </tr>
                      <tr class="odd gradeX">
                        <td align="right"  width="200">重量设置：</td>
                        <td align="left"> 1
                          <input name="XAwt" type="text" value="<?=cadd($XAwt)?>" size="3"  class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="网站使用的重量单位" />
                       
                          ＝
                          <input name="XAwtkg" type="text" value="<?=cadd($XAwtkg)?>" size="10"  class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="换算比例(当左边不是用KG时比如用LB，则在导出和打印时自动转为KG单位)" />
                          KG </td>
                      </tr>
                      
                      <tr class="odd gradeX">
                        <td align="right"  width="200">尺寸设置：</td>
                        <td align="left"> 
                          1
                          <input name="XAsz" type="text" value="<?=cadd($XAsz)?>" size="3"  class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="网站使用的尺寸单位" />
                          ＝
                          <input name="XAszcm" type="text" value="<?=cadd($XAszcm)?>" size="10"  class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="换算比例(当左边不是用CM时，则在某些导出和打印时自动转为CM单位)" />CM 
                         </td>
                      </tr>
                      
                        <tr class="odd gradeX">
                          <td  width="200" rowspan="3" align="right" valign="top">转账充值：</td>
                          <td align="left">
<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
<input type="checkbox" class="toggle" name="ON_bankAccount" value="1" <?=$ON_bankAccount?'checked':''?> />
</div>
                          </td>
                          
                      </tr>
                      <tr class="odd gradeX">
                        <td align="left">
                            防止填写错误或转账出错时，在后台首次处理<input name="bankAccountLock" type="text" class="form-control input-xsmall" value="<?=spr($bankAccountLock)?>" size="3"/>天后还可再修改
                        </td>
                      </tr>
                      <tr class="odd gradeX">
                        <td align="left">
<?php 
//语言字段处理++
$field='bankAccount';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <textarea name="<?=$field.$language?>" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?> 收款账号" rows="10" style=" width:45%; float:left;"><?=cadd($val)?></textarea>
		<?php 
	}
}
?>
<span class="help-block" style="clear:both">会员转账充值时的收款账号 (支持HTML代码)</span>
                        </td>
                      </tr>
                      
                      
                    </tbody>
                  </table>
                </div>
              </div>
              
              
              
              <!---->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>包裹物品和运单物品 设置</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
 <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
  <tbody>
    <tr class="odd gradeX">
      <td valign="top" align="right" width="200">显示内容：</td>
      <td align="left">
        <input name="ON_wupin_type" type="checkbox" value="1"  <?=$ON_wupin_type?'checked':''?>/>
        类别 &nbsp;&nbsp;
        
        <input type="hidden" name="ON_wupin_name" value="1"><!--程序判断物品行数时,用此字段-->
        品名(必显) &nbsp;&nbsp;
        
        <input name="ON_wupin_brand" type="checkbox" value="1"  <?=$ON_wupin_brand?'checked':''?>/>
        品牌/厂商 &nbsp;&nbsp;
        <input name="ON_wupin_spec" type="checkbox" value="1"  <?=$ON_wupin_spec?'checked':''?>/>
        规格 &nbsp;&nbsp;
        <input name="ON_wupin_weight" type="checkbox" value="1"  <?=$ON_wupin_weight?'checked':''?>/>
        重量 &nbsp;&nbsp;
        <input name="ON_wupin_number" type="checkbox" value="1"  <?=$ON_wupin_number?'checked':''?>/>
        数量 &nbsp;&nbsp;
        <input name="ON_wupin_unit" type="checkbox" value="1"  <?=$ON_wupin_unit?'checked':''?>/>
        单位 &nbsp;&nbsp;
        <input name="ON_wupin_price" type="checkbox" value="1"  <?=$ON_wupin_price?'checked':''?>/>
        单价 &nbsp;&nbsp;
        <input name="ON_wupin_total" type="checkbox" value="1"  <?=$ON_wupin_total?'checked':''?>/>
        总价 &nbsp;&nbsp; </td>
    </tr>
    <tr class="odd gradeX">
      <td valign="top" align="right" width="200">必填内容：</td>
      <td align="left">
      <input name="wupin_req_type" type="checkbox" value="1"  <?=$wupin_req_type?'checked':''?>/>
       类别 &nbsp;&nbsp;
        <input name="wupin_req_name" type="checkbox" value="1"  <?=$wupin_req_name?'checked':''?>/>
        品名 &nbsp;&nbsp;
        <input name="wupin_req_brand" type="checkbox" value="1"  <?=$wupin_req_brand?'checked':''?>/>
        品牌/厂商 &nbsp;&nbsp;
        <input name="wupin_req_spec" type="checkbox" value="1"  <?=$wupin_req_spec?'checked':''?>/>
        规格 &nbsp;&nbsp;
        <input name="wupin_req_weight" type="checkbox" value="1"  <?=$wupin_req_weight?'checked':''?>/>
        重量 &nbsp;&nbsp;
        <input name="wupin_req_number" type="checkbox" value="1"  <?=$wupin_req_number?'checked':''?>/>
        数量 &nbsp;&nbsp;
        <input name="wupin_req_unit" type="checkbox" value="1"  <?=$wupin_req_unit?'checked':''?>/>
        单位 &nbsp;&nbsp;
        <input name="wupin_req_price" type="checkbox" value="1"  <?=$wupin_req_price?'checked':''?>/>
        单价 &nbsp;&nbsp;
        <input name="wupin_req_total" type="checkbox" value="1"  <?=$wupin_req_total?'checked':''?>/>
        总价 &nbsp;&nbsp; 
        <span class="help-block">对物品系统和运单系统里的通用渠道有效 (对清关公司的固有资料无效)</span>
        </td>
    </tr>
    
    
  </tbody>
</table>
                 
                </div>
              </div>
			  
          <!---->
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab_3">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>积分设置</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <div class="daycat" align="left">
                <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                  <tbody>
                    
                    <?php if($off_shaidan==1){?>
                    <tr class="odd gradeX">
                      <td align="right"  width="200">晒单送分：</td>
                      <td align="left"><input name="integral_shaidan" type="text" class="form-control input-small" value="<?=cadd($integral_shaidan)?>" size="5" onkeyup="value=value.replace(/[^\d.]/g,'')"/>
                        分</td>
                    </tr>
                    <?php }?>
                    
                    <?php if($ON_daigou){?>
                    <tr class="odd gradeX">
                      <td align="right"  width="200">代购送分：</td>
                      <td align="left"><input name="integral_daigou" type="text"  class="form-control input-small"value="<?=cadd($integral_daigou)?>" size="5" onkeyup="value=value.replace(/[^\d.]/g,'')"/>分
                        </td>
                    </tr>
                    <?php }?>
                    
                    <tr class="odd gradeX">
                      <td align="right"  width="200">运单消费送分比例：</td>
                      <td align="left"> 1<?=$XAmc?>:
                        <input name="integral_yundan" type="text" class="form-control input-xsmall" value="<?=cadd($integral_yundan)?>" size="5" onkeyup="value=value.replace(/[^\d.]/g,'')"/>
                        分 <span class="help-block">不送分则写0</span></td>
                    </tr>
                    <tr class="odd gradeX">
                      <td align="right"  width="200">商城消费送分比例：</td>
                      <td align="left"> 1<?=$XAmc?>:
                        <input name="integral_mall" type="text" class="form-control input-xsmall" value="<?=cadd($integral_mall)?>" size="5" onkeyup="value=value.replace(/[^\d.]/g,'')"/>
                        分</td>
                    </tr>
                    <tr class="odd gradeX">
                      <td align="right"  width="200">抵消比例：</td>
                      <td align="left"><input name="integral_bili" type="text" class="form-control input-small" value="<?=cadd($integral_bili)?>" size="10" onkeyup="value=value.replace(/[^\d.]/g,'')"/>
                        分可以抵消1<?=$XAmc?></td>
                    </tr>
                    <tr class="odd gradeX">
                      <td align="right"  width="200">抵消条件：</td>
                      <td align="left"><input name="integral_5" type="text" class="form-control input-small" value="<?=cadd($integral_5)?>" size="10" onkeyup="value=value.replace(/[^\d.]/g,'')"/>
                        <span class="help-block">消费额大于多少才可用积分抵消</span></td>
                    </tr>
                    <tr class="odd gradeX">
                      <td align="right"  width="200">抵消限制：</td>
                      <td align="left">每
                        <input name="integral_1" type="text" class="form-control input-small" value="<?=cadd($integral_1)?>" size="10" onkeyup="value=value.replace(/[^\d.]/g,'')"/>
                        消费额可使用
                        <input name="integral_2" type="text" class="form-control input-small" value="<?=cadd($integral_2)?>" size="10" onkeyup="value=value.replace(/[^\d.]/g,'')"/>
                        积分元(积分兑换的元)，一次最多可使用
                        <input name="integral_3" type="text" class="form-control input-small" value="<?=cadd($integral_3)?>" size="10" onkeyup="value=value.replace(/[^\d.]/g,'')"/>
                        积分元</td>
                    </tr>
                    <tr class="odd gradeX">
                      <td align="right"  width="200">消费积分后还送分：</td>
                      <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                          <input type="checkbox" class="toggle" name="integral_4" value="1"  <?=$integral_4==1?'checked':''?> />
                        </div>
                        <span class="help-block">用积分抵消的部分不算</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
		<!---->
        </div>
        <div class="tab-pane" id="tab_4">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>包裹功能开关</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  <tr class="odd gradeX">
                    <td rowspan="4" align="center" valign="middle"><a class="popovers" data-trigger="hover" data-placement="top" data-content="如果不开通,整个网站的包裹系统将关闭不可用且不显示" ><strong>包裹系统</strong></a><br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_baoguo" value="1" <?=$off_baoguo?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle"><a class="popovers" data-trigger="hover" data-placement="top" data-content="只合箱但不发货,还保存在仓库里,等待会员下一步操作" >合箱</a><br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_hx" value="1" <?=$off_hx?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle"><a class="popovers" data-trigger="hover" data-placement="top" data-content="只分箱但不发货,还保存在仓库里,等待会员下一步操作" >分箱</a><br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_fx" value="1" <?=$off_fx?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle">包裹预报<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_baoguo_yubao" value="1" <?=$off_baoguo_yubao?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle">验货<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_baoguo_op_02" value="1" <?=$off_baoguo_op_02?'checked':''?> />
                      </div></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="center" valign="middle">转移仓库<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_baoguo_op_04" value="1" <?=$off_baoguo_op_04?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle">转移会员<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_tra_user" value="1" <?=$off_tra_user?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle">拍照<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_baoguo_op_06" value="1" <?=$off_baoguo_op_06?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle">减重<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_baoguo_op_07" value="1" <?=$off_baoguo_op_07?'checked':''?> />
                      </div></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="center" valign="middle">退货<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_baoguo_th" value="1" <?=$off_baoguo_th?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle">清点<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_baoguo_op_09" value="1" <?=$off_baoguo_op_09?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle">复称<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_baoguo_op_10" value="1" <?=$off_baoguo_op_10?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle">填空隙<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_baoguo_op_11" value="1" <?=$off_baoguo_op_11?'checked':''?> />
                      </div></td>
                  </tr>
                  <tr class="odd gradeX">
                    
                    <td align="center" valign="middle">
					<a class="popovers" data-trigger="hover" data-placement="top" data-content="待下单或未下完运单的包裹会员可以修改物品" >修改物品</a><br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_edit_wp" value="1" <?=$off_edit_wp?'checked':''?> />
                      </div>
					</td>
                    <td align="center" valign="middle"><a class="popovers" data-trigger="hover" data-placement="top" data-content="包裹未在仓库时也可先下运单 (运单处于待入库状态)" >未入库也可下运单</a><br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_baoguo_zxyd" value="1" <?=$off_baoguo_zxyd?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle">

                        <a class="popovers" data-trigger="hover" data-placement="top" data-content="包裹入库后会员就不用再点击“确认包裹”<br>确认包裹是指:会员确认该包裹完整无误,确认后就可以对其操作" >包裹自动确认</a><br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="baoguo_qr" value="1" <?=$baoguo_qr?'checked':''?> />
                      </div>

                    </td>
                    <td align="center" valign="middle">

                        <a class="popovers" data-trigger="hover" data-placement="top" data-content="入库时需要上架才才能入库" >上架流程</a><br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="bg_shelves" value="1" <?=$bg_shelves?'checked':''?> />
                      </div>

                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
		            <!---->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>搜索包裹单号 (查询/扫描/导入/入库)</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 

                <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                  <tbody>
										
                    <tr class="odd gradeX">
                    <td valign="top" align="right" width="200">包裹入库：</td>
                      <td align="left">
                   
                          <input type="checkbox" name="baoguo_req_weight" value="1" <?=$baoguo_req_weight?'checked':''?>  />入库时必填重量<br>
                          <input type="checkbox" name="baoguo_req_whPlace" value="1" <?=$baoguo_req_whPlace?'checked':''?>  />入库时必填仓位
                        
                       </td>
                    </tr>
										
                    <tr class="odd gradeX">
                    <td valign="top" align="right" width="200">包裹上架：</td>
                      <td align="left">
                          
                          <input type="checkbox" name="shelves_req_whPlace" value="1" <?=$shelves_req_whPlace?'checked':''?>  />上架时必填仓位
                        
                       </td>
                    </tr>
                    
										
                    <tr class="odd gradeX">
                    <td valign="top" align="right" width="200">包裹单号搜索：</td>
                      <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                          <input type="checkbox" class="toggle" name="baoguo_smws" value="1"  <?=$baoguo_smws==1?'checked':''?> />
                        </div>
                        <span class="help-block">当扫描不到包裹时反向再次搜索</span><span class="help-block">例如：会员填写的预报单号123456789，而快递公司寄过来的包裹单号是123456789999999，这时就找不到该包裹，因此可以用反向搜索就可找到。<br />
                        </span></td>
                    </tr>
                    
                    
                    
                    
                    
                    <tr class="odd gradeX">
                    <td width="200" rowspan="3" align="right" valign="top">分割单号搜索：</td>
                      <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                          <input type="checkbox" class="toggle" name="baoguo_fg" value="1"  <?=$baoguo_fg==1?'checked':''?> />
                        </div>
                        
                <span class="help-block">当扫描不到包裹时分割扫描单号来搜索<br />
                例如：会员填写的预报单号123456789，而快递公司寄过来的包裹单号是0001234567891111，这时就找不到该包裹，因此可以用分割搜索就可找到。
                </span> 
                
                      </td>
                    </tr>
                    <tr class="odd gradeX">
                      <td align="left">
                分割字数：
                <input name="baoguo_fgzs" type="text" class="form-control input-xsmall" value="<?=$baoguo_fgzs?>" size="5" />位
                <span class="help-block">以实际应用为准，不要太短 (越长越准确,太短时可能会更新到错误的包裹)</span> 
                      </td>
                    </tr>
                    
                    
                    <tr class="odd gradeX">
                      <td align="left">
分割方式:
<div class="radio-list">
   <label>
   <input type="radio" name="baoguo_fg_type" value="1" <?=$baoguo_fg_type==1?'checked':''?>> 全部逐字分割 
   <font class="gray_prompt2">如:系统单号:1234567890 可匹配单号:XXX123456XXXXXX</font>
   </label>
   
   <label>
   <input type="radio" name="baoguo_fg_type" value="2" <?=$baoguo_fg_type==2?'checked':''?>> 分割开头和结尾
   <font class="gray_prompt2">如:系统单号:1234567890 可匹配单号:123456XXXXXX567890</font>
   </label>
   
   <label>
   <input type="radio" name="baoguo_fg_type" value="3" <?=$baoguo_fg_type==3?'checked':''?>> 仅分割开头 
   <font class="gray_prompt2">如:系统单号:1234567890 可匹配单号:123456XXXXXX</font>
   </label>
   
   <label>
   <input type="radio" name="baoguo_fg_type" value="4" <?=$baoguo_fg_type==4?'checked':''?>> 仅分割结尾
   <font class="gray_prompt2">如:系统单号:1234567890 可匹配单号:XXXXXX567890</font>
   </label>

</div>
                      </td>
                    </tr>
                    
                    
					
                  </tbody>
                </table>
              </div>
          </div>

          <!---->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>其他配置</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <div class="daycat" align="left">
                <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                  <tbody>
                  
                    
                    <tr class="odd gradeX"><td valign="top" align="right" width="200">自动仓储时通知：</td>
                    <td align="left">
					<input type="checkbox" name="bg_ware_msg" value="1" <?=$bg_ware_msg?'checked':''?> /> 站内信
					</td>
                    </tr>
			  
					
					                  
                    <tr class="odd gradeX">
					<td valign="top" align="right" width="200">入库通知：</td>
                      <td align="left"><input name="baoguo_ruku_msg" type="checkbox" value="1"  <?=$baoguo_ruku_msg?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="baoguo_ruku_mail" type="checkbox" value="1"  <?=$baoguo_ruku_mail?'checked':''?>/>
                        邮件  
                        
                        &nbsp;&nbsp;
                        <input name="baoguo_ruku_sms" type="checkbox" value="1"  <?=$baoguo_ruku_sms?'checked':''?>/>
                        短信
                        
                        &nbsp;&nbsp;
                        <input name="baoguo_ruku_wx" type="checkbox" value="1"  <?=$baoguo_ruku_wx?'checked':''?>/>
                        微信
                        
                        </font></td>
                    </tr>
<tr class="odd gradeX"><td valign="top" align="right" width="200">合箱通知：</td>
                      <td align="left"><input name="baoguo_hx_msg" type="checkbox" value="1"  <?=$baoguo_hx_msg?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="baoguo_hx_mail" type="checkbox" value="1"  <?=$baoguo_hx_mail?'checked':''?>/>
                        邮件  
                        
                        &nbsp;&nbsp;
                        <input name="baoguo_hx_sms" type="checkbox" value="1"  <?=$baoguo_hx_sms?'checked':''?>/>
                        短信
                        
                        &nbsp;&nbsp;
                        <input name="baoguo_hx_wx" type="checkbox" value="1"  <?=$baoguo_hx_wx?'checked':''?>/>
                        微信
                        
                        </font></td>
                    </tr>
<tr class="odd gradeX"><td valign="top" align="right" width="200">分箱通知：</td>
                      <td align="left"><input name="baoguo_fx_msg" type="checkbox" value="1"  <?=$baoguo_fx_msg?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="baoguo_fx_mail" type="checkbox" value="1"  <?=$baoguo_fx_mail?'checked':''?>/>
                        邮件  
                        
                        &nbsp;&nbsp;
                        <input name="baoguo_fx_sms" type="checkbox" value="1"  <?=$baoguo_fx_sms?'checked':''?>/>
                        短信
                        
                        &nbsp;&nbsp;
                        <input name="baoguo_fx_wx" type="checkbox" value="1"  <?=$baoguo_fx_wx?'checked':''?>/>
                        微信
                        
                        </font></td>
                    </tr>										


                    <tr class="odd gradeX"><td valign="top" align="right" width="200">购物网站：</td>
                      <td align="left">
<textarea name="wangzhan" class="form-control input-medium" cols="30" rows="5"><?=cadd($wangzhan)?></textarea>
                        <span class="help-block"> 添加包裹是显示的购物网站<br />
                        <?=$text_ts?>
                        &raquo; 每行一个名称，不要有特殊符号<br />
                        </span></td>
                    </tr>
                    <tr class="odd gradeX"><td valign="top" align="right" width="200">快递公司：</td>
                      <td align="left">
<textarea name="baoguo_kuaidi"  class="form-control input-medium" cols="30" rows="5"><?=cadd($baoguo_kuaidi)?></textarea>
                        <span class="help-block"> 包裹预报显示的快递公司<br />
                        &raquo; 每行一个名称，不要有特殊符号<br />
                        </span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab_5">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>运单配置</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <div class="daycat" align="left">
                <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                  <tbody>
                    
                    <tr class="odd gradeX">
                      <td align="right"  width="200">支持寄往国家：</td>
                      <td align="left">
<select multiple="multiple" class="multi-select" id="my_multi_select3_0" name="openCountry[]">
<?=Country($openCountry,1)?>
</select>
<span class="help-block">留空表示全部 (左边表示未选择,右边表示已选择)</span>
						</td>
                    </tr>
					
                    <tr class="odd gradeX">
                      <td align="right"  width="200">运单号格式：</td>
                      <td align="left">
<input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="前缀"  name="ydh_tpre"   value="<?=cadd($ydh_tpre)?>">

<select name="ydh_typ" class="form-control input-xlarge select2me tooltips" data-container="body" data-placement="top" data-original-title="生成格式">
<?php OrderNo_typ($ydh_typ);?>
</select>

<input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="后缀"  name="ydh_suffix"   value="<?=cadd($ydh_suffix)?>">


                   
						</td>
                    </tr>

                     
                    <tr class="odd gradeX">
                    <td width="200"  align="right" valign="top">预先支付才处理：</td>
                      <td align="left">
                      
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="yundan_prepay" value="1" <?=$ON_yundan_prepay?'checked':''?> />
                      </div>
                       <span class="help-block">
                        &raquo; 会员下运单后,先按预估重量支付费用后,仓库才进行处理<br>
                       </span>
                       </td>
                    </tr>
                     

                    <tr class="odd gradeX">
                      <td align="right"  width="200">计费通知：</td>
                      <td align="left"><input name="yundan_fee_msg" type="checkbox" value="1"  <?=$yundan_fee_msg?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="yundan_fee_mail" type="checkbox" value="1"  <?=$yundan_fee_mail?'checked':''?>/>
                        邮件  
                        
                        &nbsp;&nbsp;
                        <input name="yundan_fee_sms" type="checkbox" value="1"  <?=$yundan_fee_sms?'checked':''?>/>
                        短信
                        
                        &nbsp;&nbsp;
                        <input name="yundan_fee_wx" type="checkbox" value="1"  <?=$yundan_fee_wx?'checked':''?>/>
                        微信
                        
						<br>
<input name="yundan_fee_settlement" type="checkbox" value="1"  <?=$yundan_fee_settlement?'checked':''?>/>
                        对月结会员也按以上设置发通知<font class="gray2">(销账通知在右边的会员分类里设置)</font>
						</td>
                    </tr>
					
                    <tr class="odd gradeX">
                      <td align="right"  width="200">后台扣费成功通知：</td>
                      <td align="left"><input name="yundan_paysucc_msg" type="checkbox" value="1"  <?=$yundan_paysucc_msg?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="yundan_paysucc_mail" type="checkbox" value="1"  <?=$yundan_paysucc_mail?'checked':''?>/>
                        邮件  
                        
                        &nbsp;&nbsp;
                        <input name="yundan_paysucc_sms" type="checkbox" value="1"  <?=$yundan_paysucc_sms?'checked':''?>/>
                        短信
                        
                        &nbsp;&nbsp;
                        <input name="yundan_paysucc_wx" type="checkbox" value="1"  <?=$yundan_paysucc_wx?'checked':''?>/>
                        微信
                        
						</td>
                    </tr>
					
                    <tr class="odd gradeX">
                      <td align="right"  width="200">后台扣费失败通知：</td>
                      <td align="left"><input name="yundan_payfail_msg" type="checkbox" value="1"  <?=$yundan_payfail_msg?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="yundan_payfail_mail" type="checkbox" value="1"  <?=$yundan_payfail_mail?'checked':''?>/>
                        邮件  
                        
                        &nbsp;&nbsp;
                        <input name="yundan_payfail_sms" type="checkbox" value="1"  <?=$yundan_payfail_sms?'checked':''?>/>
                        短信
						</td>
                    </tr>

                    
                    
                    <tr class="odd gradeX"><td valign="top" align="right" width="200">自动删除已完成的运单：</td>
                      <td align="left">
                      <input class="tooltips" data-container="body" data-placement="top" data-original-title="如果开启“长期保存记录”则此设置无效" name="yundan_del_time" type="text" value="<?=cadd($yundan_del_time)?>" size="5" />月之前
                       <span class="help-block"> 删除无用运单可优化数据库提高网站性能。写0或留空则不自动删除；
(注意:删除时会同时删除相关包裹和状态记录)</span>
                       </td>
                    </tr>
                    
                    
                    <tr class="odd gradeX"><td valign="top" align="right" width="200">代替证件：</td>
                      <td align="left">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="ON_cardInstead" value="1" <?=$ON_cardInstead?'checked':''?> />
                      </div>
                      <br><br>
                      <input class="tooltips" data-container="body" data-placement="top" data-original-title="用X月之前运单的证件来代替 (按签收的时间算，支持输入0.X表示不到一个月)" name="cardInstead_time" type="text" value="<?=cadd($cardInstead_time)?>" size="5" />月之前
                       <span class="help-block"> 
                       &raquo; 对没有证件的运单，用其他会员的旧运单的证件来代替<br>
                       &raquo; 此功能相当盗用B会员的证件来给A会员发货，开通此功能前请考虑法律责任问题<br>
                       &raquo; 开通此功能时，要关闭以上的【自动删除已完成的运单】功能<br>
                       </span>
                       </td>
                    </tr>
                    <tr class="odd gradeX">
                      <td colspan="2" align="right" valign="top">&nbsp;</td>
                    </tr>
                     
                     
                    <tr class="odd gradeX">
                    <td width="200"  align="right" valign="top">调用百度查询：</td>
                      <td align="left">
                      
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="ON_baiduSearch" value="1" <?=$ON_baiduSearch?'checked':''?> />
                      </div>
                       <span class="help-block">
                        &raquo; 前台查询不到快递公司的物流信息时，是否用百度查询<br>
                       </span>
                       </td>
                    </tr>
                     
                     
                    <tr class="odd gradeX">
                    
                    <td width="200" rowspan="2" align="right" valign="top">查询子站运单：</td>
                      <td align="left">
                      
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="ON_SonWebsite_main" value="1" <?=$ON_SonWebsite_main?'checked':''?> />
                      </div>
                       <span class="help-block">
                        &raquo; 如果此站为主站，要在此站直接查询到子站运单状态，则开通此功能<br>
                       </span>
                       </td>
                    </tr>
                    <tr class="odd gradeX">
                      <td align="left">
                        <textarea  class="form-control input-large" rows="5" name="SonWebsiteList"><?=cadd($SonWebsiteList)?></textarea>
                        <span class="help-block">
                        &raquo;  一行一个子站,不分大小写<br>
                        &raquo;  子站单号格式<strong>前缀或后缀</strong>的区别，多个区别时用(,)逗号分开，不能有相同<br>
                        &raquo;  格式前缀或后缀=网址(不加http://)，如：<br>
                                  &nbsp;&nbsp; xa1,us1=www.zy1.com<br>
                                  &nbsp;&nbsp; xa2,us2=www.zy2.com<br>
                                                        
                        </span>
                     
                      </td>
                    </tr>
                    
                    
                    
                    
                    
                    
                    
                     
                    <tr class="odd gradeX"><td valign="top" align="right" width="200">授权主站查询运单：</td>
                      <td align="left">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="ON_SonWebsite" value="1" <?=$ON_SonWebsite?'checked':''?> />
                      </div>
                      
                       <span class="help-block">
                        &raquo; 如果此站为子站，要在主站直接查询到运单状态，则开通此功能<br>
                        &raquo; 运单号的 <strong>前缀和后缀</strong> 不能与<strong>主站和其他子站</strong>完全相同，至少要有一个区别<br>
                       </span>
                        
                       </td>
                    </tr>
                     
                    
                    
                    
                   
                  </tbody>
                </table>
              </div>
            </div>
          </div>






<!---->   
            <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>收件人/发件人</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  <tr class="odd gradeX">
                      <td   valign="top" align="right" width="200">收件人-上传身份证：</td>
                      <td align="left">
                   <select name="off_shenfenzheng">
                     <option value="0" <?=!$off_shenfenzheng?'selected="selected"':''?>>不用</option>
                     <option value="1" <?=$off_shenfenzheng==1?'selected="selected"':''?>>非必须</option>
                     <option value="2" <?=$off_shenfenzheng==2?'selected="selected"':''?>>必须</option>
                   </select>
                   <span class="help-block">
                   具体还需要在仓库管理的渠道里，设置哪些渠道需要上传
                   </span>
                   </td>
                 </tr>
                 
                   <tr class="odd gradeX">
                    <td align="right"  width="200">前台上传证件：</td>
                    <td align="left">
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                    <input type="checkbox" class="toggle" name="off_upload_cert" value="1" <?=$off_upload_cert?'checked':''?> />
                   </div>
                   <span class="help-block">非会员（不用登录会员中心）也能上传证件</span>
                      </td>
                  </tr>
                   
                  <tr class="odd gradeX">
                    <td  width="200" rowspan="2" align="right">发件人-功能：</td>
                    <td align="left">
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                    <input type="checkbox" class="toggle" name="off_fajian" value="1" <?=$off_fajian?'checked':''?> />
                   </div>
		<span class="help-block">下单时需要填写发件人 ；不开通时，下面设置无效</span>
                      </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="left">
        必填内容：
       <input name="f_name_req" type="checkbox" value="1"  <?=$f_name_req?'checked':''?>/>
        姓名 &nbsp;&nbsp;
        <input name="f_mobile_code_req" type="checkbox" value="1"  <?=$f_mobile_code_req?'checked':''?>/>
        手机地区 &nbsp;&nbsp;
        <input name="f_mobile_req" type="checkbox" value="1"  <?=$f_mobile_req?'checked':''?>/>
        手机号码 &nbsp;&nbsp;
        <input name="f_tel_req" type="checkbox" value="1"  <?=$f_tel_req?'checked':''?>/>
        固话 &nbsp;&nbsp;
        <input name="f_zip_req" type="checkbox" value="1"  <?=$f_zip_req?'checked':''?>/>
        邮编 &nbsp;&nbsp;
        <input name="f_add_shengfen_req" type="checkbox" value="1"  <?=$f_add_shengfen_req?'checked':''?>/>
        省份 &nbsp;&nbsp;
        <input name="f_add_chengshi_req" type="checkbox" value="1"  <?=$f_add_chengshi_req?'checked':''?>/>
        城市 &nbsp;&nbsp;
        <input name="f_add_quzhen_req" type="checkbox" value="1"  <?=$f_add_quzhen_req?'checked':''?>/>
        区镇 &nbsp;&nbsp;
        <input name="f_add_dizhi_req" type="checkbox" value="1"  <?=$f_add_dizhi_req?'checked':''?>/>
        具体地址 &nbsp;&nbsp;
                    </td>
                  </tr>
                  
                  
                  
                  
                </tbody>
              </table>
            </div>
          </div>

<!---->       
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>运单状态自动更新配置</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">自动更新功能：</td>
                    <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_statusauto" value="1"  <?=$off_statusauto?'checked':''?> />
                      </div>
                      <span class="help-block"> 在“物流状态配置”可设置每个状态的更新时间<br />
                     (
					 如果您物流不支持自动更新，不要开通
					  )
                      </span></td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">新运单默认开自动更新：</td>
                    <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="yd_statusauto" value="1"  <?=$yd_statusauto?'checked':''?> />
                      </div>
                      <span class="help-block"> 上面开通后，这个设置开通才有效</span></td>
                  </tr>

                  
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">调用API数据更新：</td>
                    <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="status_api_ok" value="1"  <?=$status_api_ok==1?'checked':''?> />
                      </div>
                       <span class="help-block">调用快递公司的物流情况，如果是已签收就更新为“完成”状态  (如果同一批运单数量太多，不建议开通，因为同一时间大量调用数据可能会被屏蔽接口)</span>
                       </td>
                  </tr>
                  
                </tbody>
              </table>
            </div>
          </div>
 
          <!---->
          
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>导出/打印 本公司联系方式配置</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">发件-公司名/姓名：</td>
                    <td align="left">
<?php 
//语言字段处理++
$field='sendName';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <input name="<?=$field.$language?>" type="text" class="form-control input-medium tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" value="<?=cadd($val)?>" style="float:left;">
		<?php 
	}
}
?>
                  </td>
                  </tr>
                    <tr class="odd gradeX">
                    <td align="right"  width="200">发件-手机：</td>
                     <td align="left">
<?php 
//语言字段处理++
$field='sendMobile';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <input name="<?=$field.$language?>" type="text" class="form-control input-medium tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" value="<?=cadd($val)?>" style="float:left;">
		<?php 
	}
}
?>
                  </td>
                  </tr>                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">发件-电话：</td>
                     <td align="left">
<?php 
//语言字段处理++
$field='sendTel';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <input name="<?=$field.$language?>" type="text" class="form-control input-medium tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" value="<?=cadd($val)?>" style="float:left;">
		<?php 
	}
}
?>
                  </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">发件-传真：</td>
                    <td align="left">
<?php 
//语言字段处理++
$field='sendFax';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <input name="<?=$field.$language?>" type="text" class="form-control input-medium tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" value="<?=cadd($val)?>" style="float:left;">
		<?php 
	}
}
?>
                  </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">发件-邮编：</td>
                    <td align="left">
<?php 
//语言字段处理++
$field='sendZip';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <input name="<?=$field.$language?>" type="text" class="form-control input-medium tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" value="<?=cadd($val)?>" style="float:left;">
		<?php 
	}
}
?>
                  </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">发件-地址：</td>
                    <td align="left">
<?php 
//语言字段处理++
$field='sendAdd';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <input name="<?=$field.$language?>" type="text" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" value="<?=cadd($val)?>"><br>
		<?php 
	}
}
?>
                  </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <!---->
          
          
          
         <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>运单状态 <font class="red">(加粗的名称是系统绑定状态,不可更改原意！)</font></div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
<?php 
//输出多语种运单状态名称输入框
function config_status_name($field)
{
	
	
	//语言字段处理++
	$i=0;
	if(!$LGList){$LGList=languageType('',3);}
	if($LGList)
	{
		foreach($LGList as $arrkey=>$language)
		{
			$joint=$field.$language; global $$joint; $val=$$joint;
			$i++;
			?>
            <?php if($i==1){?><font style="float:left; line-height:45px;">名称:</font><?php }?>
			<input type="text" class="form-control input-medium tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?> 名称"  name="<?=$field.$language?>" value="<?=cadd($val)?>" style=" float:left;">
			<?php 
		}
	}
}
?>
            
            
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <div class="daycat" align="left">
                <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                  <tbody>
                    <tr class="odd gradeX">
                      <td align="right"  width="200"><strong>以下通知：</strong></td>
                      <td align="left"> <strong>
							<div class="radio-list">
                                 <label class="radio-inline">
                                 <input type="radio" name="status_sms_lx" value="0" <?=$status_sms_lx==0?'checked':''?>> 发给会员
                                 </label>
								 
                                 <label class="radio-inline">
                                 <input type="radio" name="status_sms_lx" value="1"  <?=$status_sms_lx==1?'checked':''?>> 发给收件人
                                 </label>
								 
                                 <label class="radio-inline">
                                 <input type="radio" name="status_sms_lx"  value="2"  <?=$status_sms_lx==2?'checked':''?>> 发给发件人
                                 </label>  
                              </div>	</strong>				  </td>
                    </tr>
				<tr class="odd gradeX">
                      <td colspan="2" align="right"></td>
                      </tr>	

                   <tr class="odd gradeX">
                      <td align="right"  width="200"><strong>待入库：</strong></td>
                      <td align="left"><?=config_status_name('status_01')?></td>
                    </tr>
                      
                    <?php if($ON_yundan_prepay){?>
                    <tr class="odd gradeX">
                      <td align="right"  width="200"><strong>待预付：</strong></td>
                      <td align="left"><?=config_status_name('status_02')?></td>
                    </tr>
                    <?php }?>
                  
                    <tr class="odd gradeX">
                      <td align="right"  width="200"><strong>待审核：</strong></td>
                      <td align="left"><?=config_status_name('status_0')?></td>
                    </tr>
					
				<tr class="odd gradeX">
                      <td align="right"  width="200"><strong>无效运单：</strong></td>
                      <td align="left"><?=config_status_name('status_1')?><div class="clear"></div>
                        <font title="更新到这个状态时发通知给会员">通知:
                        <input name="status_msg1" type="checkbox" value="1"  <?=$status_msg1==1?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="status_mail1" type="checkbox" value="1"  <?=$status_mail1==1?'checked':''?>/>
                        邮件  
                        
                        &nbsp;&nbsp;
                        <input name="status_sms1" type="checkbox" value="1"  <?=$status_sms1==1?'checked':''?>/>
                        短信
                        
                        &nbsp;&nbsp;
                        <input name="status_wx1" type="checkbox" value="1"  <?=$status_wx1==1?'checked':''?>/>
                        微信
                        
                        </font></td>
                    </tr>
					
					<tr class="odd gradeX">
                      <td align="right"  width="200"><strong>已审核,待打包：</strong></td>
                      <td align="left"><?=config_status_name('status_2')?><div class="clear"></div>
                        <font title="更新到这个状态时发通知给会员">通知:
                        <input name="status_msg2" type="checkbox" value="1"  <?=$status_msg2==1?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="status_mail2" type="checkbox" value="1"  <?=$status_mail2==1?'checked':''?>/>
                        邮件  
                        &nbsp;&nbsp;
                        <input name="status_sms2" type="checkbox" value="1"  <?=$status_sms2==1?'checked':''?>/>
                        短信
                        
                        &nbsp;&nbsp;
                        <input name="status_wx2" type="checkbox" value="1"  <?=$status_wx2==1?'checked':''?>/>
                        微信
                        
                        </font></td>
                    </tr>
                    <tr class="odd gradeX">
                      <td align="right"  width="200"><strong>已打包,待支付：</strong></td>
                      <td align="left"><?=config_status_name('status_3')?><div class="clear"></div>
                        <font title="更新到这个状态时发通知给会员"> 通知:
                        <input name="status_msg3" type="checkbox" value="1"  <?=$status_msg3==1?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="status_mail3" type="checkbox" value="1"  <?=$status_mail3==1?'checked':''?>/>
                        邮件  
                        
                        &nbsp;&nbsp;
                        <input name="status_sms3" type="checkbox" value="1"  <?=$status_sms3==1?'checked':''?>/>
                        短信
                        
                        &nbsp;&nbsp;
                        <input name="status_wx3" type="checkbox" value="1"  <?=$status_wx3==1?'checked':''?>/>
                        微信
                        
                        </font></td>
                    </tr>
					
					<tr class="odd gradeX">
                      <td align="right"  width="200"><strong>已支付,待出库：</strong></td>
                      <td align="left"><?=config_status_name('status_4')?><div class="clear"></div>
                        <font title="更新到这个状态时发通知给会员">通知:
                        <input name="status_msg4" type="checkbox" value="1"  <?=$status_msg4==1?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="status_mail4" type="checkbox" value="1"  <?=$status_mail4==1?'checked':''?>/>
                        邮件  
                        
                        &nbsp;&nbsp;
                        <input name="status_sms4" type="checkbox" value="1"  <?=$status_sms4==1?'checked':''?>/>
                        短信
                        
                        &nbsp;&nbsp;
                        <input name="status_wx4" type="checkbox" value="1"  <?=$status_wx4==1?'checked':''?>/>
                        微信
                        
                        </font></td>
                    </tr>	
<?php
for ($i=5; $i<=30; $i++)
{
	$joint='status_on_'.$i; $status_on=$$joint;
	$joint='statustime_update'.$i; $statustime_update=$$joint;
	$joint='whether'.$i; $whether=$$joint;
	$joint='status_msg'.$i; $status_msg=$$joint;
	$joint='status_mail'.$i; $status_mail=$$joint;
	$joint='status_sms'.$i; $status_sms=$$joint;
	$joint='status_wx'.$i; $status_wx=$$joint;
	
	//特殊状态
	$i_ppt='';
	if($i==13)
	{
		$i_name='<strong>国际阶段快递公司</strong>';
		$i_ppt='<a class=" popovers" data-trigger="hover" data-placement="top"  data-content="国际运输阶段时用第三方并且已做状态查询的对接(可自动获了第三方公司的物流状态)，可启用该状态"> <i class="icon-info-sign"></i> </a>';
	}elseif($i==14){
		$i_name='<strong>待付关税</strong>';
	}elseif($i==15){
		$i_name='<strong>已付关税</strong>';
	}elseif($i==20){
		$i_name='<strong>派送快递</strong>';
		$i_ppt='<a class=" popovers" data-trigger="hover" data-placement="top"  data-content="直邮或派送阶段的状态名称"> <i class="icon-info-sign"></i> </a>';
	}elseif($i==30){
		$i_name='<strong>完成签收</strong>';
	}else{
		$i_name="状态{$i}";
	}
?>
    <tr class="odd gradeX" >
      <td align="right"  width="200"><?=$i_name?> <?=$i_ppt?>：</td>
      <td align="left">
      <?php if($i==30){?>
      	<!--强制启动该状态-->
      	<input type="hidden" name="status_on_<?=$i?>" value="1">
      <?php }else{?>
          <!--可选启动该状态-->
          <label> 
          <font title="启用后此状态才有效，否则不显示">启用:</font>
          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
            <input type="checkbox" class="toggle" name="status_on_<?=$i?>" value="1" <?=$status_on?'checked':''?> />
          </div>
          </label>
      <?php }?>
      
      <div class="clear"></div>
	  <?=config_status_name('status_'.$i)?>
      <div class="clear"></div>

        <font title="从上一个启用的状态自动更新到这个状态的时间">更新:
        <input name="statustime_update<?=$i?>" type="text"  value="<?=cadd($statustime_update)?>" size="3" />
        小时</font> <span class="gray2">(使用中国时间，留空或填0则不自动更新)</span><br />
        
        <font title="在周六和周日不计算更新时间">
        周未:<input name="whether<?=$i?>" type="checkbox" value="1"  <?=$whether?'checked':''?>/>不算时间</font><br />
        
        <font title="更新到这个状态时发通知给会员">
        通知:
        <input name="status_msg<?=$i?>" type="checkbox" value="1"  <?=$status_msg?'checked':''?>/>站内信 
        &nbsp;&nbsp;
        <input name="status_mail<?=$i?>" type="checkbox" value="1"  <?=$status_mail?'checked':''?>/>邮件 
        &nbsp;&nbsp;
        <input name="status_sms<?=$i?>" type="checkbox" value="1"  <?=$status_sms?'checked':''?>/>短信
        &nbsp;&nbsp;
        <input name="status_wx<?=$i?>" type="checkbox" value="1"  <?=$status_wx?'checked':''?>/>微信
        </font>
        
        </td>
    </tr>
<?php }?>                    
 
 
 
                    
                    </tbody>
                </table>
              </div>
            </div>
          </div>
          
          
<!---->   
            <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>附加服务</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
<table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
<!------------->
<tr>
  <td width="200" align="right">按包裹合箱数量收费服务1：<br>(X<?=$XAmc?>/包裹)</td>
  <td>
  	<?php config_yundan_serviceForm('op_bgfee1_name','op_bgfee1_ppt','op_bgfee1_val');?>
  </td>
</tr>
<!------------->
<tr>
  <td width="200" align="right">按包裹合箱数量收费服务2：<br>(X<?=$XAmc?>/包裹)</td>
  <td>
  	<?php config_yundan_serviceForm('op_bgfee2_name','op_bgfee2_ppt','op_bgfee2_val');?>
  </td>
</tr>
<!------------->
<tr>
  <td width="200" align="right">按物品数量收费服务1：<br>(X<?=$XAmc?>/物品)</td>
  <td>
  	<?php config_yundan_serviceForm('op_wpfee1_name','op_wpfee1_ppt','op_wpfee1_val');?>
  </td>
</tr>
<!------------->
<tr>
  <td width="200" align="right">按物品数量收费服务2：<br>(X<?=$XAmc?>/物品)</td>
  <td>
  	<?php config_yundan_serviceForm('op_wpfee2_name','op_wpfee2_ppt','op_wpfee2_val');?>
  </td>
</tr>
<!------------->
<tr>
  <td width="200" align="right">按运单收费服务1：<br>(X<?=$XAmc?>/运单)</td>
  <td>
  	<?php config_yundan_serviceForm('op_ydfee1_name','op_ydfee1_ppt','op_ydfee1_val');?>
  </td>
</tr>
<!------------->
<tr>
  <td width="200" align="right">按运单收费服务2：<br>(X<?=$XAmc?>/运单)</td>
  <td>
  	<?php config_yundan_serviceForm('op_ydfee2_name','op_ydfee2_ppt','op_ydfee2_val');?>
  </td>
</tr>
<!------------->
<tr>
  <td width="200" align="right">免费服务(单选)：</td>
  <td>
  	<?php config_yundan_serviceForm('op_free_name','op_free_ppt','op_free_val');?>
  </td>
</tr>
<!------------->
<tr>
  <td width="200" align="right">免费服务(多选)：</td>
  <td>
  	<?php config_yundan_serviceForm('op_freearr_name','op_freearr_ppt','op_freearr_val');?>
  </td>
</tr>
    
    
</table>
<?php 
if(!$LGList){$LGList=languageType('',3);}
function config_yundan_serviceForm($field_name,$field_ppt,$field_val)
{
	//服务名称
	//语言字段处理++
	$field=$field_name;
	global $LGList;
	if(!$LGList){$LGList=languageType('',3);}
	if($LGList)
	{
		foreach($LGList as $arrkey=>$language)
		{
			$joint=$field.$language; global $$joint;	$val=$$joint;	
			?>
			<input name="<?=$field.$language?>" type="text" class="form-control input-msmall tooltips"  data-container="body" data-placement="top" data-original-title="服务名称(<?=languageType($language)?>)" value="<?=cadd($val)?>">
			<?php 
		}
	}
	?>
    
    
    
	<div class="xa_border"></div>
	<?php 
	//提示说明
	//语言字段处理++
	$field=$field_ppt;
	global $LGList;
	if(!$LGList){$LGList=languageType('',3);}
	if($LGList)
	{
		foreach($LGList as $arrkey=>$language)
		{
			$joint=$field.$language; global $$joint;	$val=$$joint;	
			?>
			<input name="<?=$field.$language?>" type="text" class="tooltips"  data-container="body" data-placement="top" data-original-title="提示说明(<?=languageType($language)?>)" value="<?=cadd($val)?>" style="width:100%">
			<?php 
		}
	}
	?>
    
    
	 
	<div class="xa_border"></div>
	<span class="xa_sep"> | </span>
	<?php 
	//选项名称
	$i_val=0;	if($field_name=='op_freearr_name'){$i_val=1;}
	for ($i_val=$i_val; $i_val<=10; $i_val++)
	{
		//语言字段处理++
		$field=$field_val.$i_val;
		if(!$LGList){$LGList=languageType('',3);}
		if($LGList)
		{
			foreach($LGList as $arrkey=>$language)
			{
				$joint=$field.$language; global $$joint;	$val=$$joint;
				?>
			  <input name="<?=$field.$language?>" type="text" class="tooltips"  data-container="body" data-placement="top" data-original-title="<?=!$i_val?'不选用的名称':'选项名称'.$i_val?>(<?=languageType($language)?>)" value="<?=cadd($val)?>"><?=!$i_val?'<br>':''?>
				<?php 
			}
		}
		echo '<span class="xa_sep"> | </span>';
		//echo '<div class="xa_border"></div>';
	}
}?>

             
            </div>
          </div>

          
        </div>
		 <!---->
         
         
         
         

<div class="tab-pane" id="tab_6">
<div class="portlet">
  <div class="portlet-title">
    <div class="caption"><i class="icon-reorder"></i>代购配置</div>
    <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
  </div>
  <div class="portlet-body form" style="display: block;"> 
    <!--表单内容-->
    <div class="daycat" align="left">
      <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
        <tbody>
          
          <tr class="odd gradeX">
            <td align="right"  width="200">代购单号格式：</td>
            <td align="left">
<input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="前缀"  name="dg_tpre"   value="<?=cadd($dg_tpre)?>">


<select name="dg_typ" class="form-control input-xlarge select2me tooltips" data-container="body" data-placement="top" data-original-title="生成格式">
<?php OrderNo_typ($dg_typ);?>
</select>

<input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="后缀"  name="dg_suffix"   value="<?=cadd($dg_suffix)?>">
         
              </td>
          </tr>
          
        <tr class="odd gradeX">
            <td align="right"  width="200">需要审核流程：</td>
            <td align="left">
<div class="make-switch tooltips" data-container="body" data-placement="top" data-original-title="关闭时，新下单状态为【<?=daigou_Status(2)?>】"  data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>"><input type="checkbox" class="toggle" name="dg_checked" value="1" <?=$dg_checked?'checked':''?> /></div>         
              </td>
          </tr>           
          
        <tr class="odd gradeX">
            <td align="right"  width="200">询价留言：</td>
            <td align="left">
<div class="make-switch tooltips" data-container="body" data-placement="top" data-original-title="开启时会员可通用在线留言方式咨询商品价格"  data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>"><input type="checkbox" class="toggle" name="dg_enquiry" value="1" <?=$dg_enquiry?'checked':''?> /></div>         
              </td>
          </tr>           
          
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">代购包裹入库码：</td>
                    <td align="left">
                    
                    <input  class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="可留空"  name="dg_whcodTpre" type="text" value="<?=cadd($dg_whcodTpre)?>" size="5"/>前缀+
                    <input  class="form-control input-xsmall" name="dg_whcodLength" type="text" value="<?=cadd($dg_whcodLength)?>" size="3"/>位字母
                      <span class="help-block">
设置5位或以上(如果代购单数量会超过1188万时要设置5位以上)；<br>

每个代购单生成一个独立的英文入库码，对某些购物网站不给有数字ID的地址时，可以使用此入库码识别包裹
</span>

				</td>
                  </tr>
                  
                                 
<tr class="odd gradeX">
<td align="right"  width="200">代购支持币种：</td>
<td align="left">
    <select multiple="multiple" class="multi-select" id="my_multi_select3_2" name="dg_openCurrency[]">
    <?=openCurrency($dg_openCurrency,2)?>
    </select>
    
    <span class="help-block">
    <font class="red"> &raquo; 必须选择，修改后会员组里的代购“寄库运费”也要修改，否则运费计费错误<br></font>
    &raquo; 左边表示未选择,右边表示已选择<br>
    </span>
</td>
</tr>

				<tr class="odd gradeX">
                      <td align="right"  width="200">操作通知：</td>
                      <td align="left">
需要补款:                      
<input name="daigou_IncCost_msg" type="checkbox" value="1"  <?=$daigou_IncCost_msg?'checked':''?>/>站内信
&nbsp;&nbsp;
<input name="daigou_IncCost_mail" type="checkbox" value="1"  <?=$daigou_IncCost_mail?'checked':''?>/>邮件  
&nbsp;&nbsp;
<input name="daigou_IncCost_sms" type="checkbox" value="1"  <?=$daigou_IncCost_sms?'checked':''?>/>短信
&nbsp;&nbsp;
<input name="daigou_IncCost_wx" type="checkbox" value="1"  <?=$daigou_IncCost_wx?'checked':''?>/>微信 
<div class="xa_border"></div>

后台扣费:                      
<input name="daigou_managePay_msg" type="checkbox" value="1"  <?=$daigou_managePay_msg?'checked':''?>/>站内信
&nbsp;&nbsp;
<input name="daigou_managePay_mail" type="checkbox" value="1" <?=$daigou_managePay_mail?'checked':''?>/>邮件  
&nbsp;&nbsp;
<input name="daigou_managePay_sms" type="checkbox" value="1"  <?=$daigou_managePay_sms?'checked':''?>/>短信
&nbsp;&nbsp;
<input name="daigou_managePay_wx" type="checkbox" value="1"  <?=$daigou_managePay_wx?'checked':''?>/>微信
<div class="xa_border"></div>

后台退费:                      
<input name="daigou_manageRef_msg" type="checkbox" value="1"  <?=$daigou_manageRef_msg?'checked':''?>/>站内信
&nbsp;&nbsp;
<input name="daigou_manageRef_mail" type="checkbox" value="1"  <?=$daigou_manageRef_mail?'checked':''?>/>邮件  
&nbsp;&nbsp;
<input name="daigou_manageRef_sms" type="checkbox" value="1"  <?=$daigou_manageRef_sms?'checked':''?>/>短信
&nbsp;&nbsp;
<input name="daigou_manageRef_wx" type="checkbox" value="1"  <?=$daigou_manageRef_wx?'checked':''?>/>微信 
<div class="xa_border"></div>

商品入库:                      
<input name="daigou_inStorage_msg" type="checkbox" value="1"  <?=$daigou_inStorage_msg?'checked':''?>/>站内信
&nbsp;&nbsp;
<input name="daigou_inStorage_mail" type="checkbox" value="1"  <?=$daigou_inStorage_mail?'checked':''?>/>邮件  
&nbsp;&nbsp;
<input name="daigou_inStorage_sms" type="checkbox" value="1"  <?=$daigou_inStorage_sms?'checked':''?>/>短信
&nbsp;&nbsp;
<input name="daigou_inStorage_wx" type="checkbox" value="1"  <?=$daigou_inStorage_wx?'checked':''?>/>微信 
<div class="xa_border"></div>
                        
                        </td>
                    </tr>


				<tr class="odd gradeX">
                      <td align="right"  width="200">状态变更通知：</td>
                      <td align="left">
<?php 
for ($i=0; $i<=10; $i++)
{
	$name=daigou_Status($i);
	if($name)
	{
		echo $name.':';
		$joint='daigou_status_msg'.$i; $msg=$$joint;
		$joint='daigou_status_mail'.$i; $mail=$$joint;
		$joint='daigou_status_sms'.$i; $sms=$$joint;
		$joint='daigou_status_wx'.$i; $wx=$$joint;
		?>
        <input name="daigou_status_msg<?=$i?>" type="checkbox" value="1"  <?=$msg?'checked':''?>/>站内信
        &nbsp;&nbsp;
        
        <input name="daigou_status_mail<?=$i?>" type="checkbox" value="1"  <?=$mail?'checked':''?>/>邮件  
        &nbsp;&nbsp;
        
        <input name="daigou_status_sms<?=$i?>" type="checkbox" value="1"  <?=$sms?'checked':''?>/>短信
        &nbsp;&nbsp;
        
        <input name="daigou_status_wx<?=$i?>" type="checkbox" value="1"  <?=$wx?'checked':''?>/>微信
        <div class="xa_border"></div>
        <?php
	}
}
?>                    
                        
                        </td>
                    </tr>
                    
                    <tr class="odd gradeX"><td valign="top" align="right" width="200">自动仓储时通知：</td>
                    <td align="left">
					<input type="checkbox" name="dg_ware_msg" value="1" <?=$dg_ware_msg?'checked':''?> /> 站内信
					</td>
                    </tr>

         
        </tbody>
      </table>
    </div>
  </div>
</div>


</div>      
         
         
         
         
         
         
         
         

        <div class="tab-pane" id="tab_7">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>商城配置</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <div class="daycat" align="left">
                <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                  <tbody>

                    <tr class="odd gradeX"><td valign="top" align="right" width="200">无库存时自动下架：</td>
                      <td align="left">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                          <input type="checkbox" class="toggle" name="off_mall_checked" value="1"  <?=$off_mall_checked?'checked':''?> />
                        </div>
                        
                       <span class="help-block"> 每小时更新一次</span></td>
                    </tr>
                    
                    <tr class="odd gradeX"><td valign="top" align="right" width="200">订单有效时间：</td>
                      <td align="left"><input name="mall_order_time" type="text" class="form-control input-xsmall" value="<?=$mall_order_time?>" size="10" />
                        小时 <span class="help-block"> &raquo; 未付款订单超过该时间将失效，数量重新入库!(由于会员订购时即会减数量，防止会员订购后而不付款，使其他会员不能购买，建议不要设置太长)<br />
                        &raquo; 填写0为不限制<br />
                       &raquo; 每小时更新一次<br />
                        </span></td>
                    </tr>
                    <tr class="odd gradeX">
                      <td align="right"  width="200">订单付款后通知：</td>
                      <td align="left"><input name="mall_order_pay_msg" type="checkbox" value="1"  <?=$mall_order_pay_msg?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="mall_order_pay_mail" type="checkbox" value="1"  <?=$mall_order_pay_mail?'checked':''?>/>
                        邮件  
                        
                        &nbsp;&nbsp;
                        <input name="mall_order_pay_sms" type="checkbox" value="1"  <?=$mall_order_pay_sms?'checked':''?>/>
                        短信
                        
                        &nbsp;&nbsp;
                        <input name="mall_order_pay_wx" type="checkbox" value="1"  <?=$mall_order_pay_wx?'checked':''?>/>
                        微信
                        
                        </font></td>
                    </tr>
						<tr class="odd gradeX">
						<td valign="top" align="right" width="200">生成包裹自动入库：</td>
                      <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                          <input type="checkbox" class="toggle" name="baoguo_ruku_od" value="1"  <?=$baoguo_ruku_od==1?'checked':''?> />
                        </div>
                        <span class="help-block">会员支付订单后生成的包裹不用工作人员操作就自动转入会员个人仓库,会员不用等待就可以直接对包裹操作<br>
(正常流程时工作人员一般需要操作:审核订单>打包>贴标签>称重>存放>更新包裹状态为“入库”) </span></td>
                    </tr>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          
        <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>前台模板</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  
              <tr class="odd gradeX">
                  <td  width="200" align="right" valign="top">常见问题：</td>
                  <td align="left">
<?php 
//语言字段处理++
$field='mallFAQ';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <textarea name="<?=$field.$language?>" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" rows="15"><?=cadd($val)?></textarea>
		<?php 
	}
}
?>
<span class="help-block">代码是样式效果，请不要乱修改代码，可修改内容 (支持HTML代码)</span>
                      
                  </td>
              </tr>
                                    
                  
                </tbody>
              </table>
            </div>
          </div>
          
                  </div>










<?php 
function Coupons_config($tag,$i)
{
	global $XAmc;
	$joint="{$tag}cp_number{$i}"; 			global $$joint;			$cp_number=$$joint;
	$joint="{$tag}cp_types{$i}"; 			global $$joint;			$cp_types=$$joint;
	$joint="{$tag}cp_usetypes{$i}"; 		global $$joint;			$cp_usetypes=$$joint;
	$joint="{$tag}cp_value{$i}"; 			global $$joint;			$cp_value=$$joint;
	$joint="{$tag}cp_limitmoney{$i}"; 		global $$joint;			$cp_limitmoney=$$joint;
	$joint="{$tag}cp_code_digits{$i}"; 		global $$joint;			$cp_code_digits=$$joint;
	$joint="{$tag}cp_overdue{$i}"; 			global $$joint;			$cp_overdue=$$joint;
	?>

 <strong>第<?=$i?>种：获得
  <input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="0则不送 (大于1则赠送,并表示该券可以使用X次)" name="<?=$tag?>cp_number<?=$i?>" value="<?=$cp_number?>">
  张</strong><br>


  券类型
  <select  class="form-control input-small select2me" name="<?=$tag?>cp_types<?=$i?>" data-placeholder="类型" >
    <?=Coupons_Types($cp_types,1)?>
  </select>
  <span class="xa_sep"> | </span>
  可用于
  <select  class="form-control input-small select2me" name="<?=$tag?>cp_usetypes<?=$i?>" data-placeholder="可使用类型" >
    <?=Coupons_usetypes($cp_usetypes,1)?>
  </select>
  
  <span class="xa_sep"> | </span>
  价值
  <input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="面值金额或折扣额" name="<?=$tag?>cp_value<?=$i?>" value="<?=$cp_value?>" >
  <?=$XAmc?>/折
  
  <span class="xa_sep"> | </span>
  最低消费
  <input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="多少<?=$XAmc?>消费金额才能使用" name="<?=$tag?>cp_limitmoney<?=$i?>" value="<?=$cp_limitmoney?>">
  <?=$XAmc?>
  
  <span class="xa_sep"> | </span>
  券号码长度
  <input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="位数:8-30位" name="<?=$tag?>cp_code_digits<?=$i?>"  value="<?=$cp_code_digits?>">
  位
  
  <span class="xa_sep"> | </span>
  有效期
  <input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="留空则永久有效" name="<?=$tag?>cp_overdue<?=$i?>"   value="<?=$cp_overdue?>">
  天
<div class="xa_border"></div>
<?php }?>






        

        
        <div class="tab-pane" id="tab_8">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>新注册配置</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">开放注册：</td>
                    <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_member_reg" value="1"  <?=$off_member_reg==1?'checked':''?> />
                      </div>
                      </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">人工审核：</td>
                    <td align="left">
                    
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="member_reg_sh" value="1"  <?=$member_reg_sh==1?'checked':''?> />
                      </div>
                      <span class="help-block">通过审核后才能登录</span>
                      </td>
                  </tr>

                  <tr class="odd gradeX">
                    <td align="right"  width="200">关闭/开通时通知：</td>
                    <td align="left">
                    
                        <input name="member_sh_msg" type="checkbox" value="1"  <?=$member_sh_msg?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="member_sh_mail" type="checkbox" value="1"  <?=$member_sh_mail?'checked':''?>/>
                        邮件  
                        
                        &nbsp;&nbsp;
                        <input name="member_sh_sms" type="checkbox" value="1"  <?=$member_sh_sms?'checked':''?>/>
                        短信
                        
                        
                        &nbsp;&nbsp;
                        <input name="member_sh_wx" type="checkbox" value="1"  <?=$member_sh_wx?'checked':''?>/>
                        微信
                        
                      
                      </td>
                  </tr>
                  
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">注册时验证手机：</td>
                    <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_reg_mobile" value="1"  <?=$off_reg_mobile==1?'checked':''?> />
                      </div>
                      <span class="help-block">开通短信功能才有效</span></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">注册时验证邮箱：</td>
                    <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_reg_email" value="1"  <?=$off_reg_email?'checked':''?> />
                      </div>
                     </td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">注册用户名类型：</td>
                    <td align="left"><select name="member_reg_lx" id="select"  class="form-control input-medium">
                        <option value="1" <?php if($member_reg_lx==1){echo 'selected="selected"';}?>>必须用邮箱</option>
                        <option value="2" <?php if($member_reg_lx==2){echo 'selected="selected"';}?>>不能邮箱</option>
                        <option value="3" <?php if($member_reg_lx==3){echo 'selected="selected"';}?>>不能用邮箱并且全部是字母</option>
                      </select></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">注册用户名禁止含有关键字：</td>
                    <td align="left"><input name="member_reg_key" type="text" value="<?=cadd($member_reg_key)?>" class="form-control"/>
                      <span class="help-block">用英文逗号“,”分开</span></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">会员ID前缀：</td>
                    <td align="left">
                    
                    <input  class=" tooltips" data-container="body" data-placement="top" data-original-title="可留空" name="memberid_tpre" type="text" value="<?=cadd($memberid_tpre)?>" size="5"/>
                      <span class="help-block">
                        在会员中心和前台显示，只展示用，无功能作用
                      </span>

				</td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">会员入库码：</td>
                    <td align="left">
                    
                    <input class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="可留空"  name="member_tpreic" type="text" value="<?=cadd($member_tpreic)?>" size="5"/>前缀+
                    <input class="form-control input-xsmall" name="member_ic" type="text" value="<?=cadd($member_ic)?>" size="3"/>位字母
                      <span class="help-block">
设置5位或以上(如果会员数量会超过1188万时要设置5位以上)；<br>
每个会员生成一个独立的英文入库码,对某些购物网站不给有数字ID的地址时,可以使用此入库码识别会员包裹</span>

				</td>
                  </tr>
                  
                  
  <tr class="odd gradeX">
<td align="right"  width="200">会员账户支持币种：</td>
<td align="left">
    <select multiple="multiple" class="multi-select" id="my_multi_select3_3" name="me_openCurrency[]">
    <?=openCurrency($me_openCurrency,2)?>
    </select>
    
    <span class="help-block">
    &raquo; 必须选择 (左边表示未选择,右边表示已选择)<br>
    &raquo; 如显示不对,请先提交<br>
    &raquo; 会员账户会选择其中的一种<br>
    </span>
</td>
</tr>
                
                     <tr class="odd gradeX">
                    <td align="right"  width="200">新注册会员送分：</td>
                    <td align="left"><input name="integral_xhysf" type="text" value="<?=cadd($integral_xhysf)?>" size="5" onkeyup="value=value.replace(/[^\d.]/g,'')"  class="tooltips" data-container="body" data-placement="top" data-original-title="写0则不送"/>
                      分 <span class="help-block">开通积分功能才有效</span></td>
                  </tr>
 
 <tr class="odd gradeX">
                    <td align="right"  width="200">新注册会员送<br>
优惠券/折扣券：</td>
                    <td align="left"><?php for($i=1; $i<=3; $i++){Coupons_config('reg',$i);}?>   </td>
                  </tr>
                                   
               
                  <tr class="odd gradeX">
                    <td align="right"  width="200">新注册发邮件：</td>
                    <td align="left">
                    
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_member_reg_sendmail" value="1"  <?=$off_member_reg_sendmail?'checked':''?> />
                      </div>
                   <br><br>

<?php 
//语言字段处理++
$field='member_reg_sendmail';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <textarea name="<?=$field.$language?>" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" rows="5"><?=cadd($val)?></textarea>
		<?php 
	}
}
?>

<?=Label('',1)//显示信息标签说明?>  

                   
                     </td>
                  </tr>
                  
                  
                  
                  
                      
                      
                </tbody>
              </table>
            </div>
          </div>
          
          <!---->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>会员推广</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <div class="daycat" align="left">
                <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                  <tbody>
                    <tr class="odd gradeX">
                      <td align="right"  width="200">会员推广：</td>
                      <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                          <input type="checkbox" class="toggle" name="off_tuiguang" value="1"  <?=$off_tuiguang==1?'checked':''?> />
                        </div></td>
                    </tr>
                    <tr class="odd gradeX">
                      <td align="right"  width="200">新注册送积分：</td>
                      <td align="left"> 推广员获
                        <input name="tuiguang_tgy" type="text" class="form-control input-small" value="<?=cadd($tuiguang_tgy)?>" size="5" onkeyup="value=value.replace(/[^\d.]/g,'')" />
                        分；新会员获
                        <input name="tuiguang_xhy" type="text" class="form-control input-small" value="<?=cadd($tuiguang_xhy)?>" size="5" onkeyup="value=value.replace(/[^\d.]/g,'')" />
                        分 </td>
                    </tr>
                    
                     
                     
                      
                    
                    
                     <tr class="odd gradeX">
                      <td  width="200" align="right">
                      会员送优惠券/折扣券：
                      <br><br>
<div class="gray2" align="left">注：本功能依赖积分功能,因此必须开通积分功能,并且上面设置至少送1积分才有效</div>
                      
                      </td>
                      <td align="left">
                      <?php for($i=1; $i<=3; $i++){Coupons_config('tgy',$i);}?> 
					  </td>
                    </tr>
                    
                     <tr class="odd gradeX">
                      <td  width="200" align="right">
                      推广员送优惠券/折扣券：
                      <br><br>
<div class="gray2" align="left">注：本功能依赖积分功能,因此必须开通积分功能,并且上面设置至少送1积分才有效</div>
                      
                      </td>
                       <td align="left">
                       <?php for($i=1; $i<=3; $i++){Coupons_config('xhy',$i);}?> 
					   </td>
                     </tr>
                     
                     
                     

                   
                     

                   <tr class="odd gradeX">
                      <td valign="top" align="right"  width="200">新注册防作弊：</td>
                      <td align="left"><input name="tuiguang_tgyip" type="checkbox" value="1"  <?=$tuiguang_tgyip==1?'checked':''?>/>
                        新注册会员与推广员注册/最后登录IP相同无效 <br />
                        <input name="tuiguang_xhyip" type="checkbox" value="1"  <?=$tuiguang_xhyip==1?'checked':''?>/>
                        新注册会员与上一个新注册会员的注册/最后登录IP相同无效 <br />
                        新注册会员与上一个新注册会员间隔
                        <input name="tuiguang_xhysj" type="text" value="<?=cadd($tuiguang_xhysj)?>" size="5"  onBlur="limitNumber(this,'0,1000','0');"  class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="写0则不限制">
                        分种以内无效 <br />
                        新注册会员名与上一个新注册会员名相似度
                        <input name="tuiguang_xhymc" type="text" value="<?=cadd($tuiguang_xhymc)?>" size="5" onBlur="limitNumber(this,'0,100','0');" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="写0则不限制">
                        %以上无效<br />
                        推广员每天最多有效获分
                        <input name="tuiguang_tgyhdcs" type="text" value="<?=cadd($tuiguang_tgyhdcs)?>" size="5" onBlur="limitNumber(this,'0,1000','0');"  class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="写0则不限制">
                        次，超过有效后
                        <input name="tuiguang_xhyhdcs" type="checkbox" value="1"  <?=$tuiguang_xhyhdcs==1?'checked':''?>/>
                        还送分给新注册会员</td>
                    </tr>
                    
                    <tr class="odd gradeX">
                      <td align="right"  width="200">后期送分：</td>
                      <td align="left"> 
                      赠送时长<input name="tuiguang_hqsf" type="text" value="<?=spr($tuiguang_hqsf)?>" size="5"  onBlur="limitNumber(this,'0,100000','0');" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="0则不限时；-1则不赠送"/>天 <font class="gray2">按新会员注册时间算，超过该时长则不再送分</font>
                      <br>

                      新会员运单消费时：推广员获
                        <input name="tuiguang_ydxf_sl" type="text" value="<?=spr($tuiguang_ydxf_sl)?>" size="5"  onBlur="limitNumber(this,'0,100000','0');" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="非0时优先用此项"/>分，或按新会员获得积分的<input name="tuiguang_ydxf_bl" type="text" class="form-control input-xsmall" value="<?=spr($tuiguang_ydxf_bl)?>" size="5"  onBlur="limitNumber(this,'0,1000','0');"  />% 
                      
                      <br>
                       新会员商城消费时：推广员获
                        <input name="tuiguang_mallxf_sl" type="text" value="<?=spr($tuiguang_mallxf_sl)?>" size="5"  onBlur="limitNumber('tuiguang_mallxf_sl','0,100000','0');" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="非0时优先用此项"/>分，或按新会员获得积分的<input name="tuiguang_mallxf_bl" type="text" class="form-control input-xsmall" value="<?=spr($tuiguang_mallxf_bl)?>" size="5"  onBlur="limitNumber(this,'0,1000','0');" />% 
                      
                      <br>
                       新会员代购消费时：推广员获
                        <input name="tuiguang_dgxf_sl" type="text" value="<?=spr($tuiguang_dgxf_sl)?>" size="5"  onBlur="limitNumber(this,'0,100000','0');" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="非0时优先用此项"/>分，或按新会员获得积分的<input name="tuiguang_dgxf_bl" type="text" class="form-control input-xsmall" value="<?=spr($tuiguang_dgxf_bl)?>" size="5"  onBlur="limitNumber(this,'0,1000','0');" />% 

                       
                     </td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
<!---->
        <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>实名认证</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                    
                  <tr class="odd gradeX">
                    <td align="right"  width="200">实名认证：</td>
                    <td align="left">
                    
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_certification" value="1"  <?=$off_certification?'checked':''?> />
                      </div>
                      </td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">未通过认证禁用的系统：</td>
                    <td align="left">
                    <input name="certification_baoguo" type="checkbox" value="1"  <?=$certification_baoguo?'checked':''?>/>包裹系统； 
                    <input name="certification_yundan" type="checkbox" value="1"  <?=$certification_yundan?'checked':''?>/>运单系统；
                    <input name="certification_mall_order" type="checkbox" value="1"  <?=$certification_mall_order?'checked':''?>/>商城系统；
                    <input name="certification_daigou" type="checkbox" value="1"  <?=$certification_daigou?'checked':''?>/>代购系统；
                    <input name="certification_qujian" type="checkbox" value="1"  <?=$certification_qujian?'checked':''?>/>上门取件系统；
                    
                   </td>
                  </tr>

                  <tr class="odd gradeX">
                    <td align="right"  width="200">申请认证条件：</td>
                    <td align="left">
                    <select name="certification_ct1" id="select" class="form-control input-medium">
                        <option value="1" <?php if($certification_ct1==1){echo 'selected="selected"';}?>>已验证手机或邮箱</option>
                        <option value="2" <?php if($certification_ct1==2){echo 'selected="selected"';}?>>已验证手机和邮箱</option>
                        <option value="3" <?php if($certification_ct1==3){echo 'selected="selected"';}?>>已验证手机</option>
                        <option value="4" <?php if($certification_ct1==4){echo 'selected="selected"';}?>>已验证邮箱</option>
                  </select>
                      <br><br>

                    <input name="certification_ct2" type="checkbox" value="1"  <?=$certification_ct2?'checked':''?>/>填写身份号码<br>
                    <input name="certification_ct3" type="checkbox" value="1"  <?=$certification_ct3?'checked':''?>/>上传身份证件
                    
                   </td>
                  </tr>
				  
				  
				  
                </tbody>
              </table>
            </div>
          </div>
          
<!---->
<div class="portlet">
  <div class="portlet-title">
    <div class="caption"><i class="icon-reorder"></i>会员生日</div>
    <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
  </div>
  <div class="portlet-body form" style="display: block;"> 
    <!--表单内容-->
<table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
    <tbody>
        <tr class="odd gradeX">
          <td align="right"  width="200">生日送分：</td>
          <td align="left"><input name="integral_MemberBirthday" type="text" class="form-control input-small" value="<?=cadd($integral_MemberBirthday)?>" size="5" onkeyup="value=value.replace(/[^\d.]/g,'')"/>
            分
            
            </td>
        </tr>

      <tr class="odd gradeX">
        <td align="right"  width="200">祝福和送分通知：</td>
        <td align="left">
            <input name="member_birthday_msg" type="checkbox" value="1"  <?=$member_birthday_msg?'checked':''?>/>
            站内信
            &nbsp;&nbsp;
            <input name="member_birthday_mail" type="checkbox" value="1"  <?=$member_birthday_mail?'checked':''?>/>
            邮件  
            
            &nbsp;&nbsp;
            <input name="member_birthday_sms" type="checkbox" value="1"  <?=$member_birthday_sms?'checked':''?>/>
            短信
            
            &nbsp;&nbsp;
            <input name="member_birthday_wx" type="checkbox" value="1"  <?=$member_birthday_wx?'checked':''?>/>
            微信
         </td>
      </tr>
      
      
      <tr class="odd gradeX">
        <td align="right"  width="200">祝福和送分内容：</td>
        <td align="left">
<?php 
//语言字段处理++
$field='member_birthday_content';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <textarea name="<?=$field.$language?>" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" rows="5"><?=cadd($val)?></textarea>
		<?php 
	}
}
?>


<?=Label('',1)//显示信息标签说明?> 
          
         </td>
      </tr>
          
  </tbody>
</table>
   </div>
</div>
          <!---->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>其他配置</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                <tr class="odd gradeX">
                    <td align="right"  width="250">自动登录：</td>
                    <td align="left">
                    
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="ON_MemberAutoLogin" value="1"  <?=$ON_MemberAutoLogin?'checked':''?> />
                      </div>
                   <span class="help-block">&raquo; 勾选自动登录时，7天内在该电脑访问网站便会自动登录到该账号<br>
&raquo; 如果会员在公共场所使用该功能，他人使用该电脑时也会自动登录，因此如无必要不建议开启该功能</span>
                   	
                      </td>
                  </tr>
                  
                 <tr class="odd gradeX">
                    <td align="right"  width="250">会员客户端：</td>
                    <td align="left">
                    
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="ON_MemberClient" value="1"  <?=$ON_MemberClient?'checked':''?> />
                      </div>
                   <span class="help-block">
                   &raquo; 专门给会员的客户使用的功能，只有运单查询<?php if($off_upload_cert){?>和上传证件功能<?php }?>，访问该客户端后就无法浏览其他页面（需要重启浏览器）<br>
                   &raquo; 作用是防止这些客户自行在网站下单，以保护会员自己的客户资源
</span>
                   	
                      </td>
                  </tr>
                 
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="250">自动弹出公告间隔：</td>
                    <td align="left"><input name="member_prompt_time" type="text" class="form-control input-xsmall" value="<?=spr($member_prompt_time)?>" size="5" />分
					</td>
                  </tr>
				  

                  
                  <tr class="odd gradeX">
                    <td align="right"  width="250">取回密码允许认证码错误次数：</td>
                    <td align="left"><input name="member_getpw_number" type="text" class="form-control input-xsmall" value="<?=cadd($member_getpw_number)?>" size="5" />次
				 <span class="help-block">超过次数后需要重新发送认证码 (请填写1-10之间,超过该范围无效)</span>	
					</td>
                  </tr>
				  
  					
                    <tr class="odd gradeX">
                      <td align="right"  width="250">月结生成账单和销帐后 通知会员：</td>
                      <td align="left">
                      <input name="settlement_msg" type="checkbox" value="1"  <?=$settlement_msg?'checked':''?>/>
                        站内信
                        &nbsp;&nbsp;
                        <input name="settlement_mail" type="checkbox" value="1"  <?=$settlement_mail?'checked':''?>/>
                        邮件  
                        
                        &nbsp;&nbsp;
                        <input name="settlement_sms" type="checkbox" value="1"  <?=$settlement_sms?'checked':''?>/>
                        短信
                        
                        &nbsp;&nbsp;
                        <input name="settlement_wx" type="checkbox" value="1"  <?=$settlement_wx?'checked':''?>/>
                        微信
						</td>
                    </tr>
              
                     <tr class="odd gradeX">
                      <td align="right"  width="250">会员专属客服：</td>
                      <td align="left">
<textarea name="CustomerService" class="form-control" cols="30" rows="5"><?=cadd($CustomerService)?></textarea>
                        <span class="help-block"> 
						格式：客服号=姓名=电话号码=QQ=微信=邮箱=其他联系方式=咨询链接(链接需要urlencode编码)<br>
						例:<br>
						101=小张=12345671=1234561=wx001=1231@qq.com=地址=http%3a%2f%2ftool.chinaz.com%2ftools%2furlencode.aspx<br>
						102=小李=12345672=1234562=wx002=1232@qq.com<br>
                        <br />
                          &raquo; 请用账号ID当作客服号<br />
                         &raquo; 每行一个名称，不要有特殊符号<br />
                         &raquo; <a href="http://tool.chinaz.com/Tools/urlencode.aspx" target="_blank">urlencode编码</a><br />
                         
                        </span>                     
                     
						</td>
                    </tr>
                    
                    <tr class="odd gradeX">
                      <td align="right"  width="250">代购咨询链接：</td>
                      <td align="left">
<textarea name="daigouCS" class="form-control" cols="30" rows="5"><?=cadd($daigouCS)?></textarea>
                        <span class="help-block"> 
                         &raquo; 每行一个链接<br />
                         &raquo; 可以不用urlencode编码
                        </span>                     
						</td>
                    </tr>                    
                    
                    <tr class="odd gradeX">
                      <td align="right"  width="250">浮动咨询链接：</td>
                      <td align="left">
<textarea name="floatingCS" class="form-control" cols="30" rows="5"><?=cadd($floatingCS)?></textarea>
                        <span class="help-block"> 
                         &raquo; 每行一个链接<br />
                         &raquo; 可以不用urlencode编码
                        </span>                     
						</td>
                    </tr>                    
                    
                 				  
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab_9">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>邮件发送</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">SMTP服务器：</td>
                    <td align="left"><input name="smtp_server" type="text" class="form-control input-medium" value="<?=cadd($smtp_server)?>"/>
                      <span class="help-block"> 具体请咨询邮箱提供商（一般可百度搜索到）<br>
                      <br>
                      下面是163邮箱：<br>
                      免费个人邮箱：smtp.163.com<br>
                      VIP个人邮箱：smtp.vip.163.com<br>
                      免费企业邮箱：smtp.ym.163.com<br>
                      收费企业邮箱：smtp.qiye.163.com </span></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">SMTP端口：</td>
                    <td align="left"><input name="smtp_port" type="text" class="form-control input-xsmall" value="<?=cadd($smtp_port)?>" size="10"/>
                      <span class="help-block"> 一般是:25/465/994   具体请咨询邮箱提供商或百度搜索 </span></td>
                  </tr>
                <tr class="odd gradeX">
                    <td align="right"  width="200">安全协议：</td>
                    <td align="left"><input name="smtp_secure" class="form-control input-xsmall" type="text" value="<?=cadd($smtp_secure)?>" size="10"/>
                      <span class="help-block"> gmail邮箱需要填写ssl，其他一般不要填写否则无法发送 </span></td>
                  </tr>
                                    <tr class="odd gradeX">
                    <td align="right"  width="200">邮箱呢称：</td>
                    <td align="left">
<?php 
//语言字段处理++
$field='smtp_name';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <input name="<?=$field.$language?>" type="text" class="form-control input-medium tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" maxlength="50" value="<?=cadd($val)?>"><br>
		<?php 
	}
}
?>
				   </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">邮箱账号：</td>
                    <td align="left"><input name="smtp_mail" type="text" class="form-control input-medium"  value="<?=cadd($smtp_mail)?>" /></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">邮箱密码：</td>
                    <td align="left"><input name="smtp_password" type="text" class="form-control input-medium" value="<?=cadd($smtp_password)?>" autocomplete="off"/>
					<span class="help-block">如果是163新账号则填写“客户端授权密码”</span>
					</td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">邮件测试：</td>
                    <td align="left">
<input name="test_mail" type="text" value="" class="form-control input-medium" />
<input type="button" class="btn btn-xs btn-default" value="发送" onClick="window.open('/xingao/config/test_mail.php?test_mail='+document.xingao.test_mail.value+'&smtp_server='+document.xingao.smtp_server.value+'&smtp_secure='+document.xingao.smtp_secure.value+'&smtp_port='+document.xingao.smtp_port.value+'&smtp_name='+document.xingao.smtp_name<?=$LT?>.value+'&smtp_mail='+document.xingao.smtp_mail.value+'&smtp_password='+document.xingao.smtp_password.value+'');">
					
					</td>
                  </tr>

                </tbody>
              </table>
<div class="xats"><font class="red2"><strong>提示：</strong><br>
&raquo; 发邮件速度较慢，建议减少开通邮件通知项目；<br>
&raquo; 尽量不要使用以上默认邮箱，请自行去申请邮箱并修改到以上资料，否则可能会不稳定并且<strong>会员邮箱可能会被泄露</strong><br></font>
&raquo; 如果网站服务器是国内，建议用163（163要单独开通SMTP服务：登录邮箱 > 设置 > POP3/SMTP/IMAP）
</div>
               
            </div>
          </div>
          <!---->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>短信</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">短信功能：</td>
                    <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_sms" value="1"  <?=$off_sms?'checked':''?> />
                      </div>
                      <span class="help-block"> 要开通/充值请联系我们;可用于手工发短信及运单状态变更系统自动发短信(具体在后面设置)</span></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">账号类型：</td>
                    <td align="left"><div class="radio-list">
                        <label class="radio-inline">
                          <input name="sms_type" type="radio" value="1"  <?=$sms_type==1?'checked':''?>/>
                          <font title="只能发到中国的手机号码">中国</font> </label>
                        <label class="radio-inline">
                          <input name="sms_type" type="radio" value="2"  <?=$sms_type==2?'checked':''?>/>
                          <font title="可以发到240多个国家的手机号码">国际 </font> </label>
                      </div>
                      <span class="help-block"> 要按所申请的类型来选择否则无法使用</span></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">用户账号：</td>
                    <td align="left"><input name="sms_user" type="text" class="form-control input-medium" value="<?=$sms_user?>" size="50" /></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">用户密码：</td>
                    <td align="left"><input name="sms_pwd" type="text" class="form-control input-medium" value="<?=$sms_pwd?>" size="50" />
                      </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">apikey：</td>
                    <td align="left"><input name="sms_key" type="text" class="form-control input-medium" value="<?=$sms_key?>" size="50" />
                      </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">签名：</td>
                    <td align="left"><input name="sms_signature" type="text" class="form-control input-medium" value="<?=cadd($sms_signature)?>" size="30" />
                      <span class="help-block">在短信内容里显示的签名(短信平台里也有签名，如果已使用平台的签名，此项请留空否则会发送2个签名)</span></td>
                  </tr>
                  
                </tbody>
              </table>
            </div>
          </div>
<!---->          

<div class="portlet">
  <div class="portlet-title">
    <div class="caption"><i class="icon-reorder"></i>微信</div>
    <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
  </div>
  <div class="portlet-body form" style="display: block;"> 
    <!--表单内容-->
    <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
      <tbody>
        <tr class="odd gradeX">
          <td align="right"  width="200">微信公众号：</td>
          <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="ON_mpWeixin" value="1"  <?=$ON_mpWeixin?'checked':''?> />
            </div>
            <br>
            <a href="https://mp.weixin.qq.com" target="_blank">申请公众号</a>
            <span class="xa_sep"> | </span>
            <a href="https://open.weixin.qq.com" target="_blank">申请开放平台</a>
            <span class="help-block">  &raquo; 支持在公众号查单、推送、上传证件等操作</span></td>
        </tr>
        <tr class="odd gradeX">
          <td align="right"  width="200">审核模式：</td>
          <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="mpWeixin_checked" value="1"  <?=$mpWeixin_checked?'checked':''?> />
            </div>
            
             <span class="help-block">  &raquo; 申请接口审核时必须开通该模式才能通过审核验证(否则提示token验证失败)，通过审核后正式使用时必须关闭该模式</span>
             </td>
        </tr>
        <tr class="odd gradeX">
          <td align="right"  width="200">微信通知：</td>
          <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="ON_WX" value="1"  <?=$ON_WX?'checked':''?> />
            </div>
            <span class="help-block"> &raquo; 开启后,可以像站内信,邮件,短信一样给会员微信发通知 (会员需要关注公众号才能发送)<br>
            &raquo; 公众号账号变更后,会员需要重新关注才能发送 </span></td>
        </tr>
        <tr class="odd gradeX">
          <td align="right"  width="200">AppID：</td>
          <td align="left"><input name="mpWeixin_appid" type="text" class="form-control" value="<?=cadd($mpWeixin_appid)?>" /></td>
        </tr>
        <tr class="odd gradeX">
          <td align="right"  width="200">AppSecret：</td>
          <td align="left"><input name="mpWeixin_appsecret" type="text" class="form-control" value="<?=cadd($mpWeixin_appsecret)?>"  size="35" /></td>
        </tr>
        <tr class="odd gradeX">
          <td align="right"  width="200">token：</td>
          <td align="left"><input name="mpWeixin_token" type="text" class="form-control input-xmedium" value="<?=cadd($mpWeixin_token)?>" size="35" />
            <button type="button" class="btn btn-default" onClick="window.open('/public/AutoInput.php?typ=pw32&space=0&case=0&returnform=opener.document.xingao.mpWeixin_token.value','','width=100,height=100');" >自动生成 </button></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!---->
<div class="portlet">
  <div class="portlet-title">
    <div class="caption"><i class="icon-reorder"></i>对接清关公司</div>
    <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
  </div>
  <div class="portlet-body form" style="display: block;">
    <!--表单内容-->
    <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
      <tbody>
       <?php if($open_gd_mosuda){?>
        <tr class="odd gradeX">
          <td  width="200" rowspan="4" align="center"> 跨境翼<br>
            <a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="备案价格*物品数量*行邮税率%+加/减税率%=税费"> 计税公式 </a>
           <br><br>
            <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="ON_gd_mosuda" value="1"  <?=$ON_gd_mosuda?'checked':''?> />
            </div>
            </td>
        </tr>
        <tr class="odd gradeX">
          <td align="left">
         <input type="checkbox"  name="ON_gd_mosuda_apply" value="1"  <?=$ON_gd_mosuda_apply?'checked':''?> />
         会员可以申请备案
       
          </td>
        </tr>
         <tr class="odd gradeX">
          <td align="left">

         
加/减税率<input class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="在原商品税率的基础上多加或减少X%税<br>正数为多加,负数为减少 (不收税则填-100)" type="text" name="gd_mosuda_plusTaxes" value="<?=spr($gd_mosuda_plusTaxes,2,0)?>"/>%
        
          </td>
        </tr>
       <tr class="odd gradeX">
          <td align="left">
           <input class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="原寄国家简称" type="text" name="gd_mosuda_CountryCode" value="<?=cadd($gd_mosuda_CountryCode)?>"  size="10" style="margin-bottom:10px;"/>
            <input class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="口岸类型" type="text" name="gd_mosuda_ShopId" value="<?=cadd($gd_mosuda_ShopId)?>"  size="10" style="margin-bottom:10px;"/>

<input type="checkbox" name="gd_mosuda_record" value="1" <?=$gd_mosuda_record?'checked':''?>>备案接口
<!--只用于是否用备案接口,跟网站渠道是否备案无关-->
<br>

          <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="账号" type="text" name="gd_mosuda_username" value="<?=cadd($gd_mosuda_username)?>"  size="10" style="margin-bottom:10px;"/>
            <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="密码" type="text" name="gd_mosuda_password" value="<?=cadd($gd_mosuda_password)?>"  size="10" style="margin-bottom:10px;"/>
            <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="KEY" type="text" name="gd_mosuda_key" value="<?=cadd($gd_mosuda_key)?>"  size="40" style="margin-bottom:10px;"/>
            
			<span class="help-block">
            &raquo; 如果没有请先自行去<a href="http://www.mosuda.com/" target="_blank">官网申请</a>
            </span>             
            </td>
        </tr>
        <?php }?>
        
      </tbody>
    </table>
  </div>
</div>



          <!---->
        <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>对接物流公司</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                

                <tr align="center" class="odd gradeX">
                    <td  width="200" rowspan="4">
                    CJ<br><br>
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>"><input type="checkbox" class="toggle" name="on_cj" value="1"  <?=$ON_cj?'checked':''?> /></div>
                    
                    </td>
                    
                  </tr>
                <tr class="odd gradeX">
                  <td align="left">接口信息-地址方式获取(주소정제 정보)：
                  <br>

                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="接口账号" type="text" name="cj_juso_account" value="<?=cadd($cj_juso_account)?>"  size="30" style="margin-bottom:10px;"/>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="接口密码" type="text" name="cj_juso_password" value="<?=cadd($cj_juso_password)?>"  size="30" style="margin-bottom:10px;"/>
                  <br>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="顾客ID （고객ID）" type="text" name="cj_juso_clntnum" value="<?=cadd($cj_juso_clntnum)?>"  size="30" style="margin-bottom:10px;" placeholder="30XXXXXX"/>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="顾客管理交易代码（고객관리거래처코드）" type="text" name="cj_juso_clnmgmcustcd" value="<?=cadd($cj_juso_clnmgmcustcd)?>"  size="30" style="margin-bottom:10px;" placeholder="30XXXXXX"/>
                  <br>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="寄送原因:01一般,02退货" type="text" name="cj_p_prngdivcd" value="<?=cadd($cj_p_prngdivcd)?>"  size="5" style="margin-bottom:10px;"/>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="寄送方式:11集货,91派送" type="text" name="cj_p_cgsts" value="<?=cadd($cj_p_cgsts)?>"  size="5" style="margin-bottom:10px;"/>

                  <span class="help-block">此接口以收件地址方式获取面单其他数据；如果没有请先自行去<a href="https://www.doortodoor.co.kr" target="_blank">官网申请</a>；</span>
                  
                  
                  </td>
                </tr>
                <tr class="odd gradeX">
                  <td align="left">接口信息-邮编方式获取(OPENDB 개발)：
                  <br>

                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="接口账号" type="text" name="cj_opendb_account" value="<?=cadd($cj_opendb_account)?>"  size="30" style="margin-bottom:10px;"/>
                  
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="接口密码" type="text" name="cj_opendb_password" value="<?=cadd($cj_opendb_password)?>"  size="30" style="margin-bottom:10px;"/>
                  
                  <span class="help-block">此接口以收件邮编方式获取面单其他数据；申请时官方会提供这种账号；</span>
                  
                  
                  </td>
                </tr>
                
                 <tr class="odd gradeX">
                  <td align="left">站内自用：
                   <br>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="付款方式:01先付,02到付,03信用" type="text" name="cj_frt_dv_cd" value="<?=cadd($cj_frt_dv_cd)?>"  size="5" style="margin-bottom:10px;"/>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="包裹大小:01极小,02小,03中,04大,05特大" type="text" name="cj_box_type_cd" value="<?=cadd($cj_box_type_cd)?>"  size="5" style="margin-bottom:10px;"/>
                  <br>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="发货人名 (韩文)" type="text" name="cj_sendr_nm" value="<?=cadd($cj_sendr_nm)?>"  size="10" style="margin-bottom:10px;"/>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="发货人电话" type="text" name="cj_sendr_tel" value="<?=cadd($cj_sendr_tel)?>"  size="10" style="margin-bottom:10px;"/>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="发货人地址" type="text" name="cj_sendr_addr" value="<?=cadd($cj_sendr_addr)?>"  size="40" style="margin-bottom:10px;"/>
                  
                  
                  <span class="help-block">此栏信息不用于API，用于打印、导出等</span>
                  
                  
                  </td>
                </tr>
 
 
                 <tr align="center" class="odd gradeX">
                    <td  width="200" rowspan="4">
                    DHL
                    <br><br>
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>"><input type="checkbox" class="toggle" name="ON_dhl" value="1"  <?=$ON_dhl?'checked':''?> /></div>
                    </td>
                  </tr>
                <tr class="odd gradeX">
                  <td align="left">接口信息：
                   <br>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="username" type="text" name="dhl_username" value="<?=cadd($dhl_username)?>"  size="30" style="margin-bottom:10px;" autocomplete="off"/>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="password" type="text" name="dhl_password" value="<?=cadd($dhl_password)?>"  size="30" style="margin-bottom:10px;"/>
                  <br>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="DeliveryName" type="text" name="dhl_DeliveryName" value="<?=cadd($dhl_DeliveryName)?>"  size="30" style="margin-bottom:10px;"/>
                  <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="PortalId" type="text" name="dhl_PortalId" value="<?=cadd($dhl_PortalId)?>"  size="30" style="margin-bottom:10px;"/>
                  
                  <span class="help-block">如果没有请先自行去<a href="http://www.dhl.com/" target="_blank">官网申请</a>；</span>
                  
                  
                  </td>
                </tr>
                
                
                 <tr class="odd gradeX">
                  <td align="left">收件地址(An/To)：
                 <br>
                 <textarea name="dhl_ToAddress" rows="5" class="form-control"><?=cadd($dhl_ToAddress)?></textarea>
                 <span class="help-block">用于打印面单的收件仓库地址、导出等 (需要跟DHL平台上设置的一样)
                 
                 <br>
                 格式:<br>
                 收件人<br>
                 其他具体地址<br>
                 道路名称 道路号<br>
                 邮编 城市<br>

				</span>
                  </td>
                </tr>
                                  
                                 
                </tbody>
              </table>
            </div>
          </div>
          
        <!---->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>翻译</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                <tr class="odd gradeX">
                    <td align="right"  width="200">使用接口：</td>
                    <td align="left">
                    <select name="fanyi_type" class="form-control input-small">
                      <option value="auto" <?=$fanyi_type=='auto'?'selected':''?>>自动</option>
                      <option value="youdao" <?=$fanyi_type=='youdao'?'selected':''?>>有道</option>
                      <option value="baidu" <?=$fanyi_type=='baidu'?'selected':''?>>百度</option>
                    </select>
                      <span class="help-block">
                      &raquo;  翻译接口用于打印或导出时自动翻译其他语种 <br>
                      </span></td>
                  </tr>
                 <tr class="odd gradeX">
                    <td  width="200" align="right">有道翻译：</td>
                    <td align="left">
                    <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="接口账号" type="text" name="youdao_api_id" value="<?=cadd($youdao_api_id)?>"  size="30" style="margin-bottom:10px;" autocomplete="off"/>
                    <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="接口密钥" type="text" name="youdao_api_key" value="<?=cadd($youdao_api_key)?>"  size="30" style="margin-bottom:10px;"/>
                    
                      <span class="help-block">
                     &raquo;  <a href="http://fanyi.youdao.com/openapi?path=data-mode" target="_blank">申请接口账号</a><br>
                     &raquo;  只支持 英日韩法俄西 译到 中文；中文译到英语  <br>
                     &raquo;  缺点只支持以上语种互译；优点翻译效果比百度好；<br>
                     &raquo;  免费版请求频率限制为每小时1000次，超过限制会被封禁<br>

                      </span>                      </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">百度翻译：</td>
                    <td align="left">
                    <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="接口账号" type="text" name="baidu_api_id" value="<?=cadd($baidu_api_id)?>"  size="30" style="margin-bottom:10px;" autocomplete="off"/>
                    <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="接口密钥" type="text" name="baidu_api_key" value="<?=cadd($baidu_api_key)?>"  size="30" style="margin-bottom:10px;"/>

                      <span class="help-block">
                     &raquo;  <a href="http://api.fanyi.baidu.com/api/trans/product/index" target="_blank">申请接口账号</a><br>
                     &raquo;  每月翻译字符数低于200万时可免费 [<a href="http://api.fanyi.baidu.com/api/trans/product/apihelp" target="_blank">详细</a>]<br>
                     &raquo;  缺点翻译质量比有道差；优点支持多种语言互译；<br>
                     &raquo;  申请时如非必要，建议不要填写IP，方便以后如要更换服务器时不用再修改；<br>

                      </span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
         <!----> 
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>快捷登录会员</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  <tr class="odd gradeX">
                    <td align="center">QQ<br>
<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
<input type="checkbox" class="toggle" name="off_connect_qq" value="1"  <?=$off_connect_qq==1?'checked':''?> />
</div></td>
                    <td align="center">
审核模式 <a class="tooltips" data-container="body" data-placement="top" data-original-title="申请接口审核时必须开通该模式才能通过审核，通过审核后正式使用时必须关闭该模式"><i class="icon-info-sign"></i></a><br>
<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
<input type="checkbox" class="toggle" name="off_connect_qq_checked" value="1"  <?=$off_connect_qq_checked==1?'checked':''?> />
</div>
<br>
<a href="https://connect.qq.com/manage.html" target="_blank">申请接口</a>	
					
					</td>
                   
<td align="center">appid<br>
<input name="connect_qqid" type="text" class="form-control" value="<?=cadd($connect_qqid)?>" />
</td>
<td align="center">appkey<br>
<input name="connect_qqkey" type="text" class="form-control" value="<?=cadd($connect_qqkey)?>" size="35" />
</td>
                  </tr>
                  
                  
             <tr class="odd gradeX">
                    <td align="center">微信扫描登录<br>
<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
<input type="checkbox" class="toggle" name="off_connect_weixin" value="1"  <?=$off_connect_weixin?'checked':''?> />
</div>

</td>
                    <td align="center"><a href="https://open.weixin.qq.com/cgi-bin/frame?t=home/web_tmpl&lang=zh_CN" target="_blank">申请接口</a>  </td>
                   
<td align="center">appid<br>
<input name="connect_weixinid" type="text" class="form-control" value="<?=cadd($connect_weixinid)?>" />
</td>
<td align="center">appkey<br>
<input name="connect_weixinkey" type="text" class="form-control" value="<?=cadd($connect_weixinkey)?>"  size="35" />
</td>
                  </tr>
                
                                   
                  <tr class="odd gradeX">
                    <td align="center">支付宝登录<br>
<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
<input type="checkbox" class="toggle" name="off_connect_alipay" value="1"  <?=$off_connect_alipay?'checked':''?> />
</div></td>
                    <td align="center">
                    <a href="https://b.alipay.com/order/productDetail.htm?productId=2015090914994961 " target="_blank">申请接口</a>
                    
                   
                    </td>
                   
<td align="center">appid<br>
<input name="connect_alipayid" type="text" class="form-control" value="<?=cadd($connect_alipayid)?>" />
</td>
<td align="center">appkey<br>
<input name="connect_alipaykey" type="text" class="form-control" value="<?=cadd($connect_alipaykey)?>"  size="35" />
</td>
                </tr>
                  
                </tbody>
              </table>
            </div>
          </div>
          <!----> 
        <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>其他接口</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  
                  
                  
              <tr class="odd gradeX">
                    <td align="center"><a class=" popovers" data-trigger="hover" data-placement="top"  data-content="开放给第三方直接查询运单物流信息，如入驻快递100">外部查询运单</a><br>
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                    <input type="checkbox" class="toggle" name="ON_api_yundanStatus" value="1"  <?=$ON_api_yundanStatus?'checked':''?> />
                    </div>
                    </td>
                    <td align="center"></td>
                    <td colspan="2" align="center">
  key<br>
  <input name="api_yundanStatus_key" type="text" class="form-control input-xmedium" value="<?=cadd($api_yundanStatus_key)?>" size="35" />
  <button type="button" class="btn btn-default" onClick="window.open('/public/AutoInput.php?typ=pw32&space=0&case=0&returnform=opener.document.xingao.api_yundanStatus_key.value','','width=100,height=100');" >自动生成 </button>
  
<span class="help-block">后台说明文档有相关说明</span>
  
  
</td>
</tr>



                <!--已停用:采用兴奥接口-->
                <!--<tr class="odd gradeX">
                    <td  width="200" align="right">
                   物流查询接口
                    </td>
                    <td align="left">
                      <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="customer" type="text" name="APIcustomer" value="<?=cadd($APIcustomer)?>"  size="50" style="margin-bottom:10px;"/>
                      
                      <input class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="key" type="text" name="APIkey" value="<?=cadd($APIkey)?>"  size="50" style="margin-bottom:10px;"/>
					</td>
                  </tr>-->

                                
                </tbody>
              </table>
            </div>
          </div>
          <!---->
        </div>
         <!----> 
        <div class="tab-pane" id="tab_10">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>安全相关配置</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
               
                  
                  <tr class="odd gradeX">
                    <td  width="200" align="right" valign="top">禁止提交敏感字符：</td>
                    <td align="left">
                    
                    <textarea name="security_clear" rows="2" class="form-control"><?=cadd($security_clear)?>
</textarea>
                      <span class="help-block"> 
                      &raquo; 用英文“,”符号分开 (如不了解请勿乱添加，否则无法正常使用)<br>
                      &raquo; 默认已包含:select,outfile,union,delete,insert,update,replace,sleep,benchmark,load_file,create (不用重复添加)<br>

                      
                      </span>
                      
                      
                    </td>
                  </tr>
                  
                </tbody>
              </table>
            </div>
          </div>
          <!---->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>验证码</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  
                  <tr class="odd gradeX">
                    <td align="center" valign="middle">后台登录<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_code_managelogin" value="1" <?=$off_code_managelogin?'checked':''?> />
                      </div>
                      <span class="help-block">登录失败3次则显示验证码</span>
                      </td>
                      <td align="center" valign="middle">会员登录<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_code_login" value="1" <?=$off_code_login?'checked':''?> />
                      </div><span class="help-block">登录失败3次则显示验证码</span></td>
                    <td align="center" valign="middle">会员注册<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_code_reg" value="1" <?=$off_code_reg?'checked':''?> />
                      </div></td>
                    
                    <td align="center" valign="middle">会员留言<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_code_liuyan" value="1" <?=$off_code_liuyan?'checked':''?> />
                      </div></td>
                      </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="center" valign="middle">商品评论<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_code_shangpin_sp" value="1" <?=$off_code_shangpin_sp?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle">晒单评论<br>
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_code_shaidan_sp" value="1" <?=$off_code_shaidan_sp?'checked':''?> />
                      </div></td>
                    <td align="center" valign="middle"></td>
                    <td align="center" valign="middle"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <!---->
        </div>
        <!---->
        <div class="tab-pane" id="tab_12">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>后台设置</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <div class="daycat" align="left">
                <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                  <tbody>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">后台登录认证码：</td>
                    <td align="left"><input name="manage_login_yz" type="text" class="form-control input-medium" value="<?=cadd($manage_login_yz)?>"  /> <span class="help-block">防止某些用户密码太简单容易被入侵，可设置认证码,每个用户登录都需要输入。这样就算对方知道密码，不知道认证码也无法登录。<br>
</span></td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">后台登录失败次数限制：</td>
                    <td align="left"><input name="manage_login_limit" type="text" class="form-control input-xsmall" value="<?=cadd($manage_login_limit)?>" size="10" />
                      次 <span class="help-block">防止入侵登录,多次登录失败则禁止登录</span></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">后台登录失败锁定时间：</td>
                    <td align="left"><input name="manage_limit_time" type="text" class="form-control input-xsmall" value="<?=cadd($manage_limit_time)?>" size="10" />
                      分</td>
                  </tr>
                                     
                  <tr class="odd gradeX">
                    <td align="right"  width="200">自动弹出公告间隔：</td>
                    <td align="left"><input name="manage_prompt_time" type="text" class="form-control input-xsmall" value="<?=spr($manage_prompt_time)?>" size="5" />分
					</td>
                  </tr>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
		<!---->
        </div>
        
        
        <!----> 
        <div class="tab-pane" id="tab_11">
        
          
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>晒单</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">晒单按语种显示：</td>
                    <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="ON_shaidan_language" value="1" <?=$ON_shaidan_language?'checked':''?> />
                      </div>
                      <span class="help-block">如:中文版则只显示中文晒单;英文版只显示英文晒单;</span>
                      </td>
                  </tr>
                
                  <tr class="odd gradeX">
                    <td align="right"  width="200">晒单支持类型：</td>
                    <td align="left">
                    <input type="checkbox" name="shaidan_Types_0" value="1" <?=$shaidan_Types_0?'checked':''?>>站内晒单
                    &nbsp;
                    <input type="checkbox" name="shaidan_Types_1" value="1" <?=$shaidan_Types_1?'checked':''?>>站外晒单
                    </td>
                  </tr>

<tr class="odd gradeX">
<td  width="200" align="right" valign="top">站外晒单说明：</td>
<td align="left">
<?php 
//语言字段处理++
$field='shaidan_explain';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <textarea name="<?=$field.$language?>" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" rows="3"><?=cadd($val)?></textarea>
		<?php 
	}
}
?>
<span class="help-block"> 比如支持的网址,晒单方法等简单说明 (支持HTML代码)</span>
</td>
</tr>
                  
                </tbody>
              </table>
            </div>
          </div>
          
          
          <!---->
        
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>浮动客服</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                  
                                    <tr class="odd gradeX">
                                    	<td  width="200" align="right" valign="top">电脑版：</td>
                                    	<td align="left">
<?php 
//语言字段处理++
$field='cs';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <textarea name="<?=$field.$language?>" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" rows="10"><?=cadd($val)?></textarea>
		<?php 
	}
}
?>
<span class="help-block"> 只能修改号码和文字，不要修改代码，如果要多个客服号码则复制新的一行并修改号码即可 (支持HTML代码)</span>
                                    		
                                   		</td>
                                   	</tr>
                                    <tr class="odd gradeX">
                                    	<td  width="200" align="right" valign="top">手机版：</td>
                                    	<td align="left">
<?php 
//语言字段处理++
$field='cs_m';
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$joint=$field.$language; $val=$$joint;
		?>
        <textarea name="<?=$field.$language?>" class="form-control tooltips"  data-container="body" data-placement="top" data-original-title="<?=languageType($language)?>" rows="10"><?=cadd($val)?></textarea>
		<?php 
	}
}
?>
<span class="help-block"> 不支持QQ在线客服，只能修改号码和文字，不要修改代码，如果要多个客服号码则复制新的一行并修改号码即可 (支持HTML代码)</span>
                                    		
                                   		</td>
                                   	</tr>
                  
                </tbody>
              </table>
            </div>
          </div>
          
          <!---->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>水印与上传</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> 
              <!--表单内容-->
              <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                <tbody>
                
                  <tr class="odd gradeX">
                    <td align="right"  width="200">添加图片水印：</td>
                    <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_water" value="1" <?=$off_water==1?'checked':''?> />
                      </div>
                      </td>
                  </tr>
<tr class="odd gradeX">
                    <td align="right"  width="200">水印位置：</td>
                    <td align="left">
                    <select name="water_location" class="form-control input-medium">
                        <option value="0" <?php if(!$water_location){echo 'selected="selected"';}?>>随机</option>
                        <option value="1" <?php if($water_location==1){echo 'selected="selected"';}?>>顶端居左</option>
                        <option value="2" <?php if($water_location==2){echo 'selected="selected"';}?>>顶端居中</option>
                        <option value="3" <?php if($water_location==3){echo 'selected="selected"';}?>>顶端居右</option>
                        <option value="4" <?php if($water_location==4){echo 'selected="selected"';}?>>中部居左</option>
                        <option value="5" <?php if($water_location==5){echo 'selected="selected"';}?>>中部居中</option>
                        <option value="6" <?php if($water_location==6){echo 'selected="selected"';}?>>中部居右</option>
                        <option value="7" <?php if($water_location==7){echo 'selected="selected"';}?>>底端居左</option>
                        <option value="8" <?php if($water_location==8){echo 'selected="selected"';}?>>底端居中</option>
                        <option value="9" <?php if($water_location==9){echo 'selected="selected"';}?>>底端居右</option>
                   </select>
                        
                      
                      </td>
                  </tr>                  <tr class="odd gradeX">
                    <td align="right"  width="200">使用水印类型：</td>
                    <td align="left">
                    <select name="water_lx" class="form-control input-medium">
                        <option value="1" <?php if($water_lx==1){echo 'selected="selected"';}?>>图片水印</option>
                        <option value="2" <?php if($water_lx==2){echo 'selected="selected"';}?>>文字水印</option>
                   </select>
                      </td>
                  </tr>

                  
                   <tr class="odd gradeX">
                    <td align="right"  width="200">文字水印：</td>
                    <td align="left"><input class="form-control tooltips" data-container="body" data-placement="top" data-original-title="只支持中文、英文、数字" name="water_font" type="text" value="<?=cadd($water_font)?>"/>
                    
                      </td>
                  </tr>
            <tr class="odd gradeX">
                    <td align="right"  width="200">文字大小：</td>
                    <td align="left"><input name="water_font_size" type="text" class="form-control input-xsmall" value="<?=spr($water_font_size)?>"  size="5"/>点
                    
                      </td>
                  </tr>
            <tr class="odd gradeX">
                    <td align="right"  width="200">文字总长度：</td>
                    <td align="left"><input   class="form-control input-xsmall popovers" data-trigger="hover" data-placement="top"  data-content="设置为底端居右时：如果水印显示不全改大数字，如果不离右边相隔远则改小数字 (长度受【文字大小】和文字字数影响)" name="water_font_length" type="text" value="<?=spr($water_font_length)?>"  size="5"/>像素
                  </td>
                  </tr>
                  
            <tr class="odd gradeX">
                    <td align="right"  width="200">文字颜色：</td>
                    <td align="left">
                    <div class="input-group color colorpicker-default input-small" data-color="<?=cadd($water_font_color)?>" data-color-format="rgba">
                    <input type="text" class="form-control" value="<?=cadd($water_font_color)?>" readonly name="water_font_color"  style="width:90px;">
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i style="background-color: <?=cadd($water_font_color)?>;"></i>&nbsp;</button>
                    </span> </div>
                    
                
                    
                      </td>
                  </tr>
                     
                  <tr class="odd gradeX">
                    <td align="right"  width="200">图片水印：</td>
                    <td align="left"><input name="water_file" type="text" class="form-control" value="<?=cadd($water_file)?>"/>
                      <span class="help-block">最好用JPG格式，透明图片用GIF格式(不支持PNG)；请用FTP上传并填写地址，以/开头如：/images/water.jpg</span>
                      </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">图片透明度：</td>
                    <td align="left"><input name="water_tran" type="text" class="form-control input-xsmall" value="<?=cadd($water_tran)?>" size="5"/>%
                      </td>
                  </tr>
                       
      <tr>
                    <td colspan="2" height="20"></td>
                  </tr>
            
                  <tr class="odd gradeX">
                    <td align="right"  width="200">自动缩小大图片：</td>
                    <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="off_narrow" value="1" <?=$off_narrow==1?'checked':''?> />
                      </div>
                      <span class="help-block">刚拍出来照片如果直接上传，文件极大影响网速，可以缩小再存放</span>
                      </td>
                  </tr>
                  
                <tr class="odd gradeX">
                    <td align="right"  width="200">证件缩小尺寸：</td>
                    <td align="left">
                    宽<input name="certi_w" type="text" class="form-control input-xsmall" value="<?=cadd($certi_w)?>" size="10"/>px * 高<input name="certi_h" type="text" class="form-control input-xsmall" value="<?=cadd($certi_h)?>" size="10"/>px
                     <span class="help-block">如：身份证、税收扫描件等</span>
                      </td>
                  </tr>
                  
                <tr class="odd gradeX">
                    <td align="right"  width="200">其他图片缩小尺寸：</td>
                    <td align="left">
                    宽<input name="other_w" type="text" class="form-control input-xsmall" value="<?=cadd($other_w)?>" size="10"/>px * 高<input name="other_h" type="text" class="form-control input-xsmall" value="<?=cadd($other_h)?>" size="10"/>px
                     <span class="help-block">如：商品图片、文章图片等</span>
                      </td>
                  </tr>
                <tr>
                    <td height="20" colspan="2" ></td>
                  </tr>                            
                 <tr class="odd gradeX">
                    <td align="right"  width="200">上传图片大小限制：</td>
                    <td align="left"><input name="image_size" type="text" class="form-control input-xsmall" value="<?=cadd($image_size)?>" size="10"/>
                      KB (1024KB=1M)
                        <?=$up_ts?>
                          </td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">上传图片扩展名：</td>
                    <td align="left"><input name="image_ext" type="text" class="form-control" value="<?=cadd($image_ext)?>" />
                      <span class="help-block">用英文“|”符号分开</span></td>
                  </tr>
                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">上传文件大小限制：</td>
                    <td align="left"><input name="file_size" type="text" class="form-control input-xsmall" value="<?=cadd($file_size)?>" size="10"/>
                      KB (1024KB=1M)
                        <?=$up_ts?></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">上传文件扩展名：</td>
                    <td align="left"><input name="file_ext" type="text" class="form-control" value="<?=cadd($file_ext)?>" />
                      <span class="help-block">用英文“|”符号分开</span></td>
                  </tr>


                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">flash文件大小限制：</td>
                    <td align="left"><input name="flash_size" type="text" class="form-control input-xsmall" value="<?=cadd($flash_size)?>" size="10"/>
                      KB (1024KB=1M)
                        <?=$up_ts?></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">上传flash扩展名：</td>
                    <td align="left"><input name="flash_ext" type="text" class="form-control" value="<?=cadd($flash_ext)?>" />
                      <span class="help-block">用英文“|”符号分开</span></td>
                  </tr>


                  
                  <tr class="odd gradeX">
                    <td align="right"  width="200">媒体文件大小限制：</td>
                    <td align="left"><input name="media_size" type="text" class="form-control input-xsmall" value="<?=cadd($media_size)?>" size="10"/>
                      KB (1024KB=1M)
                        <?=$up_ts?></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td align="right"  width="200">上传媒体扩展名：</td>
                    <td align="left"><input name="media_ext" type="text" class="form-control" value="<?=cadd($media_ext)?>" />
                      <span class="help-block">用英文“|”符号分开</span></td>
                  </tr>
                  
                </tbody>
              </table>
            </div>
          </div>
          
          
        </div>
        
         <!---->        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">



      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled> <i class="icon-ok"></i> <?=$LG['submit']?> </button>
      <button type="button" class="btn btn-danger input-small" onClick="javascript:if(confirm('确认要恢复上次的配置吗?此操作不可恢复!'))location.href='save.php?lx=restore';"> <i class="icon-reply"></i> 恢复上次配置 </button>
      
      <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
      
<?=@ini_get('opcache.revalidate_freq')>0?'<font class="gray_prompt red2" >(服务器开启了缓存功能,提交/恢复后需要过'.@ini_get('opcache.revalidate_freq').'秒后才更新配置)</font>':'';?>      
      
    </div>
      </div>
      
    </div>
    
  </form>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
