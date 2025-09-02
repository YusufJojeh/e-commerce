<?php

namespace App\Orchid\Screens;

use App\Models\Brand;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Support\Facades\Toast;

class BrandEditScreen extends Screen
{
    public ?Brand $brand = null;

    public function query(Brand $brand): array
    {
        $this->brand = $brand;

        return [
            'brand' => $brand,
        ];
    }

    public function name(): ?string
    {
        return $this->brand?->exists ? 'Edit Brand' : 'Create Brand';
    }

    public function commandBar(): array
    {
        return [
            Button::make('Save')->icon('bs.check')->method('createOrUpdate'),
            Button::make('Remove')
                ->icon('bs.trash')
                ->confirm('Delete this brand?')
                ->method('remove')
                ->canSee($this->brand?->exists),
        ];
    }

    public function layout(): array
    {
        $logoHelp = $this->brand?->logo_url
            ? 'Current: ' . $this->brand->logo_url
            : 'Upload a brand logo (JPG/PNG/WebP). Max 3MB';

        return [
            // Current logo display (only for existing brands)
            Layout::view('partials.current-image', [
                'image_url' => $this->brand?->logo_url,
                'image_path' => $this->brand?->logo_path,
                'title' => 'Current Logo'
            ])->canSee($this->brand?->exists && $this->brand?->logo_path),

            Layout::rows([
                Input::make('brand.name')
                    ->title('Name')
                    ->required(),

                Input::make('brand.slug')
                    ->title('Slug')
                    ->help('Unique URL identifier')
                    ->required(),

                // Logo upload (real file, not URL)
                Input::make('logo')
                    ->type('file')
                    ->title('Logo')
                    ->acceptedFiles('image/*')
                    ->help($logoHelp),

                Switcher::make('brand.is_external')
                    ->title('External brand')
                    ->sendTrueOrFalse()
                    ->value($this->brand?->exists ? (bool)$this->brand->is_external : false),

                Switcher::make('brand.is_active')
                    ->title('Active')
                    ->sendTrueOrFalse()
                    ->value($this->brand?->exists ? (bool)$this->brand->is_active : true),

                Input::make('brand.sort_order')
                    ->title('Order')
                    ->type('number')
                    ->value($this->brand?->sort_order ?? 0),
            ]),
        ];
    }

    public function createOrUpdate(Request $request, Brand $brand)
    {
        $imageService = app(ImageService::class);

        $validationRules = [
            'brand.name'        => ['required','string','max:255'],
            'brand.slug'        => ['required','string','max:255', Rule::unique('brands','slug')->ignore($brand->id)],
            'brand.is_external' => ['nullable','boolean'],
            'brand.is_active'   => ['nullable','boolean'],
            'brand.sort_order'  => ['nullable','integer'],
        ];

        // Add logo validation rules
        $logoRules = $imageService->getValidationRules('logo', false);
        $validationRules = array_merge($validationRules, $logoRules);

        $data = $request->validate($validationRules);

        if (blank($data['brand']['slug'])) {
            $data['brand']['slug'] = Str::slug($data['brand']['name']);
        }

        $brand->fill($data['brand']);

        // Store logo in public/brands and delete old one
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $uploadOptions = $imageService->getUploadOptions('brands');

            $result = $imageService->upload($file, 'brands', $uploadOptions);

            if ($result['success']) {
                $brand->logo_path = $result['path'];
            } else {
                Toast::error('Logo upload failed: ' . $result['error']);
                return back();
            }
        }

        $brand->save();

        Toast::info('Saved.');
        return redirect()->route('platform.brands.list');
    }

    public function remove(Brand $brand)
    {
        try {
            // Logo will be automatically deleted via model events
            $brand->delete();

            Toast::info('Deleted.');
            return redirect()->route('platform.brands.list');
        } catch (\Throwable $e) {
            Toast::error('Cannot delete: ' . $e->getMessage());
            return back();
        }
    }
}
