<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Filament widgets are components that display information on your
 * dashboard, especially statistics. Widgets are typically added to the
 * default Dashboard of the panel, but you can add them to any page,
 * including resource pages. Filament includes built-in widgets like the
 * stats widget, to render important statistics in a simple overview;
 * chart widget, which can render an interactive chart; and table widget,
 * which allows you to easily embed the Table Builder.
 * Let's add a stats widget to our default dashboard page that includes a
 * stat for each type of patient and a chart to visualize treatments
 * administered over time.
 */
class PatientTypeOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Cats', Patient::query()->where('type', 'cat')->count()),
            Stat::make('Dogs', Patient::query()->where('type', 'dog')->count()),
            Stat::make('Rabbits', Patient::query()->where('type', 'rabbit')->count()),
        ];
    }
}
