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

$page_title = 'Administer Grup';

// start output buffer
ob_start();
echo '<h4 class="pull-left">'.$page_title.'</h4>';
echo '<div class="pull-right"><a class="btn btn-primary" href="./administer_group.php?add=true">Tambahkan Grup</a></div>';
echo '<p class="clear"></p>';

if (isset($_GET['add']) && $_GET['add'] === 'true') {
  if (isset($_POST['simpan'])) {
    $group_name = $dbs->escape_string(filter_input(INPUT_POST, 'group_name', FILTER_SANITIZE_STRING));
		if (!isset($_POST['update'])) {
		  $sql = "INSERT INTO `user_group` (`group_name`)
		    VALUES ('$group_name')";
		} else {
			$updateid = filter_input(INPUT_POST, 'update', FILTER_SANITIZE_NUMBER_INT);
		  $sql = "UPDATE `user_group` SET `group_name`='$group_name' WHERE group_id=$updateid";
			$group_id = $updateid; $_GET['uid'] = $group_id;
		}
    $query = $dbs->query($sql);
		if ($query) {
      if (!isset($_POST['update'])) { $group_id = $dbs->insert_id; }

       if (isset($_POST['baca'])) {
         foreach ($_POST['baca'] as $module) {
           // check write privileges
           $is_write = 0;
           if (isset($_POST['tulis'])) {
              foreach ($_POST['tulis'] as $module_write) {
                 if ($module_write == $module) {
                   $is_write = 1;
                 }
              }
           }
           $dbs->query("INSERT IGNORE INTO group_access VALUES ($group_id, $module, 1, $is_write)");
         }
      }
		  echo '<div class="alert alert-info">Data berhasil disimpan</div>';
		} else {
			echo '<div class="alert alert-error">Data GAGAL disimpan</div>';
		}
  }
  if (isset($_POST['hapus'])) {
	  $updateid = filter_input(INPUT_POST, 'update', FILTER_SANITIZE_NUMBER_INT);
	  $sql = "DELETE FROM `group` WHERE user_id=$updateid";
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
  $grp_q = $dbs->query('SELECT * FROM `user_group` AS g WHERE `group_id`='.$uid);
  $grp_d = $grp_q->fetch_assoc();
  // TAMBAH/EDIT USER
  ?>
  <div class="row"><div class="span8">
  <form method="POST" action="./administer_group.php?add=true" class="form-horizontal">

    <div class="control-group">
    <label class="control-label" for="inputGrup">Nama Grup</label>
    <div class="controls">
    <input type="text" id="inputGrup" name="group_name" placeholder="Nama Grup" value="<?php echo $grp_d['group_name']; ?>">
    </div>
    </div>

    <div class="control-group">
    <label class="control-label" for="inputGrup">Hak Akses</label>
    <div class="controls">
    <?php
		$priv_data = array();
		if (!empty($grp_d['group_id'])) {
      // hak akses yang ada sekarang
      $rec_q = $dbs->query('SELECT * FROM group_access WHERE group_id='.(!empty($grp_d['group_id'])?$grp_d['group_id']:0));
      while ($access_d = $rec_q->fetch_assoc()) {
        $priv_data[$access_d['module_id']]['r'] = $access_d['r'];
        $priv_data[$access_d['module_id']]['w'] = $access_d['w'];
      }
		}
    $modl_sql = 'SELECT * FROM `mst_module`';
		echo '<table class="table table-bordered table-striped" id="tabel-hak-akses">';
		echo '<tr><th>Hak Akses</th><th>Baca</th><th>Tulis</th></tr>';
    $modl_q = $dbs->query($modl_sql);
    while ($modl_d = $modl_q->fetch_assoc()) {

      $read_checked = '';
      $write_checked = '';

      if (isset($priv_data[$modl_d['module_id']]['r']) AND $priv_data[$modl_d['module_id']]['r'] == 1) {
          $read_checked = 'checked';
      }

      if (isset($priv_data[$modl_d['module_id']]['w']) AND $priv_data[$modl_d['module_id']]['w'] == 1) {
          $read_checked = 'checked';
          $write_checked = 'checked';
      }

      echo '<tr>';
			echo '<td>'.$modl_d['module_name'].'</td>';
      echo '<td class="chbox"><input type="checkbox" name="baca[]" value="'.$modl_d['module_id'].'" '.$read_checked.'/></td>';
      echo '<td class="chbox"><input type="checkbox" name="tulis[]" value="'.$modl_d['module_id'].'" '.$write_checked.'/></td>';
      echo '</tr>';
    }
		echo '</table>';
    ?>
    </div>
    </div>

    <div class="control-group">
    <div class="controls">
    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
		<?php if (!empty($grp_d['group_id'])) : ?>
    <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
		<?php endif; ?>
    </div>
    </div>

		<?php if (!empty($grp_d['group_id'])) : ?>
    <input type="hidden" id="update" name="update" value="<?php echo $grp_d['group_id']; ?>" />
		<?php endif; ?>
	</form>
<?php
} else {
  // DAFTAR USER
  $main_sql = 'SELECT * FROM `user_group` as g';
  if ($main_limit <> "") {
  	$main_sql .= 'WHERE '. $main_limit;
  	$main_sql .= ' AND group_id<>1';
  } else {
    $main_sql .= ' WHERE group_id<>1';
  }
  //die($main_sql);
  $query1 = $dbs->query($main_sql);

  // hasil temuan query
  $jumlah_hasil_temuan = $query1->num_rows;

  /*
   * Configuration pager
   */

  $config['url_page'] = 'administer_group.php?page=';
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
  echo '<tr><th>Nama Grup</th><th>Edit</th></tr>';
  $result = $dbs->query($paging_sql);
  	while($rs = $result->fetch_assoc()) {
  			echo '<tr><td>'.$rs['group_name'].'</td>';
  			echo '<td><a href="administer_group.php?add=true&uid='.$rs['group_id'].'"><i class="icon-edit"></i></a></td>';
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
