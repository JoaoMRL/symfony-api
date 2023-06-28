<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/genre')]
class GenreController extends AbstractController
{

    public function __construct(private GenreRepository $repo) {}


    #[Route(methods: 'GET')]
    public function all(): JsonResponse
    {
        return $this->json($this->repo->findAll());
    }

    #[Route('/{id}',methods: 'GET')]
    public function one(int $id): JsonResponse
    {
        $genre = $this->repo->findById($id);
        if($genre == null) {
            return $this->json('Resource Not found', 404);
        }

        return $this->json($genre);
    }
    
    #[Route('/movie/{id}', methods:'GET')]
    public function movieGenres(int $id):JsonResponse {
        $genres = $this->repo->findByMovie($id);
        return $this->json($genres);
    }

    #[Route('/{id}',methods: 'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $genre = $this->repo->findById($id);
        if($genre == null) {
            return $this->json('Resource Not found', 404);
        }
        $this->repo->delete($id);

        return $this->json(null, 204);
    }

    #[Route(methods: 'POST')]
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator) {
        // $data = $request->toArray();
        // $genre = new Genre($data['title'], $data['resume'], new \DateTime($data['released']), $data['duration']);

        try {

            $genre = $serializer->deserialize($request->getContent(), Genre::class, 'json');
        } catch (\Exception $error) {
            return $this->json('Invalid body', 400);
        }
        $errors = $validator->validate($genre);
        if ($errors->count() > 0) {
            return $this->json(['errors' => $errors], 400);
        }
        $this->repo->persist($genre);

        return $this->json($genre, 201);
    }

    #[Route('/{id}', methods: 'PATCH')]
    public function update(int $id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator) {

        $genre = $this->repo->findById($id);
        if($genre == null) {
            return $this->json('Resource Not found', 404);
        }
        try {
            $serializer->deserialize($request->getContent(), Genre::class, 'json', [
                'object_to_populate' => $genre
            ]);
        } catch (\Exception $error) {
            return $this->json('Invalid body', 400);
        }
        $errors = $validator->validate($genre);
        if ($errors->count() > 0) {
            return $this->json(['errors' => $errors], 400);
        }
        $this->repo->update($genre);

        return $this->json($genre);
    }
}
