<!--显示所有会员组的所有充值赠送信息-->

<table width="100%" class="table table-striped table-bordered table-hover">
   <thead class="thead_color">
    <tr align="center">
      <td><?=$LG['incluce.zengsong_1'];//等级越高 优惠越多?></td>
      <td><?=$LG['incluce.zengsong_2'];//充值金额?></td>
      <td><?=$LG['incluce.zengsong_3'];//赠送奖励?></td>
    </tr>
    </thead>
    <tbody>
    
<?php
$query="select groupid,groupname{$LT},zengsong from member_group where checked=1 order by  myorder desc,groupname{$LT} desc,groupid desc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$zengsong=cadd($rs['zengsong']);
	if($zengsong){
		 $arr=ToArr($zengsong,1);
		 $count=arrcount($arr);
		 $i=0;
?>
    <tr align="center">
      <td rowspan="<?=$count?>"><?=cadd($rs['groupname'.$LT])?></td>
	  <?php 
      foreach($arr as $arrkey=>$value)
      {
          $line=ToArr($value,2);
		  $i+=1;
		  if($i==1){
		  ?>
          <td><?=$LG['incluce.zengsong_4']?><?=$line[0].$XAmc?></td>
          <td><?=$LG['incluce.zengsong_5']?><?=$line[1].$XAmc?></td>
      <?php
		  }else{
		  ?>
          </tr>
          <tr align="center">
              <td><?=$LG['incluce.zengsong_4']?><?=$line[0].$XAmc?></td>
              <td><?=$LG['incluce.zengsong_5']?><?=$line[1].$XAmc?></td>
      <?php
		  }
      }
      ?>
    </tr>
<?php
	}
}
?>
  </tbody>
</table>