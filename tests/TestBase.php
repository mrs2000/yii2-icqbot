<?php

namespace tests;

use mrssoft\icqbotapi\IcqBot;
use yii\mutex\FileMutex;

class TestBase extends \PHPUnit\Framework\TestCase
{
    protected array $params = [];

    /**
     * @var \mrssoft\icqbotapi\IcqBot
     */
    protected IcqBot $bot;

    private function loadParams(string $filename): array
    {
        return json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . $filename), true);
    }

    protected function setUp(): void
    {
        $this->params = $this->loadParams('params.json');

        $this->bot = new IcqBot([
            'token' => $this->params['token'],
            'mutex' => FileMutex::class
        ]);
    }
}