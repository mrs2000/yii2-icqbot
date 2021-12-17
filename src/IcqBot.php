<?php

namespace mrssoft\icqbotapi;

use Yii;
use yii\base\Component;
use yii\di\Instance;
use yii\mutex\Mutex;

/**
 * @see https://icq.com/botapi/
 */
class IcqBot extends Component
{
    public string $token = '';

    public string $apiUrl = 'https://api.icq.net/bot/v1/';

    public Mutex|array|string $mutex = 'mutex';

    public int $pollTime = 60;

    public function init()
    {
        parent::init();
        $this->mutex = Instance::ensure($this->mutex, Mutex::class);
    }

    public function self(): ?array
    {
        return $this->request('self/get');
    }

    public function sendText(string $chatId, string $text, array $buttons = []): ?array
    {
        $params = [
            'chatId' => $chatId,
            'text' => $text,
        ];
        if (count($buttons)) {
            $params['inlineKeyboardMarkup'] = json_encode($buttons);
        }

        return $this->request('messages/sendText', $params);
    }

    public function answerCallbackQuery(array $params): ?array
    {
        return $this->request('messages/answerCallbackQuery', $params);
    }

    /**
     * @return IcqEvent[]
     */
    public function pollEvents(): array
    {
        $events = [];

        if ($this->mutex->acquire('icq-poll-events')) {

            $botParams = $this->loadBotParams();
            $lastEventId = $botParams['lastEventId'] ?? 11;

            try {
                $params = [
                    'lastEventId' => $lastEventId,
                    'pollTime' => $this->pollTime,
                ];

                $response = $this->request('events/get', $params, [
                    CURLOPT_TIMEOUT => ($this->pollTime + 1)
                ]);

                if (isset($response['ok']) && $response['ok'] === true && empty($response['events']) === false) {
                    foreach ($response['events'] as $event) {

                        $events[] = match ($event['type']) {
                            IcqEvent::NEW_MESSAGE => new IcqEventNewMessage($this, $event),
                            IcqEvent::CALLBACK_QUERY => new IcqEventCallbackQuery($this, $event),
                        };

                        $botParams['lastEventId'] = $event['eventId'];
                    }
                }

                $this->saveBotParams($botParams);

            } finally {
                $this->mutex->release('icq-poll-events');
            }
        }

        return $events;
    }

    private function request(string $endpoint, array $query = [], array $options = []): ?array
    {
        $query['token'] = $this->token;

        $options += [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
        ];
/*        if (count($body)) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = json_encode($body);
        }*/

        $query = array_map(static function ($item) {
            if ($item === true) {
                $item = 'true';
            } else if ($item === false) {
                $item = 'false';
            }
            return $item;
        }, $query);

        $this->apiUrl = rtrim($this->apiUrl, '/') . '/';
        $ch = curl_init($this->apiUrl . $endpoint . '?' . http_build_query($query));
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $code == 200 && $response ? json_decode($response, true) : null;
    }

    private function saveBotParams(array $params): void
    {
        $path = Yii::getAlias('@runtime') . '/' . $this->runtimeFilename();
        file_put_contents($path, json_encode($params, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    private function loadBotParams(): array
    {
        $path = Yii::getAlias('@runtime') . '/' . $this->runtimeFilename();
        if (is_file($path)) {
            $json = file_get_contents($path);
            return json_decode($json, true);
        }
        return [];
    }

    private function runtimeFilename(): string
    {
        return 'icqbot' . md5($this->token) . '.icq';
    }
}