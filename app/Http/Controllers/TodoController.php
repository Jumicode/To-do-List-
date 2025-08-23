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
        $todos = Todo::where('user_id', Auth::id())->get();
        return response()->json($todos, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
    public function show(Todo $todo)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       $todos = Todo::where('user_id', Auth::id())->findOrFail($id);
       $todos->delete();
       return response()->json(['message' => 'Todo deleted successfully'], 200);
    }
}
