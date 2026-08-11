<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class PanduanPage extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Panduan';
    protected static ?string $title = 'Panduan Penggunaan';
    protected static \UnitEnum|string|null $navigationGroup = 'Informasi & Bantuan';
    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.panduan-page';

    public string $activeTab = 'pemakaian';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function getHeading(): string
    {
        return '';
    }

    public static function canAccess(): bool
    {
        return true;
    }

    /**
     * Disable Filament's default page header so our custom Blade layout controls the header cleanly.
     */
    public function getHeader(): ?View
    {
        return null;
    }
}
