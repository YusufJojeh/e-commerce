<?php

namespace App\Orchid\Screens;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Support\Facades\Toast;

class BrandEditScreen extends Screen
{
    public $brand;

    public function query(Brand $brand): array
    {
        $this->brand = $brand;
        return ['brand' => $brand];
    }

    public function name(): ?string
    {
        return $this->brand && $this->brand->exists ? 'Edit Brand' : 'Create Brand';
    }

    public function commandBar(): array
    {
        return [
            Button::make('Save')->icon('bs.check')->method('createOrUpdate'),
            Button::make('Remove')
                ->icon('bs.trash')
                ->confirm('Delete this brand?')
                ->method('remove')
                ->canSee($this->brand && $this->brand->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('brand.name')->title('Name')->required(),

                Input::make('brand.slug')
                    ->title('Slug')
                    ->help('Unique URL identifier')
                    ->required(),

                // keep it simple for now: just a path under storage/public/logos
                Input::make('brand.logo_path')
                    ->title('Logo Path')
                    ->placeholder('logos/sample.png'),

                Switcher::make('brand.is_external')
                    ->title('External brand')
                    ->sendTrueOrFalse()
                    ->value(false),

                Switcher::make('brand.is_active')
                    ->title('Active')
                    ->sendTrueOrFalse()
                    ->value(true),

                Input::make('brand.sort_order')
                    ->title('Order')
                    ->type('number')
                    ->value(0),
            ]),
        ];
    }

    public function createOrUpdate(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'brand.name'       => ['required','string','max:255'],
            'brand.slug'       => ['required','string','max:255', Rule::unique('brands','slug')->ignore($brand->id)],
            'brand.logo_path'  => ['nullable','string','max:255'],
            'brand.is_external'=> ['boolean'],
            'brand.is_active'  => ['boolean'],
            'brand.sort_order' => ['nullable','integer'],
        ]);

        if (blank($data['brand']['slug'])) {
            $data['brand']['slug'] = Str::slug($data['brand']['name']);
        }

        $brand->fill($data['brand'])->save();

        Toast::info('Saved.');
        return redirect()->route('platform.brands.list');
    }

    public function remove(Brand $brand)
    {
        $brand->delete();
        Toast::info('Deleted.');
        return redirect()->route('platform.brands.list');
    }
}
