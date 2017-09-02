<?php  
if($so)
{
?> 
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i>
       
        <strong>已扫<?=$num=mysqli_num_rows($xingao->query("select ydid from yundan where {$where} "));?>个运单</strong>
        
         (
		 <?php 
		 if(!$warehouseID){ $warehouseID=FeData('yundan','warehouse',"{$where}");}
		 echo warehouse($warehouseID);
		 $num=mysqli_num_rows($xingao->query("select ydid from yundan where warehouse='{$warehouseID}' and status='4' "));
		 ?> 
         待出库<?=$num?>个运单
         )
         </div>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
      </div>
      <div class="portlet-body form" style="display: block;">
        <!--表单内容-->
    
    <?php
    $order=' order by edittime desc';//默认排序
    include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页
    $query="select ydid,ydh,weight,s_name,s_mobile,s_shenfenhaoma,s_shenfenimg_z,s_shenfenimg_b,warehouse,weightRepeat from yundan where  {$where} {$order}";
    //分页处理
    $line=50;$page_line=15;//不设置则默认
    include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
    ?>
        <table class="table table-striped table-bordered table-hover" style="border:0px solid #ddd;">
        <thead>
        <tr>
        
        <th align="center"><a href="?<?=$search?>&orderby=ydh&orderlx=&typ=so" class="<?=orac('ydh')?>">运单号</a>/<a href="?<?=$search?>&orderby=edittime&orderlx=&typ=so" class="<?=orac('edittime')?>">添加</a></th>
        <th align="center"><a href="?<?=$search?>&orderby=weight&orderlx=&typ=so" class="<?=orac('weight')?>">原重量</a></th>
        <th align="center"><a href="?<?=$search?>&orderby=weightRepeat&orderlx=&typ=so" class="<?=orac('weightRepeat')?>">复称重量</a></th>
        <th align="center"><a href="?<?=$search?>&orderby=s_name&orderlx=&typ=so" class="<?=orac('s_name')?>">收件人</a></th>
        <th align="center"><a href="?<?=$search?>&orderby=s_shenfenhaoma&orderlx=&typ=so" class="<?=orac('s_shenfenhaoma')?>">证件</a></th>
        <th align="center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        while($rs=$sql->fetch_array())
        {
			$joint='warehouse_'.$rs['warehouse'].'_weightRepeat';	$weightRepeat_limit=$$joint;
			$differ=spr($rs['weightRepeat']-$rs['weight']);
        ?>
        <tr class="odd gradeX">
        
        <td align="center" valign="middle"><a href="show.php?ydid=<?=$rs['ydid']?>" target="_blank"><?=cadd($rs['ydh'])?></a></td>
        <td align="center" valign="middle"><?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?></td>
        <td align="center" valign="middle">
		<?php 
			if($rs['weightRepeat']>0)
			{
				if(abs($differ)>=$weightRepeat_limit){echo '<font class="red">';$ppt=" (相差{$differ}{$XAwt})";}else{echo '<font>';$ppt='';}
				echo spr($rs['weightRepeat']).$XAwt.$ppt;
				echo '</font>';
			}else{
				echo '未复称';
			}
		?>
        
         
         </td>
        <td align="center" valign="middle"><?=cadd($rs['s_name'])?>:<?=cadd($rs['s_mobile'])?></td>
          
        <td align="center" valign="middle">
          <?=cadd($rs['s_shenfenhaoma'])?>
          <?=ShowImg(cadd($rs['s_shenfenimg_z']).','.cadd($rs['s_shenfenimg_b']))?>
          </td>
        <td align="center" valign="middle">
       <a href="?<?=$search?>&typ=del&ydid=<?=$rs['ydid']?>" class="btn btn-xs btn-danger"><i class="icon-remove"></i> 取出</a>
        </td>
        </tr>
        <?php }?>
        </tbody>
        </table>
        <div class="row">
            <?=$listpage?>
        </div>
        <!---->
        
        <!--表单内容 结束--> 
      </div>
    </div>
<?php }?>
