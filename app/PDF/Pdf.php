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

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw =& $this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

}
