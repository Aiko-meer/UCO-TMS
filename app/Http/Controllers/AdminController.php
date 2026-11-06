<?php

namespace App\Http\Controllers;

use App\Models\Photo_vid_request_informations;
use App\Models\Publishing_request_informaitons;
use App\Models\Requestor;
use App\Models\Socmed_request_information;
use App\Models\Soft_copy_requests;
use App\Models\TrademarkRequest;
use Illuminate\Database\Eloquent\Builder;

class AdminController extends Controller
{
    public function dashboard()
    {
        [$requestTypeSummary, $requestStatusSummary, $requestGrandTotal] = $this->requestOverview();

        return view('admin.index', compact(
            'requestTypeSummary',
            'requestStatusSummary',
            'requestGrandTotal'
        ));
    }

    /**
     * Normalize the status codes used by the different request workflows.
     * Publication status 4 is "For Approval" and status 1 is approved.
     */
    private function requestOverview(): array
    {
        $summary = collect([
            'socmed' => $this->summarizeStatuses(
                Socmed_request_information::query(),
                [0 => 'pending', 1 => 'in_progress', 2 => 'completed', 3 => 'rejected']
            ),
            'photo' => $this->summarizeStatuses(
                Photo_vid_request_informations::query(),
                [0 => 'pending', 1 => 'in_progress', 2 => 'completed', 3 => 'rejected']
            ),
            'softcopy' => $this->summarizeStatuses(
                Soft_copy_requests::query(),
                [0 => 'pending', 1 => 'completed', 2 => 'rejected']
            ),
            'trademark' => $this->summarizeStatuses(
                TrademarkRequest::query(),
                [0 => 'pending', 1 => 'completed', 2 => 'rejected']
            ),
        ]);

        foreach (['layout', 'production', 'uzpr', 'uzpost'] as $specification) {
            $summary->put($specification, $this->summarizeStatuses(
                Publishing_request_informaitons::query()
                    ->where('specification', $specification),
                [0 => 'pending', 2 => 'in_progress', 4 => 'for_approval', 3 => 'rejected', 1 => 'completed']
            ));
        }

        $statusKeys = ['pending', 'in_progress', 'for_approval', 'rejected', 'completed'];
        $statusTotals = array_fill_keys($statusKeys, 0);
        $grandTotal = 0;

        foreach ($summary as $counts) {
            foreach ($statusKeys as $status) {
                $statusTotals[$status] += $counts[$status];
            }
            $grandTotal += $counts['total'];
        }

        return [$summary->all(), $statusTotals, $grandTotal];
    }

    /**
     * Fetch one row of status counts per request type instead of loading every record.
     */
    private function summarizeStatuses(Builder $query, array $statusMap): array
    {
        $counts = array_fill_keys(['pending', 'in_progress', 'for_approval', 'rejected', 'completed', 'total'], 0);
        $grouped = $query->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $counts['total'] = (int) $grouped->sum();

        foreach ($grouped as $status => $total) {
            $normalizedStatus = is_numeric($status) ? (int) $status : $status;
            if (isset($statusMap[$normalizedStatus])) {
                $counts[$statusMap[$normalizedStatus]] += (int) $total;
            }
        }

        return $counts;
    }
}
