<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
    // Allow property to be nullable to avoid initialization error
    public ?Category $category = null;

    public function query(Category $category): array
    {
        $this->category = $category;

        return [
            'category' => $category,
        ];
    }

    public function name(): ?string
    {
        return $this->category?->exists ? 'Edit Category' : 'Create Category';
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
                ->canSee($this->category?->exists),
        ];
    }

    public function layout(): array
    {
        $imageHelp = $this->category?->image_url
            ? 'Current: ' . $this->category->image_url
            : 'Upload a category image (JPG/PNG/WebP). Max 3MB';

        return [
            // Current image display (only for existing categories)
            Layout::view('partials.current-image', [
                'image_url' => $this->category?->image_url,
                'image_path' => $this->category?->image_path,
                'title' => 'Current Image'
            ])->canSee($this->category?->exists && $this->category?->image_path),

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

                // Real image upload field
                Input::make('image')
                    ->type('file')
                    ->title('Image')
                    ->acceptedFiles('image/*')
                    ->help($imageHelp),

                Switcher::make('category.is_active')
                    ->title('Active')
                    ->sendTrueOrFalse()
                    ->value($this->category?->exists ? (bool)$this->category->is_active : true),

                Input::make('category.sort_order')
                    ->title('Order')
                    ->type('number')
                    ->value((int)($this->category->sort_order ?? 0)),
            ]),
        ];
    }

    public function createOrUpdate(Request $request, Category $category)
    {
        $imageService = app(ImageService::class);

        $validationRules = [
            'category.parent_id'   => ['nullable','exists:categories,id'],
            'category.name'        => ['required','string','max:255'],
            'category.slug'        => [
                'required','string','max:255',
                Rule::unique('categories','slug')->ignore($category->id),
            ],
            'category.description' => ['nullable','string'],
            'category.is_active'   => ['nullable','boolean'],
            'category.sort_order'  => ['nullable','integer'],
        ];

        // Add image validation rules
        $imageRules = $imageService->getValidationRules('image', false);
        $validationRules = array_merge($validationRules, $imageRules);

        $data = $request->validate($validationRules);

        // Secure slug
        if (blank($data['category']['slug'])) {
            $data['category']['slug'] = Str::slug($data['category']['name']);
        }

        // Prevent selecting itself as parent
        if (!empty($data['category']['parent_id']) && (int)$data['category']['parent_id'] === (int)$category->id) {
            return back()->withErrors(['category.parent_id' => 'Category cannot be a parent of itself.']);
        }

        $category->fill($data['category']);

        // Upload and store image in storage/app/public/categories
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $uploadOptions = $imageService->getUploadOptions('categories');

            $result = $imageService->upload($file, 'categories', $uploadOptions);

            if ($result['success']) {
                $category->image_path = $result['path'];
            } else {
                Toast::error('Image upload failed: ' . $result['error']);
                return back();
            }
        }

        $category->save();

        Toast::info('Saved.');
        return redirect()->route('platform.categories.list');
    }

    public function remove(Category $category)
    {
        try {
            // Image will be automatically deleted via model events
            $category->delete();

            Toast::info('Deleted.');
            return redirect()->route('platform.categories.list');
        } catch (\Throwable $e) {
            Toast::error('Cannot delete: ' . $e->getMessage());
            return back();
        }
    }
}
