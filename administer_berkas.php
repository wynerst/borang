<?php
// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// session
require LIB_DIR.'session.inc.php';
require LIB_DIR.'session_check.inc.php';
// check privileges
if (!utility::havePrivilege('publish', 'w')) {
  $main_content = '<div class="alert alert-error">Anda tidak memiliki hak untuk masuk ke bagian ini</div>';
	require './template/sosek/page_tpl.inc.php';
	exit();
}

require SIMBIO_BASE_DIR.'simbio_GUI/table/simbio_table.inc.php';
require SIMBIO_BASE_DIR.'simbio_GUI/paging/simbio_paging.inc.php';
require SIMBIO_BASE_DIR.'simbio_DB/datagrid/simbio_dbgrid.inc.php';
require SIMBIO_BASE_DIR.'simbio_DB/simbio_dbop.inc.php';
require 'class_pager.php';

$page_title = 'Persetujuan';
$desa = 0;
$tahun = 0;
$filter = 0;

if (isset($_POST['review'])) {
	$desa = $_POST['id'];
	$tahun = $_POST['tahun'];
	$filter = $_POST['filter'];
} elseif (isset($_GET['id'])) {
	$desa = $_GET['id'];
} elseif (isset($_GET['thn'])) {
	$tahun = $_GET['thn'];
} elseif (isset($_GET['filter'])) {
	$filter = $_GET['filter'];
}

ob_start();
echo '<h4>Review data untuk dipublikasi</h4>';

if (isset($_POST['ok'])) {
    $reviewed= $_POST['approved'];
    $idrev = implode(' OR idmain_cerita =', $reviewed);
    $where = 'idmain_cerita =' . $idrev;
    $updatedata['published'] = 1;
    $updatedata['update_date'] = date('Y-m-d');
	$_db_ops = new simbio_dbop($dbs);
    $published = $_db_ops->update('main_cerita', $updatedata, $where);
    if ($published) {
		echo '<div class="alert alert-success">Data terpilih berhasil dipublikasikan</div>';
	} else {
		echo '<div class="alert alert-error">Data terpilih GAGAL dipublikasikan<br />'.$_db_ops->error.'</div>';
	}
}


//display data not reviewed
	$main_sql = 'SELECT c.*, r.nama, l.nama as desa FROM main_cerita as c LEFT JOIN ruas as r ON c.idruas = r.idruas
	   LEFT JOIN lokasi as l ON l.idlokasi = c.idlokasi WHERE c.published=0';

	$query1 = $dbs->query($main_sql);

	// hasil temuan query
	$jumlah_hasil_temuan = $query1->num_rows;
	if ($jumlah_hasil_temuan == 0) {
		echo '<div class="alert alert-error">Tidak ada data untuk di review.</div>';
	}

		/*
		 * Configuration pager
		 */

		$config['url_page'] = 'administer_editor.php?filter='.$filter.'&thn='.$tahun.'&id='.$desa.'&page=';
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
		echo '<form method="POST"><table class="table table-striped">';
		echo '<tr><th>Data</th><th>Tahun</th><th>Deskripsi</th><th><input type="submit" name="ok" value="Publikasikan"></th></tr>';
		$result = $dbs->query($paging_sql);
			while($rs = $result->fetch_assoc()) {
					echo '<tr><td>'.$rs['desa'].'<br /> - '.$rs['nama'].'</td>';
					echo '<td>'.$rs['tahun'].'</td>';
					echo '<td>'.nl2br($rs['value']).'</td>';
					echo '<td><label class="checkbox inline"><input type="checkbox" name="approved[]" value="'.$rs['idmain_cerita'].'"> OK</label><br />
					  <a href="edit.php?id='.$rs['idmain_cerita'].'" title="Edit data temuan"><i class="icon-edit"></i></a>  <a href="edit.php?del='.$rs['idmain_cerita'].'" title="Hapus data temuan"><i class="icon-remove-sign"></i></a></td>';
//					echo '<td><a href="edit.php?id='.$rs['idmain_cerita'].'" title="Edit data temuan"><i class="icon-edit"></i></a>';
//					echo ' <a href="edit.php?del='.$rs['idmain_cerita'].'" title="Hapus data temuan"><i class="icon-remove-sign"></i></a>';
//					echo ' <a href="berkas.php?id='.$rs['idlokasi'].'&thn='.$rs['tahun'].'&kat='.$rs['idruas'].'" title="Berkas gambar dan foto"><i class="icon-camera"></i></a></td>';
					echo '</tr>';
			}
		echo '</table>';
		echo '</form>';

		/**
		 * display pager down data
		 */
		try {
			$pager->createPager();
		}
		catch(Exception $e) { echo $e->getMessage(); }



// Search data
echo '<form method="GET" class="form-inline">';
// Lokasi Desa
echo '<select name="id">';
echo '<option value="">Pilih Desa</option>';
$combo_sql = 'SELECT idlokasi, nama FROM lokasi WHERE idlokasi<>6 ';
$combo_sql .= 'ORDER BY nama ';
$combo_set = $dbs->query($combo_sql);
while($rs = $combo_set->fetch_assoc()) {
	echo '<option value="'.$rs['idlokasi'].'">'.$rs['nama'].'</option>';
}
echo '</select> ';

echo '<input type="text" class="input-medium" name="tahun" placeholder="Periode Tahun"> ';

echo '<select name="filter">';
echo '<option value="">Pilih kategori</option>';
$combo_sql = 'SELECT idruas, nama FROM ruas WHERE idkel_ruas = 0 ';
$combo_sql .= 'ORDER BY nama ';
$combo_set = $dbs->query($combo_sql);
while($rs = $combo_set->fetch_assoc()) {
	if (isset($filter) AND $filter == $rs['idruas']) {
		echo '<option value="'.$rs['idruas'].'" selected >'.$rs['nama'].'</option>';
	} else {
		echo '<option value="'.$rs['idruas'].'">'.$rs['nama'].'</option>';
	}
}
echo '</select> ';
echo '<button type="submit" name="review" class="btn btn-success">Tampilkan data</button>';
echo '</form>';

$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';

