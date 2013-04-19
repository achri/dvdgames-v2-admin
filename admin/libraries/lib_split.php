<?php
class Lib_Split {
	private $CI;
	function __construct() {
		$this->CI =& get_instance();		
	}
		
	/* SPLIT 01.02.03 KE ARAY [LVL][PARENT][CAT_CODE][CAT_NAME] */
	function split_kode($kode) {
		$split_kode = explode('.',$kode);
		$kat_kode = '';
		$split_data = array();
		$lvl_hight = count($split_kode);
		for ($lvl = 1;$lvl<=$lvl_hight;$lvl++):
			$kat_kode .= ($lvl>1)?('.'.$split_kode[$lvl-1]):($split_kode[$lvl-1]);
			$this->CI->db->where('kat_kode',$kat_kode);
			$get_name = $this->CI->db->get('master_kategori');
			if($get_name->num_rows()>0):
				$get_names = $get_name->row();
				$split_data[$lvl][$split_kode[$lvl-1]][$kat_kode]=$get_names->kat_nama;
			endif;
		endfor;
		return $split_data;
	}
	
	/* USE TYPE LEVEL/PARENT/CAT_CODE/CAT_NAME to retive array data*/
	function set_split_kode($kode,$tipe){
		$split_data = $this->split_kode($kode);
		
		foreach ($split_data as $lvl=>$array_lvl):
			$tipe1[$lvl]=$lvl;
			foreach ($array_lvl as $single_kode=>$kode):
				$tipe2[$lvl]=$single_kode;
				foreach ($kode as $long_kode=>$kat_nama):
					$tipe3[$lvl]=$long_kode;
					$tipe4[$lvl]=$kat_nama;
				endforeach;
			endforeach;
		endforeach;
		
		switch($tipe):
			case 'level' : return $tipe1; break;
			case 'parent' : return $tipe2; break;
			case 'kat_kode' : return $tipe3; break;
			case 'kat_nama' : return $tipe4; break;
		endswitch;
		
	}
	
	function split_kategori($kat_id,$tipe="kat_nama")
	{
		$split = array();
		$split_tipe = '/';
		$ret = '';
		
		$kat_loop = $kat_id;
		for ($i = 1; $i <= 3; $i++):
			$where['kat_id'] = $kat_loop;
			$get_kategori = $this->CI->tbl_kategori->data_kategori($where);
			if ($get_kategori->num_rows() > 0):
				if ($tipe == "kat_nama")
					$data = $get_kategori->row()->kat_nama;
				elseif ($tipe == "kat_id")
					$data = $get_kategori->row()->kat_id;
				else
					$data = $get_kategori->row()->kat_kode;
				$split[] = $data;
				$kat_loop = $get_kategori->row()->kat_master;
			endif;
		endfor;
		
		if ($tipe == "kat_kode"):
			$split_tipe = '.';
			$ret = implode(array_splice($split,0,1));
		else:
			if (sizeOf($split) >= 3)
				$ret = implode($split_tipe,array_reverse($split));	
		endif;
		
		return $ret;
	}
	
	function set_json_view($cat_id) {
		if ($cat_id!=''):
			$pro_code = $this->tbl_produk->get_cat_code($cat_id);
		endif;
		
		// JSON STRUKTUR
		$json = '[{"parent":"parent"';		
		$level = $this->pro_code->set_split_code($pro_code,'level');
		$parent = $this->pro_code->set_split_code($pro_code,'parent');
		$cat_code = $this->pro_code->set_split_code($pro_code,'cat_code');
		$cat_name = $this->pro_code->set_split_code($pro_code,'cat_name');
		foreach($level as $lvl):
			$json .= ',"lv'.$lvl.'_code":"'.$parent[$lvl].'"';
			$json .= ',"lv'.$lvl.'_name":"'.$cat_name[$lvl].'"';
			$json .= ',"lv'.$lvl.'_catcode":"'.$cat_code[$lvl].'"';
		endforeach;
		
		if (count($level)>=3):
			$like['pro_code']=$cat_code[3];
			$get_pro = $this->tbl_produk->get_product(false,$like,$flexigrid=false,$sort='DESC');
			if ($get_pro->num_rows()>0):
				$pro_id = substr($get_pro->row()->pro_code,9,3)+1;
				$zero='';
				if(strlen($pro_id)>=1):
					$zero='00';	
				elseif (strlen($pro_id)==2):
					$zero='0';
				endif;
				$json .= ',"pro_idcode":"'.str_pad($pro_id,3,$zero,STR_PAD_LEFT).'"';
			else:
				$json .= ',"pro_idcode":"001"';
			endif;
			
		endif;
		$json .= '}]';
		return $json;
	}
	
}
?>