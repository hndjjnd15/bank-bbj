<x-filament-widgets::widget>
    <x-filament::section
        heading="Aktivitas Terbaru"
        description="Kotak masuk dan lelang terbaru."
    >
        <div class="dashboard-activities">
            @forelse ($activities as $activity)
                <a class="dashboard-activity" href="{{ $activity['url'] }}">
                    <span class="dashboard-activity-indicator dashboard-activity-indicator--{{ $activity['tone'] }}"></span>
                    <span class="dashboard-activity-content">
                        <span class="dashboard-activity-title">{{ $activity['title'] }}</span>
                        <span class="dashboard-activity-meta">
                            {{ $activity['label'] }} · {{ $activity['date']?->format('d M Y, H:i') }}
                        </span>
                    </span>
                </a>
            @empty
                <p class="dashboard-activities-empty">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
