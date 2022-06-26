<?php

namespace App\Exports;

use App\Models\Language;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

class SkillExport implements FromArray
{
    protected $language;

    /**
     * @param Language $language
     */
    public function __construct(Language $language)
    {
        $this->language = $language;
    }

    public function array(): array
    {
        $data = $this->language->skill_keys()->get(['key'])->pluck('key');
        $data = $data->map(function ($key) {
           return explode('-', $key)[0];
        });

        return [$data->toArray()];
    }
}
