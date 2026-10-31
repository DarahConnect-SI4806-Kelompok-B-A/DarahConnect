<?php

namespace Database\Seeders;

use App\Enums\BloodComponent;
use App\Enums\BloodRequestStatus;
use App\Enums\BloodType;
use App\Enums\Rhesus;
use App\Enums\UrgencyLevel;
use App\Enums\UserRole;
use App\Models\BloodRequest;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BloodRequestSeeder extends Seeder
{
    /**
     * Seed realistic sample emergency blood requests across lifecycle states.
     */
    public function run(): void
    {
        $seeker = User::where('role', UserRole::PENCARI_DARAH->value)->first();
        $uddStaff = User::where('role', UserRole::PETUGAS_UDD->value)->first();
        $rshs = Facility::where('code', 'RS-BDG-01')->first() ?? Facility::hospitals()->first();
        $borromeus = Facility::where('code', 'RS-BDG-02')->first() ?? $rshs;

        if (! $seeker || ! $rshs) {
            return;
        }

        $sampleDocPath = 'private/referral-letters/seed/surat-rujukan-contoh.pdf';
        if (! Storage::disk('local')->exists($sampleDocPath)) {
            Storage::disk('local')->put($sampleDocPath, $this->minimalReferralPdf());
        }
        $sampleDocSize = Storage::disk('local')->size($sampleDocPath);

        $samples = [
            [
                'request_code' => 'DC-REQ-SEED01',
                'requester_id' => $seeker->id,
                'facility_id' => $rshs->id,
                'patient_name' => 'Tn. Suryadi Kartawijaya',
                'patient_age' => 54,
                'patient_gender' => 'L',
                'patient_relationship' => 'Keluarga Inti (Orang Tua/Suami/Istri/Anak)',
                'medical_record_number' => 'RM-2026-88412',
                'ward_room' => 'Gedung Kemuning Lt. 3 - Ruang HCU Bed 04',
                'clinical_diagnosis' => 'Anemia Gravis et causa Perdarahan Saluran Cerna Atas',
                'hemoglobin_level' => 5.8,
                'blood_type' => BloodType::O->value,
                'rhesus' => Rhesus::POSITIVE->value,
                'component_type' => BloodComponent::PRC->value,
                'bags_needed' => 3,
                'bags_fulfilled' => 0,
                'urgency_level' => UrgencyLevel::CRITICAL->value,
                'needed_at' => now()->addHours(6),
                'additional_notes' => 'Pasien dijadwalkan endoskopi segera setelah kadar Hb mencapai minimal 8.0 g/dL.',
                'doctor_name' => 'dr. Arief Budiman, Sp.PD-KGEH',
                'referral_letter_path' => $sampleDocPath,
                'referral_letter_original_name' => 'Surat_Rujukan_Transfusi_Suryadi.pdf',
                'referral_letter_mime' => 'application/pdf',
                'referral_letter_size' => $sampleDocSize,
                'status' => BloodRequestStatus::PENDING_VERIFICATION->value,
                'submitted_at' => now()->subMinutes(35),
            ],
            [
                'request_code' => 'DC-REQ-SEED02',
                'requester_id' => $seeker->id,
                'facility_id' => $borromeus->id,
                'patient_name' => 'Ny. Lestari Wulandari',
                'patient_age' => 31,
                'patient_gender' => 'P',
                'patient_relationship' => 'Kerabat / Saudara Pasien',
                'medical_record_number' => 'RM-2026-55209',
                'ward_room' => 'Ruang Bersalin / VK Lantai 2',
                'clinical_diagnosis' => 'Perdarahan Pascapersalinan (Hemoragia Postpartum)',
                'hemoglobin_level' => 6.4,
                'blood_type' => BloodType::O->value,
                'rhesus' => Rhesus::POSITIVE->value,
                'component_type' => BloodComponent::PRC->value,
                'bags_needed' => 2,
                'bags_fulfilled' => 0,
                'urgency_level' => UrgencyLevel::CRITICAL->value,
                'needed_at' => now()->addHours(8),
                'additional_notes' => 'Sampel darah pasien sudah berada di instalasi BDRS rumah sakit.',
                'doctor_name' => 'dr. Maya Puspita, Sp.OG',
                'referral_letter_path' => $sampleDocPath,
                'referral_letter_original_name' => 'Rujukan_Medis_Lestari.pdf',
                'referral_letter_mime' => 'application/pdf',
                'referral_letter_size' => $sampleDocSize,
                'status' => BloodRequestStatus::ACTIVE->value,
                'submitted_at' => now()->subHours(2),
                'verified_by' => $uddStaff?->id,
                'verified_at' => now()->subMinutes(90),
                'verification_notes' => 'Berkas rujukan BDRS lengkap dan tervalidasi urgensi kritis.',
                'expires_at' => now()->addDays(2),
            ],
            [
                'request_code' => 'DC-REQ-SEED03',
                'requester_id' => $seeker->id,
                'facility_id' => $rshs->id,
                'patient_name' => 'An. Rafi Pratama',
                'patient_age' => 11,
                'patient_gender' => 'L',
                'patient_relationship' => 'Keluarga Inti (Orang Tua/Suami/Istri/Anak)',
                'ward_room' => 'Ruang Anak Kenanga Kamar 205',
                'clinical_diagnosis' => 'Demam Berdarah Dengue Derajat III dengan Trombositopenia',
                'hemoglobin_level' => 11.2,
                'blood_type' => BloodType::A->value,
                'rhesus' => Rhesus::POSITIVE->value,
                'component_type' => BloodComponent::TC->value,
                'bags_needed' => 4,
                'bags_fulfilled' => 0,
                'urgency_level' => UrgencyLevel::URGENT->value,
                'needed_at' => now()->addHours(18),
                'doctor_name' => 'dr. Karina Putri, Sp.A',
                'status' => BloodRequestStatus::DRAFT->value,
            ],
        ];

        foreach ($samples as $sample) {
            BloodRequest::updateOrCreate(
                ['request_code' => $sample['request_code']],
                $sample
            );
        }
    }

    /**
     * Generate a valid, self-contained single-page PDF document for referral preview testing.
     */
    private function minimalReferralPdf(): string
    {
        $objects = [
            "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n",
            "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n",
            "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>\nendobj\n",
            "4 0 obj\n<< /Length 215 >>\nstream\nBT\n/F1 16 Tf\n50 770 Td\n(SURAT RUJUKAN PERMINTAAN TRANSFUSI DARAH) Tj\n/F1 11 Tf\n0 -32 Td\n(Dokumen Verifikasi Klinis Instalasi Rawat Inap / BDRS) Tj\n0 -22 Td\n(DarahConnect Emergency Blood Network - Berkas Contoh Resmi) Tj\nET\nendstream\nendobj\n",
            "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>\nendobj\n",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $obj) {
            $offsets[] = strlen($pdf);
            $pdf .= $obj;
        }

        $xrefOffset = strlen($pdf);
        $count = count($objects) + 1;
        $pdf .= "xref\n0 {$count}\n0000000000 65535 f \n";

        for ($i = 1; $i < $count; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n<< /Size {$count} /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }
}
