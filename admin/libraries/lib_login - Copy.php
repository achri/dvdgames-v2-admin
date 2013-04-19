<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 @author		Achri
 @date creation	
 @model
	- 
 @view
	- 
 @library
    - JS		
    - PHP
 @comment
	- 
*/

class Lib_login 
{
	private $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('tbl_user');
	}
	
	// @function: cek session logged_in
	// @access	: public
	// @return	: void
	function is_logged_in()
	{
		if ($this->CI->session):
			if ($this->CI->session->userdata('logged_in')):
				return TRUE;
			else:
				return FALSE;
			endif;
		else:
			return FALSE;
		endif;
	} 
	
	function login_step1()
	{		
		$usr_login = $this->CI->input->post('usr_id');
		$usr_pswd1 = md5($this->CI->input->post('usr_pwd'));
		
		$where['usr_login'] = $usr_login; 
		$where['usr_pwd1'] = $usr_pswd1;
		
		$get_user = $this->CI->tbl_user->data_user($where);
		
		if ($get_user->num_rows() > 0):
			
			$usr_id = $get_user->row()->usr_id;
			
			$sess_data = array(
				'usr_id'=>$usr_id, 
				'usr_login'=>$usr_login, 
				'login_number'=> 1
			);
			$this->CI->session->set_userdata($sess_data);
			
			redirect($this->CI->link_controller.'/second_login/','location');
		else:
			redirect($this->CI->link_controller.'/login_fail/');
		endif;
		
		return $passed;
	}
	
	function login_step2()
	{
		$usr_pswd2 = md5($this->CI->input->post('usr_pwd'));
		
		$usr_id = $this->CI->session->userdata('usr_id');
		$usr_login = $this->CI->session->userdata('usr_login');
		//$sess_id = $this->CI->session->userdata('session_id');
		
		$where['usr_id'] = $usr_id;
		$where['usr_login'] = $usr_login; 
		$where['usr_pwd2'] = $usr_pswd2;
		
		$get_user = $this->CI->tbl_user->data_user($where);
		//If username and password match set the logged in flag in 'ci_sessions'
		if ($get_user->num_rows() > 0)
		{
			//GET IP
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])):
				$ips = $_SERVER["HTTP_X_FORWARDED_FOR"];
			else:
				$ips = $_SERVER['REMOTE_ADDR'];
			endif;
			
			//GET DATA USER
			$data_user = $get_user->row();
			$lastTime_log = $data_user->lastTime_log;
			$lastIP_log = $data_user->lastIP_log;
			$ucat_id = $data_user->ucat_id;
			$usr_nama = $data_user->usr_nama;
			
			if($lastTime_log=='')
				$lastTime_log = date('Y-m-d H:i:s');
			if($lastIP_log=='')
				$lastIP_log = $ips;
			
			$sess_data = array(
				'usr_nama'=>$usr_nama,
				'usr_login'=>$usr_login, 
				'ucat_id'=>$ucat_id, 
				'login_number'=> 0, 
				'logged_in'=> 1,
			);
			
			//GET AKSES
			/*
			$get_akses = $this->CI->db->query("select * from master_akses where ucat_id = ".$ucat_id);
			if ($get_akses->num_rows() > 0):
				$sess_data += array('usr_akses'=>$get_akses->row()->ucat_nama,'usr_alias'=>$get_akses->row()->ucat_alias);
			endif;
			
			*/
			
			$this->CI->session->set_userdata($sess_data);
			
			// SAVE LOGGING USER
			
			$newIP		   = $ips;
			
			$data['lastTime_log'] = $lastTime_log;
			$data['lastIP_log'] = $lastIP_log;
			$data['newTime_log'] = date('Y-m-d H:i:s');
			$data['newIP_log'] = $newIP;
			
			$this->CI->db->where('usr_id',$usr_id);
			if ($this->CI->db->update('sys_user',$data)):
				//On success redirect user to default page
				//if ($nav = $this->CI->session->userdata('usr_nav')):
				//	redirect($nav);
				//else:
					redirect('','location');
				//endif;
			endif;
		}
		else
		{
			$sess_data = array(
				'usr_login' => '',
				'login_number'=> ''
			);
			$this->CI->session->unset_userdata($sess_data);
			redirect($this->CI->link_controller.'/login_fail/');
		}
	}
	
	function log_out(){
		$usr_id = $this->CI->session->userdata('usr_id');
		
		//GET IP
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])):
			$ips = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else:
			$ips = $_SERVER['REMOTE_ADDR'];
		endif;
			
		// DESTROY ALL SESSION IN DATABASE
		$this->CI->session->sess_destroy();
		
		// SAVE LOGOUT USER
		$newOffTime_log = date('Y-m-d H:i:s');
		$newOffIP_log   = $ips;
		
		$data['offTime_log'] = $newOffTime_log;
		$data['offIP_log'] = $newOffIP_log;
		
		$this->CI->db->where('usr_id',$usr_id);
		if ($this->CI->db->update('sys_user',$data)):
			//redirect('','location');
			echo "<script language='javascript'>location.href = 'index';</script>";
		endif;
	}
}

/* End of file lib_login.php */
/* Location: ./application/libraries/lib_login.php */