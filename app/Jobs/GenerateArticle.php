<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\AiContentService;
use App\Services\UnsplashService;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Events\ArticleGenerated;

class GenerateArticle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topic;
    protected $categoryId;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $topic, int $categoryId, int $userId)
    {
        $this->topic = $topic;
        $this->categoryId = $categoryId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(AiContentService $aiContentService, UnsplashService $unsplashService): void
    {
        Log::info("Starting AI article generation job for topic: {$this->topic}");

        try {
            // 1. Generate article content and title
            $articleData = $aiContentService->generateFullArticle($this->topic);

            if (!$articleData) {
                Log::error("Failed to generate article content for topic: {$this->topic}");
                // Optional: Notify user of failure
                return;
            }

            // 2. Fetch image based on the AI-generated prompt
            $imagePath = null;
            if (!empty($articleData['image_prompt'])) {
                $imagePath = $unsplashService->fetchAndSaveImage($articleData['image_prompt']);
                 if (!$imagePath) {
                    Log::warning("Could not fetch image for prompt: {$articleData['image_prompt']}");
                }
            }

            // 3. Create the post record in the database
            $post = Post::create([
                'title' => $articleData['title'],
                'content' => $articleData['content'],
                'category_id' => $this->categoryId,
                'user_id' => $this->userId,
                'image' => $imagePath,
                'status' => 'draft', // Save as draft for review
                'published_at' => null,
            ]);

            // Siarkan event notifikasi ke pengguna
            $user = User::find($this->userId);
            if ($user) {
                ArticleGenerated::dispatch($post, $user);
            }

            Log::info("Successfully generated and saved article for topic: {$this->topic}");
            // Optional: Notify user of success

        } catch (\Exception $e) {
            Log::error("Exception in GenerateArticle job: " . $e->getMessage(), [
                'topic' => $this->topic,
                'user_id' => $this->userId,
            ]);
            // Optional: Notify user of failure
        }
    }
}
