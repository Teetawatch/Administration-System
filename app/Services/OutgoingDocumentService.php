<?php

namespace App\Services;

use App\Models\OutgoingDocument;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Service class for Outgoing Document business logic.
 * 
 * Following software-architecture best practices:
 * - Separation of Concerns: Business logic separated from controllers
 * - Single Responsibility: Each method has one clear purpose
 * - Reusable: Can be used by multiple controllers or commands
 */
class OutgoingDocumentService
{
    /**
     * Get paginated outgoing documents with filters.
     *
     * @param string|null $search Search term
     * @param string|null $department Department filter
     * @param string|null $urgency Urgency filter
     * @param int $perPage Items per page
     * @return LengthAwarePaginator
     */
    public function getPaginatedDocuments(
        ?string $search = null,
        ?string $department = null,
        ?string $urgency = null,
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = OutgoingDocument::with('creator')->latest();

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('document_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('to_recipient', 'like', "%{$search}%");
            });
        }

        if ($department) {
            $query->where('department', $department);
        }

        if ($urgency) {
            $query->where('urgency', $urgency);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get documents by IDs for export.
     *
     * @param array<int> $ids Document IDs
     * @return Collection<OutgoingDocument>
     */
    public function getDocumentsByIds(array $ids): Collection
    {
        return OutgoingDocument::whereIn('id', $ids)
            ->orderBy('document_date', 'desc')
            ->get();
    }

    /**
     * Generate next document number for normal documents.
     *
     * @return string
     */
    public function getNextNormalDocumentNumber(): string
    {
        $currentYear = date('Y');

        $lastNormal = OutgoingDocument::where('is_secret', false)
            ->whereYear('document_date', $currentYear)
            ->latest('id')
            ->first();

        $runningNumber = 1;

        if ($lastNormal) {
            if (is_numeric($lastNormal->document_number)) {
                $runningNumber = intval($lastNormal->document_number) + 1;
            } elseif (preg_match('/^(\d+)/', $lastNormal->document_number, $matches)) {
                $runningNumber = intval($matches[1]) + 1;
            }
        }

        return (string) $runningNumber;
    }

    /**
     * Generate next document number for secret documents.
     *
     * @return string
     */
    public function getNextSecretDocumentNumber(): string
    {
        $currentYear = date('Y');

        $lastSecret = OutgoingDocument::where('is_secret', true)
            ->whereYear('document_date', $currentYear)
            ->latest('id')
            ->first();

        $runningNumber = 1;

        if ($lastSecret) {
            if (preg_match('/ลับ\s*(\d+)/', $lastSecret->document_number, $matches)) {
                $runningNumber = intval($matches[1]) + 1;
            }
        }

        return "ลับ " . $runningNumber;
    }

    /**
     * Create a new outgoing document.
     *
     * @param array<string, mixed> $data Validated data
     * @param int $userId Creator user ID
     * @param UploadedFile|null $attachment Attachment file
     * @return OutgoingDocument
     */
    public function create(array $data, int $userId, ?UploadedFile $attachment = null): OutgoingDocument
    {
        $data['created_by'] = $userId;
        $data['is_secret'] = $data['is_secret'] ?? false;

        if ($attachment) {
            $data['attachment_path'] = $attachment->store('outgoing-documents', 'public');
        }

        return OutgoingDocument::create($data);
    }

    /**
     * Update an existing outgoing document.
     *
     * @param OutgoingDocument $document
     * @param array<string, mixed> $data Validated data
     * @param UploadedFile|null $attachment New attachment file
     * @return OutgoingDocument
     */
    public function update(
        OutgoingDocument $document,
        array $data,
        ?UploadedFile $attachment = null
    ): OutgoingDocument {
        if ($attachment) {
            // Delete old attachment if exists
            if ($document->attachment_path) {
                Storage::disk('public')->delete($document->attachment_path);
            }
            $data['attachment_path'] = $attachment->store('outgoing-documents', 'public');
        }

        $document->update($data);

        return $document->fresh();
    }

    /**
     * Delete an outgoing document and its attachment.
     *
     * @param OutgoingDocument $document
     * @return bool
     */
    public function delete(OutgoingDocument $document): bool
    {
        if ($document->attachment_path) {
            Storage::disk('public')->delete($document->attachment_path);
        }

        return $document->delete() ?? false;
    }

    /**
     * Get all unique departments.
     *
     * @return Collection<string>
     */
    public function getDepartments(): Collection
    {
        return OutgoingDocument::distinct()->pluck('department')->filter();
    }
}
