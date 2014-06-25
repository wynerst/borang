<?php
// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// session
require LIB_DIR.'session.inc.php';
require LIB_DIR.'session_check.inc.php';
// check privileges
if (!utility::havePrivilege('search', 'r')) {
  $main_content = '<div class="alert alert-error">Anda tidak memiliki hak untuk masuk ke bagian ini</div>';
	require './template/sosek/page_tpl.inc.php';
	exit();
}

if (isset($_POST['filter'])) {
	$filter = $_POST['filter'];
}

$page_title = 'Pencarian data';

ob_start();
echo '<div class="row"><div class="span8">';
echo '<h4>'.$page_title.'</h4>';
echo '<form method="POST" class="form-search">';
// Lokasi Desa
echo '<select name="id">';
echo '<option value="">Pilih Desa</option>';
$combo_sql = 'SELECT idlokasi, nama FROM lokasi WHERE idlokasi<>6 ';
$combo_sql .= 'ORDER BY nama ';
$combo_set = $dbs->query($combo_sql);
while($rs = $combo_set->fetch_assoc()) {
	if (isset($_POST['id']) AND $_POST['id'] == $rs['idlokasi']) {
		echo '<option value="'.$rs['idlokasi'].'" selected >'.$rs['nama'].'</option>';
	} else {
		echo '<option value="'.$rs['idlokasi'].'">'.$rs['nama'].'</option>';
	}
}
echo '</select> ';

echo '<input type="text" placeholder="Periode Tahun" class="input-small" name="tahun"';
if (isset($_POST['tahun']) and $_POST['tahun'] <> "") {
	echo ' value="'.$_POST['tahun'].'"> ';
} else {
	echo '> ';
}
echo '<select name="filter">';
echo '<option value="">Dalam Kategori</option>';
$combo_sql = 'SELECT idruas, nama FROM ruas WHERE idkel_ruas = 0 ';
$combo_sql .= 'ORDER BY nama ';
$combo_set = $dbs->query($combo_sql);
while($rs = $combo_set->fetch_assoc()) {
//	if (isset($filter) AND $filter == $rs['idruas']) {
//		echo '<option value="'.$rs['idruas'].'" selected >'.$rs['nama'].'</option>';
//	} else {
		echo '<option value="'.$rs['idruas'].'">'.$rs['nama'].'</option>';
//	}
}
echo '</select> ';
echo '<button type="submit" class="btn btn-success" >Tampilkan Form</button>';
echo '</form></div>';
echo '</div>';

echo '<form action="result.php" class="form-horizontal" method="POST">';

if (isset($_POST['filter']) AND $_POST['filter'] <> "") {
	// cari ruas dan judul ruas
	$combo_sql = 'SELECT * FROM ruas WHERE idkel_ruas = '.$_POST['filter']. ' OR idruas = '.$_POST['filter'].' ORDER BY idruas';
	$combo_set = $dbs->query($combo_sql);
	while($rs = $combo_set->fetch_assoc()) {
		if ($rs['idkel_ruas'] == 0) {
			echo '<legend>'.$rs['nama'].'</legend>';
			echo '<div class="control-group">';
			echo '<label class="control-label">Dari semua sub-kategori '.$rs['nama'].'</label>';
			//echo '<input type="hidden" name="main" value="'.$rs['idruas'].'">';
			echo '<div class="controls"><input type="text" class="input-xlarge" name="keywords" placeholder="Kata kunci"></div>';
			echo '</div>';

		} else {
			echo '<div class="control-group">';
			echo '<label class="control-label">'.$rs['nama'].'</label>';
			echo '<div class="controls"><input type="text" placeholder="Kata kunci" class="input-xlarge" name="'.$rs['idruas'].'"></div>';
			echo '<span class="help-block">'.$rs['deskripsi'].'</span>';
			echo '</div>';
		}
	}
} else {
	echo '<legend>'.$rs['nama'].'</legend>';
	echo '<div class="control-group">';
	echo '<label class="control-label">Dari semua kategori '.$rs['nama'].'</label>';
	echo '<div class="controls"><input type="text" class="input-xlarge" name="keywords" placeholder="Kata kunci"></div>';
	echo '</div>';

}
if (isset($_POST['tahun']) and $_POST['tahun'] <>"") {
	echo '<input type="hidden" name="tahun" value="'.$_POST['tahun'].'">';
}
if (isset($_POST['id']) and $_POST['id'] <>"") {
	echo '<input type="hidden" name="desa" value="'.$_POST['id'].'">';
}
if (isset($_POST['filter']) and $_POST['filter'] <>"") {
	echo '<input type="hidden" name="simpan" value="'.$_POST['filter'].'">';
}
echo '<div class="control-group">';
echo '<label class="control-label">'.$rs['nama'].'</label>';
echo '<div class="controls">';
echo '<button type="submit" class="btn btn-primary" >Temukan</button>';
echo '</div>';
echo '</div>';
echo '</form>';

$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
