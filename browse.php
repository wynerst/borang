<?php
// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// session
require LIB_DIR.'session.inc.php';
require LIB_DIR.'session_check.inc.php';
// check privileges

$page_title = 'Borang Program Studi';

ob_start();
echo '<div class="row"><div class="span12">';

$main_sql = 'SELECT c.*, r.* FROM main_cerita as c RIGHT JOIN ruas as r ON c.idruas = r.idruas ';
if ($main_limit <> "") {
	$main_sql .= 'WHERE '. $main_limit;
}
$main_sql .= 'ORDER BY r.b_atas';

//die($main_sql);
$query1 = $dbs->query($main_sql);

// hasil temuan query
$jumlah_hasil_temuan = $query1->num_rows;

WHILE ($data = $query1->fetch_assoc()) {
	if ($data['tipe'] > 0) {
		echo '<h4>'.$data['nama'].'</h4>';
	} else {
		echo '<h4>'.$data['nama'].'<br />'.$data['deskripsi'].'</h4>';
	}
	echo '<p><quote>'.$data['value'].'</quote></p>';
}
?>
		</div>
	</div>
<?php

$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
