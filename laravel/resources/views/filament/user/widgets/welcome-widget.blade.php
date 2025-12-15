<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-4">
            <div class="flex-shrink-0">
                <div class="w-16 h-16 rounded-full bg-primary-500 flex items-center justify-center text-white text-2xl font-bold">
                    {{ substr($user->name, 0, 1) }}
                </div>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                    Welcome, {{ $user->name }}! 👋
                </h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    @if($user->isPremium())
                        <span class="inline-flex items-center gap-1 text-amber-600">
                            <x-heroicon-s-star class="w-4 h-4" />
                            Premium Member
                        </span>
                    @else
                        Regular Member
                    @endif
                </p>
                <p class="text-sm text-gray-400 mt-2">
                    {{ $user->email }}
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
