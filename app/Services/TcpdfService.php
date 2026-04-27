<?php
namespace App\Services;
use TCPDF;

class TcpdfService extends TCPDF
{
    public array $styles = [];
    public array $palette = [];
    public bool $fillRow = false;

    public function __construct()
    {
        parent::__construct('P','mm','LETTER',true,'UTF-8',false);
        $this->SetCreator('Laravel');
        $this->SetAuthor(config('app.name'));
        $this->SetMargins(10,10,10);
        $this->SetAutoPageBreak(true,10);
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->SetFont('helvetica','',9);

        $this->palette = [
            'nivel1' => '#fffeafff',
            'nivel2' => '#c4ffc4ff',
            'nivel3' => '#dadadaff',
            'nivel4' => '#f1f1f1ff',
            'header' => '#94a4fdff',
            'odd' => '#e5eaffff',
            'even' => '#FFFFFF',
            'border' => '#B4B4B4'
        ];

        $this->styles = [
            'nivel1'=>['font'=>'B','size'=>12,'fill'=>'nivel1'],
            'nivel2'=>['font'=>'B','size'=>10,'fill'=>'nivel2'],
            'nivel3'=>['font'=>'B','size'=>9,'fill'=>'nivel3'],
            'nivel4'=>['font'=>'B','size'=>9,'fill'=>'nivel4'],
            'header'=>['font'=>'B','size'=>9,'fill'=>'header'],
            'odd'=>['font'=>'','size'=>9,'fill'=>'odd'],
            'even'=>['font'=>'','size'=>9,'fill'=>'even']
        ];
    }

    public function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex,'#');
        return [
            hexdec(substr($hex,0,2)),
            hexdec(substr($hex,2,2)),
            hexdec(substr($hex,4,2))
        ];
    }

    public function aplicarEstilo(string $key)
    {
        $s = $this->styles[$key];
        $fill = $this->hexToRgb($this->palette[$s['fill']]);
        $border = $this->hexToRgb($this->palette['border']);
        $this->SetFont('',$s['font'],$s['size']);
        $this->SetFillColor(...$fill);
        $this->SetDrawColor(...$border);
        $this->SetLineWidth(0.1);
        return 1;
    }

    public function aplicarFondo(string $colRgba)
    {
        if (str_starts_with($colRgba, 'rgba')) {
            preg_match('/rgba?\((\d+),\s*(\d+),\s*(\d+)/', $colRgba, $m);
            $r = $m[1]; $g = $m[2]; $b = $m[3];
        } else {
            $hex = substr($this->palette[$colRgba], 0, 7);
            [$r, $g, $b] = $this->hexToRgb($hex);
        }
        $this->SetFillColor($r, $g, $b);
        $luminance = 0.3*$r + 0.6*$g + 0.1*$b;
        if ($luminance < 128) $this->SetTextColor(255,255,255);
        else $this->SetTextColor(0,0,0);
    }

}
