<?php
namespace Home\Controller;
use Think\Controller;


class BaseController extends Controller{

	public $user_id;//设置全局变量
	public $not_login_with_weixin_key_url='http://baidu.com';


	/*---------初始化------------*/
	public function _initialize(){
//dump($_SESSION);exit;
		if(is_login()){
			$this->user_id=session('user_id');
		}elseif(empty($_GET['weixin_key'])){
			not_login();
		}else{
		
			login($_GET['weixin_key']);
			//dump($this->login($_GET['weixin_key']));exit;
		}
	}



	public static function checkUser($user_id){
		$a=M('user');
		$user=$a->where(array('user_id'=>$user_id))->find();
		if(!!$user){
			return true;
		}else{
			return false;
		}
	}





}




?>