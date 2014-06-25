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

if (isset($_GET['id']) AND $_GET['id']<>'' AND isset($_GET['kat']) AND $_GET['kat']<>'' AND isset($_GET['thn']) AND $_GET['thn']<>'') {
	$desa=$_GET['id'];
	$filter=$_GET['kat'];
	$tahun=$_GET['thn'];
}

if (!isset($desa) OR !isset($tahun) OR !isset($filter)) {
  $main_content = '<div class="alert alert-error">Data lokasi, waktu, dan kategori tidak lengkap!</div>';
	require './template/sosek/page_tpl.inc.php';
	exit();
}

// daftar berkas untuk kategori terkait
$_sql = 'SELECT mb.idlampiran, b.file_name, b.file_desc, b.file_title AS judul, b.mime_type AS jenis, l.nama AS lokasi, k.nama as kategori, mb.tahun, mb.user_id
    FROM files AS b
	LEFT JOIN main_berkas AS mb ON mb.idfile = b.file_id
	LEFT JOIN ruas AS k ON mb.idruas = k.idruas
	LEFT JOIN lokasi AS l ON mb.idlokasi = l.idlokasi ';
$_where = 'WHERE l.idlokasi='.$desa.' AND k.idruas='.$filter.' AND mb.tahun='.$tahun.' ';
$_berkas = $dbs->query($_sql.$_where);
echo '<table width="100%"><tr><td width="50%"><ul>';
if ($_berkas->num_rows < 1) {
	echo '<div class="alert alert-error">Tidak ada data berkas ditemukan.</div>';
}
while($rs = $_berkas->fetch_assoc()) {
	echo '<li><a href="images/'.$rs['file_name'].'" target="blank" title="Tampilkan berkas dalam jendela baru">'.$rs['judul'].'</a> - '.$rs['jenis'].' - '.$rs['file_desc'].' - '.$rs['lokasi'].' - '.$rs['kategori'].' - '.$rs['tahun'];
	if ($rs['user_id'] == $_SESSION['uid'] OR $_SESSION['uid'] == 1) {
		'  <a href="berkas.php?del='.$rs['idlampiran'].'&id='.$desa.'&thn='.$tahun.'&kat='.$filter.'"  title="Hapus data lampiran berkas"><i class="icon-remove-sign"></i></a>';
	}
	echo '</li>';
}
echo '</ul></td><td><iframe name="gambar" id="gambar" frameborder="0" scrolling="no" width="100%" height="auto"></iframe></td></tr></table>';
echo '<a href="javascript:history.back()" class="btn btn-error">Kembali</a>';

$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
