<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ledger;
use App\Models\ProductVariant;
use App\Models\ReceivablesMember;
use Illuminate\Http\Request;
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
            ->whereYear('created_at', date('Y'))
            ->first();
        }elseif($request->data == 'posisiSaldo'){
            $dataAvailable = Ledger::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, final as saldo')
            // ->where('refrence', 'SALDO')
            ->whereYear('created_at', date('Y'))
            // ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->whereRaw('ledgers.created_at = (
                SELECT MAX(l2.created_at)
                FROM ledgers as l2
                WHERE MONTH(ledgers.created_at) = MONTH(l2.created_at)
                  AND YEAR(ledgers.created_at) = YEAR(l2.created_at)
            )')
            ->orderByRaw('YEAR(created_at),MONTH(created_at)')
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
            foreach (range(1, 12) as $month) {
                $dataForMonth = $dataAvailable->firstWhere('month', $month);

                if ($dataForMonth) {
                    $data[] = [
                        'month' => $month,
                        'month_name' => $monthNames[$month],
                        'year' => date('Y'),
                        'saldo' => $dataForMonth->saldo
                    ];
                } else {
                    $data[] = [
                        'month' => $month,
                        'month_name' => $monthNames[$month],
                        'year' => date('Y'),
                        'saldo' => 0
                    ];
                }
            }
        }elseif($request->data == 'piutangAnggota'){
            $data = ReceivablesMember::select('receivables_members.total', 'receivables_members.terbayar', DB::raw('(receivables_members.total - receivables_members.terbayar) as sisa'), 'm.name')
            ->leftJoin('members as m', 'receivables_members.member_id', '=', 'm.id')
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
