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
if(!$ON_mpWeixin){exit('该功能未开启!');}
/*
	微信消息接口全能版
	换行:\n\n
*/

header('Content-type:text');
define("TOKEN",cadd($mpWeixin_token));

$wechatObj = new wechatCallbackapiTest();
if($mpWeixin_checked){
    $wechatObj->valid();//第一次验证时用
}else{
    menu_list();//自定菜单
    $wechatObj->responseMsg();//回复信息
}




//自定菜单
/*
	说明:https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141013&token=&lang=zh_CN
*/
function menu_list()
{
	global $siteurl,$off_shenfenzheng,$off_upload_cert;
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($off_shenfenzheng&&$off_upload_cert)//支持上传证件
	{
		$cert_menu=',
			{
				"name": "上传证件",
				"type": "view",
				"url": "'.$siteurl.'/m/yundan/upload.php"
			}';
	}
	
	$crs4=ClassData(4);//联系我们
	$crs22=ClassData(23);//新闻
	

	 $data='
	{
		"button": [
		
			{
				"name": "运单查询",
				"type": "view",
				"url": "'.$siteurl.'http://track.jbuy.com.au/"
				"url": "'.$siteurl.'/m/yundan/status.php"    
			}
			'.$cert_menu.',
			{
				"name": "更多",
				"sub_button": [
					{
						"name": "在线报价",
						"type": "view",
						"url": "'.$siteurl.'m/yundan/price.php"
					},
					{
						"name": "联系我们",
						"type": "view",
						"url": "'.$siteurl.'m/'.pathLT($crs4['path']).'"
					},
					{
						"name": "转运公告",
						"type": "view",
						"url": "'.$siteurl.'m/'.pathLT($crs22['path']).'"
					},
					{
						"name": "访问网站",
						"type": "view",
						"url": "'.$siteurl.'m/"
					}
					
				]
			}



		]
	}';

	$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".access_token();
	$result= send_post_data($url,$data);//POST方式提交
}











	
	
	
	
	
	
	
	  

class wechatCallbackapiTest
{
    //接收文本消息
    private function receiveText($object)
    {

	   //获取微信发来的资料
	   /*其他请看微信接口说明文档或,后面的xml里面内容*/
       global $siteurl;
       $keyword=trim($object->Content);//内容,此页trim无效
       $openid=trim($object->FromUserName);//openid
	   $content='';

        
        if ($keyword=='查询运单'){
			//程序自动处理:回复
			$content ='请使用菜单中的[查询运单]功能,谢谢!';
	    }elseif(strtoupper(leng($keyword,2))=='AD'&&fnCharCount($keyword)==6){
			//程序自动处理:绑定微信号openid
			$content =save_wx_openid('add',$keyword,$openid);//返回内容提示
	    }elseif(strtoupper(leng($keyword,2))=='DE'&&fnCharCount($keyword)==6){
			//程序自动处理:解绑微信号openid
			$content =save_wx_openid('del',$keyword,$openid);//返回内容提示
        }
		
		/*
		//自动回复:可回复如下类型 ($keyword用户发送的信息)
        }else if (strstr($keyword, "文本")){
            $content = "这是个文本消息";
        }else if (strstr($keyword, "表情")){
            $content = "微笑：/::)\n乒乓：/:oo\n中国：".$this->bytes_to_emoji(0x1F1E8).$this->bytes_to_emoji(0x1F1F3)."\n仙人掌：".$this->bytes_to_emoji(0x1F335);
        }else if (strstr($keyword, "单图文")){
            $content = array();
            $content[] = array("Title"=>"单图文标题",  "Description"=>"单图文内容", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>$siteurl.'m');
        }else if (strstr($keyword, "图文") || strstr($keyword, "多图文")){
            $content = array();
            $content[] = array("Title"=>"多图文1标题", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>$siteurl.'m');
            $content[] = array("Title"=>"多图文2标题", "Description"=>"", "PicUrl"=>"http://d.hiphotos.bdimg.com/wisegame/pic/item/f3529822720e0cf3ac9f1ada0846f21fbe09aaa3.jpg", "Url" =>$siteurl.'m');
            $content[] = array("Title"=>"多图文3标题", "Description"=>"", "PicUrl"=>"http://g.hiphotos.bdimg.com/wisegame/pic/item/18cb0a46f21fbe090d338acc6a600c338644adfd.jpg", "Url" =>$siteurl.'m');
        }else if (strstr($keyword, "音乐")){
            $content = array();
            $content = array("Title"=>"最炫民族风", "Description"=>"歌手：凤凰传奇", "MusicUrl"=>"http://mascot-music.stor.sinaapp.com/zxmzf.mp3", "HQMusicUrl"=>"http://mascot-music.stor.sinaapp.com/zxmzf.mp3"); 

       */ 


		
		
      	//无自动回复时,联系客服
		if(!$content)
		{
		   return $this->check_server($object);
        }
		

        if(is_array($content))
		{
            if (isset($content[0])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }


    //验证签名
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            echo $echoStr;
            exit;
        }
    }


	//客服代码
    public function check_server($object)
	{
		$url = "https://api.weixin.qq.com/cgi-bin/customservice/getonlinekflist?access_token=".access_token();
		$json=send_get($url);
		if(!$json['errcode']){$data=json_decode($json);}
        $res = $data->kf_online_list;
        //判断是否有客服人员在线
        if($res[0] == null){
            $content = "抱歉，目前没有客服在线，请先留言！";
            $result = $this->transmitText($object,$content);
           return $result;
        }else{
           $status = $this->transmitService($object);
           return $status;
        }
    }
	
	


    //响应消息
    public function responseMsg()
    {
		$postStr=file_get_contents("php://input");
		//$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		
        if (!empty($postStr)){
            $this->logger("R \r\n".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            
            //消息类型分离
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "image":
                    $result = $this->receiveImage($postObj);
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":
				case "shortvideo":
                    $result = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
            $this->logger("T \r\n".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

    //接收事件消息
    private function receiveEvent($object)
    {
        global $siteurl,$sitename;
		$content = "";
        switch ($object->Event)
        {
            case "subscribe":
                $content = "感谢您关注【{$sitename}】，您可以在此查询运单、上传证件、绑定账号等操作！\n\n如有问题可咨询客服！";
                $content .= (!empty($object->EventKey))?("\n来自二维码场景 ".str_replace("qrscene_","",$object->EventKey)):"";
                break;
            case "unsubscribe":
                $content = "取消关注";
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "COMPANY":
                        $content = array();
                        $content[] = array("Title"=>"手机版", "Description"=>"", "PicUrl"=>$siteurl.'images/logo_m.png', "Url" =>$siteurl.'m');
                        break;
                    default:
                        $content = "点击菜单：".$object->EventKey;
                        break;
                }
                break;
            case "VIEW":
                $content = "跳转链接 ".$object->EventKey;
                break;
            case "SCAN":
                $content = "扫描场景 ".$object->EventKey;
                break;
            case "LOCATION":
				//如果接口开通了获取位置信息并且用户开启了权限,每次进入公众号会自动发送位置信息
                //$content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
                break;
            case "scancode_waitmsg":
                if ($object->ScanCodeInfo->ScanType == "qrcode"){
                    $content = "扫码带提示：类型 二维码 结果：".$object->ScanCodeInfo->ScanResult;
                }else if ($object->ScanCodeInfo->ScanType == "barcode"){
                    $codeinfo = explode(",",strval($object->ScanCodeInfo->ScanResult));
                    $codeValue = $codeinfo[1];
                    $content = "扫码带提示：类型 条形码 结果：".$codeValue;
                }else{
                    $content = "扫码带提示：类型 ".$object->ScanCodeInfo->ScanType." 结果：".$object->ScanCodeInfo->ScanResult;
                }
                break;
            case "scancode_push":
                $content = "扫码推事件";
                break;
            case "pic_sysphoto":
                $content = "系统拍照";
                break;
            case "pic_weixin":
                $content = "相册发图：数量 ".$object->SendPicsInfo->Count;
                break;
            case "pic_photo_or_album":
                $content = "拍照或者相册：数量 ".$object->SendPicsInfo->Count;
                break;
            case "location_select":
                $content = "发送位置：标签 ".$object->SendLocationInfo->Label;
                break;
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }

        if(is_array($content)){
            $result = $this->transmitNews($object, $content);
        }else{
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }





    //接收图片消息
    private function receiveImage($object)
    {
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    //接收位置消息
    private function receiveLocation($object)
    {
        $content = "你发送的是位置，经度为：".$object->Location_Y."；纬度为：".$object->Location_X."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收语音消息
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "你刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }
        return $result;
    }

    //接收视频消息
    private function receiveVideo($object)
    {
        $content = "上传视频类型：".$object->MsgType;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收链接消息
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //回复文本消息
    private function transmitText($object, $content)
    {
        if (!isset($content) || empty($content)){
            return "";
        }

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);

        return $result;
    }

    //回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return "";
        }
        $itemTpl = "        <item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
        </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    <ArticleCount>%s</ArticleCount>
    <Articles>
$item_str    </Articles>
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        if(!is_array($musicArray)){
            return "";
        }
        $itemTpl = "<Music>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <MusicUrl><![CDATA[%s]]></MusicUrl>
        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
    </Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[music]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复图片消息
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
        <MediaId><![CDATA[%s]]></MediaId>
    </Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[image]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
        <MediaId><![CDATA[%s]]></MediaId>
    </Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[voice]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复视频消息
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
        <MediaId><![CDATA[%s]]></MediaId>
        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
    </Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[video]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复多客服消息
    private function transmitService($object)
    {
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复第三方接口消息
    private function relayPart3($url, $rawData)
    {
        $headers = array("Content-Type: text/xml; charset=utf-8");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //字节转Emoji表情
    function bytes_to_emoji($cp)
    {
        if ($cp > 0x10000){       # 4 bytes
            return chr(0xF0 | (($cp & 0x1C0000) >> 18)).chr(0x80 | (($cp & 0x3F000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else if ($cp > 0x800){   # 3 bytes
            return chr(0xE0 | (($cp & 0xF000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else if ($cp > 0x80){    # 2 bytes
            return chr(0xC0 | (($cp & 0x7C0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else{                    # 1 byte
            return chr($cp);
        }
    }

    //日志记录
    private function logger($log_content)
    {
        return;//不开启日志,如要开启则注释掉
		
		if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
            
			//上面有return,因此没有开启日志
			$max_size = 1000000;
            $log_filename =$_SERVER['DOCUMENT_ROOT'].'/log/mpWeixin.xml';
			DoMkfile($log_filename);
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
            file_put_contents($log_filename, date('Y-m-d H:i:s')." ".$log_content."\r\n", FILE_APPEND);
			
			
        }
		
    }

}
?>