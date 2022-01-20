<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MovieModel;
use App\Models\GenreModel;
use \Firebase\JWT\JWT;
use Exception;

class Movies extends ResourceController
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

      $model = new MovieModel(); 
      $data['movies'] = $model->getMoviesWithGenre();
      return $this->respond($data);
    }
      
    public function create(){

      $key = "kullu";
      $authHeader = $this->request->getHeader("Authorization");
      $token = $authHeader->getValue();
      if($token == null) return $this->fail("No token!", 401);

      try {
            JWT::decode($token, $key, array("HS256"));
      } catch (Exception $ex) {
        return $this->fail("invalid token!", 401);
      }



      $model = new MovieModel();
      $values =$this->request->getVar();
      $data_to_save = [
        'title'=>$values->title,
        'genre_id'=>$values->genre_id,
        'number_in_stock'=>$values->number_in_stock,
        'daily_rental_rate'=>$values->daily_rental_rate,
        'publish_date'=>"2018-01-03T19:04:28:809Z",
        'is_liked'=> false,
      ];
      // // echo print_r($data_to_save);
      $movie_id = $model->insert($data_to_save);
      if(!isset($movie_id)){
        $response = [
        'error' => 'Cannot create movies'
        ];
        return $this->respond($response);
      }
      return $this->respondCreated($movie_id);
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

      $model = new MovieModel();
      $data['movie'] = $model->find($id);
      if(!$data['movie']) return $this->fail("resource does not exist!", 404);
      return $this->respond($data['movie'], 200);
    }

    public function update($id = null){
      $key = "kullu";
      $authHeader = $this->request->getHeader("Authorization");
      $token = $authHeader->getValue();
      if($token == null) return $this->fail("No token!", 401);

      try {
            JWT::decode($token, $key, array("HS256"));
      } catch (Exception $ex) {
        return $this->fail("invalid token!", 401);
      }


      $model = new MovieModel();
      $values =$this->request->getVar();
      
      $data_to_save = [
        'title'=>$values->title,
        'genre_id'=>$values->genre_id,
        'number_in_stock'=>$values->number_in_stock,
        'daily_rental_rate'=>$values->daily_rental_rate,
        'publish_date'=>"2018-01-03T19:04:28:809Z",
        'is_liked'=> false,
      ];

      $id = $values->id;
      echo($id);

      $result = $model->update($id, $data_to_save);

      if(!$result) return $this-> fail("validation error!", 400);

      return $this->respond("resource created successfully", 201);
    }

    public function delete($id = null){
      $key = "kullu";
      $authHeader = $this->request->getHeader("Authorization");
      $token = $authHeader->getValue();
      if($token == null) return $this->fail("No token!", 401);

      try {
            $decode = JWT::decode($token, $key, array("HS256"));
            // print_r($decode);
            // echo($decode->data->name);
            // echo($decode->data->is_admin);
            if(! $decode->data->is_admin) return $this->fail("Not allowed to delete resource", 403); 

      } catch (Exception $ex) {
        return $this->fail("invalid token!", 401);
      }
      
      $model = new MovieModel();
      $movie = $model->find($id);

      if(!$movie) return $this->fail("resource does not exist!", 404);
    
      $model->delete($id);
      return $this->respond("resource deleted successfully!", 200);
    }

}