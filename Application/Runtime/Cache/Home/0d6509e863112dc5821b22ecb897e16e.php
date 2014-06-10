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
    <title>我的绩点</title>
    </head>
  <body>



<div class="navbar navbar-fixed-bottom navbar-inverse" role="navigation">
       <div class="container">   
          <ul class="nav nav-pills">
            <li class="active"><a href="/GPAtest/index.php/Home/Index/index">绩点查询</a></li>
            <li><a href="/GPAtest/index.php/Home/Login/logout">退出</a></li>
          </ul>
       </div>
</div>


<?php if(isset($data['Semester'])){ ?>



<?php
 foreach ($data['Semester'] as $key => $value) {?>

<div class="container"> 
  <div class="row">
    <div class="well well-lg">
      <div calss="col-md-offset-2 col-md-8">

  <table class="table">
  <tr>
    <th>课程名称</th><th>成绩</th><th>绩点</th><th>课程属性</th>
  </tr>

  <p><font color="blue"><b>学期:</b></font>&nbsp;<?php echo $value['SemesterName']; ?></p>
  <p><font color="blue"><b>总学分:</b></font>&nbsp;<?php echo $value['SemesterAllCredit']; ?></p>
  <p><font color="blue"><b>平均成绩:</b></font>&nbsp;<?php echo $value['sa_info']; ?></p>
  <p><font color="blue"><b>平均学分:</b></font>&nbsp;<?php echo $value['gpa_info']; ?></p>

  <?php foreach ($value['Course'] as $key => $info){ ?>

  <tr>
    <td><?php echo $info['CourseNameCh']; ?></td>

    <td><?php echo $info['Score']; ?></td>
    
    <td><?php echo $info['Credit']; ?></td>
    
    <td><?php echo $info['Properties']; ?></td> 
  </tr>

  <?php } ?>

  </table>
  </div>
  </div>
  </div>
  </div>
</div>

<?php } ?>  

<?php }if(isset($data['FailScore']['FailingScore'])){ ?>

<div class="container"> 
  <div class="row">
    <div class="well well-lg">
      <div calss="col-md-offset-2 col-md-8">


  <table class="table">
  <p><font color="red">当前不及格：</font></p>
  <tr>
    <th>课程名称</th><th>成绩</th><th>课程属性</th><th>学分</th><th>考试时间</th>
  </tr>


  <?php foreach ($data['FailScore']['FailingScore'] as $key => $info){ ?>

  <tr>
    <td><?php echo $info['CourseNameCh']; ?></td>

    <td><?php echo $info['Score']; ?></td> 
    
    <td><?php echo $info['Properties']; ?></td> 

    <td><?php echo $info['Credit']; ?></td>

    <td><?php echo $info['ExamTime']; ?></td>
  </tr>

  <?php } ?>

  </table>
  </div>
  </div>
  </div>
  </div>
</div>

<?php }if(isset($data['FailScore']['FailedScore'])){ ?>

<div class="container"> 
  <div class="row">
    <div class="well well-lg">
      <div calss="col-md-offset-2 col-md-8">


  <table class="table">
  <p><font color="red">曾不及格：</font></p>
  <tr>
    <th>课程名称</th><th>成绩</th><th>课程属性</th><th>学分</th><th>考试时间</th>
  </tr>

  <?php foreach ($data['FailScore']['FailedScore'] as $key => $info){ ?>

  <tr>
    <td><?php echo $info['CourseNameCh']; ?></td>

    <td><?php echo $info['Score']; ?></td> 
    
    <td><?php echo $info['Properties']; ?></td> 

    <td><?php echo $info['Credit']; ?></td>

    <td><?php echo $info['ExamTime']; ?></td>
  </tr>

  <?php } ?>

  </table>
  </div>
  </div>
  </div>
  </div>
</div>
<?php } ?> 


</body>

</html>