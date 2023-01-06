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
}
