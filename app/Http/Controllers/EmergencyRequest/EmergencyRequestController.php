<?php

namespace App\Http\Controllers\EmergencyRequest;

use App\Enums\BloodComponent;
use App\Enums\BloodRequestStatus;
use App\Enums\BloodType;
use App\Enums\Rhesus;
use App\Enums\UrgencyLevel;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmergencyRequest\StoreEmergencyRequest;
use App\Models\BloodRequest;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EmergencyRequestController extends Controller
{
    /**
     * Display the authenticated seeker's emergency blood requests and drafts.
     */
    public function index(Request $request): View
    {
        $userId = $request->user()->id;
        $statusFilter = (string) $request->query('status', 'all');

        $query = BloodRequest::query()
            ->with(['facility.region'])
            ->forRequester($userId)
            ->latest();

        if ($statusFilter !== 'all' && in_array($statusFilter, BloodRequestStatus::values(), true)) {
            $query->where('status', $statusFilter);
        }

        $requests = $query->paginate(10)->withQueryString();

        $summary = [
            'total' => BloodRequest::forRequester($userId)->count(),
            'draft' => BloodRequest::forRequester($userId)->where('status', BloodRequestStatus::DRAFT->value)->count(),
            'pending' => BloodRequest::forRequester($userId)->where('status', BloodRequestStatus::PENDING_VERIFICATION->value)->count(),
            'active' => BloodRequest::forRequester($userId)->where('status', BloodRequestStatus::ACTIVE->value)->count(),
        ];

        return view('emergency-requests.index', [
            'requests' => $requests,
            'summary' => $summary,
            'statusFilter' => $statusFilter,
        ]);
    }

    /**
     * Show the form for creating a new emergency blood request.
     */
    public function create(): View
    {
        $facilitiesByRegion = Facility::query()
            ->with('region')
            ->active()
            ->orderByDesc('type')
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Facility $facility): string => $facility->region?->full_label ?? 'Wilayah Lainnya');

        return view('emergency-requests.create', [
            'facilitiesByRegion' => $facilitiesByRegion,
            'bloodTypes' => BloodType::cases(),
            'rhesusTypes' => Rhesus::cases(),
            'components' => BloodComponent::cases(),
            'urgencyLevels' => UrgencyLevel::cases(),
            'relationships' => [
                'Keluarga Inti (Orang Tua/Suami/Istri/Anak)',
                'Kerabat / Saudara Pasien',
                'Pasien Sendiri',
                'Pendamping / Perwakilan Resmi RS',
            ],
        ]);
    }

    /**
     * Store a newly created emergency blood request or save it as a draft.
     */
    public function store(StoreEmergencyRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $isDraft = $request->isDraftSubmission();

        $fileData = [
            'referral_letter_path' => null,
            'referral_letter_original_name' => null,
            'referral_letter_mime' => null,
            'referral_letter_size' => null,
        ];

        if ($request->hasFile('referral_letter')) {
            $uploaded = $request->file('referral_letter');
            $extension = strtolower($uploaded->getClientOriginalExtension() ?: 'pdf');
            $filename = Str::uuid()->toString().'.'.$extension;
            $storedPath = $uploaded->storeAs(
                'private/referral-letters/'.now()->format('Y/m'),
                $filename,
                'local'
            );

            $fileData = [
                'referral_letter_path' => $storedPath,
                'referral_letter_original_name' => $uploaded->getClientOriginalName(),
                'referral_letter_mime' => $uploaded->getClientMimeType(),
                'referral_letter_size' => $uploaded->getSize(),
            ];
        }

        $bloodRequest = DB::transaction(function () use ($request, $validated, $isDraft, $fileData): BloodRequest {
            return BloodRequest::create(array_merge([
                'requester_id' => $request->user()->id,
                'facility_id' => $validated['facility_id'],
                'patient_name' => $validated['patient_name'],
                'patient_age' => $validated['patient_age'] ?? null,
                'patient_gender' => $validated['patient_gender'] ?? null,
                'patient_relationship' => $validated['patient_relationship'],
                'medical_record_number' => $validated['medical_record_number'] ?? null,
                'ward_room' => $validated['ward_room'] ?? null,
                'clinical_diagnosis' => $validated['clinical_diagnosis'] ?? null,
                'hemoglobin_level' => $validated['hemoglobin_level'] ?? null,
                'blood_type' => $validated['blood_type'],
                'rhesus' => $validated['rhesus'],
                'component_type' => $validated['component_type'],
                'bags_needed' => $validated['bags_needed'],
                'bags_fulfilled' => 0,
                'urgency_level' => $validated['urgency_level'],
                'needed_at' => $validated['needed_at'] ?? null,
                'additional_notes' => $validated['additional_notes'] ?? null,
                'doctor_name' => $validated['doctor_name'] ?? null,
                'status' => $isDraft
                    ? BloodRequestStatus::DRAFT->value
                    : BloodRequestStatus::PENDING_VERIFICATION->value,
                'submitted_at' => $isDraft ? null : now(),
            ], $fileData));
        });

        $message = $isDraft
            ? "Draf permohonan darah ({$bloodRequest->request_code}) berhasil disimpan. Anda dapat melengkapi berkas dan mengirimnya kapan saja."
            : "Permohonan darah darurat ({$bloodRequest->request_code}) telah berhasil diajukan dan masuk ke antrean verifikasi medis UDD.";

        return redirect()
            ->route('emergency-requests.show', $bloodRequest)
            ->with('success', $message);
    }

    /**
     * Display the specified emergency blood request detail for its owner.
     */
    public function show(Request $request, BloodRequest $bloodRequest): View
    {
        if ($bloodRequest->requester_id !== $request->user()->id) {
            abort(403, 'Anda hanya dapat mengakses rincian permohonan yang Anda ajukan sendiri.');
        }

        $bloodRequest->load(['facility.region', 'verifier']);

        return view('emergency-requests.show', [
            'bloodRequest' => $bloodRequest,
        ]);
    }

    /**
     * Stream the protected doctor referral document to authorized medical staff or the owning requester.
     */
    public function referralLetter(Request $request, BloodRequest $bloodRequest): BinaryFileResponse
    {
        $user = $request->user();

        $isOwner = $user->isSeeker() && $bloodRequest->requester_id === $user->id;
        $isAuthorizedStaff = $user->isUddStaff() || $user->isHospitalStaff() || $user->isAdmin();

        if (! $isOwner && ! $isAuthorizedStaff) {
            abort(403, 'Dokumen medis pasien bersifat rahasia dan hanya dapat diakses oleh pihak berwenang.');
        }

        if (! $bloodRequest->hasReferralLetter() || ! Storage::disk('local')->exists($bloodRequest->referral_letter_path)) {
            abort(404, 'Berkas surat rujukan dokter tidak ditemukan.');
        }

        $absolutePath = Storage::disk('local')->path($bloodRequest->referral_letter_path);
        $safeFilename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', (string) ($bloodRequest->referral_letter_original_name ?: 'surat-rujukan.pdf'));

        return response()->file($absolutePath, [
            'Content-Type' => $bloodRequest->referral_letter_mime ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="'.$safeFilename.'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
