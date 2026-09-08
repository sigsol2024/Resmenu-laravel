{{-- Site favicon from admin settings (uploads/site/...). --}}
@if(! empty($siteFaviconUrl))
<link rel="icon" href="{{ $siteFaviconUrl }}"@if(! empty($siteFaviconType)) type="{{ $siteFaviconType }}"@endif>
<link rel="shortcut icon" href="{{ $siteFaviconUrl }}">
@endif
