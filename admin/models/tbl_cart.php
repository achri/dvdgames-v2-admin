<?php if ( ! defined('BASEPATH')) exit($this->lang->line('basepath'));

class Tbl_cart extends CI_Model
{
	protected $obj;
	protected $cart_session;
	
  function __construct()
  {
		parent::__construct();
		$this->obj =& get_instance();
		$this->cart_session = $this->obj->session->userdata('cart_session');
		$this->cart_status = $this->obj->session->userdata('cart_status');
  }
	// DATA CART
	function get_cart($where)
	{
		if(is_array($where))
			foreach ($where as $field=>$value)
				$this->db->where($field,$value);
		else
			$this->db->where('cart_id',$where);
		
		return $this->db->get('cart');
	}
	
	// DATA CART
	function get_cart_detail($where)
	{
		if(is_array($where))
			foreach ($where as $field=>$value)
				$this->db->where($field,$value);
		else
			$this->db->where('cart_id',$where);
		
		return $this->db->get('cart_detail');
	}
	
  // DATA CART SESSION
  function get_data_cart()
  {
    $cart_session = $this->cart_session;
    $this->db->where('c.cart_session',$cart_session);
    $this->db->where('c.cart_status',0);
    $this->db->from('cart as c');
    $this->db->join('cart_detail as cd','cd.cart_id = c.cart_id');
    $this->db->join('master_dvd as dvd','dvd.dvd_id = cd.dvd_id');
		$this->db->order_by('dvd.dvd_nama');
    return $this->db->get();
  }
  // INSERT TO CART
  function add_to_cart($dvd_id)
  {
		// cart session
		$cart['cart_tgl'] = date('Y-m-d G:i:s');
    $cart['cart_session'] = $this->cart_session;
    $in_cart = $this->db->query('SELECT cart_id,cart_session from cart where cart_session="'.$cart['cart_session'].'" and cart_status = 0');
    
    if ($in_cart->num_rows() == 0):
      $this->db->insert('cart',$cart);
			$cart_id = $this->db->insert_id();
      $cart_detail['cart_id'] = $cart_id;
			$this->obj->session->set_userdata('cart_id',$cart_id); // cart_id session
    else:
			$cart_id = $in_cart->row()->cart_id;
      $cart_detail['cart_id'] = $cart_id;
    endif;  
		
		$this->obj->session->set_userdata('cart_id',$cart_id);
    
		// cart detail
    $cart_detail['dvd_id'] = $dvd_id;    
		$in_cart_detail = $this->db->query('SELECT cart_id,dvd_id from cart_detail where cart_id='.$cart_detail['cart_id'].' and dvd_id='.$dvd_id);
				
		if ($in_cart_detail->num_rows() == 0):
			$cart_detail['jml_dvd'] = $this->obj->tbl_dvd->get_detail_dvd($dvd_id)->row()->dvd_jumlah;
			$cart_detail['total_harga'] = $cart_detail['jml_dvd'] * 1 * 30000;
			return $this->db->insert('cart_detail',$cart_detail);
		endif;
  } 
	
	// DROP DVD DARI CART
	function drop_from_cart($where)
	{
		return $this->db->delete('cart_detail',$where);
	}
	
	// INFORMASI CART
	function list_cart_info()
	{
		$cart_session = $this->cart_session;
		$query = "
			select
			sum(qty) dvd_games,
			sum(jml_dvd) dvd_jumlah,
			concat('Rp.',format(sum(total_harga),2)) dvd_harga,
			floor(sum(jml_dvd)/5) bonus_jumlah,
			concat('Rp.',format(floor(sum(jml_dvd)/5)*30000,2)) bonus_harga,
			concat('Rp.',format(sum(total_harga) - floor(sum(jml_dvd)/5)*30000,2)) grand_total
			from cart_detail cd
			inner join cart c on c.cart_id = cd.cart_id and c.cart_session = '".$this->cart_session."' and cart_status = 0
		";
		return $this->db->query($query);
	}
	
	// SAVE CART
	function update_cart($where,$data)
	{
		if(is_array($where))
			foreach ($where as $field=>$value)
				$this->db->where($field,$value);
		else
			$this->db->where('cart_id',$where);
			
		return $this->db->update('cart',$data);
	}
	
	// SAVE CART
	function update_cart_detail($where,$data)
	{
		if(is_array($where))
			foreach ($where as $field=>$value)
				$this->db->where($field,$value);
		else
			$this->db->where('cart_id',$where);
			
		return $this->db->update('cart_detail',$data);
	}
	
	function delete_cart($where)
	{
		if(is_array($where))
			foreach ($where as $field=>$value)
				$this->db->where($field,$value);
		else
			$this->db->where('cart_id',$where);
			
		return $this->db->delete('cart');
	}
	
	function delete_cart_detail($where)
	{
		if(is_array($where))
			foreach ($where as $field=>$value)
				$this->db->where($field,$value);
		else
			$this->db->where('cart_id',$where);
			
		return $this->db->delete('cart_detail');
	}
}