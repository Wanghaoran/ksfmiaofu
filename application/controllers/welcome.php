<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {


	public function index()
	{
        //接口验证
        echo $_GET["echostr"];
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */