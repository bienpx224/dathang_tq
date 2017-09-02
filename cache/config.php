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
if(!defined('InXingAo')){exit('No InXingAo');}



	//首要配置和载入-------------------------------------------------------------
	//语言配置
	
	$ON_LG='1';//多语种
	$LGDefault='CN';//默认语种
	$ONLanguage='CN';//支持的语种
	$openLanguage='CN';//正式开放的语种
	
	//语言载入
	if(!$LGType){$LGType=3;require_once($_SERVER['DOCUMENT_ROOT'].'/Language/index.php');}
	if($LT=='CN'){$jishu= '兴奥转运系统';}else{$jishu='XingAo TS';}//后台系统名称
	
	//组文件放在前面,下面用到
	require_once($_SERVER['DOCUMENT_ROOT'].'/cache/member_per.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/cache/manage_per.php');
	
	
	
	
	//基本配置-开始-------------------------------------------------------------
	
	//语言字段处理++
	
		$sitenameCN='吉买国际转运平台';
		$sitetitleCN='中国转运|海淘转运|转运中国|海淘|转运|海外代购';
		$sitekeyCN='Jbuy,吉买,转运,代购,中国代购,淘宝代购,澳洲转运,中国转运,国际转运,海淘代购,物流';
		$sitetextCN='Jbuy吉买网是全球领先的国货代购平台，华人代购中国商品必选Jbuy，一站式代购淘宝、天猫、京东、当当等网站商品，终身全免服务费，Paypal、支付宝付款，国际邮费最低12元起。';
		$site_member_tsCN='十分抱歉:吉买网升级中,暂时不能登录,请稍后再试……';
		$site_manage_tsCN='十分抱歉:吉买网维护中,暂时不能登录,请稍后再试……';
		$bankAccountCN='<b>◆美国 （美元）</b><br>
美国银行卡账户（Bank Of America）    <br>                    
Name：Min zhang                     <br>
Account Number：483068918052     <br>
Routing Numbers：021000322       <br>
备注：客服号（例如01）<br>     
<br>
<b>◆澳大利亚 （澳币）</b><br>
commonwealth：        <br>
bsb：063109           <br>                      
acc：10969789         <br>                       
name：Boyu Cai        <br>
备注：客服号（例如01）<br>     
<br>
anz：               <br>
bsb：012475          <br>
acc：291075597       <br>
Name：yingfeng cai    <br>
备注：客服号（例如01）<br>
<br>
westpac：             <br>                
name： yingfeng cai    <br>               
bsb：032275            <br>
acc：325230             <br> 
备注：客服号（例如01）<br>
<br>
<b>◆中国 （人民币）</b><br>
支付宝账户：15921688717 <br>                
支付宝全名：蔡赢锋      <br>
备注：客服号（例如01）<br>
<br>
开户名字：刘菊             <br>
开户行：招商银行海门支行   <br>   
账号：6214 8551 3422 5016  <br>   
备注：客服号（例如01）    <br>
<br>
<b>◆其他国家 （其他货币）</b><br>
PayPal账户：journeymins@gmail.com   <br>  
收款人：Min Zhang                   <br>
备注：客服号（例如01）    <br>
<br>';
		$sendNameCN='JiYe Network Technology Co.,Ltd.';
		$sendMobileCN='15921688717';
		$sendTelCN='400-881-6851';
		$sendFaxCN='0513-82188169';
		$sendZipCN='226100';
		$sendAddCN='No.299 Haimeng Road.  Haimen Jiangsu  China.';
		$mallFAQCN='<div class=\"tit\"> 尊敬的用户，或许因为我们的描述不够完善，让您对海外存在疑问，对此我们深感歉意!我们统一海外物流部门、客服部门、售后部门，对于用户最关心的问题特别列出，您可以稍微花一点时间作了解，感谢您的理解，祝您选购愉悦! </div>
            <ul class=\"faq_ul list-paddingleft-2\" style=\"margin-top:20px;\">
              <li class=\"faq_question\">问：关于下单付款后多久能发货？</li>
              <li class=\"faq_answer\">答：付款后12小时内订单交付国外开始采购，采购时间2-5个工作日左右（海外双休），采购完毕即可发出；批量当天下午一点之前付款当天发出；自贸区付款12小时内订单移交上海自贸区，3-5个工作日左右发出。</li>
              <li class=\"faq_question\">问：关于我订购的产品未收到，不想要了，可以全额退还吗？</li>
              <li class=\"faq_answer\">答：付款后直接将您的订单移交国外，此时无法取消办理退款；如已出库后，此时我们是拦截不下来的，中间产生的快递费用需客户您自己承担（包括非质量问题拒签等等）；自贸区付款后12小时为期限，超过12小时后已将您的订单移交自贸区海关，此时无法取消，需等货物收到后拒签，中间产生的快递费用需要客户您自己承担。如订单付款后在有效时间内取消，退款金额将在12小时内自动返回到您的会员账号。（全额退款）</li>
              <li class=\"faq_question\">问：我的订单为什么不是统一地点发过来。比如有时显示深圳呢？</li>
              <li class=\"faq_answer\">答：各地保税区海关清关通关后，交由派送快递到您家，快递信息由清关地开始显示，而清关地点不一。您的订单是由广州保税区清关发出，故从深圳开始显示物流信息。</li>
              <li class=\"faq_question\">问：关于为什么我购买的产品没有发票呢？</li>
              <li class=\"faq_answer\">答：您下单为海外购物，为海外发货，无法开具国内的发票的，海外发达国家的发票为形式发票，为您提供电子以及购物凭证，有任何问题随时登陆网站或咨询客服，随时为您服务，全程负责您的货品。</li>
              <li class=\"faq_question\">问：关于批量为什么不能发顺丰呢？</li>
              <li class=\"faq_answer\">答：某些物品如粉末状，顺丰有空运以及陆运两种，粉末状是无法走顺丰空运，陆运的时效目前不及其他快递的时效高。</li>
              <li class=\"faq_question\">问：下单成功后一般多久能送到我手中？</li>
              <li class=\"faq_answer\">答：发货之日起一般15-30天左右到货，具体到货时间以每个国家以及海关的查验速度为准；具体到货时间可以根据所在地址、订购品牌等咨询在线客服为您详细解答。</li>
              <li class=\"faq_question\">问：关于同一款产品不同时间收到的包装/配方为什么有差异？</li>
              <li class=\"faq_answer\">答：所有产品全部是海外超市原则原产，包装规格以海外超市实际情况为准，海外产品部不定期更换包装以及产品规格配方含量等，所以客户不同时间购买所收到产品包装会有不同（或与网站参考不同）。因为客户所购买产品均是第一时间在海外超市采购，所以日期包装都很新。网页包装更新稍慢，仅供参考，具体以您收到的实际货物为准。</li>
              
            </ul>
            <div class=\"tit\"> <span>注意：</span>由于欧盟为环保包装，纸罐以及锡纸在运输的过程中容易破损，承诺为您“爆罐必赔，服务无忧”。请您在收货的时候务必验货，发现包装损坏，影响使用的情况，务必及时联系我们，并当场拒收。如您签收后发现，则无法判断是否是发货以及物流的问题，请您务必做好验货工作,感谢您对我们工作的理解与支持! </div>';
		$member_reg_sendmailCN='欢迎加入[!--sitename--]
[!--siteurl--]
您的登录账号：[!--username--]

我们对您的加入表示最热烈的欢迎！衷心希望您能愉快使用我们的物流转运，在使用中如果遇到什么问题欢迎您的专属客服咨询(客服号:[!--CustomerService.0--]姓名:[!--CustomerService.1--]电话号码:[!--CustomerService.2--]微信:[!--CustomerService.4--])，如果有更好的意见或建议欢迎向我们提出反馈！';
		$member_birthday_contentCN='[!--truename--] 您好，在您的生日到来之际，[!--CustomerService.1--]诚挚地献上我们的三个祝愿：一愿您身体健康；二愿您幸福快乐；三愿您万事如意；另外赠送您[!--birthday_integral--]积分，以感激您对【[!--sitename--]】的支持！';
		$smtp_nameCN='吉买国际转运平台';
		$csCN='<div class=\"toolbar-ico code wechat\"> <i></i>
  <p class=\"xa_kefu\">
    <span class=\"xa_sj\"><img src=\"/images/sj.png\"></span>
	
    <span class=\"text\">个人咨询</span>
    <span class=\"code\"> <a href=\"http://wpa.qq.com/msgrd?v=3&uin=532580849&site=qq&menu=yes\" class=\"qq_icon\"  target=\"_blank\" ><img src=\"http://wpa.qq.com/pa?p=1:532580849:16\"></a></span>
	
    <span class=\"text\">企业咨询</span>
    <span class=\"code\"><a href=\"http://wpa.qq.com/msgrd?v=3&uin=532580849&site=qq&menu=yes\" class=\"qq_icon\"  target=\"_blank\" ><img src=\"http://wpa.qq.com/pa?p=1:532580849:16\"></a></span>

 
 

	
  </p>
</div>
<div class=\"toolbar-ico code\"> <i></i>
  <p>
    <span class=\"xa_sj\"><img src=\"/images/sj.png\"></span>
    <span class=\"code\"><img src=\"/images/jbuywx.jpg\"></span>
  </p>
</div>
<div class=\"toolbar-ico code my-collection\"> <i></i>
  <p class=\"xa_dianhua\">
    <span class=\"xa_sj\"><img src=\"/images/sj.png\"></span>
    
    <span class=\"on\">电话咨询:<br/>400-881-6851<br/>中国客服中心:<br/>+86051382188169<br/>澳洲客服中心:<br/>+0291192000</span>

  </p>
</div>';
		$cs_mCN='<div class=\"toolbar-ico code wechat\"> <i></i>
  <p class=\"xa_kefu\">
    <span class=\"xa_sj\"><img src=\"/images/sj.png\"></span>
	
    <span class=\"text\">个人咨询</span>
    <span class=\"code\"> QQ：532580849</span>
	
    <span class=\"text\">企业咨询</span>
    <span class=\"code\"> QQ：532580849</span>
	
  </p>
</div>
<div class=\"toolbar-ico code\"> <i></i>
  <p>
    <span class=\"xa_sj\"><img src=\"/images/sj.png\"></span>
    <span class=\"code\">微信公众号：jbuy123</span>
  </p>
</div>
<div class=\"toolbar-ico code my-collection\"> <i></i>
  <p class=\"xa_dianhua\">
    <span class=\"xa_sj\"><img src=\"/images/sj.png\"></span>

    <span class=\"on\">电话咨询:<br/>400-881-6851<br/>+86 0513-82188169</span>

  </p>
</div>';
		$shaidan_explainCN='在以下网址晒单才获得积分<br>
www.taobao.com<br>
www.jd.com';
		
		//运单服务选项-名称
		$op_bgfee1_nameCN='内件清点';
		$op_bgfee2_nameCN='加固方式';
		$op_wpfee1_nameCN='剪吊牌';
		$op_wpfee2_nameCN='';
		$op_ydfee1_nameCN='清理减重';
		$op_ydfee2_nameCN='购买箱子';
		$op_free_nameCN='原包装盒';
		$op_freearr_nameCN='免费服务';
		//运单服务选项-提示说明
		$op_bgfee1_pptCN='会同时检查是否有违禁物品，包括包装上的价格，干燥剂，赠品，分离的配饰，金属成分等影响清关的内容物';
		$op_bgfee2_pptCN='';
		$op_wpfee1_pptCN='';
		$op_wpfee2_pptCN='';
		$op_ydfee1_pptCN='';
		$op_ydfee2_pptCN='';
		$op_free_pptCN='';
		$op_freearr_pptCN='';
		
			$op_bgfee1_val0CN='不清点';
			$op_bgfee2_val0CN='不加固';
			$op_wpfee1_val0CN='保留原样';
			$op_wpfee2_val0CN='';
			$op_ydfee1_val0CN='不清理';
			$op_ydfee2_val0CN='不购买';
			$op_free_val0CN='保留原样';
			$op_freearr_val0CN='';
			
			$op_bgfee1_val1CN='简单清点';
			$op_bgfee2_val1CN='简单加固';
			$op_wpfee1_val1CN='剪掉并丢弃';
			$op_wpfee2_val1CN='';
			$op_ydfee1_val1CN='简单清理';
			$op_ydfee2_val1CN='小箱子';
			$op_free_val1CN='保留原包装盒（拆后运回）';
			$op_freearr_val1CN='(1)取出箱内所有的发票，小票，订货单及一切文件和广告；';
			
			$op_bgfee1_val2CN='大件清点';
			$op_bgfee2_val2CN='标准加固';
			$op_wpfee1_val2CN='';
			$op_wpfee2_val2CN='';
			$op_ydfee1_val2CN='清理大件';
			$op_ydfee2_val2CN='中箱子';
			$op_free_val2CN='去掉原包装盒';
			$op_freearr_val2CN='(2)取出箱内杂志、冰袋、衣架；（注意:可能会造成褶皱、磨损等情况）';
			
			$op_bgfee1_val3CN='全部清点';
			$op_bgfee2_val3CN='升级加固';
			$op_wpfee1_val3CN='';
			$op_wpfee2_val3CN='';
			$op_ydfee1_val3CN='全部清理';
			$op_ydfee2_val3CN='大箱子';
			$op_free_val3CN='';
			$op_freearr_val3CN='(3)取出包内和鞋内所有填充物；';
			
			$op_bgfee1_val4CN='';
			$op_bgfee2_val4CN='';
			$op_wpfee1_val4CN='';
			$op_wpfee2_val4CN='';
			$op_ydfee1_val4CN='';
			$op_ydfee2_val4CN='特大箱子';
			$op_free_val4CN='';
			$op_freearr_val4CN='(4)保留原快递外箱，即使产生体积费；（不选此项的话，仓库会通过改箱换箱等尽量减少体积费，但是不会拆除货品的原包装）';
			
			$op_bgfee1_val5CN='';
			$op_bgfee2_val5CN='';
			$op_wpfee1_val5CN='';
			$op_wpfee2_val5CN='';
			$op_ydfee1_val5CN='';
			$op_ydfee2_val5CN='';
			$op_free_val5CN='';
			$op_freearr_val5CN='';
			
			$op_bgfee1_val6CN='';
			$op_bgfee2_val6CN='';
			$op_wpfee1_val6CN='';
			$op_wpfee2_val6CN='';
			$op_ydfee1_val6CN='';
			$op_ydfee2_val6CN='';
			$op_free_val6CN='';
			$op_freearr_val6CN='';
			
			$op_bgfee1_val7CN='';
			$op_bgfee2_val7CN='';
			$op_wpfee1_val7CN='';
			$op_wpfee2_val7CN='';
			$op_ydfee1_val7CN='';
			$op_ydfee2_val7CN='';
			$op_free_val7CN='';
			$op_freearr_val7CN='';
			
			$op_bgfee1_val8CN='';
			$op_bgfee2_val8CN='';
			$op_wpfee1_val8CN='';
			$op_wpfee2_val8CN='';
			$op_ydfee1_val8CN='';
			$op_ydfee2_val8CN='';
			$op_free_val8CN='';
			$op_freearr_val8CN='';
			
			$op_bgfee1_val9CN='';
			$op_bgfee2_val9CN='';
			$op_wpfee1_val9CN='';
			$op_wpfee2_val9CN='';
			$op_ydfee1_val9CN='';
			$op_ydfee2_val9CN='';
			$op_free_val9CN='';
			$op_freearr_val9CN='';
			
			$op_bgfee1_val10CN='';
			$op_bgfee2_val10CN='';
			$op_wpfee1_val10CN='';
			$op_wpfee2_val10CN='';
			$op_ydfee1_val10CN='';
			$op_ydfee2_val10CN='';
			$op_free_val10CN='';
			$op_freearr_val10CN='';
			
	  //自动显示当前语种名称
	  
	  $joint='sitename'.$LT; $sitename=$$joint;
	  $joint='sitetitle'.$LT; $sitetitle=$$joint;
	  $joint='sitekey'.$LT; $sitekey=$$joint;
	  $joint='sitetext'.$LT; $sitetext=$$joint;
	  $joint='site_member_ts'.$LT; $site_member_ts=$$joint;
	  $joint='site_manage_ts'.$LT; $site_manage_ts=$$joint;
	  $joint='bankAccount'.$LT; $bankAccount=$$joint;
	  
	  //已改用function调用,支持指定语种
	  /*
	  $joint='sendName'.$LT; $sendName=$$joint;
	  $joint='sendMobile'.$LT; $sendMobile=$$joint;
	  $joint='sendTel'.$LT; $sendTel=$$joint;
	  $joint='sendFax'.$LT; $sendFax=$$joint;
	  $joint='sendZip'.$LT; $sendZip=$$joint;
	  $joint='sendAdd'.$LT; $sendAdd=$$joint;
	  */
	  
	  $joint='mallFAQ'.$LT; $mallFAQ=$$joint;
	  $joint='member_reg_sendmail'.$LT; $member_reg_sendmail=$$joint;
	  $joint='member_birthday_content'.$LT; $member_birthday_content=$$joint;
	  $joint='smtp_name'.$LT; $smtp_name=$$joint;
	  $joint='cs'.$LT; $cs=$$joint;
	  $joint='cs_m'.$LT; $cs_m=$$joint;
	  $joint='shaidan_explain'.$LT; $shaidan_explain=$$joint;
	  
	  $joint='op_bgfee1_name'.$LT; $op_bgfee1_name=$$joint;
	  $joint='op_bgfee2_name'.$LT; $op_bgfee2_name=$$joint;
	  $joint='op_wpfee1_name'.$LT; $op_wpfee1_name=$$joint;
	  $joint='op_wpfee2_name'.$LT; $op_wpfee2_name=$$joint;
	  $joint='op_ydfee1_name'.$LT; $op_ydfee1_name=$$joint;
	  $joint='op_ydfee2_name'.$LT; $op_ydfee2_name=$$joint;
	  $joint='op_free_name'.$LT;	 $op_free_name=$$joint;
	  $joint='op_freearr_name'.$LT; $op_freearr_name=$$joint;
	  
	  $joint='op_bgfee1_ppt'.$LT; $op_bgfee1_ppt=$$joint;
	  $joint='op_bgfee2_ppt'.$LT; $op_bgfee2_ppt=$$joint;
	  $joint='op_wpfee1_ppt'.$LT; $op_wpfee1_ppt=$$joint;
	  $joint='op_wpfee2_ppt'.$LT; $op_wpfee2_ppt=$$joint;
	  $joint='op_ydfee1_ppt'.$LT; $op_ydfee1_ppt=$$joint;
	  $joint='op_ydfee2_ppt'.$LT; $op_ydfee2_ppt=$$joint;
	  $joint='op_free_ppt'.$LT;	 $op_free_ppt=$$joint;
	  $joint='op_freearr_ppt'.$LT; $op_freearr_ppt=$$joint;
	  
		  $joint='op_bgfee1_val0'.$LT; $op_bgfee1_val0=$$joint;
		  $joint='op_bgfee2_val0'.$LT; $op_bgfee2_val0=$$joint;
		  $joint='op_wpfee1_val0'.$LT; $op_wpfee1_val0=$$joint;
		  $joint='op_wpfee2_val0'.$LT; $op_wpfee2_val0=$$joint;
		  $joint='op_ydfee1_val0'.$LT; $op_ydfee1_val0=$$joint;
		  $joint='op_ydfee2_val0'.$LT; $op_ydfee2_val0=$$joint;
		  $joint='op_free_val0'.$LT;   $op_free_val0=$$joint;
		  $joint='op_freearr_val0'.$LT; $op_freearr_val0=$$joint;
		  
		  $joint='op_bgfee1_val1'.$LT; $op_bgfee1_val1=$$joint;
		  $joint='op_bgfee2_val1'.$LT; $op_bgfee2_val1=$$joint;
		  $joint='op_wpfee1_val1'.$LT; $op_wpfee1_val1=$$joint;
		  $joint='op_wpfee2_val1'.$LT; $op_wpfee2_val1=$$joint;
		  $joint='op_ydfee1_val1'.$LT; $op_ydfee1_val1=$$joint;
		  $joint='op_ydfee2_val1'.$LT; $op_ydfee2_val1=$$joint;
		  $joint='op_free_val1'.$LT;   $op_free_val1=$$joint;
		  $joint='op_freearr_val1'.$LT; $op_freearr_val1=$$joint;
		  
		  $joint='op_bgfee1_val2'.$LT; $op_bgfee1_val2=$$joint;
		  $joint='op_bgfee2_val2'.$LT; $op_bgfee2_val2=$$joint;
		  $joint='op_wpfee1_val2'.$LT; $op_wpfee1_val2=$$joint;
		  $joint='op_wpfee2_val2'.$LT; $op_wpfee2_val2=$$joint;
		  $joint='op_ydfee1_val2'.$LT; $op_ydfee1_val2=$$joint;
		  $joint='op_ydfee2_val2'.$LT; $op_ydfee2_val2=$$joint;
		  $joint='op_free_val2'.$LT;   $op_free_val2=$$joint;
		  $joint='op_freearr_val2'.$LT; $op_freearr_val2=$$joint;
		  
		  $joint='op_bgfee1_val3'.$LT; $op_bgfee1_val3=$$joint;
		  $joint='op_bgfee2_val3'.$LT; $op_bgfee2_val3=$$joint;
		  $joint='op_wpfee1_val3'.$LT; $op_wpfee1_val3=$$joint;
		  $joint='op_wpfee2_val3'.$LT; $op_wpfee2_val3=$$joint;
		  $joint='op_ydfee1_val3'.$LT; $op_ydfee1_val3=$$joint;
		  $joint='op_ydfee2_val3'.$LT; $op_ydfee2_val3=$$joint;
		  $joint='op_free_val3'.$LT;   $op_free_val3=$$joint;
		  $joint='op_freearr_val3'.$LT; $op_freearr_val3=$$joint;
		  
		  $joint='op_bgfee1_val4'.$LT; $op_bgfee1_val4=$$joint;
		  $joint='op_bgfee2_val4'.$LT; $op_bgfee2_val4=$$joint;
		  $joint='op_wpfee1_val4'.$LT; $op_wpfee1_val4=$$joint;
		  $joint='op_wpfee2_val4'.$LT; $op_wpfee2_val4=$$joint;
		  $joint='op_ydfee1_val4'.$LT; $op_ydfee1_val4=$$joint;
		  $joint='op_ydfee2_val4'.$LT; $op_ydfee2_val4=$$joint;
		  $joint='op_free_val4'.$LT;   $op_free_val4=$$joint;
		  $joint='op_freearr_val4'.$LT; $op_freearr_val4=$$joint;
		  
		  $joint='op_bgfee1_val5'.$LT; $op_bgfee1_val5=$$joint;
		  $joint='op_bgfee2_val5'.$LT; $op_bgfee2_val5=$$joint;
		  $joint='op_wpfee1_val5'.$LT; $op_wpfee1_val5=$$joint;
		  $joint='op_wpfee2_val5'.$LT; $op_wpfee2_val5=$$joint;
		  $joint='op_ydfee1_val5'.$LT; $op_ydfee1_val5=$$joint;
		  $joint='op_ydfee2_val5'.$LT; $op_ydfee2_val5=$$joint;
		  $joint='op_free_val5'.$LT;   $op_free_val5=$$joint;
		  $joint='op_freearr_val5'.$LT; $op_freearr_val5=$$joint;
		  
		  $joint='op_bgfee1_val6'.$LT; $op_bgfee1_val6=$$joint;
		  $joint='op_bgfee2_val6'.$LT; $op_bgfee2_val6=$$joint;
		  $joint='op_wpfee1_val6'.$LT; $op_wpfee1_val6=$$joint;
		  $joint='op_wpfee2_val6'.$LT; $op_wpfee2_val6=$$joint;
		  $joint='op_ydfee1_val6'.$LT; $op_ydfee1_val6=$$joint;
		  $joint='op_ydfee2_val6'.$LT; $op_ydfee2_val6=$$joint;
		  $joint='op_free_val6'.$LT;   $op_free_val6=$$joint;
		  $joint='op_freearr_val6'.$LT; $op_freearr_val6=$$joint;
		  
		  $joint='op_bgfee1_val7'.$LT; $op_bgfee1_val7=$$joint;
		  $joint='op_bgfee2_val7'.$LT; $op_bgfee2_val7=$$joint;
		  $joint='op_wpfee1_val7'.$LT; $op_wpfee1_val7=$$joint;
		  $joint='op_wpfee2_val7'.$LT; $op_wpfee2_val7=$$joint;
		  $joint='op_ydfee1_val7'.$LT; $op_ydfee1_val7=$$joint;
		  $joint='op_ydfee2_val7'.$LT; $op_ydfee2_val7=$$joint;
		  $joint='op_free_val7'.$LT;   $op_free_val7=$$joint;
		  $joint='op_freearr_val7'.$LT; $op_freearr_val7=$$joint;
		  
		  $joint='op_bgfee1_val8'.$LT; $op_bgfee1_val8=$$joint;
		  $joint='op_bgfee2_val8'.$LT; $op_bgfee2_val8=$$joint;
		  $joint='op_wpfee1_val8'.$LT; $op_wpfee1_val8=$$joint;
		  $joint='op_wpfee2_val8'.$LT; $op_wpfee2_val8=$$joint;
		  $joint='op_ydfee1_val8'.$LT; $op_ydfee1_val8=$$joint;
		  $joint='op_ydfee2_val8'.$LT; $op_ydfee2_val8=$$joint;
		  $joint='op_free_val8'.$LT;   $op_free_val8=$$joint;
		  $joint='op_freearr_val8'.$LT; $op_freearr_val8=$$joint;
		  
		  $joint='op_bgfee1_val9'.$LT; $op_bgfee1_val9=$$joint;
		  $joint='op_bgfee2_val9'.$LT; $op_bgfee2_val9=$$joint;
		  $joint='op_wpfee1_val9'.$LT; $op_wpfee1_val9=$$joint;
		  $joint='op_wpfee2_val9'.$LT; $op_wpfee2_val9=$$joint;
		  $joint='op_ydfee1_val9'.$LT; $op_ydfee1_val9=$$joint;
		  $joint='op_ydfee2_val9'.$LT; $op_ydfee2_val9=$$joint;
		  $joint='op_free_val9'.$LT;   $op_free_val9=$$joint;
		  $joint='op_freearr_val9'.$LT; $op_freearr_val9=$$joint;
		  
		  $joint='op_bgfee1_val10'.$LT; $op_bgfee1_val10=$$joint;
		  $joint='op_bgfee2_val10'.$LT; $op_bgfee2_val10=$$joint;
		  $joint='op_wpfee1_val10'.$LT; $op_wpfee1_val10=$$joint;
		  $joint='op_wpfee2_val10'.$LT; $op_wpfee2_val10=$$joint;
		  $joint='op_ydfee1_val10'.$LT; $op_ydfee1_val10=$$joint;
		  $joint='op_ydfee2_val10'.$LT; $op_ydfee2_val10=$$joint;
		  $joint='op_free_val10'.$LT;   $op_free_val10=$$joint;
		  $joint='op_freearr_val10'.$LT; $op_freearr_val10=$$joint;
		  
	$siteurl='http://www.jbuy.com.au/';
	$off_site_member='';
	$off_site_manage='';
	$off_member_nav='1';
	
	$traffic='http://tongji.baidu.com/';
	$manage_login_yz='';
	$manage_login_limit='5';
	$manage_limit_time='60';
	$theme_member='light2.css';
	$theme_member_ico='1';
	$theme_manage='light2.css';
	$theme_manage_ico='1';
	
	//验证码
	$off_code_managelogin='1';
	$off_code_reg='';
	$off_code_login='1';
	$off_code_liuyan='1';
	$off_code_shangpin_sp='1';
	$off_code_shaidan_sp='1';

	//系统功能开关(部分不能用spr)
	$ON_daigou='1';
	$off_shaidan='1';
	$off_mall='1';
	$off_integral='1';
	$ON_gd_japan='1';
	$shaidan_checked='1';
	$comments_checked='1';
	$off_api='1';
	$off_delbak='1';
	
	$ON_MusicYes='1';
	$ON_MusicNo='1';

	//其他配置
	$off_water='1';
	$water_lx='2';
	$water_file='/images/water.jpg';
	$water_font='吉买国际转运平台';
	$water_font_size='12';
	$water_font_length='60';
	$water_font_color='#ffffff';
	$water_location='8';
	$water_tran='50';
	$off_narrow='';
	$certi_w='1500';
	$certi_h='2000';
	$other_w='1000';
	$other_h='2000';
	$image_size='2000';
	$image_ext='.gif|.jpg|.png|.jpeg|.bmp';
	$file_size='2000';
	$file_ext='.gif|.jpg|.png|.jpeg|.bmp|.doc|.docx|.xls|.xlsx|.ppt|.txt|.zip|.rar|.gz|.bz2|.7z|.pdf';
	$media_size='2000';
	$media_ext='.flv|.mp3|.wav|.wma|.wmv|.mid|.avi|.mpg|.asf|.rm|.rmvb';
	$flash_size='2000';
	$flash_ext='.swf';

	//主要配置
	$XAMcurrency='CNY';
	$XAmc='元';
	$XAScurrency='USD';
	$XAsc='美元';

	$XAwt='kg';
	$XAwtkg='1';
	$XAsz='cm';
	$XAszcm='1';
	$ON_bankAccount='1';
	$bankAccountLock='7';
	
	
	//包裹
	$off_hx='1';
	$off_fx='1';
	$off_baoguo='1';
	$off_baoguo_yubao='1';
	$off_baoguo_op_02='1';
	$off_baoguo_op_04='1';
	$off_tra_user='1';
	$off_baoguo_op_06='1';
	$off_baoguo_op_07='1';
	$off_baoguo_th='1';
	$off_baoguo_op_09='1';
	$off_baoguo_op_10='1';
	$off_baoguo_op_11='1';
	$off_edit_wp='1';
	$off_baoguo_zxyd='1';
	$ON_ware='1';
	$ON_nickname='1';
	$bg_ware_msg='0';
	$baoguo_ruku_msg='1';
	$baoguo_ruku_mail='0';
	$baoguo_ruku_sms='0';
	$baoguo_ruku_wx='1';
	
	$baoguo_hx_msg='1';
	$baoguo_hx_mail='0';
	$baoguo_hx_sms='0';
	$baoguo_hx_wx='1';

	$baoguo_fx_msg='1';
	$baoguo_fx_mail='0';
	$baoguo_fx_sms='0';
	$baoguo_fx_wx='1';

	$baoguo_ruku_od='0';
	$baoguo_qr='1';
	$bg_shelves='0';
	$baoguo_smws='1';
	$baoguo_req_weight='0';
	$baoguo_req_whPlace='0';
	$shelves_req_whPlace='0';
	$baoguo_fg='1';
	$baoguo_fgzs='8';
	$baoguo_fg_type='4';
	
	$ON_wupin_type='0';
	$ON_wupin_name='1';
	$ON_wupin_brand='1';
	$ON_wupin_spec='1';
	$ON_wupin_price='1';
	$ON_wupin_unit='1';
	$ON_wupin_number='1';
	$ON_wupin_price='1';
	$ON_wupin_total='1';
	$ON_wupin_weight='1';

	$wupin_req_type='0';
	$wupin_req_name='1';
	$wupin_req_brand='0';
	$wupin_req_spec='0';
	$wupin_req_price='1';
	$wupin_req_unit='0';
	$wupin_req_number='1';
	$wupin_req_price='1';
	$wupin_req_total='0';
	$wupin_req_weight='0';

	//积分
	$integral_bili='500';
	$integral_1='0';
	$integral_2='10000';
	$integral_3='10000';
	$integral_4='1';
	$integral_5='0';
	$integral_MemberBirthday='100';
	$integral_shaidan='10';
	$integral_xhysf='10';
	$integral_daigou='10';
	$integral_yundan='1';
	$integral_mall='1';
	
	$ON_country='1';//是否有多国家 0只有一个国家,1有多国家
	$openCountry='142,116,122,133,303,304,305,307,501,502,601,609';//国家
	$openCurrency='CNY,USD,AUD';//币种
	$oneCurrency='0';//是否只有一个币种
	$ON_exchange='1';
	$JSCurrency='CNY';
	
	$ydh_tpre='';
	$ydh_typ='8';
	$ydh_suffix='';
	$ON_yundan_prepay='0';

	$yundan_fee_msg='1';
	$yundan_fee_mail='0';
	$yundan_fee_sms='0';
	$yundan_fee_wx='1';
	$yundan_fee_settlement='1';
	$uretion='';
	
	//运单附加服务
	
	
	//收件人/发件人
	$off_shenfenzheng='1';
	$off_upload_cert='1';
	
	$off_fajian='1';
	$f_name_req='1';
	$f_mobile_code_req='1';
	$f_mobile_req='1';
	$f_tel_req='0';
	$f_zip_req='0';
	$f_add_shengfen_req='0';
	$f_add_chengshi_req='0';
	$f_add_quzhen_req='0';
	$f_add_dizhi_req='1';


	$yundan_paysucc_msg='1';
	$yundan_paysucc_mail='0';
	$yundan_paysucc_sms='0';
	$yundan_paysucc_wx='1';
	
	$yundan_payfail_msg='0';
	$yundan_payfail_mail='0';
	$yundan_payfail_sms='0';
	$yundan_payfail_wx='0';

	$yundan_del_time='';
	$ON_cardInstead='1';
	$cardInstead_time='3';
	
	$ON_baiduSearch='1';
	$ON_SonWebsite_main='0';
	$SonWebsiteList='xa,us=zy';
	$ON_SonWebsite='0';
	
	//代购
	$dg_tpre='';
	$dg_typ='8';
	$dg_suffix='';
	$dg_checked='1';
	$dg_enquiry='1';
	$dg_whcodTpre='';
	$dg_whcodLength='5';
	$dg_openCurrency='CNY,USD,AUD';
	$me_openCurrency='CNY';
	$dg_ware_msg='0';
	
	$daigou_IncCost_msg='1';
	$daigou_IncCost_mail='0';
	$daigou_IncCost_sms='0';
	$daigou_IncCost_wx='1';
	
	$daigou_inStorage_msg='1';
	$daigou_inStorage_mail='0';
	$daigou_inStorage_sms='0';
	$daigou_inStorage_wx='1';
	
	$daigou_managePay_msg='1';
	$daigou_managePay_mail='0';
	$daigou_managePay_sms='0';
	$daigou_managePay_wx='0';
	
	$daigou_manageRef_msg='1';
	$daigou_manageRef_mail='0';
	$daigou_manageRef_sms='0';
	$daigou_manageRef_wx='0';
	
	$daigou_status_msg0='1';$daigou_status_mail0='0';$daigou_status_sms0='0';$daigou_status_wx0='0';$daigou_status_msg1='1';$daigou_status_mail1='0';$daigou_status_sms1='0';$daigou_status_wx1='0';$daigou_status_msg2='1';$daigou_status_mail2='0';$daigou_status_sms2='0';$daigou_status_wx2='0';$daigou_status_msg3='1';$daigou_status_mail3='0';$daigou_status_sms3='0';$daigou_status_wx3='0';$daigou_status_msg4='1';$daigou_status_mail4='0';$daigou_status_sms4='0';$daigou_status_wx4='0';$daigou_status_msg5='1';$daigou_status_mail5='0';$daigou_status_sms5='0';$daigou_status_wx5='0';$daigou_status_msg6='1';$daigou_status_mail6='0';$daigou_status_sms6='0';$daigou_status_wx6='1';$daigou_status_msg7='1';$daigou_status_mail7='0';$daigou_status_sms7='0';$daigou_status_wx7='0';$daigou_status_msg8='1';$daigou_status_mail8='0';$daigou_status_sms8='0';$daigou_status_wx8='0';$daigou_status_msg9='1';$daigou_status_mail9='0';$daigou_status_sms9='0';$daigou_status_wx9='1';$daigou_status_msg10='1';$daigou_status_mail10='0';$daigou_status_sms10='0';$daigou_status_wx10='0';
	//商城
	$off_mall_checked='1';
	$mall_order_time='12';
	$mall_order_pay_msg='1';
	$mall_order_pay_mail='';
	$mall_order_pay_sms='';
	$mall_order_pay_wx='1';
	$derstan='';

	//会员
	$off_member_reg='1';
	$off_reg_mobile='1';
	$off_reg_email='0';
	$member_reg_lx='1';
	$member_reg_key='admin,administrator,管理,维护,后台,活动,客服,吉买';
	$memberid_tpre='';
	$member_tpreic='';
	$member_ic='5';
	$member_reg_sh='0';
	$member_sh_msg='1';
	$member_sh_mail='0';
	$member_sh_sms='0';
	$member_sh_wx='1';
	$off_member_reg_sendmail='1';
	
	$ON_MemberAutoLogin='1';
	$ON_MemberClient='1';
	$member_getpw_number='5';
	$member_prompt_time='120';

	$member_birthday_msg='1';
	$member_birthday_mail='1';
	$member_birthday_sms='0';
	$member_birthday_wx='1';

	
	$off_certification='0';
	$certification_baoguo='1';
	$certification_yundan='1';
	$certification_mall_order='1';
	$certification_daigou='1';
	$certification_qujian='1';

	$certification_ct1='1';
	$certification_ct2='0';
	$certification_ct3='0';
	
	//推广
	$off_tuiguang='1';
	$tuiguang_tgy='100';
	$tuiguang_xhy='100';
	$tuiguang_tgyip='1';
	$tuiguang_xhyip='1';
	$tuiguang_xhysj='10';
	$tuiguang_xhymc='80';
	$tuiguang_tgyhdcs='5';
	$tuiguang_xhyhdcs='1';
	
	$tuiguang_hqsf='365';
	$tuiguang_ydxf_sl='0';
	$tuiguang_ydxf_bl='10';
	$tuiguang_mallxf_sl='0';
	$tuiguang_mallxf_bl='10';
	$tuiguang_dgxf_sl='0';
	$tuiguang_dgxf_bl='10';

	
			//推广:生成优惠券/折扣券参数
			$regcp_types1='1';
			$regcp_usetypes1='1';
			$regcp_value1='5';
			$regcp_limitmoney1='98';
			$regcp_code_digits1='8';
			$regcp_number1='2';
			$regcp_overdue1='60';
			
			//推广:生成优惠券/折扣券参数
			$regcp_types2='1';
			$regcp_usetypes2='1';
			$regcp_value2='10';
			$regcp_limitmoney2='300';
			$regcp_code_digits2='8';
			$regcp_number2='4';
			$regcp_overdue2='60';
			
			//推广:生成优惠券/折扣券参数
			$regcp_types3='1';
			$regcp_usetypes3='1';
			$regcp_value3='20';
			$regcp_limitmoney3='500';
			$regcp_code_digits3='8';
			$regcp_number3='5';
			$regcp_overdue3='60';
			
			//推广:生成优惠券/折扣券参数
			$tgycp_types1='1';
			$tgycp_usetypes1='0';
			$tgycp_value1='5';
			$tgycp_limitmoney1='98';
			$tgycp_code_digits1='8';
			$tgycp_number1='5';
			$tgycp_overdue1='60';
			
			//推广:生成优惠券/折扣券参数
			$tgycp_types2='1';
			$tgycp_usetypes2='0';
			$tgycp_value2='10';
			$tgycp_limitmoney2='300';
			$tgycp_code_digits2='8';
			$tgycp_number2='4';
			$tgycp_overdue2='60';
			
			//推广:生成优惠券/折扣券参数
			$tgycp_types3='1';
			$tgycp_usetypes3='0';
			$tgycp_value3='20';
			$tgycp_limitmoney3='500';
			$tgycp_code_digits3='8';
			$tgycp_number3='5';
			$tgycp_overdue3='60';
			
			//推广:生成优惠券/折扣券参数
			$xhycp_types1='1';
			$xhycp_usetypes1='0';
			$xhycp_value1='5';
			$xhycp_limitmoney1='98';
			$xhycp_code_digits1='8';
			$xhycp_number1='1';
			$xhycp_overdue1='60';
			
			//推广:生成优惠券/折扣券参数
			$xhycp_types2='1';
			$xhycp_usetypes2='0';
			$xhycp_value2='10';
			$xhycp_limitmoney2='300';
			$xhycp_code_digits2='8';
			$xhycp_number2='1';
			$xhycp_overdue2='60';
			
			//推广:生成优惠券/折扣券参数
			$xhycp_types3='1';
			$xhycp_usetypes3='0';
			$xhycp_value3='20';
			$xhycp_limitmoney3='500';
			$xhycp_code_digits3='8';
			$xhycp_number3='1';
			$xhycp_overdue3='60';
			
	
	$settlement_msg='1';
	$settlement_mail='1';
	$settlement_sms='0';
	$settlement_wx='1';
	$CustomerService='01=小言=18352662996=739459614=jbuyexpress====/images/weixin/1.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
02=星星=18352662996=3219636676=jbuyexpress1==/images/weixin/2.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
03=晓晓=18351307178=2900631985=jbox01==/images/weixin/3.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
05=芊芊=18662882962=2010585922=jbuy05==/images/weixin/5.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
06=玥玥=18362164763=2010585922=jbuyexpress2==/images/weixin/6.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
07=小琪=18351303158=3297193294=jbuy07==/images/weixin/7.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
08=小雨=18351303991=2810205033=jbuy03==/images/weixin/8.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
09=小佳=18351303877=3033059735=jbuy09==/images/weixin/9.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
10=小艺=18351303511=3185432772=jbuy10==/images/weixin/10.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
11=小鱼=18252845867=3483736636=jbuy11==/images/weixin/11.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
12=小新=18351302272==jbuy12==/images/weixin/12.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
15=小李=18362809974=18362809974=jbuy15==/images/weixin/15.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
16=乐乐=18252854293=3461331721=jbuy16==/images/weixin/16.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
17=小球=18662891845=2548204983=jbuy17==/images/weixin/17.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
18=小璐=13962831300=3519641633=jbuy18==/images/weixin/18.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
20=小吉=13962872100=1667428308=jbuy20==/images/weixin/20.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
21=萱萱=18351300818=2131023772=jbuy21==/images/weixin/21.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
22=圆圆=18252837006=2570330772=jbuy22==/images/weixin/22.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
24=小莉=18252850987=3433653553=jbuy24==/images/weixin/24.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
26=依依=15262831613=323575173=jbuy26==/images/weixin/26.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
29=冬冬=15262825863=207224320=jbuy29==/images/weixin/29.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847
30=犄角=15206285706=QQ=jbuy30==/images/weixin/30.jpg=http%3a%2f%2fp.qiao.baidu.com%2fcps%2fchat%3fsiteId%3d11072586%26userId%3d24367847';
	$daigouCS='http://p.qiao.baidu.com/cps/chat?siteId=11072586&userId=24367847';
	$floatingCS='http://p.qiao.baidu.com/cps/chat?siteId=11072586&userId=24367847
http://p.qiao.baidu.com/cps/chat?siteId=11072586&userId=24367847
http://p.qiao.baidu.com/cps/chat?siteId=11072586&userId=24367847';
	
	//邮箱接口配置
	$smtp_server='ssl://smtp.exmail.qq.com';
	$smtp_secure='';
	$smtp_port='465';
	$smtp_mail='service@jbuy.com.au';
	$smtp_password='Jbuy88';
	
	//对接清关公司
	if($open_gd_mosuda){$ON_gd_mosuda='1';}
	$ON_gd_mosuda_apply='1';
	$gd_mosuda_plusTaxes='0';
	$gd_mosuda_CountryCode='DE';
	$gd_mosuda_ShopId='27';
	$gd_mosuda_record='';
	$gd_mosuda_username='';
	$gd_mosuda_password='';
	$gd_mosuda_key='';

	//物流对接
	$APIcustomer='';
	$APIkey='';
	
	$ON_cj='1';
	$cj_juso_account='';
	$cj_juso_password='';
	$cj_juso_clntnum='';
	$cj_juso_clnmgmcustcd='';
	$cj_p_prngdivcd='01';
	$cj_p_cgsts='91';

	$cj_frt_dv_cd='03';
	$cj_box_type_cd='01';
	$cj_sendr_nm='';
	$cj_sendr_tel='';
	$cj_sendr_addr='';

	$cj_opendb_account='';
	$cj_opendb_password='';
	
	$ON_dhl='1';
	$dhl_username='';
	$dhl_password='';
	$dhl_PortalId='';
	$dhl_DeliveryName='';
	$dhl_ToAddress='收件地址1
收件地址2
收件地址3
收件地址4';
	$mprehen='';
	
	
	$fanyi_type='auto';
	$youdao_api_id='xingaozy';
	$youdao_api_key='629778263';
	$baidu_api_id='20160707000024765';
	$baidu_api_key='ZnL81C_ZxqdBXIkZUmtF';
	
	//短信
	$off_sms='1';
	$sms_user='jimai';
	$sms_pwd='deancai1';
	$sms_type='2';
	$sms_key='e7e8cfa74db970cf7a0a16bc47671e63';
	$sms_signature='';
	
	//快捷登录会员
	$off_connect_qq='1';
	$off_connect_qq_checked='';
	$connect_qqid='100353381';
	$connect_qqkey='d832f0731faab4785f1ef35302146135';
	
	$off_connect_weixin='1';
	$connect_weixinid='wx51cf9ddf6465854a';
	$connect_weixinkey='d6f32d0a503f2698fd5ec12c560d63e8';
	
	$off_connect_alipay='1';
	$connect_alipayid='2088811107092805';
	$connect_alipaykey='rwumeolpvpnkco4nfqs93st3oavt085l';
	
	//其他接口
	$ON_mpWeixin='1';
	$mpWeixin_checked='0';
	$ON_WX='1';
	$mpWeixin_token='HVCAfNheHxuszyApvvMdgeMnMRDYCECr';
	$mpWeixin_appid='wxa3a1fcebfea87bf6';
	$mpWeixin_appsecret='e1d003985b3549bcb0dd37be52e8d341';
	$ON_api_yundanStatus='1';
	$api_yundanStatus_key='dFadsZpaHMfvWWBTHFwwZAvnVfHUPKWf';
	$bottion='';

	//安全配置
	$security_clear='';

	//后台配置
	$manage_prompt_time='6000';
	
	//其他配置
	$shaidan_Types_0='1';
	$shaidan_Types_1='1';
	$ON_shaidan_language='1';
	
	//状态更新-开始-------------------------------------------------------------
	$off_statusauto='1';
	$yd_statusauto='1';
	$status_api_ok='1';
	//状态更新-结束
	
	//运单状态-开始-------------------------------------------------------------
	//显示名称
	$status_02CN='';
	$status_01CN='待入库';
	$status_0CN='待审核';
	$status_1CN='未通过审核';
	$status_2CN='待打包';
	$status_3CN='待支付';
	$status_4CN='待出库';
	$status_5CN='送往机场';
	$status_6CN='海关清关中';
	$status_7CN='飞行途中';
	$status_8CN='抵达机场';
	$status_9CN='海关清关中';
	$status_10CN='状态10';
	$status_11CN='状态11';
	$status_12CN='状态12';
	$status_13CN='已发货至第三方';
	$status_14CN='待报关税';
	$status_15CN='已付关税';
	$status_16CN='';
	$status_17CN='';
	$status_18CN='';
	$status_19CN='';
	$status_20CN='国际物流专线承运中';
	$status_21CN='';
	$status_22CN='';
	$status_23CN='';
	$status_24CN='';
	$status_25CN='';
	$status_26CN='';
	$status_27CN='';
	$status_28CN='';
	$status_29CN='';
	$status_30CN='订单已完成';
	$joint='status_02'.$LT; $status_02=$$joint;
	$joint='status_01'.$LT; $status_01=$$joint;
	$joint='status_0'.$LT; $status_0=$$joint;
	$joint='status_1'.$LT; $status_1=$$joint;
	$joint='status_2'.$LT; $status_2=$$joint;
	$joint='status_3'.$LT; $status_3=$$joint;
	$joint='status_4'.$LT; $status_4=$$joint;
	$joint='status_5'.$LT; $status_5=$$joint;
	$joint='status_6'.$LT; $status_6=$$joint;
	$joint='status_7'.$LT; $status_7=$$joint;
	$joint='status_8'.$LT; $status_8=$$joint;
	$joint='status_9'.$LT; $status_9=$$joint;
	$joint='status_10'.$LT; $status_10=$$joint;
	$joint='status_11'.$LT; $status_11=$$joint;
	$joint='status_12'.$LT; $status_12=$$joint;
	$joint='status_13'.$LT; $status_13=$$joint;
	$joint='status_14'.$LT; $status_14=$$joint;
	$joint='status_15'.$LT; $status_15=$$joint;
	$joint='status_16'.$LT; $status_16=$$joint;
	$joint='status_17'.$LT; $status_17=$$joint;
	$joint='status_18'.$LT; $status_18=$$joint;
	$joint='status_19'.$LT; $status_19=$$joint;
	$joint='status_20'.$LT; $status_20=$$joint;
	$joint='status_21'.$LT; $status_21=$$joint;
	$joint='status_22'.$LT; $status_22=$$joint;
	$joint='status_23'.$LT; $status_23=$$joint;
	$joint='status_24'.$LT; $status_24=$$joint;
	$joint='status_25'.$LT; $status_25=$$joint;
	$joint='status_26'.$LT; $status_26=$$joint;
	$joint='status_27'.$LT; $status_27=$$joint;
	$joint='status_28'.$LT; $status_28=$$joint;
	$joint='status_29'.$LT; $status_29=$$joint;
	$joint='status_30'.$LT; $status_30=$$joint;
	
	/*
	输出下拉菜单
	yundan_Status($val);
	*/
	
	function yundan_Status($val)
	{
	
					global $status_01; if($val=='-1'){echo "<option value='-1'  selected>{$status_01}</option>"; }else{echo "<option value='-1'  >{$status_01}</option>";}	
				
				
					global $status_0; if($val=='0'){echo "<option value='0'  selected>{$status_0}</option>"; }else{echo "<option value='0'  >{$status_0}</option>";}	
				
				
					global $status_1; if($val=='1'){echo "<option value='1'  selected>{$status_1}</option>"; }else{echo "<option value='1'  >{$status_1}</option>";}	
				
				
					global $status_2; if($val=='2'){echo "<option value='2'  selected>{$status_2}</option>"; }else{echo "<option value='2'  >{$status_2}</option>";}	
				
				
					global $status_3; if($val=='3'){echo "<option value='3'  selected>{$status_3}</option>"; }else{echo "<option value='3'  >{$status_3}</option>";}	
				
				
					global $status_4; if($val=='4'){echo "<option value='4'  selected>{$status_4}</option>"; }else{echo "<option value='4'  >{$status_4}</option>";}	
				
				
					global $status_20; if($val=='20'){echo "<option value='20'  selected>{$status_20}</option>"; }else{echo "<option value='20'  >{$status_20}</option>";}	
				
				
					global $status_30; if($val=='30'){echo "<option value='30'  selected>{$status_30}</option>"; }else{echo "<option value='30'  >{$status_30}</option>";}	
				
				
	}
	$status_sms_lx='0';
	//状态-结束
	
	
	//每种状态是否启用-开始-------------------------------------------------------------
	$status_on_5='';
			$status_on_6='';
			$status_on_7='';
			$status_on_8='';
			$status_on_9='';
			$status_on_10='';
			$status_on_11='';
			$status_on_12='';
			$status_on_13='';
			$status_on_14='';
			$status_on_15='';
			$status_on_16='';
			$status_on_17='';
			$status_on_18='';
			$status_on_19='';
			$status_on_20='1';
			$status_on_21='';
			$status_on_22='';
			$status_on_23='';
			$status_on_24='';
			$status_on_25='';
			$status_on_26='';
			$status_on_27='';
			$status_on_28='';
			$status_on_29='';
			$status_on_30='1';
			
	//每种状态是否启用-结束
	
	//每种状态自动更新时间-开始-------------------------------------------------------------
	$statustime_update5='2';
			$statustime_update6='1.5';
			$statustime_update7='1';
			$statustime_update8='5';
			$statustime_update9='1';
			$statustime_update10='';
			$statustime_update11='4';
			$statustime_update12='1';
			$statustime_update13='-1';
			$statustime_update14='-1';
			$statustime_update15='';
			$statustime_update16='-1';
			$statustime_update17='-1';
			$statustime_update18='-1';
			$statustime_update19='-1';
			$statustime_update20='-1';
			$statustime_update21='-1';
			$statustime_update22='-1';
			$statustime_update23='-1';
			$statustime_update24='-1';
			$statustime_update25='-1';
			$statustime_update26='-1';
			$statustime_update27='-1';
			$statustime_update28='-1';
			$statustime_update29='-1';
			$statustime_update30='10000';
			
	//状态更新是否发站内信-结束
	
	//周未是否也算时间-开始-------------------------------------------------------------
	$whether5='';
			$whether6='';
			$whether7='';
			$whether8='1';
			$whether9='1';
			$whether10='';
			$whether11='';
			$whether12='';
			$whether13='';
			$whether14='';
			$whether15='';
			$whether16='';
			$whether17='';
			$whether18='';
			$whether19='';
			$whether20='';
			$whether21='';
			$whether22='';
			$whether23='';
			$whether24='';
			$whether25='';
			$whether26='';
			$whether27='';
			$whether28='';
			$whether29='';
			$whether30='';
			
	//周未是否也算时间-结束
	
	//状态更新是否发站内信-开始-------------------------------------------------------------
	$status_msg0='0';
			$status_msg1='1';
			$status_msg2='1';
			$status_msg3='1';
			$status_msg4='1';
			$status_msg5='1';
			$status_msg6='1';
			$status_msg7='1';
			$status_msg8='1';
			$status_msg9='1';
			$status_msg10='0';
			$status_msg11='0';
			$status_msg12='0';
			$status_msg13='1';
			$status_msg14='1';
			$status_msg15='1';
			$status_msg16='0';
			$status_msg17='0';
			$status_msg18='0';
			$status_msg19='0';
			$status_msg20='1';
			$status_msg21='0';
			$status_msg22='0';
			$status_msg23='0';
			$status_msg24='0';
			$status_msg25='0';
			$status_msg26='0';
			$status_msg27='0';
			$status_msg28='0';
			$status_msg29='0';
			$status_msg30='1';
			
	//状态更新是否发站内信-结束
	
	//状态更新是否发邮件-开始-------------------------------------------------------------
	$status_mail0='0';
			$status_mail1='0';
			$status_mail2='0';
			$status_mail3='0';
			$status_mail4='0';
			$status_mail5='0';
			$status_mail6='0';
			$status_mail7='0';
			$status_mail8='0';
			$status_mail9='0';
			$status_mail10='0';
			$status_mail11='0';
			$status_mail12='0';
			$status_mail13='0';
			$status_mail14='0';
			$status_mail15='0';
			$status_mail16='0';
			$status_mail17='0';
			$status_mail18='0';
			$status_mail19='0';
			$status_mail20='0';
			$status_mail21='0';
			$status_mail22='0';
			$status_mail23='0';
			$status_mail24='0';
			$status_mail25='0';
			$status_mail26='0';
			$status_mail27='0';
			$status_mail28='0';
			$status_mail29='0';
			$status_mail30='0';
			
	//状态更新是否发邮件-结束
	
	//状态更新是否发短信-开始-------------------------------------------------------------
	$status_sms0='0';
			$status_sms1='0';
			$status_sms2='0';
			$status_sms3='0';
			$status_sms4='0';
			$status_sms5='0';
			$status_sms6='0';
			$status_sms7='0';
			$status_sms8='0';
			$status_sms9='0';
			$status_sms10='0';
			$status_sms11='0';
			$status_sms12='0';
			$status_sms13='0';
			$status_sms14='0';
			$status_sms15='0';
			$status_sms16='0';
			$status_sms17='0';
			$status_sms18='0';
			$status_sms19='0';
			$status_sms20='0';
			$status_sms21='0';
			$status_sms22='0';
			$status_sms23='0';
			$status_sms24='0';
			$status_sms25='0';
			$status_sms26='0';
			$status_sms27='0';
			$status_sms28='0';
			$status_sms29='0';
			$status_sms30='0';
			
	//状态更新是否发短信-结束
	
	//状态更新是否发微信-开始-------------------------------------------------------------
	$status_wx0='0';
			$status_wx1='1';
			$status_wx2='0';
			$status_wx3='1';
			$status_wx4='0';
			$status_wx5='1';
			$status_wx6='0';
			$status_wx7='0';
			$status_wx8='0';
			$status_wx9='0';
			$status_wx10='0';
			$status_wx11='0';
			$status_wx12='0';
			$status_wx13='1';
			$status_wx14='0';
			$status_wx15='0';
			$status_wx16='0';
			$status_wx17='0';
			$status_wx18='0';
			$status_wx19='0';
			$status_wx20='0';
			$status_wx21='0';
			$status_wx22='0';
			$status_wx23='0';
			$status_wx24='0';
			$status_wx25='0';
			$status_wx26='0';
			$status_wx27='0';
			$status_wx28='0';
			$status_wx29='0';
			$status_wx30='1';
			
	//状态更新是否发微信-结束
	
	//购物网站-开始-------------------------------------------------------------
	$wangzhan='淘宝=101
1688=102
京东=103';
	
	/*
	wangzhan($val,$lx=0);$lx=0显示名称;$lx=1显示下拉菜单;
	*/
	
	function wangzhan($val,$lx=0)
	{
		if(!$lx)
		{
			switch($val)
			{
				case '':return '';
				case 'other':return '其他';
	case '101':return '淘宝';
				case '102':return '1688';
				case '103':return '京东';
					
			}
		}
		else
		{
			if ($val==''){echo "<option value=''  selected></option>"; }else{echo "<option value='' ></option>";}	
		
				if ($val=='101'){echo "<option value='101'  selected>淘宝</option>"; }else{echo "<option value='101' >淘宝</option>";}
				
				if ($val=='102'){echo "<option value='102'  selected>1688</option>"; }else{echo "<option value='102' >1688</option>";}
				
				if ($val=='103'){echo "<option value='103'  selected>京东</option>"; }else{echo "<option value='103' >京东</option>";}
				
		if ($val=='other'){echo "<option value='other'  selected>其他</option>"; }else{echo "<option value='other' >其他</option>";}	
		}
	}
	//购物网站-结束
	
	//寄库快递-开始-------------------------------------------------------------
	$baoguo_kuaidi='顺丰
圆通
申通
中通
韵达
天天
FEDEX
UPS
DHL
TNT
EMS
其它';
	
	function baoguo_kuaidi($val)
	{
		echo "<option value=''  selected></option>";
	
					if ($val=='顺丰'){echo "<option value='顺丰'  selected>顺丰</option>"; }else{echo "<option value='顺丰' >顺丰</option>";}	
				
					if ($val=='圆通'){echo "<option value='圆通'  selected>圆通</option>"; }else{echo "<option value='圆通' >圆通</option>";}	
				
					if ($val=='申通'){echo "<option value='申通'  selected>申通</option>"; }else{echo "<option value='申通' >申通</option>";}	
				
					if ($val=='中通'){echo "<option value='中通'  selected>中通</option>"; }else{echo "<option value='中通' >中通</option>";}	
				
					if ($val=='韵达'){echo "<option value='韵达'  selected>韵达</option>"; }else{echo "<option value='韵达' >韵达</option>";}	
				
					if ($val=='天天'){echo "<option value='天天'  selected>天天</option>"; }else{echo "<option value='天天' >天天</option>";}	
				
					if ($val=='FEDEX'){echo "<option value='FEDEX'  selected>FEDEX</option>"; }else{echo "<option value='FEDEX' >FEDEX</option>";}	
				
					if ($val=='UPS'){echo "<option value='UPS'  selected>UPS</option>"; }else{echo "<option value='UPS' >UPS</option>";}	
				
					if ($val=='DHL'){echo "<option value='DHL'  selected>DHL</option>"; }else{echo "<option value='DHL' >DHL</option>";}	
				
					if ($val=='TNT'){echo "<option value='TNT'  selected>TNT</option>"; }else{echo "<option value='TNT' >TNT</option>";}	
				
					if ($val=='EMS'){echo "<option value='EMS'  selected>EMS</option>"; }else{echo "<option value='EMS' >EMS</option>";}	
				
					if ($val=='其它'){echo "<option value='其它'  selected>其它</option>"; }else{echo "<option value='其它' >其它</option>";}	
				
	 }
	 //寄库快递-结束           
	 ?>