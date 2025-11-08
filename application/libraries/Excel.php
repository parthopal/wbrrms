<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 *  ======================================= 
 *  Author     : Team Tech Arise 
 *  License    : Protected 
 *  Email      : info@techarise.com 
 * 
 *  ======================================= 
 */
require_once APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'PHPExcel.php';
require_once APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'IOFactory.php';

class Excel extends PHPExcel {

    public function __construct() {
        parent::__construct();
    }

}

?>