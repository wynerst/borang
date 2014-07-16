<?php

// key to authenticate
define('INDEX_AUTH', '1');

// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// paging class
require 'class_pager.php';
// session
require LIB_DIR.'session.inc.php';
require LIB_DIR.'session_check.inc.php';
// check privileges
if (!utility::havePrivilege('search', 'r')) {
  $main_content = '<div class="alert alert-error">Anda tidak memiliki hak untuk masuk ke bagian ini</div>';
	require './template/sosek/page_tpl.inc.php';
	exit();
}

$main_limit = '';
$page_title = 'Pencarian data sosek - '.$page_title;
$search = '';
$combine = '';

// start output buffer
ob_start();

if (isset($_POST['simpan'])) {
	$katakunci = array();
	$ruas = array();
	$ruaskunci = array();
	$simpan = $_POST['simpan'];
	// walk through the id of field
	$_sql = 'SELECT idruas, nama FROM ruas WHERE idkel_ruas =' . $simpan;
	$_sqlset = $dbs->query($_sql);
	while($rs = $_sqlset->fetch_assoc()) {
		if (isset($_POST[$rs['idruas']]) and ($_POST[$rs['idruas']] <>"")) {
			$katakunci[] = trim($_POST[$rs['idruas']]);
			$ruaskunci[] = $rs['idruas'];
		}
		$ruas[] = $rs['idruas'];
	}
	if (count($katakunci)>0) {
		$search = '+'.implode(' +',$katakunci);
		$combine = 'r.idruas ='.implode(' OR r.idruas =', $ruaskunci);
	} else {
		$combine = 'r.idruas ='.implode(' OR r.idruas =', $ruas);
	}
}

	if (isset($_GET['keywords'])) {
		$keyword = trim($_GET['keywords']);
	} elseif (isset($_POST['keywords'])) {
		$keyword = trim($_POST['keywords']);
	} else {
		$keyword = '' ;
	}

	echo '<h4>Hasil pencarian data:</h4><h5><i>'.$keyword.' '.implode(' ',$katakunci).'</i></h5>';

	if ($keyword<>'' OR $search<>'') {
		// contoh query sederhana
		$main_sql = 'SELECT c.*, l.nama AS desa, r.nama, r2.nama as kategori FROM main_cerita as c LEFT JOIN ruas as r ON c.idruas = r.idruas
			LEFT JOIN ruas as r2 ON r.idkel_ruas = r2.idruas
			LEFT JOIN lokasi as l ON c.idlokasi = l.idlokasi ';
		if ($main_limit == "") {
			if ($keyword<>"" OR $search<>"") {
				$main_limit .= '(MATCH (value) AGAINST (\''.$keyword.' '.$search. '\' IN BOOLEAN MODE)) ';
			}
			if ($combine<>'') {
				$main_limit .= 'AND ('.$combine.') ';
			}
//			if (isset($_POST['desa']) ) {
//				$main_limit .= 'AND (c.idlokasi ='.$_POST['desa'].') ';
//			}
			if (isset($_POST['tahun']) ) {
				$main_limit .= 'AND (c.tahun = '.$_POST['tahun'].') ';
			}
		}
		if ($main_limit <> "") {
			if (isset($_SESSION['uid']) AND $_SESSION['uid']>1) {
				$main_sql .= ' WHERE (c.published = 1 AND '.$main_limit. ')';
			} else {
				$main_sql .= ' WHERE '.$main_limit;
			}

			//$main_sql .= ' WHERE '.$main_limit;
			//$main_sql .= ' WHERE '.$main_limit	.' GROUP BY r2.nama';
			//die($main_sql);
			$query1 = $dbs->query($main_sql);

			// hasil temuan query
			$jumlah_hasil_temuan = $query1->num_rows;

				/*
				 * Configuration pager
				 */

				$config['url_page'] = 'result.php?keywords='.$keyword.'&thn='.$_GET['thn'].'&id='.$_GET['id'].'&page=';
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
				//echo $main_sql.'<br />';
				echo '<table class="table table-striped">';
				echo '<tr><th>Data</th><th>Tahun</th><th>Deskripsi</th><th>&nbsp;</th></tr>';
				$result = $dbs->query($paging_sql);
					while($rs = $result->fetch_assoc()) {
							echo '<tr><td>Standar: <strong>'.$rs['kategori'].'</strong> -> '.$rs['nama'].'</td>';
							echo '<td>'.$rs['tahun'].'</td>';
							echo '<td>'.nl2br($rs['value']).'</td>';
		//					echo '<td><a href="edit.php?id='.$rs['idmain_cerita'].'"><i class="icon-edit"></i></a></td>';
							echo '</tr>';
					}
					if ($result->num_rows < 1) {
					echo '<tr><td colspan="4"><div class="alert alert-error">Tidak ada data ditemukan</div>';
					echo '<a href="find.php" class="btn btn-success">Pencarian baru</a></td></tr>';
					}
				echo '</table>';

				/**
				 * display pager down data
				 */
				try {
					$pager->createPager();
				}
				catch(Exception $e) { echo $e->getMessage(); }
		} else {
			echo '<div class="alert alert-error">Parameter pencarian tidak lengkap.</div>';
			echo '<a href="find.php" class="btn btn-success">Pencarian baru</a></td></tr>';
		}
	}

$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
