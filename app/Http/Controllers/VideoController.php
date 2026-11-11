<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\Requestor;
use Illuminate\Http\Request;

/**
 * Video request dashboard.
 *
 * This used to query App\Models\Photo_vid_requests, a class that does not
 * exist, so the page threw "Class not found" and every notification pointing
 * at a video request landed on a 500. Video requests are photo/video
 * requestors whose child row has a video category - the same data
 * PhotographyController reads.
 */
class VideoController extends Controller
{
    public function dashboard()
    {
        $departments = Departments::all();

        // Video requests only: the child row's category marks it as video.
        $base = fn () => Requestor::with('photo')
            ->whereHas('photo', function ($query) {
                $query->whereIn('category', ['vid', 'video', 'videography']);
            });

        $requests = $base()->orderBy('id', 'desc')->get();

        $actviepagi = $base()->paginate(5, ['*'], 'active_page');
        $archivepagi = $base()->paginate(10, ['*'], 'archive_page');
        $monthpagi = $base()->paginate(10, ['*'], 'month_page');
        $listpagi = $base()->paginate(5, ['*'], 'list_page');

        $month = request('month', date('m'));
        $year = request('year', date('Y'));

        $archived = $base()
            ->whereHas('photo', function ($query) use ($year, $month) {
                $query->where('status', 2)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            })
            ->get();

        return view('admin.video.index', compact(
            'departments',
            'requests',
            'archived',
            'actviepagi',
            'archivepagi',
            'monthpagi',
            'listpagi'
        ));
    }
}
