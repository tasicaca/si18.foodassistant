<?php
    header('Content-type=application/json; charset=utf-8');

/*
TODO:
 Dodaj i kolicinu hrane u JSON fajl u fciju vratiHranuIzFrizideraPrekoGrupe


 */

// <editor-fold desc="Includes" defaultstate="collapsed">

// <editor-fold desc="Neophodne klase" defaultstate="collapsed">



// </editor-fold>


// </editor-fold>

// <editor-fold desc="Operation IDs" defaultstate="collapsed">
/*
opID:
 0 - Test ID
 1 - Pristup bazi
 2 - Dodavanje u bazu
 3 - Provera podataka u bazi
 4 - Pribavljanje 1 prostog podatka iz baze
 5 - Pribavljanje 1 objekta iz baze
 6 - Pribavljanje vise objekata iz baze
 7 - Izmena podataka u bazi
 8 - Brisanje podataka u bazi
 */
// </editor-fold>


class DBConnection{ // Database Connection class
    
    // <editor-fold desc="Glavne stvari" defaultstate="collapsed">
    /*********** Promenljive ***********/
    
    private $hostname; // Naziv hosta (sajta)
    private $username; // username za login na phpMyAdmin
    private $password; // Password za login na phpMyAdmin
    private $database; // Nasa baza podataka
    private $conIsSet; // Da li je konekcija uspostavljena
    
    public $dbh; // Database Handler
    
    
    /************************************/
    /*********** Konstruktori ***********/
    
    /// Konstruktor
    public function __construct(){
        
    // Podesi nazive promenljivih
    $this->hostname = "localhost"; 
    $this->username = "id5683524_root"; 
    $this->password = "password"; 
    $this->database = "id5683524_foodassistant"; 
    $this->conIsSet = true; //Inicijalno je konekcija uspostavljena
    
    // Pokusaj da uspostavis konekciju
    try {
        $this->dbh = new PDO("mysql:host=". $this->hostname .";dbname=". $this->database, $this->username, $this->password);
        } catch(PDOException $e) {
            $this->conIsSet=false; // Konekcija nije uspostavljena
            echo '<h1>An error has occurred.</h1><pre>', $e->getMessage() ,'</pre>';
        }
        
    }
    // </editor-fold>
    
    /************************************/
    /*********** Funkcije ***********/
 
   // <editor-fold desc="Funkcije za dodavanje stvari" defaultstate="collapsed">
    
    // <editor-fold desc="dodajNamirnicu-ddj" defaultstate="collapsed">
    public function dodajNamirnicu($mNaziv,$mKalorije,$mProteini,$mMasti,$mUH)
    {
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
         $insertKveri = $this->dbh->query("INSERT INTO Hrana(Naziv, Kalorije, Proteini, Masti, Ugljeni_hidrati)"
                 . " VALUES ('$mNaziv',$mKalorije,$mProteini,$mMasti,$mUH);");
        
        $uspeh=true;
        if ($insertKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
         // Vratimo json string
         $jsonNiz=array('opID'=>2,'querySucc'=>$uspeh);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
    }
    // </editor-fold>
    
    // <editor-fold desc="dodajJelo-ddj" defaultstate="collapsed">
    public function dodajJelo($mNaziv,$mKalorije,$mProteini,$mMasti,$mUH,$mJesteJelo,$mSastojci)
    {
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $insertKveri = $this->dbh->query("INSERT INTO Hrana(Naziv, Kalorije, Proteini, Masti, Ugljeni_hidrati,"
                 . "JesteJelo,Sastojci) VALUES ('$mNaziv',$mKalorije,$mProteini,$mMasti,$mUH,1,'$mSastojci');");
   
        $uspeh=true;
        if ($insertKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
         // Vratimo json string
         $jsonNiz=array('opID'=>2,'querySucc'=>$uspeh);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
    }
    // </editor-fold>
    
    // <editor-fold desc="dodajPitanje-ndj" defaultstate="collapsed">
    public function dodajPitanje($pit, $odg, $funf){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $insertKveri = $this->dbh->query("INSERT INTO Kviz (Pitanje, Odgovori, Fun_Fact) VALUES ('$pit', '$odg', '$funf');");
        
        $uspeh=true;
        if ($insertKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
         // Vratimo json string
         $jsonNiz=array('opID'=>2,'querySucc'=>$uspeh);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
            
    }
    // </editor-fold>
    
    // <editor-fold desc="dodajNovogKorisnika-ddj" defaultstate="collapsed">
    public function dodajNovogKorisnika($mUsername, $mPassword,$mIme,$mPrezime,$mPol,
            $mGodiste,$mVisina,$mMasa,$mNivoAktivnosti,$mRezimIshrane){
        
    if ($this->conIsSet==false){
       $jsonNiz=array('opID'=>1,'querySucc'=>false);
       $jsonStr = json_encode($jsonNiz);
       echo $jsonStr;
       return;
      }
      
      // Dodaj prvo grupu, jer korisnik zavisi od nje
      $dodavanjeGrupe= $this->dodajGrupu($mUsername."_grupa");
      
      if ($dodavanjeGrupe==true){ // Ako je grupa kreirana, odradi sve ostalo
          
          $IDGrupe = $this->getIDGrupePrekoNaziva($mUsername."_grupa"); 
          
          if ($IDGrupe!=-1){
          
            // Pokusaj dodavanje korisnika
          $dodavanjeKorisnika = $this->dodajKorisnikaPrekoIDGrupe($mUsername, $mPassword, $mIme, $mPrezime, $IDGrupe, $mPol, $mGodiste, $mVisina, $mMasa, $mNivoAktivnosti, $mRezimIshrane);
          
          //Ako je dodavanje korisnika ispravno, vrati podatke o korisniku za cuvanje
          if ($dodavanjeKorisnika==true){
           
            $selectKveri = $this->dbh->query("SELECT * FROM Korisnik WHERE UsernameString='$mUsername'");
            $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
            $resultat = $selectKveri->fetchAll();
        
            $uspeh=true;
            if ($selectKveri==false) // Proverava se uspeh kverija
                $uspeh=false;

            $data=null;
            if (count($resultat)>0){
                foreach($resultat as $r){
                    $data=array('id'=>$r['ID'],
                        'ime'=>$r['Ime'],
                        'prezime'=>$r['Prezime'],
                        'username'=>$r['UsernameString'],
                        'password'=>$r['PasswordString'],
                        'idGrupe'=>$r['ID_Grupe'],
                        'pol'=>$r['Pol'],
                        'visina'=>$r['Visina'],
                        'masa'=>$r['Masa'],
                        'nivoAktivnosti'=>$r['Nivo_Aktivnosti'],
                        'rezimIshrane'=>$r['Rezim_Ishrane'] );
                }
            }
            
            $jsonNiz=array('opID'=>5,'querySucc'=>$uspeh,'objID'=>'korisnik','num'=>1,'data'=>$data);
            $jsonStr = json_encode($jsonNiz);
            echo $jsonStr;
            return;
           }
          }
       }
    $jsonNiz=array('opID'=>5,'querySucc'=>false);
    $jsonStr = json_encode($jsonNiz);
    echo $jsonStr;
    return;
    }
    // </editor-fold>
    
    // <editor-fold desc="dodajReceptZaKorisnika-ddj" defaultstate="collapsed">
    public function dodajReceptZaKorisnika($idKorisnika,$naziv,$sastojci,$kolicina){
        
         if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $imaRecept = $this->daLiKorisnikImaReceptProvera($idKorisnika,$naziv);
        
        if ($imaRecept==false){
            
        $insertKveri = $this->dbh->query("INSERT INTO Recept (Naziv, Sastojci, Kolicina, ID_Vlasnika) VALUES ('$naziv', '$sastojci', '$kolicina',$idKorisnika);");
        
        $uspeh=true;
        if ($insertKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        }
        else
            $uspeh=false;
        
         // Vratimo json string
         $jsonNiz=array('opID'=>2,'querySucc'=>$uspeh);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
         
    }
    // </editor-fold>
    
     // <editor-fold desc="dodajReceptZaGrupu-ddj" defaultstate="collapsed">
    public function dodajReceptZaGrupu($idKorisnika,$naziv,$groupID){
        
         if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $receptID = $this->vratiIDRecepta($idKorisnika,$naziv);
        //echo json_encode($receptID);
        // Ispitaj i vrati da li postoji taj recept kod korisnika
        if ($receptID!=-1){
        $insertKveri = $this->dbh->query("INSERT INTO Recepti_Grupe (ID_Grupa, ID_Recept) VALUES ($groupID, $receptID);");
        
        $uspeh=true;
        if ($insertKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        }
        else
            $uspeh=false;
        
         // Vratimo json string
         $jsonNiz=array('opID'=>2,'querySucc'=>$uspeh);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
         
    }
    // </editor-fold>
   
   // <editor-fold desc="dodajNamirnicuUFrizider-ndj" defaultstate="collapsed">
    public function dodajNamirnicuUFrizider($idGrupa, $idHrana, $idKolicina)
    {
       if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        $selectKveri = $this->dbh->query("SELECT * FROM Sadrzina_Frizidera WHERE Id_Hrana=$idHrana");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $rezultat = $selectKveri->fetchAll();
        $grupa=0;
        $nadjenaGrupa=0;
        foreach($rezultat as $r) {
                    $vrednost = $r['Kolicina'];
                    $grupa = $r['ID_Grupa'];
                    if($grupa == $idGrupa)
                    {
                        $nadjenaGrupa = $grupa;
                    }
                }
        if(count($rezultat)==0)     // Nije nasao nijednu torku gde postoji hrana sa ovim ID-jem
        {
            $vrednost=0;
            $kveri = $this->dbh->query("INSERT INTO Sadrzina_Frizidera (ID_Grupa, ID_Hrana, Kolicina) values ($idGrupa, $idHrana, $idKolicina)");
        }
            else if ($nadjenaGrupa == $idGrupa) // Nasao je hranu, i ona pripada nekoj grupi korisnika 
        {           
            $idKolicina = $vrednost + $idKolicina;
            $kveri = $this->dbh->query("UPDATE Sadrzina_Frizidera SET Id_Grupa=$idGrupa, Id_Hrana=$idHrana, Kolicina=$idKolicina WHERE Id_Hrana=$idHrana");                
        }
        else    // Nasao je hranu sa zadatim ID-jem, medjutim nece da je update, jer ona ne pripada zadatoj grupi korisnika. 
        {       // Grupa 1 je kupila paradajz, to znaci da nece da Update kolicine paradajza Grupi 2, nego da ce Insert Grupi 1.
            $kveri = $this->dbh->query("INSERT INTO Sadrzina_Frizidera (ID_Grupa, ID_Hrana, Kolicina) values ($idGrupa, $idHrana, $idKolicina)");
        }
       
        $uspeh=true;
        if ($kveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
        $jsonNiz=array('opID'=>2,'querySucc'=>$uspeh);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        
    }
    // </editor-fold>
    
    // </editor-fold>
    
   // <editor-fold desc="Funkcije za provere" defaultstate="collapsed">
    
   // <editor-fold desc="daLiJeSlobodanUsername-ndj" defaultstate="collapsed">
    public function daLiJeSlobodanUsername($usernm){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $kveri = $this->dbh->query("SELECT Ime FROM Korisnik WHERE UsernameString='$usernm';");
        $kveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $kveri->fetchAll();
        
        $uspeh=true; // Uspesan kveri
        if (count($result) == 0)
            $uspeh= false;
                
         // Vratimo json string
         $jsonNiz=array('opID'=>3,'querySucc'=>$uspeh);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
            
    }
    // </editor-fold>
    
      // <editor-fold desc="daLiKorisnikImaRecept-ddj" defaultstate="collapsed">
    public function daLiKorisnikImaRecept($idUser,$naziv){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $kveri = $this->dbh->query("SELECT ID FROM Recept WHERE ID_Vlasnika=$idUser AND Naziv IN ('$naziv');");
        $kveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $kveri->fetchAll();
        
        $uspeh=true; // Uspesan kveri
        if (count($result) == 0)
            $uspeh= false;
                
         // Vratimo json string
         $jsonNiz=array('opID'=>3,'querySucc'=>$uspeh);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
            
    }
    // </editor-fold>
    
    // <editor-fold desc="autentifikuj-ddj" defaultstate="collapsed">
    public function autentifikuj($usernm,$passwrd){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $kveri = $this->dbh->query("SELECT Ime FROM Korisnik WHERE UsernameString='$usernm' AND PasswordString='$passwrd';");
        $kveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $kveri->fetchAll();
        
        $uspeh=true; // Uspesan kveri
        if (count($result) == 0)
            $uspeh= false;
                
         // Vratimo json string
         $jsonNiz=array('opID'=>3,'querySucc'=>$uspeh);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
            
    }
    // </editor-fold>

   // </editor-fold>
    
   // <editor-fold desc="Funkcije sa primitivnom povratnom vrednosti" defaultstate="collapsed">
    
    // <editor-fold desc="getIDGrupePrekoNaziva-ddj" defaultstate="collapsed">
    // Vraca ID ako bude pronadjena grupa sa nazivom, inace, vraca -1
    // Funkciju ne poziva korisnik vec se ona koristi unutar drugih (???)
    public function getIDGrupePrekoNaziva($mNaziv)
    {
        if ($this->conIsSet==false){
         return -1;
        }
        
        $kveri = $this->dbh->query("SELECT * FROM Grupa_korisnika WHERE Naziv='$mNaziv'");
        $kveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $kveri->fetchAll();
        
        if(count($result) > 0) {
            foreach($result as $r) {
                    $ajdi = $r['ID'];
                }
        }
        else // Inace, nijedna grupa nije pronadjena sa zadatim imenom
            $ajdi=-1;
        
        return $ajdi;
    }
    // </editor-fold>
    
    
    
    // </editor-fold>
    
   // <editor-fold desc="Funkcije sa slozenom povratnom vrednosti" defaultstate="collapsed">
    
    // <editor-fold desc="pribaviNamirniceZaFrizider-ndj" defaultstate="collapsed">
    public function pribaviNamirniceZaFrizider($idGrupe)
    {
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $selectKveri = $this->dbh->query("SELECT HR.Naziv, SF.Kolicina FROM Sadrzina_Frizidera SF INNER JOIN Hrana HR WHERE HR.ID = SF.ID_Hrana AND SF.ID_Grupa = $idGrupe");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh = true;
        if ($selectKveri==false)
            $uspeh=false;        
        $brojObjekata = count($result);
        
        foreach ($result as $r)    
        {
            $pom = array('naziv' => $r['Naziv'],
                'kolicina' => $r['Kolicina']);
            $data[] = $pom;
        }
        
        $jsonNiz=array('opID'=>6,'querySucc'=>$uspeh,'objID'=>'namirnice','num'=>$brojObjekata,'data'=>$data);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;        
    }
    // </editor-fold>
    
    // <editor-fold desc="vratiJednuHranu-ddj" defaultstate="collapsed">
    public function vratiJednuHranu($mID)
    {
       if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $selectKveri = $this->dbh->query("SELECT * FROM Hrana WHERE ID=$mID");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh=true;
        if ($selectKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
        $data=null; // Inicijalno, podaci ne postoje
        if(count($result) > 0) {
            foreach($result as $r) {

                $data = array('naziv'=>$r['Naziv'],
                    'kalorije'=>$r['Kalorije'],
                    'proteini'=>$r['Proteini'],
                    'masti'=>$r['Masti'],
                    'ugljeni_hidrati'=>$r['Ugljeni_hidrati'],
                    'jestejelo'=>$r['JesteJelo'],
                    'sastojci'=>$r['Sastojci']);
                    
                }
        }
        
        $jsonNiz=array('opID'=>5,'querySucc'=>$uspeh,'objID'=>'hrana','num'=>1,'data'=>$data);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
    // </editor-fold>
    
    // <editor-fold desc="vratiNizHrane-ddj" defaultstate="collapsed">
    
    // <editor-fold desc="vratiJedanFunFact-ndj" defaultstate="collapsed">
    public function vratiJedanFunFact(){
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $selectKveri = $this->dbh->query("SELECT * FROM Kviz WHERE ID = (SELECT MAX(ID) FROM Kviz)");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetch();
        $max = $result['ID'];
        
        $selectKveri = $this->dbh->query("SELECT * FROM Kviz WHERE ID = (SELECT MIN(ID) FROM Kviz)");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetch();      
        $min = $result['ID'];        
                
        $broj = rand($min, $max);
        $selectKveri = $this->dbh->query("SELECT * FROM Kviz WHERE ID = $broj");
        while ($selectKveri==false)
        {
            $selectKveri = $this->dbh->query("SELECT * FROM Kviz WHERE ID = $broj");
        }
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetch(); 
        $fact = $result['Fun_Fact'];
                
        
        $uspeh=true;
        if ($selectKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
        $data=null; // Inicijalno, podaci ne postoje
                
        $jsonNiz=array('opID'=>5,'querySucc'=>$uspeh,'objID'=>$broj,'funFact'=>$fact);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
// </editor-fold>
    
    
    
    // Q: Na osnovu cega vracas niz hrane?
    // A: Preko parametra fcije u kojoj se redjaju id-jevi svih hrana za koje
    //    zelimo da pribavimo podatke
    
    public function vratiNizHrane($strIDjeviHrane){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $selectKveri = $this->dbh->query("SELECT * FROM Hrana WHERE ID IN ($strIDjeviHrane)");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh = true;
        if ($selectKveri==false)
            $uspeh=false;
        
        $brojObjekata = count($result);
        
        $data=null;
        if(count($result) > 0) {
            foreach($result as $r) {
                    // Populisemo trenunu hranu podacima
                    $tHrana = array('id'=>$r['ID'],
                    'naziv'=>$r['Naziv'],
                    'kalorije'=>$r['Kalorije'],
                    'proteini'=>$r['Proteini'],
                    'masti'=>$r['Masti'],
                    'ugljeni_hidrati'=>$r['Ugljeni_hidrati'],
                    'jestejelo'=>$r['JesteJelo'],
                    'sastojci'=>$r['Sastojci']);
                    
                    // Dodamo hranu u glavni niz
                    $data[]=$tHrana;
                }       
        }
        
        $jsonNiz=array('opID'=>6,'querySucc'=>$uspeh,'objID'=>'hrana','num'=>$brojObjekata,'data'=>$data);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
        
    }
    
    // </editor-fold>
    
    // <editor-fold desc="vratiSveNamirnice" defaultstate="collapsed">
    
    // Vraca sve namirnice, iskljucujuci hranu
    
    public function vratiSveNamirnice(){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $selectKveri = $this->dbh->query("SELECT * FROM Hrana WHERE JesteJelo=0");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh = true;
        if ($selectKveri==false)
            $uspeh=false;
        
        $brojObjekata = count($result);
        
        $data=null;
        if(count($result) > 0) {
            foreach($result as $r) {
                    // Populisemo trenunu hranu podacima
                    $tHrana = array('id'=>$r['ID'],
                    'naziv'=>$r['Naziv'],
                    'kalorije'=>$r['Kalorije'],
                    'proteini'=>$r['Proteini'],
                    'masti'=>$r['Masti'],
                    'ugljeni_hidrati'=>$r['Ugljeni_hidrati']);
                    
                    // Dodamo hranu u glavni niz
                    $data[]=$tHrana;
                }       
        }
        
        $jsonNiz=array('opID'=>6,'querySucc'=>$uspeh,'objID'=>'sveNamirnice','num'=>$brojObjekata,'data'=>$data);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
        
    }
    
    // </editor-fold>
   
   
     // <editor-fold desc="dodajKorisnikaUZajednickuGrupu-ddj" defaultstate="collapsed">
    
    public function dodajKorisnikaUZajednickuGrupu($idGrupe,$username){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
    
        $idStareGrupe = $this->getIDGrupePrekoNaziva($username."_grupa");
        $idZaPovratak=-1;
        $uspeh=false;
        if ($idStareGrupe>-1){
            
        $premestanjeKorisnika = $this->premestiKorisnike($idStareGrupe,$idGrupe);
        if ($premestanjeKorisnika==true){
            
            $premestanjeRecepata = $this->premestiRecepteGrupe($idStareGrupe,$idGrupe);
            if ($premestanjeRecepata==true){
                
                $premestanjeSadrzajaFrizidera = $this->premestiSadrzinuFrizidera($idStareGrupe,$idGrupe);
                if ($premestanjeSadrzajaFrizidera==true){
                    $promeniGrupu= $this->kreirajNovuGrupu($idGrupe);
                    if ($promeniGrupu>0){
                        $uspeh=true;
                        $idZaPovratak = $promeniGrupu;
                    }
                }
            }
        }
     }
        
        $jsonNiz=array('opID'=>6,'querySucc'=>$uspeh,'idNoveGrupe'=>$idZaPovratak);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
        
    }
    
    // </editor-fold>
   
    // <editor-fold desc="premestiKorisnikaUGrupu-ddj" defaultstate="collapsed">
    
    public function premestiKorisnikaUGrupu($idGrupe,$username){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
    
        $idStareGrupe = $this->getIDGrupePrekoNaziva($username."_grupa");
        $idZaPovratak=-1;
        $uspeh=false;
        if ($idStareGrupe>-1){
            
        $premestanjeKorisnika = $this->premestiKorisnike($idStareGrupe,$idGrupe);
        if ($premestanjeKorisnika==true){
            
            $premestanjeRecepata = $this->premestiRecepteGrupe($idStareGrupe,$idGrupe);
            if ($premestanjeRecepata==true){
                
                $premestanjeSadrzajaFrizidera = $this->premestiSadrzinuFrizidera($idStareGrupe,$idGrupe);
                if ($premestanjeSadrzajaFrizidera==true){
                    
                    $uspeh=true;
                    $idZaPovratak = $idGrupe;
                
                }
            }
        }
     }
        
        $jsonNiz=array('opID'=>6,'querySucc'=>$uspeh,'idNoveGrupe'=>$idZaPovratak);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
        
    }
    
    // </editor-fold>
   
   // <editor-fold desc="vratiKorisnikaUGrupu-ddj" defaultstate="collapsed">
    
    public function vratiKorisnikaUGrupu($idGrupe,$username){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
         
        $idNoveGrupe = $this->getIDGrupePrekoNaziva($username."_grupa");
        
        $uspeh=false;
        if ($idNoveGrupe>-1){
             
        $premestanjeKorisnika = $this->premestiKorisnika($idNoveGrupe,$username);
        if ($premestanjeKorisnika==true){
           // echo json_encode(array('signal2'=>$idNoveGrupe));
            $premestanjeRecepata = $this->premestiRecepteKorisnika($idNoveGrupe,$username);
            if ($premestanjeRecepata==true){
                $uspeh=true;
                $idZaPovratak = $idNoveGrupe;
            }
        }
     }
        
        $jsonNiz=array('opID'=>6,'querySucc'=>$uspeh);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
        
    }
    
    // </editor-fold>
   
   // <editor-fold desc="vratiUniqueNazivGrupe-ddj" defaultstate="collapsed">
   public function vratiUniqueNazivGrupe($idGrupe){

        $kveri = $this->dbh->query("SELECT * FROM Grupa_korisnika WHERE ID=$idGrupe");
        $kveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $kveri->fetchAll();
        
        //echo json_encode(array('test3'=>'fetchovano'));
        if(count($result) > 0) {
            foreach($result as $r) {
                    $ajdi = $r['Naziv'];
                }
        }
        else // Inace, nijedna grupa nije pronadjena sa zadatim imenom
            $ajdi="-1";
        
        //echo json_encode(array('test4'=>$ajdi));
        return $ajdi;
   }
   // </editor-fold>
   
    // <editor-fold desc="kreirajNovuGrupu-ddj" defaultstate="collapsed">
    
    public function kreirajNovuGrupu($idGrupe){

       $nazivStareGrupe = $this->vratiUniqueNazivGrupe($idGrupe);
     // echo json_encode(array('test'=>$idGrupe));
      $idNoveGrupe=-1;
      $uspeh=false;
       
      if ($nazivStareGrupe!="-1"){ // Ako je grupa kreirana, odradi sve ostalo
       
         $nazivNoveGrupeIme = $nazivStareGrupe."_zajednica_".rand();
         $dodavanjeNoveGrupe= $this->dodajGrupu($nazivNoveGrupeIme);
         
         if ($dodavanjeNoveGrupe==true){
            $idNoveGrupe = $this->getIDGrupePrekoNaziva($nazivNoveGrupeIme);
            
            if ($idNoveGrupe!=-1){
             
            $premestanjeKorisnika = $this->premestiKorisnike($idGrupe,$idNoveGrupe);
            if ($premestanjeKorisnika==true){
                $premestanjeRecepata = $this->premestiRecepteGrupe($idGrupe,$idNoveGrupe);
                if ($premestanjeRecepata==true){
                    $premestanjeSadrzajaFrizidera = $this->premestiSadrzinuFrizidera($idGrupe,$idNoveGrupe);
                    if ($premestanjeSadrzajaFrizidera==true){
                        $uspeh=true;
                    }
                }
            }
         }
        }
      }
        
       if ($uspeh==true)
        return $idNoveGrupe;
       else
        return -1;
    }
    
    // </editor-fold>
    
   
   // <editor-fold desc="premestiKorisnike-ddj" defaultstate="collapsed">
   public function premestiKorisnike($idStareGrupe,$idNoveGrupe){
       if ($this->conIsSet==false){
         return false;
        }
        
        $kveri = $this->dbh->query("UPDATE Korisnik SET ID_Grupe=$idNoveGrupe WHERE ID_Grupe=$idStareGrupe");
        $uspeh=true;
        
        return $uspeh;
   }
   // </editor-fold>
   
   // <editor-fold desc="premestiKorisnika-ddj" defaultstate="collapsed">
   public function premestiKorisnika($idGrupe,$username){
       if ($this->conIsSet==false){
         return false;
        }
        
        $kveri = $this->dbh->query("UPDATE Korisnik SET ID_Grupe=$idGrupe WHERE UsernameString='$username'");
        $uspeh=true;
        
        return $uspeh;
   }
   // </editor-fold>
   
   // <editor-fold desc="premestiRecepteGrupe-ddj" defaultstate="collapsed">
   public function premestiRecepteGrupe($idStareGrupe,$idNoveGrupe){
       if ($this->conIsSet==false){
         return false;
        }
        
        $kveri = $this->dbh->query("UPDATE Sadrzina_Frizidera SET ID_Grupa=$idNoveGrupe WHERE ID_Grupa=$idStareGrupe");
        
        $uspeh=true;
        
        return $uspeh;
   }
   // </editor-fold>
   
   // <editor-fold desc="premestiSadrzinuFrizidera-ddj" defaultstate="collapsed">
   public function premestiSadrzinuFrizidera($idStareGrupe,$idNoveGrupe){
       if ($this->conIsSet==false){
         return false;
        }
        
        $kveri = $this->dbh->query("UPDATE Recepti_Grupe SET ID_Grupa=$idNoveGrupe WHERE ID_Grupa=$idStareGrupe");
        
        $uspeh=true;
        return $uspeh;
   }
   // </editor-fold>
   
    // <editor-fold desc="vratiPodatkeOKorisniku-ddj" defaultstate="collapsed">
    public function vratiPodatkeOKorisniku($mUsername)
    {
       if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $selectKveri = $this->dbh->query("SELECT * FROM Korisnik WHERE UsernameString='$mUsername'");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh=true;
        if ($selectKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
        $data=null; // Inicijalno, podaci ne postoje
        if(count($result) > 0) {
            foreach($result as $r) {

                $data = array('id'=>$r['ID'],
                    'ime'=>$r['Ime'],
                    'prezime'=>$r['Prezime'],
                    'id_grupe'=>$r['ID_Grupe'],
                    'pol'=>$r['Pol'],
                    'godiste'=>$r['Godiste'],
                    'visina'=>$r['Visina'],
                    'masa'=>$r['Masa'],
                    'nivo_aktivnosti'=>$r['Nivo_Aktivnosti'],
                    'rezim_ishrane'=>$r['Rezim_Ishrane']);
                }
        }
        
        $jsonNiz=array('opID'=>5,'querySucc'=>$uspeh,'objID'=>'podaciOKorisniku','num'=>1,'data'=>$data);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
    // </editor-fold>
    
     // <editor-fold desc="vratiPodatkeOKorisnikuID-ddj" defaultstate="collapsed">
    public function vratiPodatkeOKorisnikuID($mUserID)
    {
       if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $selectKveri = $this->dbh->query("SELECT * FROM Korisnik WHERE ID=$mUserID");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh=true;
        if ($selectKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
        $data=null; // Inicijalno, podaci ne postoje
        if(count($result) > 0) {
            foreach($result as $r) {

                $data = array('id'=>$r['ID'],
                    'ime'=>$r['Ime'],
                    'prezime'=>$r['Prezime'],
                    'id_grupe'=>$r['ID_Grupe'],
                    'pol'=>$r['Pol'],
                    'godiste'=>$r['Godiste'],
                    'visina'=>$r['Visina'],
                    'masa'=>$r['Masa'],
                    'nivo_aktivnosti'=>$r['Nivo_Aktivnosti'],
                    'rezim_ishrane'=>$r['Rezim_Ishrane']);
                }
        }
        
        $jsonNiz=array('opID'=>5,'querySucc'=>$uspeh,'objID'=>'podaciOKorisniku','num'=>1,'data'=>$data);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
    // </editor-fold>
    
    // <editor-fold desc="vratiKorisnikeGrupe-ddj" defaultstate="collapsed">
   
    public function vratiKorisnikeGrupe($idGrupe){
        
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }

        $selectKveri = $this->dbh->query("SELECT * FROM Korisnik WHERE ID_Grupe IN ($idGrupe)");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh = true;
        if ($selectKveri==false)
            $uspeh=false;
        
        $brojObjekata = count($result);
        
        $data=null;
        if(count($result) > 0) {
            foreach($result as $r) {
                    // Populisemo trenutnog korisnika podacima
                    $tUser = array('id'=>$r['ID'],
                        'ime'=>$r['Ime'],
                        'prezime'=>$r['Prezime'],
                        'username'=>$r['UsernameString']
                        );
                    
                    // Dodamo korisnika u glavni niz
                    $data[]=$tUser;
                }       
        }
        
        $jsonNiz=array('opID'=>6,'querySucc'=>$uspeh,'objID'=>'korisniciGrupe','num'=>$brojObjekata,'data'=>$data);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
    
    // </editor-fold>
   
    // <editor-fold desc="vratiRecepteGrupe-ddj" defaultstate="collapsed">
   
    public function vratiRecepteGrupe($idGrupe){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $selectKveri = $this->dbh->query("SELECT * FROM Recept r, Recepti_Grupe rg, Korisnik k WHERE rg.ID_Recept=r.ID AND r.ID_Vlasnika=k.ID AND rg.ID_Grupa=$idGrupe");
        
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh = true;
        if ($selectKveri==false)
            $uspeh=false;
        
        $brojObjekata = count($result);
        
        $data=null;
        if(count($result) > 0) {
            foreach($result as $r) {
                    // Populisemo trenutni recept podacima
                    $tRecept = array('id'=>$r['ID'],
                        'sastojci'=>$r['Sastojci'],
                        'kolicina'=>$r['Kolicina'],
                        'id_vlasnika'=>$r['ID_Vlasnika'],
                        'username'=>$r['UsernameString'],
                        'naziv'=>$r['Naziv']
                        );
                    
                    // Dodamo recept u glavni niz
                    $data[]=$tRecept;
                }       
        }
        
        $jsonNiz=array('opID'=>6,'querySucc'=>$uspeh,'objID'=>'receptiGrupe','num'=>$brojObjekata,'data'=>$data);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
    
    // </editor-fold>
    
    // <editor-fold desc="izmeniRecept-ddj" defaultstate="collapsed">
    public function izmeniRecept($mIDRecepta,$mNaziv,$mSastojci,$mKolicina){
 
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>7,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $updateKveri = $this->dbh->query("UPDATE Recept SET Naziv='$mNaziv', Sastojci='$mSastojci', Kolicina='$mKolicina' WHERE ID=$mIDRecepta");
        
        $uspeh=true;
        if ($updateKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
        $jsonNiz=array('opID'=>7,'querySucc'=>$uspeh);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
    // </editor-fold>
    
     // <editor-fold desc="obrisiRecept-ddj" defaultstate="collapsed">
    public function obrisiRecept($idRecepta){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>8,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $delKveri0 = $this->dbh->query("DELETE FROM Recepti_Grupe WHERE ID_Recept=$idRecepta");
        $delKveri1 = $this->dbh->query("DELETE FROM Recept WHERE ID=$idRecepta");
        
        $uspeh=false;
        if ($delKveri1==true && $delKveri0==true) // Proverava se uspeh kverija
            $uspeh=true;
        
        $jsonNiz=array('opID'=>8,'querySucc'=>$uspeh);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
    // </editor-fold>
    
    // <editor-fold desc="ukloniNamirnicuIzFrizidera-ndj" defaultstate="collapsed">
    public function ukloniNamirnicuIzFrizidera($idGrupe, $nazivNamirnice)
    {
    if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>8,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $deleteKveri = $this->dbh->query("DELETE FROM sadrzina_frizidera WHERE ID_Grupa = $idGrupe AND ID_Hrana = (SELECT ID FROM hrana WHERE Naziv='$nazivNamirnice')");
        $selectKveri = $this->dbh->query("SELECT ID FROM Hrana WHERE Naziv = '$nazivNamirnice'");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $ajdi=0;
        if(count($result) > 0) {
            foreach($result as $r) {
                    $ajdi = $r['ID'];
                }
        } 
  
        $uspeh = false;
        if($deleteKveri == true && $selectKveri == true)
        {
            $uspeh = true;
        }
        
        $jsonNiz=array('opID'=>8,'querySucc'=>$uspeh, 'hranaID'=>$ajdi);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
    // </editor-fold>
    
    // <editor-fold desc="vratiRecepteKorisnika-ddj" defaultstate="collapsed">
   
    public function vratiRecepteKorisnika($idKorisnika){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $selectKveri = $this->dbh->query("SELECT * FROM Recept WHERE ID_Vlasnika=$idKorisnika");
        
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh = true;
        
        $brojObjekata = count($result);
        
        $data=null;
        if(count($result) > 0) {
            foreach($result as $r) {
                    // Populisemo trenutni recept podacima
                    $tRecept = array('id'=>$r['ID'],
                        'sastojci'=>$r['Sastojci'],
                        'kolicina'=>$r['Kolicina'],
                        'id_vlasnika'=>$r['ID_Vlasnika'],
                        'naziv'=>$r['Naziv']
                        );
                    
                    // Dodamo recept u glavni niz
                    $data[]=$tRecept;
                }       
        }
        
        $jsonNiz=array('opID'=>6,'querySucc'=>$uspeh,'objID'=>'receptiKorisnika','num'=>$brojObjekata,'data'=>$data);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
    
    // </editor-fold>
    
    // <editor-fold desc="vratiHranuIzFrizideraPrekoGrupe-ddj" defaultstate="collapsed">
    public function vratiHranuIzFrizideraPrekoGrupe($mIdGrupe){
        
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $selectKveri = $this->dbh->query("SELECT * FROM Sadrzina_Frizidera WHERE ID_Grupa=$mIdGrupe");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh = true;
        if ($selectKveri==false)
            $uspeh=false;
        
        $brojObjekata = count($result);
        
        $data=null;
        if(count($result) > 0) {
            foreach($result as $r) {
                    // Populisemo trenunu hranu podacima
                    $tHrana = array('naziv'=>$r['Naziv'],
                    'kalorije'=>$r['Kalorije'],
                    'proteini'=>$r['Proteini'],
                    'masti'=>$r['Masti'],
                    'ugljeni_hidrati'=>$r['Ugljeni_hidrati'],
                    'jestejelo'=>$r['JesteJelo'],
                    'sastojci'=>$r['Sastojci']);
                    
                    // Dodamo hranu u glavni niz
                    $data[]=$tHrana;
                }       
        }
        
        $jsonNiz=array('opID'=>6,'querySucc'=>$uspeh,'objID'=>'hrana','num'=>$brojObjekata,'data'=>$data);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
    // </editor-fold>
    
    // <editor-fold desc="vratiJedanKviz-ddj" defaultstate="collapsed">
    public function vratiJedanKviz(){
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $idKviza = $this->vratiDanasnjiKviz();
        
        $selectKveri = $this->dbh->query("SELECT * FROM Kviz WHERE ID=$idKviza");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh=true;
        if ($selectKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
        $data=null; // Inicijalno, podaci ne postoje
        if(count($result) > 0) {
            foreach($result as $r) {

                $data = array('pitanje'=>$r['Pitanje'],
                    'odgovori'=>$r['Odgovori'],
                    'funfact'=>$r['Fun_Fact']);
                    
                }
        }
        
        $jsonNiz=array('opID'=>5,'querySucc'=>$uspeh,'objID'=>'kviz','num'=>1,'data'=>$data);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
    // </editor-fold>
    
    // <editor-fold desc="Vrati jedan recept" defaultstate="collapsed">
    
    // Sta treba funkcija da vrati kao Sastojke: id-jeve ili objekte hrane 
    
    public function vratiJedanRecept($mIDRecepta) {
         if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>false);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $selectKveri = $this->dbh->query("SELECT * FROM Recept WHERE ID=$mIDRecepta");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh=true;
        if ($selectKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
        $data=null; // Inicijalno, podaci ne postoje
        if(count($result) > 0) {
            foreach($result as $r) {

                $data = array('id'=>$r['ID'],
                    'naziv'=>$r['Naziv'],
                    'sastojci'=>$r['Sastojci'],
                    'kolicina'=>$r['Kolicina'],
                    'vlasnik'=>$r['ID_Vlasnika']);
                    
                }
        }
        
        $jsonNiz=array('opID'=>5,'querySucc'=>$uspeh,'objID'=>'kviz','num'=>1,'data'=>$data);
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
    }
    
    // </editor-fold>
    
    // <editor-fold desc="Vrati niz recepta" defaultstate="collapsed">
    
    // Svodi se na funkciju: vrati jedan recept
    
    public function vratiNizRecepta($stringIDjeviRecepata){
        
        
    }
    
    // </editor-fold>
    
    // <editor-fold desc="premestiRecepteKorisnika-ddj" defaultstate="collapsed">

    public function premestiRecepteKorisnika($idGrupe,$username){
        if ($this->conIsSet==false){
         return false;
        }
        
        $selectKveri = $this->dbh->query("SELECT rec.* FROM Recept rec, Korisnik kor WHERE rec.ID_Vlasnika=kor.ID AND kor.UsernameString='$username'");
        $selectKveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $selectKveri->fetchAll();
        
        $uspeh=true;
        if ($selectKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
        if(count($result) > 0) {
            foreach($result as $r) {

                $receptID=$r['ID'];
                $updateKveri = $this->dbh->query("UPDATE Recepti_Grupe SET ID_Grupa=$idGrupe WHERE ID_Recept=$receptID");
                }
        }
        
        return true;
    }
    
    // </editor-fold>
    
    // </editor-fold>
    
   // <editor-fold desc="Privatne opste funkcije" defaultstate="collapsed">
    
    // <editor-fold desc="dodajGrupu-ddj" defaultstate="collapsed">
    public function dodajGrupu($mNaziv)
    {
        if ($this->conIsSet==false){
         return false;
        }
        
        $insertKveri = $this->dbh->query("INSERT INTO Grupa_korisnika(Naziv) VALUES ('$mNaziv');");
   
        $uspeh=true;
        if ($insertKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
        return $uspeh;
    }
    // </editor-fold>
    
     // <editor-fold desc="daLiKorisnikImaReceptProvera-ddj" defaultstate="collapsed">
    public function daLiKorisnikImaReceptProvera($idUser,$naziv){
        
        if ($this->conIsSet==false){
         return false;
        }
        
        $kveri = $this->dbh->query("SELECT ID FROM Recept WHERE ID_Vlasnika=$idUser AND Naziv IN ('$naziv');");
        $kveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $kveri->fetchAll();
        
        $uspeh=true; // Uspesan kveri
        if (count($result) == 0)
            $uspeh= false;
                
         return $uspeh;
    }
    // </editor-fold>
    
    // <editor-fold desc="dodajKorisnika-ddj" defaultstate="collapsed">
    public function dodajKorisnikaPrekoIDGrupe($mUsername, $mPassword,$mIme,$mPrezime,$mIDGrupe,$mPol,
            $mGodiste,$mVisina,$mMasa,$mNivoAktivnosti,$mRezimIshrane){
        
        if ($this->conIsSet==false){
            return false;
        }
        
        $insertKveri = $this->dbh->query("INSERT INTO Korisnik(Ime,Prezime,UsernameString,PasswordString,ID_Grupe,"
                . "Pol,Godiste,Visina,Masa,Nivo_Aktivnosti,Rezim_Ishrane) VALUES ('$mIme','$mPrezime',"
                . "'$mUsername','$mPassword',$mIDGrupe,$mPol,$mGodiste,$mVisina,$mMasa,$mNivoAktivnosti,$mRezimIshrane)");
        
        $uspeh=true;
        if ($insertKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
         return $uspeh;
    }
    // </editor-fold>
    
    
   // </editor-fold>
    
    //<editor-fold desc="dodajKorisnika-ddj" defaultstate="collapsed">
    public function dodajKviz($pitanje,$odgovori,$funfact){
        
        if ($this->conIsSet==false){
            return false;
        }
        
        $insertKveri = $this->dbh->query("INSERT INTO Kviz(Pitanje,Odgovori,Fun_Fact) VALUES ('$pitanje','$odgovori','$funfact')");
        
        $uspeh=true;
        if ($insertKveri==false) // Proverava se uspeh kverija
            $uspeh=false;
        
         return $uspeh;
    }
    // </editor-fold>
    
    // <editor-fold desc="Ostalo" defaultstate="collapsed">
    
    public function vratiIDRecepta($korisnikID,$naziv){
        
        if ($this->conIsSet==false){
         return -1;
        }
        
        $kveri = $this->dbh->query("SELECT * FROM Recept WHERE ID_Vlasnika=$korisnikID AND Naziv='$naziv'");
        $kveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $kveri->fetchAll();
        
        if(count($result) > 0) {
            foreach($result as $r) {
                    $ajdi = $r['ID'];
                }
        }
        else // Inace, nijedna grupa nije pronadjena sa zadatim imenom
            $ajdi=-1;
        
        return $ajdi;
    }
    
    public function vratiDanasnjiKviz(){
        $seed = floor(time()/86400);
        srand($seed);
        
        $maxBrFunFactova=1;
        
        $kveri = $this->dbh->query("SELECT * FROM Kviz");
        $kveri->setFetchMode(PDO::FETCH_ASSOC);
        $result = $kveri->fetchAll();
        
        $maxBrFunFactova = count($result);
        
        $idFF = rand(1,$maxBrFunFactova);
        
        return $idFF;
    }
    
    /// Sluzi za testiranje objekta (izmeni kod u telu, ukoliko je potrebno)
    public function test(){
       // echo "<br/><br/> Test Uspesan! <br/><br/>";

        $jsonNiz=array('opID'=>0,'querySucc'=>'true','secretCode'=>'cao mama');
        $jsonStr = json_encode($jsonNiz);
        echo $jsonStr;
        
    }
    
    public function vratiSveKorisnikeKaoNiz(){
       
        if ($this->conIsSet==false){
         $jsonNiz=array('opID'=>1,'querySucc'=>-1);
         $jsonStr = json_encode($jsonNiz);
         echo $jsonStr;
        }
        
        $kveri = $this->dbh->query("SELECT * FROM Korisnik");
        $kveri->setFetchMode(PDO::FETCH_ASSOC);
        $rez = $kveri->fetchAll();
        
        $niz = array();
        if(count($rez) > 0) {
            foreach($rez as $r) {
                   // $kor = new Korisnik($r['usernameString'],$r['passwordString'],$r['userMode']);
                    $niz[] = new Korisnik($r['usernameString'],$r['passwordString'],$r['userMode']);
                }
        }
        
        return $niz;
    }
      // </editor-fold>
    
    /************************************/
}

?>