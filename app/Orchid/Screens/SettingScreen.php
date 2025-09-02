<?php

namespace App\Orchid\Screens;

use App\Models\Setting;
use App\Services\ImageService;
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
        $imageService = app(ImageService::class);

        // read current values
        $siteName   = Setting::get('site.name', 'MyStore');
        $logoLight  = Setting::get('site.logo_light');
        $logoDark   = Setting::get('site.logo_dark');

        $limitsJson = Setting::get('home.limits');
        $limits     = is_string($limitsJson) && $limitsJson ? json_decode($limitsJson, true) : [];

        // Get current logo URLs for display
        $logoLightUrl = $logoLight ? $imageService->getUrl($logoLight) : null;
        $logoDarkUrl  = $logoDark ? $imageService->getUrl($logoDark) : null;

        return [
            'site_name'     => $siteName,
            'logo_light'    => $logoLight,
            'logo_dark'     => $logoDark,
            'logo_light_url'=> $logoLightUrl,
            'logo_dark_url' => $logoDarkUrl,
            'limits'        => [
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
        $imageService = app(ImageService::class);

        $logoLightHelp = Setting::get('site.logo_light')
            ? 'Current: ' . $imageService->getUrl(Setting::get('site.logo_light'))
            : 'Upload PNG/SVG (max 2MB)';

        $logoDarkHelp = Setting::get('site.logo_dark')
            ? 'Current: ' . $imageService->getUrl(Setting::get('site.logo_dark'))
            : 'Upload PNG/SVG (max 2MB)';

        return [
            Layout::rows([
                Input::make('site_name')->title('Site name')->required(),

                // Logo Upload Section
                Input::make('logo_light_file')
                    ->type('file')
                    ->title('Logo (Light Theme)')
                    ->acceptedFiles('image/*')
                    ->help($logoLightHelp),

                Input::make('logo_dark_file')
                    ->type('file')
                    ->title('Logo (Dark Theme)')
                    ->acceptedFiles('image/*')
                    ->help($logoDarkHelp),
            ])->title('General'),

            // Current Logos Display
            Layout::rows([
                // Empty row to create a titled section
            ])->title('Current Logos'),
            Layout::view('admin.current-logos'),

            Layout::rows([
                Group::make([
                    Input::make('limits.special')->title('Home: Special limit')->type('number'),
                    Input::make('limits.latest')->title('Home: Latest limit')->type('number'),
                    Input::make('limits.external')->title('Home: External-brand limit')->type('number'),
                    Input::make('limits.categories')->title('Home: Categories limit')->type('number'),
                ])->autoWidth(),
            ])->title('Home Page Limits'),
        ];
    }

    public function save(Request $request)
    {
        $imageService = app(ImageService::class);

        $data = $request->validate([
            'site_name'         => ['required','string','max:255'],
            'logo_light_file'   => ['nullable','image','max:3096'], // 2MB
            'logo_dark_file'    => ['nullable','image','max:3096'], // 2MB
            'limits.special'    => ['nullable','integer','min:1'],
            'limits.latest'     => ['nullable','integer','min:1'],
            'limits.external'   => ['nullable','integer','min:1'],
            'limits.categories' => ['nullable','integer','min:1'],
        ]);

        // Save site name
        Setting::set('site.name', $data['site_name']);

        // Upload and save light logo
        if ($request->hasFile('logo_light_file')) {
            $file = $request->file('logo_light_file');
            $uploadOptions = $imageService->getUploadOptions('branding');

            $result = $imageService->upload($file, 'branding', $uploadOptions);

            if ($result['success']) {
                $old = Setting::get('site.logo_light');
                if ($old) {
                    $imageService->delete($old);
                }
                Setting::set('site.logo_light', $result['path']);
            } else {
                Toast::error('Light logo upload failed: ' . $result['error']);
            }
        }

        // Upload and save dark logo
        if ($request->hasFile('logo_dark_file')) {
            $file = $request->file('logo_dark_file');
            $uploadOptions = $imageService->getUploadOptions('branding');

            $result = $imageService->upload($file, 'branding', $uploadOptions);

            if ($result['success']) {
                $old = Setting::get('site.logo_dark');
                if ($old) {
                    $imageService->delete($old);
                }
                Setting::set('site.logo_dark', $result['path']);
            } else {
                Toast::error('Dark logo upload failed: ' . $result['error']);
            }
        }

        // Save limits
        $limits = [
            'special'    => (int)($data['limits']['special'] ?? 12),
            'latest'     => (int)($data['limits']['latest'] ?? 12),
            'external'   => (int)($data['limits']['external'] ?? 12),
            'categories' => (int)($data['limits']['categories'] ?? 8),
        ];
        Setting::set('home.limits', json_encode($limits));

        Toast::info('Settings saved successfully.');
        return back();
    }


}
