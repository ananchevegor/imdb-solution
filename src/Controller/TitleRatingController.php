<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\TitleRating;
use Doctrine\ORM\EntityManagerInterface;


class TitleRatingController extends AbstractController
{
    #[Route('/titleRating/count', name: 'title_rating_count.php', methods: ['GET'])]
    public function titleRatingCount(EntityManagerInterface $entityManager): Response
    {
        $titleRating = $entityManager->getRepository(TitleRating::class)->count([]);
        return new Response(json_encode($titleRating));
    }

    #[Route('/titleRating', name: 'title_rating.php', methods: ['GET'])]
    public function titleRating(EntityManagerInterface $entityManager): Response
    {
        $titleRating = $entityManager->getRepository(TitleRating::class)->fetchRatings();
        return new Response(json_encode($titleRating));
    }

    #[Route('/ratings', name: 'title_rating_page', methods: ['GET'])]
    public function ratingsPage(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(TitleRating::class);

        return $this->render('title_rating/index.html.twig', [
            'total' => $repository->count([]),
            'ratings' => $repository->fetchRatings(),
        ]);
    }
}
