<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all();
        return response()->json($departments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = ['name'=>'required|string|min:1|max:100'];
        $validator = \Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()->all()
            ],400);
        }
        $department = new Department($request->input());
        $department->save();
        return response()->json([
            'status'=>true,
            'errors'=>'Department created succefully'
        ],200); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return response()->json(['status'=>true,'data'=>$department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $rules = ['name'=>'required|string|min:1|max:100'];
        $validator = \Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()->all()
            ],400);
        }
        $department->update($request->input());
        return response()->json([
            'status'=>true,
            'errors'=>'Department updated succefully'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json([
            'status'=>true,
            'errors'=>'Department deleted succefully'
        ],200);
    }
}
