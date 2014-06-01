<?php
namespace Home\Controller;
use Think\Controller;


class BaseController extends Controller{

	public $user_id;//设置全局变量
	public $not_login_with_weixin_key_url='http://baidu.com';


	public function _initialize(){
//dump($_SESSION);exit;
		if(is_login()){

			$this->user_id=session('user_id');
		}elseif(empty($_GET['weixin_key'])){
			$this->not_login();
		}else{
		
			$this->login($_GET['weixin_key']);
			//dump($this->login($_GET['weixin_key']));exit;
		}
	}


    public function login($weixin_key){
//echo 'd';exit;
		$a=is_user($wyeixin_ke);

		if($a){
			$user_id=$a['user_id'];
			session('user_id',$user_id);
			$this->user_id=$a;
		}else{

			redirect(U('/Home/Account/index?weixin_key='.$weixin_key));
		}

	}


	public function not_login($from_url=''){
		redirect($this->not_login_with_weixin_key_url);
	}



}




?>