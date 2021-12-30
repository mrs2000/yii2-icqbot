<?php

namespace mrssoft\icqbotapi;

class IcqEventNewMessage extends IcqEvent
{
    public string $text;

    public function __construct(IcqBot $bot, array $event)
    {
        parent::__construct($bot, $event);
        $this->text = $event['payload']['text'] ?? '';
    }

    public function reply(array $params): ?array
    {
        if (isset($params['text'])) {
            return $this->bot->sendText($this->userId, $params['text'], $params['buttons'] ?? []);
        }
        return null;
    }
}