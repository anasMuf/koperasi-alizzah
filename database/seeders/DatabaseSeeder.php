<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Member;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'koperasi',
        //     'username' => 'koperasi',
        //     'password' => 'koperasi',
        //     'email' => 'test@example.com',
        //     'role' => 'admin',
        // ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'yayasan',
        //     'username' => 'yayasan',
        //     'password' => 'yayasan',
        //     'role' => 'yayasan',
        // ]);

        // $teachers = Teacher::all();
        // $members = Member::all();
        // if($teachers && !$members){
        //     foreach($teachers as $teacher){
        //         Member::create([
        //             'reference' => $teacher->id,
        //             'type' => 'teacher',
        //             'name' => $teacher->name,
        //         ]);
        //     }
        // }

        // Pemasukan
        \App\Models\TransactionCategory::create([
            'name' => 'Uang KAS Murid Baru',
            'type' => 'pemasukan',
            'description' => null,
        ]);
        \App\Models\TransactionCategory::create([
            'name' => 'Penjualan Barang',
            'type' => 'pemasukan',
            'description' => null,
        ]);
        \App\Models\TransactionCategory::create([
            'name' => 'Bonus Vendor',
            'type' => 'pemasukan',
            'description' => null,
        ]);
        \App\Models\TransactionCategory::create([
            'name' => 'Pelunasan Piutang Anggota',
            'type' => 'pemasukan',
            'description' => null,
        ]);
        \App\Models\TransactionCategory::create([
            'name' => 'DP tahun ajaran 25/26',
            'type' => 'pemasukan',
            'description' => null,
        ]);
        \App\Models\TransactionCategory::create([
            'name' => 'KAS Periode Sebelumnya',
            'type' => 'pemasukan',
            'description' => null,
        ]);
        \App\Models\TransactionCategory::create([
            'name' => 'Pemasukan Lain-lain',
            'type' => 'pemasukan',
            'description' => null,
        ]);

        // Pengeluaran
        \App\Models\TransactionCategory::create([
            'name' => 'Belanja Jenis Kain',
            'type' => 'pengeluaran',
            'description' => null,
        ]);
        \App\Models\TransactionCategory::create([
            'name' => 'Belanja Jenis Kertas',
            'type' => 'pengeluaran',
            'description' => null,
        ]);
        \App\Models\TransactionCategory::create([
            'name' => 'Kesejahtereaan Guru',
            'type' => 'pengeluaran',
            'description' => null,
        ]);
        \App\Models\TransactionCategory::create([
            'name' => 'Belanja Kelulusan',
            'type' => 'pengeluaran',
            'description' => null,
        ]);
        \App\Models\TransactionCategory::create([
            'name' => 'Belanja Lain-lain',
            'type' => 'pengeluaran',
            'description' => null,
        ]);

        // month periode
        \App\Models\MonthPeriod::create([
            'no_order' => '1',
            'no_month' => '07',
            'name_month' => 'juli',
        ]);
        \App\Models\MonthPeriod::create([
            'no_order' => '2',
            'no_month' => '08',
            'name_month' => 'agustus',
        ]);
        \App\Models\MonthPeriod::create([
            'no_order' => '3',
            'no_month' => '09',
            'name_month' => 'september',
        ]);
        \App\Models\MonthPeriod::create([
            'no_order' => '4',
            'no_month' => '10',
            'name_month' => 'oktober',
        ]);
        \App\Models\MonthPeriod::create([
            'no_order' => '5',
            'no_month' => '11',
            'name_month' => 'november',
        ]);
        \App\Models\MonthPeriod::create([
            'no_order' => '6',
            'no_month' => '12',
            'name_month' => 'desember',
        ]);
        \App\Models\MonthPeriod::create([
            'no_order' => '7',
            'no_month' => '01',
            'name_month' => 'januari',
        ]);
        \App\Models\MonthPeriod::create([
            'no_order' => '8',
            'no_month' => '02',
            'name_month' => 'februari',
        ]);
        \App\Models\MonthPeriod::create([
            'no_order' => '9',
            'no_month' => '03',
            'name_month' => 'maret',
        ]);
        \App\Models\MonthPeriod::create([
            'no_order' => '10',
            'no_month' => '04',
            'name_month' => 'april',
        ]);
        \App\Models\MonthPeriod::create([
            'no_order' => '11',
            'no_month' => '05',
            'name_month' => 'mei',
        ]);
        \App\Models\MonthPeriod::create([
            'no_order' => '12',
            'no_month' => '06',
            'name_month' => 'juni',
        ]);

        // year periode
        \App\Models\YearPeriod::create([
            'start_date' => '2023-07-01',
            'end_date' => '2024-06-30',
            'name_year' => '2023/2024',
        ]);
        \App\Models\YearPeriod::create([
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
            'name_year' => '2024/2025',
            'is_active' => true,
        ]);

        \App\Models\Category::create([
            'name' => 'Barang Seragam',
            'description' => null,
        ]);
        \App\Models\Category::create([
            'name' => 'Buku Jurnal, Buku Bayar, Map Hasil Karya',
            'description' => null,
        ]);

    }
}
