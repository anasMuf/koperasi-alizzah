<?php

namespace App\Http\Controllers\Maintenance;

use App\Models\Order;
use App\Models\Ledger;
use App\Models\Purchase;
use App\Helpers\LogPretty;
use App\Models\YearPeriod;
use App\Models\MonthPeriod;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use App\Models\PurchasePayment;
use App\Models\ReceivablesMember;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ReceivablesMemberPayment;

class SyncController extends Controller
{
    public static function set_mp_yp($date_param){
        $date = date('Y-m-d', strtotime($date_param));
        $month = date('m', strtotime($date_param));

        $month_period = MonthPeriod::where('no_month', $month)->first();
        $month_period_id = $month_period ? $month_period->id : null;


        $year_period_id = null;
        $matching_year_period = YearPeriod::where(function($query) use ($date) {
            $query->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date);
        })->first();

        if ($matching_year_period) {
            $year_period_id = $matching_year_period->id;
        }

        return [
            'month_period_id' => $month_period_id,
            'year_period_id' => $year_period_id,
        ];
    }

    public function tc_mp_yp(Request $request){
        try {
            DB::beginTransaction();

            $updated = [
                'purchases' => 0,#✅
                'purchase_payments' => 0,#✅
                'orders' => 0,#✅
                'order_payments' => 0,#✅
                'receivables_members' => 0,#✅
                'receivables_member_payments' => 0,#✅
                'ledgers' => 0#✅
            ];

            // Update Purchase
            $purchases = Purchase::where(function($query) {
                $query->where('transaction_category_id', 0)
                      ->orWhere('month_period_id', 0)
                      ->orWhere('year_period_id', 0);
            })->get();

            foreach ($purchases as $value) {
                $periods = self::set_mp_yp($value->purchase_at);
                $transaction_category_id = 0;

                $value->update([
                    'transaction_category_id' => $transaction_category_id,
                    'month_period_id' => $periods['month_period_id'],
                    'year_period_id' => $periods['year_period_id']
                ]);

                $updated['purchases']++;
            }

            // Update Purchase Payments
            $purchasePayments = PurchasePayment::where(function($query) {
                $query->where('transaction_category_id', 0)
                      ->orWhere('month_period_id', 0)
                      ->orWhere('year_period_id', 0);
            })->get();

            foreach ($purchasePayments as $value) {
                $periods = self::set_mp_yp($value->paid_at);
                $transaction_category_id = 12;

                $value->update([
                    'transaction_category_id' => $transaction_category_id,
                    'month_period_id' => $periods['month_period_id'],
                    'year_period_id' => $periods['year_period_id']
                ]);

                $updated['purchase_payments']++;
            }

            // Update Orders
            $orders = Order::where(function($query) {
                $query->where('transaction_category_id', 0)
                      ->orWhere('month_period_id', 0)
                      ->orWhere('year_period_id', 0);
            })->get();

            foreach ($orders as $value) {
                $periods = self::set_mp_yp($value->order_at);
                $transaction_category_id = 2;

                $value->update([
                    'transaction_category_id' => $transaction_category_id,
                    'month_period_id' => $periods['month_period_id'],
                    'year_period_id' => $periods['year_period_id']
                ]);

                $updated['orders']++;
            }

            // Update Order Payments
            $orderPayments = OrderPayment::where(function($query) {
                $query->where('transaction_category_id', 0)
                      ->orWhere('month_period_id', 0)
                      ->orWhere('year_period_id', 0);
            })->get();

            foreach ($orderPayments as $value) {
                $periods = self::set_mp_yp($value->paid_at);
                $transaction_category_id = 2;

                $value->update([
                    'transaction_category_id' => $transaction_category_id,
                    'month_period_id' => $periods['month_period_id'],
                    'year_period_id' => $periods['year_period_id']
                ]);

                $updated['order_payments']++;
            }

            // Update Receivables Members
            $receivablesMembers = ReceivablesMember::where(function($query) {
                $query->where('transaction_category_id', 0)
                      ->orWhere('month_period_id', 0)
                      ->orWhere('year_period_id', 0);
            })->get();

            foreach ($receivablesMembers as $value) {
                $periods = self::set_mp_yp($value->created_at);
                $transaction_category_id = 10;

                $value->update([
                    'transaction_category_id' => $transaction_category_id,
                    'month_period_id' => $periods['month_period_id'],
                    'year_period_id' => $periods['year_period_id']
                ]);

                $updated['receivables_members']++;
            }

            // Update Receivables Member Payments
            $receivablesMemberPayments = ReceivablesMemberPayment::where(function($query) {
                $query->where('transaction_category_id', 0)
                      ->orWhere('month_period_id', 0)
                      ->orWhere('year_period_id', 0);
            })->get();

            foreach ($receivablesMemberPayments as $value) {
                $periods = self::set_mp_yp($value->paid_at);
                $transaction_category_id = 4;

                $value->update([
                    'transaction_category_id' => $transaction_category_id,
                    'month_period_id' => $periods['month_period_id'],
                    'year_period_id' => $periods['year_period_id']
                ]);

                $updated['receivables_member_payments']++;
            }

            // Update Ledgers
            $ledgers = Ledger::where(function($query) {
                $query->where('transaction_category_id', 0)
                      ->orWhere('month_period_id', 0)
                      ->orWhere('year_period_id', 0);
            })->get();

            foreach ($ledgers as $value) {
                $periods = self::set_mp_yp($value->trx_date);
                $transaction_category_id = 0;

                $value->update([
                    'transaction_category_id' => $transaction_category_id,
                    'month_period_id' => $periods['month_period_id'],
                    'year_period_id' => $periods['year_period_id']
                ]);

                $updated['ledgers']++;
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Sinkronisasi periode bulan dan tahun berhasil',
                'updated' => $updated
            ], 200);


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
