<?php
// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// session
require LIB_DIR.'session.inc.php';
require LIB_DIR.'session_check.inc.php';
// check privileges
if (!utility::havePrivilege('administer', 'r')){
  $main_content = '<div class="alert alert-error">Anda tidak memiliki hak untuk masuk ke bagian ini</div>';
	require './template/sosek/page_tpl.inc.php';
	exit();
}

// paging class
require 'class_pager.php';

$page_title = 'Administer user';

// start output buffer
ob_start();
echo '<h4 class="pull-left">'.$page_title.'</h4>';
echo '<div class="pull-right"><a class="btn btn-primary" href="./administer_user.php?add=true">Tambahkan User</a></div>';
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
			  $sql = "INSERT INTO `user` (`realname`, `username`, `passwd`, `groups`, `input_date`, `last_update`)
			    VALUES ('$realname', '$username', MD5('$passwd'), '$groups', '".date('Y-m-d')."', '".date('Y-m-d')."' )";
			} else {
				$updateid = filter_input(INPUT_POST, 'update', FILTER_SANITIZE_NUMBER_INT);
			  $sql = "UPDATE `user` SET `realname`='$realname', `username`='$username', `passwd`=MD5('$passwd'), `groups`='$groups'
				  WHERE user_id=$updateid";
				$_GET['uid'] = $updateid;
			}
      $query = $dbs->query($sql);
			if ($query) {
			  echo '<div class="alert alert-info">Data berhasil disimpan</div>';
			} else {
				echo '<div class="alert alert-error">Data GAGAL disimpan</div>';
			}
		}
  }
  if (isset($_POST['hapus'])) {
	  $updateid = filter_input(INPUT_POST, 'update', FILTER_SANITIZE_NUMBER_INT);
	  $sql = "DELETE FROM `user` WHERE user_id=$updateid";
	  $query = $dbs->query($sql);
	  if ($query) {
		  echo '<div class="alert alert-info">Data berhasil dihapus</div>';
	  } else {
		  echo '<div class="alert alert-error">Data GAGAL dihapus</div>';
	  }
  }

  $uid = 0;
  if (isset($_GET['uid'])) {
    $uid = (integer)$_GET['uid'];
  }
  // query data user
  $user_q = $dbs->query('SELECT * FROM user AS u WHERE user_id='.$uid);
  $user_d = $user_q->fetch_assoc();
  // TAMBAH/EDIT USER
  ?>
  <div class="row"><div class="span8">
  <form method="POST" action="./administer_user.php?add=true" class="form-horizontal">

    <div class="control-group">
    <label class="control-label" for="inputRealName">Nama Lengkap</label>
    <div class="controls">
    <input type="text" id="inputRealName" name="realname" placeholder="Nama Lengkap" value="<?php echo $user_d['realname']; ?>">
    </div>
    </div>

    <div class="control-group">
    <label class="control-label" for="inputUserName">Login Username</label>
    <div class="controls">
    <input type="text" id="inputUserName" name="username" placeholder="Login Username" value="<?php echo $user_d['username']; ?>">
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
      echo '<div class="checkbox"><input type="checkbox" name="groups[]" value="'.$grup_d['group_id'].'"'.$checked.' /> '.$grup_d['group_name'].'</div>';
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
    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
		<?php if (!empty($user_d['user_id'])) : ?>
    <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
		<?php endif; ?>
    </div>
    </div>

		<?php if (!empty($user_d['user_id'])) : ?>
    <input type="hidden" id="update" name="update" value="<?php echo $user_d['user_id']; ?>" />
		<?php endif; ?>
	</form>
<?php
} else {
  // DAFTAR USER
  $main_sql = 'SELECT * FROM user as u';
  if ($main_limit <> "") {
  	$main_sql .= 'WHERE '. $main_limit;
  	$main_sql .= ' AND user_id<>1';
  } else {
    $main_sql .= ' WHERE user_id<>1';
  }
  //die($main_sql);
  $query1 = $dbs->query($main_sql);

  // hasil temuan query
  $jumlah_hasil_temuan = $query1->num_rows;

  /*
   * Configuration pager
   */

  $config['url_page'] = 'administer_user.php?page=';
  $config['all_recs'] = $jumlah_hasil_temuan;	// all row of data
  $config['scr_page'] = 5;	// scroll page
  $config['per_page'] = 10;	// per pager
  $config['cur_page'] = (isset($_GET['page'])) ? $_GET['page'] : 1;	// current page
  $config['act_page'] = 'class="current_page"';	// class css current page
  $config['css_page'] = 'class="css-pager"';	// clss css area split page
  $config['first'] = '&laquo; First';	// first page
  $config['previous'] = '&lsaquo; Prev';	// previous page
  $config['next']  = 'Next &rsaquo;';	// next page
  $config['last']  = 'Last &raquo;';	// last page

  /**
   * create pager instance
   */
  $pager = new Pager($config);

  /**
   * display pager up data
   */

  try {
  	$pager->createPager();
  } catch(Exception $e) { echo $e->getMessage(); }

  /**
   * display data
   */
  $paging_sql = $main_sql . " LIMIT ".$pager->limitStart().", ".$config['per_page'];

  //echo $paging_sql.'<br />';
  echo '<table class="table table-striped">';
  echo '<tr><th>Nama Lengkap</th><th>Login</th><th>Akses terakhir</th><th>Edit</th></tr>';
  $result = $dbs->query($paging_sql);
  	while($rs = $result->fetch_assoc()) {
  			echo '<tr><td>'.$rs['realname'].'</td>';
  			echo '<td>'.$rs['username'].'</td>';
  			echo '<td>'.$rs['last_login_ip'].'</td>';
  			echo '<td><a href="administer_user.php?add=true&uid='.$rs['user_id'].'"><i class="icon-edit"></i></a></td>';
  			echo '</tr>';
  	}
  echo '</table>';

  /**
   * display pager down data
   */
  try {
  	$pager->createPager();
  }
  catch(Exception $e) { echo $e->getMessage(); }
}


$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
