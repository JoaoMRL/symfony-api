<?php 

namespace App\Controller;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/api/movie')]
class MovieController extends AbstractController{
    public function __construct( private MovieRepository $movie){}
    #[Route(methods:'GET')]
    public function index (){
        return $this->json($this->movie->findAll());
    }
    #[Route('/{id}', methods:'GET')]
    public function indexId (int $id){
        if ($this->movie->findById($id) !== null){
            return $this->json($this->movie->findById($id));
        }
        return $this->json('Resource Not Found',404);
        
    }
    #[Route('/{id}', methods:'DELETE')]
    public function indexIdDelete (int $id){
        if ($this->movie->findById($id) !== null){
            return $this->json($this->movie->delete($id));
        }
        return $this->json(null,204);
        
    }
}