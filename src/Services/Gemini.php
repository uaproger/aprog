<?php

namespace Aprog\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * class GeminiService
 *
 * --------------------------------------------------------------------------
 *                               Chat Gemini AI
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
class Gemini
{
    protected string $apiKey;
    protected string $cacheKey;

    public function __construct(protected string $userId)
    {
        $this->apiKey = config('gemini.key');
        $this->cacheKey = "gemini_chat_history_{$this->userId}";
    }

    /**
     * --- Запитання до Gemini ---
     * @throws ConnectionException
     */
    public function ask(string $message): string
    {
        $history = $this->getHistory();

        # Додаємо нове повідомлення користувача
        $history[] = [
            'role' => 'user',
            'parts' => [['text' => $message]],
        ];

        $url = config('gemini.url');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(str_replace('{key}', $this->apiKey, $url), [
            'contents' => $history,
        ]);

        $reply = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '[No response]';

        # Додаємо відповідь моделі
        $history[] = [
            'role' => 'model',
            'parts' => [['text' => $reply]],
        ];

        # Оновлюємо історію в кеші
        if (config('gemini.life.forever')) {
            Cache::forever($this->cacheKey, $history);
        } else {
            Cache::put($this->cacheKey, $history, now()->addMinutes(config('gemini.life.duration')));
        }

        return $reply;
    }

    /**
     * --- Історія чату з Gemini (зберігається в кеші) ---
     * @return array
     */
    public function getHistory(): array
    {
        return Cache::get($this->cacheKey, []);
    }

    /**
     * --- Очищення чату з Gemini AI ---
     * @return void
     */
    public function reset(): void
    {
        Cache::forget($this->cacheKey);
    }
}
