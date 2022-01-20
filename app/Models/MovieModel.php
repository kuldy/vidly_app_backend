<?php 
namespace App\Models;
use CodeIgniter\Model;

class MovieModel extends Model
{
    protected $table = 'movies';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'genre_id', 'number_in_stock', 'daily_rental_rate', 'publish_date', 'is_liked'];


    public function getMoviesWithGenre(){
        $builder = $this->db->table("genres");
        $builder->select('*');
        $builder->join('movies', 'genres.id = movies.genre_id');
        $movies = $builder->get()->getResultArray();
        //$cart_items is an indexed array, its elements are associative arrays see the example at the end of the file.
    return $movies;

    }
}