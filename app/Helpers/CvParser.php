<?php

namespace App\Helpers;

use App\Models\Candidate;
use App\Models\SkillKey;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class CvParser
{
    public static function parse($files, Candidate $candidate)
    {
        foreach ($files as $file) {
            $fileContent = File::get(public_path('storage/attachments/' . $file));
            self::sendToPython($fileContent, $candidate);
        }
    }

    private static function sendToPython($file, Candidate $candidate)
    {
        $response = Http::attach(
            'file',
            $file,
            'CvToParse.pdf'
        )->post(env('PYTHON_URL'), [
            'file' => $file,
        ]);

        self::saveToDB(json_decode($response->body()), $candidate);
    }

    private static function saveToDB($data, Candidate $candidate)
    {
        $data = collect($data)->toArray();

        if (empty($data['skills'])) {
            $candidate->delete();
            return true;
        }

        $languageHasSkill = false;

        foreach ($data['skills'] as $skill) {
            $key = strtolower($skill);

            if ($candidate->language->skill_keys()->where('key', 'like', $key)->exists()) {
                $languageHasSkill = true;
                break;
            }
        }

        if (!$languageHasSkill) {
            $candidate->delete();
            return true;
        }

        $candidate->update([
            'year_of_experience' => $data['total_experience'],
            'skills' => $data['skills'],
            'experience' => $data['experience']
        ]);

    }
}
