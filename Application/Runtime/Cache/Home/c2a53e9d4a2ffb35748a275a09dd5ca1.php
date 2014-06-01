<?php if (!defined('THINK_PATH')) exit();?>


<!DOCTYPE html>
<html lang="zh-CN">
  <head>
     <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/css/bootstrap.min.css">
    <link rel="shortcut icon" href="/t/Public/ico/favicon.png">
   <!-- Bootstrap core CSS -->
    <link href="/t/Public/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/t/Public/css/sign.css" rel="stylesheet">

    <title>绩点查询</title>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.min.js"></script>
        <script src="http://cdn.bootcss.com/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>



<!--建表输入信息-->

<div class="container">

  <form name="login" class="form-signin" role="form" action="/t/index.php/Home/Index/index" method="post" onsubmit="return checkInfo()">
  <p class="text-warning">我们承诺：绝不泄露关于您的任何信息！</p><br />
  <h4 class="form-signin-heading">请输入学号和密码:</h4>
  <input type="hidden" id="weixin_key" value="@#$_GET['weixin_key']" />
  <input type="text" name="account" id="account" class="form-control" placeholder="学号:" required autofocus><br />
  <input type="password" name="password" id="password" class="form-control" placeholder="密码：" required><br />
  <button type="button" class="btn btn-lg btn-primary btn-block"  id="button" value="计算绩点">计算绩点</button>
  </form>

</div> <!-- /container -->




<script type="text/javascript">

$(document).ready(function(){
  $("#button").text('计算中...');
  var account=$("#account").val();
  var password=$("#password").val();
  var weixin_key=$("#weixin_key").val();
  $.post("/t/index.php/Home/Account/login",{account:account,password:password,weixin_key:weixin_key}
    function(data){
      if(data.status==0){
        alert('系统错误，请重试');
        $("#button").text('计算绩点');
      }else{
        $("#button").text('成功，跳转中...');
        window.location.href="/t/index.php/Home/Index/index";
      }
    });
});
  
</script>


  </body>
</html>