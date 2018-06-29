<?php /* ?>
<!--<a href="<?=$this->config->item('base_url')?>home/index/<?=isset($pid)?$pid:'';?>"><i class="fa fa-power-off"></i>Home</a>
<a href="<?=$this->config->item('base_url')?>search"><i class="fa fa-power-off"></i>Search</a>
<a href="<?=$this->config->item('base_url')?>profile"><i class="fa fa-power-off"></i>Profile</a>
<a href="<?=$this->config->item('base_url')?>purchase"><i class="fa fa-power-off"></i>Puchase Histroy</a>
<a href="<?=$this->config->item('base_url')?>income"><i class="fa fa-power-off"></i>Income</a>
<a href="<?=$this->config->item('base_url')?>home/logout"><i class="fa fa-power-off"></i>Log Out</a>
<br>
<?php echo (isset($page)?$page:'');?>

<?=isset($pid)?$pid:'';?>-->
<?php */ ?>


<!DOCTYPE html>
<html lang="en">
  <head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">

	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="<?=$this->config->item('base_url')?>public/images/favicon.ico">
	<title>VISU LIVE - <?php echo $page; ?></title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?=$this->config->item('base_url')?>public/frontend/css/materialize.min.css">
	<link rel="stylesheet" href="<?=$this->config->item('base_url')?>public/frontend/node_modules/bootstrap/dist/css/bootstrap.min.css">
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<link href="<?=$this->config->item('base_url')?>public/frontend/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
	<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
	<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
	<script src="<?=$this->config->item('base_url')?>public/frontend/js/ie-emulation-modes-warning.js"></script>
	<link rel="stylesheet" href="<?=$this->config->item('base_url')?>public/frontend/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?=$this->config->item('base_url')?>public/frontend/css/style.css">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  </head>
	  <body class="dashboard_container">
		<p class="pull-left visible-xs">
			  <button type="button" class="btn btn-primary btn-xs toggleBut" data-toggle="offcanvas">
			  <i class="glyphicon glyphicon glyphicon-list"></i></button>
		  </p><!--/.sidebar-offcanvas-->
		  <div class="container-fluid">
			<div class="row row-offcanvas row-offcanvas-left">
