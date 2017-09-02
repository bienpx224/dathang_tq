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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="后台管理";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
?> 

        <div class="page_ny">
         <!-- BEGIN PAGE CONTENT-->    
               
         <div class="portlet">
               <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>常用统计</div>
                  <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="javascript:;" class="reload" onClick="javascript:window.location.href=window.location.href;"></a>
                       
                     </div>
               </div>
               <div class="portlet-body">
			   <?php if($off_baoguo){?>
                  <?php if(permissions('baoguo_ed,baoguo_se','','manage',1) ){?>
				   <a href="/xingao/baoguo/list.php?status=<?=$CN_zhi='kuwai'?>" class="icon-btn">
                     <i class="icon-dropbox"></i>
                     <div>未入库包裹</div>
                     <?=$bgnum_status_kuwai=CountNum($CN_table='baoguo',$CN_field='',$CN_zhi='',$CN_where="and status in (0,1) and ware=0 {$Xwh}",$CN_userid='',$CN_color='default');?>
                   </a>
                   
                  
				   <a href="/xingao/baoguo/list.php?status=<?=$CN_zhi='ruku'?>&so=1&other=op_1" class="icon-btn">
                     <i class="icon-dropbox"></i>
                     <div>待操作包裹</div>
					<?php 
                    $bgnum_op=baoguo_num_op();
                    if($bgnum_op){echo '<span class="badge badge-warning">'.$bgnum_op.'</span>';}
                    ?>
                   </a>
				  
					  <?php if($ON_ware){?>
						  <a href="/xingao/baoguo/list.php?status=ware&orderby=ware_time&orderlx=desc" class="icon-btn">
							 <i class="icon-dropbox"></i>
							 <div>今天仓储</div>
							 <?=CountNum($CN_table='baoguo',$CN_field='ware',$CN_zhi=1,$CN_where=" and ware_time>='".strtotime(date('Y-m-d')." 00:00:00")."' {$Xwh}",$CN_userid='',$CN_color='important');?>
						  </a>
					 <?php }?>
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				 <?php }?>
			<?php }?>  
             
                 <?php if(permissions('yundan_ed,yundan_se','','manage',1) ){?>
				  <a href="/xingao/yundan/list.php?status=<?=$CN_zhi=0?>" class="icon-btn">
                     <i class="icon-plane"></i>
                     <div>待审核运单</div>
				     <?=$ydnum_status_0=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi,$CN_where=$Xwh.whereCS(),$CN_userid='',$CN_color='warning');?>
				  </a>
				  
                  <a href="/xingao/yundan/list.php?status=<?=$CN_zhi='calc_fee'?>" class="icon-btn">
                     <i class="icon-plane"></i>
                     <div>待计费运单</div>
                    <?=$ydnum_status_calc_fee=CountNum($CN_table='yundan',$CN_field='',$CN_zhi='calc_fee',$CN_where=" and money=0 and pay=0 and status>1 and status<5 {$Xwh} ".whereCS(),$CN_userid='',$CN_color='warning');?>
                  </a>
				  
                  <a href="/xingao/yundan/list.php?status=<?=$CN_zhi=4?>" class="icon-btn">
                     <i class="icon-plane"></i>
                     <div>待出库运单</div>
                    <?=$ydnum_status_4=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi,$CN_where=$Xwh.whereCS(),$CN_userid='',$CN_color='warning');?>
                  </a>
                

					<?php }?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				   
             <?php if($member_reg_sh&&permissions('member_ed,member_se','','manage',1)){?>
                  <a href="/xingao/member/list.php?so=1&checked=<?php echo $CN_zhi=0;?>" class="icon-btn">
                     <i class="icon-user"></i>
                     <div>待审会员</div>
                     <?=CountNum($table='member',$field='checked',$CN_zhi,$where='',$userid='',$color='info');?>
                  </a>
                  
                  <a href="/xingao/member/list.php" class="icon-btn">
                     <i class="icon-user"></i>
                     <div>今天新会员</div>
                     <?=CountNum($table='member',$field='',$CN_zhi='',$where='and addtime>='.strtotime(date('Y-m-d')." 00:00:00"),$userid='',$color='success');?>
                  </a>
            <?php }?>
            
                  
            <?php if($off_certification&&permissions('member_ed,member_se','','manage',1)){?>
                  <a href="/xingao/member/list.php?so=1&certification=2" class="icon-btn">
                     <i class="icon-user"></i>
                     <div>待审实名</div>
                     <?=CountNum($table='member',$field='certification',$CN_zhi=0,$where=' and certification_for=1',$userid='',$color='info');?>
                  </a>
           <?php }?>
                  
                  
                  
                  
                  
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                  <?php if( permissions('mall_order','','manage',1)  && $off_mall){?>
				  <a href="/xingao/mall_order/list.php?so=1&pay=<?=$CN_zhi=1?>&status=0" class="icon-btn">
                     <i class="icon-shopping-cart"></i>
                     <div>待处理订单</div>
					 <?=CountNum($CN_table='mall_order',$CN_field='pay',$CN_zhi,$CN_where=" and status=0 {$Xwh}",$CN_userid='',$CN_color='important');?>
                  </a>
				  <?php }?>
				
                  <?php if( permissions('mall','','manage',1)  && $off_mall){?>
                 
                  <a href="/xingao/mall/list.php?so=1&checked=<?=$CN_zhi=1?>&classid=0&attr=less" class="icon-btn">
                     <i class="icon-shopping-cart"></i>
                     <div>低库存商品</div>
                     <?=CountNum($CN_table='mall',$CN_field='checked',$CN_zhi,$CN_where=" and number<='10'  {$Xwh}",$CN_userid='',$CN_color='info');?>
                  </a>
				  <?php }?>
				
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <!---->
                   
				  <?php if(permissions('daigou_ed,daigou_cg','','manage',1) &&$ON_daigou){?>
                  <a href="/xingao/daigou/list.php?so=1&status=0" class="icon-btn">
                     <i class="icon-retweet"></i>
                     <div>待审核 代购</div>
                    <?=CountNum($CN_table='daigou',$CN_field='',$CN_zhi='',$CN_where=" and status in (0)",$CN_userid='',$CN_color='warning');
?>
                  </a>
                  <?php }?>
                 
				  <?php if(permissions('daigou_ed,daigou_cg','','manage',1) &&$ON_daigou){?>
                  <a href="/xingao/daigou/list.php?so=1&status=cg" class="icon-btn">
                     <i class="icon-retweet"></i>
                     <div>待采购 代购</div>
                    <?=CountNum($CN_table='daigou',$CN_field='',$CN_zhi='',$CN_where=" and status in (3,5)",$CN_userid='',$CN_color='warning');
?>
                  </a>
                  <?php }?>
                  
				  <?php if(permissions('daigou_th,daigou_zg,daigou_hh,daigou_ch','','manage',1) &&$ON_daigou){?>
                  <a href="/xingao/daigou/list.php?so=1&status=memberStatus" class="icon-btn">
                     <i class="icon-retweet"></i>
                     <div>待操作 代购</div>
                    <?=CountNum($CN_table='daigou',$CN_field='',$CN_zhi='',$CN_where=" and dgid in (select dgid from daigou_goods where memberStatus<>'0') ",$CN_userid='',$CN_color='warning');?>
                  </a>
                  <?php }?>
                  <!---->

                   <?php if(permissions('lipei','','manage',1) ){?>
                  <a href="/xingao/lipei/list.php?so=1&status=<?php echo $CN_zhi=0;?>" class="icon-btn">
                     <i class="icon-money"></i>
                     <div>理赔</div>
                    <?=CountNum($CN_table='lipei',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid='',$CN_color='warning');?>
                  </a>
                  <?php }?>
                  <!---->

                  <a href="/xingao/msg/list.php" class="icon-btn">
                     <i class="icon-comments-alt"></i>
                     <div>留言</div>
                    <?=CountNum($table='msg',$field='',$CN_zhi='',$where='and status>10',$userid='',$color='info');?>
                  </a>
                  <!---->

                   <?php if(permissions('qujian','','manage',1) ){?>
                  <a href="/xingao/qujian/list.php?so=1&status=<?php echo $CN_zhi=0;?>" class="icon-btn">
                     <i class="icon-share"></i>
                     <div>送件</div>
                    <?=$qjnum_status_0=CountNum($CN_table='qujian',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid='',$CN_color='warning');?>
                     
                  </a>
                  <?php }?>
                  <!---->
                  <?php if(permissions('tixian','','manage',1) ){?>
                  <a href="/xingao/tixian/list.php?so=1&status=<?php echo $CN_zhi=1;?>" class="icon-btn">
                     <i class="icon-credit-card"></i>
                     <div>代付</div>
                     <?=$txnum_status_0=CountNum($CN_table='tixian',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid='',$CN_color='warning');?>
                  </a>
                   <?php }?>
                  <!---->
                  <?php if(permissions('shaidan','','manage',1) &&$off_shaidan){?>
                  <a href="/xingao/shaidan/list.php?so=1&checked=<?php echo $CN_zhi=0;?>" class="icon-btn">
                     <i class="icon-star"></i>
                     <div>晒单</div>
                    <?=$sdnum_status_0=CountNum($CN_table='shaidan',$CN_field='checked',$CN_zhi,$CN_where='',$CN_userid='',$CN_color='warning');?>
                  </a>
                  <?php }?>
                  <!---->
                  <?php if(permissions('pinglun','','manage',1) ){?>
                  <a href="/xingao/comments/list.php?so=1&checked=<?php echo $CN_zhi=0;?>" class="icon-btn">
                     <i class="icon-thumbs-up"></i>
                     <div>评论</div>
                    <?=$cmnum_status_0=CountNum($CN_table='comments',$CN_field='checked',$CN_zhi,$CN_where="and reply_userid='0'",$CN_userid='',$CN_color='warning');?>
                  </a>
                  <?php }?>
                  <!---->
               </div>
            </div>
   
            
<!--内部公告-开始-->           
<?php 
//过期更新
if (update_time('notice','-30 minutes'))//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds
{
	$xingao->query("update notice set checked='0' where checked=1 and duetime>0 and duetime<".time()."");
}
?>
            <div class="portlet">
                  <div class="portlet-title">
                     <div class="caption"><i class="icon-cogs"></i>内部公告</div>
                     <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="javascript:;" class="reload"  onClick="javascript:window.location.href=window.location.href;"></a>
                     </div>
                  </div>
                  <div class="portlet-body">
                     <div><!-- class="table-responsive"-->
                        <table class="table  table-hover">
                           <tbody>
                           <tr>
<?php 
$notice_lx=par($_GET['notice_lx']);
$noid=par($_GET['noid']);
if(par($_GET['notice_lx'])=='status2'&&$noid)
{
	$xingao->query("update notice set status='2' where noid='{$noid}' and to_groupid in (0,{$Xgroupid})");
	SQLError();
}

$i=0;
$order=' order by level desc,edittime desc,status asc,checked desc  limit 30';//默认排序
$query="select * from notice where checked='1' and to_groupid in (0,{$Xgroupid}) and status in (0,1) {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$i++;
?>
	<td>
        
        <font class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($manage_per[$rs['groupid']]['groupname']).' 在'.DateYmd($rs['edittime']).'时发送'?>"  style="margin-right:10px;">
		 <?php
         echo $i.'. ';
		 Modals(cadd($rs['content']),cadd($rs['title']),$rs['edittime'],'content'.$rs['noid'],$count=0,$html=0,$link_name=leng($rs['title'],50))
         ?>
        </font>
        <?=Notice_Level($rs['level'])?> 
        
        <?php if(spr($rs['status'])==1){?>
       	 <a href="?noid=<?=$rs['noid']?>&notice_lx=status2" class="btn btn-xs btn-info" onClick="return confirm('确认要设置为已处理吗?\r<?=cadd($rs['title'])?>');" style="margin-left:10px;">设为已处理</a>
		<?php }?>
        
	</td>
    
<?php
	if($i%2==0){echo "</tr><tr>";}
}?>
                             </tr>
                             
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>

<!--内部公告-结束-->   

        
            
            <?php if(permissions('','','manage',1) ){?>
            <div class="portlet">
                  <div class="portlet-title">
                     <div class="caption"><i class="icon-cogs"></i>服务器</div>
                     <div class="tools">
                        <a href="javascript:;" class="expand"></a>
                        <a href="javascript:;" class="reload"  onClick="javascript:window.location.href=window.location.href;"></a>
                     </div>
                  </div>
                  <div class="portlet-body" style="display: none;">
                     <div><!-- class="table-responsive"-->
                        <table class="table table-hover">
                           
                           <tbody>
                            <tr>
                                 <td>转运系统：<a href="http://www.xingaowl.com" target="_blank"><?php echo $jishu.$banben;?></a></td>
                                 <td>&nbsp;</td>
                                 <td>运行方式： <?=$ON_demo?'演示站保密':ucwords(php_sapi_name());?></td>
                                 <td>&nbsp;</td>
                                 <td>最大可用内存：<?=$ON_demo?'演示站保密':@get_cfg_var("memory_limit");?></td>
                                 <td>&nbsp;</td>
                             </tr>
                              
                              <tr>
                                 <td>操作系统：
								
                                 <?php if($ON_demo){echo '演示站保密';}else{$os = explode(" ", php_uname()); echo $os[0]; echo "&nbsp;&nbsp;";
 if ($os[0] =="Windows") {echo "内核版本：". php_uname('r');} else {echo "内核版本：".$os[2];}}?></td>
                                 <td>&nbsp;</td>
                                 <td>GD 库：<?php if($ON_demo){echo '演示站保密';}else{echo function_exists(imageline)?'支持 ':'不支持 (无法处理图片及显示验证码)';}?></td>
                                 <td>&nbsp;</td>
                                 <td><font title="这2个值要小于‘最大可用内存’">最大文件上件：</font> <font title="upload_max_filesize"><?php if($ON_demo){echo '演示站保密';}else{echo  @get_cfg_var("file_uploads")?@get_cfg_var("upload_max_filesize") : $error;}?></font> / <font title="post_max_size"> <?=$ON_demo?'演示站保密':@get_cfg_var("post_max_size")?></font></td>
                                 <td>&nbsp;</td>
                              </tr>
                              <tr>
                                 <td>解译引擎：<?=$ON_demo?'演示站保密':$_SERVER["SERVER_SOFTWARE"]; ?></td>
                                 <td>&nbsp;</td>
                                 <td>OpenSSL：<?php if($ON_demo){echo '演示站保密';}else{ echo @function_exists(openssl_open)?'支持':'不支持 (无法在线充值)';}?></td>
                                 <td>&nbsp;</td>
                                 <td>最大执行时间：<?php if($ON_demo){echo '演示站保密';}else{ echo ini_get("max_execution_time")."秒";}?></td>
                                 <td>&nbsp;</td>
                              </tr>
                              <tr>
                                 <td>PHP版本：<?=$ON_demo?'演示站保密':PHP_VERSION;?></td>
                                 <td>&nbsp;</td>
                                 <td>SMTP支持：<?php if($ON_demo){echo '演示站保密';}else{ echo @get_cfg_var("SMTP")?'支持' : '不支持 (无法发邮件)';}?></td>
                                 <td>&nbsp;</td>
                                 <td>SESSION超时：<?=$ON_demo?'演示站保密':@spr(get_cfg_var("session.gc_maxlifetime")/60).'分种';?></td>
                                 <td>&nbsp;</td>
                              </tr>
                              <tr>
                                 <td>MYSQL版本： 
<?php 
if($ON_demo){echo '演示站保密';}else{
	$fe=mysqli_fetch_array($xingao->query("SELECT VERSION()"));
	echo $fe[0];
}?>

</td>
                                 <td>&nbsp;</td>
                                 <td>PHP_ZIP：
								 <?php 
if($ON_demo){echo '演示站保密';}else{
	if (!stristr($_SERVER["HTTP_ACCEPT_ENCODING"],'gzip'))
	 {echo "不支持 (无法导出身份证)";}
	 else
	 {echo "支持";}
}
	 ?>
	 </td>
                                 <td>&nbsp;</td>
                                 <td>被禁用的函数：
<font class="xa_newline">
<?php if($ON_demo){echo '演示站保密';}else{
$disused = @get_cfg_var("disable_functions")?"1":"0"; 
if($disused =="1"){echo @get_cfg_var("disable_functions");} else {echo "None";}}
?></font>
</td>
                                 <td>&nbsp;</td>
                              </tr>
                              <tr>
                              	<td>当前时间： <?=gmdate("Y年n月j日 H:i:s",time()+8*3600)?> &nbsp;(<?=date_default_timezone_get()=='PRC'?'北京时区':'非北京时区'?>)</td>
                              	<td>&nbsp;</td>
                              	<td>Zend OPcache缓存：<?=$ON_demo?'演示站保密':@spr(ini_get("opcache.revalidate_freq")).'秒';?></td>
                              	<td>&nbsp;</td>
                              	<td>您的IP：<?=GetIP()?></td>
                              	<td>&nbsp;</td>
                           	   </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            <?php }?> 
            
             </div>   
                     

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
