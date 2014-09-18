<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public $msgTypes = array(
        'text' => array(
            'ACTION_NAME' => 'execText',
        ),
        'image' => array(
            'ACTION_NAME' => '',
        ),
        'voice' => array(
            'ACTION_NAME' => '',
        ),
        'video' => array(
            'ACTION_NAME' => '',
        ),
        'location' => array(
            'ACTION_NAME' => '',
        ),
        'link' => array(
            'ACTION_NAME' => '',
        ),
        'event' => array(
            'childEvent' => array(
                'subscribe' => array(
                    'ACTION_NAME' => 'execSubscribe',
                ),
                'unsubscribe' => array(
                    'ACTION_NAME' => 'execUnsubscribe',
                ),
                'CLICK' => array(
                    'ACTION_NAME' => 'execClick',
                    'EventKey' => array(
                        'FUNNY_FAMILY' => 'execClick_FUNNY_FAMILY',//妙趣家族
                        'WATCH_GIFT' => 'execClick_WATCH_GIFT',//关注有礼
                    ),
                ),
                'LOCATION' => array(
                    'ACTION_NAME' => '',
                ),
            ),
        ),
    );

    public $keywordTpes = array(
        '你好,好,hello,hi' => array(
            'type' => 'text',
            'content' => '你好，我是小妙，欢迎关注康师傅妙芙官方微信！[调皮]',
        ),
        '小妙,妙芙,无聊,聊天' => array(
            'type' => 'text',
            'content' => '这里是小妙~可以把我当作树洞，有什么话都可以私信跟我讲喔！[调皮]',
        ),
        '买,购买' => array(
            'type' => 'text',
            'content' => '感谢您支持康师傅妙芙，您可以马上登陆康师傅妙芙1号店商城http://t.cn/RhiF8gq ，全天不打烊，妙芙随时享。[愉快]',
        ),
        '投诉,问题,质量,过期' => array(
            'type' => 'text',
            'content' => '感谢您购买康师傅产品，我们会收集您的反馈意见，如给您带来不便谢谢给予理解。同时您也可以在周一至周五9:00-17:00拨打400-651-0022热线服务电话咨询，或登入活动网站http://www.ksfmiaofu.cn，查看康师傅更多相关产品，谢谢。[微笑]',
        ),
        '中奖,公布' => array(
            'type' => 'text',
            'content' => '亲爱的，输码赢奖的中奖名单会在隔天的10：00在活动网站公布；关注有礼红包中奖名单会在每周推送公布，还请多多关注哦~',
        ),
        '红包,红包拿来' => array(
            'type' => 'text',
            'content' => '请多多关注康师傅妙芙，中奖名单会在每周公布哦~小妙偷偷告诉你，参与后期精彩游戏与互动还能增加红包几率哦~',
        ),
        '兑奖码,抽奖' => array(
            'type' => 'text',
            'content' => '亲爱的，返回主菜单点击【Pincode兑奖】输入您的兑奖码，即可参与抽奖哦~中奖名单会在隔天10点公布，请多多关注活动网站~',
        ),
        '聊天,在吗,有人吗' => array(
            'type' => 'text',
            'content' => '啦啦啦，跟我聊天就不无聊啦',
        ),
        '吃饭了吗' => array(
            'type' => 'text',
            'content' => '我刚吃完妙芙，你要不要来一个？',
        ),
        '你在干嘛' => array(
            'type' => 'text',
            'content' => '我也在无聊呢~',
        ),
        '你爱我吗' => array(
            'type' => 'text',
            'content' => '爱你更爱妙芙，么么哒！',
        ),
        '心情不好' => array(
            'type' => 'text',
            'content' => '呐，做人呢最重要的是开心，没什么大不了的~来，小妙给你讲个笑话吧：“[笑]和[话]是两个很要好的朋友，有一天「笑」死掉了，「话」跪在他的坟墓旁边，哭着说：「呜....我好想笑哦...」。”',
        ),
        '天气' => array(
            'type' => 'text',
            'content' => '吃个妙芙，心情好了，不管晴天雨天心里都是满满阳光呢~',
        ),
        '你是男是女' => array(
            'type' => 'text',
            'content' => '人家是美女呢，好害羞~',
        ),
    );



    public function index()
	{
        //接口验证
        //echo $_GET["echostr"];

        //验证消息真实性
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = 'ksfmiaofu_wechat';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if($tmpStr != $signature ){
            echo '';
            exit;
        }

        //判断消息类型并执行相应Action
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

        $MsgType = strval($postObj -> MsgType);
        $Event = strval($postObj -> Event);

        if($MsgType == 'event'){
            $action_name = $this -> msgTypes[$MsgType]['childEvent'][$Event]['ACTION_NAME'];
        }else{
            $action_name = $this -> msgTypes[$MsgType]['ACTION_NAME'];
        }

        if(!$action_name){
            echo '';
            exit;
        }else{
            $this -> $action_name($postObj);
        }
    }

    //自定义菜单－妙趣家族
    public function execClick_FUNNY_FAMILY($postObj){
        $fromUsername = $postObj -> FromUserName;
        $toUsername = $postObj -> ToUserName;
        $content = '康师傅妙趣家族即将上线，敬请关注哦~[调皮]';
        $this -> responseText($toUsername, $fromUsername, $content);
    }

    //自定义菜单－妙享乐园－关注有礼
    public function execClick_WATCH_GIFT($postObj){
        $fromUsername = $postObj -> FromUserName;
        $toUsername = $postObj -> ToUserName;
        $Articles = array(
            array(
                'title' => '小妙微信红包周周送不停！分享给小伙伴们惊喜一下吧！',
                'description' => '即日起凡在“添加好友”中搜索“康师傅妙芙”，添加关注，并成功分享到朋友圈，与小妙成为好朋友，即有机会获得小妙送出的获得50元微信红包！还不呼朋唤友加关注？',
                'picurl' => 'http://mmbiz.qpic.cn/mmbiz/awcj9xOKiaDJFDmZIoacqwa8YJvAVs4k7rIRUKzdKxxKVDt8RNocHIBkhCTiapYodeE32hibsiaatGpNOkKrnsHrfQ/0',
                'url' => 'http://mp.weixin.qq.com/s?__biz=MzA5MTg2OTQyMw==&mid=200355835&idx=1&sn=c687ba28f9c84e2197e2807cbb206891#rd',
            ),
        );

        $this -> responseNews($toUsername, $fromUsername, $Articles);
    }


    //文字消息
    public function execText($postObj){

        //关键字检测
        $this -> keywordrespon($postObj);

        $fromUsername = $postObj -> FromUserName;
        $toUsername = $postObj -> ToUserName;
        $content = '感谢您关注康师傅妙芙，您的消息已收到哦~[调皮]';
//        $this -> responseText($toUsername, $fromUsername, $content);
        $this -> responseImage($toUsername, $fromUsername, '200355983');

    }

    //关注
    public function execSubscribe($postObj){
        $fromUsername = $postObj -> FromUserName;
        $toUsername = $postObj -> ToUserName;
        $content = '感谢您的关注。9月15日开始，凡购买妙芙的小伙伴们在菜单栏中【Pincode兑奖】中输入包装盒内的兑奖码可以抽奖，更多有趣的妙芙吃法及有奖互动，敬请期待哦！[调皮]';
        $this -> responseText($toUsername, $fromUsername, $content);
    }

    //取消关注
    public function execUnsubscribe($postObj){
        echo '';
        exit;
    }

    //点击菜单
    public function execClick($postObj){
        $EventKey = strval($postObj -> EventKey);
        $action_name = $this -> msgTypes['event']['childEvent']['CLICK']['EventKey'][$EventKey];
        if(!$action_name){
            $fromUsername = $postObj -> FromUserName;
            $toUsername = $postObj -> ToUserName;
            $content = '感谢您关注康师傅妙芙，您的消息已收到哦~[调皮]';
            $this -> responseText($toUsername, $fromUsername, $content);
            exit;
        }else{
            $this -> $action_name($postObj);
        }
    }

    //回复文本消息
    public function responseText($toUserName, $fromUserName, $content){
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $fromUserName, $toUserName, time(), 'text', $content);
        echo $resultStr;
        exit;
    }

    //回复图片消息
    public function responseImage($toUserName, $fromUserName, $content){
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Image>
                    <MediaId><![CDATA[%s]]></MediaId>
                    </Image>
                    </xml>";
        $resultStr = sprintf($textTpl, $fromUserName, $toUserName, time(), 'image', $content);
        echo $resultStr;
        exit;
    }

    //回复图文消息
    public function responseNews($toUserName, $fromUserName, $Articles){
        $textTpl = "<xml>
                    <ToUserName><![CDATA[" . $fromUserName . "]]></ToUserName>
                    <FromUserName><![CDATA[" . $toUserName . "]]></FromUserName>
                    <CreateTime>" . time() . "</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>" . count($Articles) . "</ArticleCount>
                    <Articles>";

        foreach($Articles as $value){
            $textTpl .= "<item>
                         <Title><![CDATA[" . $value['title'] . "]]></Title>
                         <Description><![CDATA[" . $value['description'] . "]]></Description>
                         <PicUrl><![CDATA[" . $value['picurl'] . "]]></PicUrl>
                         <Url><![CDATA[" . $value['url'] . "]]></Url>
                         </item>";
        }

        $textTpl .= "</Articles>
                     </xml>";

        echo $textTpl;
        exit;
    }

    //关键字回复
    public function keywordrespon($postObj){

        $fromUsername = $postObj -> FromUserName;
        $toUsername = $postObj -> ToUserName;
        //消息内容
        $Content =  strtolower(strval($postObj -> Content));


        //关键字检测算法
        foreach($this -> keywordTpes as $key => $value){

            $key_arr = explode(',', $key);

            foreach($key_arr as $value2){
                if(strpos($Content, $value2) === false){
                    continue;
                }else{
                    //检测回复标记类型并执行回复
                    if($value['type'] == 'text'){
                        $content = $value['content'];
                        $this -> responseText($toUsername, $fromUsername, $content);
                    }
                }
            }

        }
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */