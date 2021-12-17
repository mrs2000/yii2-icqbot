<?php

namespace tests;

class MessagesTest extends TestBase
{
    public function testSendTextMessage()
    {
        $reponse = $this->bot->sendText('@633400657', 'Привет.');

        self::assertArrayHasKey('ok', $reponse);
        self::assertArrayHasKey('msgId', $reponse);
        self::assertTrue($reponse['ok']);
    }

    public function testSendTextMessageWithButtons()
    {
        //attention, primary, base

        $buttons = [
            [
                [
                    'text' => 'Кнопка attention',
                    'url' => 'https://helpdesk.novator-group.ru/',
                    'style' => 'attention'
                ],
                [
                    'text' => 'Кнопка primary',
                    'callbackData' => 'create-ticket',
                    'style' => 'primary'
                ],
            ],
            [
                [
                    'text' => 'Кнопка Base',
                    'url' => 'https://helpdesk.novator-group.ru/',
                    'style' => 'base'
                ]
            ]
        ];

        $text = "Привет, @[633400657]" . PHP_EOL . 'Вторая строка сообщения.';

        $reponse = $this->bot->sendText('@633400657', $text, $buttons);

        self::assertArrayHasKey('ok', $reponse);
        self::assertArrayHasKey('msgId', $reponse);
        self::assertTrue($reponse['ok']);
    }
}