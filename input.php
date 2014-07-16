<?php
// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// session
require LIB_DIR.'session.inc.php';
require LIB_DIR.'session_check.inc.php';
// check privileges
if (!utility::havePrivilege('add', 'w')) {
  $main_content = '<div class="alert alert-error">Anda tidak memiliki hak untuk masuk ke bagian ini</div>';
	require './template/sosek/page_tpl.inc.php';
	exit();
}

$page_title = 'Data entry';

ob_start();
echo '<h4>'.$page_title.'</h4>';
$output = '';
$tahun = '';
$desa = '';
if (isset($_POST['filter'])) {
	$tahun = $_POST['tahun'];
//	$desa = $_POST['id'];
	$desa = 999;
	$filter = $_POST['filter'];
	// cari ruas dan judul ruas
	$combo_sql = 'SELECT b_atas, b_bawah FROM ruas WHERE idruas = '.$_POST['filter'];
	$combo_set = $dbs->query($combo_sql);
	$rs_batas = $combo_set->fetch_array();
	$batas_atas = $rs_batas['b_atas'];
	$batas_bawah = $rs_batas['b_bawah'];
	$combo_sql = 'SELECT * FROM ruas WHERE b_atas >= '.$batas_atas.' AND b_bawah <= '.$batas_bawah.' ORDER BY b_atas, b_bawah';
	$combo_set = $dbs->query($combo_sql);
	$output .= '<form class="form-horizontal" method="POST">';
	while($rs = $combo_set->fetch_assoc()) {
		if ($rs['tingkat'] == 0) {
			$output .= '<legend>'.$rs['nama'].'</legend>';
			$output .= '<span class="help-block">'.$rs['deskripsi'].'</span>';
		} else {
			$output .= '<div class="control-group">';
			$output .= '<label class="control-label">'.$rs['nama'].'</label>';
			$output .= '<div class="controls"><textarea rows="1" class="input-block-level" name="'.$rs['idruas'].'"></textarea>';
			if ($rs['deskripsi'] <> "") {
				$output .= '<span class="help-block">'.$rs['deskripsi'].'</span>';
			}
			$output .= '</div></div>';
		}
	}
	$output .= '<input type="hidden" name="tahun" value="'.$tahun.'">';
//	$output .= '<input type="hidden" name="lokasi" value="'.$desa.'">';
	$output .= '<input type="hidden" name="simpan" value="'.$filter.'">';
	$output .= '<input type="hidden" name="user_id" value="'.$_SESSION['uid'].'">';
	$output .= '<div class="control-group">';
	$output .= '<label class="control-label">'.$rs['nama'].'</label>';
	$output .= '<div class="controls">';
	$output .= '<button type="submit" class="btn btn-primary" >Simpan data</button> ';
	$output .= '<a href="berkas.php?kat='.$filter.'&id='.$desa.'&thn='.$tahun.'&input=true" class="btn btn-error">Lampiran berkas</a></div>';
	$output .= '</div>';
	$output .= '</form>';
	echo $output;
	echo '<hr/>';
}

if (isset($_POST['simpan'])) {
	require SIMBIO_BASE_DIR.'simbio_DB/simbio_dbop.inc.php';
	$simpan = $_POST['simpan'];
	// walk through the id of field
	$_sql = 'SELECT idruas, nama FROM ruas';
	$_sqlset = $dbs->query($_sql);
	$_db_ops = new simbio_dbop($dbs);
	while($rs = $_sqlset->fetch_assoc()) {
		if (isset($_POST[$rs['idruas']]) and ($_POST[$rs['idruas']] <>"")) {
			$value = $_POST[$rs['idruas']];
			$data['value'] = $value;
			$data['idruas'] = $rs['idruas'];
//			$data['tahun'] = $_POST['tahun'];
//			$data['idlokasi'] = $_POST['lokasi'];
			$data['user_id'] = $_SESSION['uid'];
			$data['create_date'] = date('Y-m-d');
			$data['update_date'] = date('Y-m-d');
			if ($_db_ops->insert('main_cerita', $data)) {
				// Sukses
				$ouput = '<div class="info-box">Sukses</div>';
			} else {
				// Error
				$ouput = '<div class="info-box">Gagal</div>';
			}
		}
	}
	echo $output;
}

echo '<div class="row"><div class="span8"><form method="POST" class="form-search">';
/**
// Lokasi Desa
echo '<select name="id">';
echo '<option value="">Pilih Desa</option>';
$combo_sql = 'SELECT idlokasi, nama FROM lokasi WHERE idlokasi<>6 ';
$combo_sql .= 'ORDER BY nama ';
$combo_set = $dbs->query($combo_sql);
while($rs = $combo_set->fetch_assoc()) {
	if (isset($desa) AND $desa == $rs['idlokasi']) {
		echo '<option value="'.$rs['idlokasi'].'" selected >'.$rs['nama'].'</option>';
	} else {
		echo '<option value="'.$rs['idlokasi'].'">'.$rs['nama'].'</option>';
	}
}
echo '</select> ';
*/
echo 'Periode Tahun <input type="text" placeholder="Periode Tahun" class="input-small" name="tahun" value="';
if (isset($_POST['tahun'])) {
	echo $_POST['tahun'].'"> ';
} else {
	echo date('Y').'"> ';
}
echo '<select name="filter">';
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
echo '<button type="submit" class="btn btn-success">Tampilkan Form</button>';
echo '</form></div>';

$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
