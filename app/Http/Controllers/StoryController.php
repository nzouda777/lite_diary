<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;

class StoryController extends Controller
{
    //
    public function index() {
        return auth()->user()->stories()->latest()->get();
    }

    public function publicStories() {
        return Story::where("is_public", true)->with('user:id, name')->latest()->get();
    }

    public function show($id) {
        $story = Story::findOrFail($id);

        if (!$story->is_public && $story->user_id !== auth()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $story;
    }

    public function store(Request $request) {
        $validate = $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string',
            'is_public' => 'boolean'
        ]);
        return auth()->user()->stories()->create($validate);
    }

    public function update(Request $request, $id){
        $story = Story::findOrFail($id);

        if ($story->user_id !== auth()->id) {
            return response()->json(['message'=> 'unauthorized'], 403);
        }

        $story->update($request->only(['title','content', 'is_public']));

        return $story;
    }

    public function destroy($id) {
        $story = Story::findOrFail($id);

        if ($story->user_id !== auth()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $story->delete();
        return response()->json(['message'=> 'Deleted'],200);
    }
}
