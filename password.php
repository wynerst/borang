<?php
// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// session
require LIB_DIR.'session.inc.php';
require LIB_DIR.'session_check.inc.php';
// check privileges
if (!isset($_SESSION['uid']) && !isset($_SESSION['uname'])) {
	$main_content = '<div class="alert alert-error">Silahkan login kedalam aplikasi terlebih dahulu</div>';
	require './template/sosek/page_tpl.inc.php';
	exit();
}

// paging class
require 'class_pager.php';

$page_title = 'Administer user password';

// start output buffer
ob_start();
echo '<h4 class="pull-left">'.$page_title.'</h4>';
echo '<p class="clear"></p>';

if (isset($_GET['add']) && $_GET['add'] === 'true') {
  if (isset($_POST['simpan'])) {
	  if ($_POST['password1'] !== $_POST['password2']) {
      echo '<div class="alert alert-error">Password tidak sama!</div>';
	  } else {
      $realname = $dbs->escape_string(filter_input(INPUT_POST, 'realname', FILTER_SANITIZE_STRING));
      $username = $dbs->escape_string(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
      $groups = $dbs->escape_string(@serialize($_POST['groups']));
	  $passwd = $dbs->escape_string(filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING));
			if (!isset($_POST['update'])) {
			  $sql = "INSERT INTO `user` (`realname`, `username`, `passwd`, `groups`)
			    VALUES ('$realname', '$username', MD5('$passwd'), '$groups')";
			} else {
				$updateid = filter_input(INPUT_POST, 'update', FILTER_SANITIZE_NUMBER_INT);
			  $sql = "UPDATE `user` SET `realname`='$realname', `passwd`=MD5('$passwd'), `last_update`='".date('Y-m-d')."'
				  WHERE user_id=$updateid";
				$_GET['uid'] = $updateid;
			}
      $query = $dbs->query($sql);
			if ($query) {
			  echo '<div class="alert alert-info">Data berhasil disimpan</div>';
			  $main_content = ob_get_clean();
			  require './template/sosek/page_tpl.inc.php';
			  exit();
			} else {
				echo '<div class="alert alert-error">Data GAGAL disimpan</div>';
			}
		}
  }

}

  $uid = 0;
  if (isset($_SESSION['uid'])) {
    $uid = (integer)$_SESSION['uid'];
  }
  // query data user
  $user_q = $dbs->query('SELECT * FROM user AS u WHERE user_id='.$uid);
  $user_d = $user_q->fetch_assoc();
  // TAMBAH/EDIT USER
  ?>
  <div class="row"><div class="span8">
  <form method="POST" action="./password.php?add=true" class="form-horizontal">

    <div class="control-group">
    <label class="control-label" for="inputRealName">Nama Lengkap</label>
    <div class="controls">
    <input type="text" id="inputRealName" name="realname" placeholder="Nama Lengkap" value="<?php echo $user_d['realname']; ?>">
    </div>
    </div>

    <div class="control-group">
    <label class="control-label" for="inputUserName">Login Username</label>
    <div class="controls">
    <input disabled=disabled type="text" id="inputUserName" name="username" placeholder="Login Username" value="<?php echo $user_d['username']; ?>">
    </div>
    </div>

    <div class="control-group">
    <label class="control-label" for="inputGrup">Grup</label>
    <div class="controls">
    <?php
    $curr_groups = array();
    if ($user_d['groups']) {
      $curr_groups = @unserialize($user_d['groups']);
    }
    $grup_sql = 'SELECT * FROM user_group';
    $grup_q = $dbs->query($grup_sql);
    while ($grup_d = $grup_q->fetch_assoc()) {
      if (in_array($grup_d['group_id'], $curr_groups)) {
        $checked = ' checked';
      } else {
        $checked = '';
      }
      echo '<div class="checkbox"><input disabled=disabled type="checkbox" name="groups[]" value="'.$grup_d['group_id'].'"'.$checked.' /> '.$grup_d['group_name'].'</div>';
    }
    ?>
    </div>
    </div>

    <div class="control-group">
    <label class="control-label" for="password1">Password</label>
    <div class="controls">
    <input type="password" id="password1" name="password1" value="">
    </div>
    </div>

    <div class="control-group">
    <label class="control-label" for="password2">Konfirmasi Password</label>
    <div class="controls">
    <input type="password" id="password2" name="password2" value="">
    </div>
    </div>

    <div class="control-group">
    <div class="controls">
    <button type="submit" name="simpan" class="btn btn-primary">Update</button>
    </div>
    </div>

		<?php if (!empty($user_d['user_id'])) : ?>
    <input type="hidden" id="update" name="update" value="<?php echo $user_d['user_id']; ?>" />
		<?php endif; ?>
	</form>
<?php

$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
