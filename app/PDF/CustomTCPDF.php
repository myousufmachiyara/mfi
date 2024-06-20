<?
// app/PDF/CustomTCPDF.php

namespace App\PDF;

use TCPDF;

class CustomTCPDF extends TCPDF {
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);

        // Get current date and format it
        $date = date('Y-m-d');
        // Get day of the week
        $day = date('l', strtotime($date));
        // Company name
        $companyName = 'Your Company Name';

        // Output date, day, and company name
        $this->Cell(0, 10, 'Date: ' . $date . ' (' . $day . ') | Company: ' . $companyName, 0, false, 'L', 0, '', 0, false, 'T', 'M');

        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}
?>