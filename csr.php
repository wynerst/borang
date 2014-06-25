<?php
// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';

ob_start();
if (isset($_GET['p'])) {
  include $_GET['p'].'.php';
} else {
  include 'ptba_csr.php';
}
$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
