<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AiService
{
    /**
     * Generate MCQ questions based on a topic and subject.
     * 
     * @param string $topic
     * @param string $subject
     * @param int $count
     * @return array
     */
    public function generateMcqs(string $topic, string $subject, int $count = 5)
    {
        // For demonstration purposes, we verify if an API key exists in config
        $apiKey = config('services.openai.key');

        if (!$apiKey || $apiKey === 'your-api-key-here') {
            return $this->getMockMcqs($topic, $subject, $count);
        }

        try {
            // Placeholder for real OpenAI implementation
            /*
             $response = Http::withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
             'model' => 'gpt-4',
             'messages' => [
             ['role' => 'system', 'content' => 'You are an educational expert. Generate multiple choice questions in JSON format.'],
             ['role' => 'user', 'content' => "Generate {$count} MCQ questions about '{$topic}' for the subject '{$subject}'."]
             ]
             ]);
             return $response->json();
             */
            return $this->getMockMcqs($topic, $subject, $count);
        }
        catch (\Exception $e) {
            Log::error("AI Service Error: " . $e->getMessage());
            return ['error' => 'Failed to connect to AI Service'];
        }
    }

    /**
     * Fallback mock data for demonstration.
     */
    private function getMockMcqs($topic, $subject, $count)
    {
        $questions = [];
        for ($i = 1; $i <= $count; $i++) {
            $questions[] = [
                'question' => "Sample Question {$i} about {$topic} in {$subject}?",
                'options' => [
                    ['option' => 'Option A (Correct)', 'is_answer' => 1],
                    ['option' => 'Option B', 'is_answer' => 0],
                    ['option' => 'Option C', 'is_answer' => 0],
                    ['option' => 'Option D', 'is_answer' => 0],
                ],
                'note' => "This is a sample explanation for question {$i}."
            ];
        }
        return [
            'success' => true,
            'data' => $questions,
            'message' => 'This is a mock response (AI Service is in Demo Mode)'
        ];
    }
}
