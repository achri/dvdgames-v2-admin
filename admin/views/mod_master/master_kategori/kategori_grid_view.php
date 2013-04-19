<script language="javascript">
$(document).ready(function() {
	
	//dtgLoadButton();
	
	var grid = grid_content.jqGrid({
		ajaxGridOptions : {
			type:"POST"
		},
		jsonReader : {
			root:"data",
			repeatitems: false
		},
		ondblClickRow: function(id){
			var gridwidth = grid_content.width();
			
			gridwidth = gridwidth / 2;
			grid.editGridRow(id, {
				closeAfterEdit:true,
				mtype:'POST'
			});
			return;
		},
		loadError :function(xhr,status, err){ 
			try {
				jQuery.jgrid.info_dialog(jQuery.jgrid.errors.errcap,'<div class="ui-state-error">'+ xhr.responseText +'</div>', jQuery.jgrid.edit.bClose,{buttonalign:'right'});
			} 
			catch(e) { alert(xhr.responseText);} 
		},
		onSelectRow: function(kat_id){
			var gsr = grid_content.jqGrid('getGridParam','selrow'); 
			grid_content.jqGrid('GridToForm',gsr,"#kat_form"); 
			selrow_kat();
		},
		/*
		gridComplete: function(){ 
			var ids = grid_content.jqGrid('getDataIDs'); 
			for(var i=0;i < ids.length;i++){ 
				var cl = ids[i], gbr_fn; 
				gbr_fn = grid_content.jqGrid('getRowData',ids[i]).kat_gambar; 
				
				$.ajax({
					url : 'index.php/<?php echo $link_controller?>/show_photo/'+gbr_fn+'/kategori',
					type: 'POST',
					success: function(gbr) {
						grid_content.jqGrid('setRowData',ids[i],{gambar:gbr}); 
					}
				});
			} 
		},
		*/
		caption: 'Daftar Kategori',
		url : 'index.php/<?php echo $link_controller;?>/get_data',
		datatype : "JSON",
		//colNames:['Kode','Nama', 'Jumlah'], 
		colModel:[ 
			{name:'kat_id', hidden:true, key:true}, 
			{name:'kat_nama', label:'Kategori', width:200, sortable: true},
			{name:'kat_gambar', hidden:true, width:100, align:'center'},
			//{name:'gambar', label:'Gambar', width:100, align:'center'},
		], 
		sortname : 'kat_nama',
		sortorder : 'ASC',
		rowNum:5, 
		rowList:[5,10,20,30],
		height: 'auto',
		rownumbers: true,
		//viewsortcols : true,
		sortable : true,
		viewrecords: true,
		autowidth: true,
		forceFit: true,
		shrinkToFit: true,
		pager: grid_paging,
		
	});
	
	grid_content.jqGrid('navGrid','#'+grid_paging,
		{edit:false,add:false,del:false},
		{closeAfterEdit:true,reloadAfterSubmit:true,mtype: 'POST'}/*edit options*/,
		{closeAfterAdd:true,reloadAfterSubmit:true,mtype: 'POST'} /*add options*/,
		{reloadAfterSubmit:true,mtype: 'POST'} /*delete options*/,
		{sopt:['eq','cn','ge','le'],
		overlay:false,mtype: 'POST'}/*search options*/
	);
});
</script>

<table id="newapi_<?php echo $class_name?>"></table>
<div id="pnewapi_<?php echo $class_name?>"></div>