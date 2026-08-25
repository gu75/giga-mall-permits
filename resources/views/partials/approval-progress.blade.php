@props(['steps' => [], 'currentStep' => 0, 'status' => 'In progress'])

@php
    $steps = is_array($steps) ? $steps : [];
    $currentStep = max(0, min((int) $currentStep, max(count($steps) - 1, 0)));
@endphp

<div class="mt-4">
    <div class="mb-2 flex items-center justify-between text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-500">
        <span>Status</span>
        <span>{{ $status }}</span>
    </div>

    <div class="flex items-center gap-2">
        @foreach ($steps as $index => $step)
            @php
                $isDone = $index < $currentStep;
                $isCurrent = $index === $currentStep;
            @endphp

            @if ($index > 0)
                <div class="h-1 flex-1 rounded-full {{ $index <= $currentStep ? 'bg-[#0A2342]' : 'bg-[#E2E8F0]' }}"></div>
            @endif

            <div class="flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-full border text-[10px] font-bold
                    {{ $isDone || $isCurrent ? 'border-[#0A2342] bg-[#0A2342] text-white' : 'border-[#CBD5E1] bg-white text-[#64748B]' }}">
                    {{ $index + 1 }}
                </div>
                <span class="hidden text-[11px] font-medium text-gray-600 md:inline">{{ $step }}</span>
            </div>
        @endforeach
    </div>
</div>
