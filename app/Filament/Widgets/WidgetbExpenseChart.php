<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Transaction;
use Illuminate\Support\Carbon;


class WidgetbExpenseChart extends ChartWidget
{
    protected static ?string $heading = 'Pengeluaran';
    protected static string $color = 'warning';

    use InteractsWithPageFilters;

    protected function getData(): array
    {

        $startDate = ! is_null($this->filters['startDate'] ?? null) ? Carbon::parse($this->filters['startDate']) : null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ? Carbon::parse($this->filters['endDate']) : now();

        $data = Trend::query(Transaction::expenses())
            ->between(
                start: $startDate,
                end: $endDate,
            )
            ->perMonth()
            ->sum('amount');
            // dd($data->toArray());

            return [
                'datasets' => [
                    [
                        'label' => 'Pengeluaran',
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
