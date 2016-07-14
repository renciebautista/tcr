<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Excel;
use App\AuditStore;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\Style\Color;
class ExportController extends Controller
{
    public function index(){
    	return view('export.store');	
    }

    public function exportxlsx(){    	
		$filename = "Store Report";
    	$stores = AuditStore::exportStore();	
    	set_time_limit(0); 				
		
		$writer = WriterFactory::create(Type::XLSX);		
		$writer->openToBrowser($filename.'.xlsx'); 	

		$style_title = (new StyleBuilder())           
           ->setFontSize(16)
           ->setFontName('Calibri')
           ->setFontColor(Color::BLACK)           
           ->build();

       $style_header = (new StyleBuilder())           
           ->setFontSize(14)
           ->setFontName('Calibri')
           ->setFontColor(Color::BLACK)           
           ->build();	

        $style_details = (new StyleBuilder())           
           ->setFontSize(11)
           ->setFontName('Agency FB')
           ->setFontColor(Color::BLACK)           
           ->build();	
		$writer->addRowWithStyle(array('','','','','','','','','STORE REPORTS'),$style_title);
		$writer->addRowWithStyle(array('Month','Account','Customer Code','Customer','Area','Region Code','Remarks','Distributor Code','Distributor','Store Code','Store Name','Channel Code','Template','Agency Code','Agency Description','User','PJP','Frequency'),$style_header);
		$writer->addRow(array(''));		
		foreach ($stores as $s) {	
			$details[0] = $s->description;				
			$details[1] = $s->account;
			$details[2] = $s->customer_code;
			$details[3] = $s->customer;
			$details[4] = $s->area;
			$details[5] = $s->region_code;
			$details[6] = $s->remarks;
			$details[7] = $s->distributor_code;
			$details[8] = $s->distributor;
			$details[9] = $s->store_code;			
			$details[10] = $s->store_name;
			$details[11] = $s->channel_code;
			$details[12] = $s->template;
			$details[13] = $s->agency_code;
			$details[14] = $s->agency_description;
			$details[15] = $s->name;
			$details[16] = $s->pjp;
			$details[17] = $s->freq;				
			$writer->addRowWithStyle($details,$style_details);
		}

		$sheet = $writer->getCurrentSheet();
		$sheet->setName('STORES');
		$writer->close();  
    }
    public function exportcsv(){    	
		$filename = "Store Report";
    	$stores = AuditStore::exportStore();	
    	set_time_limit(0); 				
		
		$writer = WriterFactory::create(Type::CSV);		
		$writer->openToBrowser($filename.'.csv'); 	

		$style_title = (new StyleBuilder())           
           ->setFontSize(16)
           ->setFontName('Calibri')
           ->setFontColor(Color::BLACK)           
           ->build();

       $style_header = (new StyleBuilder())           
           ->setFontSize(14)
           ->setFontName('Calibri')
           ->setFontColor(Color::BLACK)           
           ->build();	

        $style_details = (new StyleBuilder())           
           ->setFontSize(11)
           ->setFontName('Agency FB')
           ->setFontColor(Color::BLACK)           
           ->build();	
		$writer->addRowWithStyle(array('','','','','','','','','STORE REPORTS'),$style_title);
		$writer->addRowWithStyle(array('Month','Account','Customer Code','Customer','Area','Region Code','Remarks','Distributor Code','Distributor','Store Code','Store Name','Channel Code','Template','Agency Code','Agency Description','User','PJP','Frequency'),$style_header);
		$writer->addRow(array(''));		
		foreach ($stores as $s) {	
			$details[0] = $s->description;				
			$details[1] = $s->account;
			$details[2] = $s->customer_code;
			$details[3] = $s->customer;
			$details[4] = $s->area;
			$details[5] = $s->region_code;
			$details[6] = $s->remarks;
			$details[7] = $s->distributor_code;
			$details[8] = $s->distributor;
			$details[9] = $s->store_code;			
			$details[10] = $s->store_name;
			$details[11] = $s->channel_code;
			$details[12] = $s->template;
			$details[13] = $s->agency_code;
			$details[14] = $s->agency_description;
			$details[15] = $s->name;
			$details[16] = $s->pjp;
			$details[17] = $s->freq;				
			$writer->addRowWithStyle($details,$style_details);
		}

		$sheet = $writer->getCurrentSheet();
		$sheet->setName('STORES');
		$writer->close();  
    }
}

//  	Excel::create($filename, function($excel) use($stores,$filename){

			// $excel->sheet('Stores', function($sheet) use($stores,$filename) {
			//  	$sheet->setBorder('A2:R2', 'thin');			 	
			//  	$sheet->setAutoSize(true);						
			// 	$sheet->setStyle(array(
			// 	    'font' => array(
			// 	        'name'      =>  'Calibri',
			// 	        'size'      =>  15,
			// 	        'bold'      =>  true
			// 	    )
			// 	));
			//  	$sheet->row(1, array(
			// 		     '','','','','','','','','','STORES REPORT'
			// 		));
			//  	$sheet->cells('J1', function($cells) {
			// 	    $cells->setBackground('#27ae60');
			// 	});
			//  	$sheet->row(2, array(
			// 		     'Month','Account','Customer Code','Customer','Area','Region Code','Remarks','Distributor Code','Distributor','Store Code','Store Name','Channel Code','Template','Agency Code','Agency Description','User','PJP','Frequency'
			// 		));
			//  		$sheet->cells('A2:R2', function($cells) {
			// 	    $cells->setBackground('#27ae60');
			// 	});
			//  	$sheet->setStyle(array(
			// 	    'font' => array(
			// 	        'name'      =>  'Calibri',
			// 	        'size'      =>  15,
			// 	        'bold'      =>  true
			// 	    )
			// 	));
			// 	$counter = 3;								

			//  	foreach($stores as $s){				 	
			// 	 	$sheet->row($counter, array(
			// 	 		$s->description,
			// 	 		$s->account,
			// 	 		$s->customer_code,
			// 	 		$s->customer,
			// 	 		$s->area,
			// 	 		$s->region_code,
			// 	 		$s->remarks,
			// 	 		$s->distributor_code,
			// 	 		$s->distributor,
			// 	 		$s->store_code,
			// 	 		$s->store_name,
			// 	 		$s->channel_code,
			// 	 		$s->template,
			// 	 		$s->agency_code,
			// 	 		$s->agency_description,
			// 	 		$s->name,
			// 	 		$s->pjp,					
			// 	 		$s->freq,
			//  		));

			//  		$sheet->setBorder('A'.$counter, 'thin'); 
			// 	 	$sheet->setBorder('B'.$counter, 'thin'); 
			// 	 	$sheet->setBorder('C'.$counter, 'thin');
			// 	 	$sheet->setBorder('D'.$counter, 'thin'); 
			// 	 	$sheet->setBorder('E'.$counter, 'thin'); 
			// 	 	$sheet->setBorder('F'.$counter, 'thin'); 
			// 	 	$sheet->setBorder('G'.$counter, 'thin'); 	
			// 	 	$sheet->setBorder('H'.$counter, 'thin'); 
			// 	 	$sheet->setBorder('I'.$counter, 'thin');
			// 	 	$sheet->setBorder('J'.$counter, 'thin');
			// 	 	$sheet->setBorder('K'.$counter, 'thin');
			// 	 	$sheet->setBorder('L'.$counter, 'thin');
			// 	 	$sheet->setBorder('M'.$counter, 'thin');
			// 	 	$sheet->setBorder('N'.$counter, 'thin');
			// 	 	$sheet->setBorder('O'.$counter, 'thin'); 
			// 	 	$sheet->setBorder('P'.$counter, 'thin'); 
			// 	 	$sheet->setBorder('Q'.$counter, 'thin');
			// 	 	$sheet->setBorder('R'.$counter, 'thin');  				 	
			//  		$counter++;
			//  	}

		 // 	});

	  //   })->export('xls');						
