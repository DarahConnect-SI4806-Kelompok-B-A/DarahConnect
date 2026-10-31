<?php

namespace App\Http\Requests\Auth;

use App\Enums\BloodType;
use App\Enums\Rhesus;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $rawPhone = (string) $this->input('phone', '');
        $normalizedPhone = preg_replace('/[\s\-\(\)]+/', '', $rawPhone);

        if (is_string($normalizedPhone) && str_starts_with($normalizedPhone, '+62')) {
            $normalizedPhone = '0'.substr($normalizedPhone, 3);
        } elseif (is_string($normalizedPhone) && str_starts_with($normalizedPhone, '62')) {
            $normalizedPhone = '0'.substr($normalizedPhone, 2);
        }

        $this->merge([
            'name' => trim((string) $this->input('name', '')),
            'email' => Str::lower(trim((string) $this->input('email', ''))),
            'phone' => $normalizedPhone,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $isDonor = $this->input('role') === UserRole::PENDONOR->value;

        return [
            'role' => ['required', 'string', Rule::in(UserRole::publicRegistrationValues())],
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^08[1-9][0-9]{7,12}$/', 'unique:users,phone'],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],

            // Donor-specific biological & eligibility profile
            'blood_type' => [
                Rule::requiredIf($isDonor),
                'nullable',
                Rule::in(BloodType::values()),
            ],
            'rhesus' => [
                Rule::requiredIf($isDonor),
                'nullable',
                Rule::in(Rhesus::values()),
            ],
            'gender' => [
                Rule::requiredIf($isDonor),
                'nullable',
                Rule::in(['L', 'P']),
            ],
            'birth_date' => [
                Rule::requiredIf($isDonor),
                'nullable',
                'date',
                'before_or_equal:'.now()->subYears(17)->toDateString(),
                'after:'.now()->subYears(65)->toDateString(),
            ],
            'weight_kg' => [
                Rule::requiredIf($isDonor),
                'nullable',
                'integer',
                'min:45',
                'max:250',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'role.required' => 'Silakan pilih peran akun Anda terlebih dahulu.',
            'role.in' => 'Peran yang dipilih tidak valid untuk pendaftaran mandiri.',
            'name.required' => 'Nama lengkap sesuai identitas wajib diisi.',
            'name.min' => 'Nama lengkap minimal terdiri dari 3 karakter.',
            'email.required' => 'Alamat email aktif wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar pada akun lain.',
            'phone.required' => 'Nomor ponsel aktif wajib diisi untuk koordinasi darurat.',
            'phone.regex' => 'Masukkan nomor ponsel Indonesia yang valid (contoh: 081234567890).',
            'phone.unique' => 'Nomor ponsel ini sudah terdaftar pada akun lain.',
            'region_id.required' => 'Wilayah kabupaten/kota domisili wajib dipilih.',
            'region_id.exists' => 'Wilayah domisili yang dipilih tidak ditemukan.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'blood_type.required' => 'Golongan darah ABO wajib dipilih untuk akun Pendonor.',
            'rhesus.required' => 'Faktor Rhesus (+ / -) wajib dipilih untuk akun Pendonor.',
            'gender.required' => 'Jenis kelamin wajib dipilih untuk akun Pendonor.',
            'birth_date.required' => 'Tanggal lahir wajib diisi untuk memvalidasi batas usia pendonor.',
            'birth_date.before_or_equal' => 'Calon pendonor wajib berusia minimal 17 tahun sesuai ketentuan medis PMI.',
            'birth_date.after' => 'Usia pendaftaran mandiri pendonor dibatasi hingga 65 tahun.',
            'weight_kg.required' => 'Berat badan wajib diisi untuk akun Pendonor.',
            'weight_kg.min' => 'Berat badan minimal calon pendonor adalah 45 kg sesuai standar keselamatan donor.',
        ];
    }
}
