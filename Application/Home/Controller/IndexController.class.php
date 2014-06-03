<?php
namespace Home\Controller;
use Think\Controller;


class IndexController extends BaseController {


	public function index(){

    if(!BaseController::checkUser($this->user_id)){
      redirect(U('Home/Login/index'));
    }

		//$info=$this->get_info($account,$password);
    $a=M('user');
    $map=$this->user_id;
    $user=$a->where($map)->find();
    $account=$user['account'];
    $password=$user['password'];
    $data=$this->get_info($account,$password);
    //print_r($data);exit();
		$this->assign('data',$data);
		//print_r($info);
		$this->display();
	}





  //计算，最终返回一个包含 各学期 GPA(平均绩点)、加权平均分SA、以及各科成绩信息
   function get_info($acount, $password) {  

    /*-----------------------常量,公用-----------------*/ 
    $cookie_file = tempnam('./temp', 'cookie');  //temp文件夹，文件以cookie开头

      
    /*-----------------------模拟登陆---------------------*/    
    $login_url = "http://202.115.47.141/loginAction.do";
    $login_curl_post = array (
        'zjh' => $acount,
        'mm'  => $password
      );
    $login_data_return = $this->curl_gpa($login_url, $cookie_file, $login_curl_post, "setcookie");

      
    /*---------------判断用户名密码是否正确--------------------*/ 
    $error_msg_1 = "您的密码不正确，请您重新输入！";
    $error_msg_2 = "你输入的证件号不存在，请您重新输入！";
    if(preg_match_all("/".$error_msg_1."/", iconv('gbk','UTF-8',$login_data_return), $temp_match))  $this->error_to_login($error_msg_1);   //密码不正确
    if(preg_match_all("/".$error_msg_2."/", iconv('gbk','UTF-8',$login_data_return), $temp_match))  $this->error_to_login($error_msg_2);   //证件号不存在

      
    /*------------------------------------------正则各种及格成绩----------------------------------------------*/  
    $score_url='http://202.115.47.141/gradeLnAllAction.do';
    $score_curl_post = array (
        'type' => "ln",
        'oper' => "qbinfo"//"sxinfo"
      );
    $score_data_return = $this->curl_gpa($score_url, $cookie_file, $score_curl_post, "readcookie");
    //print_r($score_data_return);exit();

      
    /*-----------------------获取成绩信息---------------------*/
    $data = preg_replace('/\s/','', $score_data_return); //替换空格、回车、换行
      //print_r($data);exit();
    $pattern = '/(align=\"center\">)(.*)(<\/)/U';
    preg_match_all($pattern, $data, $match_score);  //$match_score 为所有学期成绩
      //print_r($match_score[2]);exit();
        
      /*----------获取学期信息----------*/
    $pattern_1 = '/(align=\"middle\">&nbsp;<b>)(.*)(<\/b>&nbsp;)/U';
      //$pattern_2 = '/(已修读课程总学分：&nbsp;&nbsp;)(.*)(&nbsp;&nbsp;)/U';  //中文不容易正则
    $pattern_2 = '/(<tdheight=\"21\">)(.*)(&nbsp;1\.0&nbsp;)(.*)(&nbsp;&nbsp;)(.*)(&nbsp;&nbsp;)(.*)(&nbsp;&nbsp;)(.*)(&nbsp;&nbsp;)(.*)(&nbsp;&nbsp;)(.*)(<\/td>)/U';
    preg_match_all($pattern_1, $data, $match_semester_name);
    preg_match_all($pattern_2, $data, $match_semester_num);
      // print_r($data);exit();
      //print_r($match_semester_name[2]);       //各学期名字
      //print_r($match_semester_num[6]);exit(); //各学期学分总数
      //print_r($match_semester_num[14]);exit();//及格的门数
        
      /*----------比较信息以确保正则信息准确无误----------*/
    $max_course = 0; //总共科目数
    for ($count = 0; $count < count($match_semester_num[14]); $count++)
      $max_course += $match_semester_num[14][$count];
      
    if (count($match_score[2]) != $max_course*7)  //比较 抓取的及格科目数 和 所有成绩数组总数 的对应关系是否正确
      error_to_login("Something wrong....");

    if (count($match_semester_num[14]) != count($match_semester_name[2]))  //比较 学期总数 是否相等
      error_to_login("Something wrong.....");
        
      /*---------------格式化信息进入数组$score_info---------------*/
        /**
         * 将所有成绩信息，按科目分类整理进$score数组(下面为对应中英文)
         *    课程号       => CourseNum
         *    课序号       => LessonNum
         *    课程名       => CourseNameCh
         *    英文课程名   => CourseNameEn
         *    学分         => Credit
         *    课程属性     => Properties
         *    成绩         => Score
         *    绩点         => GradePoint
         *    学期名字     => SemesterName
         *    各学期总学分 => SemesterAllCredit
         */
    $score_info = array();   //创建数组
        /**
         *  返回数组的格式举例
         *  $score_info[0]['SemeterName'];              //表示 学期 0 的 学期名字
         *  $score_info[0]['Course'][0]['CourseNum'];    //表示 学期 0 的 课程 0 的 课程名称
         *  $score_info[0]['gpa_info'];                 //表示 学期 0 的 平均绩点数
         *  $score_info[0]['sa_info'];                  //表示 学期 0 的 加权平均分
         */  
    
    for ($sem = 0; $sem < count($match_semester_name[2]); $sem++) {
        $score_info[$sem]['SemesterName'] = $match_semester_name[2][$sem];       //该学期名称
        $score_info[$sem]['SemesterAllCredit'] = $match_semester_num[6][$sem];   //该学期总学分
        $temp = $this->get_course_index_before($match_semester_num[14], $sem);                  //该学期之前已有所有课程的总数（对应 $match_score[2] 的下标）
        $temp_sem_gpa = $temp_sem_sa = $temp_sem_credit = 0;
        for ($cou = 0; $cou < $match_semester_num[14][$sem]; $cou++) {
            $score_info[$sem]['Course'][$cou]['CourseNum']    = $match_score[2][$temp+$cou*7];
            $score_info[$sem]['Course'][$cou]['LessonNum']    = $match_score[2][$temp+$cou*7+1];
            $score_info[$sem]['Course'][$cou]['CourseNameCh'] = $match_score[2][$temp+$cou*7+2];
            $score_info[$sem]['Course'][$cou]['CourseNameEn'] = $match_score[2][$temp+$cou*7+3];
            $score_info[$sem]['Course'][$cou]['Credit']       = $match_score[2][$temp+$cou*7+4];
            $score_info[$sem]['Course'][$cou]['Properties']   = $match_score[2][$temp+$cou*7+5];
            $score_info[$sem]['Course'][$cou]['Score']        = str_replace(array("<palign=\"center\">","&nbsp;"), "", $match_score[2][$temp+$cou*7+6]);   //去掉标签，截取分数
            $score_info[$sem]['Course'][$cou]['GradePoint']   = $this->score_to_gp($score_info[$sem]['Course'][$cou]['Score']);
            
            $temp_sem_gpa += $score_info[$sem]['Course'][$cou]['Credit']*$score_info[$sem]['Course'][$cou]['GradePoint'];
            $temp_sem_sa  += $score_info[$sem]['Course'][$cou]['Credit']*$this->score_to_sa($score_info[$sem]['Course'][$cou]['Score']);
        }
        
        if ($score_info[$sem]['SemesterAllCredit'] == 0) {
            $score_info[$sem]['gpa_info'] = 0;
            $score_info[$sem]['sa_info']  = 0;
        } else {
            $score_info[$sem]['gpa_info'] = sprintf('%.3f', $temp_sem_gpa/$score_info[$sem]['SemesterAllCredit']);
            $score_info[$sem]['sa_info']  = sprintf('%.3f', $temp_sem_sa/$score_info[$sem]['SemesterAllCredit']);
        }
    }

      //print_r($score_info);exit();
      
      
    /*------------------------------------------正则各种不及格成绩----------------------------------------------*/
    $fail_score_url='http://202.115.47.141/gradeLnAllAction.do';
    $fail_score_curl_post = array (
        'type' => "ln",
        'oper' => "bjg"
      );
    $fail_score_data_return = $this->curl_gpa($fail_score_url, $cookie_file, $fail_score_curl_post, "readcookie");
      //print_r($fail_score_data_return);exit();
      
    /*-----------------------获取成绩信息---------------------*/
    $fail_data = preg_replace('/\s/','', $fail_score_data_return); //替换空格、回车、换行
      //print_r($fail_data);exit();
    $pattern = '/(<tablewidth=\"100)(.*)(<\/table>)/U';
    //$pattern_1 = '/(<tdalign=\"center\">)(.*)(<\/td>)/U';
    preg_match_all($pattern, $fail_data, $match_temp);
    //print_r($match_temp[0][7]);exit(); //尚不及格
    //print_r($match_temp[0][12]);exit();  //曾不及格

    $pattern = '/(<tdalign=\"center\">)(.*)(<\/td>)/U';
    preg_match_all($pattern, $match_temp[0][7], $match_failing_score);
    preg_match_all($pattern, $match_temp[0][12], $match_failed_score);
    //print_r($match_failing_score[2]);exit();   //尚不及格
    //print_r($match_failed_score[2]);exit();    //曾不及格
      
    
    /*---------------格式化信息进入数组$fail_score_info---------------*/
        /**
         * 将所有成绩信息，按科目分类整理进$fail_score_info数组(下面为对应中英文)
         *    尚不及格     => FailingScore
         *    曾不及格     => FailedScore
         *    不及格成绩   => FailScore
         *    未通过原因   => FailReason
         *    考试时间     => ExamTime
         */

    $fail_score_info = array();
    //尚不及格
    $count = 0;
    for ($init = 0; $init < count($match_failing_score[2]); $init = $init+9) {
        $fail_score_info['FailingScore'][$count]['CourseNum']    = $match_failing_score[2][$init];
        $fail_score_info['FailingScore'][$count]['LessonNum']    = $match_failing_score[2][$init+1];
        $fail_score_info['FailingScore'][$count]['CourseNameCh'] = $match_failing_score[2][$init+2];
        $fail_score_info['FailingScore'][$count]['CourseNameEn'] = $match_failing_score[2][$init+3];
        $fail_score_info['FailingScore'][$count]['Credit']       = $match_failing_score[2][$init+4];   
        $fail_score_info['FailingScore'][$count]['Properties']   = $match_failing_score[2][$init+5];
        $fail_score_info['FailingScore'][$count]['Score']        = str_replace(array("<palign=\"left\">","&nbsp;</P>"), "", $match_failing_score[2][$init+6]);
        $fail_score_info['FailingScore'][$count]['ExamTime']     = $match_failing_score[2][$init+7];
        $fail_score_info['FailingScore'][$count]['FailReason']   = str_replace("&nbsp;", "", $match_failing_score[2][$init+8]);
        $count++;
    }
    //曾不及格
    $count = 0;
    for ($init = 0; $init < count($match_failed_score[2]); $init = $init+9) {
        $fail_score_info['FailedScore'][$count]['CourseNum']    = $match_failed_score[2][$init];
        $fail_score_info['FailedScore'][$count]['LessonNum']    = $match_failed_score[2][$init+1];
        $fail_score_info['FailedScore'][$count]['CourseNameCh'] = $match_failed_score[2][$init+2];
        $fail_score_info['FailedScore'][$count]['CourseNameEn'] = $match_failed_score[2][$init+3];
        $fail_score_info['FailedScore'][$count]['Credit']       = $match_failed_score[2][$init+4];   
        $fail_score_info['FailedScore'][$count]['Properties']   = $match_failed_score[2][$init+5];
        $fail_score_info['FailedScore'][$count]['Score']        = str_replace(array("<palign=\"left\">","&nbsp;</P>"), "", $match_failed_score[2][$init+6]);
        $fail_score_info['FailedScore'][$count]['ExamTime']     = $match_failed_score[2][$init+7];
        $fail_score_info['FailedScore'][$count]['FailReason']   = str_replace("&nbsp;", "", $match_failed_score[2][$init+8]);
        $count++;
    }
      

    /*------------------------------------------返回最终的数组----------------------------------------------*/
      return array( 'Semester'  => $score_info,
                    'FailScore' => $fail_score_info);
  }

    //确保分数为 数字，用以计算加权平均分
    function score_to_sa($score) {
        $score = iconv('gbk','UTF-8', $score);  //需转码  教务处网页为GBK编码
        if ($score == "优秀")  return 90;
        else if ($score == "良好") return 80;
        else if ($score == "中等") return 70;
        else if ($score == "通过") return 60;
        else if ($score == "未通过") return 0;
        else
            return $score;  
    }
    
     //将分数转换成对应的绩点
     //必选  任选  选修
     //优秀  良好  未通过  中等
     function score_to_gp($score) {
        $score = iconv('gbk','UTF-8', $score);  //需转码  教务处网页为GBK编码
        if ($score == "优秀")  return 3.8;
        else if ($score == "良好") return 3.2;
        else if ($score == "中等") return 2.2;
        else if ($score == "通过") return 1.0;
        else if ($score == "未通过") return 0;
        else if ($score >= 0  && $score < 60) return 0;
        else if ($score >= 60 && $score < 65) return 1.0;
        else if ($score >= 65 && $score < 70) return 1.7;
        else if ($score >= 70 && $score < 75) return 2.2;
        else if ($score >= 75 && $score < 80) return 2.7;
        else if ($score >= 80 && $score < 85) return 3.2;
        else if ($score >= 85 && $score < 90) return 3.6;
        else if ($score >= 90 && $score < 95) return 3.8;
        else if ($score >= 95 && $score <= 100) return 4.0;
        else if ($score < 0 || $score > 100)  error_to_login("Something Wrong...");     
    }  

    //获得 $sem 学期 之前的已有所有课程的总数（对应 $match_score[2] 的下标）
    function get_course_index_before($arr, $sem) {
        if ($sem == 0)
            return 0;
        $sum = 0;
        for ($init = 0; $init < $sem; $init++)
            $sum += $arr[$init];
        return $sum*7;
    }

    //错误信息触发，并跳转到登录界面
    function error_to_login($str) {
        header('content-type:text/html;charset=utf-8;');
        echo "<script language=\"JavaScript\">";
        echo "alert(\"$str\");";
        echo "location.href='index.html';";
        echo "</script>";
    }

    //模拟登陆，记录或读取cookies，并返回模拟的数据
    function curl_gpa($url, $cookie_file, $curl_post, $type) {
        $curl = curl_init();                                 //初始化curl对象
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);               //0为头文件不可见
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);       //0(或false)为显示模拟返回的内容；1为不返回数据、隐藏
        curl_setopt($curl, CURLOPT_POST, 1);                 //设置post
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post);  //提供需要post的数据
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");  //必写

        if ($type == "setcookie")
          curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
        else if($type == "readcookie")
          curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
        else
          error_to_login("Something wrong...");

        $data_return = curl_exec($curl);
        curl_close($curl);
        return $data_return;
    }

}
?>

