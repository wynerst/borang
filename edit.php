<?php
// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// session
require LIB_DIR.'session.inc.php';
require LIB_DIR.'session_check.inc.php';
// check privileges
if (!utility::havePrivilege('edit', 'w')) {
  $main_content = '<div class="alert alert-error">Anda tidak memiliki hak untuk masuk ke bagian ini</div>';
	require './template/sosek/page_tpl.inc.php';
	exit();
}
$page_title = 'Data Editor';

require SIMBIO_BASE_DIR.'simbio_DB/simbio_dbop.inc.php';

if (isset($_POST['editid'])) {
    $sql_op = new simbio_dbop($dbs);
	$id = (integer)$_POST['editid'];
	$data['value']=trim($dbs->escape_string(strip_tags($_POST['value'])));
	$data['update_date']=date('Y-m-d');
	if ($_SESSION['uid'] == 1) {
		$data['published'] = 1;
	} else {
		$data['published'] = 1;
	}
	$update = $sql_op->update('main_cerita', $data, 'idmain_cerita='. $id);
	if ($update) {
		utility::jsAlert('Sukses Update!');
	} else {
		utility::jsAlert('Update Gagal!');
	}
}

if (isset($_GET['del'])) {
    $sql_op = new simbio_dbop($dbs);
	$id = (integer)$_GET['del'];
	if ($rs['user_id'] > 1 AND $rs['user_id']<>$_SESSION['uid']) {
		$main_content = '<div class="alert alert-error">Anda tidak memiliki hak untuk masuk ke bagian ini</div>';
		require './template/sosek/page_tpl.inc.php';
		exit();
	} else {
		$update = $sql_op->delete('main_cerita', 'idmain_cerita='. $id);
		if ($update) {
			utility::jsAlert('Berhasil dihapus!');
		} else {
			utility::jsAlert('Gagal mengapus!');
		}
	}
}

if (isset($_GET['id']) OR isset($update)) {
	if (isset($update)) {
		$edit = $id;
	} else {
		$edit = $_GET['id'];
	}
	$main_sql = 'SELECT c.*, r.*, r2.idruas AS idkelas, r2.nama AS kelas, l.idlokasi, l.nama AS desa
    FROM main_cerita as c LEFT JOIN ruas as r ON c.idruas = r.idruas
		LEFT JOIN lokasi AS l on c.idlokasi = l.idlokasi
		LEFT JOIN ruas as r2 ON r.idkel_ruas = r2.idruas
		WHERE c.idmain_cerita ='.$edit;
	$main_rs = $dbs->query($main_sql);
	$rs= $main_rs->fetch_assoc();
	if ($_SESSION['uid'] > 1 AND $rs['user_id']<>$_SESSION['uid']) {
		$disable = 'disabled';
	} else {
		$disable = '';
	}
}

echo '<form action="edit.php" class="form-horizontal" method="POST">';
echo '<legend>Deskripsi/Cerita</legend>';

echo '<div class="control-group">';
echo '<label class="control-label">Kategori</label>';
echo '<div class="controls"><input type="text" class="input-medium" disabled value="'.$rs['kelas'].'"></div>';
echo '</div>';
echo '<div class="control-group">';
echo '<label class="control-label">Desa</label>';
echo '<div class="controls"><input type="text" class="input-medium" disabled value="'.$rs['desa'].'"></div>';
echo '</div>';
echo '<div class="control-group">';
echo '<label class="control-label">Tahun</label>';
echo '<div class="controls"><input type="text" class="input-small" disabled value="'.$rs['tahun'].'"></div>';
echo '</div>';
echo '<div class="control-group">';
echo '<label class="control-label">Deskripsi:<br />'. strtoupper($rs['nama']).'</label>';
echo '<div class="controls"><textarea rows="4" class="input-block-level" name="value" '.$disable.'>'.$rs['value'].'</textarea></div>';
echo '</div>';
echo '<input type="hidden" name="editid" value="'.$edit.'">';
echo '<div class="control-group">';
echo '<label class="control-label">&nbsp;</label>';
echo '<div class="controls">';
echo '<button type="submit" class="btn btn-primary" value="Simpan" '.$disable.'>Simpan data</button> ';
echo '<a href="index.php?id='.$rs['idlokasi'].'&thn='.$rs['tahun'].'&filter='.$rs['idkelas'].'" class="btn btn-warning" >Kembali</a>';
echo '</div>';
echo '</div>';

echo '</form>';

$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
