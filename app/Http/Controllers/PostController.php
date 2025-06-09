<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['tags', 'user'])
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('posts.form', compact('tags'));
    }

    public function store(Request $request)
    {
        try {
            // Validate request data, thêm validation ảnh
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'tags' => 'nullable|array',
                'tags.*' => 'exists:tags,id',
                'image' => 'nullable|image|max:2048', // tối đa 2MB
            ]);

            // Xử lý upload ảnh nếu có
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts', 'public');
            }

            // Tạo post với dữ liệu hợp lệ
            $post = Post::create([
                'title' => $data['title'],
                'content' => $data['content'],
                'image' => $imagePath,
                'user_id' => Auth::id()
            ]);

            // Sync tags nếu có
            if (!empty($data['tags'])) {
                $post->tags()->sync($data['tags']);
            }

            Log::info('Post created', [
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'title' => $post->title,
                'tags' => $data['tags'] ?? []
            ]);

            return redirect()->route('posts.index')
                ->with('success', 'Bài viết đã được tạo thành công.');

        } catch (\Exception $e) {
            Log::error('Post creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Không thể tạo bài viết. Vui lòng thử lại.');
        }
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        try {
            $this->authorize('update', $post);

            // Validate request, thêm ảnh
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'tags' => 'nullable|array',
                'tags.*' => 'exists:tags,id',
                'image' => 'nullable|image|max:2048',
            ]);

            $oldData = $post->toArray();

            // Xử lý upload ảnh mới, xóa ảnh cũ nếu có
            if ($request->hasFile('image')) {
                if ($post->image && Storage::disk('public')->exists($post->image)) {
                    Storage::disk('public')->delete($post->image);
                }
                $post->image = $request->file('image')->store('posts', 'public');
            }

            $post->title = $data['title'];
            $post->content = $data['content'];
            $post->save();

            if (isset($data['tags'])) {
                $post->tags()->sync($data['tags']);
            }

            Log::info('Post updated', [
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'old_data' => $oldData,
                'new_data' => $post->toArray(),
                'tags_changed' => isset($data['tags'])
            ]);

            return redirect()->route('posts.index')
                ->with('success', 'Bài viết đã được cập nhật.');

        } catch (\Exception $e) {
            Log::error('Post update failed', [
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Không thể cập nhật bài viết. Vui lòng thử lại.');
        }
    }

    public function destroy(Post $post)
    {
        try {
            $this->authorize('delete', $post);

            $postData = $post->toArray();
            $tags = $post->tags()->pluck('id')->toArray();

            // Xóa ảnh khi xóa bài viết
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }

            $post->tags()->detach();
            $post->delete();

            Log::info('Post deleted', [
                'user_id' => Auth::id(),
                'post_data' => $postData,
                'tags' => $tags
            ]);

            return redirect()->route('posts.index')
                ->with('success', 'Bài viết đã được xóa.');

        } catch (\Exception $e) {
            Log::error('Post deletion failed', [
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Không thể xóa bài viết. Vui lòng thử lại.');
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $this->authorize('bulkDelete', Post::class);

            $ids = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:posts,id'
            ])['ids'];

            $posts = Post::whereIn('id', $ids)->with('tags')->get();
            $deletedData = [];

            foreach ($posts as $post) {
                $deletedData[] = [
                    'id' => $post->id,
                    'title' => $post->title,
                    'tags' => $post->tags()->pluck('id')->toArray()
                ];

                // Xóa ảnh từng bài viết
                if ($post->image && Storage::disk('public')->exists($post->image)) {
                    Storage::disk('public')->delete($post->image);
                }

                $post->tags()->detach();
                $post->delete();
            }

            Log::info('Bulk posts deleted', [
                'user_id' => Auth::id(),
                'deleted_posts' => $deletedData
            ]);

            return redirect()->route('posts.index')
                ->with('success', 'Đã xóa ' . count($deletedData) . ' bài viết.');

        } catch (\Exception $e) {
            Log::error('Bulk deletion failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Không thể xóa các bài viết. Vui lòng thử lại.');
        }
    }
}
