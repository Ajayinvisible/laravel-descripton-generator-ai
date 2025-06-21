<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeminiService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    protected string $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyAe6ywU-8XlS2P1g6QWwxL2g7SF_hA04Lk';


    public function generateMetaDescription(string $title, string $content = ''): ?string
    {
        $prompt = <<<PROMPT
        Write **only one** concise, SEO-friendly meta description (max 150 characters) for the following blog post.
        Do not include multiple options, bullets, or headings — just return one clear sentence.
        
        Title: {$title}
        
        Content:
        {$content}
        PROMPT;

        $response = $this->sendPrompt($prompt);

        return $response ? trim(strip_tags($response)) : null;
    }



    /**
     * Generate a short SEO-friendly meta keywords (max 140 characters).
     */
    public function generateMetaKeywords(string $title, string $content = ''): ?string
    {
        $prompt = <<<PROMPT
        Write a concise SEO-friendly meta keywords (max 150 characters) for a blog post.

        Title: {$title}

        Content:
        {$content}
        PROMPT;

        return $this->sendPrompt($prompt);
    }


    /**
     * Generate a rich structured HTML description with headings, subheadings, and lists.
     */
    public function generateFullDescription(string $title, string $content = ''): ?string
    {
        $prompt = <<<PROMPT
            You are a professional SEO content writer.
            
            Write a clean, structured **HTML product description** using the following formatting rules:
            
            - Don't Use `<h1>` it is already includede in title so
            - Use exactly two `<h2>` headings for key sections
            - Use `<h3>` and `<h4>` for additional nested subtopics as needed
            - Use `<p>` for all paragraphs
            - Use `<ul><li>` for features, specs, or benefits
            - Return only **valid HTML markup**
            - **Do NOT** include `<meta>`, `<script>`, or markdown wrappers like ```html
            
            Title: {$title}
            
            Content: {$content}
        PROMPT;

        return $this->sendPrompt($prompt);
    }



    /**
     * Shared method to send prompt to Gemini API and get response.
     */
    protected function sendPrompt(string $prompt): ?string
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-goog-api-key' => env('GEMINI_API_KEY'),
            ])->post($this->endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text');
            } else {
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gemini API exception: ' . $e->getMessage());
        }

        return null;
    }
}
