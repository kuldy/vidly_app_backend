<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use \Firebase\JWT\JWT;

class User extends ResourceController{
		

	public function register(){
		$rules = [
			"name" => "required",
			// "email" => "required|valid_email|is_unique[users.email]|min_length[6]",
			"email" => "required|valid_email|min_length[6]",

			"phone_no" => "required",
			"password" => "required",
		];

		$messages = [
			"name" => [
					"required" => "Name is required"
			],
			"email" => [
					"required" => "Email required",
					"valid_email" => "Email address is not in format"
			],
			"phone_no" => [
					"required" => "Phone Number is required"
			],
			"password" => [
					"required" => "password is required"
			],
		];

		if (!$this->validate($rules, $messages)) return $this->fail("validation error", 400);
		$userModel = new UserModel();
		$userdata = $userModel->where("email", $this->request->getVar("email"))->first();
		if(! empty($userdata)) return $this->fail("user already registered", 400);

		$name = $this->request->getVar("name");
		$email = $this->request->getVar("email");

		$data = [
			"name" => $name,
			"email" => $email,
			"phone_no" => $this->request->getVar("phone_no"),
			"password" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
		];

		$user_id = $userModel->insert($data);
		if (! isset($user_id)) return $this->fail("failed to create user", 400);

		$key = $this->getKey();
		$uer_info = [
			"name" => $name,
			"email" => $email,
			"id" => $user_id,
			"is_admin" => false,
		];
		
		$token = $this->getToken($key, $uer_info);
		
		// $this->setUserSession(false);

		$response = [ "id" => $user_id, "name" => $name, "email" => $email, "token" => $token];

		return $this->respondCreated($response);
	}


	public function login(){
		$rules = [
			"email" => "required|valid_email|min_length[6]",
			"password" => "required",
		];

		$messages = [
			"email" => [
					"required" => "Email required",
					"valid_email" => "Email address is not in format"
			],
			"password" => [
					"required" => "password is required"
			],
		];

		if(!$this->validate($rules, $messages)) return $this->failValidationError("validation errors");
		
		$userModel = new UserModel();
		$userdata = $userModel->where("email", $this->request->getVar("email"))->first();

		if(empty($userdata)) return $this->failUnauthorized("invalid email");

		if(!password_verify($this->request->getVar("password"), $userdata['password']))
			return $this->failUnauthorized("password is incorrect!"); //401

		// if($userdata["admin"]) $this->setUserSession($userdata["admin"], $userdata["id"]);

		$data = [
			'id' => $userdata['id'],
			'name' => $userdata['name'],
			'email' => $userdata['email'],
			'is_admin' => $userdata['admin'],
		];


		$key = $this->getKey();
		$token = $this->getToken($key, $data);
		// $this->setTokenInSession($token);

		$response = ['token' => $token];
		return $this->respondCreated($response); 
	}

	public function details(){
		$key = $this->getKey();
		$authHeader = $this->request->getHeader("Authorization");
		$authHeader = $authHeader->getValue();
		$token = $authHeader;

		// if( ! $this->matchToken($token))
		// 	return $this->respondCreated(array('error' => 'Token Mismatch cant access the page'));

		try {
			$decoded = JWT::decode($token, $key, array("HS256"));
			if ($decoded) 
				return $this->respondCreated(array('data' => $decoded));

		}
		catch (Exception $ex) {
			return $this->respondCreated(array('messages' => 'Access denied','token'=> $token,));
		}
	}
		

	public function logout(){

		//todo need to change iat, nbf and exp in another way
		// echo "inside logout";

		$key = $this->getKey();
		$authHeader = $this->request->getHeader("Authorization");
		$authHeader = $authHeader->getValue();
		$token = $authHeader;

		// if( ! $this->matchToken($token)){
		// 	$response = [
		// 					'status' => 200,
		// 					'messages' => 'Token Mismatch cant access the page',
		// 					'data' => [
		// 					]
		// 			];
		// 	return $this->respondCreated($response);
		// }

		try {
			$decoded = JWT::decode($token, $key, array("HS256"));

				$iat = time(); // current timestamp value
				$nbf = $iat + 2;
				$exp = $iat + 5;
				$data = [];

				$payload = array(
						"iss" => "The_claim",
						"aud" => "The_Aud",
						"iat" => $iat, // issued at
						"nbf" => $nbf, //not before in seconds
						"exp" => $exp, // expire time in seconds
						"data" => $data
				);

				$token = JWT::encode($payload, $key);

				// $this->setTokenInSession($token);

				$response = [
						'status' => 200,
						'messages' => 'logout done',
						'data' => []
				];
				return $this->respondCreated($response);

		} catch (Exception $ex) {
			
			$response = [
					'status' => 401,
					'messages' => 'Access denied',
					'data' => []
			];
			return $this->respondCreated($response);
		}

	}

	private function getToken($key, $user_info){

		$iat = time(); // current timestamp value
		$nbf = $iat + 10;
		$exp = $iat + 3600;
		$payload = array(
			"iat" => $iat, // issued at
			"data" => $user_info,
			// "iss" => "The_claim",
			// "aud" => "The_Aud",
			// "nbf" => $nbf, //not before in seconds
			// "exp" => $exp, // expire time in seconds		
		);
		$token = JWT::encode($payload, $key);

		return $token;
	}

	private function getKey(){
		return "kullu";
	}

	private function setTokenInSession( $token = null ){

			if(session()->get('token')){
					session()->remove('token');
			}
				 $data = [
				'token' => $token,
			];
			// $session = \Config\Services::session($config);
			session()->set($data);
			return true;
	}
	private function matchToken( $token ){   
		$oldtoken = session()->get('token');
		// if( $oldtoken == $token)
		// return true;
		return $oldtoken == $token ? true : false;
	}
	private function setUserSession($isAdmin, $id){

		// if(session()->get('isAdmin')) session()->remove('isAdmin');
		// if(session()->get('user_id')) session()->remove('user_id');

		$data = [
			"isAdmin" => $isAdmin,
			"user_id" => $id,
		];
		session()->set($data);
	}

}