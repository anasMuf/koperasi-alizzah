<?php

namespace App\Http\Controllers\SIAKAD;

use App\Helpers\LogPretty;
use Illuminate\Http\Request;
use App\Models\SIAKAD\Student;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    protected $menu = "siswa";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data['menu'] = $this->menu;
        if($request->ajax()){
            $dateRange = [];
            if(isset($request->dates) && $request->dates != ''){
                $dates = explode(' - ',$request->dates);
                foreach($dates as &$date){
                    $dateRange[] = date('Y-m-d',strtotime($date));
                }
            }
            $data = Student::
            when($dateRange, function($q) use ($dateRange){
                $q->whereBetween('created_at',$dateRange);
            })
            ->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '
                <button type="button" class="btn btn-warning btn-sm" onclick="openData('.$row->id.')" title="edit"><i class="fa fa-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteData('.$row->id.')" title="hapus"><i class="fa fa-trash"></i></button>
                ';
                return $btn;
            })
            ->rawColumns([
                'action'
            ])
            ->make(true);
        }
        return view('students.main',$data);
    }

    public function form(Request $request){
        $data['data'] = $request->id ? Student::find($request->id) : [];
        $content = view('students.form',$data)->render();
        return response()->json(['message' => 'oke', 'content' => $content],200);
    }

    public function store(Request $request){
        DB::beginTransaction();
        try {
            $rules = [
                'name' => 'required|unique:students,name',
                'gender' => 'required',
            ];
            $attributes = [
                'name' => 'Nama Siswa',
                'gender' => 'Jenis Kelamin',
            ];
            $messages = [
                'required' => 'Kolom :attribute harus terisi',
                'unique' => ':attribute sudah ada',
            ];
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->getMessageBag()
                ],422);
            }

            $student = ($request->id) ? Student::find($request->id) : new Student;
            $student->no_induk = $request->no_induk;
            $student->name = $request->name;
            $student->gender = $request->gender;
            $student->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Student stored successfully',
            ],200);
        } catch (\Throwable $th) {
            DB::rollBack();
            LogPretty::error($th);
            return response()->json([
                'success'=> false,
                'message'=> 'Internal Server Error!',
            ],500);
        }
    }

    public function delete(Request $request){
        DB::beginTransaction();
        try{
            $student = Student::findOrFail($request->id);
            $student->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully',
            ],200);
        } catch (\Throwable $th) {
            DB::rollBack();
            LogPretty::error($th);
            return response()->json([
                'success'=> false,
                'message'=> 'Internal Server Error!',
            ],500);
        }
    }
}
