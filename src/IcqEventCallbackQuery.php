<?php

namespace mrssoft\icqbotapi;

class IcqEventCallbackQuery extends IcqEvent
{
    public string|null $callbackData = null;
    public string $queryId;

    public function __construct(IcqBot $bot, array $event)
    {
        parent::__construct($bot, $event);

        $this->callbackData = $event['payload']['callbackData'] ?? null;
        $this->queryId = $event['payload']['queryId'] ?? null;
    }

    /**
     * @param array $params [text, showAlert, url]
     * @return array|null
     */
    public function answer(array $params = []): ?array
    {
        $params['queryId'] = $this->queryId;
        return $this->bot->answerCallbackQuery($params);
    }
}