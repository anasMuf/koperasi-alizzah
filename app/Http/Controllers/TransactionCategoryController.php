<?php

namespace App\Http\Controllers;

use App\Helpers\LogPretty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionCategory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TransactionCategoryController extends Controller
{
    protected $menu = "kategori transaksi";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data['menu'] = $this->menu;
        if($request->ajax()){
            // $dateRange = [];
            // if(isset($request->dates) && $request->dates != ''){
            //     $dates = explode(' - ',$request->dates);
            //     foreach($dates as &$date){
            //         $dateRange[] = date('Y-m-d',strtotime($date));
            //     }
            // }
            $data = TransactionCategory::
            get();
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
        return view('transaction-category.main',$data);
    }

    public function form(Request $request){
        $data['data'] = $request->id ? TransactionCategory::find($request->id) : [];
        $content = view('transaction-category.form',$data)->render();
        return response()->json(['message' => 'oke', 'content' => $content],200);
    }

    public function store(Request $request){
        DB::beginTransaction();
        try {
            $rules = [
                'name' => 'required|unique:categories,name',
            ];
            $attributes = [
                'name' => 'Nama Kategori Transaksi',
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

            $transactionCategory = ($request->id) ? TransactionCategory::find($request->id) : new TransactionCategory;
            $transactionCategory->name = $request->name;
            $transactionCategory->type = $request->type;
            $transactionCategory->description = $request->description;
            $transactionCategory->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Transaction Category stored successfully',
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
            $transactionCategory = TransactionCategory::findOrFail($request->id);
            $transactionCategory->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Transaction Category deleted successfully',
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
