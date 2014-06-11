<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
     <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/css/bootstrap.min.css">
    <link rel="shortcut icon" href="/ico/favicon.png">
   <!-- Bootstrap core CSS -->
    <link href="/dist/css/bootstrap.css" rel="stylesheet">
    <title>成绩单</title>
    </head>
  <body>



<div class="navbar navbar-fixed-bottom navbar-inverse" role="navigation">
       <div class="container">   
          <ul class="nav nav-pills">
            <li class="active"><a href="/GPAtest/index.php/Home/Index/index">成绩单</a></li>
            <li><a href="/GPAtest/index.php/Home/Login/logout">退出</a></li>
          </ul>
       </div>
</div>


<?php if(isset($data['Semester'])){ ?>



<?php
 foreach ($data['Semester'] as $key => $value) {?>

  <div class="container"> 
    <div class="well well-lg">
     
      <table class="table table-bordered">
      <tr>
        <td  bgcolor="#66CCCC"><font color="white">学期:</font></td>
        <td bgcolor="#CCFF66"><font color="white"><b><?php echo $value['SemesterName']; ?></b></font></td>
        <td bgcolor="#FF99CC"><font color="white">总学分:</font></td>
        <td bgcolor="#FF9999"><font color="white"><b><?php echo $value['SemesterAllCredit']; ?></b></font></td>
      </tr>
      <tr>
        <td bgcolor="#FFCC99"><font color="white">平均绩点:</font></td>
        <td bgcolor="#0099CC"><font color="white"><b><?php echo $value['gpa_info']; ?></b></font></td>
        <td bgcolor="#FF9900"><font color="white">平均成绩:</font></td>
        <td bgcolor="#FFCC00"><font color="white"><b><?php echo $value['sa_info']; ?></b></font></td>
      </tr>

  
      <tr bgcolor="#CCFFFF">
        <th>课程名称</th>
        <th>成绩</th>
        <th>绩点</th>
        <th>课程属性</th>
      </tr>

      

      <?php foreach ($value['Course'] as $key => $info){ ?>

      <tr>
        <td bgcolor="#CCFFCC"><?php echo $info['CourseNameCh']; ?></td>

        <td bgcolor="#FFFFFF"><?php echo $info['Score']; ?></td>
        
        <td bgcolor="#FFFFCC"><?php echo $info['Credit']; ?></td>
        
        <td bgcolor="#CCFFFF"><?php echo $info['Properties']; ?></td> 
      </tr>

      <?php } ?>

      </table>
      <br/>    </div>
  </div>

<?php } ?>  

<?php }if(isset($data['FailScore']['FailingScore'])){ ?>


  <div class="container"> 
    <div class="well well-lg">

  <table class="table table-bordered">
  <tr>
  <th scope="col" colspan="5" bgcolor="#FF6666"><font color="white">当前不及格:</font></th>
  </tr>
  <tr class="warning">
    <th scope="col">课程名称</th>
    <th scope="col">成绩</th>
    <th scope="col">课程属性</th>
    <th scope="col">学分</th>
    <th scope="col">考试时间</th>
  </tr>

  <?php foreach ($data['FailScore']['FailingScore'] as $key => $info){ ?>

  <tr class="warning">
    <td><?php echo $info['CourseNameCh']; ?></td>

    <td><?php echo $info['Score']; ?></td> 
    
    <td><?php echo $info['Properties']; ?></td> 

    <td><?php echo $info['Credit']; ?></td>

    <td><?php echo $info['ExamTime']; ?></td>
  </tr>

  <?php } ?>

  </table>
  <br/>    </div>
  </div>

<?php }if(isset($data['FailScore']['FailedScore'])){ ?>



  <div class="container"> 
    <div class="well well-lg">

  <table class="table">
  <tr>
  <th scope="col" colspan="5"  bgcolor="#FF6666"><font color="white">曾不及格:</font></th>
  </tr>
  <tr class="danger">
    <th>课程名称</th>
    <th>成绩</th>
    <th>课程属性</th>
    <th>学分</th>
    <th>考试时间</th>
  </tr>

  <?php foreach ($data['FailScore']['FailedScore'] as $key => $info){ ?>

  <tr  class="danger">
    <td><?php echo $info['CourseNameCh']; ?></td>

    <td><?php echo $info['Score']; ?></td> 
    
    <td><?php echo $info['Properties']; ?></td> 

    <td><?php echo $info['Credit']; ?></td>

    <td><?php echo $info['ExamTime']; ?></td>
  </tr>

  <?php } ?>

  </table>

<?php } ?> 
    </div>
  </div>

</body>

</html>