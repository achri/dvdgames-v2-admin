<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

// BASE DIRECT OPENSSL OR NATIVE SERVER PROTOCOL @AHRIE
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$wwwp = 'https://';
} else {
	$wwwp = 'http://';
}

if (INWEB) {
	$asset_src = 'source.dvdgames-online.com/';
	$asset_upload = 'storage.dvdgames-online.com/storage/';
	$env_status = '';
} else {
	$asset_src = $_SERVER['SERVER_NAME'].':5001/';
	$asset_upload = $_SERVER['SERVER_NAME'].':5002/storage/';
	$env_status = getenv('APP_STATUS');
}

// BASE LINK
$config['base_url'] = $wwwp.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
// ASSET LINK
$config['asset_src'] = $wwwp.$asset_src;
$config['asset_upload'] = $wwwp.$asset_upload;
// APPLICATION STATUS
$config['app_status'] = getenv('APP_STATUS');

// CONFIG HARGA
$config['harga_dvd'] = 30000;
$config['harga_cd'] = 15000;

// LIMIT ITEM DVD
$config['dvd_limit'] = 8;

// EMAIL
$config['email_admin'] = "administrator@localhost";
$config['email_sales'] = "sales@localhost";
$config['email_support'] = "support@localhost";

// DEBUG
$config['debug'] = FALSE;

// JS AND CSS COMPRESOR
$config['minify'] = WEB_MINIFY;
$config['url_packed']['css'] = $config['base_url'].'/asset/css/packed/';
$config['url_packed']['js'] = $config['base_url'].'/asset/js/packed/';
$config['url_packed']['temp'] = $config['base_url'].'/asset/temp/';

// UNDERCONTRACTION
$config['uc'] = FALSE;

// DEFAULT SKIN
$config['themes_default'] = WEB_THEMES;

/*
* STATUS KODE DLM TABLE
	cart		
	- cart_status	: 0 = sedang memesan
									1 = tunggu pemesan mengkonfirmasi
									2 = invoice dibuat
	cart_detail
	- on_status		: 0 = pesanan belum diproses
									1 = pesanan sudah diproses
	invoice
	- inv_status	: 0 = invoice belum dibayar
									1 = sudah dibayar
									2 = pesanan sudah dikirim
									3 = tunda kirim pesanan
									4 = batalkan pesanan
									5 = retur pesanan
									99 = pesanan telah expired (belum dibayar)
	master_dvd
	- dvd_status	: 0 = stok master kosong
									1 = stok master ada
*/

