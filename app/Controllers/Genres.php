<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\GenreModel;
use \Firebase\JWT\JWT;
use Exception;

class Genres extends ResourceController
{
    use ResponseTrait;

    // all users
    public function genres(){

      // $description = "page not found";
      // return $this->fail($description, 404);

      // $description = "this field is required";
      // return $this->fail($description, 400);

      $model = new GenreModel();
      $data = $model->findAll();
      return $this->respond($data);
      // $key = "kullu";
      // $authHeader = $this->request->getHeader("Authorization");

      // if($authHeader == null){
      //   $response = [
      //     'status' => 401,
      //     'message'=> 'Authorization token is required'
      //   ];
      //   return $this->respond($response);
      // }

      // $authHeader = $authHeader->getValue();
      // $token = $authHeader;

      // try {
      //     $decoded = JWT::decode($token, $key, array("HS256"));

      //     if ($decoded) {

      //       $model = new GenreModel();
      //       // $data['listings'] = $model->orderBy('id', 'DESC')->findAll();
      //       // $data['listings'] = $model->getJoinOfImagesAndListings();
      //       $data['genres'] = $model->findAll();
      //       // echo print_r($data);
      //       return $this->respond($data);
      //     }
      // } catch (Exception $ex) {        
      //   $response = [
      //       'status' => 401,
      //       'messages' => 'Access denied',
      //   ];
      //   return $this->respondCreated($response);
      // }

    }

    public function getGenre($id){
      $model = new GenreModel();
        $data['genre'] = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No employee found');
        }
    }
      

    //add listings to listings
    public function addGenre(){

      $model = new GenreModel();
      $values =$this->request->getVar();
      

      $data_to_save = [
        'name'=>$values->name,
      ];

      // // echo print_r($data_to_save);
      $genre_id = $model->insert($data_to_save);

      if(!isset($genre_id)){
        $response = [
        'error' => 'Cannot create genre'
        ];
        return $this->respond($response);
      }
      $response = [
        'success' =>'Genre created successfully',
        'data'=>$genre_id,
      ];

      return $this->respond($genre_id);
    }

}