<?php
/*
 $bgid=$rs['bgid'];
 $show_small=1;//简洁显示
 $notlist=0;//不输列表,需要带有 $yundan_bg=yundan_bg_list($bgid,$callFrom='manage');
 $groupid=$Mgroupid;//$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");
 require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/bg_hx_fh.php');
*/
if($bgid||$notlist)
{
	
	if(!$show_small&&!$notlist){echo '&raquo; '.$LG['yundan.Xcall_bg_hx_fh_2'];}
	if(!$notlist){$yundan_bg=yundan_bg($bgid,'',$callFrom);}//有直接输出
	if(!$show_small&&!$notlist){echo '<br>';}
	
	$bg_number_mall=$yundan_bg['bg_number_mall'];
	$bg_number_other=$yundan_bg['bg_number_other'];

	if($bg_number_other>0)
	{
		$Price_fh_hxsl=$member_per[$groupid]['Price_fh_hxsl'];
		$Price_fh_hx_fee1=$member_per[$groupid]['Price_fh_hx_fee1'];
		$Price_fh_hx_fee2=$member_per[$groupid]['Price_fh_hx_fee2'];
		$Price_fh_hx_fee1_way=$member_per[$groupid]['Price_fh_hx_fee1_way'];
		$Price_fh_hx_fee2_way=$member_per[$groupid]['Price_fh_hx_fee2_way'];

		$baoguo_hx_fee=baoguo_hx_fee($bg_number_other,'',$groupid,1);
		if(!$show_small)
		{
			echo '<font class=" popovers" data-trigger="hover" data-placement="top"  data-content="';
			if($Price_fh_hx_fee1_way){$fee_unit1=$LG['other.call_price_24'];}
			if($Price_fh_hx_fee2_way){$fee_unit2=$LG['other.call_price_24'];}

			echo LGtag($LG['yundan.Xcall_bg_hx_fh_1'],
					'<tag1>=='.$Price_fh_hxsl.'||'.
					'<tag2>=='.$Price_fh_hx_fee1.$XAmc.$fee_unit1.'||'.
					'<tag3>=='.$Price_fh_hx_fee2.$XAmc.$fee_unit2.'||'.
					'<tag4>=='.$member_per[$groupid]['Price_fh_feesl']
				 );
			echo '">';
			echo '&raquo; '.LGtag($LG['yundan.Xcall_bg_hx_fh_3'],'<tag1>=='.$bg_number_other.'||<tag2>=='.$baoguo_hx_fee.$XAmc);
			echo '</font>';
			//后面不要放<br>,否则费用详细页难看
		}
	}
	
	if($bg_number_mall)
	{
	  if(!$show_small){echo '<br>&raquo; '.LGtag($LG['yundan.Xcall_bg_hx_fh_4'],'<tag1>=='.$bg_number_mall);}
	}
}
 ?>