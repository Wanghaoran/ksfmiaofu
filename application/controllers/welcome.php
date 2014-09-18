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
                    ),
                ),
                'LOCATION' => array(
                    'ACTION_NAME' => '',
                ),
            ),
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


    //文字消息
    public function execText($postObj){
        $fromUsername = $postObj -> FromUserName;
        $toUsername = $postObj -> ToUserName;
        $content = '感谢您关注康师傅妙芙，您的消息已收到哦~[调皮]';
        $this -> responseText($toUsername, $fromUsername, $content);
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



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */