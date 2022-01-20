<?php 
namespace App\Models;
use CodeIgniter\Model;

class GenreModel extends Model
{
    protected $table = 'genres';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];
}