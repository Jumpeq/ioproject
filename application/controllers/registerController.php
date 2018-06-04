<?php
header('Content-type: text/html; charset=utf-8'); 
/**
 * \class Klasa odpowiedzialna za zmienne i funkcje rejestracji 
 */
class Register extends dbConnection {
    private $imie;
    private $naziwsko;
    private $pesel;
	/**
	* Funkcja odpowiedzialna za stworzenie nowego pracownika. Należy podać imię nazwisko i pesel.
	*/
    public function __construct($imie, $nazwisko, $pesel){
        $this->imie = $imie;
        $this->nazwisko = $nazwisko;
        $this->pesel = $pesel;
    }
	/**
	* Sprawdza czy podany klient jest już w bazie
	*/
    public function sprawdzWBazie(){
        if(empty($this->imie) || empty($this->nazwisko) || empty($this->pesel)){
            return 0;
        }else{
            $dbRegister = $this->sendquery("SELECT * FROM pracownicy WHERE Imie='$this->imie' AND Nazwisko='$this->nazwisko' AND PESEL='$this->pesel'");
            return $dbRegister;
        }
       return 0;
    }
	/**
	* Sprawdza czy dane dwa hasła są takie same
	*/
    public function matchPasswords($password1,$password2){
        return $password1 == $password2 ? 1 : 0;
    }
	/**
	* Funkcja odpowiedzialna za stworzenie nowego konta. Należy podać login, hasło i email.
	*/
    public function createAccount($login, $password,$email){
        $cr_account = $this->connect()->prepare("UPDATE pracownicy SET login=?,password=?,firstlogin=?,email=? WHERE PESEL=?");
        $cr_account->bind_param('ssiss',$login,password_hash($password, PASSWORD_DEFAULT),$i = 0,$email,$this->pesel);
        $cr_account->execute();
        $cr_account->close();
    }
	/**
	* Funkcja sprawdzająca czy istnieje podany login użytkownika 
	*/
    public function checkLogin($login){
        $dbRegister = $this->sendquery("SELECT * FROM pracownicy WHERE login='$login'");
        return $dbRegister;
    }
	/**
	* Funkcja sprawdzająca czy istnieje podany pesel i login użytkownika przy pierwszym logowaniu
	*/
    public function checkFirstRegiser(){
        $dbFirstLogin = $this->sendquery("SELECT * FROM pracownicy WHERE PESEL='$this->pesel' AND firstlogin=0");
        return $dbFirstLogin;
    }
	/**
	* Funkcja sprawdzająca czy istnieje podany pesel użytkownika 
	*/
    public function checkPESEL(){
        $dbCheckPesel = $this->sendquery("SELECT COUNT(*) FROM pracownicy WHERE PESEL='$this->pesel'");
       if($dbCheckPesel['COUNT(*)'] == 1){
           return 1;
       }
       else{
           return 0;
       }
    }
	/**
	* Funkcja sprawdzająca czy istnieje podany email użytkownika 
	*/
    public function checkEmail($email){
        $dbEmail= $this->sendquery("SELECT * FROM pracownicy WHERE email='$email'");
        return $dbEmail;
    }
}
?>