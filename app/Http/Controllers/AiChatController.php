<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Landmark;
use App\Models\Review;
use App\Models\TravelStory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'conversation_id' => ['nullable', 'string', 'max:100'],
            'messages' => ['required', 'array', 'min:1'],
            'messages.*.role' => ['required', 'in:user,assistant,system,tool'],
            'messages.*.content' => ['required', 'string', 'max:4000'],
        ]);

        $apiKey = config('services.openrouter.key');
        if (!$apiKey) {
            return response()->json([
                'message' => 'OpenRouter is not configured. Add OPENROUTER_API_KEY in Railway environment variables.',
            ], 503);
        }

        $conversationId = $validated['conversation_id'] ?? (string) Str::uuid();
        $messages = collect($validated['messages'])
            ->slice(max(count($validated['messages']) - 12, 0))
            ->values()
            ->all();

        $latestUserMessage = data_get(
            collect($messages)
                ->reverse()
                ->first(function (array $message): bool {
                    return ($message['role'] ?? null) === 'user';
                }),
            'content',
            ''
        );

        $siteContext = $this->buildSiteContext($latestUserMessage);
        $systemPrompt = $this->buildSystemPrompt($siteContext['summary']);

        $payloadMessages = array_merge(
            [[
                'role' => 'system',
                'content' => $systemPrompt,
            ]],
            array_map(static function (array $message): array {
                return [
                    'role' => $message['role'],
                    'content' => $message['content'],
                ];
            }, $messages)
        );

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$apiKey,
            'HTTP-Referer' => config('services.openrouter.site_url'),
            'X-Title' => config('services.openrouter.site_name'),
            'Content-Type' => 'application/json',
        ])
            ->timeout(45)
            ->post(rtrim(config('services.openrouter.base_url'), '/').'/chat/completions', [
                'model' => config('services.openrouter.model'),
                'messages' => $payloadMessages,
                'temperature' => 0.7,
                'max_tokens' => 700,
            ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'OpenRouter request failed',
                'details' => $response->json(),
            ], 502);
        }

        $content = data_get($response->json(), 'choices.0.message.content', '');
        $suggestions = $this->buildSuggestions($latestUserMessage, $siteContext['landmarks']);

        return response()->json([
            'conversation_id' => $conversationId,
            'message' => [
                'role' => 'assistant',
                'content' => trim($content) ?: 'I could not generate a reply right now. Please try again.',
                'suggestions' => $suggestions,
            ],
            'context' => [
                'landmarks_used' => count($siteContext['landmarks']),
                'reviews_used' => count($siteContext['reviews']),
                'stories_used' => count($siteContext['stories']),
                'badges_used' => count($siteContext['badges']),
            ],
        ]);
    }

    private function buildSystemPrompt(string $siteSummary): string
    {
        return <<<PROMPT
You are Tut-Assistant, the friendly Egypt travel assistant for TUTBOT.

Rules:
- Be concise, helpful, and travel-focused.
- Answer in clear English.
- Use the provided site data for factual claims about landmarks, reviews, stories, and badges.
- If you do not know a fact from the site data, say so instead of inventing it.
- If the user asks about booking, direct them to the booking page.
- If the user asks about their saved data, remind them to sign in if needed.

Site data summary:
$siteSummary
PROMPT;
    }

    private function buildSiteContext(string $query): array
    {
        $tokens = collect(preg_split('/\s+/', Str::lower($query) ?: '') ?: [])
            ->filter(fn (string $token) => strlen($token) > 2)
            ->take(6)
            ->values()
            ->all();

        $landmarkQuery = Landmark::query();
        foreach ($tokens as $token) {
            $landmarkQuery->orWhere('name', 'like', "%{$token}%")
                ->orWhere('region', 'like', "%{$token}%")
                ->orWhere('category', 'like', "%{$token}%")
                ->orWhere('description', 'like', "%{$token}%");
        }

        $landmarks = $landmarkQuery
            ->orderByDesc('rating')
            ->limit(6)
            ->get([
                'id', 'name', 'region', 'category', 'price', 'rating', 'reviews',
                'description', 'best_season', 'opening_hours', 'closing_hours', 'cost_level',
            ])
            ->values();

        if ($landmarks->isEmpty()) {
            $landmarks = Landmark::query()
                ->orderByDesc('rating')
                ->limit(6)
                ->get([
                    'id', 'name', 'region', 'category', 'price', 'rating', 'reviews',
                    'description', 'best_season', 'opening_hours', 'closing_hours', 'cost_level',
                ])
                ->values();
        }

        $reviews = Review::query()
            ->orderByDesc('rating')
            ->limit(3)
            ->get(['id', 'name', 'rating', 'text', 'location'])
            ->values();

        $stories = TravelStory::query()
            ->orderByDesc('likes')
            ->limit(3)
            ->get(['id', 'traveler_name', 'location', 'category', 'excerpt', 'likes'])
            ->values();

        $badges = Badge::query()
            ->orderByDesc('points_required')
            ->limit(4)
            ->get(['id', 'name', 'description', 'tier'])
            ->values();

        $summary = implode("\n", [
            'Landmarks: '.($landmarks->isEmpty() ? 'none' : $landmarks->map(fn ($landmark) => sprintf(
                '- %s (%s) | category=%s | price=%s EGP | rating=%s | best season=%s',
                $landmark->name,
                $landmark->region,
                $landmark->category,
                $landmark->price,
                $landmark->rating,
                $landmark->best_season ?? 'n/a'
            ))->implode("\n")),
            'Reviews: '.($reviews->isEmpty() ? 'none' : $reviews->map(fn ($review) => sprintf(
                '- %s (%s) | rating=%s | "%s"',
                $review->name,
                $review->location ?? 'n/a',
                $review->rating,
                Str::limit($review->text, 120)
            ))->implode("\n")),
            'Stories: '.($stories->isEmpty() ? 'none' : $stories->map(fn ($story) => sprintf(
                '- %s (%s) | category=%s | likes=%s | "%s"',
                $story->traveler_name,
                $story->location,
                $story->category,
                $story->likes,
                Str::limit($story->excerpt, 120)
            ))->implode("\n")),
            'Badges: '.($badges->isEmpty() ? 'none' : $badges->map(fn ($badge) => sprintf(
                '- %s | tier=%s | %s',
                $badge->name,
                $badge->tier ?? 'n/a',
                Str::limit($badge->description, 80)
            ))->implode("\n")),
        ]);

        return compact('landmarks', 'reviews', 'stories', 'badges', 'summary');
    }

    private function buildSuggestions(string $query, $landmarks): array
    {
        $query = Str::lower($query);

        return $landmarks
            ->filter(function ($landmark) use ($query) {
                return Str::contains(Str::lower($landmark->name.' '.$landmark->region.' '.$landmark->category), $query)
                    || Str::contains($query, Str::lower($landmark->region));
            })
            ->take(3)
            ->map(fn ($landmark) => [
                'type' => 'landmark',
                'id' => (string) $landmark->id,
                'name' => $landmark->name,
            ])
            ->values()
            ->all();
    }
}