<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ViewLedger;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ReceivablesMember;
use Illuminate\Support\Facades\DB;

class DashboardContoroller extends Controller
{
    protected $menu = "dashboard";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $data['menu'] = $this->menu;
        return view('dashboard.main',$data);
    }

    public function data(Request $request){
        $data = null;
        if($request->data == 'penjualanPiutang'){
            $data = Order::selectRaw('SUM(total) as penjualan, SUM(total - terbayar) as piutang')
            ->whereYear('created_at', $request->tahun)
            ->first();
        }elseif($request->data == 'posisiSaldo'){
            $dataAvailable = ViewLedger::selectRaw('MONTH(trx_date) as month, YEAR(trx_date) as year, final as saldo')
            ->whereYear('trx_date', $request->tahun)
            ->whereRaw('view_ledgers.trx_date = (
                SELECT MAX(l2.trx_date)
                FROM view_ledgers as l2
                WHERE MONTH(view_ledgers.trx_date) = MONTH(l2.trx_date)
                AND YEAR(view_ledgers.trx_date) = YEAR(l2.trx_date)
            )')
            ->orderByRaw('YEAR(trx_date),MONTH(trx_date)')
            ->get();

            $monthNames = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mai',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Augustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];
            $data = [];
            $lastSaldo = 0;

            foreach (range(1, 12) as $month) {
                $dataForMonth = $dataAvailable->firstWhere('month', $month);

                if(date('m') <= $month){
                    if ($dataForMonth) {
                        $lastSaldo = $dataForMonth->saldo;
                        $data[] = [
                            'month' => $month,
                            'month_name' => $monthNames[$month],
                            'year' => $request->tahun,
                            'saldo' => $lastSaldo
                        ];
                    }
                }
                // else {

                //     // $data[] = [
                //     //     'month' => $month,
                //     //     'month_name' => $monthNames[$month],
                //     //     'year' => $request->tahun,
                //     //     'saldo' => $lastSaldo
                //     // ];
                // }
            }
        }elseif($request->data == 'piutangAnggota'){
            $data = ReceivablesMember::select('receivables_members.total', 'receivables_members.terbayar', DB::raw('(receivables_members.total - receivables_members.terbayar) as sisa'), 'm.name')
            ->leftJoin('members as m', 'receivables_members.member_id', '=', 'm.id')
            ->where('status','BELUM LUNAS')
            ->orderBy('sisa', 'desc')
            ->get();
        }elseif($request->data == 'stokBarang'){
            $data = ProductVariant::selectRaw("
                CONCAT(p.name, ' ', COALESCE(CONCAT('(', product_variants.name, ')'), '')) as name_product,
                product_variants.stock,
                product_variants.limit_stock
            ")
            ->leftJoin('products as p', 'product_variants.product_id', '=', 'p.id')
            ->whereRaw('product_variants.stock - product_variants.limit_stock < product_variants.limit_stock')
            ->orderBy('product_variants.stock', 'asc')
            ->get();
        }
        return response()->json([
            'success' => true,
            'data' => $data
        ],200);
    }
}
