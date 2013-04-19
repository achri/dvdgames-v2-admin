<script language="javascript">
/* JQGRID SELECTOR */
var dvd_grid_content = jQuery("#newapi_<?php echo $class_name?>"),
	dvd_grid_paging	= 'pnewapi_<?php echo $class_name?>';

function dvd_bersihkan() {
	$('form .dvd_form').val('');
	dvd_show_photo();
	$('#dvd_nama').focus();
	return false;
}

function dvd_unlocked() {
	$('form .dvd_form').attr('disabled',false);
	$('form #dvd_getgambar').attr('disabled',false);
	return false;
}

function dvd_locked() {
	$('form .dvd_form, #dvd_getgambar').attr('disabled','disabled');
	return false;
}

/* SET DEFAULT CONDITION */
function dvd_kondisi_awal(status) {
	$('#dvd_tambah').val("Tambah");
	$('#dvd_ubah').val("Edit");
	$('#dvd_tambah').show();
	$('#dvd_ubah, #dvd_hapus, #dvd_batal').hide();
	dvd_locked();
	dvd_grid_content.trigger('reloadGrid');
	
	if (status){
		dvd_bersihkan();
		dvd_show_photo();
	}
		
	return false;
}

/* BIND EVENT SELECTED GRID */
function dvd_selrow_dvd() {
	$('#dvd_tambah, #dvd_ubah, #dvd_hapus').show();
	$('#dvd_tambah').val("Tambah");
	$('#dvd_ubah').val("Edit");
	$('#dvd_batal').hide();
	dvd_locked();
	dvd_show_photo();
	
	var get_gambar = $('#dvd_gambar').val();
	$('#dvd_gambar_awal').val(get_gambar);
	return false;
}

function dvd_show_photo() {
	var target = $('#dvd_gambar').val();
	if (!target) {
		target = 1;
	}	
	
	$.ajax({
		url: 'index.php/<?php echo $link_controller?>/show_photo/'+target+'/dvd',
		type: 'POST',
		success: function(data){
			$('#dvd_photos').html(data);
			return false;
		}
	});
	return false;
}

/* GENERATE KODE DVD */
function set_kode(kat_id) {
	var dvd_id = $('#dvd_id').val();
	if (kat_id) {
		$.ajax({
			url: 'index.php/<?php echo $link_controller?>/set_kode/'+kat_id+'/'+dvd_id,
			type: 'POST',
			success: function(kode) {
				$('#dvd_kode').val(kode);
				return false;
			}
		});
	} else {
		$('#dvd_kode').val('');
	}	
	return false;
}

/* ADD RECORD */
function tambah_dvd(status) {
	$('#dvd_ubah, #dvd_hapus').hide();
	$('#dvd_batal').show();
	
	if (status == "Tambah"){
		dvd_bersihkan();
		dvd_unlocked();
		$('#dvd_tambah').val("Simpan");
	} else {
		if (validasi("form#dvd_form")) {
			$.ajax({
				url: 'index.php/<?php echo $link_controller?>/tambah_dvd',
				type: 'POST',
				data: $('form#dvd_form').formSerialize(),
				success: function(data) {
					if (data) {
						dvd_kondisi_awal();
						//$('#tambah').val("Tambah");
					} else {
						informasi("Duplikasi DVD");
					}
				}
			});
		}
	}
	return false;
}

/* EDIT RECORD */
function ubah_dvd(status) {
	$('#dvd_tambah, #dvd_hapus').hide();
	$('#dvd_batal').show();
	if (status == "Edit"){
		dvd_unlocked();
		$('#dvd_ubah').val("Ubah");
	} else {
		if (validasi("form#dvd_form")) {
			$.ajax({
				url: 'index.php/<?php echo $link_controller?>/ubah_dvd',
				type: 'POST',
				data: $('form#dvd_form').formSerialize(),
				success: function(data) {
					if (data) {
						dvd_kondisi_awal();
						//$('#ubah').val("Edit");
					} else {
						informasi("ERROR KONEKSI");
					}
				}
			});
		}
	}
	return false;
}

/* DELETE RECORD */
function hapus_dvd() {
	var dvd_id = $('#dvd_id').val();
	dialog_confirm("DVD ini akan dihapus ???",function(yes) {
		$.ajax({
			url: 'index.php/<?php echo $link_controller?>/hapus_dvd/'+dvd_id,
			type: 'POST',
			success: function(data) {
				if (!data)
					dvd_kondisi_awal(true)
				else
					dialog_alert("DVD tidak diperbolehkan dihapus !!!, karena terdaftar di transaksi. Atau coba update stok master kosong.");
			}
		});	
	});
			
	return false;
}

// AJAX UPLOAD
$(document).ready(function(){
	var button = $('.dvd_photos'), interval,
		dvd_upload = new AjaxUpload('#dvd_getgambar',{
			action: 'index.php/<?php echo $link_controller?>/ajaxupload', 
			name: 'userfile',
			data: {'gambar': $('#dvd_gambar').val()},
			//responseType: 'json',
			onSubmit : function(file, ext){
				//dvd_upload.setData({'gambar': $('#dvd_gambar').val()});
				if (! (ext && /^(jpg|png|jpeg|gif)$/i.test(ext))){
					informasi('Exstensi File tidak mendukung !!!');
					return false;
				} else {		
					button.text('mengirim');
					this.disable();
					interval = window.setInterval(function(){
						var text = button.text();
						if (text.length < 13){
							button.text(text + '.');					
						} else {
							button.text('mengirim');				
						}
					}, 200);
				}
			},
			onChange: function(file, extension){
			},
			onComplete: function(file, response){
				var test = response.split("|");
				if (test.length > 0)
					response = test[0];
								
				button.text('Unggah');
				window.clearInterval(interval);
				this.enable();
				$('#dvd_gambar').val(response);	
				$('#dvd_photos').load('index.php/<?php echo $link_controller?>/show_photo/'+response);
				return false;
			}
		});
		
	
});
dvd_kondisi_awal(true);
</script>

<div style="display:table;width:99%">
	<table width="100%" border=0>
	<tr valign="top">
		<td width="100%">
			<?php $this->load->view($link_view.'/dvd_form_view')?>
		</td>
		<td align="center">
			<fieldset class="ui-widget-content ui-corner-all" style="width:300px; height:343px;">
				<legend>COVER</legend>
				<table width="100%" height="100%">
				<tr>
					<td align="center" valign="middle">
					<span style="position:absolute; z-index: 99999; top: 130px; left: 913px">
						<!--img src="<//?php echo base_url()?>asset/images/Jewel.png" width="275px" height="240px"/-->
					</span>
					<div id="dvd_photos" class="dvd_photos">
						<!-- AJAX PHOTO -->
					</div>
					</td>
				</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php $this->load->view($link_view.'/dvd_grid_view')?>
		</td>
	</tr>
	</table>
</div>