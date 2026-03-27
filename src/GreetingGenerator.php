<?php

namespace App;

use Psr\Log\LoggerInterface;

class GreetingGenerator
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function generateRandomGreeting(string $name): string
    {
        $greetings = [
            'Hello',
            'Hi',
            'Welcome',
            'Nice to see you',
        ];

        $greeting = $greetings[array_rand($greetings)];
        $this->logger->info('Greeting generated.', [
            'name' => $name,
            'greeting' => $greeting,
        ]);

        return $greeting;
    }
}
