<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function mount(): void
    {
        if (empty($this->filters['period'])) {
            $this->setPeriodPreset('7D');
        }
    }

    public function setPeriodPreset(string $preset): void
    {
        $this->filters['period'] = $preset;
        $today = now()->format('Y-m-d');

        switch ($preset) {
            case '1D':
                $this->filters['startDate'] = $today;
                $this->filters['endDate'] = $today;
                break;
            case '7D':
                $this->filters['startDate'] = now()->subDays(6)->format('Y-m-d');
                $this->filters['endDate'] = $today;
                break;
            case '1M':
                $this->filters['startDate'] = now()->subMonth()->format('Y-m-d');
                $this->filters['endDate'] = $today;
                break;
            case '3M':
                $this->filters['startDate'] = now()->subMonths(3)->format('Y-m-d');
                $this->filters['endDate'] = $today;
                break;
            case 'YTD':
                $this->filters['startDate'] = now()->startOfYear()->format('Y-m-d');
                $this->filters['endDate'] = $today;
                break;
            case 'all':
                $this->filters['startDate'] = null;
                $this->filters['endDate'] = null;
                break;
            case 'custom':
                // keep custom inputs open
                break;
        }
    }

    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\WelcomeWidget::class,
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                View::make('filament.pages.dashboard.filter-toolbar')
                    ->columnSpanFull(),
            ]);
    }
}
