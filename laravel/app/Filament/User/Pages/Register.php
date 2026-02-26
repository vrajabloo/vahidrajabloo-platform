<?php

namespace App\Filament\User\Pages;

use App\Models\User;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class Register extends BaseRegister
{
    protected function handleRegistration(array $data): Model
    {
        /** @var User $user */
        $user = parent::handleRegistration($data);

        // Prevent accidental auto-verification if DB default is misconfigured.
        $user->forceFill(['email_verified_at' => null])->save();

        return $user;
    }

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

    public function getSubheading(): string|HtmlString|null
    {
        return new HtmlString(
            '<a href="https://vahidrajabloo.com" class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">' .
            '← Back to Website' .
            '</a>'
        );
    }
}
