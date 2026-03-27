<?php
namespace App\Controller;

use App\Entity\TitleBasics;
use App\Repository\TitleBasicsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Psr\Log\LoggerInterface;

class TitleBasicsController extends AbstractController
{
    #[Route('/all', name: 'title_basics_all.php', methods: ['GET', 'POST'])]
    public function titleBasics(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var TitleBasicsRepository $repository */
        $repository = $entityManager->getRepository(TitleBasics::class);
        $limit = 100;
        $totalItems = $repository->countAll();
        $totalPages = max(1, (int) ceil($totalItems / $limit));

        $requestedPage = $request->isMethod('POST')
            ? $request->request->getInt('page', 1)
            : $request->query->getInt('page', 1);
        $requestedPage = min(max(1, $requestedPage), $totalPages);

        $currentPage = $request->isMethod('POST')
            ? $request->request->getInt('currentPage', 1)
            : 1;
        $currentPage = min(max(1, $currentPage), $totalPages);

        $firstCursor = $request->isMethod('POST')
            ? (string) $request->request->get('firstCursor', '')
            : '';
        $lastCursor = $request->isMethod('POST')
            ? (string) $request->request->get('lastCursor', '')
            : '';

        if ($requestedPage === 1) {
            $titleBasics = $repository->fetchFirstPage($limit);
            $currentPage = 1;
        } elseif ($requestedPage === $totalPages) {
            $titleBasics = $repository->fetchLastPage($limit);
            $currentPage = $totalPages;
        } else {
            $hasPageContext = $request->isMethod('POST')
                && $firstCursor !== ''
                && $lastCursor !== ''
                && $currentPage !== $requestedPage;

            $titleBasics = $hasPageContext
                ? $this->loadPageFromCurrentContext(
                    $repository,
                    $requestedPage,
                    $currentPage,
                    $firstCursor,
                    $lastCursor,
                    $limit
                )
                : $this->loadPageFromStart($repository, $requestedPage, $limit);

            $currentPage = $requestedPage;
        }

        if ($titleBasics === []) {
            $titleBasics = $repository->fetchFirstPage($limit);
            $currentPage = 1;
        }

        $firstCursor = $this->getFirstCursor($titleBasics);
        $lastCursor = $this->getLastCursor($titleBasics);


        $response = $this->render('title_basics/index.html.twig', [
            'titleBasics' => $titleBasics,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'limit' => $limit,
            'pageStart' => max(1, $currentPage - 2),
            'pageEnd' => min($totalPages, $currentPage + 2),
            'firstCursor' => $firstCursor,
            'lastCursor' => $lastCursor,
            'search' => '',
        ]);
        
        return $response;
    }

    private function loadPageFromCurrentContext(
        TitleBasicsRepository $repository,
        int $requestedPage,
        int $currentPage,
        string $firstCursor,
        string $lastCursor,
        int $limit,
    ): array {
        $titleBasics = [];

        if ($requestedPage > $currentPage) {
            $cursor = $lastCursor;

            for ($page = $currentPage + 1; $page <= $requestedPage && $cursor !== ''; $page++) {
                $titleBasics = $repository->findByCursor($cursor, $limit, 'next');
                $cursor = $this->getLastCursor($titleBasics) ?? '';
            }

            return $titleBasics;
        }

        $cursor = $firstCursor;

        for ($page = $currentPage - 1; $page >= $requestedPage && $cursor !== ''; $page--) {
            $titleBasics = $repository->findByCursor($cursor, $limit, 'prev');
            $cursor = $this->getFirstCursor($titleBasics) ?? '';
        }

        return $titleBasics;
    }

    private function loadPageFromStart(
        TitleBasicsRepository $repository,
        int $requestedPage,
        int $limit,
    ): array {
        $titleBasics = $repository->fetchFirstPage($limit);

        for ($page = 2; $page <= $requestedPage && $titleBasics !== []; $page++) {
            $cursor = $this->getLastCursor($titleBasics);

            if ($cursor === null || $cursor === '') {
                break;
            }

            $titleBasics = $repository->findByCursor($cursor, $limit, 'next');
        }

        return $titleBasics;
    }

    private function getFirstCursor(array $titleBasics): ?string
    {
        if ($titleBasics === []) {
            return null;
        }

        return $titleBasics[0]->getTconst();
    }

    private function getLastCursor(array $titleBasics): ?string
    {
        if ($titleBasics === []) {
            return null;
        }

        return $titleBasics[array_key_last($titleBasics)]->getTconst();
    }

    #[Route('/deleteCache', name: 'title_basics_delete_cache.php', methods: ['GET'])]
    public function deleteCache(EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $repository = $entityManager->getRepository(TitleBasics::class);
        $repository->clearCache();
        $logger->info('Cache cleared for TitleBasics');
        return new Response('Cache cleared for TitleBasics');
    }


    #[Route('/search', name: 'title_basics_search.php', methods: ['GET'])]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $search = trim((string) $request->query->get('search', ''));

        if ($search === '') {
            return $this->redirectToRoute('title_basics_all.php');
        }

        $currentPage = $request->query->getInt('page', 1);

        /** @var TitleBasicsRepository $repository */
        $repository = $entityManager->getRepository(TitleBasics::class);
        $results = $repository->fetchByName($search);

        $limit = 100;
        $totalItems = $repository->fetchCountByName($search);
        $totalPages = max(1, (int) ceil($totalItems / $limit));


        $response = $this->render('title_basics/index.html.twig', [
            'titleBasics' => $results,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'limit' => $limit,
            'pageStart' => max(1, $currentPage - 2),
            'pageEnd' => min($totalPages, $currentPage + 2),
            'firstCursor' => $this->getFirstCursor($results) ?? '',
            'lastCursor' => $this->getLastCursor($results) ?? '',
            'search' => $search,
        ]);

        return $response;
    }
}
