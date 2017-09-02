
<div id="accordion1" class="panel-group">
<?php 
$i=0;
$query_wh="select whid,name{$LT},address{$LT} from warehouse where checked='1' order by myorder desc,whid desc";
$sql_wh=$xingao->query($query_wh);
while($wh=$sql_wh->fetch_array())
{
	if($member_warehouse[$Mgroupid][$wh['whid']]['checked'])
	{
		$i+=1;
?>
	<div class="panel panel-default">
	   <div class="panel-heading">
		<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_<?=$i?>">
			<h4 class="panel-title">
			<?=cadd($wh['name'.$LT])?>
			<font color="red">--------&#x60A8;&#x5728;&#x4E2D;&#x56FD;&#x7684;&#x79C1;&#x4EBA;&#x4ED3;&#x5E93;&#x5730;&#x5740;&#xFF0C;&#x60A8;&#x53EF;&#x4EE5;&#x586B;&#x5199;&#x4EE5;&#x4E0B;&#x5730;&#x5740;&#x5230;&#x4E2D;&#x56FD;&#x4EFB;&#x4F55;&#x7F51;&#x7AD9;&#x8D2D;&#x7269;&#xFF01;</font>
			</h4>

		</a>
	   </div>
	   
	   <div id="accordion1_<?=$i?>" class="panel-collapse collapse <?=$i==1?'in':''?>">
		  <div class="panel-body">
			 <?=Label(TextareaToBr($wh['address'.$LT],1))?>
		  </div>
	   </div>
	</div>
<?php
    }
}
?>			
</div>