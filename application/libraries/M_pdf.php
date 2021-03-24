<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once __DIR__ . '/mpdf/vendor/autoload.php';
/*
Requires
    php: ^5.6 || ~7.0.0 || ~7.1.0 || ~7.2.0 || ~7.3.0
    ext-gd: *
    ext-mbstring: *
    psr/log: ^1.0
    setasign/fpdi: 1.6.*
    paragonie/random_compat: ^1.4|^2.0|9.99.99
    myclabs/deep-copy: ^1.7

Requires (Dev)
    phpunit/phpunit: ^5.0
    mockery/mockery: ^0.9.5
    squizlabs/php_codesniffer: ^2.7.0
    tracy/tracy: ^2.4

Suggests
    ext-bcmath: Needed for generation of some types of barcodes
    ext-zlib: Needed for compression of embedded resources, such as fonts
    ext-xml: Needed mainly for SVG manipulation
*/

class m_pdf {
	public $param;
	public $pdf;
	public function __construct($param = ['mode' =>'utf8', 'format' => 'A4']){
		$this->param = $param;
		$this->pdf = new \Mpdf\Mpdf($this->param);
	}
	
	function generate_pdf($html, $file_path_and_name=null){
	    #$this->param = "";
	    $this->pdf->mirrorMargins = true;
	    $this->pdf->SetDisplayMode('fullpage');
			$this->pdf->autoScriptToLang = true;
			$this->pdf->autoLangToFont = true;
			
	    $this->pdf->WriteHTML($html);
	    
	    if($file_path_and_name){
	    	$this->pdf->Output($file_path_and_name, 'F');
	    	return true;
	    }
	    
	    return $this->pdf->Output('', 'S');
	}
}
?>