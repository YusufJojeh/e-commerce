<?php

namespace App\Orchid\Screens;

use App\Models\Setting;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;

class SettingScreen extends Screen
{
    public function name(): ?string { return 'Settings'; }
    public function description(): ?string { return 'Site & homepage settings'; }

    public function query(): array
    {
        // read current values
        $siteName   = method_exists(Setting::class, 'get') ? Setting::get('site.name') : optional(Setting::where('key','site.name')->first())->value;
        $logoLight  = method_exists(Setting::class, 'get') ? Setting::get('site.logo_light') : optional(Setting::where('key','site.logo_light')->first())->value;
        $logoDark   = method_exists(Setting::class, 'get') ? Setting::get('site.logo_dark') : optional(Setting::where('key','site.logo_dark')->first())->value;

        $limitsJson = method_exists(Setting::class, 'get') ? Setting::get('home.limits') : optional(Setting::where('key','home.limits')->first())->value;
        $limits     = is_string($limitsJson) && $limitsJson ? json_decode($limitsJson, true) : [];

        return [
            'site_name'  => $siteName,
            'logo_light' => $logoLight,
            'logo_dark'  => $logoDark,
            'limits'     => [
                'special'    => $limits['special']    ?? 12,
                'latest'     => $limits['latest']     ?? 12,
                'external'   => $limits['external']   ?? 12,
                'categories' => $limits['categories'] ?? 8,
            ],
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Save')->icon('bs.check')->method('save'),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('site_name')->title('Site name')->required(),
                Group::make([
                    Input::make('logo_light')->title('Logo light path')->placeholder('logos/logo-light.png'),
                    Input::make('logo_dark')->title('Logo dark path')->placeholder('logos/logo-dark.png'),
                ])->autoWidth(),

                Group::make([
                    Input::make('limits.special')->title('Home: Special limit')->type('number'),
                    Input::make('limits.latest')->title('Home: Latest limit')->type('number'),
                    Input::make('limits.external')->title('Home: External-brand limit')->type('number'),
                    Input::make('limits.categories')->title('Home: Categories limit')->type('number'),
                ])->autoWidth(),
            ])->title('General'),
        ];
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'site_name'         => ['required','string','max:255'],
            'logo_light'        => ['nullable','string','max:255'],
            'logo_dark'         => ['nullable','string','max:255'],
            'limits.special'    => ['nullable','integer','min:1'],
            'limits.latest'     => ['nullable','integer','min:1'],
            'limits.external'   => ['nullable','integer','min:1'],
            'limits.categories' => ['nullable','integer','min:1'],
        ]);

        $this->writeSetting('site.name',      'site', $data['site_name']);
        $this->writeSetting('site.logo_light','site', $data['logo_light'] ?? '');
        $this->writeSetting('site.logo_dark', 'site', $data['logo_dark'] ?? '');

        $limits = [
            'special'    => (int)($data['limits']['special'] ?? 12),
            'latest'     => (int)($data['limits']['latest'] ?? 12),
            'external'   => (int)($data['limits']['external'] ?? 12),
            'categories' => (int)($data['limits']['categories'] ?? 8),
        ];
        $this->writeSetting('home.limits', 'home', json_encode($limits));

        Toast::info('Settings saved.');
        return back();
    }

    private function writeSetting(string $key, string $group, string $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['group' => $group, 'value' => $value]
        );
    }
}
