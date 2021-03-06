<?php

namespace view;

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private $message='';

	private $model;

	
    public function __construct(\model\LoginModel $model) {
				
		$this->model = $model;
	}
    
    /*
     * Function that makes validation of user input and returns string messages.
     * Maybe was possible to use throw exceptions here.
     *
     */
    public function validation(){

			if($this->getRequestUserName() === '' && !$this->model->isLoggedIn())
			{
				return  'Username is missing';

			}
			else if($this->getRequestPassword() === '' && !$this->model->isLoggedIn())
			{
                return  'Password is missing';

			}
			else if(!$this->model->isLoggedIn() && ($this->getRequestUserName() != $this->model->getUsername() || $this->getRequestPassword() != $this->model->getPassword()))
			{
				return  'Wrong name or password';
			}
			else if ( $this->model->isLoggedIn())
			{
			    return  'Welcome';
			}
    }

    public function setLogoutMessage()
	{
		$this->setMessage('Bye bye!');
	}
	
    public function setSuccessMessage()
	{
       $this->setMessage("Registered new user.");
	}

    /*
     * Resets message!
     */
    public function resetMessage()
    {
       $message = '';
    }

    /*
     * setter for message
     */
    public function setMessage($message)
    {
          $this->message = $message;
    }

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {

		if(isset($_SESSION['success'])) {
			$this->setSuccessMessage();
			$this->setRequestUsername($_SESSION['success']);
			unset($_SESSION['success']);
		}
           
		if($this->model->isLoggedIn()) 
		{
			$response = $this->generateLogoutButtonHTML($this->message);
		} 
		else 
		{
			$response = $this->generateLoginFormHTML($this->message);
		}

		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/

	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getRequestUserName() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	//CREATE SET-FUNCTION 
	public function setRequestUserName($username)
	{
		$_POST[self::$name] = $username;
	}

	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getRequestUserName() {
		//RETURN REQUEST VARIABLE: USERNAME

		if(isset($_POST[self::$name])) {

            return $_POST[self::$name];

        } 
        else {

            return '';

        }
	}
	public function getRequestPassword() {
		//RETURN REQUEST VARIABLE: PASSWORD

		if(isset($_POST[self::$password])) {

            return $_POST[self::$password];

        } 
        else {

            return '';
            
        }
	}
    
    // If login button is pressed
	public function isLoginPosted() {
		return isset($_POST[self::$login]);
	}
	
	// If logout button is pressed
	public function isLogoutPosted() {
		return isset($_POST[self::$logout]);
	}
	
}