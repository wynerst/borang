<?php

// key to authenticate
define('INDEX_AUTH', '1');

// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// session
require LIB_DIR.'session.inc.php';

$page_title='Profil Desa';

// start output buffer
ob_start();

echo '<script type="text/javascript">
function mapOnMouseOver(str){document.getElementById("mousemovemessage").innerHTML="Profil desa "+str; }
function mapOnMouseOut(str){document.getElementById("mousemovemessage").innerHTML="out of "+str; }
function mapOnClick(str){alert(str);}
</script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>';

echo '<div class="row"><div class="span8"><h4>Desa-desa lokasi survey PT Bukit Asam</h4></div>
    </div>
<table>
<tr><td><div class="alert alert-info" id="mousemovemessage"></div><img src="sosek.png" border="0" ismap="ismap" usemap="#mapmap" alt="html imagemap created with QGIS" ></td>
<td>
<iframe
name="display"
width="500"
height="600"
src="profil.php"
frameborder="no"
scrolling="yes">
</iframe>
</td></tr></table>';

echo '<map name="mapmap">
<area shape="poly" href="profil.php?desa=Gedung Agung" target="display" onMouseOver="mapOnMouseOver(\'Gedung Agung\')"  coords="312,158,314,156,317,154,319,154,322,153,325,155,329,158,331,164,330,170,325,175,318,176,311,172,310,165,312,158" alt="Gedung Agung">
<area shape="poly" href="profil.php?desa=Gedung Agung" target="display" onMouseOver="mapOnMouseOver(\'Gedung Agung\')"  coords="28,64,178,61,179,61,180,85,29,88,28,64" alt="Gedung Agung">
<area shape="poly" href="profil.php?desa=Arahan" target="display" onMouseOver="mapOnMouseOver(\'Arahan\')"  coords="93,99,180,98,180,112,93,112,93,99" alt="Arahan">
<area shape="poly" href="profil.php?desa=Arahan" target="display" onMouseOver="mapOnMouseOver(\'Arahan\')"  coords="322,237,321,238,319,235,317,232,319,226,322,223,327,222,332,225,333,226,334,233,333,237,327,237,322,237" alt="Arahan">
<area shape="poly" href="profil.php?desa=Banjar Sari" target="display" onMouseOver="mapOnMouseOver(\'Banjarsari\')"  coords="59,134,178,134,179,152,59,152,59,134" alt="Banjarsari">
<area shape="poly" href="profil.php?desa=Banjar Sari" target="display" onMouseOver="mapOnMouseOver(\'Banjarsari\')"  coords="318,243,322,245,325,246,327,253,323,258,317,260,313,258,308,255,309,248,314,244,318,243" alt="Banjarsari">
<area shape="poly" href="profil.php?desa=Prabu Menang" target="display" onMouseOver="mapOnMouseOver(\'Perabu Menang\')"  coords="300,293,298,292,295,292,293,291,292,289,291,284,293,282,296,280,298,278,301,278,304,279,307,281,308,285,307,289,304,292,300,293" alt="Perabu Menang">
<area shape="poly" href="profil.php?desa=Prabu Menang" target="display" onMouseOver="mapOnMouseOver(\'Perabu Menang\')"  coords="18,173,177,171,177,190,19,190,18,173" alt="Perabu Menang">
<area shape="poly" href="profil.php?desa=Gunung Kembang" target="display" onMouseOver="mapOnMouseOver(\'Gunung Kembang\')"  coords="4,211,179,211,177,212,178,231,4,229,4,211" alt="Gunung Kembang">
<area shape="poly" href="profil.php?desa=Gunung Kembang" target="display" onMouseOver="mapOnMouseOver(\'Gunung Kembang\')"  coords="291,296,299,299,302,301,303,306,301,310,298,313,292,314,287,314,284,312,283,305,284,302,288,298,290,298,291,296" alt="Gunung Kembang">
<area shape="poly" href="profil.php?desa=Sirah Pulau" target="display" onMouseOver="mapOnMouseOver(\'Sirah Pulau\')"  coords="49,253,175,254,175,270,49,269,49,253" alt="Sirah Pulau">
<area shape="poly" href="profil.php?desa=Sirah Pulau" target="display" onMouseOver="mapOnMouseOver(\'Sirah Pulau\')"  coords="307,333,310,336,312,340,311,343,308,347,302,348,297,346,296,341,298,337,302,333,307,333" alt="Sirah Pulau">
<area shape="poly" href="profil.php?desa=Merapi" target="display" onMouseOver="mapOnMouseOver(\'Merapi\')"  coords="100,290,174,290,174,307,100,307,100,290" alt="Merapi">
<area shape="poly" href="profil.php?desa=Merapi" target="display" onMouseOver="mapOnMouseOver(\'Merapi\')"  coords="294,362,290,365,292,365,288,367,281,365,279,362,279,359,280,355,282,352,285,351,290,352,293,354,294,357,294,362" alt="Merapi">
<area shape="poly" href="profil.php?desa=Muara Maung" target="display" onMouseOver="mapOnMouseOver(\'Muara Maung\')"  coords="34,326,171,326,172,344,35,344,34,326" alt="Muara Maung">
<area shape="poly" href="profil.php?desa=Muara Maung" target="display" onMouseOver="mapOnMouseOver(\'Muara Maung\')"  coords="235,392,232,390,232,384,236,379,242,378,246,381,250,387,245,393,241,394,238,394,235,392" alt="Muara Maung">
<area shape="poly" href="profil.php?desa=Telatang" target="display" onMouseOver="mapOnMouseOver(\'Telatang\')"  coords="96,364,175,364,175,379,96,379,96,364" alt="Telatang">
<area shape="poly" href="profil.php?desa=Telatang" target="display" onMouseOver="mapOnMouseOver(\'Telatang\')"  coords="230,411,227,412,226,413,219,412,217,409,217,404,219,400,223,398,228,397,231,398,233,402,235,406,232,409,230,411" alt="Telatang">
<area shape="poly" href="profil.php?desa=Tanjung Lalang" target="display" onMouseOver="mapOnMouseOver(\'Tanjung Lalalng\')"  coords="553,579,550,579,545,575,544,568,550,562,556,562,559,564,562,571,559,577,553,579" alt="Tanjung Lalalng">
<area shape="poly" href="profil.php?desa=Tanjung Lalang" target="display" onMouseOver="mapOnMouseOver(\'Tanjung Lalang\')"  coords="324,569,491,569,491,586,324,585,324,569" alt="Tanjung Lalang">
<area shape="poly" href="profil.php?desa=Pulau Panggung" target="display" onMouseOver="mapOnMouseOver(\'Pulau Panggung\')"  coords="534,548,531,544,530,540,532,535,534,533,540,535,545,537,544,544,539,548,534,548" alt="Pulau Panggung">
<area shape="poly" href="profil.php?desa=Pulau Panggung" target="display" onMouseOver="mapOnMouseOver(\'Pulau Panggung\')"  coords="322,521,490,520,490,537,322,537,322,521" alt="Pulau Panggung">
<area shape="poly" href="profil.php?desa=Darmo" target="display" onMouseOver="mapOnMouseOver(\'Darmo\')"  coords="587,497,588,498,589,500,589,506,587,508,582,511,577,509,573,507,573,502,576,495,579,493,583,494,587,497" alt="Darmo">
<area shape="poly" href="profil.php?desa=Darmo" target="display" onMouseOver="mapOnMouseOver(\'Darmo\')"  coords="636,480,708,481,708,500,636,499,636,480" alt="Darmo">
<area shape="poly" href="profil.php?desa=Keban Agung" target="display" onMouseOver="mapOnMouseOver(\'Keban Agung\')"  coords="636,449,770,449,771,469,636,469,636,449" alt="Keban Agung">
<area shape="poly" href="profil.php?desa=Keban Agung" target="display" onMouseOver="mapOnMouseOver(\'Keban Agung\')"  coords="554,465,559,462,566,463,570,468,567,476,563,481,555,477,552,470,554,465" alt="Keban Agung">
<area shape="poly" href="profil.php?desa=Tanjung Enim Selatan" target="display" onMouseOver="mapOnMouseOver(\'Tanjung Enim Selatan\')"  coords="551,460,546,456,328,455,327,431,546,431,547,444,557,444,560,447,561,452,558,459,551,460" alt="Tanjung Enim Selatan">
<area shape="poly" href="profil.php?desa=Pasar Tanjung Enim" target="display" onMouseOver="mapOnMouseOver(\'Pasar Tanjung Enim\')"  coords="320,375,520,375,520,394,320,393,320,375" alt="Pasar Tanjung Enim">
<area shape="poly" href="profil.php?desa=Pasar Tanjung Enim" target="display" onMouseOver="mapOnMouseOver(\'Pasar Tanjung Enim\')"  coords="558,370,561,368,564,368,567,370,569,373,568,379,565,381,562,382,558,381,556,376,556,372,558,370" alt="Pasar Tanjung Enim">
<area shape="poly" href="profil.php?desa=Tegal Rejo" target="display" onMouseOver="mapOnMouseOver(\'Tegalrejo\')"  coords="593,381,593,377,596,372,603,372,606,376,604,384,601,387,595,385,593,381" alt="Tegalrejo">
<area shape="poly" href="profil.php?desa=Tegal Rejo" target="display" onMouseOver="mapOnMouseOver(\'Tegalrejo\')"  coords="613,344,727,344,727,363,614,363,613,344" alt="Tegalrejo">
<area shape="poly" href="profil.php?desa=Tanjung Enim" target="display" onMouseOver="mapOnMouseOver(\'Desa Tanjung Enim\')"  coords="611,385,796,385,796,404,611,405,611,385" alt="Desa Tanjung Enim">
<area shape="poly" href="profil.php?desa=Lingga" target="display" onMouseOver="mapOnMouseOver(\'Lingga\')"  coords="568,363,567,362,565,360,565,357,566,353,568,351,571,351,575,351,577,352,579,355,579,359,577,362,576,364,573,365,570,365,568,363" alt="Lingga">
<area shape="poly" href="profil.php?desa=Lingga" target="display" onMouseOver="mapOnMouseOver(\'Lingga\')"  coords="601,318,676,318,676,335,601,335,601,318" alt="Lingga">
<area shape="poly" href="profil.php?desa=Tanjung Raja" target="display" onMouseOver="mapOnMouseOver(\'Tanjung Raja\')"  coords="568,287,569,285,570,283,572,280,575,280,579,280,581,282,582,284,583,288,582,290,581,292,578,294,575,294,572,294,570,292,568,287" alt="Tanjung Raja">
<area shape="poly" href="profil.php?desa=Tanjung Raja" target="display" onMouseOver="mapOnMouseOver(\'Tanjung Raja\')"  coords="640,247,780,247,780,266,641,266,640,247" alt="Tanjung Raja">
<area shape="poly" href="profil.php?desa=Karang Raja" target="display" onMouseOver="mapOnMouseOver(\'Karang Raja\')"  coords="551,250,555,247,560,246,563,249,565,254,563,258,559,261,554,262,550,257,550,253,551,250" alt="Karang Raja">
<area shape="poly" href="profil.php?desa=Karang Raja" target="display" onMouseOver="mapOnMouseOver(\'Karang Raja\')"  coords="641,186,774,187,775,206,641,206,641,186" alt="Karang Raja">
<!--
<area shape="poly" href="profil.php?desa=Tanjung Lontar" target="display" onMouseOver="mapOnMouseOver(\'Tanjung Lontar\')"  coords="336,175,342,173,346,176,347,179,347,183,344,186,339,188,336,185,334,183,334,178,336,175" alt="Tanjung Lontar">
<area shape="poly" href="profil.php?desa=Tanjung Lontar" target="display" onMouseOver="mapOnMouseOver(\'Tanjung Lontar\')"  coords="360,206,456,207,458,211,460,213,468,212,471,211,473,207,494,207,498,210,508,210,509,206,523,206,523,224,360,222,360,206" alt="Tanjung Lontar">
-->
<area shape="poly" href="profil.php?desa=Lebuay Bandung" target="display" onMouseOver="mapOnMouseOver(\'Lebuay Bandung\')"  coords="464,210,461,209,458,205,459,199,461,198,467,199,470,201,471,207,468,209,464,210" alt="Lebuay Bandung">
<area shape="poly" href="profil.php?desa=Lebuay Bandung" target="display" onMouseOver="mapOnMouseOver(\'Lebuay Bandung\')"  coords="465,111,600,112,601,129,466,129,465,111" alt="Lebuah Bandung">
<area shape="poly" href="profil.php?desa=Air Lintang" target="display" onMouseOver="mapOnMouseOver(\'Air Lintang\')"  coords="504,207,499,207,498,206,494,203,495,200,497,196,499,195,502,194,505,195,509,196,509,201,507,206,504,207" alt="Air Lintang">
<area shape="poly" href="profil.php?desa=Air Lintang" target="display" onMouseOver="mapOnMouseOver(\'Air Lintang\')"  coords="505,139,601,139,601,156,505,156,505,139" alt="Air Lintang">
<area shape="poly" href="profil.php?desa=Tanjung Jambu" target="display" onMouseOver="mapOnMouseOver(\'Tanjung Jambu\')"  coords="418,172,416,175,417,175,416,177,410,179,406,177,403,172,405,166,408,165,412,165,416,165,417,168,418,172" alt="Tanjung Jambu">
<area shape="poly" href="profil.php?desa=Tanjung Jambu" target="display" onMouseOver="mapOnMouseOver(\'Tanjung Jambu\')"  coords="414,32,565,32,566,52,414,52,414,32" alt="Tanjung Jambu">
<area shape="poly" href="profil.php?desa=Muara Lawai" target="display" onMouseOver="mapOnMouseOver(\'Muara Lawai\')"  coords="421,172,423,168,427,166,432,166,437,170,438,173,437,179,431,182,427,181,423,178,421,177,421,172" alt="Muara Lawai">
<area shape="poly" href="profil.php?desa=Muara Lawai" target="display" onMouseOver="mapOnMouseOver(\'Muara Lawai\')"  coords="430,64,563,64,562,64,562,84,430,84,430,64" alt="Muara Lawai">
</map>';

$main_content = ob_get_clean();

require './template/sosek/page_tpl.inc.php';
