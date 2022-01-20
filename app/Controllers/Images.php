<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ImageModel;


class Images extends ResourceController
{
    use ResponseTrait;

    // all users
    public function index(){
      $model = new ImageModel();
      $data['images'] = $model->orderBy('id', 'DESC')->findAll();
      return $this->respond($data);
    }

    //add listings or products

}
