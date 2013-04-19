<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	13/12/2010
 @model
	- dynatree_model
	- tbl_tiki
 @view
	- main_view
	- kategori_list_view
	- kategori_form_view
	- kategori_add_view
 @library
    - JS
		- dynatree
		- jquery.form
    - PHP
 @comment
	- 
	
*/

class Master_order extends DVD_Controller {
	public static $link_view, $link_controller;
	
	private $harga_dvd;
	// constructor
	function __construct () 
	{
		parent::__construct();	
	
		$class = get_class($this);
		
		$this->_loader_class();
		
		$output = array();
		$output += $this->_content_init();
		$output += $this->_public_init();
		$output += $this->_variable_init();
		
		$this->load->vars($output);
		
		log_message('debug', "IMP -> Class $class init success");
	}
	
	// @info	: Loader class model,helper,config,library
	// @params	: null
	// @return	: null
	function _loader_class()
	{
		$this->load->library(array(
			
		));
		$this->load->model(array(
			"tbl_kategori","tbl_invoice","tbl_cart","jqgrid_model",
		));
		return false;
	}
	
	// @info	: Extra Sub Header Content for JS & CSS
	// @access	: private
	// @params	: null
	// @return	: array	
	function _content_init()
	{
		$content = "";
		
		$arrayCSS = array (
		);
		
		$arrayJS = array (		
			'asset/js/helper/currency.js'
		);
		
		if (is_array($arrayCSS))
		foreach ($arrayCSS as $css):
			$content .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url().$css."\"/>\n";
		endforeach;
		
		if (is_array($arrayJS))
		foreach ($arrayJS as $js):
			$content .= "<script type=\"text/javascript\" src=\"".base_url().$js."\"/></script>\n";
		endforeach;
		
		// BIND OPTIONAL JS HERE =========>
		$content .= "";
		
		$output['extraSubHeadContent'] = $content;
		
		return $output;
	}
	
	// @info	: Load public variable
	// @access	: private
	// @params	: null
	// @return	: array
	function _public_init()
	{
		// public variable
		self::$link_controller = 'mod_master/master_order';
		self::$link_view = 'mod_master/master_order';
		$output['link_view'] = self::$link_view;
		$output['link_controller'] = self::$link_controller;	
		
		return $output;
	}
	
	// @info	: Load local variable
	// @access	: private
	// @params	: null
	// @return	: array
	function _variable_init()
	{
		$this->harga_dvd = $this->config->item('harga_dvd');
		// private variable
		$output['page_title'] = "Master Order";
		
		return $output;
	}
	
	// @info	: Populate data json to grid
	// @access	: public
	// @params	: POST string
	// @params	: string	$tiki_id
	// @return	: JSON array string
	public function get_data($type='trace')
	{
		if ($type=='trace'):			
			$table = "invoice";		
			$rs = $this->jqgrid_model->get_data($table,FALSE,FALSE,FALSE,array(
				array(
					'table'=>'user',
					'join'=>'user.user_id = '.$table.'.user_id',
					'fields'=>array('user_nama','user_email','user_alamat','user_pobox','user_telp','user_fuid','user_fb_site'),
					'type'=>'INNER'
				),
				array(
					'table'=>'master_sync_jne',
					'join'=>'master_sync_jne.sync_id = '.$table.'.sync_id',
					'fields'=>array('sync_name','sync_ss','sync_reg','sync_oke','sync_yes'),
					'type'=>'INNER'
				),
				array(
					'table'=>'cart',
					'join'=>'cart.cart_id = '.$table.'.cart_id AND cart.cart_status = 2',
					'fields'=>array('inv_id','cart_tgl','qty_total','dvd_total','dvd_harga','bonus_total','bonus_harga','grand_total','cart_status'),
					'type'=>'INNER'
				),
			),array(
				'*',
				'(SELECT DATEDIFF(NOW(),inv_tgl)) as hari',
				'(select IF(sum(on_status) < count(on_status),"Belum","Selesai")
					from cart_detail where cart_id = '.$table.'.cart_id
				) as on_order'
			),FALSE,FALSE);
		else:
			$inv_id = $this->input->get_post('inv_id');
			$table = "cart_detail";		
			$rs = $this->jqgrid_model->get_data($table,FALSE,FALSE,FALSE,array(
				array(
					'table'=>'cart',
					'join'=>'cart.cart_id = '.$table.'.cart_id AND cart.inv_id = '.$inv_id.' AND cart.cart_status = 2',
					'fields'=>array('inv_id','cart_tgl','qty_total','dvd_total','dvd_harga','bonus_total','bonus_harga','grand_total','cart_status'),
					'type'=>'INNER'
				),
				array(
					'table'=>'master_dvd',
					'join'=>'master_dvd.dvd_id = '.$table.'.dvd_id',
					'fields'=>array('kat_id','dvd_nama'),
					'type'=>'INNER'
				),
				array(
					'table'=>'master_kategori',
					'join'=>'master_kategori.kat_id = master_dvd.kat_id',
					'fields'=>array('kat_id','kat_nama'),
					'type'=>'INNER'
				)
			),array(
				'*',
				/*'(CASE on_status
					WHEN 0 THEN "Pilih Opsi"
					WHEN 1 THEN "Selesai" 
					WHEN 2 THEN "Tunda" 
				END) as on_status'
				*/
			),FALSE,FALSE,
			array(
				'qty_total'=>'qty_total',
				'dvd_total'=>'dvd_total',
				'dvd_harga'=>'dvd_harga',
				'bonus_total'=>'bonus_total',
				'bonus_harga'=>'bonus_harga',
				'grand_total'=>'grand_total',
			));
			
		endif;
		
		if (false == empty($rs['raw_data']))
			unset($rs['raw_data']);
		echo json_encode($rs);
	}
	
	// @info	: Manipulate data from grid
	// @access	: public
	// @params	: POST string
	// @params	: string	$tiki_id
	// @return	: JSON array string
	public function set_data()
	{
		$where['inv_id'] = $this->input->get_post('inv_id');
		$table = "invoice";
		$this->jqgrid_model->set_data($table,false,$where);
	}
	
	// @info	: Indexing Layout
	// @access	: public
	// @params	: null
	// @return	: [object]
	function index() {
		$output[''] = '';
		
		$this->load->view(self::$link_view."/order_main_view",$output);
	}
	
	function update_invoice()
	{
		$where['inv_id'] = $this->input->post('inv_id');
		
		$inv_status = $this->input->post('inv_status_old');
		$set_inv_status = $this->input->post('inv_status');
		if ($inv_status != $set_inv_status)
			$data['inv_status'] = $set_inv_status;
			
		$data['tiki_noresi'] = $this->input->post('tiki_noresi');
		$this->tbl_invoice->update_invoice($where,$data);
	}
	
	function gridData_kategori()
	{
		echo "<select><option value=''>SEMUA</option>";
		$get_kategori = $this->tbl_kategori->data_kategori();
		if ($get_kategori->num_rows() > 0) 
		{
			foreach($get_kategori->result() as $rows)
				echo "<option value='".$rows->kat_nama."'>".$rows->kat_nama."</option>";
		} else 
			echo "<option value=''>Kosong</option>";
			
		echo "</select>";
	}
	
	function set_order_status()
	{
		$where['cart_id'] = $this->input->post('cart_id');
		$where['dvd_id'] = $this->input->post('dvd_id');
		$data['on_status'] = $this->input->post('status');
		if ($this->tbl_cart->update_cart_detail($where,$data)) {
			// count on_status in cart and update cart on_status_all
			unset($where['dvd_id'],$data['on_status']);
			if ($this->db->query('select IF(sum(on_status) >= count(on_status),true,false) as status from cart_detail where cart_id='.$where['cart_id'])->row()->status) {
				$data['on_status_all'] = 1;
				echo "selesai";
			} else 
				$data['on_status_all'] = 0;
			
			$this->tbl_cart->update_cart($where,$data);
		}
	}
}

/* End of file master_tiki.php */
/* Location: ./app/controllers/mod_master/master_tiki.php */