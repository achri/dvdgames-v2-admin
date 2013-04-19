<script language="javascript">
$(document).ready(function() {
	
	//dtgLoadButton();
	
	var dvd_grid = dvd_grid_content.jqGrid({
		ajaxGridOptions : {
			type:"POST"
		},
		jsonReader : {
			root:"data",
			repeatitems: false
		},
		loadError :function(xhr,status, err){ 
			try {
				jQuery.jgrid.info_dialog(jQuery.jgrid.errors.errcap,'<div class="ui-state-error">'+ xhr.responseText +'</div>', jQuery.jgrid.edit.bClose,{buttonalign:'right'});
			} 
			catch(e) { alert(xhr.responseText);} 
		},
		onSelectRow: function(kat_id){
			var gsr = dvd_grid_content.jqGrid('getGridParam','selrow'),
					dvd_status = dvd_grid_content.getRowData(kat_id).dvd_status_old;
			dvd_grid_content.jqGrid('GridToForm',gsr,"#dvd_form"); 
			$('#dvd_form #dvd_status').val(dvd_status);
			dvd_selrow_dvd();
		},
		gridComplete: function(){
			var id = dvd_grid_content.jqGrid('getDataIDs'); 
			for(var i=0;i < id.length;i++){ 
				var cl = id[i],
					dvd_status = dvd_grid_content.getRowData(cl).dvd_status,
					arr_stat = new Array('Kosong','Tersedia');
				dvd_grid_content.jqGrid('setRowData',cl,{dvd_status:arr_stat[dvd_status],dvd_status_old:dvd_status});
			}
			return;
		},
		caption: 'Daftar DVD',
		url : 'index.php/<?php echo $link_controller;?>/get_data',
		datatype : "JSON",
		//colNames:['Kode','Nama', 'Jumlah'], 
		colModel:[ 
			{name:'dvd_id',hidden:true, key:true}, 
			{name:'kat_id',hidden:true, key:true}, 
			{name:'created',label:'Tgl', width:35,align:'center', formatter:'date', formatoptions:{srcformat:"Y-m-d",newformat:"d/m/y"},searchoptions:{dataInit:function(el){$(el).datepicker({dateFormat:'yy-mm-dd'});} }},
			{name:'dvd_kode',label:'Kode', width:90, align:'center'}, 
			{name:'kat_nama',label:'Kategori', width:90, surl: "index.php/<?php echo $link_controller?>/gridData_kategori", stype:'select'},
			{name:'dvd_nama',label:'Nama'}, 
			{name:'dvd_jumlah',label:'Jml', width:20, align:"right"}, 
			{name:'dvd_gambar',label:'Cover', width:80, align:"center"},
			{name:'dvd_status',label:'Master', width:80, align:"center", editoptions:{value:":Semua;1:Tersedia;0:Kosong",size:10}, stype:'select'}, 	
			{name:'dvd_status_old',hidden:true},
			
			{name:'dvd_review',hidden:true}, 
			{name:'dvd_release',hidden:true}, 
			{name:'dvd_serial',hidden:true},
			{name:'dvd_cheat',hidden:true},
			{name:'dvd_link',hidden:true},
			{name:'dvd_rating',hidden:true},
			{name:'keterangan',hidden:true}
		], 
		sortname : 'dvd_kode',
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
		pager: dvd_grid_paging,
		
	});
	
	dvd_grid_content.jqGrid('navGrid','#'+dvd_grid_paging,
		{view:false,edit:false,add:false,del:false,add:false,refresh:false,search:false},
		{closeAfterEdit:true,reloadAfterSubmit:true,mtype: 'POST'},
		{closeAfterAdd:true,reloadAfterSubmit:true,mtype: 'POST'},
		{reloadAfterSubmit:true,mtype: 'POST'},
		{sopt:['eq','cn','ge','le'],
		overlay:false,mtype: 'POST'}
	);
	
	dvd_grid_content.jqGrid('navButtonAdd',
		'#'+dvd_grid_paging,
		{
			caption:"Search",
			title:"Toggle Search Toolbar",
			buttonicon :'ui-icon-pin-s', 
			onClickButton:function(){ 
				dvd_grid_content[0].toggleToolbar() 
			} 
		}
	);
			
	dvd_grid_content
		.jqGrid('navButtonAdd',
		'#'+dvd_grid_paging,
		{
			caption:"Clear",
			title:"Clear Search",
			buttonicon :'ui-icon-refresh', 
			onClickButton:function(){ 
				dvd_grid_content[0].clearToolbar() 
			} 
		}
	); 
	
	dvd_grid_content.jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});//[0].toggleToolbar();
});
</script>

<table id="newapi_<?php echo $class_name?>"></table>
<div id="pnewapi_<?php echo $class_name?>"></div>