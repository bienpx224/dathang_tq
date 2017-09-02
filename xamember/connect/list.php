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
$headtitle=$LG['connect.headtitle'];
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
      <h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
      
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="portlet tabbable">
    <div class="portlet-body" style="padding:10px;"> 
      <!--搜索-->
      
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center"><?=$LG['connect.platform']//平台?></th>
              <th align="center"><?=$LG['connect.name']//账号/名称?></th>
              <th align="center"><?=$LG['connect.binding']//绑定时间?></th>
              <th align="center"><?=$LG['connect.lastTime']//上次登录?></th>
              <th align="center"><?=$LG['connect.loginNumber']//登录次数?></th>
              <th align="center"><?=$LG['op']//操作?></th>
            
            </tr>
          </thead>
          <tbody>
<?php
//qq快捷登录---------------------------------------------------------------------------------------------------
if($off_connect_qq)
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/api/login/qq/qqConnectAPI.php');
	$query="select * from member_connect where apptype='qq'  {$Mmy} order by id desc";
	$sql=$xingao->query($query);
	
	//有绑定
	while($rs=$sql->fetch_array())
	{
		$qc = new QC($rs['bindtoken'],$rs['bindkey']);
		$arr = $qc->get_user_info();//该会员的资料
?>
            <tr class="odd gradeX">
              <td align="center">QQ</td>
              <td align="center"><img src='<?=$arr['figureurl']?>' width="30"> <?=$arr["nickname"]?></td>
              <td align="center"><?=DateYmd($rs['bindtime'],1)?></td>
              <td align="center"><?=DateYmd($rs['lasttime'],1)?></td>
              <td align="center"><?=$rs['loginnum']?></td>
              <td align="center">
             
	<?php
    if($rs['id'])
    {
		echo '<a href="save.php?lx=del&id='.$rs['id'].'" class="btn btn-xs btn-danger"  onclick="return confirm(\''.$LG['connect.pptList1'].'\');"> <i class="icon-remove"></i> '.$LG['connect.pptList2'].'</a>';
    }
    ?>
    <a href="/api/login/qq/" class="btn btn-xs btn-info"  target="_blank" ><i class="icon-edit"></i> <?=$LG['connect.btnAdd']//添加号码?></a>
</td>
</tr>
<?php
	}
	$rc=mysqli_affected_rows($xingao);
	
	//没有绑定过
	if($rc<=0)
	{
	?>
            <tr class="odd gradeX">
              <td align="center">QQ</td>
              <td align="center"><?=$LG['connect.unbounded']//未绑定?></td>
              <td align="center"></td>
              <td align="center"></td>
              <td align="center"></td>
              <td align="center">
                <a href="/api/login/qq/" class="btn btn-xs btn-info"  target="_blank" ><i class="icon-edit"></i> <?=$LG['connect.btnBinding']//绑定号码?></a>
            </td>
            </tr>
          
	<?php
	}
}
?>
<!--=============================================-->


<?php
//微信快捷登录---------------------------------------------------------------------------------------------------
if($off_connect_weixin)
{
	$query="select * from member_connect where apptype='weixin'  {$Mmy} order by id desc";
	$sql=$xingao->query($query);
	
	//有绑定
	while($rs=$sql->fetch_array())
	{
		$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$rs['bindtoken'].'&openid='.$rs['bindkey'].'&lang=zh_CN';
	    $arr =send_get($url);//获取微信的资料
?>
            <tr class="odd gradeX">
              <td align="center"><?=$LG['connect.wx']//微信?></td>
              <td align="center"><img src='<?=$arr['headimgurl']?>' width="30"> <?=$arr['nickname']?></td>
              <td align="center"><?=DateYmd($rs['bindtime'],1)?></td>
              <td align="center"><?=DateYmd($rs['lasttime'],1)?></td>
              <td align="center"><?=$rs['loginnum']?></td>
              <td align="center">
             
	<?php
    if($rs['id'])
    {
		echo '<a href="save.php?lx=del&id='.$rs['id'].'" class="btn btn-xs btn-danger"  onclick="return confirm(\''.$LG['connect.pptList1'].'\');"> <i class="icon-remove"></i> '.$LG['connect.pptList2'].'</a>';
    }
    ?>
    <a href="/api/login/weixin/" class="btn btn-xs btn-info"  target="_blank" ><i class="icon-edit"></i> <?=$LG['connect.btnAdd']//添加号码?></a>
</td>
</tr>
<?php
	}
	$rc=mysqli_affected_rows($xingao);
	
	//没有绑定过
	if($rc<=0)
	{
	?>
            <tr class="odd gradeX">
              <td align="center"><?=$LG['connect.wx']//微信?></td>
              <td align="center"><?=$LG['connect.unbounded']//未绑定?></td>
              <td align="center"></td>
              <td align="center"></td>
              <td align="center"></td>
              <td align="center">
                <a href="/api/login/weixin/" class="btn btn-xs btn-info"  target="_blank" ><i class="icon-edit"></i> <?=$LG['connect.btnBinding']//绑定号码?></a>
            </td>
            </tr>
          
	<?php
	}
}
?>




<!--=============================================-->
<?php
//alipay支付宝快捷登录---------------------------------------------------------------------------------------------------
if($off_connect_alipay)
{
	$query="select * from member_connect where apptype='alipay'  {$Mmy} order by id desc";
	$sql=$xingao->query($query);
	
	//有绑定
	while($rs=$sql->fetch_array())
	{
?>
            <tr class="odd gradeX">
              <td align="center"><?=$LG['connect.alipay']//支付宝?></td>
              <td align="center"><?=$rs["bindtoken"]?></td>
              <td align="center"><?=DateYmd($rs['bindtime'],1)?></td>
              <td align="center"><?=DateYmd($rs['lasttime'],1)?></td>
              <td align="center"><?=$rs['loginnum']?></td>
              <td align="center">
             
	<?php
    if($rs['id'])
    {
		echo '<a href="save.php?lx=del&id='.$rs['id'].'" class="btn btn-xs btn-danger"  onclick="return confirm(\''.$LG['connect.pptList1'].'\');"> <i class="icon-remove"></i> '.$LG['connect.pptList2'].'</a>';
    }
    ?>
    <a href="/api/login/alipay/" class="btn btn-xs btn-info"  target="_blank" ><i class="icon-edit"></i> <?=$LG['connect.btnBinding']//绑定号码?></a>
</td>
</tr>
<?php
	}
	$rc=mysqli_affected_rows($xingao);
	
	//没有绑定过
	if($rc<=0)
	{
	?>
            <tr class="odd gradeX">
              <td align="center"><?=$LG['connect.btnBinding']//绑定号码?></td>
              <td align="center"><?=$LG['connect.alipay']//支付宝?></td>
              <td align="center"></td>
              <td align="center"></td>
              <td align="center"></td>
              <td align="center">
                <a href="/api/login/alipay/" class="btn btn-xs btn-info"  target="_blank" ><i class="icon-edit"></i> <?=$LG['connect.btnBinding']//绑定号码?></a>
            </td>
            </tr>
          
	<?php
	}
	
	
}
?>
          </tbody>
        </table>
    </div>
    <!--表格内容结束--> 
    
  </div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
