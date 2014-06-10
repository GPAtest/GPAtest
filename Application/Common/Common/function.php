<?php

	//通过weixin_key判断数据库中是否有此用户，如果有，返回user_id
	function is_user($weixin_key,$field='weixin_key'){
		$a=M('user');
		$user=$a->where(array($field=>$weixin_key))->find();
		if(empty($user['user_id'])){
			return false;
		}else{
			return $user;
		}
	}


	function is_login(){
		if(session('user_id')){
			return true;
		}else{
			return false;
		}
	}





	function not_login($from_url=''){
		$not_login_with_weixin_key_url='http://localhost/GPAtest/index.php/Home/Index/index/weixin_key/'.time();
		redirect($not_login_with_weixin_key_url);
	}





?>