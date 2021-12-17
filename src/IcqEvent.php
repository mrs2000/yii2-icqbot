<?php

namespace mrssoft\icqbotapi;

abstract class IcqEvent
{
    public const NEW_MESSAGE = "newMessage";
    public const CALLBACK_QUERY = "callbackQuery";

    public int $eventId;
    public string $type;

    public array $format;

    public array $parts;

    public array $from;
    public string $userId;

    protected IcqBot $bot;

    public function __construct(IcqBot $bot, array $event)
    {
        $this->bot = $bot;

        $this->eventId = $event['eventId'];
        $this->type = $event['type'];
        $this->from = $event['payload']['from'] ?? '';
        $this->parts = $event['payload']['parts'] ?? [];

        $this->userId = $event['payload']['from']['userId'];
    }
}