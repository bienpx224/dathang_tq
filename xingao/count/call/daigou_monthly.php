<?php 	unset($money_0);unset($money_1);unset($money_2);unset($money_3);$num=0;?>


<tr class="odd gradeX">
  <td align="center"><?=$month?'今年'.$month.'月':'指定的范围'?></td>
  
  <!---------------------------代购价格--------------------------->
  <td align="center">
      <a href="?detailed=1<?=$search?>" target="_blank">
      <?php 
      //所有币种	
      $arr=ToArr($openCurrency,',');
      if($arr)
      {
          foreach($arr as $arrkey=>$value)
          {
              //只统计已采购的
			  $where_sum="{$where} and priceCurrency='{$value}' and status in (6,7,8)";
			 
			  //统计代购单:运费
              $dg=FeData('daigou',"count(*) as total, sum(`freightFeePay`) as freightFeePay"," {$where_sum}");
			  
			  //统计商品:商品费用
              $gb=FeData('daigou_goods',"count(*) as total,sum(`goodsFeePay`) as goodsFeePay"," dgid=(select dgid from daigou where {$where_sum} limit 1)");
              
              if(spr($gb['goodsFeePay'])||spr($dg['freightFeePay']))
              {
                  $money_0[$value]=spr($dg['freightFeePay']+$gb['goodsFeePay']);
                  echo $money_0[$value].$value;
                  echo '<br>';
              }
          }
      }
      ?>
    </a>			  
  </td>
  
  <!---------------------------采购成本--------------------------->
  <td align="center">
      <?php 
      //所有币种	
      $arr=ToArr($openCurrency,',');
      if($arr)
      {
          foreach($arr as $arrkey=>$value)
          {
              //只统计已采购的
              $fe=FeData('daigou',"count(*) as total,
              sum(`procurementCost`) as procurementCost
              
              "," {$where} and priceCurrency='{$value}' and status in (6,7,8)");
              

              if(spr($fe['procurementCost']))
              {
                  $money_1[$value]=spr($fe['procurementCost']);
                  echo $money_1[$value].$value;
                  echo '<br>';
              }
          }
      }
      ?>
  </td>
  
  <!---------------------------利润--------------------------->
  <td align="center">
      <?php 
      //所有币种	
      $arr=ToArr($openCurrency,',');
      if($arr)
      {
          foreach($arr as $arrkey=>$value)
          {
               //只统计已采购的
			  $where_sum="{$where} and priceCurrency='{$value}' and status in (6,7,8)";
			 
			  //统计代购单:运费
              $dg=FeData('daigou',"count(*) as total, sum(`freightFeePay`) as freightFeePay,sum(`procurementCost`) as procurementCost"," {$where_sum}");
			  
			  //统计商品:商品费用
              $gb=FeData('daigou_goods',"count(*) as total,sum(`goodsFeePay`) as goodsFeePay"," dgid=(select dgid from daigou where {$where_sum} limit 1)");
              
              if(spr($dg['procurementCost']))
              {
                  $money_2[$value]=spr($gb['goodsFeePay']+$dg['freightFeePay']-$dg['procurementCost']);
                  if($money_2[$value]<0){echo '<font class="red">';}else{echo '<font>';}
                  echo $money_2[$value].$value;
                  echo '</font><br>';
              }
          }
      }
      ?>
  </td>
  
  <!---------------------------扣费--------------------------->
  <td align="center">
      <?php 
      //所有币种	
      $arr=ToArr($openCurrency,',');
      if($arr)
      {
          foreach($arr as $arrkey=>$value)
          {
              //只统计已采购的
			  $where_sum="{$where} and toCurrency='{$value}' and status in (6,7,8)";
			 
			  //统计代购单:运费
              $dg=FeData('daigou',"count(*) as total, sum(`freightFeePayTo`) as freightFeePayTo"," {$where_sum}");
			  
			  //统计商品:商品费用
              $gb=FeData('daigou_goods',"count(*) as total,sum(`goodsFeePayTo`) as goodsFeePayTo"," dgid=(select dgid from daigou where {$where_sum} limit 1)");
              
              if(spr($gb['goodsFeePayTo'])||spr($dg['freightFeePayTo']))
              {
                  $money_3[$value]=spr($gb['goodsFeePayTo']+$dg['freightFeePayTo']);
                  echo $money_3[$value].$value;
                  echo '<br>';
              }
          }
      }
      ?>
  </td>
  
  
  <!---------------------------总单数--------------------------->
  <td align="center">
  <?php 
  //总单数
  $fe=FeData('daigou',"count(*) as total"," {$where} and status in (6,7,8)");
  echo  $num=spr($fe['total']);
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
	
?>