<?php
// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// session
//require LIB_DIR.'session.inc.php';
//require LIB_DIR.'session_check.inc.php';
// check privileges

$page_title = 'Beranda Aplikasi';

ob_start();
echo '<div class="row"><div class="span12">';
?>
		<h3 align="center">
				BORANG Akreditasi Program Studi<br \>
				Ilmu Perpustakaan - FTI
		</h3><p align="center"><img class="img-rounded" src="images/yarsi.png" /></p>
		</div>
	</div>
<?php
$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
