<?php 		
//图片扩大插件
require_once($_SERVER['DOCUMENT_ROOT'].'/public/enlarge/call.html');

$yd=FeData('yundan','ydid,ydh,goodsdata,warehouse,channel',$where_yd);
$ydid=$yd['ydid'];
$yd['goodsdata']=cadd($yd['goodsdata']);

if($yd['goodsdata'])
{
	$yd_gdid=yundan_goodsdata($yd['goodsdata'],'',1);//获取全部ID
	$yd_gdid=ToArr($yd_gdid);//转数组
	krsort($yd_gdid);//修改排序
	$yd_gdid=ToStr($yd_gdid);//重新转回字符串
}
?>

<div class="portlet">
  <div class="portlet-title">
    <div class="caption"><i class="icon-reorder"></i>已扫描<?=arrcount($yd_gdid)?>个商品资料</div>
    <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
  </div>
  <div class="portlet-body form" style="display: block;"> 
    <!--表单内容-->
<?php
if($ydid&&warehouse_per('ts',$yd['warehouse'],1))
{
	echo '<font class="red" style="font-size: 16px;">无权操作该仓库运单</font>';
	
}elseif($yd_gdid){
	

	$order=" order by find_in_set(gdid,'{$yd_gdid}')";//默认排序
	include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页
	$query="select * from gd_japan where gdid in ({$yd_gdid}) {$order}";
	//分页处理
	$line=30;$page_line=15;//不设置则默认
	include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
	?>
		<table class="table table-striped table-bordered table-hover" style="border:0px solid #ddd;">
          <thead>
            <tr>
              <th align="center" class="table-checkbox"><a href="?<?=$search?>&orderby=&orderlx=&typ=so" class="<?=orac('')?>">扫描时间</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=nameJP&orderlx=&typ=so" class="<?=orac('nameJP')?>">日文名称</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=nameCN&orderlx=&typ=so" class="<?=orac('nameCN')?>">中文名称</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=nameEN&orderlx=&typ=so" class="<?=orac('nameEN')?>">英文名称</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=price&orderlx=&typ=so" class="<?=orac('price')?>">单价</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=number&orderlx=&typ=so" class="<?=orac('number')?>">内含数量</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=weight&orderlx=&typ=so" class="<?=orac('weight')?>">重量</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=capacity&orderlx=&typ=so" class="<?=orac('capacity')?>">容量</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=imgurl&orderlx=&typ=so" class="<?=orac('imgurl')?>">外图</a></th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
	$gd=yundan_goodsdata($yd['goodsdata'],$rs['gdid'],2);//获取资料,返回数组
?>
            <tr class="odd gradeX <?=$rs['checked']?'':'gray2';?>">
              <td rowspan="2" align="center" valign="middle">
                <?php EnlargeImg(ImgAdd($rs['img']),$rs['gdid'],2)?>
                
                <font class="gray tooltips" data-container="body" data-placement="top" data-original-title="条码号码"><?=cadd($rs['barcode'])?></font>
                <br>
                <font class="gray tooltips red2" data-container="body" data-placement="top" data-original-title="运单里数量">数量<?=cadd($gd[1])?></font>
                
                </td>
              <td align="center" valign="middle"><?=cadd($rs['nameJP'])?></td>
              <td align="center" valign="middle"><?=cadd($rs['nameCN'])?></td>
              <td align="center" valign="middle"><?=cadd($rs['nameEN'])?></td>
              <td align="center" valign="middle"><?=spr($rs['price'])?><?=$XAsc?></td>
              <td align="center" valign="middle"><?=spr($rs['number'])?></td>
              <td align="center" valign="middle"><?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?></td>
              <td align="center" valign="middle"><?=$rs['capacity']>0?spr($rs['capacity']).'ML':''?></td>
              <td align="center" valign="middle"><?php if($rs['imgurl']){?><a href="<?=cadd($rs['imgurl'])?>" target="_blank" class=" tooltips" data-container="body" data-placement="top" data-original-title="查看图片"><i class="icon-picture"></i></a><?php }?></td>
              <td align="center" valign="middle">
  				 <a href="?<?=$search?>&typ=del&ydid=<?=$ydid?>&gdid=<?=$rs['gdid']?>" class="btn btn-xs btn-danger"><i class="icon-remove"></i> 取出</a>
                 
                </td>
            </tr>
            <!---->
            <tr>
              <td colspan="9" align="left">
              
         <div class="gray modal_border"> <strong>分类：</strong>
         <?=classify($rs['classid'])?>
         </div>
                
                <!---->
         <?php 
         $zhi=cadd($rs['composition']);
         if($zhi){
		 ?>
          <div class="gray modal_border"> <strong>成份：</strong>
         <?php 
			echo leng($zhi,200,"...");
			Modals($zhi,$title='成份',$time=$rs['addtime'],$nameid='composition'.$rs['gdid'],$count=200);
		 ?>
         </div>
        <?php }?>
                
                <!---->
                
         <?php
         $zhi=cadd($rs['content']);
         if($zhi){
		 ?>
          <div class="gray modal_border"> <strong>备注：</strong>
         <?php 
			echo leng($zhi,200,"...");
			Modals($zhi,$title='备注',$time=$rs['addtime'],$nameid='content'.$rs['gdid'],$count=200);
		 ?>
         </div>
        <?php }?>
                
                
                <!----></td>
            </tr>
		<!--分隔-开始-->
		<!--<tr>
			<td colspan="10" style="border-left: 0px none #ffffff;	border-right: 0px none #ffffff; background-color:#ffffff; height:30px;"></td>
		</tr>-->
		<!--分隔-结束-->

<?php
}
?>
          </tbody>
        </table>
        
        
		<div class="row">
			<?=$listpage?>
		</div>
	<!---->
<?php }?>   
    
    <!--表单内容 结束--> 
  </div>
</div>
