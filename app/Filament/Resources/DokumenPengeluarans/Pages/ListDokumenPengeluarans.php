<?php

namespace App\Filament\Resources\DokumenPengeluarans\Pages;

use App\Filament\Resources\DokumenPengeluarans\DokumenPengeluaranResource;
use App\Models\DokumenPengeluaran;
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

    public function getDefaultActiveTab(): string|int|null
    {
        $user = auth()->user();

        if ($user->hasRole('verifikator')) {
            return 'diajukan';
        }

        if ($user->hasRole('ppk')) {
            return 'diverifikasi';
        }

        if ($user->hasRole('bendahara')) {
            return 'disahkan';
        }

        return 'semua';
    }

    public function getTabs(): array
    {
        $baseQuery = fn () => DokumenPengeluaranResource::getEloquentQuery();

        return [
            'semua' => \Filament\Schemas\Components\Tabs\Tab::make('Semua')
                ->badge($baseQuery()->count()),
            'diajukan' => \Filament\Schemas\Components\Tabs\Tab::make('Diajukan')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', DokumenPengeluaran::STATUS_DIAJUKAN))
                ->badge($baseQuery()->where('status', DokumenPengeluaran::STATUS_DIAJUKAN)->count())
                ->badgeColor('warning'),
            'dikembalikan' => \Filament\Schemas\Components\Tabs\Tab::make('Dikembalikan')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', DokumenPengeluaran::STATUS_DIKEMBALIKAN))
                ->badge($baseQuery()->where('status', DokumenPengeluaran::STATUS_DIKEMBALIKAN)->count())
                ->badgeColor('danger'),
            'diverifikasi' => \Filament\Schemas\Components\Tabs\Tab::make('Diverifikasi')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', DokumenPengeluaran::STATUS_DIVERIFIKASI))
                ->badge($baseQuery()->where('status', DokumenPengeluaran::STATUS_DIVERIFIKASI)->count())
                ->badgeColor('info'),
            'disahkan' => \Filament\Schemas\Components\Tabs\Tab::make('Disahkan')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', DokumenPengeluaran::STATUS_DISAHKAN))
                ->badge($baseQuery()->where('status', DokumenPengeluaran::STATUS_DISAHKAN)->count())
                ->badgeColor('success'),
            'dibayar' => \Filament\Schemas\Components\Tabs\Tab::make('Dibayar')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', DokumenPengeluaran::STATUS_DIBAYAR))
                ->badge($baseQuery()->where('status', DokumenPengeluaran::STATUS_DIBAYAR)->count())
                ->badgeColor('success'),
            'diarsipkan' => \Filament\Schemas\Components\Tabs\Tab::make('Diarsipkan')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', DokumenPengeluaran::STATUS_DIARSIPKAN))
                ->badge($baseQuery()->where('status', DokumenPengeluaran::STATUS_DIARSIPKAN)->count())
                ->badgeColor('gray'),
        ];
    }
}

