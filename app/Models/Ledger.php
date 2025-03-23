<?php

namespace App\Models;

use App\Models\ViewLedger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ledger extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'type',
        'description',
        'refrence',
        // 'current',
        'trx_date',
        'debit',
        'credit',
        // 'final'
        'transaction_category_id',
        'month_period_id',
        'year_period_id',
    ];

    public static function store($request){
        $data = ($request->id_ledger) ? Ledger::find($request->id_ledger) : new Ledger;
        $data->type = $request->type;
        $data->description = $request->description;
        $data->refrence = $request->refrence;
        $data->trx_date = $request->trx_date;
        // $data->current = $request->current;
        $data->debit = $request->debit;
        $data->credit = $request->credit;
        // $data->final = $request->final;

        $data->transaction_category_id = $request->transaction_category_id;
        $data->month_period_id = $request->month_period_id;
        $data->year_period_id = $request->year_period_id;

        $data->save();

        return $data?:false;
    }

    public static function report($request){
        // If no year is specified, use the current year
        $yearPeriodId = $request->year_period_id;
        if (!$yearPeriodId) {
            $currentYear = YearPeriod::where('is_current', true)->first();
            $yearPeriodId = $currentYear ? $currentYear->id : null;
        } else {
            $currentYear = YearPeriod::find($yearPeriodId);
        }

        // Get all months in order
        $months = MonthPeriod::orderBy('no_order')->get();

        // Get all transaction categories grouped by type
        $categories = TransactionCategory::orderBy('type')->get();
        $categoryGroups = [];
        foreach ($categories as $category) {
            if (!isset($categoryGroups[$category->type])) {
                $categoryGroups[$category->type] = [];
            }
            $categoryGroups[$category->type][] = $category;
        }

        $ledgers = Ledger::selectRaw("
            transaction_category_id,
            transaction_categories.type as type_category,
            transaction_categories.name as name_category,
            month_period_id,
            month_periods.name_month,
            year_period_id,
            year_periods.name_year,
            CASE
                WHEN transaction_categories.type = 'pemasukan' THEN sum(debit)
                WHEN transaction_categories.type = 'pengeluaran' THEN sum(credit)
                ELSE NULL
            END as total
        ")
        ->join('transaction_categories','ledgers.transaction_category_id','transaction_categories.id')
        ->join('month_periods','ledgers.month_period_id','month_periods.id')
        ->join('year_periods','ledgers.year_period_id','year_periods.id')
        ->where('ledgers.year_period_id', $yearPeriodId)
        ->groupBy('transaction_category_id')
        ->groupBy('transaction_categories.type')
        ->groupBy('transaction_categories.name')
        ->groupBy('month_period_id')
        ->groupBy('month_periods.name_month')
        ->groupBy('year_period_id')
        ->groupBy('year_periods.name_year')
        ->orderBy('transaction_categories.type')
        ->get();

        // Organize data by category and month
        $ledgersByCategory = [];

        // Pertama, inisialisasi struktur untuk semua kategori yang ada
        foreach ($categoryGroups as $type => $typeCats) {
            if (!isset($ledgersByCategory[$type])) {
                $ledgersByCategory[$type] = [
                    'categories' => [],
                    'monthly_totals' => array_fill(1, 12, 0)
                ];
            }

            foreach ($typeCats as $cat) {
                $ledgersByCategory[$type]['categories'][$cat->id] = [
                    'name' => $cat->name,
                    'monthly_values' => array_fill(1, 12, 0)
                ];
            }
        }

        foreach ($ledgers as $ledger) {
            $typeCategory = $ledger->type_category;
            $categoryId = $ledger->transaction_category_id;
            $monthId = $ledger->month_period_id;

            if (!isset($ledgersByCategory[$typeCategory])) {
                $ledgersByCategory[$typeCategory] = [
                    'categories' => [],
                    'monthly_totals' => array_fill(1, 12, 0)
                ];
            }

            if (!isset($ledgersByCategory[$typeCategory]['categories'][$categoryId])) {
                $ledgersByCategory[$typeCategory]['categories'][$categoryId] = [
                    'name' => $ledger->name_category,
                    'monthly_values' => array_fill(1, 12, 0)
                ];
            }

            // Set the value for this category and month
            $ledgersByCategory[$typeCategory]['categories'][$categoryId]['monthly_values'][$monthId] = $ledger->total;

            // Add to monthly totals for this type
            $ledgersByCategory[$typeCategory]['monthly_totals'][$monthId] += $ledger->total;
        }

        return [
            'ledgersByCategory' => $ledgersByCategory,
            'months' => $months,
            'year' => $currentYear,
            'categoryGroups' => $categoryGroups
        ];
    }
}
