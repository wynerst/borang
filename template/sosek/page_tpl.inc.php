<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $page_title; ?></title>
<link rel="stylesheet" type="text/css" href="./bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="./bootstrap/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" type="text/css" href="./style.css" />
<script type="text/javascript" src="./js/jquery.js"></script>
<script type="text/javascript" src="./bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <a class="brand" href="./csr.php"> &nbsp; Borang Akreditasi</a>
    <ul class="nav">
      <li><a href="./home.php">Home</a></li>
      <?php if (utility::havePrivilege('filter', 'r')) : ?>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="./index.php">Browsing <b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="./#">Filter data</a></li>
			<li><a href="./#">Walktrough Borang data</a></li>
		</ul>
      </li>
      <?php endif; ?>

      <?php if (utility::havePrivilege('search', 'r')) : ?>
      <li><a href="./find.php">Find</a></li>
      <?php endif; ?>

      <?php if (utility::havePrivilege('add', 'w')) : ?>
      <li><a href="./input.php">Data Entry</a></li>
      <?php endif; ?>

      <?php if (utility::havePrivilege('administer', 'w')) : ?>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Administer <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="./kategori.php">Kategori data</a></li>
          <li><a href="./administer_user.php">Administer users</a></li>
          <li><a href="./administer_group.php">Administer groups</a></li>
          <li><a href="./administer_editor.php">Persetujuan data</a></li>
        </ul>
      </li>
      <?php endif; ?>

      <?php if (isset($_SESSION['uid']) && isset($_SESSION['uname'])) : ?>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Akun <b class="caret"></b></a>
        <ul class="dropdown-menu">
		  <li><a href="./logout.php">Logout/Keluar</a></li>
          <li><a href="./password.php">Ganti Password</a></li>
        </ul>
      </li>
      <?php else: ?>
      <li><a href="./login.php">Login</a></li>
      <?php endif; ?>
    </ul>
  </div>
</div>
<?php echo $main_content; ?>
</body>
</html>
