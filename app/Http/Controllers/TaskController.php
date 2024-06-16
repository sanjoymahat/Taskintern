<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function  createTask(Request $request){

        
        $validator = Validator::make($request->all(), [
            'task' => 'required | string',
            'status' => ' required | boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }
        $validatedData = [
            'task' => $request->task,
            'status' => $request->status,
            'user_id' =>Auth::id()
        ];
        $task=task::create($validatedData);
        if($task){
            return response()->json(["status" => true, "message" => "Task created","task" => $validatedData], 201);
        }
        else{
            return response()->json(["status" => false, "message" => "Failed To create Task"],422);

        }

    }
    public function updateTask(Request $request){
       
        $validatedData = [
            'status' => $request->status,
            'id'=>$request->id
        ];
        $task = task::find($request->id);
        if($task){
            $task->status = $request->status;
            $task->save();
            return response()->json(["status" => true, "message" => "Task Update"], 201);
        }
        return response()->json(["status" => false, "message" => " Task Not Found !"],404);

       

    }
}
