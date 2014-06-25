<?php

// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// session
// require SENAYAN_BASE_DIR.'admin/default/session_check.inc.php';
require SIMBIO_BASE_DIR.'simbio_GUI/table/simbio_table.inc.php';
require SIMBIO_BASE_DIR.'simbio_GUI/form_maker/simbio_form_table_AJAX.inc.php';
require SIMBIO_BASE_DIR.'simbio_GUI/paging/simbio_paging.inc.php';
require SIMBIO_BASE_DIR.'simbio_DB/datagrid/simbio_dbgrid.inc.php';

if (isset($_GET['id']) AND $_GET['id']<>'' AND isset($_GET['kat']) AND $_GET['kat']<>'' AND isset($_GET['thn']) AND $_GET['thn']<>'') {
	$desa=$_GET['id'];
	$filter=$_GET['kat'];
	$tahun=$_GET['thn'];
}

if (isset($_GET['file'])) {
	$file=$_GET['file'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Peta kondisi sosial ekonomi wilayah</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" />
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

<?php

if (isset($file)) {
	$_sql = 'SELECT * FROM files ';
	$_where = 'WHERE file_id='.$file;
	$_berkas = $dbs->query($_sql.$_where);
	while($rs = $_berkas->fetch_assoc()) {
		echo '<div class="alert alert-info">'.$rs['file_name'].'</div>';
        $file_ext = substr($rs['mime_type'], 0,5);
        if ($file_ext == 'image') {
			echo '<a href="images/'.$rs['file_name'].'" target="blank"><img src="images/'.$rs['file_name'].'" width="500px" height="600px" ></a><br />'.$rs['file_desc'];
		} else {
			echo '<a href="images/'.$rs['file_name'].'" target="blank">Klik disini untuk menampilkan berkas '.$rs['mime_type'].'</a><br />'.$rs['file_desc'];
		}
	}
	echo '<br /><a href="javascript:history.back()">Daftar berkas</a>';

} else {

	if (!isset($desa) OR !isset($tahun) OR !isset($filter)) {
	  echo '<div class="alert alert-error">Data lokasi, waktu, dan kategori tidak lengkap!</div>';
		exit();
	}

	// daftar berkas untuk kategori terkait
	$_sql = 'SELECT mb.idlampiran, b.file_id, b.file_name, b.file_desc, b.file_title AS judul, b.mime_type AS jenis, l.nama AS lokasi,
	    k.nama as kategori, mb.tahun
		FROM files AS b
		LEFT JOIN main_berkas AS mb ON mb.idfile = b.file_id
		LEFT JOIN ruas AS k ON mb.idruas = k.idruas
		LEFT JOIN lokasi AS l ON mb.idlokasi = l.idlokasi ';
	$_where = 'WHERE l.idlokasi='.$desa.' AND k.idruas='.$filter.' AND mb.tahun='.$tahun.' ';
	$_berkas = $dbs->query($_sql.$_where);
	echo '<div class="alert alert-info">Berkas terkait:</div><table class="table table-hover" width="100%">';
	while($rs = $_berkas->fetch_assoc()) {
		echo '<tr><td><a href="view.php?file='.$rs['file_id'].'" >'.$rs['judul'].'</a> - '.$rs['file_desc'].' - '.$rs['lokasi'].' - '.$rs['kategori'].' - '.$rs['tahun'].'</td></tr>';
	}
	echo '</table>';
	echo '<br /><a href="javascript:history.back()">Profil desa '.$rs['lokasi'].'</a>';

}

echo '</body></html>';

