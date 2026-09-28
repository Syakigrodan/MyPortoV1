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

        return view('index', compact('projects', 'skills', 'certificates', 'comments'));
    }
}
