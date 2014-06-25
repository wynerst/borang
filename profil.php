<?php

// key to authenticate
define('INDEX_AUTH', '1');

// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// paging class
require 'class_pager.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Peta kondisi sosial ekonomi wilayah</title>
<style type="text/css">
body { font:13px Tahoma, Geneva, sans-serif; }
.css-pager a { text-decoration:none; color:#666; background:#F4F4F4; border:1px solid #e0e0e0;
								 padding:2px 5px; margin:2px; font-weight:700; font-size:11px; }
.css-pager a:hover { text-decoration:none; color:#fff; background:#0A85CB; border:1px solid #3af;
										 padding:2px 5px; margin:2px; }
.current_page { background-color:#0A85CB; border:1px solid #3af; padding:2px 5px; margin:2px; color:#fff; font-weight:700; font-size:11px; }
</style>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" />
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

<?php
if (isset($_GET['desa'])) {
	$desa = $_GET['desa'];
	$tahun = date('Y');
}

if (isset($_GET['filter'])) {
	$desa = $_GET['filter'];
	$tahun = date('Y');
}

$main_limit = '';

if ($desa <> "") {
	if ($main_limit == "") {
		$main_limit = 'c.idlokasi = '.$desa;
	}
}

if (isset($_GET['desa']) OR isset($_GET['filter'])) {
	// contoh query sederhana
	$main_sql = 'SELECT c.*, l.nama AS desa, r.nama FROM main_cerita as c LEFT JOIN ruas as r ON c.idruas = r.idruas
		LEFT JOIN lokasi as l ON l.idlokasi=c.idlokasi ';
	if ($main_limit <> "") {
		$main_sql .= 'WHERE c.tahun='.$tahun.' AND r.idkel_ruas=1 AND l.nama LIKE \'%'. $desa . '%\' AND c.published = 1';
	} else {
		$main_sql .= 'WHERE c.published = 1';
	}

	//die($main_sql);
	$query1 = $dbs->query($main_sql);

	// hasil temuan query
	$jumlah_hasil_temuan = $query1->num_rows;

		/*
		 * Configuration pager
		 */

		$config['url_page'] = 'profil.php?filter='.$desa.'&thn='.$_GET['thn'].'&id='.$_GET['id'].'&page=';
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
					echo '<tr><td>'.$rs['nama'].' - Desa '.$rs['desa'];
					echo '<br /><a href="view.php?id='.$rs['idlokasi'].'&thn='.$rs['tahun'].'&kat='.$rs['idruas'].'" title="Tampilkan Berkas gambar"><i class="icon-camera"></i></a>';
					echo '&nbsp;&nbsp;<a href="profil.php?desa='.$rs['desa'].'" title="Tampilkan full screen" target="blank"><i class="icon-fullscreen"></i></a></td>';
					echo '<td>'.$rs['tahun'].'</td>';
					echo '<td>'.nl2br(trim($rs['value'])).'</td>';
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
?>
</body>
</html>
