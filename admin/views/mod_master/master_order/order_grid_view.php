<script type="text/javascript">
var grid_content = jQuery("#newapi_order"),
	lastsel,jual_id;
	
function order_status(grids,row_id,status,cart_id,dvd_id) {
	$.ajax({
		url: 'index.php/<?php echo $link_controller;?>/set_order_status/',
		type: 'POST',
		data: {'status':status,'cart_id':cart_id,'dvd_id':dvd_id},
		success: function(data) {
			jQuery("#"+grids).trigger('reloadGrid');
			
			if (data)
				jQuery("#newapi_order").trigger('reloadGrid');
				
				//jQuery("#newapi_order").expandSubGridRow(4);
				//jQuery("#newapi_order").collapseSubGridRow(row_id);

		}
	});
	return false;
};

function editRow(id) {
	if(id && id!==lastsel){ 
		grid_content.jqGrid('restoreRow',lastsel); 
		grid_content.jqGrid('editRow',id,true); 
		lastsel=id;
	}
	return false;
}


$(document).ready(function() {

	//dtgLoadButton();

	var grid = grid_content.jqGrid({   
		url: "index.php/<?php echo $link_controller;?>/get_data",
		editurl: "index.php/<?php echo $link_controller;?>/set_data",
		colModel: [ 
			{name:"id",key:true,hidden:true},
			{name:"inv_id",editable:true,hidden:true},
			{name:"inv_tgl",label:"Tgl Order",width:15,align:"center",formatter:'date',formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"d-M-Y H:i:s"},searchoptions:{dataInit:function(el){$(el).datepicker({dateFormat:'yy-mm-dd'});} }}, //searchoptions:{dataInit:function(el){$(el).datepicker({dateFormat:'yy-mm-dd'});}} },
			{name:"inv_code",label:"Invoice",width:5,align:"center"},
			{name:"inv_no",label:"Unix",width:3,align:"center"},
			{name:"grand_total_all",label:"Biaya",width:10,align:"right",formatter:'currency', formatoptions:{prefix:"Rp. ",thousandsSeparator:","}},	
			{name:"qty_total",label:"Pesan",width:5,align:"center"},
			{name:"on_status_all",label:'Order',width:10,align:'center',editable:false,edittype:'select',editoptions:{value:":Semua;0:Belum;1:Selesai",size:10,dataEvents:{}},editrules:{edithidden:true,required:true,integer:true}, stype:'select'},
			{name:"inv_status",label:'Status',width:10,align:'center',editable:true,edittype:'select',editoptions:{value:":Semua;0:Belum Dibayar;1:Lunas;2:Sudah Dikirim;3:Tunda;4:Batal;5:Expired",size:1,dataEvents:{}},editrules:{edithidden:true,required:true,integer:true}, stype:'select'},
			{name:"tiki_noresi",label:"No Resi",width:15,align:"center",editable:true,edittype:'text'},//,editrules:{edithidden:true,required:true,integer:true}},
			{name:"opsi",label:'Opsi',width:5, align:'center',sortable:false,search:false,title:false},
			{name:"hari",label:"Hari",width:3,align:"center"},
			{name:"inv_status_old",hidden:true},
			
			{name:"user_nama",hidden:true},
			{name:"user_alamat",hidden:true},
			{name:"user_pobox",hidden:true},
			{name:"user_telp",hidden:true},
			{name:"user_fuid",hidden:true},
			{name:"user_fb_site",hidden:true},
			{name:"sync_name",hidden:true},
			{name:"tiki_tariff",hidden:true},	
			{name:"tiki_paket",hidden:true},
				
		],
		gridComplete: function(){ 
			var id = grid_content.jqGrid('getDataIDs'); 
			for(var i=0;i < id.length;i++){ 
				var cl = id[i],
					inv_status = grid_content.getRowData(cl).inv_status,
					on_status_all = grid_content.getRowData(cl).on_status_all,
					arr_stat = new Array('Belum Dibayar','Lunas','Sudah Dikirim','Tunda','Batal','Hubungi Kami');
					arr_stat_all = new Array('Belum','Selesai');
					//btn_edit = '<input type="button" value="Edit" onclick="editRow('+cl+')" />';
					be = "<input title='Ubah' class='ui-icon ui-icon-pencil' style='margin:0 3px 0 0;float:left;height:22px;width:20px;' type='button' value='E' onclick=\"grid_content.editRow('"+cl+"');\" />"; 
					se = "<input title='Simpan' class='ui-icon ui-icon-check' style='margin:0 3px 0 0;float:left;height:22px;width:20px;' type='button' value='S' onclick=\"grid_content.saveRow('"+cl+"');\" />"; 
					ce = "<input title='Batal' class='ui-icon ui-icon-refresh' style='float:left;height:22px;width:20px;' type='button' value='C' onclick=\"grid_content.restoreRow('"+cl+"');\" />";
				grid_content.jqGrid('setRowData',cl,{inv_status:arr_stat[inv_status],inv_status_old:inv_status,on_status_all:arr_stat_all[on_status_all],opsi:be+se+ce});
			}
			return;
		},
		jsonReader : {
			root:"data",
			repeatitems: false
		},
		pager: "#pnewapi_order", 
		sortname: 'inv_tgl', 
		sortorder: "DESC",
		//viewrecords: true,
		loadError :function(xhr,status, err){ 
			try {
				jQuery.jgrid.info_dialog(jQuery.jgrid.errors.errcap,'<div class="ui-state-error">'+ xhr.responseText +'</div>', jQuery.jgrid.edit.bClose,{buttonalign:'right'});
			} 
			catch(e) { alert(xhr.responseText);} 
		},
		onSelectRow: function(id) {
			grid_content.jqGrid('GridToForm',id,"#order_form");
			var inv_status = grid_content.getRowData(id).inv_status_old;
			$('#order_form #inv_status').val(inv_status);
			//grid_content.toggleSubGridRow(id);
		},
		datatype:'json',
		rowNum:5,
		rowList:[5,10,20,30,40],
		rownumbers:true,
		hiddengrid:false,
		//autowidth:true,
		width: grid_content.parent().innerWidth(),
		forceFit:true,
		shrinkToFit:true,
		height:'75%',
		caption:'DAFTAR PEMESANAN',
		subGridOptions: { 
			"plusicon" : "ui-icon-triangle-1-e", 
			"minusicon" : "ui-icon-triangle-1-s", 
			"openicon" : "ui-icon-arrowreturn-1-e",
			//"reloadOnExpand" : false, 
			//"selectOnExpand" : true
		},
		subGrid: true,
		subGridRowExpanded: function(subgrid_id, row_id) { 
			var subgrid_table_id = subgrid_id+"_t", 
				pager_id = "p_"+subgrid_table_id,
				inv_id = grid_content.getRowData(row_id).inv_id,
				sub_grid_table = jQuery("#"+subgrid_table_id);
				
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"'></table><div id='"+pager_id+"'></div>"); 
			
			jQuery("#"+subgrid_table_id).jqGrid({   
				url: "index.php/<?php echo $link_controller;?>/get_data/barang",
				colModel: [ 
					{name:"id",key:true,hidden:true},
					{name:"inv_id",label:"inv_id",hidden:true,search:true,stype:'hidden',searchoptions:{searchhidden:true}},
					{name:"cart_id",hidden:true},
					{name:"dvd_id",hidden:true},
					{name:"kat_id",hidden:true},
					{name:"dvd_kode",label:"Kode",width:5,align:"center"},
					//{name:"kat_nama",label:"Kategori",width:10,align:"center"},
					{name:'kat_nama',label:'Kategori', width:10, surl: "index.php/<?php echo $link_controller?>/gridData_kategori", stype:'select'},
					{name:"dvd_nama",label:"Judul",width:20,align:"left"},
					{name:"dvd_jumlah",label:"Jml",width:3,align:"center"},
					{name:"qty",label:"Pesan",width:3,align:"right"}, 
					{name:"total_harga",label:"Harga",width:10,align:"right",formatter:'currency', formatoptions:{prefix:"Rp. ",thousandsSeparator:","}},	
					{name:"on_status",label:"Status",width:5,align:"left",editoptions:{value:":Semua;0:Tunda;1:Selesai",size:10},stype:'select'},	
					{name:"opsi",label:"Opsi",width:10,align:"left",sortable:false,search: false},	
				],
				gridComplete: function(){ 
					var id = jQuery("#"+subgrid_table_id).jqGrid('getDataIDs'); 
					for(var i=0;i < id.length;i++){ 
						var cl = id[i], button,
								cart_data = jQuery("#"+subgrid_table_id).getRowData(cl),
								arr_on_status = new Array('Tunda','Selesai'),
								btn_tunda = "<input type=\"button\" value=\"Tunda\" onclick=\"order_status('"+subgrid_table_id+"',"+row_id+",0,"+cart_data.cart_id+","+cart_data.dvd_id+")\" />",
								btn_selesai = "<input type=\"button\" value=\"Selesai\" onclick=\"order_status('"+subgrid_table_id+"',"+row_id+",1,"+cart_data.cart_id+","+cart_data.dvd_id+")\" />";
						
						button = btn_tunda + btn_selesai;
						if(cart_data.on_status == 1)
							button = btn_tunda;
				
						jQuery("#"+subgrid_table_id).jqGrid('setRowData',cl,{opsi:button,on_status:arr_on_status[cart_data.on_status]});
						button = '';
					}
				},
				jsonReader : {
					root:"data",
					repeatitems: false
				},
				postData:{'inv_id':inv_id},
				pager: pager_id, 
				sortname: 'dvd_nama', 
				sortorder: "ASC",
				//viewrecords: true,
				loadError :function(xhr,status, err){ 
					try {
						jQuery.jgrid.info_dialog(jQuery.jgrid.errors.errcap,'<div class="ui-state-error">'+ xhr.responseText +'</div>', jQuery.jgrid.edit.bClose,{buttonalign:'right'});
					} 
					catch(e) { alert(xhr.responseText);} 
				},
				onSelectRow: function(id) {
					return false;
				},
				ondblClickRow: function(id){
					return false;
				},
				datatype:'json',
				rowNum:5,
				rowList:[5,10,20],
				rownumbers:true,
				hiddengrid:false,
				//autowidth:true,
				width: grid_content.innerWidth() - 60,
				forceFit:true,
				shrinkToFit:true,
				height:'auto',
				//caption:'DAFTAR ORDER',
				footerrow : true,
				userDataOnFooter : true,
				toolbar: [true,"bottom"],
				loadComplete: function() {
					var ids = $("#"+subgrid_table_id).jqGrid('getDataIDs');
					
					if (ids.length) {
						var udata = $("#"+subgrid_table_id).getGridParam('userData');
						if (udata.qty_total != null)
							$("#t_"+subgrid_table_id).removeClass('ui-widget ui-state-default ui-widget-header ui-widget-content').html('<table width="100%">'+
								'<thead align="center" valign="bottom" class="ui-state-default">'+
								'	<tr>'+
								'		<td width="10%" rowspan=2>Order<br>DVD</td>'+
								'		<td width="30%" colspan=2>Total DVD</td>'+
								'		<td width="30%" colspan=2>Total Bonus</td>'+
								'		<td width="20%" rowspan=2>Grand<br>Total</td>'+
								'	</tr>'+
								'	<tr>'+
								'		<td width="10%">Jumlah</td>'+
								'		<td width="20%">Harga</td>'+
								'		<td width="10%">Jumlah</td>'+
								'		<td width="20%">Harga</td>'+
								'	</tr>'+
								'</thead>'+
								'<tbody align="center" valign="top">'+
								'	<tr align="right" class="ui-state-hover">'+
								'		<td>'+udata.qty_total+'</td>'+
								'		<td>'+udata.dvd_total+'</td>'+
								'		<td>Rp.'+inttocurr(udata.dvd_harga)+'</td>'+
								'		<td>'+udata.bonus_total+'</td>'+
								'		<td>Rp.'+inttocurr(udata.bonus_harga)+'</td>'+
								'		<td>Rp.'+inttocurr(udata.grand_total)+'</td>'+
								'	</tr>'+
								'</tbody>'+
							'</table>');
					}
				}
			}); 
			
			jQuery("#t_"+subgrid_table_id).css({"text-align":"center","height":"100%"}).addClass('tfont');
					
			// NAV BUTTON
			jQuery("#"+subgrid_table_id).jqGrid('navGrid',
				'#'+pager_id,
				{view:false,edit:false, add:false,del:false,search:false,refresh:false},
				{closeAfterEdit:true,mtype: 'POST'},
				{closeAfterAdd:true,mtype: 'POST'}, 
				{mtype: 'POST'}, 
				{sopt:['eq','cn','ge','le'],overlay:false,mtype: 'POST'}
			);
			
			jQuery("#"+subgrid_table_id).jqGrid('navButtonAdd',
				'#'+pager_id,
				{
					caption:"Search",
					title:"Toggle Search Toolbar",
					buttonicon :'ui-icon-pin-s', 
					onClickButton:function(){ 
						jQuery("#"+subgrid_table_id)[0].toggleToolbar() 
					} 
				}
			);
					
			jQuery("#"+subgrid_table_id)
				.jqGrid('navButtonAdd',
				'#'+pager_id,
				{
					caption:"Clear",
					title:"Clear Search",
					buttonicon :'ui-icon-refresh', 
					onClickButton:function(){ 
						jQuery("#"+subgrid_table_id)[0].clearToolbar() 
					} 
				}
			); 
			jQuery("#"+subgrid_table_id).jqGrid('filterToolbar',{
				stringResult: true,
				searchOnEnter : false
			})[0].toggleToolbar();
			
		}, 
		subGridRowColapsed: function(subgrid_id, row_id) { 
			var subgrid_table_id; 
			subgrid_table_id = subgrid_id+"_t"; 
			jQuery("#"+subgrid_table_id).remove(); 
		}	
		
	}); 
	
	grid_content.jqGrid('navGrid',"#pnewapi_order",{view:true,edit:false, add:false,del:false,search:false,refresh:false},
		{closeAfterEdit:true,mtype: 'POST'},
		{closeAfterAdd:true,mtype: 'POST'}, 
		{mtype: 'POST'}, 
		{sopt:['eq','cn','ge','le'],overlay:false,mtype: 'POST'}
	);
	
	grid_content.jqGrid('navButtonAdd',
		"#pnewapi_order",
		{
			caption:"Search",
			title:"Toggle Search Toolbar",
			buttonicon :'ui-icon-pin-s', 
			onClickButton:function(){ 
				grid_content[0].toggleToolbar() 
			} 
		}
	);
			
	grid_content
		.jqGrid('navButtonAdd',
		"#pnewapi_order",
		{
			caption:"Clear",
			title:"Clear Search",
			buttonicon :'ui-icon-refresh', 
			onClickButton:function(){ 
				grid_content[0].clearToolbar() 
			} 
		}
	); 
	grid_content.jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});//[0].toggleToolbar();
	
	$('#gs_hari, #gs_on_order').hide();
	
	//grid_content.jqGrid('gridResize',{minWidth:635,maxWidth:635,minHeight:80, maxHeight:350});
	/*
	$("#savedata").click(function(){ 
		var kirim_id= $("#order_form #kirim_id").val(); 
		if(kirim_id) { 
			grid_content.jqGrid('FormToGrid',kirim_id,"#order_form"); 
		} 
	})
	*/;
	
	$("#savedata").click(function(){ 
		var invid = jQuery("#inv_id").val(),
				noresi = jQuery("#tiki_noresi").val(); 
				status = jQuery("#inv_status").val(); 
		if(invid) {
			if(status == 2 && (noresi == ''||noresi == 0)) {
				alert('Nomor Resi belum diisi');
			} else {
				$.ajax({
					url: 'index.php/<?php echo $link_controller;?>/update_invoice',
					type: 'POST',
					data: $('#order_form').serialize(),
					success: function() {
						grid_content.trigger('reloadGrid');
					}
				});	
			}
		} else {
			alert('Invoice atau Status belum dipilih !!');
		}		
	});
	
});
</script>
<table id="newapi_order" class="scroll"></table>
<div id="pnewapi_order" class="scroll" style="text-align:center;"></div>
