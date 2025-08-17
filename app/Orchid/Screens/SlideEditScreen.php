<?php

namespace App\Orchid\Screens;

use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;

class SlideEditScreen extends Screen
{
    public $slide;

    public function query(Slide $slide): array
    {
        $this->slide = $slide;
        return ['slide' => $slide];
    }

    public function name(): ?string
    {
        return $this->slide && $this->slide->exists ? 'Edit Slide' : 'Create Slide';
    }

    public function commandBar(): array
    {
        return [
            Button::make('Save')->icon('bs.check')->method('createOrUpdate'),
            Button::make('Remove')
                ->icon('bs.trash')
                ->confirm('Delete this slide?')
                ->method('remove')
                ->canSee($this->slide && $this->slide->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Select::make('slide.position')->title('Position')
                    ->options(['main'=>'Main hero','slider'=>'Slider'])
                    ->required(),

                Input::make('slide.title')->title('Title')->required(),
                Input::make('slide.subtitle')->title('Subtitle'),

                Input::make('slide.image_path')->title('Image path')->placeholder('banners/hero.jpg')->required(),

                Input::make('slide.cta_label')->title('CTA label')->placeholder('Shop Now'),
                Input::make('slide.cta_url')->title('CTA URL')->placeholder('/products'),

                Input::make('slide.sort_order')->title('Order')->type('number')->value(0),

                DateTimer::make('slide.starts_at')->title('Starts at')->allowInput(),
                DateTimer::make('slide.ends_at')->title('Ends at')->allowInput(),

                Switcher::make('slide.is_active')->title('Active')->sendTrueOrFalse()->value(true),
            ])->title('Slide details'),
        ];
    }

    public function createOrUpdate(Request $request, Slide $slide)
    {
        $data = $request->validate([
            'slide.position'   => ['required', Rule::in(['main','slider'])],
            'slide.title'      => ['required','string','max:255'],
            'slide.subtitle'   => ['nullable','string','max:255'],
            'slide.image_path' => ['required','string','max:255'],
            'slide.cta_label'  => ['nullable','string','max:255'],
            'slide.cta_url'    => ['nullable','string','max:255'],
            'slide.sort_order' => ['nullable','integer'],
            'slide.starts_at'  => ['nullable','date'],
            'slide.ends_at'    => ['nullable','date','after_or_equal:slide.starts_at'],
            'slide.is_active'  => ['boolean'],
        ]);

        $slide->fill($data['slide'])->save();

        Toast::info('Saved.');
        return redirect()->route('platform.slides.edit', $slide);
    }

    public function remove(Slide $slide)
    {
        $slide->delete();
        Toast::info('Deleted.');
        return redirect()->route('platform.slides.list');
    }
}
