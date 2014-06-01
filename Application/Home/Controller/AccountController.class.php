<?php
namespace Home\Controller;
use Think\Controller;


class AccountController extends Controller{


	public function index(){
		if(IS_POST){
			dump($_POST['weixin_key']);
			$a=M('user');
			$data_user['weixin_key']=$_POST['weixin_key'];
			$data_user['account']=$_POST['account'];
			$data_user['password']=$_POST['password'];
			$user=$a->add($data_user);
			dump($user['user_id']);exit();die();

			if($user){
				session('user_id',$user['user_id']);
				$data['status']=0;
				$this->ajaxReturn($data,'JSON');
			}else{
				$data['status']=0;
				$this->ajaxReturn($data,'JSON');
			}
		}elseif(empty($_GET['weixin_key'])){
			echo '未从微信进入！';
		}elseif(is_user($_GET['weixin_key'])){
			//echo '您的账号号已登陆,接下来将跳转至您的成绩单';
			redirect(U('/Home/Index/index'));
		}else{
    //  dump($_SESSION);
		//	dump($_GET);exit;
		
		$this->display();
	
		}
	}

	function test(){
		$this->display();
	}


}





?>