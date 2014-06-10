<?php
namespace Home\Controller;
use Think\Controller;


class BaseController extends Controller{

	public $user_id;//设置全局变量
	public $not_login_with_weixin_key_url;


	/*---------初始化------------*/
	public function _initialize(){
//dump($_SESSION);exit;
		if(is_login()){
			$this->user_id=session('user_id');
		}elseif(empty($_GET['weixin_key'])){
			not_login();
		}else{		
			$this->login($_GET['weixin_key']);
		}
	}




	public	function login($weixin_key){

		$a=is_user($weixin_key);

		if($a){
			$user_id=$a['user_id'];
			session('user_id',$user_id);
			redirect(U('/Home/Index/index'));
		}else{
			redirect(U('/Home/Login/index?weixin_key='.$weixin_key));
		}

	}


}




?>