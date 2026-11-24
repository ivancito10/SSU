<?php return array (
  'barryvdh/laravel-dompdf' => 
  array (
    'providers' => 
    array (
      0 => 'Barryvdh\\DomPDF\\ServiceProvider',
    ),
    'aliases' => 
    array (
      'Pdf' => 'Barryvdh\\DomPDF\\Facade\\Pdf',
      'PDF' => 'Barryvdh\\DomPDF\\Facade\\Pdf',
    ),
  ),
  'barryvdh/laravel-snappy' => 
  array (
    'aliases' => 
    array (
      'PDF' => 'Barryvdh\\Snappy\\Facades\\SnappyPdf',
      'SnappyImage' => 'Barryvdh\\Snappy\\Facades\\SnappyImage',
    ),
    'providers' => 
    array (
      0 => 'Barryvdh\\Snappy\\ServiceProvider',
    ),
  ),
  'berkayk/onesignal-laravel' => 
  array (
    'aliases' => 
    array (
      'OneSignal' => 'Berkayk\\OneSignal\\OneSignalFacade',
    ),
    'providers' => 
    array (
      0 => 'Berkayk\\OneSignal\\OneSignalServiceProvider',
    ),
  ),
  'jeroennoten/laravel-adminlte' => 
  array (
    'providers' => 
    array (
      0 => 'JeroenNoten\\LaravelAdminLte\\AdminLteServiceProvider',
    ),
  ),
  'laravel/breeze' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Breeze\\BreezeServiceProvider',
    ),
  ),
  'laravel/reverb' => 
  array (
    'aliases' => 
    array (
      'Output' => 'Laravel\\Reverb\\Output',
    ),
    'providers' => 
    array (
      0 => 'Laravel\\Reverb\\ApplicationManagerServiceProvider',
      1 => 'Laravel\\Reverb\\ReverbServiceProvider',
    ),
  ),
  'laravel/sail' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Sail\\SailServiceProvider',
    ),
  ),
  'laravel/sanctum' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Sanctum\\SanctumServiceProvider',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'livewire/livewire' => 
  array (
    'providers' => 
    array (
      0 => 'Livewire\\LivewireServiceProvider',
    ),
    'aliases' => 
    array (
      'Livewire' => 'Livewire\\Livewire',
    ),
  ),
  'maatwebsite/excel' => 
  array (
    'providers' => 
    array (
      0 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
    ),
    'aliases' => 
    array (
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
    ),
  ),
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/collision' => 
  array (
    'providers' => 
    array (
      0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    ),
  ),
  'nunomaduro/termwind' => 
  array (
    'providers' => 
    array (
      0 => 'Termwind\\Laravel\\TermwindServiceProvider',
    ),
  ),
  'pestphp/pest-plugin-laravel' => 
  array (
    'providers' => 
    array (
      0 => 'Pest\\Laravel\\PestServiceProvider',
    ),
  ),
  'power-components/livewire-powergrid' => 
  array (
    'providers' => 
    array (
      0 => 'PowerComponents\\LivewirePowerGrid\\Providers\\PowerGridServiceProvider',
    ),
  ),
  'simplesoftwareio/simple-qrcode' => 
  array (
    'providers' => 
    array (
      0 => 'SimpleSoftwareIO\\QrCode\\QrCodeServiceProvider',
    ),
    'aliases' => 
    array (
      'QrCode' => 'SimpleSoftwareIO\\QrCode\\Facades\\QrCode',
    ),
  ),
  'spatie/laravel-ignition' => 
  array (
    'providers' => 
    array (
      0 => 'Spatie\\LaravelIgnition\\IgnitionServiceProvider',
    ),
    'aliases' => 
    array (
      'Flare' => 'Spatie\\LaravelIgnition\\Facades\\Flare',
    ),
  ),
  'spatie/laravel-permission' => 
  array (
    'providers' => 
    array (
      0 => 'Spatie\\Permission\\PermissionServiceProvider',
    ),
  ),
);