<?php
    header('Content-type=application/json; charset=utf-8');
    // <editor-fold desc="Include files" defaultstate="collapsed">
    // Ukljuci neophodne fajlove
    include_once './klase/DBClass.php';
    include_once './klase/Korisnik.php';
    include_once './pluginovi/Smarti/MySmarty.php';
    
    // </editor-fold>
    
    
    // <editor-fold desc="Glavni Objekti" defaultstate="collapsed">
    $smarti = new MySmarty();
    // Kreiraj handler za konekciju
    $konekcija = new DBConnection();
    // </editor-fold>

    // <editor-fold desc="Promenljive" defaultstate="collapsed">
    $debugModeOn=false;
    $trenMod=0;
    // </editor-fold>
    
    
    // <editor-fold desc="Podesavanja" defaultstate="collapsed">
    if (isset($_GET['debug']))
    {
        if ($_GET['debug']=="true")
              $debugModeOn=true;
    }
    if (isset($_POST['debug']))
    {
        if ($_POST['debug']=="true")
              $debugModeOn=true;
    }
    
    
    if (isset($_GET['check'])){
    
       if ($_GET['check']=='daLiPostojiUser'){
           if (isset($_GET['user'])){
               $konekcija->daLiJeSlobodanUsername($_GET['user']);
           }
        }
        else if ($_GET['check']=='autentifikuj'){
           if ( isset($_GET['user']) && isset($_GET['pass']) ){
               $konekcija->autentifikuj($_GET['user'],$_GET['pass']);
           }
        }   
    }
    
    if (isset($_GET['add'])){
        if ($_GET['add']=='noviKorisnik'){ // Ukoliko dodajemo novog korisnika, proveri sva polja
            if (isset($_GET['user']) && isset($_GET['pass']) && isset($_GET['ime']) && isset($_GET['prezime']) &&
                isset($_GET['pol']) && isset($_GET['god']) && isset($_GET['visina']) && isset($_GET['masa']) &&
        isset($_GET['nivAkt']) && isset($_GET['rezIsh']) ){
                // Ukoliko su sva polja ispravna, kreiraj korisnika
            $konekcija->dodajNovogKorisnika($_GET['user'], $_GET['pass'], $_GET['ime'], $_GET['prezime'], $_GET['pol'], 
                    $_GET['god'], $_GET['visina'], $_GET['masa'], $_GET['nivAkt'], $_GET['rezIsh']);
                }
        }
    }
        
    if (isset($_GET['mod'])){
                
        if ($_GET['mod']=='test'){
            $konekcija->test();
        }
        else if ($_GET['mod']=='dodajkorisnika')
        {
            $trenMod=1;
        }
        else if ($_GET['mod']=='dodajKviz')
        {
            $trenMod=3;
        }
        else if ($_GET['mod']=='dodajhranu')
        {
            $trenMod=2;
            if (isset($_POST['naziv']) && isset($_POST['kalorije']) && isset($_POST['proteini']) &&
                    isset($_POST['masti']) && isset($_POST['ugh'])){
                echo $konekcija->dodajNamirnicu($_POST['naziv'], $_POST['kalorije'], $_POST['proteini'], $_POST['masti'], $_POST['ugh']);
            }
        }
        else if ($_GET['mod']=='korisniciGrupe'){  
            
            if (isset($_GET['idGrupe'])){
                if ($_GET['idGrupe']>0){
                    $grupaID=$_GET['idGrupe'];
                    $konekcija->vratiKorisnikeGrupe($grupaID);
                }
            }  
        }
        else if ($_GET['mod']=='receptiGrupe'){  
            if (isset($_GET['idGrupe'])){
                if ($_GET['idGrupe']>0){
                    $grupaID = $_GET['idGrupe'];
                    $konekcija->vratiRecepteGrupe($grupaID);
                }
            }  
        }
        else if ($_GET['mod']=='vratiPodatkeOKorisniku'){
            if (isset($_GET['user'])){
                $username = $_GET['user'];
                $konekcija->vratiPodatkeOKorisniku($username);
            }
        }
        else if ($_GET['mod']=='vratiSveNamirnice'){
            $konekcija->vratiSveNamirnice();
        }
        else if ($_GET['mod']=='dodajReceptZaKorisnika'){
            if (isset($_GET['userID']) && isset($_GET['naziv'])
            && isset($_GET['sastojci']) && isset($_GET['kolicina']) ){
                $idKorisnika = $_GET['userID'];
                $naziv = $_GET['naziv'];
                $sastojci = $_GET['sastojci'];
                $kolicina = $_GET['kolicina'];
                $konekcija->dodajReceptZaKorisnika($idKorisnika,$naziv,$sastojci,$kolicina);
            }
        }
        else if ($_GET['mod']=='dodajReceptZaGrupu'){
            if (isset($_GET['userID']) && isset($_GET['naziv'])
            && isset($_GET['groupID']) ){
                $idKorisnika = $_GET['userID'];
                $naziv = $_GET['naziv'];
                $groupID = $_GET['groupID'];
                $konekcija->dodajReceptZaGrupu($idKorisnika,$naziv,$groupID);
            }
        }
        else if($_GET['mod'] == 'dodajUFrizider')
        {
            $konekcija->dodajNamirnicuUFrizider($_GET['IdGrupa'],$_GET['IdHrana'],$_GET['Kolicina']);
        }
        else if ($_GET['mod']=='vratiRecepteKorisnika'){
            if ( isset($_GET['userID']) ){
                $idKorisnika = $_GET['userID'];
                $konekcija->vratiRecepteKorisnika($idKorisnika);
            }
        }
        else if ($_GET['mod']=='vratiJedanRecept'){
            if ( isset($_GET['receptID']) ){
                $idRecept = $_GET['receptID'];
                $konekcija->vratiJedanRecept($idRecept);
            }
        }
        else if($_GET['mod'] == 'vratiJedanFunFact')
        {
            $konekcija->vratiJedanFunFact();
        }
        else if ($_GET['mod']=='obrisiRecept'){
            if (isset($_GET['receptID'])){
            $idRecepta =  $_GET['receptID'];
            $konekcija->obrisiRecept($idRecepta);
            }
        }
        else if ($_GET['mod']=='izmeniRecept'){
            if (isset($_GET['receptID']) && isset($_GET['naziv']) && 
            isset($_GET['sastojci']) && isset($_GET['kolicina']) ){
            $idRecepta =  $_GET['receptID'];
            $naziv = $_GET['naziv'];
            $kolicina = $_GET['kolicina'];
            $sastojci = $_GET['sastojci'];
            $konekcija->izmeniRecept($idRecepta,$naziv,$sastojci,$kolicina);
            }
        }
        else if($_GET['mod'] == 'pribaviNamirniceZaFrizider')
        {
            $konekcija->pribaviNamirniceZaFrizider($_GET['idGrupe']);
        }
        else if ($_GET['mod']=='kreirajNovuGrupu'){
            if (isset($_GET['idGrupe'])  ){
            $idGrupe =  $_GET['idGrupe'];
            
            $konekcija->kreirajNovuGrupu($idGrupe);
            }
        }
        else if ($_GET['mod']=='dodajKorisnikaUZajednickuGrupu'){
            if (isset($_GET['idGrupe']) && isset($_GET['usernameKorisnika']) ){
            $idGrupe =  $_GET['idGrupe'];
            $user = $_GET['usernameKorisnika'];
            $konekcija->dodajKorisnikaUZajednickuGrupu($idGrupe,$user);
            }
        }
        else if ($_GET['mod']=='vratiPodatkeOKorisnikuID'){
            if (isset($_GET['userID']) ){
            $idKorisnika =  $_GET['userID'];
            $konekcija->vratiPodatkeOKorisnikuID($idKorisnika);
            }
        }
        else if ($_GET['mod']=='premestiKorisnikaUGrupu'){
            if (isset($_GET['idGrupe']) && isset($_GET['usernameKorisnika']) ){
            $idGrupe =  $_GET['idGrupe'];
            $username = $_GET['usernameKorisnika'];
            $konekcija->premestiKorisnikaUGrupu($idGrupe,$username);
            }
        }
        else if ($_GET['mod']=='vratiKorisnikaUGrupu'){
            if (isset($_GET['idGrupe']) && isset($_GET['usernameKorisnika']) ){
            $idGrupe =  $_GET['idGrupe'];
            $username = $_GET['usernameKorisnika'];
            $konekcija->vratiKorisnikaUGrupu($idGrupe,$username);
            }
        }
        else if ($_GET['mod']=='vratiJedanKviz'){
            //$konekcija->();
        }
        else if($_GET['mod'] == 'ukloniNamirnicuIzFrizidera')
        {
            $konekcija->ukloniNamirnicuIzFrizidera($_GET["idGrupe"], $_GET["nazivNamirnice"]);
        }
        
        
    }
    
    // POST NACIN
    
        if (isset($_POST['mod'])){
                
        if ($_POST['mod']=='test'){
            $konekcija->test();
        }
        else if ($_POST['mod']=='dodajkorisnika')
        {
            $trenMod=1;
        }
        else if ($_POST['mod']=='dodajhranu')
        {
            $trenMod=2;
            if (isset($_POST['naziv']) && isset($_POST['kalorije']) && isset($_POST['proteini']) &&
                    isset($_POST['masti']) && isset($_POST['ugh'])){
                echo $konekcija->dodajNamirnicu($_POST['naziv'], $_POST['kalorije'], $_POST['proteini'], $_POST['masti'], $_POST['ugh']);
            }
        }
        else if ($_POST['mod']=='dodajKviz'){
            $trenMod=3;
            if ( isset($_POST['pitanje']) && isset($_POST['tacanOdg']) && isset($_POST['netacan1']) 
                    && isset($_POST['netacan2']) && isset($_POST['netacan3']) && isset($_POST['funfact']) ){
                $pitanje = $_POST['pitanje'];
                $odgovori = $_POST['tacanOdg'].";".$_POST['netacan1'].";".$_POST['netacan2'].";".$_POST['netacan3'];
                $funfact = $_POST['funfact'];
                $konekcija->dodajKviz($pitanje,$odgovori,$funfact);
            }
        }
        
        
    }

    
    // </editor-fold>
 
    // <editor-fold desc="Code Body" defaultstate="collapsed">
    //Izvrsi odredjenu fciju
    if (isset($_GET['execute'])){
    
       if ($_GET['execute']=="test")
        {
            $konekcija->test();
        }
       else if ($_GET['execute']=="hrana")
        {
            
            $hranaID=1;
            if (isset($_GET['hranaID']))
                $hranaID = $_GET['hranaID'];
            $konekcija->vratiJednuHranu($hranaID); // echo-uj za prikaz
        }
        
    }  
    
    
    // </editor-fold>
     
   // <editor-fold desc="Dodele smarty-ju" defaultstate="collapsed">  
    $smarti->assign('debugOn',$debugModeOn); 
    $smarti->assign('trenmod',$trenMod); 
    
   // </editor-fold>
   
    
   // Ovo cemo kasnije ukloniti, jer ne zelimo da prikazemo stranicu kada se zahteva neki podatak
   $smarti->display($smarti->lokacijaFoldera()."pocetna.tpl");

?>