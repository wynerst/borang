<?php
echo '
<div id="banner" style="background:url(images/banner.jpg);">
    	<!--img src="assets/img/content/banner-csr.jpg" alt="" /-->
		<div id="bannertxt">
        	<h1>
                 <span> CORPORATE SOCIAL RESPONSIBILITY </span>
               About CSR
            </h1>
		</div>
</div>
<div id="container">
    <div id="content">
        	<h3 class="big">Corporate Social Responsibility</h3>';

$top_sql = 'SELECT * FROM ruas WHERE idkel_ruas = 0';
$top_set = $dbs->query($top_sql);
$result = '<table width="100%" >';
while($rs = $top_set->fetch_assoc()) {
	$result .= '<tr><td>'.$rs['nama'].'</td></tr>';
	$child_sql = 'SELECT * FROM ruas WHERE idkel_ruas = '.$rs['idruas'];
	$child_set = $dbs->query($child_sql);
	while($rs_child = $child_set->fetch_assoc()) {
		$result .= '<tr><td>&nbsp;</td><td>'.$rs_child['nama'].'</td></tr>';
	}
}

$result .='</table>';
echo $result;
echo '</div>
        <div id="menu">
        	            <ul>
                <li class="opened">
                    <a class="active sub" href="#">CSR Report and Journal</a>
                	<ul style="display: block;">
                        <li class="opened"><a href="csr.php">About CSR</a></li>
                        <li><a href="http://ptba.co.id/en/csr/download">Sustainable Report</a></li>
                        <li><a href="http://ptba.co.id/en/csr/bulletin">CSR Bulletin</a></li>
						<li><a href="http://ptba.co.id/en/csr/newsActivities">CSR News &amp; Activities</a></li>
                        <li><a class="active" href="csr.php?p=ruas">Kategori data</a></li>
                        <li><a  href="sosek.php">Profil desa</a></li>
                    </ul>
                </li>
            </ul>        </div>
</div>';

