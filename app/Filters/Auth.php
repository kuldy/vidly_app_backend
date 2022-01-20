<?php namespace App\Filters;
 
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Exception;

 
Class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {  
      // $response = service('response');

      // $key = "kullu";
      // $authHeader = $request->getHeader("Authorization");
      // $token = $authHeader->getValue();
      // if($token == null) {
      //   $data = [
      //     "message" => "Auth token required!",
      //   ];
      //   $response->setStatusCode(401);
      //   $response->setJSON($data);
      //   // if($token == null) return $this->fail("Auth token required!", 401);
      //   return $response; 
      // }

      // try {
      //       JWT::decode($token, $key, array("HS256"));
      // } catch (Exception $ex) {        
      //   $data = [
      //     "message" => "Invalid Token access denied!",
      //   ];
      //   $response->setStatusCode(401);
      //   $response->setJSON($data);
      //   // return $this->fail("Invalid Token access denied!", 401);
      //   return $response;
      // }

    }
 
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
      // Do something here
    }
}