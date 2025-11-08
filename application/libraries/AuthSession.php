<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AuthSession {

    var $_rowId;

    public $ci;
	
	public function __construct() {
	
        $this->ci =& get_instance();  
		$this->ci->load->driver('session');
      
    }
	
	
	function checkAuthSession(){
   
		$rowId = $this->_rowId;
		$rowIdDecrypted = base64_decode($rowId);
		$rowIdDecryptedArr = explode("#`", $rowIdDecrypted);
		$siteSessionId = $this->ci->session->userdata('session_id');
		$output = array();
		
			if($siteSessionId == $rowIdDecryptedArr[0]){
				$output["tag"] = true;
				$output["data"] = $rowIdDecryptedArr;
			} else {
				$output["tag"] = true;
			}
			
		 return $output;	
	}
	
	
	public function is_logged_in($session_id_name = null) {
	
        if($session_id_name!=null){
            $this->session_login_id = $session_id_name;
        }
        
        if ($this->session_login_id == null)
            return false;
        else {
            $user = $this->ci->session->userdata($this->session_login_id);    
            return $user;
        }
    }
	
	
	public function is_logged($session_id_name = null) {
	
            $user = (bool) $this->ci->session->userdata($session_id_name);    
            return $user;

    }
	
public function islogged() {

if ($this->ci->session->userdata('role_id') !== FALSE) {
  echo 'Variable is set';
} else {
  echo 'Variable is not set';
} 

}
}

?>