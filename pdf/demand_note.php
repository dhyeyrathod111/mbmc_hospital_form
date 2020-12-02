<?php 
	
    require('fpdf.php');
    include('conf.php');


class PDF extends FPDF
{

	var $widths;
	var $aligns;

	function SetWidths($w)
	{
	    //Set the array of column widths
	    $this->widths=$w;
	}

	function SetAligns($a)
	{
	    //Set the array of column alignments
	    $this->aligns=$a;
	}

	function Row($data, $status)
    {
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
			//Print the text
			$k = ($i + 1);
			
			if($status == 'header'){
				$this->Rect($x,$y,$w,$h+10);
				$this->SetFont('Arial','B',13);
				$this->MultiCell($w,7, $data[$i]."\n".$k,0,$a);
			}elseif($status == 'data'){
				$this->Rect($x,$y,$w,$h+4);
				$this->SetFont('Arial','',13);
				$this->MultiCell($w,12, $data[$i],0,$a);
			}else{
				$this->Rect($x,$y,$w,$h+4);
				$this->SetFont('Arial','B',13);
				$this->MultiCell($w,12, $data[$i],0,$a);
			}
            
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
		//Go to the next line
		if($status == 'header'){
			$this->Ln($h+10);
		}else{
			$this->Ln($h+4);
		}
    }

	function CheckPageBreak($h)
	{
	    //If the height h would cause an overflow, add a new page immediately
	    if($this->GetY()+$h>$this->PageBreakTrigger)
	        $this->AddPage($this->CurOrientation);
	}

	
	function NbLines($w,$txt)
	{
	    //Computes the number of lines a MultiCell of width w will take
	    $cw=&$this->CurrentFont['cw'];
	    if($w==0)
	        $w=$this->w-$this->rMargin-$this->x;
	    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	    $s=str_replace("\r",'',$txt);
	    $nb=strlen($s);
	    if($nb>0 and $s[$nb-1]=="\n")
	        $nb--;
	    $sep=-1;
	    $i=0;
	    $j=0;
	    $l=0;
	    $nl=1;
	    while($i<$nb)
	    {
	        $c=$s[$i];
	        if($c=="\n")
	        {
	            $i++;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	            continue;
	        }
	        if($c==' ')
	            $sep=$i;
	        $l+=$cw[$c];
	        if($l>$wmax)
	        {
	            if($sep==-1)
	            {
	                if($i==$j)
	                    $i++;
	            }
	            else
	                $i=$sep+1;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	        }
	        else
	            $i++;
	    }
	    return $nl;
	}
}

// Instanciation of inherited class
$pdf = new PDF("P", "mm","A3");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

            //get data of annexure
			$pwd_id = $_GET['id'];
			
			$applicationDataobj = mysqli_query($conn, "SELECT pd.*, (SELECT company_name FROM company_details WHERE company_id = pd.company_name) companys_name, (SELECT company_address FROM company_address WHERE address_id = pd.company_address) companys_address FROM pwd_applications pd WHERE pd.id = '".$pwd_id."'");
			$applicationData = mysqli_fetch_object($applicationDataobj);
			

// header
			$logopath = '../assets/images/mbmc_offical/logo.png';
			$pdf->Image($logopath,15,8,40);

			$pdf->SetFont('Helvetica','',20);

			$pdf->Cell(300,4,'MIRA BHAINDAR MUNCIPAL CORPORATION',0,0,'C');
			$pdf->Ln(2);

			$pdf->SetFont('Helvetica','',14);
			$pdf->Cell(300,19,"Indira Gandhi Bhavan, Chattrapati Shivaji Maharaj Marg.", 0,0,'C');
			$pdf->Ln(2);
			$pdf->Cell(300,28,"Bhainder(W), Dist - Thane", 0, 0, 'C');
			$pdf->Ln(2);
			$pdf->Cell(300,38,"Tel Nos. 022-28192828/ 28193028/ 28181183/ 28181353/ 28145985.", 0, 0, 'C');
			$pdf->Ln(2);
			$pdf->Cell(300,48,"Fax No. 022-28197636", 0, 0, 'C');

			$pdf->Ln(2);
			$pdf->SetLineWidth(1);
			$pdf->Line(10, 53, 300-15, 53);
            $pdf->Ln(10);
			$pdf->SetFont('Arial','B',14);
			$pdf->Cell(0,60,": Demand Note :", 0, 0, 'C');

			//start date
			$pdf->SetY( 50 );
			$pdf->SetX( 20 );
			$pdf->SetFont('Arial','B',16);
			//generate random nos.
			$pdf->Cell(0,60,"NO.MBMC/PWD/".$applicationData->app_id."/".date('Y')."-".date('y', strtotime('+1 year')), 0, 0, 'L');
			
			$pdf->SetY( 50 );
			// $pdf->SetX( 20 );
			$pdf->Cell(260, 60, "Date: ".date("d/m/yy"), 0, 0, 'R');
			//End Date

			$pdf->Ln(7);
			$pdf->SetX(20);
			$pdf->Cell(0, 80, "To,", 0, 0, 'L');
			$pdf->Ln(8);
			$pdf->SetX(20);
			$pdf->Cell(0, 80, $applicationData->companys_name, 0, 0, 'L');
			
			$pdf->SetFont("Arial",'B',15);
			$pdf->SetY($pdf->getY()+45);
			$pdf->SetX(20);
			$pdf->MultiCell(100, 7, $applicationData->companys_address, 0, 'L');

			$pdf->Ln(1);
			$pdf->Cell(0,30, "Sub :- Trench On : ".$applicationData->road_name, 0, 0, 'C');

			$pdf->Ln(1);
			$pdf->Cell(0,60, "Ref:- DNMG/CNS/".$applicationData->app_id."/".date('Y')."-".date('y', strtotime('+1 year'))." Date - ".date("d/m/Y"), 0, 0, 'C');

			$pdf->SetY($pdf->GetY()+20);
			$pdf->SetX(20);
			$pdf->Cell(300,75, "Sir,", 0, 0, 'L');
			$pdf->Ln(2);

			$pdf->SetY($pdf->GetY()+46);
			$pdf->SetX(20);
			$pdf->MultiCell(0, 8, "           With reference to your above referred letter , the above mentioned site was visited & your request to take trench has been accepted on certain terms & condition and subject to payment of charges as calculated and shown in annexure A and B.", 0, 'L');

			$pdf->Ln(4);

			$pdf->SetX(20);
			$pdf->MultiCell(0, 8, "           You shall make payment within seven days from receipt of this demand note, failing which this demand note shall become invalid. On making payment of all charges and taxes, you shall present the copy of receipt with the corporation so that necessary permission can be issued to you.", 0, 'L');

			$pdf->Ln(4);
			$pdf->SetX(20);
			$pdf->MultiCell(0, 8, "Further, it is to mention that payment of reinstatement charges does not guaranteed the grant of permission.", 0, 'L');

			$pdf->Ln(4);
			$pdf->SetY($pdf->GetY()+30);

			$pdf->Cell(250,50,"(Deepak Khambit)",0,0,'R');

			$pdf->Ln(3);
			$pdf->Cell(250,60,"Executive Engineer (PWD)",0,0,'R');

			$pdf->Ln(4);
			$pdf->Cell(250,70,"Mira Bhainder Muincipal Corporation",0,0,'R');

			$pdf->Ln(10);
			$pdf->SetX(20);
            $pdf->Cell(250,75,"GST ID 27AAALM0855BIZE",0,0,'L');
            
            $pdf->AddPage("L");
			

            // $header_array = array('Sr No', 'Name Of Road', 'Type Of Surface','Length in Mt.DLP' , 'RM Rate in Rs.', 'Multiplication Factor', 'RI Charges', 'Security Deposit 10% (Refundable)', 'Land Rent', 'CGST @9%', 'SGST @9%', 'Total GST', 'Grand Total');
            $header_array = array('Sr No', 'Name Of Road', 'Type Of Surface','Length in Mt.DLP' , 'RM Rate in Rs.', 'Multiplication Factor', 'RI Charges', 'Supervision Fees', 'Total RI Charges', 'Security Deposit 10% (Refundable)', 'Land Rent', 'CGST @9%', 'SGST @9%', 'Total GST', 'Grand Total');

	       // $pdf->SetWidths(array(15,45,40,20,27,20,30,30,30,30,30,30,30,30));
	       $pdf->SetWidths(array(15,45,40,20,24,20,22,30,20,31,20,30,30,30,30,30));

			
			$annexureData = mysqli_query($conn, "SELECT rid.*, (SELECT road_title FROM road_type WHERE road_id = rid.road_type_id) road_type, (SELECT road_name FROM pwd_applications WHERE id = rid.pwd_app_id) road_name, (rid.total_ri_charges + rid.security_deposit + rid.total_gst) grand_total FROM road_information_pwd rid WHERE pwd_app_id = '".$pwd_id."' AND status = 1");
			
			// $rowPwd = mysqli_fetch_row($annexureData);
			
			$sr_no = 1;
			
			while($rowPwd = mysqli_fetch_object($annexureData)){
				// $data_array[] = array($sr_no, $rowPwd->road_name, $rowPwd->road_type, $rowPwd->total_length, $rowPwd->surface_rate, $rowPwd->mul_factor, $rowPwd->ri_chargers, $rowPwd->security_deposit, $rowPwd->land_rant, $rowPwd->cgst, $rowPwd->sgst, ($rowPwd->cgst + $rowPwd->sgst), $rowPwd->grand_total);
				$data_array[] = array($sr_no, $rowPwd->road_name, $rowPwd->road_type, $rowPwd->total_length, $rowPwd->surface_rate, $rowPwd->mul_factor, round($rowPwd->ri_chargers,2), round($rowPwd->supervision_charges,2), round($rowPwd->total_ri_charges,2), round($rowPwd->security_deposit,2), round($rowPwd->land_rant,2), round($rowPwd->cgst,2), round($rowPwd->sgst,2), round(($rowPwd->cgst + $rowPwd->sgst),2), round($rowPwd->grand_total,2));
				$sr_no++;
			}
			
			//table Header
			$pdf->Row($header_array,'header');
			
			//Table Content
			foreach ($data_array as $key_data => $value_data) {
				$pdf->Row($value_data,'data');
			}

			//total Array
// 			$sumData = mysqli_query($conn, "SELECT SUM(rid.ri_chargers + rid.supervision_charges) total_ri, SUM(rid.security_deposit) total_security, SUM(rid.land_rant) total_rent, SUM(rid.cgst) total_cgst, SUM(rid.sgst) total_sgst, SUM(rid.cgst + rid.sgst) total_gst, SUM((rid.total_ri_charges + rid.security_deposit + rid.total_gst)) total_grand_total FROM road_information_pwd rid WHERE pwd_app_id = '".$pwd_id."' AND status = 1");
            $sumData = mysqli_query($conn, "SELECT SUM(rid.ri_chargers) ri_total,SUM(rid.ri_chargers + rid.supervision_charges) total_ri, SUM(rid.supervision_charges) supervision, SUM(rid.security_deposit) total_security, SUM(rid.land_rant) total_rent, SUM(rid.cgst) total_cgst, SUM(rid.sgst) total_sgst, SUM(rid.cgst + rid.sgst) total_gst, SUM((rid.total_ri_charges + rid.security_deposit + rid.total_gst)) total_grand_total FROM road_information_pwd rid WHERE pwd_app_id = '".$pwd_id."' AND status = 1");


			while($sumPwd = mysqli_fetch_object($sumData)){
				// $dat_array = array('Total', '', '', '', '', '', $sumPwd->total_ri, $sumPwd->total_security, $sumPwd->total_rent, $sumPwd->total_cgst, $sumPwd->total_sgst, $sumPwd->total_gst, $sumPwd->total_grand_total);
                $dat_array = array('Total', '', '', '', '', '', round($sumPwd->ri_total,2), round($sumPwd->supervision,2), round($sumPwd->total_ri,2), round($sumPwd->total_security,2), round($sumPwd->total_rent,2), round($sumPwd->total_cgst,2), round($sumPwd->total_sgst,2), round($sumPwd->total_gst,2), round($sumPwd->total_grand_total,2));
			}

			$pdf->Row($dat_array, 'total');

			$pdf->Ln(4);
			$pdf->SetY($pdf->GetY()+30);

			$pdf->Cell(380,50,"(Deepak Khambit)",0,0,'R');

			$pdf->Ln(3);
			$pdf->Cell(380,60,"Executive Engineer (PWD)",0,0,'R');

			$pdf->Ln(4);
			$pdf->Cell(380,70,"Mira Bhainder Muincipal Corporation",0,0,'R');

			$pdf->Ln(6);
			$pdf->Cell(0, 95, "1) Take Note that it is responsibility of your company / firm to pay GSR (Shown in coloumn No. 10, 11 & 12) as raised under Reserve Charge Mechanism.");

			$pdf->Ln(6);
			$pdf->Cell(0, 105, "2) To submit the copy of challan of said payment of GST month wise with the corporation.");

			$pdf->Ln(7);
			$pdf->Cell(0, 115, "3) Payment (Except Shown in column no 10, 11 & 12) to be made by way of cheque / DD to be drawn in favour of 'Mira-Bhainder Municipal Corporation'.");

// 	$pdf->Output();
	$filename = "../uploads/demand_note/demand_note_".$pwd_id.".pdf";
	$pdf->Output($filename,'F');
	$data = array('success' => 1);
	echo json_encode($data);
	
?>