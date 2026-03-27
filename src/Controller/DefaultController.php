<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use App\GreetingGenerator;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'index.php')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }

    #[Route('/users', name: 'users.php', methods: ['GET', 'POST'])]
    public function users(LoggerInterface $logger): Response
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $logger->info("Handling request for users endpoint with method: $requestMethod");
        return new Response($requestMethod);
    }

    #[Route('/greet/{name}', name: 'greet.php')]
    public function greet(string $name, LoggerInterface $logger): Response
    {
        $greetingGenerator = new GreetingGenerator($logger);
        $greeting = $greetingGenerator->generateRandomGreeting($name);

        return new Response($greeting);
    }

    #[Route('/diceRoll', name: 'twig_greet.php', methods: ['GET'])]
    public function diceRoll(): Response
    {
        $playerOner = rand(1, 100);
        $playerTwo = rand(1, 100);
        $won = 'Player One';
        if ($playerTwo > $playerOner) {
            $won = 'Player Two';
        } elseif ($playerTwo === $playerOner) {
            $won = 'Draw';
        }
        return new Response("Player One: $playerOner, Player Two: $playerTwo, Winner: $won");
    }


}
