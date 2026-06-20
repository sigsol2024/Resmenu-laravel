<?php

/**
 * Category icons for menu templates (ported from legacy includes/category-icons.php).
 */

if (! function_exists('resmenu_get_category_icon')) {
    /**
     * Return a category-appropriate icon (emoji) for section dividers or labels.
     *
     * @param  array<string, mixed>  $category
     */
    function resmenu_get_category_icon($category, $fallback = '✧'): string
    {
        $name = isset($category['name']) ? mb_strtolower(trim((string) $category['name']), 'UTF-8') : '';
        $slug = isset($category['slug']) ? mb_strtolower(trim((string) $category['slug']), 'UTF-8') : '';
        $text = $name.' '.$slug;

        $map = resmenu_get_category_icon_map();
        foreach ($map as $keyword => $icon) {
            if ($keyword !== '' && (mb_strpos($text, $keyword) !== false || mb_strpos($name, $keyword) !== false || mb_strpos($slug, $keyword) !== false)) {
                return $icon;
            }
        }

        return $fallback;
    }
}

if (! function_exists('resmenu_get_category_icon_map')) {
    /** @return array<string, string> */
    function resmenu_get_category_icon_map(): array
    {
        static $map = null;
        if ($map !== null) {
            return $map;
        }

        $map = [
            'ice cream' => '🍦', 'icecream' => '🍦', 'gelato' => '🍦', 'sorbet' => '🍦', 'frozen' => '🍦',
            'dessert' => '🍰', 'desserts' => '🍰', 'cake' => '🍰', 'cakes' => '🍰', 'sweet' => '🍰', 'sweets' => '🍰',
            'pastry' => '🍰', 'pastries' => '🍰', 'cookie' => '🍪', 'cookies' => '🍪', 'brownie' => '🍫', 'brownies' => '🍫',
            'pie' => '🥧', 'pies' => '🥧', 'tart' => '🥧', 'tarts' => '🥧', 'donut' => '🍩', 'donuts' => '🍩',
            'cupcake' => '🧁', 'cupcakes' => '🧁', 'chocolate' => '🍫', 'candy' => '🍬', 'pudding' => '🍮',
            'shisha' => '🌿', 'hookah' => '🌿',
            'champagne' => '🍾', 'wine' => '🍷', 'wines' => '🍷', 'vineyard' => '🍷',
            'cognac' => '🥃', 'whiskey' => '🥃', 'whisky' => '🥃', 'bourbon' => '🥃', 'tequila' => '🥃',
            'rum' => '🥃', 'vodka' => '🥃', 'gin' => '🥃', 'liqueur' => '🥃', 'spirits' => '🥃', 'liquor' => '🥃',
            'cocktail' => '🍸', 'cocktails' => '🍸', 'bar' => '🍸', 'drink' => '🍷', 'drinks' => '🍷', 'beverage' => '🍷', 'beverages' => '🍷',
            'mocktail' => '🍹', 'mocktails' => '🍹', 'happy hour' => '🍸', 'late night' => '🌙',
            'beer' => '🍺', 'beers' => '🍺', 'draft' => '🍺', 'craft' => '🍺',
            'coffee' => '☕', 'espresso' => '☕', 'cappuccino' => '☕', 'latte' => '☕', 'tea' => '🍵', 'teas' => '🍵',
            'hot chocolate' => '☕', 'cocoa' => '☕',
            'juice' => '🧃', 'juices' => '🧃', 'smoothie' => '🥤', 'smoothies' => '🥤', 'milkshake' => '🥤',
            'soda' => '🥤', 'soft drink' => '🥤', 'water' => '💧',
            'sake' => '🍶',
            'appetizer' => '🥗', 'appetizers' => '🥗', 'starter' => '🥗', 'starters' => '🥗',
            'salad' => '🥗', 'salads' => '🥗', 'tapas' => '🥗', 'small plate' => '🥗', 'sharing' => '🥗', 'share' => '🥗',
            'soup' => '🍲', 'soups' => '🍲', 'chowder' => '🍲', 'bisque' => '🍲',
            'main' => '🥩', 'mains' => '🥩', 'entree' => '🥩', 'entrees' => '🥩', 'grill' => '🥩', 'grilled' => '🥩',
            'steak' => '🥩', 'steaks' => '🥩', 'meat' => '🥩', 'bbq' => '🍖', 'barbecue' => '🍖', 'smoked' => '🍖',
            'seafood' => '🦐', 'fish' => '🐟', 'shrimp' => '🦐', 'prawn' => '🦐', 'crab' => '🦀', 'lobster' => '🦞',
            'oyster' => '🦪', 'oysters' => '🦪', 'sushi' => '🍣', 'sashimi' => '🍣',
            'side' => '🥔', 'sides' => '🥔', 'fry' => '🍟', 'fries' => '🍟', 'wing' => '🍗', 'wings' => '🍗',
            'breakfast' => '🍳', 'brunch' => '🍳', 'lunch' => '🍽️', 'dinner' => '🍽️', 'supper' => '🍽️',
            'pizza' => '🍕', 'pizzas' => '🍕', 'burger' => '🍔', 'burgers' => '🍔', 'pasta' => '🍝', 'pastas' => '🍝',
            'taco' => '🌮', 'tacos' => '🌮', 'mexican' => '🌮', 'noodle' => '🍜', 'noodles' => '🍜', 'ramen' => '🍜',
            'rice' => '🍚', 'curry' => '🍛', 'indian' => '🍛', 'thai' => '🍜', 'chinese' => '🥡', 'japanese' => '🍣', 'korean' => '🍱',
            'asian' => '🍜',
            'sandwich' => '🥪', 'sandwiches' => '🥪', 'wrap' => '🌯', 'wraps' => '🌯',
            'snack' => '🥜', 'snacks' => '🥜', 'dip' => '🥑', 'dips' => '🥑', 'bread' => '🥖', 'cheese' => '🧀',
            'charcuterie' => '🧀', 'board' => '🧀',
            'vegan' => '🌱', 'plant' => '🌱', 'vegetarian' => '🥬', 'healthy' => '🥗', 'organic' => '🌿', 'local' => '📍',
            'kids' => '👶', 'child' => '👶', 'children' => '👶', 'family' => '👨‍👩‍👧‍👦', 'senior' => '👴',
            'chef' => '👨‍🍳', 'special' => '⭐', 'specials' => '⭐', 'today' => '⭐', 'catch' => '🐟', 'fresh' => '🐟',
        ];

        return $map;
    }
}
