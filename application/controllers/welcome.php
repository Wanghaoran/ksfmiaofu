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
                        'RESERVATION_HOTEL' => 'execClick_RESERVATION_HOTEL',
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
    

    //文字消息
    public function execText($postObj){
        $fromUsername = $postObj -> FromUserName;
        $toUsername = $postObj -> ToUserName;
        $content = 'This is Test Message';
        $this -> responseText($toUsername, $fromUsername, $content);
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