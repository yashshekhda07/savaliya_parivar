<?php

namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\Login_model as loginmodel;
use App\Models\Home_model as homemodel;

class Login extends BaseController
{
	protected $session;

	public function __construct()
	{
		helper(['form', 'url']);
		$this->session = session();
	}

	public function index(){
		$isLoggedIn = $this->session->get( 'isLoggedIn' );
		if($isLoggedIn == "" || $isLoggedIn == NULL || $isLoggedIn != TRUE){
			return view("login");
		}else{
			return redirect()->to(base_url().'/');
		}
	}

	public function authenticate(){
		$this->loginmodel = new loginmodel();
		$input = $this->validate([
			'password' => 'required',
			'email' => 'required|min_length[2]',
		]);

		if (!$input) {
			return view('login', [
				'validation' => $this->validator
			]);
		} else {
			$email = $this->request->getVar('email');
			$password = $this->request->getVar('password');
			$auth_user = $this->loginmodel->authenticate($email, $password);
			if($auth_user != NULL){
				$sessionArray = array(
					'userId'        => $auth_user->email,
					'name'        => $auth_user->fullname,
					'role'        => $auth_user->role,
					'branch'        => $auth_user->branch,
					'isLoggedIn'    => TRUE
				);

				$this->session->set($sessionArray);
				return redirect()->to(base_url().'/');
			}else{
				$this->session->setFlashdata('message', 'Email or password mismatch');
				return redirect()->to(base_url().'/login');
			}
		}
	}

	public function logout(){
		unset(
			$_SESSION['userId'],
			$_SESSION['isLoggedIn']
		);
		return redirect()->to(base_url().'/');
	}
}
