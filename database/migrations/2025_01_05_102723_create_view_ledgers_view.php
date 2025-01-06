<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            SELECT
                l1.type,
                l1.description,
                l1.refrence,
                (
                    SELECT
                        COALESCE(SUM(l2.debit - l2.credit), 0)
                    FROM
                        ledgers l2
                    WHERE
                        l2.trx_date < l1.trx_date
                        AND l2.deleted_at IS NULL
                ) AS current,
                l1.debit,
                l1.credit,
                (
                    SELECT
                        COALESCE(SUM(l2.debit - l2.credit), 0)
                    FROM
                        ledgers l2
                    WHERE
                        l2.trx_date < l1.trx_date
                        AND l2.deleted_at IS NULL
                ) + l1.debit - l1.credit AS final,
                l1.trx_date,
                l1.updated_at,
                l1.deleted_at
            FROM
                ledgers l1
            WHERE
                l1.deleted_at IS NULL
            ORDER BY
                l1.trx_date ASC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW view_ledgers");
    }
};
