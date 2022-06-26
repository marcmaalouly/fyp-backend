<?php

namespace App\Helpers;

use App\Exports\SkillExport;
use App\Models\Candidate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class CvParser
{
    public static function parse($files, Candidate $candidate)
    {
        $skillFile = self::getSkillCsvFile($candidate);

        foreach ($files as $file) {
            $fileContent = File::get(public_path('storage/attachments/' . $file));
            self::sendToPython($fileContent, $skillFile, $candidate);
        }
    }

    private static function getSkillCsvFile(Candidate $candidate)
    {
        $public_path = public_path('storage/skillExports/');

        if (!File::exists($public_path)) {
            File::makeDirectory($public_path, 0755, true);
        }

        $exportClass = new SkillExport($candidate->language);
        $csvName = $candidate->language->id . '-skillExport.csv';
        Excel::store($exportClass, $csvName, 'skill_exports');

        return File::get(public_path('storage/skillExports/' . $csvName));
    }

    private static function sendToPython($file, $skillFile, Candidate $candidate)
    {
        $response = Http::attach(
            'file[0]',
            $file,
            'CvToParse.pdf'
        )->attach(
            'file[1]',
            $skillFile,
            'skillFile.csv'
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

            if ($candidate->language->skill_keys()->where('key', 'like', '%'.$key.'%')->exists()) {
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
            'experience' => $data['experience'],
            'full_name' => $data['name']
        ]);
    }
}
