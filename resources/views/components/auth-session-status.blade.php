@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success py-2 mb-4']) }}>
        {{ $status }}
    </div>
@endif
