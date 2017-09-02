<?php 
switch($sqllx)
{
	case 'article'://------------------------------------------------------------------------------------------------
		$allclassid=$classid.SmallClassID($classid);
		$order=' order by istop desc,isgood desc,ishead desc,edittime desc,addtime desc';//默认排序
		$query="select id,url{$LT},path,title{$LT},edittime,addtime,titlecolor,intro{$LT},img{$LT}{$field} from article where checked=1 and classid in ({$allclassid}) {$where} {$order} limit {$limit}";
		$sql=$xingao->query($query);
	break;
	
	case 'mall'://------------------------------------------------------------------------------------------------
		$allclassid=$classid.SmallClassID($classid);
		$order=' order by istop desc,isgood desc,ishead desc,edittime desc,addtime desc';//默认排序
		$query="select mlid,url{$LT},path,title{$LT},edittime,addtime,titlecolor,intro{$LT},titleimg{$LT},price{$field} from mall where checked=1 and classid in ({$allclassid}) and titleimg{$LT}<>'' {$where} {$order} limit {$limit}";
		$sql=$xingao->query($query);
	break;
}

//while($rs=$sql->fetch_array())//这行不能放这里,不会没有信息也会输出一条空信息
?>

