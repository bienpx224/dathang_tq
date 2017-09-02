<!--
    调用1:
    $classtag='1';//标记:同个页面,同个$classtype时,要有不同标记
    $classtype=1;//分类类型
    $classid=8;//已保存的ID
    require($_SERVER['DOCUMENT_ROOT'].'/public/classify.php');
        
    调用2:输出所有该类型的分类（一个下拉框）,不用调用此页面
    ClassifyAll($classtype,$zhi)

	获取ID:
    $classid=GetEndArr($_POST['classid'.$classtag.$classtype]);
    
    显示全部名称:
    echo classify($classid);
    
    显示该分类名称:
    echo classify($classid,2);
    
    显示全部ID:
    echo classify($classid,1);
    
    
    也可以用直接全部输出方式:
    <select  class="form-control input-medium select2me" data-placeholder="Select..." name="classid" >
    <option value="0" >根目录</option>
    < ?php LevelClassify(0,0,par($rs['classid']));?>
    </select>
    
-->



<?php
//输出位置:设置最多支持多少级
$max_level=20;
for ($level=1; $level<=$max_level; $level++)
{
    echo '<span id="classify'.$classtag.$classtype.'_'.$level.'"></span>';
}
$level=0;
if($classid){$classid_edit=classify($classid,1);}//修改时获取全部ID
?>








<script>
//显示清关资料联动分类下拉
function classify_show<?=$classtag.$classtype?>(bclassid,classid,level,tpy) 
{
	if(!level){level=0;}
	if(!isNaN(level)){level=parseInt(level);}
	level=level+1;
	
	var xmlhttp_classify=createAjax(); 
	if (xmlhttp_classify) 
	{  
		xmlhttp_classify.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp_classify.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp_classify.send('lx=classify&classtag=<?=$classtag?>&classtype=<?=$classtype?>&bclassid='+bclassid+'&classid='+classid+'&level='+level+'');

		xmlhttp_classify.onreadystatechange=function() 
		{  
			if (xmlhttp_classify.readyState==4 && xmlhttp_classify.status==200) 
			{ 
				document.getElementById('classify<?=$classtag.$classtype?>_'+level).innerHTML=unescape(xmlhttp_classify.responseText); 
				
				//不是修改输出时,清除上一个大分类的所有小分类
				if(tpy!='edit')
				{
					for($i=level; $i<=<?=$max_level?>; $i++)
					{
						level_now=$i+1;
						if(!document.getElementById('classify<?=$classtag.$classtype?>_'+level_now)){break;}
						document.getElementById('classify<?=$classtag.$classtype?>_'+level_now).innerHTML='';
					}
				}
				
				
			}
		}
	}
}
</script>




<script language="javascript">
	//显示清关资料联动分类下拉
	$(function(){   
		<?php
		if($classid_edit)
		{
			//修改时输出
			$arr=ToArr($classid_edit);$level=0;$bclassid_now=0;
			foreach($arr as $arrkey=>$value)
			{
				echo "classify_show{$classtag}{$classtype}({$bclassid_now},{$value},{$level},'edit');
				";
				$level++;
				$bclassid_now=$value;
			}
			echo "classify_show{$classtag}{$classtype}({$bclassid_now},{$value},{$level},'edit');";//后面多显示一级小分类
		}else{
			//新加时输出
			echo 'classify_show'.$classtag.$classtype.'();';
		}
		?>
		
	});
</script>


<?php
//清空 
$classtag=0;
$classtype=0;
$classid=0;
$classid_edit=0;
?>

