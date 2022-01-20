<?php 
namespace App\Models;
use CodeIgniter\Model;

class BugModel extends Model
{
    protected $table = 'bugs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['description', 'resolved', 'user_id'];

}