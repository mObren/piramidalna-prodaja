<?php

class Prodavac {

    protected $email;
    protected $stedniRacun = 0;
    protected $tekuciRacun = 0;
    protected $mojiProdavci = [];
    static protected $zauzetiEmailovi = [];

    public function __construct($email) {
        if (in_array($email, self::$zauzetiEmailovi)) {
            throw new Exception('Mejl adresa je zauzeta!');
        }
        self::$zauzetiEmailovi[] = $email;
        $this->email = $this->setEmail($email);
    }

    static public function getZauzetiEmailovi() {
        return self::$zauzetiEmailovi;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getStedniRacun() {
        return $this->stedniRacun;
    }

    public function getTekuciRacun() {
        return $this->tekuciRacun;
    }

    public function getMojiProdavci() {
        return $this->mojiProdavci;
    }

    public function setEmail(string $email) {
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {

            throw new Exception('Prosledjeni parametar mora biti u formatu emaila');
        }

        $this->email = $email;
        return $this;
    }

    public function setStedniRacun($stedniRacun) {
        $this->stedniRacun = $stedniRacun;
        return $this;
    }

    public function setTekuciRacun($tekuciRacun) {
        $this->tekuciRacun = $tekuciRacun;
        return $this;
    }

    public function setMojiProdavci($mojiProdavci) {
        $this->mojiProdavci = [];
        $this->mojiProdavci[] = $mojiProdavci;
        return $this;
    }

    /**
     * 
     * @param Prodavac $podredjeni
     */
    public function dodajProdavca(Prodavac $podredjeni) {
        $this->mojiProdavci[] = $podredjeni;
        return $this;
    }

////////////////////////////////////////////////////

    public function prodajPredmet(PredmetProdaje $predmetProdaje, $kolicina = 1) {
        $this->tekuciRacun += $predmetProdaje->prikaziCenu() * $kolicina;
        echo 'Prodaje se ' . $kolicina . ' komada predmeta po ceni ' . $predmetProdaje->prikaziCenu() . ' rsd.. <br>';
    }

    protected function uzmiZaradjeno() {
        $this->stedniRacun += $this->tekuciRacun * 0.6;
        $this->tekuciRacun -= $this->tekuciRacun * 0.6;
    }

    public function sakupiProfit() {
        foreach ($this->mojiProdavci as $podredjeniProdavac) {
            $podredjeniProdavac->uzmiZaradjeno();
            $this->tekuciRacun += $podredjeniProdavac->getTekuciRacun();
        }
        $this->uzmiZaradjeno();
        $this->tekuciRacun = 0;
    }

}

class Menadzer extends Prodavac {

    public function __construct($email) {
        if (in_array($email, self::$zauzetiEmailovi)) {
            throw new Exception('Mejl adresa je zauzeta!');
        }
        self::$zauzetiEmailovi[] = $email;
        $this->email = $this->setEmail($email);
    }

    static public function getZauzetiEmailovi() {
        return self::$zauzetiEmailovi;
    }

    public function dodajProdavca(Prodavac $podredjeni) {
        $this->mojiProdavci[] = $podredjeni;
        return $this;
    }

    public function getEmail() {
        return parent::getEmail();
    }

    public function getMojiProdavci() {
        return parent::getMojiProdavci();
    }

    public function getStedniRacun() {
        return parent::getStedniRacun();
    }

    public function getTekuciRacun() {
        return parent::getTekuciRacun();
    }

    public function prodajPredmet(\PredmetProdaje $predmetProdaje, $kolicina = 1) {
        parent::prodajPredmet($predmetProdaje, $kolicina);
    }

    public function sakupiProfit() {
        foreach ($this->mojiProdavci as $podredjeniProdavac) {
            $podredjeniProdavac->uzmiZaradjeno();
            $this->tekuciRacun += $podredjeniProdavac->getTekuciRacun();
            $podredjeniProdavac->setTekuciRacun(0);
        }
        $this->uzmiZaradjeno();
    }

    public function setEmail(string $email) {
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {

            //bacanje exception-a
            throw new Exception('Email is not valid email address');
        }

        $this->email = $email;
        return $this;
    }

    public function setMojiProdavci($mojiProdavci) {
        return parent::setMojiProdavci($mojiProdavci);
    }

    public function setStedniRacun($stedniRacun) {
        return parent::setStedniRacun($stedniRacun);
    }

    public function setTekuciRacun($tekuciRacun) {
        return parent::setTekuciRacun($tekuciRacun);
    }

    protected function uzmiZaradjeno() {
        $this->stedniRacun += $this->tekuciRacun * 0.8;
        $this->tekuciRacun -= $this->tekuciRacun * 0.8;
    }

}

class Direktor extends Menadzer {

    static public function getZauzetiEmailovi() {
        return self::$zauzetiEmailovi;
    }

    public function __construct($email) {
        if (in_array($email, self::$zauzetiEmailovi)) {
            throw new Exception('Mejl adresa je zauzeta!');
        }
        self::$zauzetiEmailovi[] = $email;
        $this->email = $this->setEmail($email);
    }

    public function dodajProdavca(Prodavac $podredjeni) {
        if (!($podredjeni instanceof Menadzer)) {
            throw new Exception('Zaposleni mora imati zvanje menadzera!');
        }
        $this->mojiProdavci[] = $podredjeni;
        return $this;
    }

    public function getEmail() {
        return parent::getEmail();
    }

    public function getMojiProdavci() {
        return parent::getMojiProdavci();
    }

    public function getStedniRacun() {
        return parent::getStedniRacun();
    }

    public function getTekuciRacun() {
        return parent::getTekuciRacun();
    }

    public function prodajPredmet(\PredmetProdaje $predmetProdaje, $kolicina = 1) {
        parent::prodajPredmet($predmetProdaje, $kolicina);
    }

    public function sakupiProfit() {
        foreach ($this->mojiProdavci as $podredjeniProdavac) {
            $podredjeniProdavac->sakupiProfit();
            $this->tekuciRacun += $podredjeniProdavac->getTekuciRacun();
            $podredjeniProdavac->setTekuciRacun(0);
        }
        $this->uzmiZaradjeno();
        $this->tekuciRacun = 0;
    }

    public function setEmail(string $email) {
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {

            //bacanje exception-a
            throw new Exception('Email is not valid email address');
        }

        $this->email = $email;
        return $this;
    }

    public function setMojiProdavci($mojiProdavci): \this {
        return parent::setMojiProdavci($mojiProdavci);
    }

    public function setStedniRacun($stedniRacun): \this {
        return parent::setStedniRacun($stedniRacun);
    }

    public function setTekuciRacun($tekuciRacun): \this {
        return parent::setTekuciRacun($tekuciRacun);
    }

    protected function uzmiZaradjeno() {
        $this->stedniRacun += $this->tekuciRacun;
    }

}

interface PredmetProdaje {

    public function prikaziCenu();
}

trait HasCena {

    protected $cena;

    public function getCena() {
        return $this->cena;
    }

    public function setCena($cena) {
        $this->cena = $cena;
        return $this;
    }

}

trait HasNaziv {

    protected $naziv;

    public function getNaziv() {
        return $this->naziv;
    }

    public function setNaziv($naziv) {
        $this->naziv = $naziv;
        return $this;
    }

}

class Proizvod implements PredmetProdaje {

    use HasCena;
    use HasNaziv;

    protected $barkod;

    public function __construct($cena) {
        $this->cena = $cena;
    }

    public function __toString() {
        
    }

    public function getBarkod() {
        return $this->barkod;
    }

    public function setBarkod($barkod) {
        $this->barkod = $barkod;
        return $this;
    }

    public function prikaziCenu() {
        return $this->getCena();
    }

}

class Usluga implements PredmetProdaje {

    use HasCena,
        HasNaziv;

    protected $normaCas;

    public function prikaziCenu() {
        return $this->getCena();
    }

    public function __toString() {
        
    }

    public function __construct($cena) {
        $this->cena = $cena;
    }

}

////////////// SIMULACIJA //////////////////////////////

$Usluga1 = new Usluga(1000);
$UslugaMedvedja = new Usluga(50);

$ProizvodTehnika = new Proizvod(2990);
$Zvake = new Usluga(30);

$Marko = new Prodavac('marko@email.com');
$Maja = new Prodavac('maja@yahoo.com');
$Zile = new Prodavac('zile@gmail.com');
$Miki = new Prodavac('miki@331.com');
$Iva = new Prodavac('iva@icloud.com');
$Ana = new Prodavac('ana22@imejl.com');
$Darko = new Prodavac('1234@567.com');
$Janko = new Prodavac('janko@email.com');
$Haralampije = new Prodavac('hari@yahoo.com');
$Jack = new Prodavac('jack@email.com');
$Riska = new Prodavac('risk@email.com');
$Dule = new Prodavac('dulepacov@hotmail.com');
$Svaba = new Prodavac('svabic@predrag.com');
$Ekser = new Prodavac('ekser@adas.com');
$Nikola = new Prodavac('nikola@nidza.com');
$Marica = new Prodavac('marica@yahoo.com');
$Jovana = new Prodavac('jovana@icloud.com');
$Bane = new Prodavac('bane@gmail.com');
$Marko2 = new Prodavac('marko.prezime@email.com');
$Pera = new Prodavac('pera.p@email.com');

$Nikola2 = new Menadzer('nikola2@nesto.com');
$Dejana = new Menadzer('dejana@email.com');
$Milos = new Menadzer('milos@331.com');
$Tomas = new Menadzer('tomi@bussiness.com');

$MrDirektor = new Direktor('glavnilik@hotmail.com');

$MrDirektor->dodajProdavca($Nikola2);
$MrDirektor->dodajProdavca($Dejana);
$MrDirektor->dodajProdavca($Tomas);
$MrDirektor->dodajProdavca($Milos);

$Milos->dodajProdavca($Svaba)->dodajProdavca($Miki)->dodajProdavca($Ekser)->dodajProdavca($Dule)->dodajProdavca($Jovana);
$Tomas->dodajProdavca($Maja)->dodajProdavca($Marko)->dodajProdavca($Ana)
        ->dodajProdavca($Bane)->dodajProdavca($Haralampije);
$Dejana->dodajProdavca($Iva)->dodajProdavca($Jack)
        ->dodajProdavca($Janko)->dodajProdavca($Marica)->dodajProdavca($Pera);
$Nikola2->dodajProdavca($Darko)->dodajProdavca($Riska)->dodajProdavca($Marko2)
        ->dodajProdavca($Zile)->dodajProdavca($Nikola);

$Marko->prodajPredmet($ProizvodTehnika, 2);
$Maja->prodajPredmet($Usluga1, 4);
$Marica->prodajPredmet($UslugaMedvedja, 7);
$Marko2->prodajPredmet($ProizvodTehnika);
$Miki->prodajPredmet($ProizvodTehnika, 3);
$Marko->prodajPredmet($Zvake, 21);
$Ana->prodajPredmet($ProizvodTehnika, 2);
$Bane->prodajPredmet($ProizvodTehnika, 3);
$Darko->prodajPredmet($ProizvodTehnika);
$Dule->prodajPredmet($Usluga1, 2);
$Ekser->prodajPredmet($ProizvodTehnika, 2);
$Haralampije->prodajPredmet($Zvake, 4);
$Iva->prodajPredmet($ProizvodTehnika);
$Jack->prodajPredmet($ProizvodTehnika, 4);
$Janko->prodajPredmet($Usluga1, 2);
$Jovana->prodajPredmet($Usluga1, 2);
$Nikola->prodajPredmet($Zvake, 2);
$Pera->prodajPredmet($ProizvodTehnika, 2);
$Riska->prodajPredmet($Zvake, 2);
$Svaba->prodajPredmet($UslugaMedvedja, 2);
$Zile->prodajPredmet($Zvake, 50);

$Milos->prodajPredmet($ProizvodTehnika);
$Dejana->prodajPredmet($ProizvodTehnika);
$Nikola2->prodajPredmet($UslugaMedvedja, 4);
$Tomas->prodajPredmet($UslugaMedvedja, 2);

$MrDirektor->prodajPredmet($ProizvodTehnika, 5);

$MrDirektor->sakupiProfit();




print_r($Ana->getStedniRacun()); echo ' Ana <br> ';
print_r($Bane->getStedniRacun()); echo ' Bane <br> ';
print_r($Darko->getStedniRacun()); echo ' Darko <br> ';
print_r($Dule->getStedniRacun()); echo ' Dule <br> ';
print_r($Ekser->getStedniRacun()); echo ' Ekser <br> ';
print_r($Haralampije->getStedniRacun()); echo ' Haralampije <br> ';
print_r($Iva->getStedniRacun()); echo ' Iva <br> ';
print_r($Jack->getStedniRacun()); echo ' Jack <br> ';
print_r($Janko->getStedniRacun()); echo ' Janko <br> ';
print_r($Jovana->getStedniRacun()); echo ' Jovana <br> ';
print_r($Maja->getStedniRacun()); echo ' Maja <br> ';
print_r($Marica->getStedniRacun()); echo ' Marica <br> ';
print_r($Marko->getStedniRacun()); echo ' Marko <br> ';
print_r($Marko2->getStedniRacun()); echo ' Drugi Marko <br> ';
print_r($Miki->getStedniRacun()); echo ' Miki <br> ';
print_r($Nikola->getStedniRacun()); echo ' Nikola <br> ';
print_r($Pera->getStedniRacun()); echo ' Pera <br> ';
print_r($Riska->getStedniRacun()); echo ' Riska <br> ';
print_r($Svaba->getStedniRacun()); echo ' Svaba <br> ';
print_r($Zile->getStedniRacun()); echo ' Zile <br><br> ';

print_r($Dejana->getStedniRacun()); echo ' Dejana Menadzer <br> ';
print_r($Milos->getStedniRacun()); echo ' Milos Menadzer <br> ';
print_r($Nikola2->getStedniRacun()); echo ' Nikola Menadzer <br> ';
print_r($Tomas->getStedniRacun()); echo ' Tomas Menadzer <br><br> ';
print_r($MrDirektor->getStedniRacun()); echo ' DIREKTOR <br><br> ';









print_r(Prodavac::getZauzetiEmailovi());
