<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodolistResource;
use App\Models\Todolist;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TodolistController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $todolist = Todolist::latest()->get();

    try {
      return TodolistResource::collection($todolist);
    } catch (Exception $e) {
      Log::error("Failed get todolist", ['error' => $e->getMessage()]);
      return response()->json([
        'message' => 'Failed to get todolist data',
        'error' => $e->getMessage()
      ]);
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $data = $request->validate([
      'title' => 'required|min:3|max:255',
      'desc' => 'required',
      'is_done' => 'required|boolean'
    ]);

    $todo = Todolist::where('title', $data['title'])->first();

    if ($todo) {
      return response()->json(['message' => 'Todolist already exists'], 409);
    }

    Todolist::create($data);

    // return response()->json(['message' => 'successfully added todolist data'], 201);
    try {
      return response()->json(['message' => 'successfully added todolist data']);
    } catch (ValidationException $e) {
      return response()->json(['message' => 'failed to add todolist data', 'error' => $e->errors()]);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $todo = Todolist::find($id);

    if (!$todo) {
      return response()->json(null, 404);
    }

    return response($todo);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $data = $request->validate([
      'title' => 'required|min:3|max:255',
      'desc' => 'required',
      'is_done' => 'required|boolean'
    ]);

    $todo = Todolist::find($id);

    if (!$todo) {
      return response()->json(['message' => 'Todolist not found'], 404);
    }

    try {
      $todo->update($data);
      return response()->json([
        'message' => 'Successfully updated todolist data',
        'data' => $todo
      ], 200);
    } catch (Exception $e) {
      return response()->json([
        'message' => 'Failed to update todolist data',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {

    $todo = Todolist::find($id);

    if (!$todo) {
      return response()->json(['message' => 'Todolist not found'], 404);
    }

    try {
      $todo->delete();
      return response()->json([
        'message' => 'Successfully deleted todolist data'
      ], 200);
    } catch (Exception $e) {
      return response()->json([
        'message' => 'Failed to deleted todolist data',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
