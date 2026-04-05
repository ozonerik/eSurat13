<x-filament-widgets::widget>
    <x-filament::section
        collapsible
        collapsed>
        <x-slot name="heading">
            {{ $this->getAppName() }} v{{ $this->getAppVersion() }}
        </x-slot>
        <x-slot name="description">
            Changelog dan informasi aplikasi
        </x-slot>
        <div class="space-y-6">
            <!-- Changelog -->
            <div>
                <div class="space-y-3">
                    @foreach ($this->getChangelog() as $log)
                    <div class="border-l-4 border-amber-400 pl-3 py-2">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                v{{ $log['version'] }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $log['date'] }}
                            </span>
                        </div>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                            @foreach ($log['changes'] as $change)
                            <li class="flex items-start gap-2">
                                <span class="text-amber-400 mt-0.5">•</span>
                                <span>{{ $change }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Footer Info -->
            <div class="border-t pt-3 text-xs text-gray-500 dark:text-gray-400">
                <p>Dikembangkan oleh: <span class="font-medium">ozonerik IT Solutions</span></p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>