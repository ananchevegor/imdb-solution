<?php

namespace App\Controller;
use App\Entity\TitleAkas;
use App\Entity\TitleCrew;
use App\Entity\TitleBasics;
use App\Entity\NameBasics;
use App\Entity\TitlePrincipals;
use App\Entity\TitleRating;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;


class CardController extends AbstractController
{
    #[Route('/card/{tconst}', name: 'card.php', methods: ['GET'])]
    public function card(string $tconst, EntityManagerInterface $entityManager): Response
    {
        $titleAkas = $entityManager->getRepository(TitleAkas::class)->finById($tconst);
        $titleBasicsRepository = $entityManager->getRepository(TitleBasics::class);
        $titlePrincipalsRepository = $entityManager->getRepository(TitlePrincipals::class);
        $titleRatingRepository = $entityManager->getRepository(TitleRating::class);


        if (!$titleAkas) {
            throw $this->createNotFoundException('TitleAkas not found for id: ' . $tconst);
        }
        $titleCrew = $entityManager->getRepository(TitleCrew::class)->findByTconst($tconst);
        $nconstsDirectors = [];
        foreach ([$titleCrew->getDirectors()] as $crewList) {
            if ($crewList) {
                $nconstsDirectors = array_merge($nconstsDirectors, explode(',', $crewList));
            }
        }
        $nconstsWriters = [];
        foreach ([$titleCrew->getWriters()] as $crewList) {
            if ($crewList) {
                $nconstsWriters = array_merge($nconstsWriters, explode(',', $crewList));
            }
        }
        $nameBasicsRepository = $entityManager->getRepository(NameBasics::class);
        $directorsAndWriters = array_merge($nconstsDirectors, $nconstsWriters);
        if (isset($directorsAndWriters)) {
           
            $nameBasics[] = $nameBasicsRepository->findByNconst(implode(",", $directorsAndWriters));
             
        }
        if (isset($nameBasics) && count($nameBasics) > 0) {
            foreach ($nameBasics as $nB) 
            {
                if ($nB !== NULL) {
                    $knftBasics[] = $titleBasicsRepository->fetchByTconst($nB->getKnownForTitles());
                }
                
            }
        }



        $titlePrincipals = $titlePrincipalsRepository->findByTconst($tconst);




        foreach ($titlePrincipals as $tP) {
            $tPNconst[] = $tP->getNconst();
        }

        if (isset($tPNconst)) {
            $tPNconstMerged = implode(",", $tPNconst);
            $tPNames = $nameBasicsRepository->findByNconst($tPNconstMerged);

            $titleRating = $titleRatingRepository->fetchRatingByTconst($tconst);

            if ($titleRating !== null) {
                $averageRating = $titleRating->getAverageRating();
                $numVotes = $titleRating->getNumVotes();
            }
        }

       

        

        return $this->render('title_card/index.html.twig', [
            'tconst' => $tconst,
            'titleAkas' => $titleAkas,
            'titleBasic' => $titleBasicsRepository->fetchByTconst($tconst)[0] ?? null,
            'titleCrew' => $titleCrew,
            'titlePrincipals' => $titlePrincipals,
            'nameBasics' => $nameBasics ?? [],
            'knftBasics' => $knftBasics ?? [],
            'tPNames' => $tPNames ?? null,
            'averageRating' => $averageRating ?? null,
            'numVotes' => $numVotes ?? null,
        ]);
    }
}
