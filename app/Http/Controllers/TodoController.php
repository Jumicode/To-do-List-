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
    public function show($id)
    {
        
        $todos = Todo::where('user_id', Auth::id())->findOrFail($id);
        
        if (!$todos) {
            $data = [
                'message' => 'Todo not found',
                'status' => 404
        ];
            return response()->json($data, 404);
        }

        $data = [
            'todos' => $todos,
            'status' => 200
        ];
        return response()->json($data, 200); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $todos = Todo::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
       'title' => 'required|string',
       'description' => 'nullable|string',
       'completed' => 'boolean' 
        ]);

     $todos->update($request->all());
     return response()->json($todos);    

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
