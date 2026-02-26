<?php

namespace App\Filament\User\Pages;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class Register extends BaseRegister
{
    protected int $maxRegistrationAttempts = 3;

    protected int $registrationDecaySeconds = 600;

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit($this->maxRegistrationAttempts, $this->registrationDecaySeconds);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function (): Model {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }

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
