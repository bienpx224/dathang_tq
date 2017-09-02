<?php 
unset($money_0);unset($money_1);unset($money_2);unset($money_3);$num=0;

$query="select * from daigou where {$where} and status in (6,7,8) order by addtime asc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
?>	

<tr class="odd gradeX">
    <td align="center"><?=cadd($rs['username'])?><br> <font class="gray2"><?=$rs['userid']?></font> </td>
    <td align="center"><?=$month?DateYmd($rs['addtime']).'<br><font class="gray2">今年'.$month.'月</font>':DateYmd($rs['addtime'])?></td>
    <td align="center"><?=DateYmd($rs['procurementTime'])?></td>
    
    <!---------------------------代购价格--------------------------->
    <td align="center">
		<?php 
		$goodsFeePay=daigou_totalPay($rs);
        if($goodsFeePay||spr($rs['freightFeePay']))
        {
            $money_0[$rs['priceCurrency']]=spr($goodsFeePay+$rs['freightFeePay']);
            echo $money_0[$rs['priceCurrency']].$rs['priceCurrency'];
        }
        ?>
    </td>
    
    <!---------------------------采购成本--------------------------->
    <td align="center">
		<?php 
        if(spr($rs['procurementCost']))
        {
            $money_1[$rs['priceCurrency']]=spr($rs['procurementCost']);
            echo $money_1[$rs['priceCurrency']].$rs['priceCurrency'];
        }
        ?>
    </td>
    
    <!---------------------------利润--------------------------->
    <td align="center">
		<?php 
        if(spr($rs['procurementCost']))
        {
			$goodsFeePay=daigou_totalPay($rs);
            $money_2[$rs['priceCurrency']]=spr($goodsFeePay+$rs['freightFeePay']-$rs['procurementCost']);
            if($money_2[$rs['priceCurrency']]<0){echo '<font class="red">';}else{echo '<font>';}
            echo $money_2[$rs['priceCurrency']].$rs['priceCurrency'];
            echo '</font>';
        }
        ?>
    </td>
    
    <!---------------------------扣费--------------------------->
    <td align="center">
	  <?php 
	  $goodsFeePayTo=daigou_totalPay($rs,1);
      if($goodsFeePayTo||spr($rs['freightFeePayTo']))
      {
          $money_3[$rs['toCurrency']]=spr($goodsFeePayTo+$rs['freightFeePayTo']);
          echo $money_3[$rs['toCurrency']].$rs['toCurrency'];
      }
      ?>
    </td>
  
  
  <!---------------------------总单数--------------------------->
  <td align="center">
  <?php 
  //总单数
  echo  $num=1;
  ?>单
  </td>

</tr>

<?php
	//本页总计----------------------------------------------------
	//所有币种	
	$arr=ToArr($openCurrency,',');
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			//列总计
			$total_money_0[$value]+=$money_0[$value]; 
			$total_money_1[$value]+=$money_1[$value]; 
			$total_money_2[$value]+=$money_2[$value]; 
			$total_money_3[$value]+=$money_3[$value]; 
			$money_MC+=$money_2[$value]*exchange($value,$XAMcurrency);//图表用:转主币(利润)
		}
	}
	$total_num+=$num;
}
//$total_num=mysqli_num_rows($sql);
?>
