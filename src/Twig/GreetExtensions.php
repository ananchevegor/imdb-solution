<?php

namespace App\Twig;

use App\GreetingGenerator;
use Twig\Attribute\AsTwigFilter;

class GreetExtensions
{
    public function __construct(
        private readonly GreetingGenerator $greetingGenerator,
    ) {
    }

    #[AsTwigFilter('greet')]
    public function greetUser(string $name): string
    {
        $greeting = $this->greetingGenerator->generateRandomGreeting($name);

        return "$greeting $name!";
    }
}
