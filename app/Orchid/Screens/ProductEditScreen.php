<?php

namespace App\Orchid\Screens;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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
    public $product;

    public function query(Product $product): array
    {
        $this->product = $product->load('images','category','brand');

        return [
            'product'   => $this->product,
            'images'    => $this->product->exists ? $this->product->images()->orderBy('sort_order')->get() : collect(),
        ];
    }

    public function name(): ?string
    {
        return $this->product && $this->product->exists ? 'Edit Product' : 'Create Product';
    }

    public function commandBar(): array
    {
        return [
            Button::make('Save')->icon('bs.check')->method('createOrUpdate'),
            Button::make('Remove')
                ->icon('bs.trash')
                ->confirm('Delete this product?')
                ->method('remove')
                ->canSee($this->product && $this->product->exists),
        ];
    }

    public function layout(): array
    {
        return [
            // تفاصيل المنتج
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

                    Input::make('product.name')->title('Name')->required(),
                    Input::make('product.slug')->title('Slug')->help('Unique URL identifier')->required(),
                    Input::make('product.sku')->title('SKU')->required(),

                    Input::make('product.price')->title('Price')->type('number')->step('0.01')->required(),
                    Input::make('product.sale_price')->title('Sale Price')->type('number')->step('0.01'),

                    Input::make('product.stock_qty')->title('Stock Qty')->type('number')->value(0),

                    Switcher::make('product.is_featured')->title('Featured')->sendTrueOrFalse()->value(false),
                    Switcher::make('product.is_active')->title('Active')->sendTrueOrFalse()->value(true),

                    DateTimer::make('product.published_at')->title('Published At')->allowInput(),
                ])->title('Details'),

                Layout::rows([
                    TextArea::make('product.short_description')->title('Short Description')->rows(3),
                    TextArea::make('product.description')->title('Description')->rows(6),
                ])->title('Descriptions'),
            ]),

            // إدارة الصور الحالية
            Layout::table('images', [
                TD::make('path','Image Path'),
                TD::make('alt','Alt'),
                TD::make('is_primary','Primary')->render(fn(ProductImage $img)=>$img->is_primary ? 'Yes':'No')->align(TD::ALIGN_CENTER),
                TD::make('sort_order','Order'),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn(ProductImage $img) =>
                        Button::make('Delete')
                            ->icon('bs.trash')
                            ->confirm('Delete this image?')
                            ->method('deleteImage', ['id'=>$img->id])
                    ),
            ])->title('Images'),

            // إضافة صورة جديدة بسرعة
            Layout::rows([
                Input::make('new_image.path')->title('Image Path')->placeholder('products/sample.jpg')->required(),
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
            'product.category_id'   => ['required','exists:categories,id'],
            'product.brand_id'      => ['required','exists:brands,id'],
            'product.name'          => ['required','string','max:255'],
            'product.slug'          => ['required','string','max:255', Rule::unique('products','slug')->ignore($product->id)],
            'product.sku'           => ['required','string','max:255'],
            'product.price'         => ['required','numeric','min:0'],
            'product.sale_price'    => ['nullable','numeric','min:0','lte:product.price'],
            'product.stock_qty'     => ['nullable','integer','min:0'],
            'product.is_featured'   => ['boolean'],
            'product.is_active'     => ['boolean'],
            'product.published_at'  => ['nullable','date'],
            'product.short_description' => ['nullable','string'],
            'product.description'       => ['nullable','string'],
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
            $product->images()->delete();
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
        $this->validate($request, [
            'new_image.path'       => ['required','string','max:255'],
            'new_image.alt'        => ['nullable','string','max:255'],
            'new_image.is_primary' => ['boolean'],
            'new_image.sort_order' => ['nullable','integer'],
        ]);

        // إذا اخترت primary اجعل الباقي false
        if ($request->boolean('new_image.is_primary')) {
            $product->images()->update(['is_primary' => false]);
        }

        ProductImage::create([
            'product_id' => $product->id,
            'path'       => $request->input('new_image.path'),
            'alt'        => $request->input('new_image.alt'),
            'is_primary' => $request->boolean('new_image.is_primary'),
            'sort_order' => (int) $request->input('new_image.sort_order', 0),
        ]);

        Toast::info('Image added.');
        return back();
    }

    public function deleteImage(Request $request)
    {
        $id = $request->get('id');
        $img = $id ? ProductImage::find($id) : null;

        if (! $img) {
            Toast::warning('Image not found.');
            return back();
        }

        $img->delete();
        Toast::info('Image deleted.');
        return back();
    }
}
