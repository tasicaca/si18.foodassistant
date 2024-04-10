<?php

class Korisnik{
    
    public $username;
    public $password;
    public $ime;
    public $prezime;
    public $idGrupe;
    
    public function __construct($mUser,$mPass,$mIme,$mPrezime,$mIDGrupe){
        if ($mUser=="" || $mUser==null)
            $this->username="";
        else
            $this->username=$mUser;
            
        if ($mPass=="" || $mPass==null)
            $this->password="";
        else
            $this->password=$mPass;
            
       if ($mIme=="" || $mIme==null)
            $this->ime="";
        else
            $this->ime=$mIme;
        
       if ($mPrezime=="" || $mPrezime==null)
            $this->prezime="";
        else
            $this->prezime=$mPrezime;
        
       if ($mIDGrupe<0 || $mIDGrupe==null)
            $this->idGrupe=-1;
        else
            $this->idGrupe=$mIDGrupe;
    }
    
}

?>