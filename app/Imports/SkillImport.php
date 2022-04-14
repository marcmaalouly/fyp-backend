<?php

namespace App\Imports;

use App\Models\SkillKey;
use Maatwebsite\Excel\Concerns\ToModel;

class SkillImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SkillKey([
            'key' => $row[0]
        ]);
    }
}
