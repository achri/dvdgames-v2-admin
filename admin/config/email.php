<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['useragent'] = 'DVDGAMES';
$config['protocol'] = 'smtp';
//$config['mailpath'] = '/usr/sbin/sendmail';

if(INWEB) {
	$config['smtp_host'] = 'mail.dvdgames-online.com';
	$config['smtp_user'] = 'admin+dvdgames-online.com';
	$config['smtp_pass'] = 'a1b2c3d4e5';
	$config['smtp_port'] = 2525;
} else {
	$config['smtp_host'] = '127.0.0.1';
	$config['smtp_user'] = 'administrator+localhost';
	$config['smtp_pass'] = '12345';
	$config['smtp_port'] = 25;
}

$config['smtp_timeout'] = 5;
$config['wordwrap'] = TRUE;
$config['wrapchars'] = 76;
$config['mailtype'] = 'html';
$config['charset'] = 'iso-8859-1';//'utf-8';
//$config['validate'] = TRUE;
$config['priority'] = 3;

$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";