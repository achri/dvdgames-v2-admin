<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 
class Lib_mail {
	private $CI,$harga_dvd,$email_admin,$email_selles;
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('email');
		$this->CI->config->load('email');
		
		// private variable
		$this->harga_dvd	= $this->CI->config->item('harga_dvd');
		
		// email
		$this->email_admin	= $this->CI->config->item('email_admin');
		$this->email_selles	= $this->CI->config->item('email_selles');
	}
	
	function mail_resi($kirim_id)
	{
		// PREPARE CONTENT
		$data = "
		<HTML>
		<HEAD>
		<TITLE>DVDGAMES-ONLINE.COM RINCIAN PEMBELIAN</TITLE>
		</HEAD>
		<BODY>
		";
		
		$SQLjual = '
			select p.jual_no,p.email,p.tot_jumlah,p.tot_dvd,p.bonus,p.tot_harga,
			k.no_resi,k.scan_resi,k.alamat,k.total_biaya,t.tarif_hrg,t.tarif_cas,tk.tiki_wilayah,
			tkp.paket_nama
			from pengiriman as k
			inner join master_tarif as t on t.tarif_id = k.tarif_id
			inner join master_tiki as tk on tk.tiki_id = t.tujuan
			inner join master_tiki_paket as tkp on tkp.paket_id = t.paket_id
			inner join penjualan as p on p.jual_id = k.jual_id and k.kirim_id = "'.$kirim_id.'"
		';	
		
		$gjual = $this->CI->db->query($SQLjual);
		foreach ($gjual->result() as $row):
			$data .= "
			<table width='100%'>
			<tbody valign='top'>
			<tr>
				<td colspan = 3>
					RINCIAN PENGIRIMAN DVD
				</td>
			</tr>
			<tr>
				<td colspan = 3><hr></td>
			</tr>
			<tr>	
				<td width = '20px'>No. Resi</td><td width='1px'>:</td><td>".$row->no_resi."</td>
			</tr>
			<tr>	
				<td>Nomor</td><td>:</td><td>".$row->jual_no."</td>
			</tr>
			<tr>	
				<td>Email</td><td>:</td><td>".$row->email."</td>
			</tr>
			<tr>	
				<td>Alamat</td><td>:</td><td>".$row->alamat."</td>
			</tr>
			<tr>
				<td colspan = 3><hr></td>
			</tr>
			<tr>	
				<td>Jumlah</td><td>:</td><td>".$row->tot_jumlah." item(s) = ".$row->tot_dvd." dvd</td>
			</tr>
			<tr>	
				<td>Potongan</td><td>:</td><td>".$row->bonus." dvd = Rp. ".number_format(($row->bonus != 0)?($row->bonus * $this->harga_dvd):(0),2)."</td>
			</tr>
			<tr>	
				<td>Harga</td><td>:</td><td>Rp. ".number_format($row->tot_harga,2)."</td>
			</tr>
			<tr>
				<td colspan = 3><hr></td>
			</tr>
			<tr>	
				<td>Paket</td><td>:</td><td>".$row->paket_nama."</td>
			</tr>
			<tr>	
				<td>Wilayah</td><td>:</td><td>".$row->tiki_wilayah."</td>
			</tr>
			<tr>	
				<td>Biaya</td><td>:</td><td>Rp. ".number_format($row->tarif_hrg,2)."</td>
			</tr>
			<tr>	
				<td>Cas</td><td>:</td><td>Rp. ".number_format($row->tarif_cas,2)."</td>
			</tr>
			<tr>
				<td colspan = 3><hr></td>
			</tr>
			<tr>	
				<td>Total Biaya</td><td>:</td><td>Rp. ".number_format($row->total_biaya,2)."</td>
			</tr>
			<tr>
				<td colspan = 3>&nbsp;</td>
			</tr>
			</tbody>
			</table>
			catatan: <br/>
			- Kami tidak bertangungjawab atas keterlambatan pengiriman paket oleh TIKI. <br/>
			- Untuk komplein barang silahkan hubungi kami. <br/>
			by ".anchor(site_url(),'DVDGAMES-ONLINE.COM')." <br/>
			";
			
			$email = $row->email;
			$bukti_resi = $row->scan_resi;
		endforeach;
		
		$data .= "
		</BODY>
		</HTML>
		";
		
		// EMAIL CONTENT
		$this->CI->email->clear();
		$this->CI->email->from($this->email_admin, 'DVDGAMES-ONLINE.COM');
		$this->CI->email->to($email);
		$this->CI->email->cc($this->email_selles);
		//$this->CI->email->bcc('administrator@localhost');
		$this->CI->email->subject('Rincian pengiriman DVD');
		$this->CI->email->set_alt_message('Untuk informasi lebih lanjut hubungi kami : cs@dvdgames-online.com');
		$this->CI->email->message(htmlspecialchars_decode($data));
		
		$this->CI->email->attach('uploaded/tiki/'.$bukti_resi);

		$this->CI->email->send();
		echo $this->CI->email->print_debugger();
		
	}
}