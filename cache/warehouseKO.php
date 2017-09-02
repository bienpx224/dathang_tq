<?php 
	//101仓库数据
	//调用:$joint='warehouse_'.$rs['warehouse'].'_sign';echo $$joint;
	
	$warehouse_101_country='502';
	$warehouse_101_weightRepeat='0.1';
	$warehouse_101_sign='F.O.B.JAPAN';
	$warehouse_101_area='2';
	
$warehouse_101_ON_op_bgfee1_0='1';
			$warehouse_101_ON_op_bgfee2_0='1';
			$warehouse_101_ON_op_ydfee1_0='1';
			$warehouse_101_ON_op_ydfee2_0='1';
			$warehouse_101_ON_op_free_0='1';
			$warehouse_101_ON_op_freearr_0='1';
			$warehouse_101_weight_limit_1='30';
			$warehouse_101_customs_types_limit_1='婴幼儿奶粉,婴幼儿米粉,婴幼儿食品,婴幼儿麦片';
			$warehouse_101_customs_weight_limit_1='20';
			$warehouse_101_signday_1='60';
			$warehouse_101_shenfenzheng_1='1';
			$warehouse_101_customs_1='gd_mosuda';
			$warehouse_101_baoxian_1_1='1000';
			$warehouse_101_baoxian_2_1='5';
			$warehouse_101_baoxian_3_1='10';
			$warehouse_101_baoxian_5_1='1000';
			$warehouse_101_insuranceFormula_1='100=10
200=5%
300=10%';
			$warehouse_101_insuranceFormulaType_1='1';
			$warehouse_101_ON_op_bgfee1_1='1';
			$warehouse_101_ON_op_bgfee2_1='1';
			$warehouse_101_ON_op_ydfee1_1='1';
			$warehouse_101_ON_op_ydfee2_1='1';
			$warehouse_101_ON_op_free_1='1';
			$warehouse_101_ON_op_freearr_1='1';
			$warehouse_101_channel_1='婴儿食品 (要证件,要备案)';
			$warehouse_101_weight_limit_ppt_1='实际称重如有超过，我们将自行为您分成多个运单!';
			$warehouse_101_content_1='大概5-10个工作日到达，只支持婴儿食品';
			$warehouse_101_weight_limit_2='30';
			$warehouse_101_signday_2='60';
			$warehouse_101_baoxian_1_2='1000';
			$warehouse_101_baoxian_2_2='10';
			$warehouse_101_baoxian_3_2='20';
			$warehouse_101_baoxian_5_2='1000';
			$warehouse_101_ON_op_bgfee1_2='1';
			$warehouse_101_ON_op_bgfee2_2='1';
			$warehouse_101_ON_op_ydfee1_2='1';
			$warehouse_101_ON_op_ydfee2_2='1';
			$warehouse_101_channel_2='海外至中国-空运(不要证件,不要备案)';
			$warehouse_101_content_2='大概5-10个工作日到达';
			
	//100仓库数据
	//调用:$joint='warehouse_'.$rs['warehouse'].'_sign';echo $$joint;
	
	$warehouse_100_country='116';
	$warehouse_100_weightRepeat='0.1';
	$warehouse_100_sign='F.O.B.JAPAN';
	$warehouse_100_area='1';
	
$warehouse_100_ON_op_bgfee1_0='1';
			$warehouse_100_ON_op_bgfee2_0='1';
			$warehouse_100_ON_op_ydfee1_0='1';
			$warehouse_100_ON_op_ydfee2_0='1';
			$warehouse_100_ON_op_free_0='1';
			$warehouse_100_ON_op_freearr_0='1';
			$warehouse_100_JPChannel_1='1';
			$warehouse_100_insuranceFormula_1='100=10
200=5%
300=10%';
			$warehouse_100_insuranceFormulaType_1='1';
			$warehouse_100_ON_op_bgfee1_1='1';
			$warehouse_100_ON_op_bgfee2_1='1';
			$warehouse_100_ON_op_ydfee1_1='1';
			$warehouse_100_ON_op_ydfee2_1='1';
			$warehouse_100_ON_op_free_1='1';
			$warehouse_100_ON_op_freearr_1='1';
			$warehouse_100_channel_1='日本至中国-EMS';
			$warehouse_100_content_1='大概5-7个工作日到';
			$warehouse_100_JPChannel_2='2';
			$warehouse_100_ON_op_bgfee1_2='1';
			$warehouse_100_ON_op_bgfee2_2='1';
			$warehouse_100_ON_op_ydfee1_2='1';
			$warehouse_100_ON_op_ydfee2_2='1';
			$warehouse_100_channel_2='日本至中国-空运';
			$warehouse_100_content_2='大概5-10个工作日到';
			$warehouse_100_JPChannel_3='3';
			$warehouse_100_channel_3='日本至中国-SAL';
			$warehouse_100_content_3='大概7-10个工作日到';
			$warehouse_100_JPChannel_4='4';
			$warehouse_100_channel_4='日本至中国-船运';
			$warehouse_100_content_4='大概7-15个工作日到';
			
			//整个系统仓库数据
			$warehouse_more='1';
			$warehouse='海外仓库：101
			日本仓库：100';
			
			/*
			$lx=0显示名称;$lx=1显示下拉菜单;$per=0 下拉时是否只显示可管理的仓库(非0时,都是只显示可管理,1时自动,否则给参数);
			$option_noempty=1 不显示空下拉
			*/
			function warehouse($val,$lx=0,$per=0,$option_noempty=0)
			{
				global $manage_per,$member_per,$member_warehouse;
				global $Xgroupid,$Mgroupid;
				require($_SERVER["DOCUMENT_ROOT"]."/public/global.php");
				//显示名称
				if(!$lx)
				{
					$arr=$val;
					if($arr)
					{
						if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
						foreach($arr as $arrkey=>$value)
						{
							if ($value==""){$show="";}
					 elseif ($value=='101'){$show='海外仓库';}
						elseif ($value=='100'){$show='日本仓库';}
						
						if($showname){$showname.=",".$show;}else{$showname=$show;}
						}
				   }
					return $showname;
					
				//显示下拉菜单
				}else{
					
			if(!$option_noempty){if ($val==''){echo "<option value=''  selected>请选择仓库</option>"; }else{echo "<option value='' >请选择仓库</option>";}}
					//支持下拉多选
					
					if($per)
					{
						if($per==1)
						{
							if($Xgroupid){
								$admin=$manage_per[$Xgroupid]['admin'];
								$warehouse=explode(",",$manage_per[$Xgroupid]['warehouse']);
							}elseif($Mgroupid){
								$warehouse=array();//必须用空数组,否则下面in_array判断错误
							}

						}else{
							$warehouse=explode(",",$per);
						}
					}
					$val=explode(",",$val);
				
						
							if (in_array('101',$val )){
								echo "<option value='101'  selected>海外仓库</option>"; 
							}else{
								if($per){
									$show=0;
									if ($Xgroupid&&($admin||!$per||in_array('101',$warehouse))){$show=1;}//管理员权限
									elseif ($Mgroupid&&$member_warehouse[$Mgroupid]['101']['checked']&&(in_array('101',$warehouse)||empty($warehouse))){$show=1;}//会员权限
									elseif(!$Mgroupid&&in_array('101',$warehouse)){$show=1;}
		
								}else{
									$show=1;
								}
								
								if($show){echo "<option value='101' >海外仓库</option>";}
							}
			
						
						
							if (in_array('100',$val )){
								echo "<option value='100'  selected>日本仓库</option>"; 
							}else{
								if($per){
									$show=0;
									if ($Xgroupid&&($admin||!$per||in_array('100',$warehouse))){$show=1;}//管理员权限
									elseif ($Mgroupid&&$member_warehouse[$Mgroupid]['100']['checked']&&(in_array('100',$warehouse)||empty($warehouse))){$show=1;}//会员权限
									elseif(!$Mgroupid&&in_array('100',$warehouse)){$show=1;}
		
								}else{
									$show=1;
								}
								
								if($show){echo "<option value='100' >日本仓库</option>";}
							}
			
						
				}
			}
		?>