<?php 

namespace App\Controller;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController{
    public function __construct( private MovieRepository $movie){}
    #[Route('/api/movie', methods:'GET')]
    public function index (){
        return $this->json($this->movie->findAll());
    }
    #[Route('/api/movie/{id}', methods:'GET')]
    public function indexId (int $id){
        if ($this->movie->findById($id) !== null){
            return $this->json($this->movie->findById($id));
        }
        return $this->json('Resource Not Found',404);
        
    }
    #[Route('/api/movie/{id}', methods:'DELETE')]
    public function indexIdDelete (int $id){
        if ($this->movie->findById($id) !== null){
            return $this->json($this->movie->delete($id));
        }
        return $this->json('Resource Not Found',404);
        
    }
}