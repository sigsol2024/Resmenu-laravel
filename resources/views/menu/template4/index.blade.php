@php
    $primaryColor = $customization['primary_color'] ?? '#f20d0d';
    $menuTitleColor = $customization['menu_title_color'] ?? '#121212';
    $priceColor = $customization['price_color'] ?? '#f20d0d';
    $priceSize = (int) ($customization['price_size'] ?? 18);
    $priceFont = $customization['price_font'] ?? 'Epilogue';
    $descColor = $customization['description_color'] ?? '#666666';
    $categoryTitleColor = $customization['category_title_color'] ?? '#ffffff';
    $bgColor = $customization['background_color'] ?? '#f8f5f5';
    $heroBgImage = '';
    if ($singleSectionView && !empty($sections[0]['image'])) {
        $heroBgImage = $uploadBaseUrl.'/sections/'.e($sections[0]['image']);
    } elseif (!empty($restaurant['hero_image'])) {
        $heroBgImage = $uploadBaseUrl.'/heroes/'.e($restaurant['hero_image']);
    } else {
        $heroBgImage = 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1600&h=900&fit=crop';
    }
    $activeCategories = [];
    foreach ($sections as $sec) {
        foreach ($sec['categories'] ?? [] as $cat) {
            if (!empty($cat['menu_items'])) {
                $activeCategories[] = $cat;
            }
        }
    }
@endphp
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $restaurant['name'] }}@if($singleSectionView && !empty($sections[0]['name'])) — {{ $sections[0]['name'] }}@else — Menu@endif</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<script>
tailwind.config = { darkMode: "class", theme: { extend: {
    colors: { primary: "{{ $primaryColor }}", "background-light": "{{ $bgColor }}", charcoal: "#121212" },
    fontFamily: { display: ["Epilogue", "sans-serif"] }
}}};
</script>
<style>
.food-pattern::before { content:''; position:fixed; inset:0; background-image:url('{{ $template4BaseUrl }}/bg_black.png'); background-repeat:repeat; background-size:280px; opacity:.1; pointer-events:none; z-index:0; }
#menuPanel { right:-288px; transition:right .3s ease; }
#menuPanel.open { right:0; }
</style>
</head>
<body class="font-display bg-background-light text-charcoal">
<header class="fixed top-0 w-full z-50 bg-gray-800/50 backdrop-blur-xl border-b border-white/10 px-6 py-4">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        @if(!empty($restaurant['logo']))
            <img src="{{ $uploadBaseUrl }}/logos/{{ $restaurant['logo'] }}" alt="{{ $restaurant['name'] }}" class="h-10 w-auto object-contain">
        @else
            <h1 class="text-white text-xl font-bold">{{ $restaurant['name'] }}</h1>
        @endif
        <button type="button" id="menuToggle" class="p-2 text-white/80 hover:text-white" aria-label="Open menu">☰</button>
    </div>
</header>

<div id="menuOverlay" class="fixed inset-0 bg-black/60 z-40 hidden" onclick="toggleMenu()"></div>
<div id="menuPanel" class="fixed top-0 w-72 h-full bg-gray-900/95 z-50 overflow-y-auto p-6 pt-20">
    <button type="button" id="menuClose" class="absolute top-4 right-4 text-white" onclick="toggleMenu()">✕</button>
    <nav class="flex flex-col gap-3">
        @if($singleSectionView)
            <a class="text-white font-medium" href="{{ $fullMenuUrl }}">Full menu</a>
        @endif
        @foreach($sectionsForNav as $navSection)
            <a class="text-white/80 hover:text-white" href="{{ $fullMenuUrl }}#section-{{ $navSection['slug'] ?? '' }}">{{ $navSection['name'] ?? '' }}</a>
        @endforeach
        @foreach($activeCategories as $cat)
            <a class="text-white/70 text-sm" href="#{{ $cat['slug'] }}-section">{{ $cat['name'] }}</a>
        @endforeach
    </nav>
</div>

<section class="relative min-h-[85vh] flex items-center justify-center bg-charcoal overflow-hidden pt-20">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('{{ $heroBgImage }}')"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-charcoal via-black/70 to-transparent opacity-90"></div>
    <div class="relative z-10 text-center max-w-4xl px-6">
        <h2 class="text-white text-5xl md:text-7xl font-serif font-black mb-6">{{ $restaurant['name'] }}</h2>
        @if(!empty($restaurant['description']))
            <p class="text-lg text-white/90 mb-8">{{ $restaurant['description'] }}</p>
        @endif
        <a href="#menu" class="inline-block bg-primary text-white font-bold px-10 py-4 rounded-xl">VIEW MENU</a>
    </div>
</section>

<main class="food-pattern relative min-h-screen" id="menu">
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-20">
        @if(empty($sections))
            <p class="text-center text-charcoal/60 py-20">No menu items available at the moment.</p>
        @else
            @foreach($sections as $section)
                @if(empty($section['categories'])) @continue @endif
                <div class="mb-16" id="section-{{ $section['slug'] }}">
                    <h2 class="text-center text-2xl font-serif font-black mb-10 text-charcoal">
                        @if(!$singleSectionView)
                            <a href="{{ $fullMenuUrl }}/{{ $section['slug'] }}" class="hover:underline">{{ $section['name'] }}</a>
                        @else
                            {{ $section['name'] }}
                        @endif
                    </h2>
                    @foreach($section['categories'] as $category)
                        @if(empty($category['menu_items'])) @continue @endif
                        <div class="mb-16" id="{{ $category['slug'] }}-section">
                            <h3 class="text-lg font-serif font-black bg-charcoal rounded-xl px-4 py-3 inline-block mb-6" style="color:{{ $categoryTitleColor }}">{{ $category['name'] }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach($category['menu_items'] as $item)
                                    @php $itemImage = !empty($item['image']) ? $uploadBaseUrl.'/menu-items/'.$item['image'] : ''; @endphp
                                    <div class="bg-white border border-charcoal/5 rounded-2xl overflow-hidden flex flex-col hover:shadow-lg">
                                        @if($itemImage)
                                            <div class="h-40 bg-cover bg-center" style="background-image:url('{{ $itemImage }}')"></div>
                                        @endif
                                        <div class="p-4">
                                            <h4 class="font-bold mb-1" style="color:{{ $menuTitleColor }}">{{ $item['name'] }}</h4>
                                            <span class="font-black text-lg" style="color:{{ $priceColor }};font-size:{{ $priceSize }}px;font-family:{{ $priceFont }},sans-serif">{{ \App\Support\PriceFormatter::format($item['price']) }}</span>
                                            @if(!empty($item['description']))
                                                <p class="text-sm mt-2 line-clamp-2" style="color:{{ $descColor }}">{{ $item['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
</main>

<footer class="bg-charcoal text-white py-12 text-center text-sm text-white/50">
    <p>{{ $restaurant['name'] }}</p>
</footer>

<script>
function toggleMenu() {
    document.getElementById('menuPanel').classList.toggle('open');
    document.getElementById('menuOverlay').classList.toggle('hidden');
}
document.getElementById('menuToggle')?.addEventListener('click', toggleMenu);
document.getElementById('menuClose')?.addEventListener('click', toggleMenu);
</script>
</body>
</html>
