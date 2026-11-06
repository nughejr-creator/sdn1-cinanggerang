<?php

namespace App\Filament\Widgets;

use App\Models\HeadmasterProfile;
use App\Models\SchoolProfile;
use Filament\Widgets\Widget;

class AdminWelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.admin-welcome-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function getViewData(): array
    {
        $user = auth()->user();
        $headmaster = HeadmasterProfile::where('is_active', true)->first() ?? HeadmasterProfile::first();
        $school = SchoolProfile::first();

        return [
            'user' => $user,
            'headmaster' => $headmaster,
            'school' => $school,
        ];
    }
}
