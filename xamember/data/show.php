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
$headtitle=$LG['data.show_1'];//我的资料
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

$rs=FeData('member','*',"1=1 {$Mmy}");
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$Mtruename." ".$headtitle?>
        </a>  </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="tab-content">
    <div class="tab-pane active" id="tab_1">
      <div class="form">
        <div class="form-body">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i><?=$LG['data.show_2'];//主要资料?></div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> <!--表单内容-->
              
              <table class="table table-striped table-bordered table-hover" style="margin-bottom:0px;">
                <tbody>
                
                  <tr class="odd gradeX">
                    <td width="150" align="right"><?=$LG['data.show_3'];//会员类型?></td>
                    <td><?=$member_per[$Mgroupid]['groupname']?></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td width="150" align="right"><?=$LG['data.show_4'];//登录账号/ID?></td>
                    <td><?=cadd($rs['username'])?>
                      (
                      <?=$memberid_tpre?><?=cadd($rs['userid'])?>
                      ) </td>
                  </tr>
                  
                  <?php if($member_per[$Mgroupid]['off_settlement']||spr($rs['settlement_all_money'])!=0){?>                  <?php }?>
                  <tr class="odd gradeX">
                    <td width="150" align="right">email</td>
                    <td><?=substr_cut(cadd($rs['email']),$length=3)?></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td width="150" align="right"><?=$LG['data.show_5'];//手机?></td>
                    <td><?=substr_cut(cadd($rs['mobile']),$length=3)?></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td width="150" align="right"><?=$LG['data.show_6'];//登录情况?></td>
                    <td><?=$LG['data.show_36']?>
                      <?=cadd($rs['loginnum'])?>
                      <?=$LG['data.show_37']?>:
                      <?=DateYmd($rs['lasttime'],1)?>
                     &nbsp;&nbsp; IP:
                      <?=cadd($rs['lastip'])?>
                       <a href="../log/list.php" class="label label-sm label-default" style="color:#FFFFFF"><?=$LG['data.show_7'];//登录日志?></a></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td width="150" align="right"><?=$LG['data.show_8'];//注册情况?></td>
                    <td>
                   
				<?=DateYmd($rs['addtime'],1)?> (IP:<?=$rs['regip']?>)
                
                <strong> <?=$rs['tg_userid']?$LG['data.show_38'].substr_cut($rs['tg_username'],2).'('.$rs['tg_userid'].')'.$LG['data.show_39']:''?> </strong>
                      
                      </td>
                  </tr>
                  
                
                  <tr class="odd gradeX">
                    <td colspan="2" align="right"></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td width="150" align="right"><strong><?=$LG['CustomerService']//客服?></strong></td>
                    <td><?=CustomerService($rs['CustomerService'],2)?></td>
                  </tr>
                                  </tbody>
              </table>
            </div>
          </div>
          
          <!---->
          <?php if($off_api&&$rs['api']){?>
          <a name="api"></a>
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i>API</div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;"> <!--表单内容-->
              
              <table class="table table-striped table-bordered table-hover" style="margin-bottom:0px;">
                <tbody>
                  <tr class="odd gradeX">
                    <td width="150" align="right">API KEY</td>
                    <td><?=cadd($rs['api_key'])?></td>
                  </tr>
                  <tr class="odd gradeX">
                    <td width="150" align="right"><?=$LG['data.show_10'];//API 权限?></td>
                    <td>
                      <font class="<?=!$rs['api_yd_query']?'gray2':'red2';?>">
                      <?=$rs['api_yd_query']?$LG['data.show_40']:$LG['data.show_41'];?>
                      </font><br>
        
                      <font class="<?=!$rs['api_yd_add']?'gray2':'red2';?>">
                      <?=$rs['api_yd_add']?$LG['data.show_42']:$LG['data.show_43'];?>
                      </font>
					</td>
                  </tr>
                  <tr class="odd gradeX">
                    <td width="150" align="right"><?=$LG['data.show_11'];//对接说明?></td>
                    <td>
                       <a class="btn btn-default" href="DownloadApi.php" target="_blank"><?=$LG['data.show_12'];//API说明?></a>
					</td>
                  </tr>
                 
                </tbody>
              </table>
            </div>
          </div>
          <?php }?>
          <!---->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i><?=$LG['data.show_13'];//认证资料?></div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;">
            <!--表单内容-->
            
            <table class="table table-striped table-bordered table-hover" style="margin-bottom:0px;">
              <tbody>
              
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.truename'];//真实姓名?></td>
                <td><?=substr_cut($rs['truename'])?></td>
              </tr>
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.enname'];//英文名/拼音?></td>
                <td><?=substr_cut($rs['enname'])?></td>
              </tr>
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.gender'];//性别?></td>
                <td><?=Gender($rs['gender'])?></td>
              </tr>
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.birthday'];//生日?></td>
                <td><?=DateYmd($rs['birthday'],2)?></td>
              </tr>
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.shenfenhaoma'];//身份证号码?></td>
                <td><?=substr_cut($rs['shenfenhaoma'])?></td>
              </tr>
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.form_29'];//身份证正面?></td>
                <td><?=$rs['shenfenimg_z']?$LG['data.form_32']:$LG['data.form_33']?></td>
              </tr>
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.form_34'];//身份证背面?></td>
                <td><?=$rs['shenfenimg_b']?$LG['data.form_32']:$LG['data.form_33']?></td>
              </tr>
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.form_35'];//手持证件?></td>
                <td><?=$rs['handCert']?$LG['data.form_32']:$LG['data.form_33']?></td>
              </tr>
               <tr class="odd gradeX">
                <td width="150" align="right"></td>
                <td>
                  <a class="btn btn-default" href="/xamember/data/form.php?tab=4"><?=$LG['data.show_22'];//修改认证资料?></a>
                </td>
              </tr>
                             
                </tbody>
              
            </table>
          </div>
        </div>
        
         <!---->
            <div class="portlet">
            <div class="portlet-title">
              <div class="caption"><i class="icon-reorder"></i><?=$LG['data.form_2'];//基本资料?></div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
            </div>
            <div class="portlet-body form" style="display: block;">
            <!--表单内容-->
            
            <table class="table table-striped table-bordered table-hover" style="margin-bottom:0px;">
              <tbody>
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.qq'];//QQ?></td>
                <td><?=cadd($rs['qq'])?></td>
              </tr>
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.wx'];//微信?></td>
                <td><?=cadd($rs['wx'])?></td>
              </tr>
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.zip'];//邮编?></td>
                <td><?=cadd($rs['zip'])?></td>
              </tr>
              
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.store'];//网店?></td>
                <td><?=cadd($rs['store'])?></td>
              </tr>
              
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.form_8'];//头像?></td>
                <td><?=ImgAdd($rs['img'],1,200)?></td>
              </tr>
              <tr class="odd gradeX">
                <td width="150" align="right"><?=$LG['data.show_28'];//备注?></td>
                <td><?=TextareaToBr($rs['content'])?></td>
              </tr>
                </tbody>
              
            </table>
          </div>
        </div>
        <?php if(permissions('off_company','','member',1) ){?>
        <div class="portlet">
          <div class="portlet-title">
            <div class="caption"><i class="icon-reorder"></i><?=$LG['data.form_9'];//企业资料?></div>
            <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
          </div>
          <div class="portlet-body form" style="display: block;"> <!--表单内容-->
            
            <table class="table table-striped table-bordered table-hover" style="margin-bottom:0px;">
              <tbody>
                <tr class="odd gradeX">
                  <td width="150" align="right"><?=$LG['data.company_countries'];//公司所属国家?></td>
                  <td><?php Country($rs['company_countries'])?></td>
                </tr>
                <tr class="odd gradeX">
                  <td width="150" align="right"><?=$LG['data.company_tel'];//公司电话?></td>
                  <td><?=cadd($rs['company_tel'])?></td>
                </tr>
                <tr class="odd gradeX">
                  <td width="150" align="right"><?=$LG['data.company_name'];//公司名称?></td>
                  <td><?=cadd($rs['company_name'])?></td>
                </tr>
                <tr class="odd gradeX">
                  <td width="150" align="right"><?=$LG['data.form_10'];//公司执照?></td>
                  <td><?=ImgAdd($rs['company_license'],1,200)?></td>
                </tr>
                <tr class="odd gradeX">
                  <td width="150" align="right"><?=$LG['data.company_add'];//公司地址?></td>
                  <td><?=cadd($rs['company_add'])?></td>
                </tr>
                <tr class="odd gradeX">
                  <td width="150" align="right"><?=$LG['data.company_business'];//公司业务?></td>
                  <td><?=TextareaToBr($rs['company_business'])?></td>
                </tr>
              </tbody>
            </table>
            <?php }?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
