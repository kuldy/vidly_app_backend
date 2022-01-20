<?php 
namespace App\Models;
use CodeIgniter\Model;

class ListingModel extends Model
{
    protected $table = 'listings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'price', 'category', 'description', 'longitude', 'latitude'];

    public function getJoinOfImagesAndListings(){
        $builder = $this->db->table("listings");
        $builder->select('*');
        $builder->join('images', 'listings.id = images.listings_id');
        $links = $builder->get()->getResultArray();
        return $links;
    }

    
}