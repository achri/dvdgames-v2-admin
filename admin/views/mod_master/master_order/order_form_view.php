<form id="order_form" method="post" name="order_form"> 
<fieldset class="ui-widget-content ui-corner-all"> 
	<legend>INVOICE PEMESANAN</legend> 
	<table width="100%" class="ui-widget-content" border=0 style="border:0px"> 
		<tbody valign="top"> 
			<tr> 
				<td width="10%">No. Order</td> 
				<td>:</td>
				<td>
					<input type="hidden" name="inv_id" id="inv_id"/>
					<input type="text" name="inv_code" readonly="true"/>
				</td>
				<td>&nbsp;</td>
				<td>No. Resi</td>
				<td>:</td>
				<td>
					<input type="text" id="tiki_noresi" name="tiki_noresi"/>
				</td> 					
			</tr> 
			<tr> 
				<td>Tgl. Order</td> 
				<td>:</td>
				<td>
					<input readonly="true" type="text" name="inv_tgl"/>
				</td>
				<td>&nbsp;</td>
				<td>Wilayah</td>
				<td>:</td>
				<td>
					<input readonly="true" type="text" name="sync_name" size="40"/>
				</td> 
			</tr>
			<tr> 
				<td>Atas Nama</td> 
				<td>:</td>
				<td>
					<input readonly="true" type="text" name="user_nama"/>
				</td>
				<td>&nbsp;</td>
				<td>Jenis Paket</td> 
				<td>:</td>
				<td>
					<input readonly="true" type="text" name="tiki_paket"/>
				</td>						
			</tr>
			<tr> 
				<td>Alamat</td> 
				<td>:</td>
				<td>
					<textarea readonly="true" name="user_alamat"></textarea>
				</td>
				<td>&nbsp;</td>
				<td>Biaya</td>
				<td>:</td>
				<td>
					<input readonly="true" type="text" name="tiki_tariff"/>
				</td> 				
			</tr>
			<tr> 
				<td>Status</td> 
				<td>:</td>
				<td>
					<input type="hidden" name="inv_status_old" />
					<select id="inv_status" name="inv_status">
						<option value="0">Belum Dibayar</option>
						<option value="1">Lunas</option>
						<option value="2">Sudah Dikirim</option>
						<option value="3">Tunda</option>
						<option value="4">Batal</option>
						<option value="5">Expired</option>
					</select>
				</td>
				<td>&nbsp;</td>
				<td>Total Harga</td>
				<td>:</td>
				<td>
					<input readonly="true" type="text" name="grand_total_all"/>
				</td> 				
			</tr>
			<tr><td colspan=7><hr></td></tr>
			<tr> 
				<td><input type="button" id="savedata" value="Simpan" onclick=""/></td>
			</tr> 
		</tbody>
	</table> 
</fieldset> 
</form>