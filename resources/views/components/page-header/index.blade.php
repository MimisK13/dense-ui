@props(['title' => null])

<div {{ $attributes->class('page-header') }}>
    <div>
        @isset($breadcrumbs)
            <div class="breadcrumb">{{ $breadcrumbs }}</div>
        @endisset
        <div class="flex items-center gap-3 @isset($breadcrumbs) mt-1.5 @endisset">
            @isset($icon)
                <div class="page-icon shrink-0">{{ $icon }}</div>
            @endisset
            <div>
                @if($title)
                    <div class="page-title">{{ $title }}</div>
                @endif
                @isset($meta)
                    <div class="page-meta">{{ $meta }}</div>
                @endisset
            </div>
        </div>
    </div>
    @isset($actions)
        <div class="page-actions">{{ $actions }}</div>
    @endisset
</div>
