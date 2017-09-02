<?php
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

$lx=par($_POST['lx']);
$ydid=intval($_POST['ydid']);
if($lx!='status_ajax'||!$ydid){exit('参数错误');}


$rs=FeData('yundan','ydid,ydh,status,statustime,api_status,api_time,api_comnu,gnkd,gnkdydh',"ydid='{$ydid}'");
//spr($rs['status'])=20;$rs['gnkd']='shentong';$rs['gnkdydh']=229251337880;//测试
if((spr($rs['status'])>=20&&$rs['gnkd']&&$rs['gnkdydh'])||(spr($rs['status'])>=13&&$rs['gnkd']))
{
	  $com=par($rs['gnkd']);//com快递公司代码
	  $nu=cadd($rs['gnkdydh']);//nu快递单号
	  if(!$nu){$nu=$rs['ydh'];}
	  
	  //API查询
	  $order='asc';
	  require($_SERVER['DOCUMENT_ROOT'].'/api/logistics/xingao.php');//返回$succeed，$data
	  
	  //输出json-开始
	  if($succeed&&is_json($data))
	  {
	  ?>
	  
		<!--显示状态内容-->
		<?php 
		$arr=(array)json_decode($data,true);
		if($arr['data'])
		{
			foreach($arr['data'] as $arrkey=>$value)
			{
			?>
			<tr class="odd gradeX">
				<td align="center" valign="middle" width="169"><?=$arr['data'][$arrkey]['time']?></td>
				<td align="left" valign="middle"><?=$arr['data'][$arrkey]['context']?></td>
			</tr>
			<?php
			}
		}
		?>
		
		
		<!--显示说明和更新时间-->
		<tr class="odd gradeX">
			<td align="center" valign="middle" width="169"></td>
			<td class="gray2" align="right" valign="middle" ><?=LGtag($LG['front.28'],'<tag1>=='.DateYmd($rs['api_time']).'||<tag2>=='.$uptime)?></td>
		</tr>
		
		  
	  <?php
	  }
	  //输出json-结束
  ?>
	  

      <!--预存不足或其他原因查不到时,调用百度查询-->
      <?php if(!$succeed&&$ON_baiduSearch){?>
       <tr class="odd gradeX">
         <td align="center" valign="middle" width="169"><?=$LG['front.26'];//由于查询太慢，已自动使用百度查询：?></td>
         <td align="center" valign="middle">
            <iframe src="http://www.baidu.com/baidu?word=<?=pregRep('\(','\)','',$expresses[$com])?> <?=$nu?>" width="100%" height="540" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no"></iframe>
          </td>
      </tr>
      <?php }?>	
    
    
<?php }?>
