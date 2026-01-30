<?php

namespace SmartSummary;

use GuzzleHttp\Client;
use SmartSummary\PromptService;

class AiService {
    private $config;
    private $client;
 
    public function __construct($config) {
        $this->config = $config;
        $this->client = new Client();
    }
 
    public function generateSummary($transcript, $section = null) {
        $prompt = $this->buildPrompt($transcript, $section);
 
        $response = $this->client->post('https://api.groq.com/openai/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->config['groq']['api_key'],
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => $this->config['groq']['model'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional meeting summary assistant. Extract accurate information only from the provided transcript. Do not invent information.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => $this->config['groq']['max_tokens'],
                'temperature' => $this->config['groq']['temperature']
            ]
        ]);
 
        $body = json_decode($response->getBody(), true);
        return $this->parseResponse($body['choices'][0]['message']['content'], $section);
    }
 
    private function buildPrompt($transcript, $section = null) {
        if ($section) {
            return PromptService::getSectionPrompt($section) . "\n\nTranscript:\n" . $transcript;
        }

        return PromptService::getMainPrompt() . "\n\nTranscript:\n" . $transcript;
    }
 
    private function parseResponse($response, $section = null) {
        $response = trim($response);
 
        if ($section) {
            $jsonStart = strpos($response, '[');
            $jsonEnd = strrpos($response, ']');
 
            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonStr = substr($response, $jsonStart, $jsonEnd - $jsonStart + 1);
                $data = json_decode($jsonStr, true);
 
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $data;
                }
            }
        } else {
            $jsonStart = strpos($response, '{');
            $jsonEnd = strrpos($response, '}');
 
            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonStr = substr($response, $jsonStart, $jsonEnd - $jsonStart + 1);
                $data = json_decode($jsonStr, true);
 
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $data;
                }
            }
 
        }

        return $this->getFallbackResponse($section);
    }
 
    private function getFallbackResponse($section = null) {
        if ($section) {
            return [];
        }
 
        return [
            'agenda' => [],
            'key_decisions' => [],
            'action_items' => []
        ];
    }
}

