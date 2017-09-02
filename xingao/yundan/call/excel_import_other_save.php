<?php
$types=par($_POST['types']);

$lotno=par($_POST['lotno']);
$op_status_4=par($_POST['op_status_4']);
$op_status_30=par($_POST['op_status_30']);
$op_status_20=par($_POST['op_status_20']);
$op_status_kd=par($_POST['op_status_kd']);

//处理部分-开始**************************************************************************************************
//必须有$file 文件
if ($lx=="tj")
{ 
	if(!$types)
	{
		DelFile($file);//删除文件
		exit ("<script>alert('请选择导入类型！');goBack();</script>");
	}

	require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/Import_call.php');
	
	//导入部分-开始-------------------------------------------------------------------------------------
	for ($row=2;$row<=$highestRow;$row++) //$row =2; 从第2行读取(第一行是标题)
	{
		$strs=array();
		for ($col = 0;$col < $highestColumnIndex;$col++)
		{
			$strs[$col] =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
		}    
		
		//通用处理
		$ok=1;
		$ydid=0;
		
		switch($types)
		{
			case 'dsfydh'://第三方运单号-----------------------------------------------------------------------
			
				//表格数据验证
				if($ok && ( !par($strs[1]) || !par($strs[8]) ) ) 
				{
					$ok=0;$error_result+=1;
					echo '<strong>'.$strs[0].'</strong>：表格要有运单号和第三方运单号。此行没导入！<br>';
				}
				
				//查询数据验证
				if($ok)
				{
					$query="select * from yundan where ydh='".par($strs[8])."'";
					$sql=$xingao->query($query);
					while($rs=$sql->fetch_array())
					{
						$ydid=$rs['ydid'];
						$strs_zhi=$strs[8];
						$strs_name='';
						require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/excel_import_other_yz.php');
						
						if ($ok&&$rs['dsfydh'])
						{
							$ok=0;$error_result+=1;
							echo '<strong>'.$strs[8].'</strong>：已经有第三方运单号。此行没导入！<br>';
						}
						
						if($ok)
						{
							$xingao->query("update yundan set dsfydh='".par($strs[1])."' where ydid='{$rs[ydid]}'");SQLError();
							$succ_result+=1;
							if($op_status_kd && mysqli_affected_rows($xingao))
							{
								//更新状态
								yundan_updateStatus($rs,$update_status=20,0,1);
							}
						}
					}//while($rs=$sql->fetch_array())
					if ($ok&&!$ydid)
					{
						$ok=0;$error_result+=1;
						echo '<strong>'.$strs[8].'</strong>：没找到该运单号！<br>';
					}

				}
			break;
			
			case 'lotno'://批次号----------------------------------------------------------------------------------
			
				//表格数据验证
				if($ok && ( !par($strs[0]) || ( !par($strs[1])&&!par($strs[2]) ) ) ) 
				{
					$ok=0;$error_result+=1;
					echo '<strong>'.$strs[0].'</strong>：表格要有运单号和要导入的信息。此行没导入！<br>';
				}
				
				//查询数据验证
				if($ok)
				{
					$query="select * from yundan where ydh='".par($strs[0])."'";
					$sql=$xingao->query($query);
					while($rs=$sql->fetch_array())
					{
						$ydid=$rs['ydid'];
						$strs_zhi=$strs[0];
						$strs_name='';
						require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/excel_import_other_yz.php');
						
						if($ok)
						{
							$xingao->query("update yundan set classid='".add($strs[1])."',lotno='".add($strs[2])."' where ydid='{$rs[ydid]}'");SQLError();
							$succ_result+=1;
							if($op_status_kd && mysqli_affected_rows($xingao))
							{
								//更新状态
								yundan_updateStatus($rs,$update_status=20,0,1);
							}
						}
					}//while($rs=$sql->fetch_array())
					if ($ok&&!$ydid)
					{
						$ok=0;$error_result+=1;
						echo '<strong>'.$strs[0].'</strong>：没找到该运单号！<br>';
					}

				}
			break;
			
			case 'gnkd'://派送快递------------------------------------------------------------------------
			
				//表格数据验证
				$strs_zhi=$strs[2]." ".$strs[3]." ".$strs[4];
				
				if($ok && ( !par($strs[0]) || !par($strs[1]) ) ) 
				{
					$ok=0;$error_result+=1;
					echo '<strong>'.$strs_zhi.'</strong>：表格要有快递公司编号和快递单号。此行没导入！<br>';
				}
				
				if($ok) 
				{
					$where='';
					if(par($strs[2])){$where.=" and ydh='".par($strs[2])."'";}
					if(par($strs[3])){$where.=" and dsfydh='".par($strs[3])."'";}
					if(par($strs[4])){$where.=" and hscode='".par($strs[4])."'";}
					if(!$where) 
					{
						$ok=0;$error_result+=1;
						echo '<strong>'.$strs_zhi.'</strong>：表格要有 运单号 或 第三运单号 或 HG/HS编码。此行没导入！<br>';
					}
				}
				//查询数据验证
				if($ok)
				{
					$query="select * from yundan where 1=1 {$where}";
					$sql=$xingao->query($query);
					while($rs=$sql->fetch_array())
					{
						$ydid=$rs['ydid'];
						//$strs_zhi=$strs[0];
						$strs_name='运单号/第三运单号/HG-HS编码';
						require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/excel_import_other_yz.php');
						
						if($ok&&$rs['gnkd']&&$rs['gnkdydh'])
						{
							$ok=0;$error_result+=1;
							echo $strs_zhi .$strs_name.' 已填快递公司和快递单号。此行没导入！<br>';
						}
						
						if($ok)
						{
							$xingao->query("update yundan set gnkd='".par($strs[0])."',gnkdydh='".par($strs[1])."' where ydid='{$rs[ydid]}'");SQLError();
							$succ_result+=1;
							if($op_status_kd && mysqli_affected_rows($xingao))
							{
								//更新状态
								yundan_updateStatus($rs,$update_status=20,0,1);
							}
						}
					}//while($rs=$sql->fetch_array())
					if ($ok&&!$ydid)
					{
						$ok=0;$error_result+=1;
						echo '<strong>'.$strs_zhi.'</strong>：没找到该'.$strs_name.'！<br>';
					}

				}
			break;
			
			case 'sf'://顺丰快递----------------------------------------------------------------------------------
			
				//表格数据验证
				if($ok && ( !par($strs[0]) || !par($strs[1]) ) ) 
				{
					$ok=0;$error_result+=1;
					echo '<strong>'.$strs[0].'</strong>：表格要有HG/HS编码和快递主单号。此行没导入！<br>';
				}
				
				//查询数据验证
				if($ok)
				{
					//处理主单号==========================================
					$query="select * from yundan where hscode='".par($strs[0])."'";//一般是一条信息
					$sql=$xingao->query($query);
					while($rs=$sql->fetch_array())
					{
						$ydid=$rs['ydid'];
						$strs_zhi=$strs[0];
						$strs_name='HG/HS条码';
						require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/excel_import_other_yz.php');

						if($ok&&$rs['gnkd']&&$rs['gnkdydh'])
						{
							$ok=0;$error_result+=1;
							echo $strs_zhi .$strs_name.' 已填快递公司和快递单号。此行没导入！<br>';
						}
						
						if($ok)
						{
							$xingao->query("update yundan set gnkd='shunfeng',gnkdydh='".par($strs[1])."' where ydid='{$rs[ydid]}'");SQLError();
							$succ_result+=1;
							
							if($op_status_kd && mysqli_affected_rows($xingao))
							{
								//更新状态
								yundan_updateStatus($rs,$update_status=20,0,1);
							}
						}
					}//while($rs=$sql->fetch_array())
					
					//处理子单号==========================================
					$ok=1;
					$str=par($strs[2]);
					if($str)
					{
						$str=str_replace('，',',', $str);//先全部替换,号，防有中文,号
						$array=explode(',',$str); //转数组
						$counts=count($array);
						
						for ($i=0;$i<$counts;$i++)
						{
							if(trim($array[$i]))
							{
								//有太多查询,不用显示,因此在查询时判断
								$where=" and (gnkd='' or gnkdydh='') ";
								if ($op_status_30){$where.=" and status<>30";}
								if ($op_status_20){$where.=" and status<20";}
								if ($op_status_4){$where.=" and status>4";}
								if ($lotno){$where.=" and lotno='{$lotno}'";}

								$query="select * from yundan where 1=1 {$where} and s_mobile='".par($strs[4])."' and s_name='".par($strs[5])."' ";//有多条信息
								$sql=$xingao->query($query);
								while($rs=$sql->fetch_array())
								{
									$ydid=$rs['ydid'];
									$s_add=$rs['s_add_shengfen'].$rs['s_add_chengshi'].$rs['s_add_quzhen'].$rs['s_add_dizhi'];
									if($ok && RepEmpty(cadd($s_add))!=RepEmpty($strs[3]) && RepEmpty(cadd($s_add))!=RepEmpty($strs[3]))
									{
										$ok=0;$error_result+=1;
										//echo $array[$i].' 子单号地址不相同。此行没导入！<br>';//有太多查询,不用显示
									}
									
									if($ok)
									{
										$xingao->query("update yundan set gnkd='shunfeng',gnkdydh='".par($array[$i])."' where ydid='{$rs[ydid]}'");SQLError();
										$succ_result+=1;
										echo $array[$i].' <font class="gray2">子单号导入成功。已导到'.$rs['ydh'].'</font><br>';//子单号防止导错,显示导入成功的单号
										
										if($op_status_kd && mysqli_affected_rows($xingao))
										{
											//更新状态
											yundan_updateStatus($rs,$update_status=20,0,1);
										}
									}
								}//while($rs=$sql->fetch_array())
							}
						}
					}

					if ($ok&&!$ydid)
					{
						$ok=0;$error_result+=1;
						echo '<strong>'.$strs[0].'</strong>：没找到该HG/HS条码！<br>';
					}
				}
			break;
			
		}
	}//for ($row=2;$row<=$highestRow;$row++)//导入部分-结束-------------------------------------------------------------------------------------
	
	echo '<br><hr size="1" width="100%" />';
	echo $LG['importSuccess'].":<strong>{$succ_result}</strong><br>";
	echo $LG['importFailure'].":<strong>{$error_result}</strong><br>";

	DelFile($file);//删除文件
	//$token->drop_token("yundan_excel_import"); //同一个页面不能删除-处理完后删除密钥
	
	//Import_call.php 文件中有2个div开头
	echo ' 
	</div>
    </div>
	';
	
}
//处理部分-结束**************************************************************************************************
?>