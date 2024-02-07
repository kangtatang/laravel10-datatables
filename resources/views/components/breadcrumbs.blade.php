<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        @foreach($breadcrumbs as $breadcrumb)
        <li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active' : '' }}">
            @if (!$breadcrumb['active'])
            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
            @else
            {{ $breadcrumb['label'] }}
            @endif
        </li>
        @endforeach
    </ol>
</nav>
