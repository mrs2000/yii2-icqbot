<?php

namespace tests;

use mrssoft\icqbotapi\IcqEventCallbackQuery;

class EventsTest extends TestBase
{
    public function testPoll()
    {
        $events = $this->bot->pollEvents();

        foreach ($events as $event) {
            if ($event instanceof IcqEventCallbackQuery) {
                $event->answer([
                    //'text' => '$event->callbackData: ' . $event->callbackData,
                    //'showAlert' => true,
                    //'url' => 'https://ya.ru/',
                ]);
            }
        }

        self::assertNotEmpty($events);
    }
}