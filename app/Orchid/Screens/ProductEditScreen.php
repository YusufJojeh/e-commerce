<?php

namespace App\Orchid\Screens;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class ProductEditScreen extends Screen
{
    // Allow property to be nullable with default value null
    public ?Product $product = null;

    public function query(Product $product): array
    {
        $this->product = $product->load('images', 'category', 'brand');

        return [
            'product' => $this->product,
            'images'  => $this->product->exists
                ? $this->product->images()->orderBy('sort_order')->get()
                : collect(),
        ];
    }

    public function name(): ?string
    {
        return $this->product?->exists ? 'Edit Product' : 'Create Product';
    }

    public function commandBar(): array
    {
        return [
            Button::make('Save')->icon('bs.check')->method('createOrUpdate'),
            Button::make('Remove')
                ->icon('bs.trash')
                ->confirm('Delete this product?')
                ->method('remove')
                ->canSee($this->product?->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::columns([
                Layout::rows([
                    Select::make('product.category_id')
                        ->title('Category')
                        ->fromModel(Category::class, 'name', 'id')
                        ->required(),

                    Select::make('product.brand_id')
                        ->title('Brand')
                        ->fromModel(Brand::class, 'name', 'id')
                        ->required(),

                    Input::make('product.name')
                        ->title('Name')
                        ->required(),

                    Input::make('product.slug')
                        ->title('Slug')
                        ->help('Unique URL identifier')
                        ->required(),

                    Input::make('product.sku')
                        ->title('SKU')
                        ->required(),

                    Input::make('product.price')
                        ->title('Price')
                        ->type('number')
                        ->step('0.01')
                        ->required(),

                    Input::make('product.sale_price')
                        ->title('Sale Price')
                        ->type('number')
                        ->step('0.01')
                        ->help('Leave empty if no sale'),

                    Input::make('product.stock_qty')
                        ->title('Stock Quantity')
                        ->type('number')
                        ->help('Leave empty for unlimited'),

                    Switcher::make('product.is_featured')
                        ->title('Featured')
                        ->sendTrueOrFalse(),

                    Switcher::make('product.is_active')
                        ->title('Active')
                        ->sendTrueOrFalse(),

                    DateTimer::make('product.published_at')
                        ->title('Published At')
                        ->allowInput(),
                ])->title('Basic Information'),

                Layout::rows([
                    TextArea::make('product.short_description')
                        ->title('Short Description')
                        ->rows(3),

                    TextArea::make('product.description')
                        ->title('Description')
                        ->rows(10),
                ])->title('Description'),
            ]),

            // Images table
            Layout::table('images', [
                TD::make('path', 'Preview')->render(function (ProductImage $img) {
                    $url = $img->url;
                    if ($url) {
                        return '<div class="d-flex flex-column align-items-center">
                                    <img src="'.$url.'" alt="'.e($img->alt ?? '').'" style="height:80px;width:80px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;">
                                    <small class="text-muted mt-1">'.e($img->path).'</small>
                                </div>';
                    }
                    return '<span class="text-muted">No image</span>';
                })->width('150')->align(TD::ALIGN_CENTER),

                TD::make('alt','Alt')->width('220')->render(fn(ProductImage $img) => e($img->alt ?? '—')),

                TD::make('is_primary','Primary')
                    ->render(fn(ProductImage $img)=> $img->is_primary ? 'Yes' : 'No')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100'),

                TD::make('sort_order','Order')->width('90'),

                TD::make(__('Actions'))
                    ->align(TD::ALIGN_RIGHT)
                    ->width('240')
                    ->render(function (ProductImage $img) {
                        $buttons = [];
                        if (! $img->is_primary) {
                            $buttons[] = Button::make('Set Primary')
                                ->icon('bs.star')
                                ->method('makePrimary', ['id' => $img->id]);
                        }

                        $buttons[] = Button::make('Delete')
                            ->icon('bs.trash')
                            ->confirm('Delete this image?')
                            ->method('deleteImage', ['id' => $img->id]);

                        return implode(' ', array_map(fn($b) => $b->render(), $buttons));
                    }),
            ])->title('Images'),

            // Add new image (file upload)
            Layout::rows([
                Input::make('new_image.file')
                    ->type('file')
                    ->title('Image File')
                    ->acceptedFiles('image/*')
                    ->required()
                    ->help('Upload image (JPG/PNG/WebP). Max 5MB'),

                Input::make('new_image.alt')->title('Alt (optional)'),
                Switcher::make('new_image.is_primary')->title('Primary?')->sendTrueOrFalse(),
                Input::make('new_image.sort_order')->title('Order')->type('number')->value(0),

                Button::make('Add Image')->icon('bs.plus')->method('addImage'),
            ])->title('Add New Image'),
        ];
    }

    public function createOrUpdate(Request $request, Product $product)
    {
        $data = $request->validate([
            'product.category_id'        => ['required','exists:categories,id'],
            'product.brand_id'           => ['required','exists:brands,id'],
            'product.name'               => ['required','string','max:255'],
            'product.slug'               => ['required','string','max:255', Rule::unique('products','slug')->ignore($product->id)],
            'product.sku'                => ['required','string','max:255'],
            'product.price'              => ['required','numeric','min:0'],
            'product.sale_price'         => ['nullable','numeric','min:0','lte:product.price'],
            'product.stock_qty'          => ['nullable','integer','min:0'],
            'product.is_featured'        => ['nullable','boolean'],
            'product.is_active'          => ['nullable','boolean'],
            'product.published_at'       => ['nullable','date'],
            'product.short_description'  => ['nullable','string'],
            'product.description'        => ['nullable','string'],
        ]);

        if (blank($data['product']['slug'])) {
            $data['product']['slug'] = Str::slug($data['product']['name']);
        }

        $product->fill($data['product'])->save();

        Toast::info('Saved.');
        return redirect()->route('platform.products.edit', $product);
    }

    public function remove(Product $product)
    {
        try {
            // Images will be automatically deleted via model events
            $product->delete();

            Toast::info('Deleted.');
            return redirect()->route('platform.products.list');
        } catch (\Throwable $e) {
            Toast::error('Cannot delete: '.$e->getMessage());
            return back();
        }
    }

    public function addImage(Request $request, Product $product)
    {
        $imageService = app(ImageService::class);
        $validationRules = $imageService->getValidationRules('new_image.file', true);
        $validationRules['new_image.alt'] = ['nullable','string','max:255'];
        $validationRules['new_image.is_primary'] = ['nullable','boolean'];
        $validationRules['new_image.sort_order'] = ['nullable','integer'];

        $validated = $request->validate($validationRules);

        $file = $request->file('new_image.file');
        $uploadOptions = $imageService->getUploadOptions('products');
        
        $result = $imageService->upload($file, 'products', $uploadOptions);

        if (!$result['success']) {
            Toast::error('Image upload failed: ' . $result['error']);
            return back();
        }

        if ($request->boolean('new_image.is_primary')) {
            $product->images()->update(['is_primary' => false]);
        }

        ProductImage::create([
            'product_id' => $product->id,
            'path'       => $result['path'],
            'alt'        => $validated['new_image']['alt'] ?? null,
            'is_primary' => $request->boolean('new_image.is_primary'),
            'sort_order' => (int) ($validated['new_image']['sort_order'] ?? 0),
        ]);

        Toast::info('Image added.');
        return back();
    }

    public function deleteImage(Request $request)
    {
        $id = $request->get('id');
        $image = ProductImage::findOrFail($id);

        try {
            $image->delete(); // This will automatically delete the file via model events
            Toast::info('Image deleted.');
        } catch (\Throwable $e) {
            Toast::error('Cannot delete image: ' . $e->getMessage());
        }

        return back();
    }

    public function makePrimary(Request $request)
    {
        $id = $request->get('id');
        $image = ProductImage::findOrFail($id);

        try {
            // Remove primary from all other images of this product
            $image->product->images()->update(['is_primary' => false]);
            
            // Set this image as primary
            $image->update(['is_primary' => true]);

            Toast::info('Primary image updated.');
        } catch (\Throwable $e) {
            Toast::error('Cannot update primary image: ' . $e->getMessage());
        }

        return back();
    }
}
