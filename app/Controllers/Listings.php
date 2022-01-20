<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ListingModel;
use App\Models\ImageModel;
use \Firebase\JWT\JWT;
use Exception;



// use App\Models\CrudUserModel;
// use CodeIgniter\Controller;
// use  App\Controllers\BaseController;

class Listings extends ResourceController
{
    use ResponseTrait;

    // all users
    public function listings(){
      $key = "kullu";
      $authHeader = $this->request->getHeader("Authorization");

      if($authHeader == null){
        $response = [
          'status' => 401,
          'message'=> 'Authorization token is required'
        ];
        return $this->respond($response);
      }

      $authHeader = $authHeader->getValue();
      $token = $authHeader;

      try {
          $decoded = JWT::decode($token, $key, array("HS256"));

          if ($decoded) {

            $model = new ListingModel();
            // $data['listings'] = $model->orderBy('id', 'DESC')->findAll();
            $data = $model->getJoinOfImagesAndListings();
            // echo print_r($data);
            return $this->respond($data);
          }
      } catch (Exception $ex) {        
        $response = [
            'status' => 401,
            'messages' => 'Access denied',
        ];
        return $this->respondCreated($response);
      }

    }
      

    //add listings to listings
    public function addListing(){

      $model = new ListingModel();
      $image = new ImageModel();
      $values =$this->request->getVar();
      // echo print_r($values);

      $data_to_save = [
        'title'=>$values->title,
        'price'=>$values->price,
        'category'=>$values->category->value,
        'description'=>$values->description,
        'longitude'=>$values->location->longitude,
        'latitude'=>$values->location->latitude,
      ];
      // // echo print_r($data_to_save);
      $listings_id = $model->insert($data_to_save);

      if(!isset($listings_id)){
        $response = [
        'success' => false,
        'messages' => 'Cannot create listings'
        ];
        return $this->respond($response);
      }

      $image_to_save=$values->images;

      if(count($image_to_save) == 0){
        $response = [
        'success' => false,
        'messages' => 'Cannot get the images from data'
        ];
        return $this->respond($response);
      }

      foreach($image_to_save as $value){
        $image_data=[];
        $image_data['imageUrl']=$value;
        $image_data['listings_id']=$listings_id;
        $image_data['thumbnailUrl']=$value;
        // echo print_r($image_data);
        $image_id = $image->insert($image_data);
      }
      
      $response = [
        'success' => true,
        'message' =>'Employee created successfully',
        'data'=>$listings_id,
      ];

      return $this->respond($values);
    }

}