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
$pervar='qujian';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="打印上门取件";
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
?>
<!--分页打印后，最后总是打印一张空白页，可能是浏览器问题未找到原因-->
<script language="javascript" src="/js/jquery.jqprint.js"></script>
<script type="text/javascript"> 
$(function(){ 
$("#print").click(function(){ 
$(".my_show").jqprint(); 
}) 
}); 
</script> 

<script language="JavaScript">   
    var hkey_root,hkey_path,hkey_key
    hkey_root="HKEY_CURRENT_USER"
hkey_path=" Explorer\\PageSetup\\"
//设置网页打印的页眉页脚为空 
try{
var RegWsh = new ActiveXObject("WScript.Shell")
hkey_key="header" 
RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"")
hkey_key="footer"
RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"")
}catch(e){}
   </script>
   
<object id="WebBrowser" classid=CLSID:8856F961-340A-11D0-A96B-00C04FD705A2 height="0" width="0"></object> 
<style media="print" type="text/css"> 
.Noprint{display:none;} 
.PageNext{page-break-after: always;} 
</style>

<div class="my_show" align="center"> 
<?php 
//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$status=par($_POST['status']);
$qjid=par(ToStr($_REQUEST['qjid']));
$s_id=par($_POST['s_id']);
$b_id=par($_POST['b_id']);
$s_addtime=par($_POST['s_addtime']);
$b_addtime=par($_POST['b_addtime']);
$s_qjdate=par($_POST['s_qjdate']);
$b_qjdate=par($_POST['b_qjdate']);

if($qjid){$lx='tj';}

if($lx=="tj")
{
	//打印选项--------------------------------------------------------------------------------------
	if (!$qjid&&!CheckEmpty($status)&&!$s_addtime&&!$b_addtime&&!$s_qjdate&&!$b_qjdate&&!$s_id&&!$b_id)
	{
		exit ("<script>alert('至少要填写/选择一种打印选项！');goBack();</script>");
	}

	$where=" where 1=1 ";
	
	if(CheckEmpty($status)){$where.=" and status='{$status}'";}
	
	if ($qjid)
	{
		$where.=" and qjid in (".$qjid.")";
	}

	if($s_id){$where.=" and qjid>='".$s_id."'";}
	if($b_id){$where.=" and qjid<='".$b_id."'";}

	if($s_addtime){$where.=" and addtime>='".strtotime($s_addtime." 00:00:00")."'";}
	if($b_addtime){$where.=" and addtime<='".strtotime($b_addtime." 23:59:59")."'";}

	if($s_qjdate){$where.=" and qjdate>='".strtotime($s_qjdate." 00:00:00")."'";}
	if($b_qjdate){$where.=" and qjdate<='".strtotime($b_qjdate." 23:59:59")."'";}
	
	//查询输出--------------------------------------------------------------------------------------
	$i=0;
	$query="select * from qujian {$where} order by qjid desc";
	$sql=$xingao->query($query);
	$rc_print=mysqli_affected_rows($xingao);
	while($rs=$sql->fetch_array())
	{
		$i+=1;
		
?>
<div align="center"  class="<?php if($i==6){echo 'PageNext';$i=0; }?>">
<?php require("print_temp_1.php"); ?><br><br>
</div>
<?php
	
	}//while($rs=$empire->fetch($sql))
	$rc=mysqli_affected_rows($xingao);
	if (!$rc)
	{
		exit("<script>alert('没有找到符合的选项！');goBack('uc');</SCRIPT>");
	}
}
?>
</div>



<?php
if($lx!="tj"){
?>
<div class="alert alert-block fade in alert_cs col-md-5" style="margin-top:30px;">

  <form action="?" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="tj">
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i>
          打印选项
        </div>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
      </div>
      <div class="portlet-body form" style="display: block;"> 
        <!--表单内容-->
        <div class="form-group">
          <label class="control-label col-md-3"><?=$LG['status']//状态?></label>
          <div class="col-md-9">
            <select  class="form-control input-small select2me" name="status" data-placeholder="状态">
                  <option value="" selected>全部</option>
                  <?=qujian_Status($status,1)?>
                </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-3">信息ID</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="qjid" value="<?=$qjid?>" >
            <span class="help-block">多个用“,”分开</span> </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-md-3">信息ID范围</label>
          <div class="col-md-9">
            <input type="text" class="form-control input-small" name="s_id" value="<?=$s_id?>" >
            到
             <input type="text" class="form-control input-small" name="b_id" value="<?=$b_id?>" >
           
           </div>
        </div>

      
          
        
        
        <div class="form-group">
          <label class="control-label col-md-3">申请日期</label>
          <div class="col-md-9">
          <input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="s_addtime"  value="<?=$s_addtime?>">   
          到   
           <input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="b_addtime"   value="<?=$b_addtime?>">   
            
          </div>
        </div>
        
    <div class="form-group">
          <label class="control-label col-md-3">取件日期</label>
          <div class="col-md-9">
             <input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="s_qjdate"   value="<?=$s_qjdate?>">
             
              到   
           <input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="b_qjdate"  value="<?=$b_qjdate?>">   
            
            
          </div>
        </div>        
        
      </div>
      <div class="xats">
   &raquo;  以上为多选项操作,至少要选择一种选项!<br>
   &raquo;  范围可以只填写其中一项,表示最小从此开始或最大到此结束!<br>
   </div>
    </div>
     
   
        
        
                
        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">





      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> 打 印 </button>
      <button type="button" class="btn btn-danger" onClick="goBack('c');"  style="margin-left:30px;"><i class="icon-remove"></i> <?=$LG['close']?> </button>
    </div>
  </form>
</div>
<?php
}
?>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
