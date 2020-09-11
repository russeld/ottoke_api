<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Tag;
use App\Client;

class TagsController extends Controller
{
    public function index(Request $request, $uuid)
    {
        $client = Client::where('uuid', $uuid)->firstOrFail();
        return $client->tags->pluck('name');
    }

    public function store(Request $request, $uuid)
    {
        $client = Client::where('uuid', $uuid)->firstOrFail();
        $tag = Tag::create(['name' => $request->input('tag')]);
        $client->attachTag($tag);
        return $tag;
    }

    public function delete($tagId, $uuid)
    {
        $client = Client::where('uuid', $uuid)->firstOrFail();
        $tag = $client->tags()->find($tagId);
        $tag->delete();
    }
}
