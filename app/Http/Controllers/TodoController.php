<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Todo;
use App\Catalog;
use App\Client;
use App\Sheet;

class TodoController extends Controller
{
    public function index(Request $request, $uuid)
    {
        $client = Client::where('uuid', $uuid)->firstOrFail();
        $query = $client->todos()
            ->with(['tags:name'])
            ->ordered();

        if ($request->query('my_day'))
        {
            $query->whereNotNull('my_day')->whereDate('my_day', Carbon::now());
        } else {
            $query->where('sheet_id', $request->query('sheet_id'));
        }

        $query->where('title', 'like', '%' . $request->get('search') . '%');

        return $query->ordered()->get();
    }

    public function store(Request $request, $uuid)
    {
        $client = Client::where('uuid', $uuid)->firstOrFail();
        $todo = new Todo($request->all());
        $todo->client()->associate($client);
        $todo->save();

        if ($request->input('tags')) 
        {
            $todo->attachTags($request->input('tags'));
        }

        $todo->load(['tags:name']);

        return $todo;
    }

    public function show($uuid, $todoId)
    {
        $client = Client::where('uuid', $uuid)->firstOrFail();
        $todo = $client->todos()->where('id', $todoId)->firstOrFail();

        return $todo;
    }

    public function update(Request $request, $uuid, $todoId)
    {
        $client = Client::where('uuid', $uuid)->firstOrFail();
        $todo = $client->todos()->where('id', $todoId)->firstOrFail();

        $todo->fill($request->all());
        $todo->syncTags($request->input('tags'));
        $todo->save();

        $todo->load(['tags:name']);
        return $todo;
    }

    public function destroy($uuid, $todoId)
    {
        $client = Client::where('uuid', $uuid)->firstOrFail();
        $todo = $client->todos()->where('id', $todoId)->firstOrFail();
        $todo->delete();
    }

    public function swap(Request $request)
    {
        Todo::setNewOrder($request->input('todos'));
        return response($request->input('todos'));
    }
}
