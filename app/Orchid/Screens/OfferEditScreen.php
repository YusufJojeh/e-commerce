<?php

namespace App\Orchid\Screens;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;

class OfferEditScreen extends Screen
{
    public $offer;

    public function query(Offer $offer): array
    {
        $this->offer = $offer->load('products');

        // pre-select product ids
        return [
            'offer'       => $this->offer,
            'product_ids' => $this->offer->exists ? $this->offer->products->pluck('id')->all() : [],
        ];
    }

    public function name(): ?string
    {
        return $this->offer && $this->offer->exists ? 'Edit Offer' : 'Create Offer';
    }

    public function commandBar(): array
    {
        return [
            Button::make('Save')->icon('bs.check')->method('createOrUpdate'),
            Button::make('Remove')
                ->icon('bs.trash')
                ->confirm('Delete this offer?')
                ->method('remove')
                ->canSee($this->offer && $this->offer->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('offer.title')->title('Title')->required(),
                TextArea::make('offer.description')->title('Description')->rows(3),

                Select::make('offer.type')
                    ->title('Type')
                    ->options([
                        'percent'       => 'Percent (%)',
                        'fixed'         => 'Fixed amount',
                        'free_shipping' => 'Free shipping',
                    ])
                    ->required()
                    ->help('If free shipping, value is ignored.'),

                Input::make('offer.value')
                    ->title('Value')
                    ->type('number')
                    ->step('0.01')
                    ->help('For percent: 1–100. For fixed: >= 0'),

                DateTimer::make('offer.starts_at')->title('Starts at')->allowInput(),
                DateTimer::make('offer.ends_at')->title('Ends at')->allowInput(),

                Switcher::make('offer.is_active')->title('Active')->sendTrueOrFalse()->value(true),

                Input::make('offer.banner_image')->title('Banner image path')->placeholder('banners/summer.jpg'),
                Input::make('offer.cta_url')->title('CTA URL')->placeholder('/products'),

                // Attach products
                Select::make('product_ids')
                    ->title('Products in this offer')
                    ->fromModel(Product::class, 'name', 'id')
                    ->multiple()
                    ->help('Choose products to include in this offer'),
            ])->title('Offer Details'),
        ];
    }

    public function createOrUpdate(Request $request, Offer $offer)
    {
        $data = $request->validate([
            'offer.title'       => ['required','string','max:255'],
            'offer.description' => ['nullable','string'],
            'offer.type'        => ['required', Rule::in(['percent','fixed','free_shipping'])],
            'offer.value'       => ['nullable','numeric'],
            'offer.starts_at'   => ['nullable','date'],
            'offer.ends_at'     => ['nullable','date','after_or_equal:offer.starts_at'],
            'offer.is_active'   => ['boolean'],
            'offer.banner_image'=> ['nullable','string','max:255'],
            'offer.cta_url'     => ['nullable','string','max:255'],
            'product_ids'       => ['array'],
            'product_ids.*'     => ['integer','exists:products,id'],
        ]);

        // Conditional rules: enforce value for percent/fixed
        $type = $data['offer']['type'];
        if (in_array($type, ['percent','fixed'], true)) {
            $request->validate([
                'offer.value' => ['required','numeric', $type === 'percent' ? 'min:1|max:100' : 'min:0'],
            ]);
        } else {
            // free_shipping: ignore value
            $data['offer']['value'] = null;
        }

        $offer->fill($data['offer'])->save();

        // sync products
        $ids = $data['product_ids'] ?? [];
        $offer->products()->sync($ids);

        Toast::info('Saved.');
        return redirect()->route('platform.offers.edit', $offer);
    }

    public function remove(Offer $offer)
    {
        try {
            $offer->products()->detach();
            $offer->delete();
            Toast::info('Deleted.');
            return redirect()->route('platform.offers.list');
        } catch (\Throwable $e) {
            Toast::error('Cannot delete: '.$e->getMessage());
            return back();
        }
    }
}
