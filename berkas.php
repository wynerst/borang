<?php
// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// session
require LIB_DIR.'session.inc.php';
require LIB_DIR.'session_check.inc.php';
// check privileges
if (!utility::havePrivilege('berkas', 'r')) {
  $main_content = '<div class="alert alert-error">Anda tidak memiliki hak untuk masuk ke bagian ini</div>';
	require './template/sosek/page_tpl.inc.php';
	exit();
}

// require SENAYAN_BASE_DIR.'admin/default/session_check.inc.php';
require SIMBIO_BASE_DIR.'simbio_GUI/table/simbio_table.inc.php';
require SIMBIO_BASE_DIR.'simbio_GUI/form_maker/simbio_form_table_AJAX.inc.php';
require SIMBIO_BASE_DIR.'simbio_GUI/paging/simbio_paging.inc.php';
require SIMBIO_BASE_DIR.'simbio_DB/datagrid/simbio_dbgrid.inc.php';
require SIMBIO_BASE_DIR.'simbio_DB/simbio_dbop.inc.php';
require SIMBIO_BASE_DIR.'simbio_UTILS/simbio_date.inc.php';
require SIMBIO_BASE_DIR.'simbio_FILE/simbio_file_upload.inc.php';

$page_title ='Berkas lampiran - '.$page_title;

ob_start();
// Uploading file
if (!empty($_FILES['image']) AND $_FILES['image']['size']) {
	// create upload object
	$image_upload = new simbio_file_upload();
	$image_upload->setAllowableFormat($sysconf['allowed_images']);
	$image_upload->setMaxSize($sysconf['max_image_upload']*1024);
	$image_upload->setUploadDir(IMAGES_BASE_DIR);
	// upload the file and change all space characters to underscore
	$img_upload_status = $image_upload->doUpload('image', preg_replace('@\s+@i', '_', $_FILES['image']['name']));
	if ($img_upload_status === UPLOAD_SUCCESS) {
		$data['image'] = $dbs->escape_string($image_upload->new_filename);
		// write log
		//utility::writeLogs($dbs, 'staff', $_SESSION['uid'], 'bibliography', $_SESSION['realname'].' upload image file '.$image_upload->new_filename);
		echo '<div class="alert alert-success">File berhasil di-upload';
        $file_ext = strtolower(substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], '.')+1));
		$sql_op = new simbio_dbop($dbs);
		// insert new file data
		$_berkas['file_title'] = $_POST['title'];
		$_berkas['file_desc'] = $_POST['description'];
		$_berkas['file_name'] = preg_replace('@\s+@i', '_', $_FILES['image']['name']);
        $_berkas['mime_type'] = $sysconf['mimetype'][$file_ext];
		$_berkas['uploader_id'] = $_SESSION['uid'];
		$_berkas['input_date'] = date("Y-m-d");
		$_berkas['last_update'] = date("Y-m-d");
		$insert = $sql_op->insert('files',$_berkas);
		if ($insert) {
			$_idx_berkas['idfile'] = $sql_op->insert_id;
//			$_idx_berkas['idlokasi'] = $_POST['desa'];
			$_idx_berkas['idruas'] = $_POST['filter'];
			$_idx_berkas['user_id'] = $_SESSION['uid'];
			$_idx_berkas['tahun'] = $_POST['tahun'];
			$_idx_berkas['create_date'] = date("Y-m-d");
			$_idx_berkas['update_date'] = date("Y-m-d");
/**				echo '<br />'.$_idx_berkas['idfile'];
				echo '<br />'.$_idx_berkas['idlokasi'];
				echo '<br />'.$_idx_berkas['tahun'];
				echo '<br />'.$_idx_berkas['idruas'];
				echo '<br />'.$_idx_berkas['create_date']; */
			$idx_insert = $sql_op->insert('main_berkas',$_idx_berkas);
			if ($idx_insert) {
				echo '<br />File berhasil di tambahkan</div>';
			} else {
				echo '<br />Data File gagal di tambahkan</div>';
			}
		} else {
			echo '<br />MetaData File gagal di tambahkan ['.$sql_op->error().']</div>';
		}

	} else {
		// write log
		//utility::writeLogs($dbs, 'staff', $_SESSION['uid'], 'bibliography', 'ERROR : '.$_SESSION['realname'].' FAILED TO upload image file '.$image_upload->new_filename.', with error ('.$image_upload->error.')');
		echo '<div class="alert alert-error">File GAGAL di-upload! '.$image_upload->error.'</div>';
	}
}

if (isset($_GET['kat']) AND $_GET['kat']<>'' AND isset($_GET['thn']) AND $_GET['thn']<>'') {
	$desa=999;
	$filter=$_GET['kat'];
	$tahun=$_GET['thn'];
}

if (isset($_GET['input']) AND $_GET['input']=='true') {
	$disabled = '';
} else {
	$disabled = 'disabled';
}

if (isset($_POST['id']) AND isset($_POST['tahun']) AND isset($_POST['filter'])) {
	$desa=$_POST['id'];
	$tahun=$_POST['tahun'];
	$filter=$_POST['filter'];
}

if (isset($_GET['del']) and $_GET['del'] <>"") {
    $sql_op = new simbio_dbop($dbs);
	$id = (integer)$_GET['del'];
	$update = $sql_op->delete('main_berkas', 'idlampiran='. $id);
	if ($update) {
		utility::jsAlert('Data berkas Berhasil dihapus!');
	} else {
		utility::jsAlert('Gagal mengapus data Berkas!');
	}
}

if (!isset($tahun) OR !isset($filter)) {
  $main_content = '<div class="alert alert-error">Data periode dan Standar tidak lengkap!</div>';
	require './template/sosek/page_tpl.inc.php';
	exit();
}

echo '<div class="row"><div class="span8"><form method="POST" action="berkas.php" class="form-search">';
// Lokasi Desa
echo 'Periode ';
/** echo '<select name="id" disabled>';
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

echo '<input type="text" placeholder="Periode Tahun" class="input-small" name="tahun" disabled value="';
if (isset($tahun)) {
	echo $tahun.'"> ';
} else {
	echo date("Y").'"> ';
}

if (isset($_POST['id']) AND isset($_POST['tahun'])) {
//if (isset($_POST['id']) AND isset($_POST['tahun']) AND isset($_POST['filter'])) {
	echo '<input type="hidden" name="desa" value="'.$desa.'">';
	echo '<input type="hidden" name="tahun" value="'.$tahun.'">';
	//echo '<input type="hidden" name="filter" value="'.$filter.'">';
} else {
	if (isset($desa) AND isset($tahun)) {
	//if (isset($desa) AND isset($tahun) AND isset($filter)) {
		echo '<input type="hidden" name="desa" value="'.$desa.'">';
		echo '<input type="hidden" name="tahun" value="'.$tahun.'">';
		//echo '<input type="hidden" name="filter" value="'.$filter.'">';
	}
}

//echo '<button type="submit" class="btn btn-success" >Tampilkan Form</button>';
echo '</form></div>';
echo '</div>';

// daftar berkas untuk kategori terkait
$_sql = 'SELECT mb.idlampiran, b.file_name, b.file_desc, b.file_title AS judul, b.mime_type AS jenis, k.nama as kategori, mb.tahun, mb.user_id
    FROM files AS b
	LEFT JOIN main_berkas AS mb ON mb.idfile = b.file_id
	LEFT JOIN ruas AS k ON mb.idruas = k.idruas ';
$_where = 'WHERE k.idruas='.$filter.' AND mb.tahun='.$tahun.' ';
$_berkas = $dbs->query($_sql.$_where);
echo '<form method="POST" enctype="multipart/form-data" class="form-search">';
echo '<select name="filter" '.$disabled.' >';
if ($disabled =='') {
	$combo_parent_sql = 'SELECT b_atas, b_bawah FROM ruas WHERE idruas ='.$filter;
    $combo_parent_rs = $dbs->query($combo_parent_sql);
    $combo_parent_data = $combo_parent_rs->fetch_array();

	$combo_sql = 'SELECT idruas, nama, tingkat FROM ruas WHERE b_atas >='.$combo_parent_data['b_atas'].' AND b_bawah <='.$combo_parent_data['b_bawah'];
} else {
	$combo_sql = 'SELECT idruas, nama tingkat FROM ruas ORDER BY b_atas, b_bawah';
}
//$combo_sql = 'SELECT idruas, nama FROM ruas WHERE idkel_ruas = 0 ';
//$combo_sql .= ' ORDER BY nama ';
$combo_set = $dbs->query($combo_sql);
while($rs = $combo_set->fetch_assoc()) {
	if (isset($filter) AND $filter == $rs['idruas']) {
		echo '<option value="'.$rs['idruas'].'" selected >'.str_repeat("&nbsp;",$rs['tingkat'])."+ ".$rs['nama'].'</option>';
	} else {
		echo '<option value="'.$rs['idruas'].'">'.str_repeat("&nbsp;",$rs['tingkat'])."+ ".$rs['nama'].'</option>';
	}
}
echo '</select> ';
echo '<input type="text" name="title" id="title" placeholder="Judul berkas" value="" /> &nbsp;&nbsp;';
echo '<input type="text" name="description" id="description" placeholder="Deskripsi/caption berkas" class="input-large" value="" /> &nbsp;&nbsp;';
echo '<input type="file" name="image" id="image" value="" /> &nbsp;&nbsp;';
echo '<input type="submit" name="simpanBerkas" class="btn btn-success" value="Unggah berkas baru">&nbsp;&nbsp;Maximum '.$sysconf['max_image_upload'].' KB';

	if (isset($desa) AND isset($tahun)) {
//	if (isset($desa) AND isset($tahun) AND isset($filter)) {
		echo '<input type="hidden" name="desa" value="'.$desa.'">';
		echo '<input type="hidden" name="tahun" value="'.$tahun.'">';
//		echo '<input type="hidden" name="filter" value="'.$filter.'">';
	}

echo '</form>';
echo '<table width="100%"><tr><td width="50%"><ul>';
while($rs = $_berkas->fetch_assoc()) {
	echo '<li><a href="images/'.$rs['file_name'].'" target="blank" title="Tampilkan berkas dalam jendela baru">'.$rs['judul'].'</a> - '.$rs['jenis'].' - '.$rs['file_desc'].' - '.$rs['lokasi'].' - '.$rs['kategori'].' - '.$rs['tahun'];
	if ($rs['user_id'] == $_SESSION['uid'] OR $_SESSION['uid'] == 1) {
		'  <a href="berkas.php?del='.$rs['idlampiran'].'&id='.$desa.'&thn='.$tahun.'&kat='.$filter.'"  title="Hapus data lampiran berkas"><i class="icon-remove-sign"></i></a>';
	}
	echo '</li>';
}
echo '</ul></td><td><iframe name="gambar" id="gambar" frameborder="0" scrolling="no" width="100%" height="auto"></iframe></td></tr></table>';
$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
