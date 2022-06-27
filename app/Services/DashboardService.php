<?php

namespace App\Services;

use App\Http\Traits\ServiceTrait;
use App\Models\Candidate;

class DashboardService
{
    use ServiceTrait;

    public function get()
    {
        $positionsAndLanguages = auth()->user()->positions()->with(['languages' => function ($query) {
            return $query->with('candidates');
        }])->get();
        $totalCandidates = 0;
        $nameOfOpenings = [];
        $totalCandidateInOpenings = [];
        $nameOfLanguage = [];
        $totalCandidateInLanguages = [];
        $allCandidates = [];
        $positionsAndLanguages->map(function ($positionAndLanguage) use (&$totalCandidates, &$totalCandidateInOpenings,
            &$nameOfOpenings, &$nameOfLanguage, &$totalCandidateInLanguages, &$allCandidates) {
            $nameOfOpenings[] = $positionAndLanguage->name;
            $positionAndLanguage->languages->map(function ($language) use (&$totalCandidates, &$totalCandidateInOpenings,
                &$nameOfLanguage, &$totalCandidateInLanguages, &$allCandidates, $positionAndLanguage) {
                $count = $language->candidates()->count();
                $totalCandidates = $totalCandidates + $count;
                $totalCandidateInOpenings[] = $count;
                $nameOfLanguage[] = $language->name;
                $totalCandidateInLanguages[] = $count;
                $language->candidates()->get(['year_of_experience', 'skills', 'email'])->map(function ($candidates) use (&$allCandidates) {
                    $allCandidates[] = $candidates;
                });
            });
        });

        $mostExperiencedCandidates = collect($allCandidates)->sortByDesc('year_of_experience')->take(5);
        $mostExperiencedCandidateNames = $mostExperiencedCandidates->pluck('email');
        $mostExperiencedCandidateValues = $mostExperiencedCandidates->pluck('year_of_experience');

        $totalFavorites = auth()->user()->favorite_candidates()->count();
        $totalMeetings = auth()->user()->candidate_meetings()->whereDate('start_time', now()->format('Y-m-d'))->count();

        $data = [
            'total_candidates' => $totalCandidates,
            'total_favorites' => $totalFavorites,
            'total_meetings' => $totalMeetings,
            'total_candidate_in_opening' => [$nameOfOpenings, $totalCandidateInOpenings],
            'total_candidate_in_language' => [$nameOfLanguage, $totalCandidateInLanguages],
            'top_experienced' => [$mostExperiencedCandidateNames, $mostExperiencedCandidateValues]
        ];

        return $this->success($data, 'Dashboard Fetched');
    }
}
