<?php

namespace App\Http\Controllers\Api;

use App\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoResource;
use App\Http\Resources\TodoCollection;

class TodoController extends Controller
{
    /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
    public function index()
    {
        return new TodoCollection(Todo::latest()->paginate(5));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TodoRequest $request)
    {
        return new TodoResource(Todo::create($request->validated()));
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Todo  $todo
    * @return \Illuminate\Http\Response
    */
    public function show(Todo $todo)
    {
        return new TodoResource($todo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        $todo->update($request->validated());
        return new TodoResource($todo);
    }

    /**
     * Selected todos updated confirmed status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateConfirmed(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'checked' => 'required|boolean'
        ]);

        Todo::whereIn('id', $request->ids)->update(['confirmed' => $request->checked]);

        return ['success' => true];
    }

    /**
     * Remove all completed todos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyCompleted(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
        ]);

        Todo::destroy($request->ids);

        return ['success' => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        return $todo->delete();
    }
}
