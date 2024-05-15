<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PDFExportController extends Controller
{
    public function exportPDF()
    {
        $pdf = PDF::loadView('tutorial.tutorial-to-download'); 
        return $pdf->download('tutorial.pdf'); 
    }
}
