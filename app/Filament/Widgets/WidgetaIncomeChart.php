<?php


namespace App\Filament\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
// use Carbon\Carbon;

class WidgetaIncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Pemasukan';
    protected static string $color = 'success';

    use InteractsWithPageFilters;

    protected function getData(): array
    {

        $startDate = Carbon::parse($this->filters['startDate'] ?? now()->firstOfMonth());
        $endDate = Carbon::parse($this->filters['endDate'] ?? now());

        \Log::info('Start Date: ', ['date' => $startDate]);
        \Log::info('End Date: ', ['date' => $endDate]);
        \Log::info('Incomes Query: ', ['query' => Transaction::incomes()->toSql(), 'bindings' => Transaction::incomes()->getBindings()]);

        $data = Trend::query(Transaction::incomes())
            ->between(
                start: $startDate,
                end: $endDate
            )
            ->perMonth()
            ->sum('amount');
        dd($data);
    return [
        'datasets' => [
            [
                'label' => 'Pemasukan',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate)->toArray(),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date)->toArray(),
    ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
