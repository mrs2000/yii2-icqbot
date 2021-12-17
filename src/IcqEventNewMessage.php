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
}