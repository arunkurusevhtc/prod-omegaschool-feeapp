<?php
class pdf 
{
	static function convert($html,$file="",$forceCreate=""){
		include_once("fpdf/html2fpdf.php");
		$pdf=new HTML2FPDF("p");		
		$pdf->SetFont('Arial','',100);
		$pdf->SetMargins(6,10,10,10);
		// $pdf->UseCSS(true);
		$pdf->AddPage();
		if (preg_match("/MSIE/i", $_SERVER["HTTP_USER_AGENT"])){
	        header("Content-type: application/PDF");
	    } else {
	        header("Content-type: application/PDF");
	        header("Content-Type: application/pdf");
	    }
		$pdf->WriteHTML($html);
		
		/* Force to download on mobile and tablet devices */
		include_once('mobile_detect.php');
		$detect = new Mobile_Detect;		
		
		if($detect->isMobile() && $forceCreate != 1) {			
			if(strpos($file, '/') >= 0) {
				$file = end(explode('/', $file));
			} elseif(strpos($file, '\\') >= 0) {
				$file = end(explode('\\', $file));
			}

			$pdf->Output($file, 'D');
			
		} else {
			$pdf->Output($file);
		}		
		
	}
}
?>
