@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$sizeClasses = [
    'sm' => 'modal-sm',
    'md' => '',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
    '2xl' => 'modal-xl',
];
$sizeClass = $sizeClasses[$maxWidth] ?? '';
@endphp

<div class="modal fade @if($show) show @endif" id="{{ $name }}" tabindex="-1" aria-labelledby="{{ $name }}Label" @if($show) style="display: block;" @endif>
    <div class="modal-dialog {{ $sizeClass }}">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('{{ $name }}');
    const modal = new bootstrap.Modal(modalElement);
    
    // Listen for open-modal events
    window.addEventListener('open-modal', function(event) {
        if (event.detail === '{{ $name }}') {
            modal.show();
        }
    });
    
    // Listen for close-modal events
    window.addEventListener('close-modal', function(event) {
        if (event.detail === '{{ $name }}') {
            modal.hide();
        }
    });
});
</script>
