<?php

namespace App\Filament\Resources\DokumenPengeluarans\Pages;

use App\Filament\Resources\DokumenPengeluarans\DokumenPengeluaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDokumenPengeluarans extends ListRecords
{
    protected static string $resource = DokumenPengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn () => auth()->user()->hasRole('pptk')),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => \Filament\Schemas\Components\Tabs\Tab::make('Semua')
                ->badge(\App\Models\DokumenPengeluaran::count()),
            'diajukan' => \Filament\Schemas\Components\Tabs\Tab::make('Diajukan')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', 'diajukan'))
                ->badge(\App\Models\DokumenPengeluaran::where('status', 'diajukan')->count())
                ->badgeColor('warning'),
            'proses_verifikasi' => \Filament\Schemas\Components\Tabs\Tab::make('Proses Verifikasi/Revisi')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->whereIn('status', ['lolos_verifikasi', 'revisi']))
                ->badge(\App\Models\DokumenPengeluaran::whereIn('status', ['lolos_verifikasi', 'revisi'])->count())
                ->badgeColor('info'),
            'sah' => \Filament\Schemas\Components\Tabs\Tab::make('Disahkan (PPK)')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', 'sah'))
                ->badge(\App\Models\DokumenPengeluaran::where('status', 'sah')->count())
                ->badgeColor('success'),
            'cair' => \Filament\Schemas\Components\Tabs\Tab::make('Selesai')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', 'cair'))
                ->badge(\App\Models\DokumenPengeluaran::where('status', 'cair')->count())
                ->badgeColor('success'),
        ];
    }
}
