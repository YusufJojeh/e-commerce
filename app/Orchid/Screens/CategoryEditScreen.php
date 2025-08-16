<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Support\Facades\Toast;

class CategoryEditScreen extends Screen
{
    public $category;

    public function query(Category $category): array
    {
        $this->category = $category;

        return [
            'category' => $category,
        ];
    }

    public function name(): ?string
    {
        return $this->category && $this->category->exists
            ? 'Edit Category'
            : 'Create Category';
    }

    public function commandBar(): array
    {
        return [
            Button::make('Save')
                ->icon('bs.check')
                ->method('createOrUpdate'),

            Button::make('Remove')
                ->icon('bs.trash')
                ->confirm('Delete this category?')
                ->method('remove')
                ->canSee($this->category && $this->category->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Select::make('category.parent_id')
                    ->title('Parent')
                    ->empty('— None —')
                    ->fromModel(Category::class, 'name', 'id')
                    ->help('Optional parent category'),

                Input::make('category.name')
                    ->title('Name')
                    ->required(),

                Input::make('category.slug')
                    ->title('Slug')
                    ->help('Unique URL identifier')
                    ->required(),

                TextArea::make('category.description')
                    ->title('Description')
                    ->rows(3),

                Switcher::make('category.is_active')
                    ->title('Active')
                    ->sendTrueOrFalse()
                    ->value(true),

                Input::make('category.sort_order')
                    ->title('Order')
                    ->type('number')
                    ->value(0),
            ]),
        ];
    }

    public function createOrUpdate(Request $request, Category $category)
    {
        $data = $request->validate([
            'category.parent_id'   => ['nullable','exists:categories,id'],
            'category.name'        => ['required','string','max:255'],
            'category.slug'        => [
                'required','string','max:255',
                Rule::unique('categories','slug')->ignore($category->id),
            ],
            'category.description' => ['nullable','string'],
            'category.is_active'   => ['boolean'],
            'category.sort_order'  => ['nullable','integer'],
        ]);

        // Generate slug if left blank (safety)
        if (blank($data['category']['slug'])) {
            $data['category']['slug'] = Str::slug($data['category']['name']);
        }

        $category->fill($data['category'])->save();

        Toast::info('Saved.');
        return redirect()->route('platform.categories.list');
    }

    public function remove(Category $category)
    {
        $category->delete();
        Toast::info('Deleted.');
        return redirect()->route('platform.categories.list');
    }
}
