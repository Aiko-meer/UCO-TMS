<?php

namespace App\Http\Controllers;

use App\Models\Requestor;
use App\Models\Soft_copy_requests;
use App\Models\TrademarkRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequestTrackingController extends Controller
{
    // status value => [label, tone]
    private const SOCMED_STATUSES = [
        0 => ['Pending', 'warning'],
        1 => ['Approved', 'success'],
        2 => ['Posted', 'success'],
        3 => ['Rejected', 'danger'],
    ];

    private const PUBLISHING_STATUSES = [
        0 => ['Pending', 'warning'],
        1 => ['Approved', 'success'],
        2 => ['In progress', 'info'],
        3 => ['Rejected', 'danger'],
        4 => ['For approval', 'primary'],
    ];

    private const PHOTO_STATUSES = [
        0 => ['Pending', 'warning'],
        1 => ['Approved', 'success'],
        2 => ['Done', 'success'],
        3 => ['Rejected', 'danger'],
    ];

    private const RELEASE_STATUSES = [
        0 => ['Pending', 'warning'],
        1 => ['Done', 'success'],
        2 => ['Rejected', 'danger'],
    ];

    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ref' => 'required|string|max:20',
        ]);

        $ref = trim($validated['ref']);

        $parts = [];
        $storedId = null;
        $submittedAt = null;

        $requestor = Requestor::with(['socmed', 'publishing', 'photo'])
            ->where('request_id', $ref)
            ->first();

        if ($requestor) {
            $storedId = $requestor->request_id;
            $submittedAt = $requestor->created_at;
            $parts = $this->requestorParts($requestor);
        }

        // Soft copy and trademark requests do not have a requestor row.
        if ($softCopy = Soft_copy_requests::where('request_id', $ref)->first()) {
            $storedId ??= $softCopy->request_id;
            $submittedAt ??= $softCopy->created_at;
            $parts[] = $this->part('Soft Copy Request', $softCopy->status, self::RELEASE_STATUSES);
        }

        if ($trademark = TrademarkRequest::where('request_id', $ref)->first()) {
            $storedId ??= $trademark->request_id;
            $submittedAt ??= $trademark->created_at;
            $parts[] = $this->part('Trademark Request', $trademark->status, self::RELEASE_STATUSES);
        }

        if ($parts === []) {
            return response()->json([
                'message' => 'No request was found with that reference number.',
            ], 404);
        }

        return response()->json([
            'request_id' => $storedId,
            'submitted_at' => $submittedAt?->toIso8601String(),
            'parts' => $parts,
        ]);
    }

    /**
     * Recover the reference numbers sent from an email address + last name.
     */
    public function recover(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'l_name' => 'required|string|max:255',
        ], [], [
            'l_name' => 'last name',
        ]);

        $email = trim($validated['email']);
        $lastName = trim($validated['l_name']);
        $matches = [];

        $requestors = Requestor::with(['socmed', 'publishing', 'photo'])
            ->where('email', $email)
            ->where('l_name', $lastName)
            ->latest()
            ->limit(10)
            ->get();

        foreach ($requestors as $requestor) {
            $matches[] = $this->summary(
                $requestor->request_id,
                $requestor->created_at,
                $this->requestorParts($requestor)
            );
        }

        $softCopies = Soft_copy_requests::where('email', $email)
            ->where('l_name', $lastName)
            ->latest()
            ->limit(10)
            ->get();

        foreach ($softCopies as $softCopy) {
            $matches[] = $this->summary(
                $softCopy->request_id,
                $softCopy->created_at,
                [$this->part('Soft Copy Request', $softCopy->status, self::RELEASE_STATUSES)]
            );
        }

        $trademarks = TrademarkRequest::where('email', $email)
            ->where('l_name', $lastName)
            ->latest()
            ->limit(10)
            ->get();

        foreach ($trademarks as $trademark) {
            $matches[] = $this->summary(
                $trademark->request_id,
                $trademark->created_at,
                [$this->part('Trademark Request', $trademark->status, self::RELEASE_STATUSES)]
            );
        }

        usort($matches, fn ($a, $b) => strcmp($b['submitted_at'] ?? '', $a['submitted_at'] ?? ''));
        $matches = array_slice($matches, 0, 10);

        if ($matches === []) {
            return response()->json([
                'message' => 'No requests were found for that email address and last name.',
            ], 404);
        }

        return response()->json(['requests' => $matches]);
    }

    private function requestorParts(Requestor $requestor): array
    {
        $parts = [];

        if ($requestor->socmed) {
            $parts[] = $this->part('Social Media Request', $requestor->socmed->status, self::SOCMED_STATUSES);
        }

        if ($requestor->publishing) {
            $parts[] = $this->part(
                $this->publishingType($requestor->publishing->specification),
                $requestor->publishing->status,
                self::PUBLISHING_STATUSES
            );
        }

        if ($requestor->photo) {
            $parts[] = $this->part('Photography / Video Request', $requestor->photo->status, self::PHOTO_STATUSES);
        }

        return $parts;
    }

    private function summary(string $requestId, mixed $submittedAt, array $parts): array
    {
        return [
            'request_id' => $requestId,
            'submitted_at' => $submittedAt?->toIso8601String(),
            'parts' => $parts,
        ];
    }

    private function part(string $type, mixed $status, array $statuses): array
    {
        $code = $status === null ? 0 : (int) $status;
        [$label, $tone] = $statuses[$code] ?? ['Unknown', 'info'];

        return [
            'type' => $type,
            'status' => $label,
            'status_code' => $code,
            'tone' => $tone,
        ];
    }

    private function publishingType(?string $specification): string
    {
        return match ($specification) {
            'layout' => 'Layout Request',
            'production' => 'Production Request',
            'uzpost' => 'UZ Post Request',
            'uzpr' => 'UZ PR Request',
            default => 'Creative Request',
        };
    }
}
