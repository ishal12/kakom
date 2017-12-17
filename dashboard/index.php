<?php
session_start();
require '../db.php';

$sqlU = "SELECT * FROM users WHERE username = '".$_COOKIE['id']."'" ;
$resultU = mysqli_query($link, $sqlU);
$rowU = mysqli_fetch_array($resultU);
if(!$resultU) {
    echo "SQL ERROR: ".$sqlU;
}

if(!isset($_COOKIE['login'])) {
    header('location: ../index.php');
}
if($rowU['name'] == null){
    header('location: ../proses.php?cmd=logout');
}

$sqlUser = "SELECT * FROM users WHERE username != '".$_COOKIE['id']."'";
$resultUser = mysqli_query($link, $sqlUser);
if(!$resultUser) {
    echo "SQL ERROR: ".$sqlUser;
}

$sqlChat = "SELECT c.*, a1.name as name1, a2.name as name2
  FROM chat_rooms as c INNER JOIN users as a1 ON a1.username = c.user1
  INNER JOIN users as a2 ON a2.username = c.user2
  WHERE c.user1 = '".$_COOKIE['id']."' OR c.user2 = '".$_COOKIE['id']."'";
$resultChat = mysqli_query($link, $sqlChat);
if(!$resultChat) {
    echo "SQL ERROR: ".$sqlChat;
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ChatKK | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../assets/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="../../index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>C</b>KK</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Chat</b>KK</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="../assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../assets/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $rowU['name']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $rowU['name']; ?>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="../proses.php?cmd=logout" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $rowU['name']; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active">
          <a href="index.php">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Friend Table</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <thead>
                  <th>Username</th>
                  <th>Name</th>
                  <th>Status</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <?php
                    while ($rowUser = mysqli_fetch_object($resultUser)) {
                      echo '<tr>';
                      echo '<td>'.$rowUser->username.'</td>';
                      echo '<td>'.$rowUser->name.'</td>';
                      echo '<td>'.$rowUser->status.'</td>';
                      echo '<td>'.'<button class="btn btn-success btn-chat" data-user1="'.$rowUser->username.'" data-user2="'.$rowU['username'].'">Add Chat</button>'.'</td>';
                      echo '</tr>';
                    }
                  ?>
                </tbody>  
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Chat Table</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <thead>
                  <th>Username</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Last Chat</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <?php
                    $x = 0;
                    while ($rowChat = mysqli_fetch_object($resultChat)) {

                      echo '<tr>';
                      $user = 'x';
                      if($rowChat->user1 == $_COOKIE['id']){
                        echo '<td>'.$rowChat->user2.'</td>';
                        $user = $rowChat->user2;
                      }else{
                        echo '<td>'.$rowChat->user1.'</td>';
                        $user = $rowChat->user1;
                      }
                      $sqlMsg = "SELECT * FROM messages as m JOIN users as u ON u.username = m.users_username WHERE chat_rooms_id = ".$rowChat->id." AND users_username = '".$user."' ORDER BY created_at DESC LIMIT 1";
                      $resultMsg = mysqli_query($link, $sqlMsg);
                      $rowMsg = mysqli_fetch_array($resultMsg);
                      if(!$resultMsg) {
                          echo "SQL ERROR: ".$sqlMsg;
                      }
                      echo '<td>'.$rowMsg["created_at"].'</td>';
                      echo '<td>'.$rowMsg["status"].'</td>';
                      echo '<td>'.$rowMsg["message"].'</td>';
                      echo '<td>'.'<button data-toggle="modal" data-id="'.$rowChat->id.'" data-target="#modal-chat" class="btn btn-success btn-room">Go To Chat</button>'.'</td>';
                      echo '</tr>';
                    }
                  ?>
                </tbody>  
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>

      <div class="calert col-xs-4" style="position: fixed; bottom: 0px; right: 0px; margin: 10px;"></div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <div class="modal fade" id="modal-chat" style="display: none;">
    <!-- DIRECT CHAT PRIMARY -->
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="box box-primary direct-chat direct-chat-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Direct Chat</h3>

          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <!-- Conversations are loaded here -->
            <div class="direct-chat-messages" style="height: 500px;">
              <!-- Message. Default to the left -->
              <div class="direct-chat-msg">
                <div class="direct-chat-info clearfix">
                  <span class="direct-chat-name pull-left">Alexander Pierce</span>
                  <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
                </div>
                <!-- /.direct-chat-info -->
                <img class="direct-chat-img" src="../assets/dist/img/user1-128x128.jpg" alt="Message User Image"><!-- /.direct-chat-img -->
                <div class="direct-chat-text">
                  Is this template really for free? That's unbelievable!
                </div>
                <!-- /.direct-chat-text -->
              </div>
              <!-- /.direct-chat-msg -->

              <!-- Message to the right -->
              <div class="direct-chat-msg right">
                <div class="direct-chat-info clearfix">
                  <span class="direct-chat-name pull-right">Sarah Bullock</span>
                  <span class="direct-chat-timestamp pull-left">23 Jan 2:05 pm</span>
                </div>
                <!-- /.direct-chat-info -->
                <img class="direct-chat-img" src="../assets/dist/img/user3-128x128.jpg" alt="Message User Image"><!-- /.direct-chat-img -->
                <div class="direct-chat-text">
                  You better believe it!
                </div>
                <!-- /.direct-chat-text -->
              </div>
              <!-- /.direct-chat-msg -->
            </div>
            <!--/.direct-chat-messages-->
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <form method="post">
              <div class="input-group">
                <input id="msg" type="text" name="message" placeholder="Type Message ..." class="form-control">
                    <span class="input-group-btn">
                      <button type="submit" class="btn btn-primary btn-flat btn-msg">Send</button>
                    </span>
              </div>
            </form>
          </div>
          <!-- /.box-footer-->
        </div>
        <!--/.direct-chat -->
      </div>
    </div>
    
  </div>
      

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer>

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../assets/dist/js/demo.js"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()

  })

  var messages = $('.direct-chat-messages');

  function scrollToBottom() {
    messages.animate({ scrollTop: messages.prop('scrollHeight')}, 0);
  }

  function bindRoom(){
    $('.btn-room').unbind();

    $('.btn-room').click(function(){
      var idRoom = $(this).data('id');
      var idUser = '<?php echo $_COOKIE["id"] ?>'; 

      $.ajax({
        type: 'post',
        url: '../proses.php?cmd=chat',
        dataType: 'json',
        data: {
          'room': idRoom,
        },
        success: function(data){
          // nama, tangga, jam
          var reloadChat = chat(data);
          setInterval(reloadChat, 1000 );
          // chat(data);
          scrollToBottom();
          
        },
        error: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            alert(msg);
        },
      })

      bindMsg(idRoom, idUser);
    })
  }

  function bindMsg(room, idUser){
    $('.btn-msg').unbind();
    $('.btn-msg').click(function(e){
      var msg = $('#msg').val();
      console.log(room)
      if(msg != ''){
        e.preventDefault();
        $.ajax({
          type: 'post',
          url: '../proses.php?cmd=add_msg',
          dataType: 'json',
          data: {
            'msg': msg,
            'room': room,
            'id': idUser,
          },
          success: function (data) {
            chat(data);
            $('#msg').val('');
            scrollToBottom();
          },
          error: function (jqXHR, exception) {
              var msg = '';
              if (jqXHR.status === 0) {
                  msg = 'Not connect.\n Verify Network.';
              } else if (jqXHR.status == 404) {
                  msg = 'Requested page not found. [404]';
              } else if (jqXHR.status == 500) {
                  msg = 'Internal Server Error [500].';
              } else if (exception === 'parsererror') {
                  msg = 'Requested JSON parse failed.';
              } else if (exception === 'timeout') {
                  msg = 'Time out error.';
              } else if (exception === 'abort') {
                  msg = 'Ajax request aborted.';
              } else {
                  msg = 'Uncaught Error.\n' + jqXHR.responseText;
              }
              alert(msg);
          },
        });
      }else{
        e.preventDefault();
        scrollToBottom();
      }
    })
  }

  function chat(data){
    // nama, tangga, jam
    $('.direct-chat-msg').remove();
    for(var i = 0; i<data.length; i++){
      if(data[i].user != "<?php echo $_COOKIE['id']; ?>"){
        var sender = '<div class="direct-chat-msg">'+
                        '<div class="direct-chat-info clearfix">'+
                          '<span class="direct-chat-name pull-left">'+data[i].user+'</span>'+
                          '<span class="direct-chat-timestamp pull-right">'+data[i].tgl+'</span>'+
                        '</div>'+

                        '<img class="direct-chat-img" src="../assets/dist/img/user1-128x128.jpg" alt="Message User Image">'+
                        '<div class="direct-chat-text">'+
                          data[i].msg+
                        '</div>'+

                      '</div>';
        $('.direct-chat-messages').append(sender);
      }else{
        var recv =  '<div class="direct-chat-msg right">'+
                      '<div class="direct-chat-info clearfix">'+
                        '<span class="direct-chat-name pull-right">'+data[i].user+'</span>'+
                        '<span class="direct-chat-timestamp pull-left">'+data[i].tgl+'</span>'+
                      '</div>'+

                      '<img class="direct-chat-img" src="../assets/dist/img/user3-128x128.jpg" alt="Message User Image">'+
                      '<div class="direct-chat-text">'+
                        data[i].msg+
                      '</div>'+

                    '</div>';
        $('.direct-chat-messages').append(recv);
      }
    }
  }

  bindRoom()

  $('.btn-chat').click(function(){
    var user1 = $(this).data('user1');
    var user2 = $(this).data('user2');
    $.ajax({
      type: 'post',
      url: '../proses.php?cmd=add_chat',
      dataType: 'json',
      data: {
        'user1': user1,
        'user2': user2,
      },
      success: function (data) {
        if(data.pesan == 1){
          location.reload();
        }else{
          $('.calert').append('<div class="callout callout-danger" >'+
                                '<h4>Alert!</h4>'+

                                '<p>'+data.pesan+'</p>'+
                              '</div>');
          $('.callout').fadeOut(3000, function(){ $(this).remove();});
        }
      },
      error: function (jqXHR, exception) {
          var msg = '';
          if (jqXHR.status === 0) {
              msg = 'Not connect.\n Verify Network.';
          } else if (jqXHR.status == 404) {
              msg = 'Requested page not found. [404]';
          } else if (jqXHR.status == 500) {
              msg = 'Internal Server Error [500].';
          } else if (exception === 'parsererror') {
              msg = 'Requested JSON parse failed.';
          } else if (exception === 'timeout') {
              msg = 'Time out error.';
          } else if (exception === 'abort') {
              msg = 'Ajax request aborted.';
          } else {
              msg = 'Uncaught Error.\n' + jqXHR.responseText;
          }
          alert(msg);
      },
    });
  })

  
</script>
</body>
</html>
