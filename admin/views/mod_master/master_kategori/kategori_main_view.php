<?php
	if (isset($extraSubHeadContent))
		echo $extraSubHeadContent;
?>

<script language="javascript">
/* JQGRID SELECTOR */
var grid_content = jQuery("#newapi_<?php echo $class_name?>"),
	grid_paging	= 'pnewapi_<?php echo $class_name?>';

function bersihkan() {
	$('form .kat_form').val('');
	show_photo();
	$('#kat_nama').focus();
	return false;
}

function unlocked() {
	$('form .kat_form').attr('disabled',false);
	$('form #getgambar').attr('disabled',false);
	return false;
}

function locked() {
	$('form .kat_form, #getgambar').attr('disabled','disabled');
	return false;
}

/* SET DEFAULT CONDITION */
function kondisi_awal(status) {
	$('#tambah').val("Tambah");
	$('#ubah').val("Edit");
	$('#tambah').show();
	$('#ubah, #hapus, #batal').hide();
	locked();
	grid_content.trigger('reloadGrid');
	
	if (status){
		bersihkan();
		show_photo();
	}
		
	return false;
}

/* BIND EVENT SELECTED GRID */
function selrow_kat() {
	$('#tambah, #ubah, #hapus').show();
	$('#tambah').val("Tambah");
	$('#batal').hide();
	locked();
	show_photo();
	
	var get_gambar = $('#gambar').val();
	$('#gambar_awal').val(get_gambar);
	return false;
}

function show_photo() {
	var target = $('#gambar').val();
	if (!target) {
		target = 1;
	}	
	
	$.ajax({
		url: 'index.php/<?php echo $link_controller?>/show_photo/'+target+'/kategori',
		type: 'POST',
		success: function(data){
			$('#photos').html(data);
			return false;
		}
	});
	return false;
}

/* ADD RECORD */
function tambah_kat(status) {
	$('#ubah, #hapus').hide();
	$('#batal').show();
	
	if (status == "Tambah"){
		bersihkan();
		unlocked();
		$('#tambah').val("Simpan");
	} else {
		if (validasi("form#kat_form")) {
			$.ajax({
				url: 'index.php/<?php echo $link_controller?>/tambah_kategori',
				type: 'POST',
				data: $('form#kat_form').formSerialize(),
				success: function(data) {
					if (data) {
						kondisi_awal();
						//$('#tambah').val("Tambah");
					}
				}
			});
		}
	}
	return false;
}

/* EDIT RECORD */
function ubah_kat(status) {
	$('#tambah, #hapus').hide();
	$('#batal').show();
	if (status == "Edit"){
		unlocked();
		$('#ubah').val("Ubah");
	} else {
		if (validasi("form#kat_form")) {
			$.ajax({
				url: 'index.php/<?php echo $link_controller?>/ubah_kategori',
				type: 'POST',
				data: $('form#kat_form').formSerialize(),
				success: function(data) {
					if (data) {
						kondisi_awal();
						//$('#ubah').val("Edit");
					}
				}
			});
		}
	}
	return false;
}

/* DELETE RECORD */
function hapus_kat() {
	var kat_id = $('#kat_id').val();
	
	dialog_confirm("Kategori ini akan dihapus??",function(yes){
		if (yes) {
			$.ajax({
				url: 'index.php/<?php echo $link_controller?>/hapus_kategori/'+kat_id,
				type: 'POST',
				success: function(data) {
					if (!data) {
						kondisi_awal(true);
					} else {
						alert('Kategori ini sedang dipakai !!!');
					}
				}
			});	
		}
	});
	return false;
}

// AJAX UPLOAD
$(document).ready(function(){
/*
	var button = $('.photos'), interval,
		upload = new AjaxUpload('#getgambar',{
			action: 'index.php/<?php echo $link_controller?>/ajaxupload', 
			name: 'userfile',
			onSubmit : function(file, ext){
				upload.setData({'gambar': $('#gambar').val()});
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
				button.text('Unggah');
				window.clearInterval(interval);
				this.enable();
				$('#gambar').val(response);	
				$('#photos').load('index.php/<?php echo $link_controller?>/show_photo/'+response);
				return false;
			}
		});
		*/
});

kondisi_awal(true);
</script>

<div style="display:table;width:99%">
	<table width="100%" border=0>
	<tr valign="top">
		<td width="100%">
			<?php $this->load->view($link_view.'/kategori_form_view')?>
		</td>
		<td align="center" rowspan=2>
			<fieldset class="ui-widget-content ui-corner-all" style="width:300px; height:343px;">
				<legend>COVER</legend>
				<table width="100%" height="100%">
				<tr>
					<td align="center" valign="middle">
					<span style="position:absolute; z-index: 99999; top: 130px; left: 913px">
						<!--img src="<//?php echo base_url()?>asset/images/Jewel.png" width="275px" height="240px"/-->
					</span>
					<div id="photos" class="photos">
						<!-- AJAX PHOTO -->
					</div>
					</td>
				</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr valign="top">
		<td>
			<?php $this->load->view($link_view.'/kategori_grid_view')?>
		</td>
	</tr>
	</table>
</div>