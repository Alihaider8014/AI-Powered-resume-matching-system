<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false, // Disable SSL verification
        ]);
        // $this->client = new Client();
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function analyzeResumes($jobDescription, $resumes)
    {
        $prompt = "You are an expert recruiter. Analyze the following resumes against the given job description. Rank them from best to worst and provide a match percentage.\n\nJob Description:\n{$jobDescription}\n\nResumes:\n";

        foreach ($resumes as $index => $resume) {
            $prompt .= ($index + 1) . '️⃣ Candidate ' . ($index + 1) . ': ' . $resume . "\n";
        }

        $prompt .= "\nOutput format:\n1. Ranked List with Match Score (%)\n2. Missing Skills per Candidate\n3. Brief Justification for Each Ranking";

        // Send request to OpenAI API
        $response = $this->client->post('https://api.openai.com/v1/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an expert recruiter.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return $body['choices'][0]['message']['content'] ?? '';
    }
}
