<?php

namespace App\Helpers;

use App\Models\Candidate;
use App\Models\CandidateAttachment;
use Illuminate\Support\Facades\Storage;

class AttachmentHelper
{
    public static function saveInDB(Candidate $candidate)
    {
        $files = Storage::disk('attachments')->allFiles($candidate->language->position->user_id . '/' . $candidate->language_id
            . '/' . $candidate->email);

        foreach ($files as $file) {
            CandidateAttachment::create([
                'candidate_id' => $candidate->id,
                'path' => 'storage/attachments/' . $file,
                'name' => explode($candidate->email . "/", $file)[1]
            ]);
        }

        return $files;
    }
}
