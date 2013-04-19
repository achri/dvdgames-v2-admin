<form id="kat_form"> 
<fieldset class="ui-widget-content ui-corner-all"> 
	<legend>FORM DATA KATEGORI</legend> 
	<table width="100%" class="ui-widget-content" border=0 style="border:0px"> 
		<tbody valign="top"> 
			<tr> 
				<td width="15%">Nama Kategori</td> 
				<td>:</td>
				<td>
					<input class="kat_form" type="hidden" name="kat_id" id="kat_id"/>
					<input class="kat_form required uppercase" type="text" name="kat_nama" id="kat_nama" title="Nama Kategori"/>
				</td>
				<td>&nbsp;</td>
				<td>Gambar</td>
				<td>:</td>
				<td>
					<input type="button" id="getgambar" value="Pilih" class="ui-corner-all ui-widget-header"> 
					<input class="kat_form" readonly="true" name="kat_gambar" type="text" id="gambar" size="11"/>
					<input type="hidden" id="gambar_awal" name="kat_gambar_awal" class="kat_form">
				</td> 				
			</tr> 
			<tr><td colspan=7><hr></td></tr>
			<tr> 
				<td colspan=2><input onclick="tambah_kat(this.value);" type="button" id="tambah" value="Tambah" /></td> 
				<td colspan=2>
					<input onclick="ubah_kat(this.value);" type="button" id="ubah" value="Ubah" />
					<input onclick="hapus_kat(this.value);" type="button" id="hapus" value="Hapus" />
				</td> 
				<td colspan=2><input onclick="kondisi_awal();" type="button" id="batal" value="Batal" /></td> 
				<td align="right"><input type="reset" value="Bersihkan" /></td> 
			</tr> 
		</tbody> 
	</table> 
</fieldset> 
</form>