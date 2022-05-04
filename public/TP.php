<?php
require __DIR__ . '../../vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

// item
require("Item.php");

/**
 * Class TP
 * @author Elias
 * @see facebook.com/elias_champi
 * @version 0.1.2019
 */
class TP {

 	/**
     * @param array $data
     */
 	public function print($data)
 	{
 		$printName = "TP-300";
 		$connector = new WindowsPrintConnector($printName);
 		$printer = new Printer($connector);
 		//logos
 		$logo = EscposImage::load("mini.png", false);
 		// verificar
 		$total = new Item('Importe Total: ', $data["main"]["total"], true);
 
 		/* Start the printer */
 		/* Print top logo */
 		$printer->setJustification(Printer::JUSTIFY_CENTER);
 		$printer->graphics($logo,  Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);

 		//inicio titulo
 		$printer->feed();

 		// inicio sub
 		$printer->setEmphasis(true);
		$printer->text("Telf: " . "951994718\n");
		$printer->text("URB. TINGO CALLE EL EDEN S/N (FRENTE AL MAESTRO)\n");
	    	$printer->text("-------------------------\n");
        	$printer->feed(2);
        	$printer->setEmphasis(false);
        	// fin sub
        	// inicio sub2
 		$printer->setJustification(Printer::JUSTIFY_LEFT);
 		$printer->text("Cajero(a): " . $data["main"]["user"]["name"] . "\n");
 		$printer->text("Razón Social: " . $data["main"]["name"]["name"] . "\n");
		
		$register = $data["main"]["has_register"];
		if (!empty($register)) {
		  $printer->text("Grado: " . substr($register["register"]["section_code"], -2) . " de " . $register["register"]["level"] . "\n");
		}
 		$printer->text("Fecha de Emisión: " . $data["created_at"] . "\n");
 		$printer->feed(2);

 		// fin sub2
 		// inicio serie
 		$printer->setJustification(Printer::JUSTIFY_CENTER);
 		$printer->setEmphasis(true);

 		$type = "NOTA DE VENTA N°: " ;
 		if($data["main"]["type"] === "03") {
 			$type = "BOLETA DE VENTA N°: " ;
 		}
		$printer->text($type);
		$printer->text($data["main"]["serie"] . "\n");
 		$printer->text("-------------------------\n");
 		$printer->setEmphasis(false);
		$printer->setJustification(Printer::JUSTIFY_RIGHT);

        	// aqui el encabezado se engresara como un detalle
        	// Detalle 
        	$printer->text("Descripción del Comprobante:\n");
 		foreach ($data["detail"] as $item) {
 			$name = $item["actiontype"]["name"];
 			if ($name === "Mensualidad") {
 				$name .=  " " . $item["title"] . " ";
 			}
 			$printer->text(new Item($name,$item["paid"], true));
 		}
		$printer->feed(2);
 		// total
 		$printer->setEmphasis(true);
 		$printer->text($total);
 		$printer->setEmphasis(false);
 		/* Footer */
 		$printer->feed(2);
 		$printer->setJustification(Printer::JUSTIFY_CENTER);
 		$printer->text("-------------------------\n");
 		$printer->text("*** Gracias por su preferencia ***\n");
 		$printer->text("Conserve este comprobante en caso de Reclamo\n");
 		$printer->feed(2);
		
		$printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
 		$printer->text($data["title"] . "\n");
 		$printer->selectPrintMode();
 		$printer->feed();
 		
 		$printer->cut();
 		$printer->pulse();
 		$printer->close();
 	}
 }

 
