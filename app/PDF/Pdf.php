<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 05/06/2019
 * Time: 13:48
 */

namespace App\PDF;


use Codedge\Fpdf\Fpdf\Fpdf;

class Pdf extends Fpdf
{
    function Header()
    {
        $this->SetFont('Arial','I',8);
        $this->Image(public_path()."/img/logo_extrato.png", 10, 10,'PNG');
        $this->SetY("18");
        $this->Cell(50,5,"",0,0,'L');
        $this->SetFont('Arial','B',16);
        $this->Cell(90,5, utf8_decode("Extrato Contrato"),0,0,'C');
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,date('d/m/Y'),0,1,'R');
        $this->SetY("24");
        $this->Cell(0,0,'',1,1,'L');
        $this->Ln(8);
    }

    function Footer()
    {
        //Busca nome da sistema e url
        $this->SetY(-15);
        $this->SetFont('Arial','B',7);
        $this->Cell(0,0,'',1,1,'L');
        $this->Cell(50,10,utf8_decode('Unidade Gestora: '.session()->get('user_ug').' - '.backpack_auth()->user()->name),0,0,'L');
        $this->SetFont('Arial','I',8);
        $this->Cell(90,10,'',0,0,'C');
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,utf8_decode('Página '.$this->PageNo()),0,0,'R');
    }

}
