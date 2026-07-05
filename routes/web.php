<?php

use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\DomainController as AdminDomainController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\SemesterController as AdminSemesterController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Admin\EvaluationController as AdminEvaluationController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\ArchiveController as AdminArchiveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RoleDashboardController;
use App\Http\Controllers\Student\FeedbackController as StudentFeedbackController;
use App\Http\Controllers\Student\MilestoneController;
use App\Http\Controllers\Student\MeetingController as StudentMeetingController;
use App\Http\Controllers\Student\PresentationController;
use App\Http\Controllers\Supervisor\DocumentController;
use App\Http\Controllers\Student\DocumentController as StudentDocumentController;
use App\Http\Controllers\Student\ProjectController as StudentProjectController;
use App\Http\Controllers\Student\TeamController as StudentTeamController;
use App\Http\Controllers\Supervisor\EvaluationController as SupervisorEvaluationController;
use App\Http\Controllers\Supervisor\ProposalController as SupervisorProposalController;
use Illuminate\Support\Facades\Route;



Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/announcements', [PublicController::class, 'announcements'])->name('public.announcements');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [RoleDashboardController::class, 'show'])->name('dashboard');
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->middleware('student')->name('student.dashboard');
    Route::get('/supervisor/dashboard', [\App\Http\Controllers\Supervisor\DashboardController::class, 'index'])
        ->middleware('supervisor')
        ->name('supervisor.dashboard');
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('admin')->name('admin.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/teams', [StudentTeamController::class, 'index'])->name('teams.index');
    Route::post('/teams', [StudentTeamController::class, 'store'])->name('teams.store');
    Route::post('/teams/invite', [StudentTeamController::class, 'invite'])->name('teams.invite');
    Route::patch('/teams/invite/{teamMember}', [StudentTeamController::class, 'respondInvite'])->name('teams.respondInvite');

    Route::get('/project', [StudentProjectController::class, 'index'])->name('project.index');
    Route::post('/project', [StudentProjectController::class, 'storeOrUpdate'])->name('project.storeOrUpdate');
    Route::patch('/project/{project}/submit', [StudentProjectController::class, 'submit'])->name('project.submit');

    Route::get('/documents', [StudentDocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [StudentDocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{documentSubmission}/download', [StudentDocumentController::class, 'download'])->name('documents.download');

    Route::get('/milestones', [MilestoneController::class, 'index'])->name('milestones.index');
    Route::post('/milestones', [MilestoneController::class, 'store'])->name('milestones.store');
    Route::patch('/milestones/{milestone}', [MilestoneController::class, 'update'])->name('milestones.update');
    Route::delete('/milestones/{milestone}', [MilestoneController::class, 'destroy'])->name('milestones.destroy');

    Route::get('/feedback', [StudentFeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/feedback/send', [StudentFeedbackController::class, 'send'])->name('feedback.send');

    Route::get('/presentations', [PresentationController::class, 'index'])->name('presentations.index');
    Route::get('/meetings', [StudentMeetingController::class, 'index'])->name('meetings.index');
});

Route::middleware(['auth', 'role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
    Route::get('/proposals', [SupervisorProposalController::class, 'index'])->name('proposals.index');
    Route::patch('/proposals/{project}/status', [SupervisorProposalController::class, 'updateStatus'])->name('proposals.updateStatus');
    Route::post('/proposals/{project}/evaluation', [SupervisorEvaluationController::class, 'store'])->name('proposals.evaluation.store');

    Route::get('/assigned-teams', [\App\Http\Controllers\Supervisor\TeamController::class, 'index'])
        ->name('assignedTeams.index');

    Route::get('/evaluation', [\App\Http\Controllers\Supervisor\EvaluationController::class, 'index'])
        ->name('evaluation.index');

    Route::get('/progress', [\App\Http\Controllers\Supervisor\ProgressController::class, 'index'])
        ->name('progress.index');

    Route::get('/communication', [\App\Http\Controllers\Supervisor\CommunicationController::class, 'index'])
        ->name('communication.index');
    Route::post('/communication/{feedbackMessage}/reply', [\App\Http\Controllers\Supervisor\CommunicationController::class, 'reply'])
        ->name('communication.reply');

    Route::get('/meetings', [\App\Http\Controllers\Supervisor\MeetingController::class, 'index'])
        ->name('meetings.index');
    Route::post('/meetings', [\App\Http\Controllers\Supervisor\MeetingController::class, 'store'])
        ->name('meetings.store');
    Route::patch('/meetings/{meeting}', [\App\Http\Controllers\Supervisor\MeetingController::class, 'update'])
        ->name('meetings.update');
    Route::delete('/meetings/{meeting}', [\App\Http\Controllers\Supervisor\MeetingController::class, 'destroy'])
        ->name('meetings.destroy');

    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{documentSubmission}/download', [DocumentController::class, 'download'])->name('documents.download');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/domains', [AdminDomainController::class, 'index'])->name('domains.index');
    Route::post('/domains', [AdminDomainController::class, 'store'])->name('domains.store');
    Route::patch('/domains/{domain}', [AdminDomainController::class, 'update'])->name('domains.update');
    Route::delete('/domains/{domain}', [AdminDomainController::class, 'destroy'])->name('domains.destroy');

    Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');
    Route::delete('/announcements/{announcement}', [AdminAnnouncementController::class, 'destroy'])->name('announcements.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');

    Route::get('/semesters', [AdminSemesterController::class, 'index'])->name('semesters.index');
    Route::post('/semesters', [AdminSemesterController::class, 'store'])->name('semesters.store');
    Route::delete('/semesters/{semester}', [AdminSemesterController::class, 'destroy'])->name('semesters.destroy');

    Route::get('/projects', [AdminProjectController::class, 'index'])->name('projects.index');
    Route::patch('/projects/{project}', [AdminProjectController::class, 'update'])->name('projects.update');

    Route::get('/teams', [AdminTeamController::class, 'index'])->name('teams.index');
    Route::patch('/teams/{team}', [AdminTeamController::class, 'update'])->name('teams.update');
    Route::patch('/teams/{team}/supervisor', [AdminTeamController::class, 'assignSupervisor'])->name('teams.assignSupervisor');

    Route::get('/evaluations', [AdminEvaluationController::class, 'index'])->name('evaluations.index');
    Route::post('/evaluations', [AdminEvaluationController::class, 'store'])->name('evaluations.store');
    Route::patch('/evaluations/{evaluation}', [AdminEvaluationController::class, 'update'])->name('evaluations.update');
    Route::delete('/evaluations/{evaluation}', [AdminEvaluationController::class, 'destroy'])->name('evaluations.destroy');

    Route::get('/documents', [AdminDocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{documentSubmission}/download', [AdminDocumentController::class, 'download'])->name('documents.download');
    Route::patch('/documents/{documentSubmission}/review', [AdminDocumentController::class, 'updateReview'])->name('documents.updateReview');
    Route::delete('/documents/{documentSubmission}', [AdminDocumentController::class, 'delete'])->name('documents.destroy');

    Route::get('/reports', [AdminController::class, 'reports'])
        ->name('reports');

    Route::get('/archive', [AdminArchiveController::class, 'index'])->name('archive.index');
    Route::post('/archive/{project}', [AdminArchiveController::class, 'archive'])->name('archive.store');
    Route::post('/archive/{project}/restore', [AdminArchiveController::class, 'restore'])->name('archive.restore');
    Route::get('/archive/{project}/details', [AdminArchiveController::class, 'viewArchiveDetails'])->name('archive.details');
    Route::post('/archive/bulk-archive', [AdminArchiveController::class, 'bulkArchive'])->name('archive.bulkArchive');
});

require __DIR__ . '/auth.php';
