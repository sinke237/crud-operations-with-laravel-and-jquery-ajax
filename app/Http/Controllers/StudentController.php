<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;

class StudentController extends Controller
{
    public function index(){
        return view('student.index');
    }

    public function getstudents(){
        $students = Student::all();
        return response()->json([
            'students'=>$students,
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name'=> 'required|max:190',
            'email'=> 'required|max:190',
            'phone'=> 'required|max:190',
            'course'=> 'required|max:190',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            $student = new Student;
            $student->name = $request->input('name');
            $student->email = $request->input('email');
            $student->phone = $request->input('phone');
            $student->course = $request->input('course');
            $student->save();
            return response()->json([
                'status'=>200,
                'message'=>'Added Successfully',
            ]);
        }
    }

    public function edit($id){
        $student = Student::find($id);
        if($student){
            return response()->json([
                'status'=>200,
                'student'=>$student,
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Student Not Found',
            ]);
        }
    }
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name'=> 'required|max:190',
            'email'=> 'required|max:190',
            'phone'=> 'required|max:190',
            'course'=> 'required|max:190',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            $student = Student::find($id);
            if($student){
                $student->name = $request->input('name');
                $student->email = $request->input('email');
                $student->phone = $request->input('phone');
                $student->course = $request->input('course');
                $student->update();
                return response()->json([
                    'status'=>200,
                    'message'=>'Updated Successfully',
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Student Not Found',
                ]);
            }
        }
    }
    public function destroy($id){
        $student = Student::find($id);
        $student->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Student deleted successfully',
        ]);
    }

}
