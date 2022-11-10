<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = Todo::all();
        if($todos->count() <= 0) {
            return response()->json([
                'status' => true,
                'message' => 'Data Kosong'
            ], 200);
        }

        return response()->json([
            'status'=> true,
            'data' => $todos
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = false;
        $message= '';
        //validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:todos',
            'description' => 'required'
        ], [
            'title.required' => 'Judul tidak boleh kosong',
            'title.unique' => 'Judul sudah ada dalam database',
            'description.required' => 'Deskripsi tidak boleh kosong'
        ]);
        // creating
        if($validator->fails()) {
            $status = false;
            $message = $validator->errors();
            return response()->json([
                'status' => $status,
                'message' => $message
            ], 400);
        }

        $todo = new Todo();
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->save();

        $status = true;
        $message = 'Sucess';
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $todo
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'status' => true,
                'message' => 'Data tidak ada'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $todo
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status = false;
        $message = '';
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'is_done' => 'required'
        ]);

        if ($validator->fails()) {
            $message = $validator->errors();
            return response()->json([
                'status'=> $status,
                'message' => $message
            ], 400);
        }

        $findTodo = Todo::find($id);
        if (!$findTodo) {
            return response()->json([
                'status' => false,
                'message' => 'Data not found'
            ], 404);
        }
        $findTodo -> update($request->all());
        return response()->json([
            'status'=> true,
            'message' => 'Success',
            'data' => $findTodo
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return response()->json([
                'status' => false,
                'message' => 'Data not found'
            ], 404);
        }

        $todo->destroy($id);
        return response()->json([
            'status' => true,
            'message' => 'Success Delete Todo'
        ], 200);
    }
}
