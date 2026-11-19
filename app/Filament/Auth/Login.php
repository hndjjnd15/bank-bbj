<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getHeading(): string|Htmlable|null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return parent::getHeading();
        }

        return 'Masuk ke Portal Admin';
    }

    public function getSubheading(): string|Htmlable|null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return parent::getSubheading();
        }

        return 'Gunakan akun yang diberikan administrator Bank BBJ.';
    }

    // Ikon di dalam kolom mengikuti desain halaman masuk.
    protected function getEmailFormComponent(): TextInput
    {
        /** @var TextInput $component */
        $component = parent::getEmailFormComponent();

        return $component->prefixIcon(Heroicon::OutlinedEnvelope);
    }

    protected function getPasswordFormComponent(): TextInput
    {
        /** @var TextInput $component */
        $component = parent::getPasswordFormComponent();

        return $component->prefixIcon(Heroicon::OutlinedLockClosed);
    }
}
