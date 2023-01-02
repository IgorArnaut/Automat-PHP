<?php
class Artikal
{
    // Atributi
    private $sifra;
    private $naziv;
    private $cena;
    private $kolicina;

    // Konstruktor
    public function __construct()
    {
    }

    // Vraca sifru
    public function get_sifra()
    {
        return $this->sifra;
    }

    // Vraca naziv
    public function get_naziv()
    {
        return $this->naziv;
    }

    // Vraca cenu
    public function get_cena()
    {
        return $this->cena;
    }

    // Vraca kolicinu
    public function get_kolicina()
    {
        return $this->kolicina;
    }

    // Menja vrednost sifre u novu
    public function set_sifra($sifra)
    {
        return $this->sifra = $sifra;
    }

    // Menja vrednost naziva u novu
    public function set_naziv($naziv)
    {
        return $this->naziv = $naziv;
    }

    // Menja vrednost cene u novu
    public function set_cena($cena)
    {
        $this->cena = $cena;
    }

    // Menja vrednost kolicine u novu
    public function set_kolicina($kolicina)
    {
        return $this->kolicina = $kolicina;
    }

    // Vraca html element
    public function get_html()
    {
        $html = "";
        $html .= "<td>";

        if (isset($this->sifra) && isset($this->naziv) && isset($this->cena) && isset($this->kolicina)) {
            if ($this->kolicina > 0) {
                $html .= "<img src=\"images/" . str_replace(" ", "-", $this->naziv) . ".jpg\" width=\"100\">";
                $html .= "<br>";
                $html .= "{$this->sifra} | {$this->cena} DIN";
            } else {
                $html .= "<img src=\"images/none.png\" style=\"background-color: white;\" width=\"100\">";
                $html .= "<br>";
                $html .= "{$this->sifra} | {$this->cena} DIN";
            }
        } else {
            $html .= "<img src=\"images/none.jpg\" style=\"background-color: white;\" width=\"100\">";
            $html .= "<br>";
            $html .= "X | X";
        }

        $html .= "</td>";
        return $html;
    }
}
