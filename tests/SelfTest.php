<?php

namespace tests;

class SelfTest extends TestBase
{
    public function testSelf()
    {
        $reponse = $this->bot->self();

        self::assertArrayHasKey('ok', $reponse);
        self::assertTrue($reponse['ok']);
        self::assertArrayHasKey('firstName', $reponse);
        self::assertArrayHasKey('userId', $reponse);
    }
}