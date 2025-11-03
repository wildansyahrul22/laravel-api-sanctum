<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodolistResource;
use App\Models\Logging;
use App\Models\Todolist;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TodolistController extends Controller
{
  use AuthorizesRequests;
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $todolist = Todolist::latest()->where('user_id', Auth::user()->id)->get();

    try {
      return TodolistResource::collection($todolist);
    } catch (Exception $e) {
      Log::error("Failed get todolist", ['error' => $e->getMessage()]);

      Logging::record("Failed get all todolist: " . $e->getMessage());

      return response()->json([
        'message' => 'Failed to get all todolist data',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $this->authorize('create', Todolist::class);

    $data = $request->validate([
      'title' => 'required|min:3|max:255',
      'desc' => 'required',
      'is_done' => 'required|boolean'
    ]);

    $todo = Todolist::where('title', $data['title'])->first();

    if ($todo) {
      return response()->json(['message' => 'Todolist already exists'], 409);
    }


    // return response()->json(['message' => 'successfully added todolist data'], 201);
    try {
      $data['user_id'] = Auth::user()->id;
      Todolist::create($data);

      return response()->json(['message' => 'successfully created todolist data'], 201);
    } catch (ValidationException $e) {
      Logging::record("Failed created todolist: " . $e->getMessage());
      return response()->json(['message' => 'failed to created todolist data', 'error' => $e->errors()]);
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
      Logging::record("Failed updated todolist with id " . $id . ": " . $e->getMessage());
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
      Logging::record("Failed deleted todolist with id " . $id . ": " . $e->getMessage());
      return response()->json([
        'message' => 'Failed to deleted todolist data',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
