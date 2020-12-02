<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require APPPATH . 'controllers/Common.php';
	
	class PdfController extends Common {

		public function index(){
			echo base_url();
			echo "<br>";
			echo "http://localhost/mbmc//assets/images/mbmc_offical/logo.png";
			echo "<br>";
		}

		// private function Header()
		// {
		// 	// Logo
		// 	$this->Image('logo.png',10,6,30);
		// 	// Arial bold 15
		// 	$this->SetFont('Arial','B',15);
		// 	// Move to the right
		// 	$this->Cell(80);
		// 	// Title
		// 	$this->Cell(30,10,'Title',1,0,'C');
		// 	// Line break
		// 	$this->Ln(20);
		// }

		function GenerateWord()
		{
			//Get a random word
			$nb=rand(3,10);
			$w='';
			for($i=1;$i<=$nb;$i++)
				$w.=chr(rand(ord('a'),ord('z')));
			return $w;
		}

		function GenerateSentence()
		{
			//Get a random sentence
			$nb=rand(1,10);
			$s='';
			for($i=1;$i<=$nb;$i++)
				$s.= $this->GenerateWord().' ';
			return substr($s,0,-1);
		}

		public function demand_note(){
			$this->load->library('PDF_MC_Table');
			$pdf = new FPDF("P", "mm","A3");
			$pdf_mc_pdf = new PDF_MC_Table();

			$pdf->AddPage();

			// Header
			$logopath = base_url().'/assets/images/mbmc_offical/logo.png';
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
			// End Header

			$pdf->Ln(10);
			$pdf->SetFont('Arial','B',14);
			$pdf->Cell(0,60,": Demand Note :", 0, 0, 'C');

			//start date
			$pdf->SetY( 50 );
			$pdf->SetX( 20 );
			$pdf->SetFont('Arial','B',16);
			//generate random nos.
			$pdf->Cell(0,60,"NO.MBMC/PWD/470/19-20", 0, 0, 'L');
			
			$pdf->SetY( 50 );
			// $pdf->SetX( 20 );
			$pdf->Cell(260, 60, "Date: ".date("d/m/yy"), 0, 0, 'R');
			//End Date

			$pdf->Ln(7);
			$pdf->SetX(20);
			$pdf->Cell(0, 80, "To,", 0, 0, 'L');
			$pdf->Ln(8);
			$pdf->SetX(20);
			$pdf->Cell(0, 80, "Company Name", 0, 0, 'L');
			
			$pdf->SetFont("Arial",'B',15);
			$pdf->SetY($pdf->getY()+45);
			$pdf->SetX(20);
			$pdf->MultiCell(100, 7, "Borivali Receiving Station, Near Magathane Depot, Tata Power House Road, Borivali (E), Mumbai 400066", 0, 'L');

			$pdf->Ln(1);
			$pdf->Cell(0,30, "Sub :- Trench On : Siddhivinayak Road, Nr.Lodha Aqua CHS.Mira Road (E)", 0, 0, 'C');

			$pdf->Ln(1);
			$pdf->Cell(0,60, "Ref:- DNMG/CNS/0278/20-21 Date - 04/07/2020", 0, 0, 'C');

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


			$pdf->AddPage();

			$pdf->Cell(250,70,"Annexure (A)",0,0,'C');

			$pdf->Ln(3);

			$pdf->SetFont('Arial','',12);

			$pdf_mc_pdf->SetWidths(array(30,50,30,40));
			
			for($i=0;$i<20;$i++)
				$pdf_mc_pdf->Row(array($this->GenerateSentence(),$this->GenerateSentence(),$this->GenerateSentence(),$this->GenerateSentence()));
	
			$pdf->Output();
		}

		function demand_note1(){
			$pdf = new FPDF("L","mm","Letter");
			$pdf->AliasNbPages();
			$pdf->AddPage();
			
			//header
			$pdf->SetMargins(0,6,0);
			$logopath = base_url().'/assets/images/mbmc_offical/logo.png';
			$pdf->Image($logopath,15,8,40);
			
			$pdf->SetFont('Helvetica','',20);
			
			$pdf->Cell(300,4,'MIRA BHAINDAR MUNCIPAL CORPORATION',0,0,'C');
			
			$pdf->Ln();
			$pdf->SetFont('Helvetica','',12);
			
			$pdf->Cell(300,19,"Indira Gandhi Bhavan, Chattrapati Shivaji Maharaj Marg.", 0,0,'C');
			$pdf->Ln(3);
			$pdf->Cell(300,28,"Bhainder(W), Dist - Thane", 0, 0, 'C');
			$pdf->Ln(3);
			$pdf->Cell(300,34,"Tel Nos. 022-28192828/ 28193028/ 28181183/ 28181353/ 28145985.", 0, 0, 'C');
			$pdf->Ln(3);
			$pdf->Cell(300,40,"Fax No. 022-28197636", 0, 0, 'C');
			$pdf->Ln(3);
			$pdf->SetLineWidth(1);
			$pdf->Line(10, 53, 300-20, 53);
			//End Header
			$pdf->Ln(3);
			//header center
			$pdf->SetFont('Arial','B',14);
			$pdf->Cell(300,60,": Demand Note :", 0, 0, 'C');
			//End Header center
			$pdf->Ln(3);

			//No and date
			$pdf->SetY( 40 );
			$pdf->SetX( 20 );
			//generate random nos.
			$pdf->Cell(300,75,"NO.MBMC/PWD/470/19-20", 0, 0, 'L');
			
			$pdf->SetY( 40 );
			// $pdf->SetX( 20 );
			$pdf->Cell(250, 75, "Date: ".date("d/m/yy"), 0, 0, 'R');
			//End No and date

			//address of company 
			$pdf->Ln(3);
			$pdf->SetY( 50 );
			$pdf->SetX( 20 );
			$pdf->Cell(300, 80, "To,", 0, 0, 'L');
			$pdf->Ln(3);
			$pdf->SetX( 20 );
			$pdf->Cell(300, 90, "Company Name,", 0, 0, 'L');
			$pdf->Ln(3);
			$pdf->SetX( 20 );
			$pdf->Cell(300, 100, "Company Address", 0, 0, 'L');
			//End address of company

			$pdf->Ln(3);

			//subject

			$pdf->SetY( 80 );
			// $pdf->SetX( 20 );
			$pdf->Cell(300,70, "Sub :- Trench On : Siddhivinayak Road, Nr.Lodha Aqua CHS.Mira Road (E)", 0, 0, 'C');

			$pdf->Ln(3);

			//Ref 
			$pdf->SetY( 90 );
			// $pdf->SetX( 20 );
			$pdf->Cell(300,70, "Ref:- DNMG/CNS/0278/20-21 Date - 04/07/2020", 0, 0, 'C');
			//END Ref
			$pdf->Ln(3);
			//paragraph body
			$pdf->SetX(20);
			$pdf->Cell(300,75, "Sir,", 0, 0, 'L');

			$pdf->Ln(3);

			$pdf->SetY( 150 );

			$pdf->Multicell(0, 50, "String", 0, "C");
			
			//end paragraph body
			$pdf->Output();
		}

	}
?>
