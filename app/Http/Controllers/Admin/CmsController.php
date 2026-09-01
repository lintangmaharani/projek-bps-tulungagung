<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsContent;

class CmsController extends Controller
{
    public function index()
    {
        $defaults = [
            // Beranda / Hero
            'hero_title' => 'Program PKL & Magang',
            'hero_desc' => 'Portal terpadu pendaftaran PKL bagi pelajar dan mahasiswa untuk belajar langsung pengelolaan data statistik resmi negara dalam lingkungan kerja yang profesional.',
            'hero_btn_text' => 'Daftar Sekarang →',
            'hero_btn_url' => '',
            
            // Tentang
            'about_title' => 'Program PKL & Magang',
            'about_desc_1' => 'Program Magang di Badan Pusat Statistik (BPS) Kabupaten Tulungagung adalah wadah pembelajaran dunia kerja bagi pelajar maupun mahasiswa untuk mengenal lebih dekat proses bisnis penyediaan data statistik resmi pemerintah.',
            'about_desc_2' => 'Peserta akan ditempatkan di berbagai divisi kerja untuk membantu kegiatan perkantoran dan pengolahan data.',
            'about_benefit_1' => 'Pengalaman kerja nyata di instansi pemerintah.',
            'about_benefit_2' => 'Pemahaman seputar teknik pengolahan dan pelayanan data statistik.',
            'about_benefit_3' => 'Peningkatan keterampilan kerja (soft skills dan hard skills).',
            'about_benefit_4' => 'Sertifikat resmi magang dari BPS Kabupaten Tulungagung.',

            // FAQ
            'faq_title' => 'Pertanyaan Umum (FAQ)',
            'faq_subtitle' => 'Informasi praktis seputar pendaftaran dan pelaksanaan magang.',
            'faq_q_1' => 'Berapa minimal dan maksimal durasi untuk kegiatan magang atau PKL?',
            'faq_a_1' => 'Durasi magang umumnya disesuaikan dengan ketentuan dari institusi pendidikan asal peserta, yang rata-rata berkisar antara 1 hingga 3 bulan.',
            'faq_q_2' => 'Dokumen apa saja yang perlu disiapkan untuk mendaftar?',
            'faq_a_2' => 'Dokumen utama yang diperlukan meliputi surat pengantar resmi dari sekolah/universitas, proposal magang, transkrip nilai/rapor terakhir, serta CV terbaru.',
            'faq_q_3' => 'Apakah peserta magang mendapatkan fasilitas atau uang saku?',
            'faq_a_3' => 'Program magang dan PKL bersifat sukarela untuk keperluan pembelajaran, sehingga instansi tidak menyediakan gaji/uang saku khusus, namun peserta mendapatkan bimbingan dan sertifikat resmi.',
            'faq_q_4' => 'Apakah pendaftaran magang dikenakan biaya?',
            'faq_a_4' => 'Tidak. Seluruh proses pendaftaran, seleksi, hingga pelaksanaan magang di BPS Kabupaten Tulungagung 100% GRATIS tanpa dipungut biaya apapun.',
            'faq_q_5' => 'Bagaimana cara mendapatkan sertifikat magang?',
            'faq_a_5' => 'Sertifikat magang diunggah oleh admin setelah periode magang selesai dan jurnal harian Anda terisi lengkap. Anda dapat mengunduhnya melalui dashboard.',
        ];

        foreach ($defaults as $key => $val) {
            CmsContent::firstOrCreate(['key' => $key], ['value' => $val]);
        }

        $contents = CmsContent::all()->pluck('value', 'key');
        return view('admin.cms.index', compact('contents'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            CmsContent::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        return redirect()->back()->with('success', 'Konten Beranda, Tentang, dan FAQ berhasil diperbarui!');
    }
}