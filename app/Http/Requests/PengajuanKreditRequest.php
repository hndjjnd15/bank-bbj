<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PengajuanKreditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Nominal dikirim dengan pemisah ribuan (contoh: 50.000.000), jadi dirapikan
    // menjadi angka saja sebelum divalidasi. Nilai ini juga yang dikembalikan
    // sebagai old() saat validasi gagal.
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nominal_kredit' => preg_replace('/[^0-9]/', '', (string) $this->input('nominal_kredit', '')),
        ]);
    }

    public function rules(): array
    {
        $produkUsaha = implode(',', config('pengajuan-kredit.produk_usaha', []));
        $produkPekerjaan = implode(',', config('pengajuan-kredit.produk_pekerjaan', []));
        $mimes = 'mimes:'.config('pengajuan-kredit.berkas_mimes', 'pdf,jpg,jpeg,png');
        $maks = 'max:'.config('pengajuan-kredit.berkas_maks_kb', 5120);

        // Aturan dokumen yang sama untuk semua input unggahan
        $dokumen = ['file', $mimes, $maks];

        return [
            // ===== Informasi pengajuan =====
            'email' => ['required', 'email', 'max:150'],
            'nominal_kredit' => ['required', 'numeric', 'min:1'],
            'jenis_kredit' => ['required', Rule::in(array_keys(config('pengajuan-kredit.produk', [])))],

            // ===== Data pribadi =====
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'digits:16'],
            'jenis_kelamin' => ['required', Rule::in(config('pengajuan-kredit.jenis_kelamin', []))],
            'agama' => ['required', Rule::in(config('pengajuan-kredit.agama', []))],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'alamat_ktp' => ['required', 'string', 'max:500'],
            'domisili' => ['required', Rule::in(array_keys(config('pengajuan-kredit.domisili', [])))],
            'alamat_domisili' => ['required_if:domisili,lain', 'nullable', 'string', 'max:500'],
            'ktp' => ['required', ...$dokumen],
            'npwp' => ['required', 'string', 'max:30'],
            'file_npwp' => ['required', ...$dokumen],
            'no_hp' => [
                'required', 'string', 'max:20',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $digit = preg_replace('/[^0-9]/', '', (string) $value);

                    if (strlen($digit) < 9 || strlen($digit) > 15) {
                        $fail('Nomor HP/WA tidak valid.');
                    }
                },
            ],

            // ===== Data usaha (hanya untuk UMKM dan Petani/Peternak) =====
            'nama_usaha' => ['exclude_unless:jenis_kredit,'.$produkUsaha, 'required', 'string', 'max:150'],
            'lokasi_usaha' => ['exclude_unless:jenis_kredit,'.$produkUsaha, 'required', 'string', 'max:500'],
            'file_upload' => ['exclude_unless:jenis_kredit,'.$produkUsaha, 'required', ...$dokumen],

            // ===== Data pekerjaan (produk pegawai) =====
            'instansi' => ['exclude_unless:jenis_kredit,'.$produkPekerjaan, 'required', 'string', 'max:150'],
            'jabatan' => ['exclude_unless:jenis_kredit,'.$produkPekerjaan, 'required', 'string', 'max:100'],

            // ===== Status pernikahan =====
            'status_pernikahan' => ['required', Rule::in(array_keys(config('pengajuan-kredit.status_pernikahan', [])))],

            // Menikah: data pasangan
            'nama_pasangan' => ['exclude_unless:status_pernikahan,menikah', 'required', 'string', 'max:100'],
            'hubungan_pasangan' => ['exclude_unless:status_pernikahan,menikah', 'required', 'string', 'max:50'],
            'nik_pasangan' => ['exclude_unless:status_pernikahan,menikah', 'required', 'string', 'max:20'],
            'agama_pasangan' => ['exclude_unless:status_pernikahan,menikah', 'required', Rule::in(config('pengajuan-kredit.agama', []))],
            'tempat_lahir_pasangan' => ['exclude_unless:status_pernikahan,menikah', 'required', 'string', 'max:100'],
            'tanggal_lahir_pasangan' => ['exclude_unless:status_pernikahan,menikah', 'required', 'date', 'before:today'],
            'ktp_pasangan' => ['exclude_unless:status_pernikahan,menikah', 'required', ...$dokumen],
            'buku_nikah' => ['exclude_unless:status_pernikahan,menikah', 'required', ...$dokumen],
            'npwp_pasangan' => ['exclude_unless:status_pernikahan,menikah', 'required', 'string', 'max:30'],
            'file_npwp_pasangan' => ['exclude_unless:status_pernikahan,menikah', 'required', ...$dokumen],
            'hp_pasangan' => ['exclude_unless:status_pernikahan,menikah', 'required', 'string', 'max:20'],

            // Belum menikah: data orang tua
            'nama_orang_tua' => ['exclude_unless:status_pernikahan,belum', 'required', 'string', 'max:100'],
            'hubungan_orang_tua' => ['exclude_unless:status_pernikahan,belum', 'required', 'string', 'max:50'],
            'nik_orang_tua' => ['exclude_unless:status_pernikahan,belum', 'required', 'string', 'max:20'],
            'agama_orang_tua' => ['exclude_unless:status_pernikahan,belum', 'required', Rule::in(config('pengajuan-kredit.agama', []))],
            'tempat_lahir_orang_tua' => ['exclude_unless:status_pernikahan,belum', 'required', 'string', 'max:100'],
            'tanggal_lahir_orang_tua' => ['exclude_unless:status_pernikahan,belum', 'required', 'date', 'before:today'],
            'ktp_orang_tua' => ['exclude_unless:status_pernikahan,belum', 'required', ...$dokumen],
            'npwp_orang_tua' => ['exclude_unless:status_pernikahan,belum', 'required', 'string', 'max:30'],
            'file_npwp_orang_tua' => ['exclude_unless:status_pernikahan,belum', 'required', ...$dokumen],
            'hp_orang_tua' => ['exclude_unless:status_pernikahan,belum', 'required', 'string', 'max:20'],

            // Cerai / cerai mati: akta pendukung
            'akta_cerai_kematian' => ['exclude_unless:status_pernikahan,cerai', 'required', ...$dokumen],

            // ===== Persetujuan =====
            'persetujuan' => ['accepted'],
            'persetujuan_ideb' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'required_if' => ':attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
            'digits' => ':attribute harus berisi :digits digit angka.',
            'date' => 'Format :attribute tidak valid.',
            'before' => ':attribute tidak valid.',
            'numeric' => ':attribute harus berupa angka.',
            'min' => ':attribute minimal :min.',
            'max' => ':attribute terlalu panjang atau terlalu besar (maksimal :max).',
            'in' => 'Pilihan :attribute tidak valid.',
            'file' => ':attribute harus berupa dokumen.',
            'mimes' => ':attribute harus berformat PDF, JPG, JPEG, atau PNG.',
            'accepted' => 'Anda harus menyetujui pernyataan ini untuk mengirim pengajuan.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'Email',
            'nominal_kredit' => 'Nominal pengajuan kredit',
            'jenis_kredit' => 'Jenis produk kredit',
            'nama_lengkap' => 'Nama lengkap',
            'nik' => 'Nomor KTP/NIK',
            'jenis_kelamin' => 'Jenis kelamin',
            'agama' => 'Agama',
            'tempat_lahir' => 'Tempat lahir',
            'tanggal_lahir' => 'Tanggal lahir',
            'alamat_ktp' => 'Alamat KTP',
            'domisili' => 'Alamat domisili',
            'alamat_domisili' => 'Alamat domisili',
            'ktp' => 'Dokumen KTP',
            'npwp' => 'NPWP',
            'file_npwp' => 'Dokumen NPWP',
            'no_hp' => 'Nomor HP/WA',
            'nama_usaha' => 'Nama usaha',
            'lokasi_usaha' => 'Lokasi usaha',
            'file_upload' => 'Dokumen SKU/NIB',
            'instansi' => 'Nama instansi/perusahaan',
            'jabatan' => 'Jabatan',
            'status_pernikahan' => 'Status pernikahan',
            'nama_pasangan' => 'Nama pasangan',
            'hubungan_pasangan' => 'Hubungan dengan pemohon',
            'nik_pasangan' => 'NIK pasangan',
            'agama_pasangan' => 'Agama pasangan',
            'tempat_lahir_pasangan' => 'Tempat lahir pasangan',
            'tanggal_lahir_pasangan' => 'Tanggal lahir pasangan',
            'ktp_pasangan' => 'Dokumen KTP pasangan',
            'buku_nikah' => 'Dokumen buku nikah',
            'npwp_pasangan' => 'NPWP pasangan',
            'file_npwp_pasangan' => 'Dokumen NPWP pasangan',
            'hp_pasangan' => 'Nomor HP/WA pasangan',
            'nama_orang_tua' => 'Nama orang tua',
            'hubungan_orang_tua' => 'Hubungan orang tua dengan pemohon',
            'nik_orang_tua' => 'NIK orang tua',
            'agama_orang_tua' => 'Agama orang tua',
            'tempat_lahir_orang_tua' => 'Tempat lahir orang tua',
            'tanggal_lahir_orang_tua' => 'Tanggal lahir orang tua',
            'ktp_orang_tua' => 'Dokumen KTP orang tua',
            'npwp_orang_tua' => 'NPWP orang tua',
            'file_npwp_orang_tua' => 'Dokumen NPWP orang tua',
            'hp_orang_tua' => 'Nomor HP/WA orang tua',
            'akta_cerai_kematian' => 'Dokumen akta cerai/akta kematian',
            'persetujuan' => 'Persetujuan persyaratan',
            'persetujuan_ideb' => 'Persetujuan pengecekan IDEB OJK',
        ];
    }
}
