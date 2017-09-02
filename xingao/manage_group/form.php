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
$pervar='manage_ma';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="后台用户权限";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$groupid=par($_GET['groupid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$groupid){exit ("<script>alert('groupid{$LG['pptError']}');goBack();</script>");}
}
if($groupid)
{
	$rs=FeData('manage_group','*',"groupid='{$groupid}'");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("manage_group");

?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > 
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
        <small>
        <?=cadd($rs['groupname'])?>
        </small> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
  <input name="lx" type="hidden" value="<?=add($lx)?>">
  <input name="groupid" type="hidden" value="<?=$rs['groupid']?>">
  <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless">
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>部门/组/分类/等级名称</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <div class="form-group">
                    <label class="control-label col-md-2">名称</label>
                    <div class="col-md-10 has-error">
                      <input type="text" class="form-control"  maxlength="100" name="groupname" required value="<?=cadd($rs['groupname'])?>">
                    </div>
                  </div>
                  
                  <div class="form-group">
                  <label class="control-label col-md-2">排序</label>
                  <div class="col-md-10">
                    <input type="text" class="form-control input-xsmall"  name="myorder" value="<?=cadd($rs['myorder'])?>"><span class="help-block">越大越排前</span>
                  </div>
                </div>
                  
                  
                </div>
              </div>
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>高级管理权限</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                      <tr class="odd gradeX">
                        <td align="center"><strong class="red"><a class="tooltips" data-container="body" data-placement="top" data-original-title="拥有所有权限,下面单独的设置无效">最高权限</a></strong><br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="admin" value="1" <?=$rs['admin']?'checked':''?> />
                          </div>
                        </td>
                        <td align="center">系统设置<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="manage_sy" value="1" <?=$rs['manage_sy']?'checked':''?> />
                          </div></td>
                        <td align="center">数据库管理<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="manage_db" value="1" <?=$rs['manage_db']?'checked':''?> />
                          </div></td>
                        <td align="center">管理员管理<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="manage_ma" value="1" <?=$rs['manage_ma']?'checked':''?> />
                          </div></td>
                        <td align="center">
                        
                                                   只能看自己的会员<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="member_my" value="1" <?=$rs['member_my']?'checked':''?> />
                          </div>

</td>
                      </tr>
                      <tr class="odd gradeX">
                        <td align="center"></td>
                       <td align="center">会员组/费用设置<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="manage_me" value="1" <?=$rs['manage_me']?'checked':''?> />
                          </div></td>
                        <td align="center">汇率设置<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="manage_ex" value="1" <?=$rs['manage_ex']?'checked':''?> />
                          </div></td>
                        <td align="center">其他<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="manage_ot" value="1" <?=$rs['manage_ot']?'checked':''?> />
                          </div></td>
                        <td align="center">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!---->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>会员管理权限</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                      <tr class="odd gradeX">
                        <td align="center">修改/删除<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="member_ed" value="1" <?=$rs['member_ed']?'checked':''?> title="已包括 查看" />
                          </div></td>
                          <td align="center"><a class="tooltips" data-container="body" data-placement="top" data-original-title="查看&修改联系方式；查看余额&积分；审核实名认证；开通API；">高级管理</a><br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="member_cp" value="1" <?=$rs['member_cp']?'checked':''?> />
                          </div>
                         
                          </td>
                          <td align="center">查看联系方式<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="member_co" value="1" <?=$rs['member_co']?'checked':''?> />
                          </div>
                         
                          </td>
                        <td align="center">查看基本资料/审核<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="member_se" value="1" <?=$rs['member_se']?'checked':''?> />
                          </div></td>
                        
                        <td align="center">充值<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="member_re" value="1" <?=$rs['member_re']?'checked':''?> />
                          </div></td>
                      </tr>
                      <tr class="odd gradeX">
                        <td align="center">扣款<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="member_de" value="1" <?=$rs['member_de']?'checked':''?> />
                          </div></td>
                        <td align="center">加/扣分<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="member_in" value="1" <?=$rs['member_in']?'checked':''?> />
                          </div></td>
                       
                        <td align="center">发信/删信<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="member_le" value="1" <?=$rs['member_le']?'checked':''?> />
                          </div></td>
                           <td align="center">
                           
                           </td>
                        <td align="center">导出/其他<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="member_ot" value="1" <?=$rs['member_ot']?'checked':''?> />
                          </div></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!---->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>包裹管理权限</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                      <tr class="odd gradeX">
                        <td align="center">修改/删除<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="baoguo_ed" value="1" <?=$rs['baoguo_ed']?'checked':''?> />
                          </div></td>
                        <td align="center">查看<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="baoguo_se" value="1" <?=$rs['baoguo_se']?'checked':''?> />
                          </div></td>
                        <td align="center">入库/添加<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="baoguo_ad" value="1" <?=$rs['baoguo_ad']?'checked':''?> />
                          </div></td>
                        <td align="center">其他<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="baoguo_ot" value="1" <?=$rs['baoguo_ot']?'checked':''?> />
                          </div>
						   <span class="help-block">打印</span>
						   </td>
                      </tr>
                      
                    </tbody>
                  </table>
                </div>
              </div>
              <!---->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>运单管理权限</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                 
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                      <tr class="odd gradeX">
                        <td align="center">修改/删除<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="yundan_ed" value="1" <?=$rs['yundan_ed']?'checked':''?> />
                          </div></td>
                        <td align="center">查看<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="yundan_se" value="1" <?=$rs['yundan_se']?'checked':''?> />
                          </div></td>
                        <td align="center">增加运单<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="yundan_ad" value="1" <?=$rs['yundan_ad']?'checked':''?> />
                          </div></td>
                        <td align="center">批量修改<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="yundan_st" value="1" <?=$rs['yundan_st']?'checked':''?> />
                          </div></td>
                      </tr>
                      <tr class="odd gradeX">
                        <td align="center">算运费<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="yundan_fe" value="1" <?=$rs['yundan_fe']?'checked':''?> />
                          </div></td>
                       <td align="center">算税费<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="yundan_ta" value="1" <?=$rs['yundan_ta']?'checked':''?> />
                          </div></td>
                        <td align="center">快递导入<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="yundan_im" value="1" <?=$rs['yundan_im']?'checked':''?> />
                          </div></td>
                       <td align="center">导出<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="yundan_ex" value="1" <?=$rs['yundan_ex']?'checked':''?> />
                          </div></td>
                      </tr>
                      <tr class="odd gradeX">
                        <td align="center">打印<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="yundan_pr" value="1" <?=$rs['yundan_pr']?'checked':''?> />
                          </div></td>
                       <td align="center">扫描出库<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="yundan_sc" value="1" <?=$rs['yundan_sc']?'checked':''?> />
                          </div></td>
                        <td align="center">
                           </td>
                       <td align="center"><a class="tooltips" data-container="body" data-placement="top" data-original-title="HS/HG/快递单号 管理">其他</a><br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="yundan_ot" value="1" <?=$rs['yundan_ot']?'checked':''?> />
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
                  <div class="caption"><i class="icon-reorder"></i>代购管理权限</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                    
                      <tr class="odd gradeX">
                      <td align="center">
                      增加运单<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="daigou_ad" value="1" <?=$rs['daigou_ad']?'checked':''?> />
                          </div>
                          
                      
                          </td>
                       <td align="center">修改/删除<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="daigou_ed" value="1" <?=$rs['daigou_ed']?'checked':''?> />
                          </div></td>
                       <td align="center">采购<br>
                       	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="daigou_cg" value="1" <?=$rs['daigou_cg']?'checked':''?> />
                       		</div></td>
                       <td align="center">换货<br>
                       	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="daigou_hh" value="1" <?=$rs['daigou_hh']?'checked':''?> />
                       		</div></td>
                       <td align="center">查货<br>
                       	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="daigou_ch" value="1" <?=$rs['daigou_ch']?'checked':''?> />
                       		</div></td>
                        </tr>
                      <tr class="odd gradeX">
                        <td align="center">查看<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="daigou_se" value="1" <?=$rs['daigou_se']?'checked':''?> />
                          </div></td>
                        <td align="center">退货退款<br>
                       	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="daigou_th" value="1" <?=$rs['daigou_th']?'checked':''?> />
                       		</div></td>
                        <td align="center">增购数量<br>
                       	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="daigou_zg" value="1" <?=$rs['daigou_zg']?'checked':''?> />
                       		</div></td>
                        <td align="center">查看实际网址<br>
                       	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="daigou_ck" value="1" <?=$rs['daigou_ck']?'checked':''?> />
                       		</div></td>
                        <td align="center">导出<br>
                       	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="daigou_ex" value="1" <?=$rs['daigou_ex']?'checked':''?> />
                       		</div></td>
                      </tr>
                      <tr class="odd gradeX">
                        <td align="center">入库<br>
                        	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="daigou_inStorage" value="1" <?=$rs['daigou_inStorage']?'checked':''?> />
                       		</div></td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">其他<br>
                       	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="daigou_ot" value="1" <?=$rs['daigou_ot']?'checked':''?> />
                       		</div></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!---->
              
              
              
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>商城管理权限 (商品管理需要有‘信息/栏目’栏目权限)</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                    
                      <tr class="odd gradeX">
                      <td align="center">商品管理<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="mall" value="1" <?=$rs['mall']?'checked':''?> />
                          </div></td>
                       <td align="center">订单管理<br>
                       	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="mall_order" value="1" <?=$rs['mall_order']?'checked':''?> />
                       		</div></td>
                        </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!---->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>月结管理权限</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                    
                      <tr class="odd gradeX">
                      <td align="center">查看/导出<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="settlement_se" value="1" <?=$rs['settlement_se']?'checked':''?> />
                          </div></td>
                       <td align="center">生成账单/销账<br>
                       	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="settlement_ed" value="1" <?=$rs['settlement_ed']?'checked':''?> />
                       		</div></td>
                        </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!---->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>其他系统管理权限</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                 
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                      <tr class="odd gradeX">
                        
                        
                        <td align="center">取件<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="qujian" value="1" <?=$rs['qujian']?'checked':''?> />
                          </div></td>
                        <td align="center">理赔<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="lipei" value="1" <?=$rs['lipei']?'checked':''?> />
                          </div></td>
                        <td align="center">提现<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="tixian" value="1" <?=$rs['tixian']?'checked':''?> />
                          </div></td>
                        <td align="center">内部公告<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="notice" value="1" <?=$rs['notice']?'checked':''?> />
                          </div></td>
                    
                      <td align="center">晒单<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="shaidan" value="1" <?=$rs['shaidan']?'checked':''?> />
                          </div></td>
                         </tr>   
                      <tr class="odd gradeX">
                        
                       
                        <td align="center">评论<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="pinglun" value="1" <?=$rs['pinglun']?'checked':''?> />
                          </div></td>
                      <td align="center">信息/栏目<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="qita" value="1" <?=$rs['qita']?'checked':''?> />
                          </div>
                          
                        </td>
                      <td align="center">优惠券/折扣券<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="coupons" value="1" <?=$rs['coupons']?'checked':''?> />
                          </div></td>
                        <td align="center">分类等级(含航班/船运/托盘)<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="classify" value="1" <?=$rs['classify']?'checked':''?> />
                          </div></td>
                        <td align="center">日本清关资料<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="goodsdata" value="1" <?=$rs['goodsdata']?'checked':''?> />
                          </div></td>
                      </tr>
                      
                    </tbody>
                  </table>
                </div>
              </div>
              <!---->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>统计管理权限</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                      <tr class="odd gradeX">
                        
                        <td align="center">包裹<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="count_bg" value="1" <?=$rs['count_bg']?'checked':''?> />
                          </div></td>
                        <td align="center">运单<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="count_yd" value="1" <?=$rs['count_yd']?'checked':''?> />
                          </div></td>
                        <td align="center">代购<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="count_dg" value="1" <?=$rs['count_dg']?'checked':''?> />
                          </div></td>
                        <td align="center">会员数量<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="count_hy_sl" value="1" <?=$rs['count_hy_sl']?'checked':''?> />
                          </div></td>
                        <td align="center">财务核销<br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="count_hy_hx" value="1" <?=$rs['count_hy_hx']?'checked':''?> />
                          </div></td>
                      </tr>
                      <tr class="odd gradeX">
                        <td align="center"></td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center"><a class="tooltips" data-container="body" data-placement="top" data-original-title="流量统计、商城订单等">其他</a><br>
                          <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="count_ot" value="1" <?=$rs['count_ot']?'checked':''?> />
                          </div></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!---->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>只能管理该仓库的信息</strong> (不选则不限，对包裹、运单、商城、代购等用到仓库的有效)</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                 
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                      <tr class="odd gradeX">
  
<?php
$rs_warehouse=array($rs['warehouse']);
$i=0;
$query_wh="select name{$LT},whid from warehouse order by myorder desc,whid desc";
$sql_wh=$xingao->query($query_wh);
while($wh=$sql_wh->fetch_array())
{
	$i++;
?>
<td align="center"><?=cadd($wh['name'.$LT])?><br>
    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
    <input type="checkbox" class="toggle" name="warehouse[]" value="<?=$wh['whid']?>" <?=array_intersect($rs_warehouse,array($wh['whid']))?'checked':''?> />
    </div>
</td>
<?php	
	if($i==4){echo '</tr><tr class="odd gradeX">';$i=0;}		
}
?>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!---->
            </div>
          </div>
        </div>        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">





      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
      <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
    </div>
      </div>
      
    </div>
    
  </form>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
