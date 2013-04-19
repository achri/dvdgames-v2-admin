<HTML>
<HEAD>
<TITLE>
<?php 
	if (isset($site_title)): 
		echo $site_title;
	else:
		echo "DVD GAME ONLINE ADMIN";
	endif;
?>
</TITLE>
<HEAD>
<base href="<?php echo base_url()?>"/>
<?php 
	if (isset($extraHeadContent)):
		echo $extraHeadContent;
	else:
		exit('SITE UNDERCONTRACTION !!!');
	endif;
?>
<link href="asset/images/layout/dvds_small.png" rel="shortcut icon" type="image/ico" />
</HEAD>
<BODY>
<div class="dialog-content" title="DIALOG"></div>
<div class="dialog-validasi" title="DIALOG"></div>
<DIV id="admin-tabs">
	<ul>
		<li><a href='index.php/mod_master/master_kategori'>KATEGORI DVD</a></li>
		<li><a href='index.php/mod_master/master_dvd'>DAFTAR DVD</a></li>
		<!--li><a href='index.php/mod_master/master_tiki'>DAFTAR TIKI</a></li-->
		<li><a href='index.php/mod_master/master_order'>DAFTAR ORDER</a></li>
	</ul>
</DIV>
</BODY>
</HTML>
