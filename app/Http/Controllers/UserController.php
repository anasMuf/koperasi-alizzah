<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\LogPretty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $menu = "akun pengguna";

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
            $data = User::
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
        return view('users.main',$data);
    }

    public function form(Request $request){
        $data['data'] = $request->id ? User::find($request->id) : [];
        $content = view('users.form',$data)->render();
        return response()->json(['message' => 'oke', 'content' => $content],200);
    }

    public function store(Request $request){
        DB::beginTransaction();
        try {
            $rules = [
                'name' => 'required|unique:users,name',
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

            $student = ($request->id) ? User::find($request->id) : new User;
            $student->no_induk = $request->no_induk;
            $student->name = $request->name;
            $student->gender = $request->gender;
            $student->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User stored successfully',
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
            $student = User::findOrFail($request->id);
            $student->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
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
