<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\BugModel;
use \Firebase\JWT\JWT;
use Exception;

class Bugs extends ResourceController
{
    use ResponseTrait;

    public function index(){
      $key = "kullu";
      // $authHeader = $this->request->getHeader("Authorization");
      // $token = $authHeader->getValue();
      // if($token == null) return $this->fail("No token!", 401);

      // try {
      //       JWT::decode($token, $key, array("HS256"));
      // } catch (Exception $ex) {
      //   return $this->fail("invalid token!", 401);
      // }

      $model = new BugModel(); 
      $data = $model->findAll();
      return $this->respond($data);
    }
      
    public function create(){

      // $key = "kullu";
      // $authHeader = $this->request->getHeader("Authorization");
      // $token = $authHeader->getValue();
      // if($token == null) return $this->fail("No token!", 401);

      // try {
      //       JWT::decode($token, $key, array("HS256"));
      // } catch (Exception $ex) {
      //   return $this->fail("invalid token!", 401);
      // }


      $model = new BugModel();
      $values =$this->request->getVar();
      $data_to_save = [
        'description'=>$values->description,
        'resolved'=>$values->resolved,
        'user_id'=>$values->user_id,
        
      ];
      // // echo print_r($data_to_save);
      $bug_id = $model->insert($data_to_save);
      if(!isset($bug_id)){
        $response = [
        'error' => 'Cannot create movies'
        ];
        return $this->respond($response);
      }

      $data = [
        'id' => $bug_id,
        'description'=>$values->description,
        'resolved'=>$values->resolved,
        'user_id'=>$values->user_id,
      ];
      return $this->respondCreated($data);
    }

    public function show($id = null){

      // $key = "kullu";
      // $authHeader = $this->request->getHeader("Authorization");
      // $token = $authHeader->getValue();
      // if($token == null) return $this->fail("No token!", 401);

      // try {
      //       JWT::decode($token, $key, array("HS256"));
      // } catch (Exception $ex) {
      //   return $this->fail("invalid token!", 401);
      // }

      $model = new BugModel();
      $data['bug'] = $model->find($id);
      if(!$data['bug']) return $this->fail("resource does not exist!", 404);
      return $this->respond($data['bug'], 200);
    }

    public function update($id = null){
      // $key = "kullu";
      // $authHeader = $this->request->getHeader("Authorization");
      // $token = $authHeader->getValue();
      // if($token == null) return $this->fail("No token!", 401);

      // try {
      //       JWT::decode($token, $key, array("HS256"));
      // } catch (Exception $ex) {
      //   return $this->fail("invalid token!", 401);
      // }

      $model = new BugModel();
      $values =$this->request->getVar();

      $data_to_save = [
        'description'=>$values->description,
        'resolved'=>$values->resolved,
        'user_id'=>$values->user_id,
      ];

      $bug = $model->find($id);

      $result = $model->update($id, $data_to_save);

      if(!$result) return $this-> fail("validation error!", 400);

      return $this->respond($data_to_save, 201);
    }

    public function delete($id = null){
      $model = new BugModel();
      $bug = $model->find($id);

      if(!$bug) return $this->fail("resource does not exist!", 404);
    
      $model->delete($id);
      $data = ['id' => $id,'message' => "resource deleted successfully!"];
      return $this->respond($data, 200);
    }

    public function assignBug($id = null){
      $model = new BugModel();
      $values =$this->request->getVar();

      $user_id = $values->user_id;
      
      $data_to_save = [
        'user_id'=>$user_id,
      ];
      // echo($id);
      $result = $model->update($id, $data_to_save);

      if(!$result) return $this-> fail("validation error!", 400);

      $data = [
        'message' => 'bug assigned successfully!',
        'id' => $id,
        'user_id' => $user_id,
      ];
      return $this->respond($data, 201);

    }

    public function resolveBug($id = null){
      $model = new BugModel();
      $values =$this->request->getVar();

      $data_to_save = [
        'resolved'=>true,
      ];
      // echo($id);
      $result = $model->update($id, $data_to_save);

      if(!$result) return $this-> fail("validation error!", 400);

      $data = [
        'message' => 'bug resolved successfully!',
        'id' => $id
      ];
      return $this->respond($data, 201);
    }

}