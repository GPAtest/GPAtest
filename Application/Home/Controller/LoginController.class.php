<?php
namespace Home\Controller;
use Think\Controller;


class LoginController extends Controller{



	public function logout(){
		session(null);
		$this->success('退出成功',U('Home/Index/index'));
	}


	public function index(){
		if(IS_POST){
			//dump($_POST['weixin_key']);
			$a=M('user');
			$data_user['weixin_key']=$_POST['weixin_key'];
			$data_user['account']=$_POST['account'];
			$data_user['password']=$_POST['password'];
			$user=$a->add($data_user);
			//dump($user['user_id']);exit();die();

			if($user){
				session('user_id',$user);
				$data['status']=1;
				$this->ajaxReturn($data,'JSON');
			}else{
				$data['status']=0;
				$this->ajaxReturn($data,'JSON');
			}	
		}
	$this->display();
	}


	/*----------修改学号和密码----------*/
	public function change(){
		if(IS_POST){
			$a=M('user');
			$map['user_id']=$this->user_id;
			$updata['weixin_key']=$_POST['weixin_key'];
			$updata['account']=$_POST['account'];
			$updata['password']=$_POST['password'];
			$user=$a->where($map)->save($updata);
			if($user){
				$data['status']=1;
				$this->ajaxReturn($data,'JSON');
			}else{
				$data['status']=0;
				$this->ajaxReturn($data,'JSON');
			}	
		}
	$this->display();
	}


}









?>