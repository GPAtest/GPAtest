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



<?php foreach ($data['Semester'] as $key => $value) {?>
<div class="table-responsive">
  <table class="table">
  <tr>
    <th>CourseName</th><th>Score</th><th>Credit</th><th>Properties</th>
  </tr>

  <p>Semester:<?php echo $value['SemesterName']; ?></p>
  <p>Semester All Credits:<?php echo $value['SemesterAllCredit']; ?></p>
  <p>Average Score:<?php echo $value['sa_info']; ?></p>
  <p>GradePoint:<?php echo $value['gpa_info']; ?></p>

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

<?php } ?>  


<?php }elseif(isset($data['FailScore']['FailingScore'])){ ?>
<p>当前不及格：</p>

<div class="table-responsive">
  <table class="table">
  <tr>
    <th>CourseName</th><th>Score</th><th>Properties</th><th>Credit</th><th>ExamTime</th>
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

<?php }elseif(isset($data['FailScore']['FailedScore'])){ ?>
<p>曾不及格：</p>

<div class="table-responsive">
  <table class="table">
  <tr>
    <th>CourseName</th><th>Score</th><th>Properties</th><th>Credit</th><th>ExamTime</th>
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
<?php } ?> 


</body>

</html>