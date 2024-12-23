<?php
require_once 'vendor/autoload.php';

use TCPDF;

/** @package  */
class PDFGenerator {
    /**
     * Generates a PDF document using TCPDF library.
     *
     * This function creates a new PDF document, sets various document properties,
     * adds content, and outputs the PDF either to the browser or as a download.
     *
     * @return void This function does not return a value. It outputs the PDF directly.
     */
    public function generatePDF() {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Alexis');
        $pdf->SetTitle('SignOff Project PDF');
        $pdf->SetSubject('TCPDF Example');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // Set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // Set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Add content
        $pdf->Cell(0, 10, 'Welcome to TCPDF!', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'This is an example of PDF generation using TCPDF.', 0, 1, 'L');

        // Output PDF document
        $pdf->Output('example.pdf', 'I'); // 'I' to display in browser, 'D' to download
    }

}

$pdfGenerator = new PDFGenerator();
$pdfGenerator->generatePDF();
?>
