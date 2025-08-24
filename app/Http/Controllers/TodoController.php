<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $todos = Todo::where('user_id', Auth::id())->get();
        return response()->json($todos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'completed' => 'boolean'
        ]);

        $todo = Todo::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'completed'=> $request->completed
        ]);
        return response()->json($todo, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $todo = Todo::where('user_id', Auth::id())->find($id);

        if (!$todo) {
            return response()->json(['message' => 'Unauthorized or not found', 'status' => 403], 403);
        }

        $data = [
            'todos' => $todo,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $todo = Todo::where('user_id', Auth::id())->find($id);

        if (!$todo) {
            return response()->json(['message' => 'Unauthorized or not found', 'status' => 403], 403);
        }

        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'completed' => 'boolean'
        ]);

        $todo->update($request->all());
        return response()->json($todo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $todo = Todo::where('user_id', Auth::id())->find($id);

        if (!$todo) {
            return response()->json(['message' => 'Unauthorized or not found', 'status' => 403], 403);
        }

        $todo->delete();
        return response()->json(['message' => 'Todo deleted successfully'], 200);
    }

    /**
     * Display a paginated listing of the resource.
     */
    public function paginatedIndex(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $page = (int) $request->query('page', 1);
        $limit = (int) $request->query('limit', 10);

        $query = Todo::where('user_id', Auth::id());
        $total = $query->count();

        $todos = $query->skip(($page - 1) * $limit)
                       ->take($limit)
                       ->get(['id', 'title', 'description','completed']);

        return response()->json([
            'data' => $todos,
            'page' => $page,
            'limit' => $limit,
            'total' => $total
        ]);
    }
}
