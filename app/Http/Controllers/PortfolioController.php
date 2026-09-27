<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Skill;

class PortfolioController extends Controller
{
    public function __invoke()
    {
        $projects = Project::orderBy('sort_order')->get();

        $skills = Skill::orderBy('sort_order')->get();

        $certificates = Certificate::orderByDesc('issued_date')->get();

        $comments = Comment::orderByDesc('is_pinned')->orderByDesc('created_at')->get();

        $stats = [
            ['value' => max($projects->count(), config('portfolio.stats.projects', 50)), 'suffix' => '+', 'label' => 'Projects Shipped'],
            ['value' => config('portfolio.stats.satisfaction', 99), 'suffix' => '%', 'label' => 'Client Satisfaction'],
            ['value' => config('portfolio.stats.years', 5), 'suffix' => '+', 'label' => 'Years Experience'],
            ['value' => max($certificates->count(), config('portfolio.stats.certifications', 15)), 'suffix' => '+', 'label' => 'Certifications & Awards'],
        ];

        return view('index', compact('projects', 'skills', 'certificates', 'stats', 'comments'));
    }
}
