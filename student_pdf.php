<?php
require_once __DIR__ . '/vendor/autoload.php';
require('common.php');
/** @var PDO $dbh Database Connection */

use Mpdf\Mpdf;

// Modified from https://github.com/mpdf/mpdf-examples/blob/master/example34_invoice_example.php

try {
    // Setup mPDF parameters
    $mpdf = new Mpdf([
        'margin_left' => 20,
        'margin_right' => 15,
        'margin_top' => 48,
        'margin_bottom' => 25,
        'margin_header' => 10,
        'margin_footer' => 10
    ]);
    // SetProtection – Encrypts and sets the PDF document permissions https://mpdf.github.io/reference/mpdf-functions/setprotection.html
    $mpdf->SetProtection(['print']);

    // Set some basic document metadata https://mpdf.github.io/reference/mpdf-functions/settitle.html
    $mpdf->SetTitle("Acme Trading Co. - Invoice");
    $mpdf->SetAuthor("Acme Trading Co.");

    // Set a watermark https://mpdf.github.io/reference/mpdf-functions/setwatermarktext.html
//    $mpdf->SetWatermarkText("Paid");
//    $mpdf->showWatermarkText = true;
//    $mpdf->watermarkTextAlpha = 0.1;

    // SetDisplayMode – Specify the initial Display Mode when the PDF file is opened in Adobe Reader https://mpdf.github.io/reference/mpdf-functions/setdisplaymode.html
    $mpdf->SetDisplayMode('fullpage');

    // Set up headers and footers - https://mpdf.github.io/headers-footers/method-4.html
    // Note: For this demo, the headers and footers are set up in the HTML file instead, from line 53

    // Get the actual contents from a file - in this case, it's an HTML file https://mpdf.github.io/reference/mpdf-functions/writehtml.html
    // In reality, you'll likely need to somehow modify the template so the data is properly inserted
    $students = $dbh->prepare("SELECT * FROM `students`");

    $data = array();
    if ($students->execute() && $students->rowCount() > 0) {
        while ($student = $students->fetchObject()) {
            $data .= '<tr>'
                . '<td>' . $student->id . '</td>'
                . '<td>' . $student->firstName.' '.$student->surname . '</td></tr>';
        }
    }

    $content = '<html>

<body>
<!--mpdf
<htmlpageheader name="myheader">
<table width="100%"><tr>
<td width="50%" style="color:#0000BB; "><span style="font-weight: bold; font-size: 14pt;">Little Dreamer Music School</span><br />123 Anystreet<br />Your City<br />GD12 4LP<br /><span style="font-family:dejavusanscondensed;">&#9742;</span> 01777 123 567</td>
</tr></table>
</htmlpageheader>
<htmlpagefooter name="myfooter">
<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
Page {PAGENO} of {nb}
</div>
</htmlpagefooter>
<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<h1>
    <div style="text-align: center;">List of Student Names</div>
</h1>
<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
    <thead>
    <tr>
        <td width="50%">Student ID</td>
        <td width="50%">Student Name</td>
    </tr>
    </thead>
    <tbody>
    <!-- ITEMS HERE -->
    ' . $data . '
    </tbody>
</table>
</body>
</html>
';

    $mpdf->WriteHTML($content);



    // Output – Finalise the document and send it to specified destination https://mpdf.github.io/reference/mpdf-functions/output.html
    $mpdf->Output();
} catch (\Mpdf\MpdfException $e) {
    var_dump($e);
}

