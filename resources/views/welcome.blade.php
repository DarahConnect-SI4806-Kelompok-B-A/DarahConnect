@extends('layouts.app')

@section('title', 'Jejaring Darurat & Pelayanan Donor Darah Terpadu')

@section('content')
    <section class="dc-hero-surface py-5">
        <div class="container py-lg-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="dc-pill dc-pill-critical mb-3">
                        <i class="bi bi-heart-pulse-fill"></i>
                        <span>Emergency Blood Network Indonesia</span>
                    </div>
                    <h1 class="dc-display mb-3">
                        Menghubungkan Kebutuhan Transfusi Kritis dengan Pendonor Siaga.
                    </h1>
                    <p class="dc-lead mb-4">
                        Platform kolaborasi terverifikasi antara keluarga pasien, relawan pendonor sukarela, Unit Donor Darah (UDD) PMI, dan Bank Darah Rumah Sakit (BDRS) untuk respons pemenuhan darah yang cepat, aman, dan terukur.
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        @if (Route::has('auth.register'))
                            <a href="{{ route('auth.register') }}" class="btn btn-dc-primary btn-lg">
                                <i class="bi bi-droplet-half"></i>
                                Gabung Sekarang
                            </a>
                        @else
                            <a href="#jejaring-faskes" class="btn btn-dc-primary btn-lg">
                                <i class="bi bi-hospital"></i>
                                Lihat Jejaring Faskes
                            </a>
                        @endif

                        @if (Route::has('discovery.index'))
                            <a href="{{ route('discovery.index') }}" class="btn btn-dc-secondary btn-lg">
                                <i class="bi bi-compass"></i>
                                Eksplorasi Kebutuhan Aktif
                            </a>
                        @else
                            <a href="#alur-layanan" class="btn btn-dc-secondary btn-lg">
                                <i class="bi bi-shield-check"></i>
                                Pelajari Alur Medis
                            </a>
                        @endif
                    </div>

                    <div class="row g-3 mt-4 pt-2">
                        <div class="col-4">
                            <div class="dc-card p-3">
                                <div class="dc-title-2 text-danger dc-tabular mb-0">{{ $uddCount }}</div>
                                <div class="dc-caption">Gedung UDD PMI Mitra</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dc-card p-3">
                                <div class="dc-title-2 dc-tabular mb-0">{{ $hospitalCount }}</div>
                                <div class="dc-caption">RS Rujukan Terhubung</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dc-card p-3">
                                <div class="dc-title-2 dc-tabular mb-0">{{ $regionCount }}</div>
                                <div class="dc-caption">Wilayah Layanan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="dc-card p-4 p-md-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="dc-eyebrow">Ekosistem Terverifikasi</div>
                                <h2 class="dc-title-3 mb-0 mt-1"> Komponen &amp; Standar Layanan</h2>
                            </div>
                            <span class="dc-blood-badge">O+</span>
                        </div>
                        <p class="dc-caption mb-3">
                            Setiap permohonan darah darurat diverifikasi oleh petugas medis UDD berdasarkan surat rujukan dokter sebelum dipublikasikan ke komunitas pendonor.
                        </p>

                        <div class="vstack gap-2">
                            <div class="p-3 rounded-4 bg-light border d-flex align-items-start gap-3">
                                <div class="text-danger fs-4"><i class="bi bi-file-earmark-medical-fill"></i></div>
                                <div>
                                    <div class="fw-semibold small">Validasi Klinis Berlapis</div>
                                    <div class="dc-caption">Pemeriksaan rujukan klinis, kadar Hb, dan kompatibilitas komponen (WB, PRC, TC, FFP).</div>
                                </div>
                            </div>
                            <div class="p-3 rounded-4 bg-light border d-flex align-items-start gap-3">
                                <div class="text-danger fs-4"><i class="bi bi-geo-alt-fill"></i></div>
                                <div>
                                    <div class="fw-semibold small">Pencocokan Cerdas &amp; Aman Privasi</div>
                                    <div class="dc-caption">Rekomendasi radius faskes terdekat &amp; ruang koordinasi titik temu tanpa ekspos nomor pribadi.</div>
                                </div>
                            </div>
                            <div class="p-3 rounded-4 bg-light border d-flex align-items-start gap-3">
                                <div class="text-danger fs-4"><i class="bi bi-qr-code-scan"></i></div>
                                <div>
                                    <div class="fw-semibold small">Reservasi Jadwal &amp; Skrining Mandiri</div>
                                    <div class="dc-caption">Pemesanan sesi donor gedung UDD maupun Mobile Unit disertai anamnesis digital pra-donor.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="alur-layanan" class="py-5">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
                <div>
                    <div class="dc-eyebrow">Cara Kerja Platform</div>
                    <h2 class="dc-title-1 mb-0 mt-1">Dua Jalur Kemanusiaan dalam Satu Sistem</h2>
                </div>
                <div class="dc-segmented" data-dc-segmented id="streamSwitcher" role="tablist" aria-label="Pilih Alur Layanan">
                    <button type="button" class="dc-segmented-btn active" role="tab" aria-selected="true" data-value="emergency">
                        Darurat Pasien
                    </button>
                    <button type="button" class="dc-segmented-btn" role="tab" aria-selected="false" data-value="scheduled">
                        Donor Terjadwal
                    </button>
                </div>
            </div>

            <div id="panel-emergency" class="row g-4">
                <div class="col-md-3">
                    <div class="dc-card h-100 p-4">
                        <span class="dc-pill dc-pill-critical mb-3">Langkah 01</span>
                        <h3 class="dc-title-3">Ajukan Permohonan</h3>
                        <p class="dc-caption mb-0">Keluarga pasien mengisi data klinis, golongan darah, faskes rujukan, serta melampirkan surat rujukan dokter.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dc-card h-100 p-4">
                        <span class="dc-pill dc-pill-critical mb-3">Langkah 02</span>
                        <h3 class="dc-title-3">Verifikasi UDD</h3>
                        <p class="dc-caption mb-0">Petugas UDD meninjau keabsahan berkas medis dan menyetujui masa aktif penayangan permohonan.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dc-card h-100 p-4">
                        <span class="dc-pill dc-pill-critical mb-3">Langkah 03</span>
                        <h3 class="dc-title-3">Pencocokan Donor</h3>
                        <p class="dc-caption mb-0">Pendonor yang kompatibel secara aturan transfusi dan dekat dengan faskes menerima rekomendasi prioritas.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dc-card h-100 p-4">
                        <span class="dc-pill dc-pill-critical mb-3">Langkah 04</span>
                        <h3 class="dc-title-3">Komitmen &amp; Koordinasi</h3>
                        <p class="dc-caption mb-0">Pendonor mengirim estimasi waktu hadir dan berkoordinasi titik temu di rumah sakit secara aman.</p>
                    </div>
                </div>
            </div>

            <div id="panel-scheduled" class="row g-4 d-none">
                <div class="col-md-4">
                    <div class="dc-card h-100 p-4">
                        <span class="dc-pill dc-pill-neutral mb-3">Tahap 01</span>
                        <h3 class="dc-title-3">Pilih Lokasi &amp; Sesi</h3>
                        <p class="dc-caption mb-0">Pilih kuota sesi harian di Gedung UDD PMI atau agenda kegiatan donor keliling (Mobile Unit) terdekat.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dc-card h-100 p-4">
                        <span class="dc-pill dc-pill-neutral mb-3">Tahap 02</span>
                        <h3 class="dc-title-3">Anamnesis Mandiri</h3>
                        <p class="dc-caption mb-0">Jawab 20 kuesioner skrining kesehatan pra-donor untuk memastikan kesiapan kondisi fisik Anda.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dc-card h-100 p-4">
                        <span class="dc-pill dc-pill-neutral mb-3">Tahap 03</span>
                        <h3 class="dc-title-3">Tiket Digital QR</h3>
                        <p class="dc-caption mb-0">Dapatkan tiket reservasi ber-QR Code untuk proses kedatangan terjadwal yang tertib tanpa antre panjang.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="jejaring-faskes" class="py-4">
        <div class="container">
            <div class="dc-card p-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
                    <div>
                        <div class="dc-eyebrow">Jejaring Fasilitas Kesehatan</div>
                        <h2 class="dc-title-2 mb-0 mt-1">Mitra UDD PMI &amp; Rumah Sakit Rujukan</h2>
                    </div>
                    <span class="dc-pill dc-pill-success">
                        <i class="bi bi-check2-circle"></i>
                        {{ $facilities->count() }} Fasilitas Aktif
                    </span>
                </div>

                <div class="row g-3">
                    @foreach ($facilities as $facility)
                        <div class="col-md-6 col-xl-4">
                            <div class="p-3 rounded-4 border bg-light bg-opacity-50 h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                        <span class="dc-pill {{ $facility->type === 'udd' ? 'dc-pill-critical' : 'dc-pill-neutral' }}">
                                            {{ $facility->type === 'udd' ? 'UDD PMI' : 'RS Rujukan' }}
                                        </span>
                                        <span class="dc-caption fw-medium">{{ $facility->region?->name }}</span>
                                    </div>
                                    <div class="fw-semibold mb-1">{{ $facility->name }}</div>
                                    <div class="dc-caption mb-2">{{ $facility->address }}</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top dc-caption">
                                    <span><i class="bi bi-clock me-1"></i>{{ $facility->operating_hours ?? '24 Jam' }}</span>
                                    <span><i class="bi bi-telephone me-1"></i>{{ $facility->phone ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const switcher = document.getElementById('streamSwitcher');
        const emergencyPanel = document.getElementById('panel-emergency');
        const scheduledPanel = document.getElementById('panel-scheduled');
        if (!switcher || !emergencyPanel || !scheduledPanel) return;

        switcher.addEventListener('dc:segment-change', (e) => {
            const isEmergency = e.detail.value === 'emergency';
            emergencyPanel.classList.toggle('d-none', !isEmergency);
            scheduledPanel.classList.toggle('d-none', isEmergency);
        });
    });
</script>
@endpush
