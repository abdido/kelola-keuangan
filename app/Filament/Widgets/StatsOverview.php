<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? now()->startOfMonth()) ? Carbon::parse($this->filters['startDate']) : now()->startOfMonth();

        $endDate = ! is_null($this->filters['endDate'] ?? null) ? Carbon::parse($this->filters['endDate']) : now();

        $income = Transaction::incomes();
        $outcome = Transaction::expenses();

        if ($startDate && $endDate) {
            $income->whereBetween('date_transaction', [$startDate, $endDate]);
            $outcome->whereBetween('date_transaction', [$startDate, $endDate]);
        }

        
        $income = $income->sum('amount');
        $outcome = $outcome->sum('amount');

        // $income = Transaction::incomes()->get()->whereBetween('date', [$startDate, $endDate])->sum('amount');
        // $outcome = Transaction::expenses()->get()->whereBetween('date', [$endDate, $startDate])->sum('amount');

        return [
            Stat::make('Total Pemasukan', $income),
            Stat::make('Total Pengeluaran', $outcome),
            Stat::make('Selisih', $income - $outcome)
        ];
    }
}
