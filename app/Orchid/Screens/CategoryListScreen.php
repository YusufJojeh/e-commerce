<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;

class CategoryListScreen extends Screen
{
    public function name(): ?string
    {
        return 'Categories';
    }

    public function description(): ?string
    {
        return 'Browse and manage categories';
    }

    public function query(): array
    {
        return [
            'categories' => Category::with('parent')
                ->orderBy('sort_order')
                ->paginate(20),
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Create')
                ->icon('bs.plus')
                ->route('platform.categories.create'),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('categories', [
                TD::make('name')->sort()->filter(TD::FILTER_TEXT),
                TD::make('parent.name','Parent')->sort(),
                TD::make('is_active','Active')->render(fn(Category $c) => $c->is_active ? 'Yes' : 'No')->align(TD::ALIGN_CENTER),
                TD::make('sort_order','Order')->sort(),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (Category $c) =>
                        Link::make('Edit')->icon('bs.pencil')->route('platform.categories.edit', $c)
                    ),
            ]),
        ];
    }
}
