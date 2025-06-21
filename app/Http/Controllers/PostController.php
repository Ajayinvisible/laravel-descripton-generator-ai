<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('index', [
            'posts' => $posts
        ]);
    }

    public function create()
    {
        return view('welcome');
    }

    public function store(Request $request, GeminiService $geminiService)
    {
        // 1. Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:160',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
        ]);

        $title = $validated['title'];
        $slug = Str::slug($title); // generate slug
        $content = ''; // optional input content

        // 2. Generate missing fields with Gemini
        $meta_description = $validated['meta_description'] ?? $geminiService->generateMetaDescription($title, $content);
        $meta_keywords = $validated['meta_keywords'] ?? $geminiService->generateMetaKeywords($title, $content);
        $description = $validated['description'] ?? $geminiService->generateFullDescription($title, $content);

        // 3. Upload image if exists
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public'); // stores in storage/app/public/posts
        }

        // 4. Create post in database
        $post = Post::create([
            'title' => $title,
            'slug' => $slug,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'description' => $description,
            'image' => $imagePath,
        ]);

        // 5. Return response
        return $post
            ? redirect()->back()->with('success', 'Post created successfully.')
            : redirect()->back()->withInput()->with('error', 'Post not created.');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        if (!$post) {
            return redirect()->back()->with('error', 'Not found');
        }
        return view('edit', [
            'post' => $post
        ]);
    }

    public function update(Request $request, $id, GeminiService $geminiService)
    {
        $post = Post::findOrFail($id);

        // 1. Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:160',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
        ]);

        $title = $validated['title'];
        $slug = Str::slug($title);
        $content = '';

        // 2. Use existing or generate with Gemini
        $meta_description = $validated['meta_description'] ?? $geminiService->generateMetaDescription($title, $content);
        $meta_keywords = $validated['meta_keywords'] ?? $geminiService->generateMetaKeywords($title, $content);
        $description = $validated['description'] ?? $geminiService->generateFullDescription($title, $content);

        // 3. Handle image upload if new image is provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        } else {
            $imagePath = $post->image; // keep existing image
        }

        // 4. Update post
        $updated = $post->update([
            'title' => $title,
            'slug' => $slug,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'description' => $description,
            'image' => $imagePath,
        ]);

        // 5. Return response
        return $updated
            ? redirect()->back()->with('success', 'Post updated successfully.')
            : redirect()->back()->withInput()->with('error', 'Post update failed.');
    }
}
