<?php 
@putenv("NLS_LANG=KOREAN_KOREA.KO16MSWIN949"); //配置 Oracle 数据库需使用的环境变量 NLS_LANG

//从config.php获取配置参数
$p_clntnum=$cj_juso_clntnum; //顾客ID  (一般30开头,如30217264)
$p_clnmgmcustcd=$cj_juso_clnmgmcustcd; //顾客管理交易处代码  (一般30开头,如30217264)
$p_prngdivcd=$cj_p_prngdivcd; //寄送原因:01一般,02退货
$p_cgsts=$cj_p_cgsts; //寄送方式:11集货,91派送


/*
$rs['cj']='{"p_zipnum":"35293","p_zipid":"0","p_oldaddress":"\ub300\uc804 \uc11c\uad6c \uad34\uc815\ub3d9","p_oldaddressdtl":"73-4\ubc88\uc9c0 \ucc9c\uc5f0\ube4c\ub529 \ub77c\ube14\ub9ac","p_newaddress":"\ub300\uc804 \uc11c\uad6c \uac00\uc7a5\ub85c","p_nesaddressdtl":"52, \ucc9c\uc5f0\ube4c\ub529 \ub77c\ube14\ub9ac","p_etcaddr":"\uad34\uc815\ub3d9 73-4","p_shortaddr":"\uc11c\uad6c\uad34\uc815","p_clsfaddr":"\uad34\uc815 73-4","p_clldlvbrancd":"6438","p_clldlvbrannm":"\ub300\uc804\uc2e0\uc11c\uad6c\uac08\ub9c8","p_clldlcbranshortnm":"\ub300\uc804\uc2e0\uc11c\uad6c\uac08\ub9c8","p_clldlvempnum":"521259","p_clldlvempnm":"\uae40\uc601**","p_clldlvempnicknm":"D03","p_clsfcd":"1G41","p_clsfnm":"\ub300\ub355Sub","p_subclsfcd":"4","p_rspsdiv":"01","p_newaddryn":"N","p_errorcd":"0","p_errormsg":"\uc815\uc7ac\uc131\uacf5"}';

echo GetJson($rs['cj'],'p_clsfcd');//注意:$rs['cj']不要用cadd($rs['cj'])

*/


/*
	保存到数据为json格式
	
	返回如:
    [p_zipnum] => 35293
    [p_zipid] => 0
    [p_oldaddress] => 대전 서구 괴정동
    [p_oldaddressdtl] => 73-4번지 천연빌딩 라블리
    [p_newaddress] => 대전 서구 가장로
    [p_nesaddressdtl] => 52, 천연빌딩 라블리
    [p_etcaddr] => 괴정동 73-4
    [p_shortaddr] => 서구괴정
    [p_clsfaddr] => 괴정 73-4
    [p_clldlvbrancd] => 6438
    [p_clldlvbrannm] => 대전신서구갈마
    [p_clldlcbranshortnm] => 대전신서구갈마
    [p_clldlvempnum] => 521259
    [p_clldlvempnm] => 김영**
    [p_clldlvempnicknm] => D03
    [p_clsfcd] => 1G41
    [p_clsfnm] => 대덕Sub
    [p_subclsfcd] => 4
    [p_rspsdiv] => 01
    [p_newaddryn] => N
    [p_errorcd] => 0
    [p_errormsg] => 정재성공
*/

//从CJ平台获取数据
function f_juso_refine($conn_ora_juso_refine,$conn_ora_post,$p_clntnum,$p_clnmgmcustcd,$p_prngdivcd,$p_cgsts,$p_address,$p_postnum="")
{
	//用地址获取数据-开始--------------------------------------------------------------------
	$arr_imsi = array();
	$arr_post = array();
	$query ="DECLARE ";
	$query .="P_CLNTNUM VARCHAR2(400) := '$p_clntnum';";
	$query .="P_CLNTMGMCUSTCD VARCHAR2(400) := '$p_clnmgmcustcd';";
	$query .="P_PRNGDIVCD VARCHAR2(400) := '$p_prngdivcd';";
	$query .="P_CGOSTS VARCHAR2(400) := '$p_cgsts';";
	$query .="P_ADDRESS VARCHAR2(400) := '$p_address';";
	$query .="P_ZIPNUM VARCHAR2(400);";
	$query .="P_ZIPID VARCHAR2(400);";
	$query .="P_OLDADDRESS VARCHAR2(400);";
	$query .="P_OLDADDRESSDTL VARCHAR2(400);";
	$query .="P_NEWADDRESS VARCHAR2(400);";
	$query .="P_NESADDRESSDTL VARCHAR2(400);";
	$query .="P_ETCADDR VARCHAR2(400);";
	$query .="P_SHORTADDR VARCHAR2(400);";
	$query .="P_CLSFADDR VARCHAR2(400);";
	$query .="P_CLLDLVBRANCD VARCHAR2(400);";
	$query .="P_CLLDLVBRANNM VARCHAR2(400);";
	$query .="P_CLLDLCBRANSHORTNM VARCHAR2(400);";
	$query .="P_CLLDLVEMPNUM VARCHAR2(400);";
	$query .="P_CLLDLVEMPNM VARCHAR2(400);";
	$query .="P_CLLDLVEMPNICKNM VARCHAR2(400);";
	$query .="P_CLSFCD VARCHAR2(400);";
	$query .="P_CLSFNM VARCHAR2(400);";
	$query .="P_SUBCLSFCD VARCHAR2(400);";
	$query .="P_RSPSDIV VARCHAR2(400);";
	$query .="P_NEWADDRYN VARCHAR2(400);";
	$query .="P_ERRORCD VARCHAR2(400);";
	$query .="P_ERRORMSG VARCHAR2(400);";
	$query .="BEGIN ";
	$query .= " PKG_RVAP_ADDRSEARCH.PR_RVAP_SEARCHADDRESS (";
	$query .= " P_CLNTNUM";
	$query .= " , P_CLNTMGMCUSTCD";
	$query .= " , P_PRNGDIVCD";
	$query .= " , P_CGOSTS";
	$query .= " , P_ADDRESS";
	$query .= " , :P_ZIPNUM";
	$query .= " , :P_ZIPID";
	$query .= " , :P_OLDADDRESS";
	$query .= " , :P_OLDADDRESSDTL";
	$query .= " , :P_NEWADDRESS";
	$query .= " , :P_NESADDRESSDTL";
	$query .= " , :P_ETCADDR";
	$query .= " , :P_SHORTADDR";
	$query .= " , :P_CLSFADDR";
	$query .= " , :P_CLLDLVBRANCD";
	$query .= " , :P_CLLDLVBRANNM";
	$query .= " , :P_CLLDLCBRANSHORTNM";
	$query .= " , :P_CLLDLVEMPNUM";
	$query .= " , :P_CLLDLVEMPNM";
	$query .= " , :P_CLLDLVEMPNICKNM";
	$query .= " , :P_CLSFCD";
	$query .= " , :P_CLSFNM";
	$query .= " , :P_SUBCLSFCD";
	$query .= " , :P_RSPSDIV";
	$query .= " , :P_NEWADDRYN";
	$query .= " , :P_ERRORCD";
	$query .= " , :P_ERRORMSG";
	$query .= " );";
	$query .= "END;";
	$stid = oci_parse($conn_ora_juso_refine, $query);



	//打印参数(获取值)
	oci_bind_by_name($stid,":P_ZIPNUM", $arr_imsi["p_zipnum"] ,400);
	oci_bind_by_name($stid,":P_ZIPID", $arr_imsi["p_zipid"] ,400);
	oci_bind_by_name($stid,":P_OLDADDRESS", $arr_imsi["p_oldaddress"] ,400);
	oci_bind_by_name($stid,":P_OLDADDRESSDTL", $arr_imsi["p_oldaddressdtl"] ,400);
	oci_bind_by_name($stid,":P_NEWADDRESS", $arr_imsi["p_newaddress"] ,400);
	oci_bind_by_name($stid,":P_NESADDRESSDTL", $arr_imsi["p_nesaddressdtl"] ,400);
	oci_bind_by_name($stid,":P_ETCADDR", $arr_imsi["p_etcaddr"] ,400);
	oci_bind_by_name($stid,":P_SHORTADDR", $arr_imsi["p_shortaddr"] ,400);
	oci_bind_by_name($stid,":P_CLSFADDR", $arr_imsi["p_clsfaddr"] ,400);
	oci_bind_by_name($stid,":P_CLLDLVBRANCD", $arr_imsi["p_clldlvbrancd"] ,400);
	oci_bind_by_name($stid,":P_CLLDLVBRANNM", $arr_imsi["p_clldlvbrannm"] ,400);
	oci_bind_by_name($stid,":P_CLLDLCBRANSHORTNM",$arr_imsi["p_clldlcbranshortnm"],400);
	oci_bind_by_name($stid,":P_CLLDLVEMPNUM", $arr_imsi["p_clldlvempnum"] ,400);
	oci_bind_by_name($stid,":P_CLLDLVEMPNM", $arr_imsi["p_clldlvempnm"] ,400);
	oci_bind_by_name($stid,":P_CLLDLVEMPNICKNM", $arr_imsi["p_clldlvempnicknm"] ,400);
	oci_bind_by_name($stid,":P_CLSFCD", $arr_imsi["p_clsfcd"] ,400);
	oci_bind_by_name($stid,":P_CLSFNM", $arr_imsi["p_clsfnm"] ,400);
	oci_bind_by_name($stid,":P_SUBCLSFCD", $arr_imsi["p_subclsfcd"] ,400);
	oci_bind_by_name($stid,":P_RSPSDIV", $arr_imsi["p_rspsdiv"] ,400);
	oci_bind_by_name($stid,":P_NEWADDRYN", $arr_imsi["p_newaddryn"] ,400);
	oci_bind_by_name($stid,":P_ERRORCD", $arr_imsi["p_errorcd"] ,400);
	oci_bind_by_name($stid,":P_ERRORMSG", $arr_imsi["p_errormsg"] ,400);
	oci_execute($stid);
	oci_free_statement($stid);
	$arr_imsi = eval('return '.iconv("euc-kr","utf-8",var_export($arr_imsi,true)).';');//数组转编码 (返回是euc-kr,要转到utf-8)
	//print_r($arr_imsi);
	//用地址获取数据-结束--------------------------------------------------------------------
	
	
	
	
	
	
	
	//用邮编获取数据-开始--------------------------------------------------------------------
	if($arr_imsi["p_errorcd"]=="0"){ //顺利定制的话
	
		//echo '地址定制成功:<br>';
		return  $arr_imsi;//print_r($arr_imsi);
		
	}else{//没能顺利定制的时候
		if($p_postnum==""){//邮政编码没有的话
		
			//echo '地址定制失败:<br>';
			return  $arr_imsi;//print_r($arr_imsi);
			
		}else{//有邮政编码的话
			$query = "SELECT * FROM tb_post010 where zip_no='$p_postnum' ";
			$stid_post = oci_parse($conn_ora_post, $query);
			oci_execute($stid_post);
			$row = oci_fetch_array($stid_post, OCI_BOTH);
			if(oci_num_rows($stid_post)>0){
				
				
				$arr_imsi["p_zipnum"]=$row["ZIP_NO"]; //우편번호
				$arr_imsi["p_zipid"]=""; //우편번호ID
				$arr_imsi["p_oldaddress"]='';//ic($p_address);
				$arr_imsi["p_newaddress"]=""; //신주소
				$arr_imsi["p_nesaddressdtl"]=""; //신주소상세
				$arr_imsi["p_etcaddr"]=""; //기타주소
				$arr_imsi["p_shortaddr"]=""; //주소약칭
				$arr_imsi["p_clsfaddr"]=""; //상세번지
				$arr_imsi["p_clldlvbrancd"]=$row["MAN_BRAN_ID"];//집배송점소코드
				$arr_imsi["p_clldlvbrannm"]=$row["MAN_BRAN_NM"];//집배송점소명
				$arr_imsi["p_clldlcbranshortnm"]=$row["MAN_BRAN_NM"]; //집배송점소약칭
				$arr_imsi["p_clldlvempnum"]=""; //집배송사원번호
				$arr_imsi["p_clldlvempnm"]=$row["CLDV_EMP_NM"]; //집배송사원명
				$arr_imsi["p_clldlvempnicknm"]=""; //집배송사원분류코드
				$arr_imsi["p_clsfcd"]=$row["END_NO"]; //도착점코드
				$arr_imsi["p_clsfnm"]=$row["END_NM"]; //도착점명
				$arr_imsi["p_subclsfcd"]=$row["SUB_END_NO"]; //도착점서브코드
				$arr_imsi["p_rspsdiv"]=""; //전담구분
				$arr_imsi["p_newaddryn"]="N"; //도로명주소여부
				$arr_imsi["p_errorcd"]="0";
				$arr_imsi["p_errormsg"]='';
				//print_r($row);
			}
			$arr_imsi = eval('return '.iconv("euc-kr","utf-8",var_export($arr_imsi,true)).';');//数组转编码 (返回是euc-kr,要转到utf-8)
			
			//echo '邮编定制成功:<br>';
			return  $arr_imsi;//print_r($arr_imsi);
			//有2项空白:3=clsfaddr,6=clldlvempnicknm
		}
	//用邮编获取数据-结束--------------------------------------------------------------------
	}
		

}		
		
	
//oracle链接地址定制服务器 (地址定制)
function oracle_open_juso_refine($dns='CGIS') 
{
	global $cj_juso_account,$cj_juso_password;
	$user=$cj_juso_account;$passwd=$cj_juso_password;
	
	$dns = "(DESCRIPTION=
	(ADDRESS=(PROTOCOL=TCP)(HOST=203.248.116.111)(PORT=1521))
	(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME=CGISDEV)))"; 

	$conn = oci_connect($user, $passwd, $dns);
	if (!$conn) 
	{
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	return $conn;
}


//oracle 链接邮政编码真是服务器 (OPENDB 开发)
function oracle_open_post($dns='OPENDB') 
{
	global $cj_opendb_account,$cj_opendb_password;
	$user=$cj_opendb_account;$passwd=$cj_opendb_password;

	$dns = "(DESCRIPTION=
	(ADDRESS=(PROTOCOL=TCP)(HOST=210.98.159.153)(PORT=1523))
	(CONNECT_DATA = (SERVER = DEDICATED)(SID = OPENDBT)))"; 
	$conn = oci_connect($user, $passwd, $dns);
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	return $conn;
}
?>