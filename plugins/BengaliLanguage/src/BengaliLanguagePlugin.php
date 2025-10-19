<?php

namespace Plugins\BengaliLanguage;

use App\Core\Plugin\Plugin;
use Illuminate\Support\Facades\App;

class BengaliLanguagePlugin extends Plugin
{
    public function boot(): void
    {
        $this->loadTranslations();
    }

    public function loadTranslations()
    {
        $this->loadJsonTranslationsFrom($this->path . '/lang');
    }
}
