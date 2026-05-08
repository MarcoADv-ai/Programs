<?php

namespace App\Http\Controllers;

use App\Http\Requests\WikiRequest;
use App\Models\WikiCategories;
use App\Models\WikiPost;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class WikiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $wiki_categories = WikiCategories::with('posts')->get();
            $wiki_post = WikiPost::with('category')->orderBy('title', 'asc')->notArchived()->get();

            return Inertia::render('Wiki/Index', ['wiki_categories' => $wiki_categories, 'wiki_post' => $wiki_post]);
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Errors/404');
        } catch (Exception $e) {
            return Inertia::render('Errors/500');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = WikiCategories::all();
        return Inertia::render('Admin/Wiki/Create', ['wiki_categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WikiRequest $request)
    {
        $validated = $request->validated();

        if ($validated) {
            $wiki_post = new WikiPost();
            $wiki_post->title = $request->title;
            $wiki_post->slug = $request->slug;
            $wiki_post->body = $request->body;
            $wiki_post->icon = $request->icon;
            $wiki_post->description = $request->description;
            $wiki_post->category_id = $request->category_id;
            $wiki_post->save();
            return response()->json([
                'message' => 'Post "' . $wiki_post->title . '" created successfully!',
                'status' => 'success'
            ], 200);
        }

        return response()->json([
            'message' => 'Something went wrong!',
            'status' => 'error'
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($category, $slug)
    {
        $wikiPost = WikiPost::with('category')->where('slug', $slug)->firstOrFail();
        $wikiCategories = WikiCategories::with('posts')->get();
        // dd($wikiCategories);
        return Inertia::render('Wiki/Show', ['wiki_post' => $wikiPost, 'wiki_categories' => $wikiCategories]);
        try {
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Errors/404');
        } catch (Exception $e) {
            return Inertia::render('Errors/500');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        $wiki_post = WikiPost::findOrfail($id);
        $categories = WikiCategories::all();
        return Inertia::render('Admin/Wiki/Edit', ['wiki_post' => $wiki_post, 'wiki_categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|max:255',
            'icon' => 'required|max:255',
            'description' => 'required',
            'body' => 'required',
            'category_id' => 'required|integer',
        ]);

        try {
            $wiki_post = WikiPost::findOrFail($id);
            $wiki_post->update($validatedData);
            return response()->json([
                'message' => 'Post "' . $wiki_post->title . '" updated successfully!',
                'status' => 'success'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Post not found',
                'status' => 'error'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'There was an error updating the post',
                'status' => 'error'
            ], 500);
        }
    }

    public function toggleArchive(Request $request, $id)
    {
        try {
            $wiki_post = WikiPost::findOrFail($id);
            $wiki_post->archive = !$wiki_post->archive;
            $wiki_post->save();

            $message = $wiki_post->archive ? 'Post archived successfully!' : 'Post unarchived successfully!';
            return response()->json([
                'message' => $message,
                'status' => 'success'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Post not found',
                'status' => 'error'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'There was an error updating the post',
                'status' => 'error'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $wiki_post = WikiPost::findOrFail($id);
        $wiki_post->delete();
        return response()->json([
            'message' => 'Post "' . $wiki_post->title . '" deleted successfully!',
            'status' => 'success'
        ], 200);
    }

    public function show_category($id)
    {
        $wiki_category = WikiCategories::with('posts')->findOrFail($id);

        return response()->json([
            'wiki_category' => $wiki_category,
        ], 200);
    }

    public function update_category($id)
    {
        $wiki_category = WikiCategories::findOrFail($id);
        $wiki_category->name = request()->name;
        $wiki_category->slug = request()->slug;
        $wiki_category->save();

        return response()->json([
            'message' => 'Category "' . $wiki_category->name . '" updated successfully!',
            'status' => 'success'
        ], 200);
    }

    public function store_category(Request $request)
    {
        $wiki_category = new WikiCategories();
        $wiki_category->name = $request->name;
        $wiki_category->slug = $request->slug;
        $wiki_category->save();
        return response()->json([
            'message' => 'Category "' . $wiki_category->name . '" created successfully!',
            'status' => 'success'
        ], 200);
    }

    public function destroy_category($id)
    {
        $wiki_category = WikiCategories::findOrFail($id);
        $wiki_category->delete();
        return response()->json([
            'message' => 'Category "' . $wiki_category->name . '" deleted successfully!',
            'status' => 'success'
        ], 200);
    }
}
