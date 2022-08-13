<?php

namespace App\Models\Concerns\Filters;

use Illuminate\Database\Eloquent\Builder;

trait ItemFilter
{
    public function scopeFilter(Builder $query, array $data): Builder
    {
        $search = $data['search'] ?? null;
        $category = $data['category'] ?? null;

        return $query->when(
            $search,
            fn ($query) => $query->where(
                fn ($query) => $query->nameFilter($search, 'or')
                    ->descriptionFilter($search, 'or')
                    ->brandFilter($search, 'or')
        )
        )->when($category, fn ($query, $category) => $query->where('category_id', $category))->enabled();
    }

    public function scopeNameFilter(Builder $query, ?string $search, ?string $boolean = 'and'): Builder
    {
        return $query->where('name', 'like', "%$search%", $boolean);
    }

    public function scopeDescriptionFilter(Builder $query, ?string $search, ?string $boolean = 'and'): Builder
    {
        return $query->where('description', 'like', "%$search%", $boolean);
    }

    public function scopeBrandFilter(Builder $query, ?string $search, ?string $boolean = 'and'): Builder
    {
        return $query->where('brand', 'like', "%$search%", $boolean);
    }

    public function scopeCategoryFilter(Builder $query, ?string $category): Builder
    {
        return $query->with('category', fn ($query) => $query->where('slug', $category));
    }

    public function scopeMoreVisited(Builder $query): Builder
    {
        return $query->withCount('rating_items')
            ->orderByDesc('rating_items_count');
    }
}
