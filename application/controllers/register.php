<?PHP
require "../application.php";
Sessions::startSession();
if(isset($_POST['submit'])){
    $login = $_POST['login'];
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $pesel = $_POST['pesel'];
    $password1 = $_POST['pwd1'];
    $password2 = $_POST['pwd2'];
    $email = $_POST['email'];
	
	/** Sprawdzanie czy wszystkie pola są wypełnione. */
    if(empty($login) || empty($imie) || empty($nazwisko) || empty($pesel) || empty($password1) || empty($password2) || empty($email)){
        $_SESSION['noRegisterData'] = true;
        header("Location: ../../views/login.php");
        die();
    }else{
        $register = new Register($imie,$nazwisko,$pesel);
        //if($register->sprawdzWBazie()){
            //sprawdz PESEL
			/** Sprawdzanie czy pesel jest prawidłowy. */
            if(!$register->checkPESEL()){
                $_SESSION['isPESELOccupied'] = true;
                header("Location: ../../views/login.php");
                die();
            }else{
				/** Sprawdzanie czy użytkownik jest już zarejestrowany. */
                if($register->checkFirstRegiser()){
                    $_SESSION['isAlreadyRegistered'] = true;
                    header("Location: ../../views/login.php");
                    die();
                }else{
					/** Sprawdzanie czy login nie jest zajęty. */
                    if($register->checkLogin($login)){
                        $_SESSION['isLoginOccupied'] = true;
                        header("Location: ../../views/login.php");
                        die();
                    }else{
						/** Sprawdzanie czy adres email nie jest zajęty. */
                        if($register->checkEmail($email)){
                            $_SESSION['emailIsOccupied'] = true;
                            header("Location: ../../views/login.php");
                            die();
                        }else{
                            if($register->sprawdzWBazie()){
								/** Sprawdzanie czy obydwa hasła są takie same. */
                                if($register->matchPasswords($password1,$password2)){
									/** Tworzenie konta. */
                                    $register->createAccount($login,$password1,$email);
                                    $_SESSION['registerSuccess'] = true;
                                    header("Location: ../../views/login.php");
                                }else{
                                    $_SESSION['isPasswordsCorrect'] = true;
                                    header("Location: ../../views/login.php");
                                    die();
                                }
                            }else{
                                $_SESSION['badInputData'] = true;                        
                                header("Location: ../../views/login.php");
                                die();
                            }
                        }
                    }
                }
            }
    }
}else{
   header("Location: ../../views/login.php");
}
?>