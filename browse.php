<?php
// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// session
require LIB_DIR.'session.inc.php';
require LIB_DIR.'session_check.inc.php';
// check privileges
if (!utility::havePrivilege('filter', 'r')){
  $main_content = '<div class="alert alert-error">Anda tidak memiliki hak untuk masuk ke bagian ini</div>';
	require './template/sosek/page_tpl.inc.php';
	exit();
}

// paging class
require 'class_pager.php';

if (isset($_GET['filter'])) {
	$filter = $_GET['filter'];
}
if (isset($_GET['id'])) {
	$desa = $_GET['id'];
}
if (isset($_GET['thn'])) {
	$tahun = $_GET['thn'];
}

$main_limit = '';

if (isset($filter) AND $filter <> "") {
	if ($main_limit == "") {
		$main_limit = 'r.idkel_ruas = '.$filter;
	} else {
		$main_limit .= ' AND r.idkel_ruas = '.$filter;
	}
}
if (isset($tahun) AND $tahun <> "") {
	if ($main_limit == "") {
		$main_limit = 'c.tahun = '.$tahun;
	} else {
		$main_limit .= ' AND c.tahun = '.$tahun;
	}
}
if (isset($desa) AND $desa <> "") {
	if ($main_limit == "") {
		$main_limit = 'c.idlokasi = '.$desa;
	} else {
		$main_limit .= ' AND c.idlokasi = '.$desa;
	}
}

// start output buffer
ob_start();

//$page_title = 'Peta kondisi sosial ekonomi wilayah';


	// contoh query sederhana
	$main_sql = 'SELECT c.*, r.nama, u.realname FROM main_cerita as c LEFT JOIN ruas as r ON c.idruas = r.idruas
	   LEFT JOIN user as u ON c.user_id = u.user_id ';
	if ($main_limit <> "") {
		$main_sql .= 'WHERE '. $main_limit;
	}
	//die($main_sql);
	$query1 = $dbs->query($main_sql);

	// hasil temuan query
	$jumlah_hasil_temuan = $query1->num_rows;

		/*
		 * Configuration pager
		 */

		$config['url_page'] = 'index.php?filter='.$_GET['filter'].'&thn='.$_GET['thn'].'&id='.$_GET['id'].'&page=';
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
		}
		catch(Exception $e) { echo $e->getMessage(); }

	// ambil hasil query
	// looping

		/**
		 * display data
		 */
		$paging_sql = $main_sql . " LIMIT ".$pager->limitStart().", ".$config['per_page'];
		//echo $paging_sql.'<br />';
		echo '<table class="table table-striped">';
		echo '<tr><th>Data</th><th>Tahun</th><th>Deskripsi</th><th>&nbsp;</th></tr>';
		$result = $dbs->query($paging_sql);
			while($rs = $result->fetch_assoc()) {
					echo '<tr><td>'.$rs['nama'];
					if (utility::havePrivilege('publish', 'w')){
						echo '<br />OPT: '.$rs['realname'];
					}
					echo '</td>';
					echo '<td>'.$rs['tahun'].'</td>';
					echo '<td>'.nl2br($rs['value']).'</td>';
					if (utility::havePrivilege('filter', 'w')){
						echo '<td><a href="edit.php?id='.$rs['idmain_cerita'].'" title="Edit data temuan"><i class="icon-edit"></i></a>';
						if ($rs['user_id'] == $_SESSION['uid'] OR $_SESSION['uid'] == 1) {
							echo ' <a href="edit.php?del='.$rs['idmain_cerita'].'" title="Hapus data temuan"><i class="icon-remove-sign"></i></a>';
						}
						echo ' <a href="berkas.php?id='.$rs['idlokasi'].'&thn='.$rs['tahun'].'&kat='.$rs['idruas'].'" title="Berkas gambar dan foto"><i class="icon-camera"></i></a>';
						echo '</td>';
					} else {
						echo '<td><a href="images.php?id='.$rs['idlokasi'].'&thn='.$rs['tahun'].'&kat='.$rs['idruas'].'"><i class="icon-camera"></i></a></td>';
					}
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

$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
