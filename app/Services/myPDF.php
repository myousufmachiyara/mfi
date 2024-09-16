<?php

namespace App\Services;

use TCPDF;

class myPDF extends TCPDF
{

    private $tableHtml = ''; // Variable to store the HTML for the table

    // Set table HTML
    public function setTableHtml($html) {
       $this->tableHtml = $html;
    }

    public function Header()
    {
        $this->SetFont('helvetica','B', 10);
        $this->Cell(0, 10, '', 0, 1, 'C');
        $this->Ln(5);
        if ($this->getPage()>1) {
            $this->setCellPadding(1.2); // Set padding for all cells in the table
            $this->writeHTML($this->tableHtml, true, false, true, false, '');
            $this->SetY(30);
        }
    }

    public function Footer()
    {
        $this->SetY(-15);

        // Set font
        $this->SetFont('helvetica', 'I', 13);
    
        // Get the page width
        $pageWidth = $this->getPageWidth();
        
        // Content and positions
        $companyName = 'Memon Fabrication And Installation';
        $websiteLink = 'mfi.com.pk';
        $pageNumber = 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages();
    
        // Calculate text widths
        $leftTextWidth = $this->GetStringWidth($websiteLink);
        $centerTextWidth = $this->GetStringWidth($companyName);
        $rightTextWidth = $this->GetStringWidth($pageNumber);
    
        // Calculate positions
        $leftX = 10; // Distance from left
        $centerX = ($pageWidth - $centerTextWidth) / 2; // Center position
        $rightX = $pageWidth - 34; // Distance from right
    
        // Draw the border
        $this->SetLineWidth(1);
        $this->SetDrawColor(0, 0, 0); // Black color
        $this->Line(10, $this->getY(), $pageWidth - 10, $this->getY()); // Top border of the footer
    
        // Company website on the left
        $this->SetX($leftX);
        $this->Cell($leftTextWidth, 10, $websiteLink, 0, 0, 'L');
    
        // Company name in the center
        $this->SetX($centerX);
        $this->Cell($centerTextWidth, 10, $companyName, 0, 0, 'C');
    
        // Page number on the right
        $this->SetX($rightX);
        $this->Cell($rightTextWidth, 10, $pageNumber, 0, 0, 'R');
    }
 

    function convertCurrencyToWords($number) {
        $Thousand = 1000;
        $Million = $Thousand * $Thousand;
        $Billion = $Thousand * $Million;
        $Trillion = $Thousand * $Billion;

        if ($number == 0) return "Zero";
        $isNegative = $number < 0;
        $number = abs($number);

        $result = $isNegative ? "(negative) " : "";
        
        // Trillions
        if ($number >= $Trillion) {
            $result .= $this->convertDigitGroup(floor($number / $Trillion)) . " Trillion ";
            $number %= $Trillion;
        }
        
        // Billions
        if ($number >= $Billion) {
            $result .= $this->convertDigitGroup(floor($number / $Billion)) . " Billion ";
            $number %= $Billion;
        }
        
        // Millions
        if ($number >= $Million) {
            $result .= $this->convertDigitGroup(floor($number / $Million)) . " Million ";
            $number %= $Million;
        }
        
        // Thousands
        if ($number >= $Thousand) {
            $result .= $this->convertDigitGroup(floor($number / $Thousand)) . " Thousand ";
            $number %= $Thousand;
        }

        // Hundreds and below
        if ($number > 0) {
            $result .= $this->convertDigitGroup($number);
        }

        return trim($result) . " Rupees Only";
    }

    function convertDigitGroup($number) {
        $hundreds = floor($number / 100);
        $remainder = $number % 100;
        $result = "";

        if ($hundreds > 0) {
            $result .= $this->convertSingleDigit($hundreds) . " Hundred ";
        }

        if ($remainder > 0) {
            if ($remainder < 20) {
                $result .= $this->convertTens($remainder);
            } else {
                $result .= $this->convertTens(floor($remainder / 10) * 10);
                if ($remainder % 10 > 0) {
                    $result .= "-" . $this->convertSingleDigit($remainder % 10);
                }
            }
        }

        return trim($result);
    }

    function convertSingleDigit($digit) {
        $digits = [
            0 => "",
            1 => "One",
            2 => "Two",
            3 => "Three",
            4 => "Four",
            5 => "Five",
            6 => "Six",
            7 => "Seven",
            8 => "Eight",
            9 => "Nine"
        ];

        return $digits[$digit];
    }

    function convertTens($number) {
        $tens = [
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen",
            20 => "Twenty",
            30 => "Thirty",
            40 => "Forty",
            50 => "Fifty",
            60 => "Sixty",
            70 => "Seventy",
            80 => "Eighty",
            90 => "Ninety"
        ];

        return isset($tens[$number]) ? $tens[$number] : "";
    }

}
