<script language="javascript">
//123456789012345678901234567890
$('#dvd_form input').attr('maxlength',50);
</script>
<form id="dvd_form"> 
<fieldset class="ui-widget-content ui-corner-all"> 
	<legend>FORM DATA DVD</legend> 
	<table width="100%" class="ui-widget-content" border=0 style="border:0px"> 
		<tbody valign="top"> 
			<tr> 
				<td width="15%">Kode Dvd</td> 
				<td>:</td>
				<td>
					<input class="dvd_form" type="hidden" name="dvd_id" id="dvd_id"/>
					<input class="dvd_form" type="text" name="dvd_kode" id="dvd_kode" readonly=true/>
				</td>
				<td>&nbsp;</td>
				<td>Cover</td>
				<td>:</td>
				<td>
					<input type="button" id="dvd_getgambar" value="Pilih" class="ui-corner-all ui-widget-header"> 
					<input class="dvd_form" readonly="true" name="dvd_gambar" type="text" id="dvd_gambar" size="11"/>
					<input type="hidden" id="dvd_gambar_awal" name="dvd_gambar_awal" class="dvd_form">
				</td> 				
			</tr> 
			<tr> 
				<td>Kategori</td>
				<td>:</td>
				<td>
					<select name="kat_id" class="dvd_form required select" title="Kategori" onchange="set_kode(this.value);">
						<option value="">[Pilih]</option>
						<?php
						if ($list_kategori->num_rows() > 0):
							foreach ($list_kategori->result() as $row):
						?>
							<option value="<?php echo $row->kat_id?>"><?php echo $row->kat_nama?></option>
						<?php
							endforeach;
						endif;
						?>
					</select>
				</td>
				<td>&nbsp;</td>	
				<td>Release</td>
				<td>:</td>
				<td><input type="text" name="dvd_release" class="dvd_form kalender"/></td>				
			</tr> 
			<tr> 
				<td>Nama Dvd</td>
				<td>:</td>
				<td><input class="dvd_form required uppercase" type="text" name="dvd_nama" title="Nama Dvd"/></td>
				<td>&nbsp;</td>
				<td>Link</td>
				<td>:</td>
				<td><input class="dvd_form" type="text" name="dvd_link" /></td>
			</tr> 
			<tr> 
				<td>Jumlah</td>
				<td>:</td>
				<td><input type="text" name="dvd_jumlah" class="dvd_form required number" title="Jumlah Dvd"/></td>  
				<td rowspan=2>&nbsp;</td>
				<td rowspan=2>Cheat</td>
				<td rowspan=2>:</td>
				<td rowspan=2><textarea class="dvd_form" name="dvd_cheat" /></textarea></td>
			</tr> 
			<tr> 
				<td>Review</td>
				<td>:</td>
				<td><textarea class="dvd_form" name="dvd_review" /></textarea></td>
			</tr> 
			<tr> 
				<td>Serial</td>
				<td>:</td>
				<td><input class="dvd_form uppercase" type="text" name="dvd_serial" /></td> 
				<td rowspan=2>&nbsp;</td>	
				<td rowspan=2>Keterangan</td>
				<td rowspan=2>:</td>
				<td rowspan=2><textarea class="dvd_form" name="keterangan" /></textarea></td> 
			</tr> 
			<tr> 
				<td>Rating</td>
				<td>:</td>
				<td><input class="dvd_form number" type="text" name="dvd_rating" /></td> 
			</tr> 
			<tr> 
				<td>Stok Master</td>
				<td>:</td>
				<td>
					<select class="dvd_form" id="dvd_status" name="dvd_status">
						<option value="1">Tersedia</option>
						<option value="0">Kosong</option>
					</select>
				</td> 
			</tr> 
			<tr><td colspan=7><hr></td></tr>
			<tr> 
				<td colspan=2><input class="dvd_btn" onclick="tambah_dvd(this.value);" type="button" id="dvd_tambah" value="Tambah" /></td> 
				<td colspan=2>
					<input class="dvd_btn" onclick="ubah_dvd(this.value);" type="button" id="dvd_ubah" value="Ubah" />
					<input class="dvd_btn" onclick="hapus_dvd(this.value);" type="button" id="dvd_hapus" value="Hapus" />
				</td> 
				<td colspan=2><input class="dvd_btn" onclick="dvd_kondisi_awal();" type="button" id="dvd_batal" value="Batal" /></td> 
				<td align="right"><input type="reset" value="Bersihkan" /></td> 
			</tr> 
		</tbody> 
	</table> 
</fieldset> 
</form>