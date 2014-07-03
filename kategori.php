<?php
// key to authenticate
define('INDEX_AUTH', '1');
require 'sysconfig.inc.php';
require SIMBIO_BASE_DIR.'simbio_DB/simbio_dbop.inc.php';

// session
require LIB_DIR.'session.inc.php';
require LIB_DIR.'session_check.inc.php';
// check privileges
if (!utility::havePrivilege('add', 'w')) {
  $main_content = '<div class="alert alert-error">Anda tidak memiliki hak untuk masuk ke bagian ini</div>';
	require './template/sosek/page_tpl.inc.php';
	exit();
}

$page_title = 'Kategori ruas data';

ob_start();

if (isset($_GET['id']) AND $_GET['id']<> "") {
	$_sql = 'SELECT * FROM ruas WHERE idruas ='.$_GET['id'];
	$edit_set = $dbs->query($_sql);
	while($rs = $edit_set->fetch_assoc()) {
		$idruas = $rs['idruas'];
		$nama = $rs['nama'];
		$idkel_ruas = $rs['idkel_ruas'];
		$deskripsi = $rs['deskripsi'];
	}
} else {
	$idruas = '';
	$nama = '';
	$idkel_ruas = '';
	$deskripsi = '';
}

if (isset($_GET['del']) AND $_GET['del']<> "") {
    $sql_op = new simbio_dbop($dbs);
	$delete = $sql_op->delete('ruas', 'idruas='.$_GET['del']);
	if ($delete) {
        utility::jsAlert('Kategori sukses dihapus!');
	} else {
        utility::jsAlert('Kategori Gagal Dihapus!');
	}

}

if (isset($_POST['nama']) and $_POST['nama']<>"") {
	$data['nama'] = $_POST['nama'];
	$data['deskripsi'] = $_POST['deskripsi'];
	if (isset($_POST['utama']) and $_POST['utama']<>"") {
		$data['idkel_ruas'] = 0;
	} else {
		$data['idkel_ruas'] = $_POST['idkel_ruas'];
	}
	$parent_sql = 'SELECT * FROM ruas WHERE idruas='.$data['idkel_ruas'] ;
//  $parent_sql = 'SELECT * FROM ruas WHERE idruas='.$data['idkel_ruas'] .' OR idruas='.$data['idkel_ruas'] .' ORDER BY b_atas DESC LIMIT 0,1';
	$parent_rs = $dbs->query($parent_sql);
	$parent_data = $parent_rs->fetch_array();

	$data['tingkat'] = $parent_data['tingkat'] +1;
	$data['b_atas'] = $parent_data['b_bawah'];
	$data['b_bawah'] =  $parent_data['b_bawah'] +1;

	$data['create_date'] = date('Y-m-d');
	$data['update_date'] = date('Y-m-d');

    $sql_op = new simbio_dbop($dbs);
    if (isset($_POST['idruas']) AND $_POST['idruas'] <> "") {
		unset($data['tingkat']);
		unset($data['b_atas']);
		unset($data['b_bawah']);
		$update = $sql_op->update('ruas', $data, 'idruas='.$_POST['idruas']);
		if ($update) {
			utility::jsAlert('Sukses Diubah!');
		} else {
			utility::jsAlert('Update Gagal!');
		}
	} else {
		$update = $dbs->query('UPDATE `ruas` SET `b_atas`=`b_atas`+3,`b_bawah`=`b_bawah`+3 WHERE `b_atas` >='.$data['b_atas'].' AND `b_bawah` >='.$data['b_atas']);
//		$update = $dbs->query('UPDATE `ruas` SET `b_atas`=`b_atas`+2,`b_bawah`=`b_bawah`+3 WHERE `b_atas` >'.$data['b_atas'].' AND `b_bawah` >='.$data['b_atas']);
//		$update = $dbs->query('UPDATE `ruas` SET `b_bawah`=`b_bawah`+2 WHERE `b_bawah` >='.$data['b_atas']);
		$insert = $sql_op->insert('ruas', $data);
		if ($insert) {
			utility::jsAlert('Sukses Ditambahkan!');
		} else {
			utility::jsAlert('Update penambahan ruas GAGAL!');
		}
	}
}

echo '<form class="form-horizontal" method="POST" action="kategori.php">';
echo '<select name="idkel_ruas">';
echo '<option value="">Bagian dari ruas</option>';

//$combo_sql = 'SELECT idruas, nama FROM ruas WHERE idkel_ruas = 0 ';
$combo_sql = 'SELECT * FROM ruas ';
$combo_sql .= 'ORDER BY b_atas,b_bawah ';
$combo_set = $dbs->query($combo_sql);
while($rs = $combo_set->fetch_assoc()) {
	if (isset($idkel_ruas) AND $rs['idruas'] == $idkel_ruas) {
		echo '<option value="'.$rs['idruas'].'" selected>'.str_repeat("&nbsp;",$rs['tingkat'])."+ ".$rs['nama'].'</option>';
	} else {
		echo '<option value="'.$rs['idruas'].'">'.str_repeat("&nbsp;",$rs['tingkat'])."+ ".$rs['nama'].'</option>';
	}
}
echo '</select> ';
echo '<input type="text" placeholder="Nama ruas" class="input-xlarge" name="nama" value="'.$nama.'"> ';
echo '<input type="text" placeholder="Deskripsi" class="input-xxlarge" name="deskripsi" value="'.$deskripsi.'"> ';
if (isset($idruas) AND $idruas<>'') {
	echo '<input type="hidden" name="idruas" value="'.$idruas.'"> ';
} else {
	echo '<input type="checkbox" name="utama" class="checkbox"> Ruas utama ';
}
echo '<button type="submit" class="btn btn-primary" value="Simpan" >Simpan data</button>';
echo '</form>';

//Maksimum tingkat
$max_sql = 'SELECT max(`tingkat`) FROM ruas';
$max_set =  $dbs->query($max_sql);
$max_level = $max_set->fetch_row();
// data ruas
$top_sql = 'SELECT * FROM ruas ORDER BY b_atas, b_bawah';
$top_set = $dbs->query($top_sql);
$result = '<table class="table-striped" width="90%" >';
while($rs = $top_set->fetch_assoc()) {
/**		$result .= '<tr>';
		$result .= '<td width="5%"><a href="kategori.php?id='.$rs['idruas'].'" title="Ubah kategori: '.$rs['nama'].'"><i class="icon-edit"></i></a>';
		$result .= ' <a href="kategori.php?del='.$rs['idruas'].'" title="Hapus kategori: '.$rs['nama'].'"><i class="icon-remove-sign"></i></a></td>';
		$result .= '<td>&nbsp;</td><td>'.str_repeat("&nbsp;",$rs['tingkat']).$rs['nama']."&nbsp;--&nbsp;".$rs['deskripsi'].'</td>';
		$result .= '</tr>';
**/
		$result .= "<tr>\n";
		for ($i=0; $i<= $max_level[0]; $i++) {
			if ($rs['tingkat'] <> $i) {
				$result .= "<td>&nbsp;</td>\n";
			} else {
				$span = $max_level[0]+1-$i;
				$result .= '<td width="4%" align="center"><a href="kategori.php?id='.$rs['idruas'].'" title="Ubah kategori: '.$rs['nama'].'"><i class="icon-edit"></i></a>';
				$result .= '<a href="kategori.php?del='.$rs['idruas'].'" title="Hapus kategori: '.$rs['nama'].'"><i class="icon-remove-sign"></i></a></td>';
				$result .= '<td colspan="'.$span.'">'.$rs['nama']."&nbsp;--&nbsp;".$rs['deskripsi'].'</td>';
				break;
			}
		}
		$result .= "</tr>\n";

}
$result .='</table>';
echo $result;

$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
