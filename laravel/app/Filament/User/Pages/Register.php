<?php

namespace App\Filament\User\Pages;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getRoleFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->label('User Type')
            ->options([
                User::ROLE_DISABLED_USER => 'Disabled User',
                User::ROLE_FAMILY_USER => 'Family Disabled',
                User::ROLE_SUPPORTER_USER => 'Supporter',
            ])
            ->required()
            ->default(User::ROLE_DISABLED_USER);
    }
}
