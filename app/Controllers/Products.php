<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProductModel;


class Products extends ResourceController
{
    use ResponseTrait;

    // all users
    public function index(){
      $model = new ProductModel();
      $data['products'] = $model->orderBy('id', 'DESC')->findAll();
      return $this->respond($data);
    }

    //add listings or products
    public function addProduct(){

    // echo var_dump($this->request);
      echo var_dump($this->request->getVar('abc'));

    // $request = \Config\Services::request();
    // $crudUserModel = new CrudUserModel();
    // $data = [
    //   'name' => $this->request->getVar('name'),
    //   'email'  => $this->request->getVar('email'),
    // ];
    // $crudUserModel->insert($data);
    // return $this->response->redirect(site_url('/users-list'));
    }
}