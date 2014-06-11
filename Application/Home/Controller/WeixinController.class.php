<?php

namespace Home\Controller;
use Think\Controller;
use Org\Net\Wechat;
class WeixinController extends Controller {
public function index(){
//include "wechat.class.php";
$options = array(
		'token'=>'fyscu' //填写你设定的key
	);
$w=new Wechat($options);
$w->valid();
$type = $w->getRev()->getRevType();
switch($type) {
	case Wechat::MSGTYPE_TEXT:

	switch($w->getRevContent()){
		case '绩点':
		case '绩点计算':
		case 'jd':
		case 'jidian':
		case 'JD';
		$arr=array(
			'0'=>array(
				'Title'=>'点击进入一键报修',
				'Description'=>'一键报修是四川大学飞扬俱乐部提供的针对四川大学学生的免费公益电脑维修服务，报修后将会有技术员主动联系您，请您保持手机畅通。',
				'PicUrl'=>'http://fyyf.scuinfo.com/gpa/Public/wechat/one.png',
				'Url'=>'http://fyyf.scuinfo.com/gpa/index.php/Home/Index/index/weixin_key/'.$w->getRevFrom(),
				),


			);
$w->news($arr)->reply();
			
		break;
       		case '接单':
		case 'JD':
		case 'jd':
		case 'Jd':
		$arr=array(
			'0'=>array(
				'Title'=>'点击进入一键接单',
				'Description'=>'辛苦了技术员',
				'PicUrl'=>'http://fyyf.scuinfo.com/repair/Public/wechat/one.png',
				'Url'=>'http://fyyf.scuinfo.com/repair/index.php/Home/Index/index/weixin_key/'.$w->getRevFrom(),
				),


			);
		$w->news($arr)->reply();

		break;
		case '管理':
		$arr=array(
			'0'=>array(
				'Title'=>'报修管理',
				'Description'=>'',
				'PicUrl'=>'http://fyyf.scuinfo.com/repair/Public/wechat/one.png',
				'Url'=>'http://fyyf.scuinfo.com/repair/index.php/Home/Index/index/weixin_key/'.$w->getRevFrom(),
				),


			);
		$w->news($arr)->reply();

		break;
		default:
$w->text("微信菌已收到你的留言啦~
在每个上完课的晚上，微信菌都会坐在电脑前，倾听你的心声，解答你的疑惑哦~
友情提醒，如果你的电脑出现了故障。请登录飞扬官方网站http://fyscu.com/，进入网上报修系统，就会有技术员到寝室来为你解决电脑问题哦。")->reply();
		break;
	}
			
			break;
	case Wechat::MSGTYPE_EVENT:
	$event=$w->getRevEvent();
            if($event['event']=='subscribe'){
            	$w->text("终于等到你。
坐。
飞扬俱乐部所有工作人员将竭诚为您服务。有什么技术上的问题可以直接给微信君留言~")->reply();
            }
			break;
	case Wechat::MSGTYPE_IMAGE:
			break;
	default:
			$weObj->text("help info")->reply();
}
}
}