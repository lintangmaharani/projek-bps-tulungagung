@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ activeTab: 'beranda' }">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">CMS Landing Page</h1>
            <p class="text-xs text-slate-500">Kelola teks untuk bagian Beranda, Tentang, dan FAQ.</p>
        </div>
        <a href="{{ url('/') }}" target="_blank" class="text-xs font-semibold text-bpsBlue bg-blue-50 px-4 py-2 rounded-xl hover:bg-blue-100 transition flex items-center space-x-1">
            <span>Lihat Landing Page</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.cms.update') }}" method="POST">
        @csrf

        <!-- TAB NAVIGATION (Hanya 3 Menu) -->
        <div class="grid grid-cols-3 gap-2 border-b border-slate-200 mb-6 bg-slate-900/5 p-1.5 rounded-2xl">
            <button type="button" @click="activeTab = 'beranda'" :class="activeTab === 'beranda' ? 'bg-bpsBlue text-white shadow' : 'text-slate-600 hover:bg-slate-200/60'" class="px-4 py-2.5 text-xs font-bold rounded-xl transition text-center">Beranda</button>
            <button type="button" @click="activeTab = 'tentang'" :class="activeTab === 'tentang' ? 'bg-bpsBlue text-white shadow' : 'text-slate-600 hover:bg-slate-200/60'" class="px-4 py-2.5 text-xs font-bold rounded-xl transition text-center">Tentang</button>
            <button type="button" @click="activeTab = 'faq'" :class="activeTab === 'faq' ? 'bg-bpsBlue text-white shadow' : 'text-slate-600 hover:bg-slate-200/60'" class="px-4 py-2.5 text-xs font-bold rounded-xl transition text-center">FAQ</button>
        </div>

        <!-- ================= 1. BERANDA ================= -->
        <div x-show="activeTab === 'beranda'" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-bpsBlue uppercase tracking-wider">Bagian Beranda (Hero)</h2>
            <div class="space-y-1">
                <label class="text-xs font-semibold text-slate-700">Judul Utama Hero</label>
                <input type="text" name="hero_title" value="{{ $contents['hero_title'] ?? '' }}" class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-semibold text-slate-700">Deskripsi Hero</label>
                <textarea name="hero_desc" rows="3" class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5">{{ $contents['hero_desc'] ?? '' }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-700">Teks Tombol Utama</label>
                    <input type="text" name="hero_btn_text" value="{{ $contents['hero_btn_text'] ?? '' }}" class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-700">URL Tombol Utama (Opsional)</label>
                    <input type="text" name="hero_btn_url" value="{{ $contents['hero_btn_url'] ?? '' }}" class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5">
                </div>
            </div>
            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-bpsBlue hover:bg-blue-900 text-white text-xs font-bold rounded-xl shadow transition">Simpan Perubahan</button>
            </div>
        </div>

        <!-- ================= 2. TENTANG ================= -->
        <div x-show="activeTab === 'tentang'" style="display: none;" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-bpsBlue uppercase tracking-wider">Bagian Tentang Program Magang</h2>
            <div class="space-y-1">
                <label class="text-xs font-semibold text-slate-700">Judul Tentang</label>
                <input type="text" name="about_title" value="{{ $contents['about_title'] ?? '' }}" class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-semibold text-slate-700">Paragraf Deskripsi 1</label>
                <textarea name="about_desc_1" rows="3" class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5">{{ $contents['about_desc_1'] ?? '' }}</textarea>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-semibold text-slate-700">Paragraf Deskripsi 2</label>
                <textarea name="about_desc_2" rows="3" class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5">{{ $contents['about_desc_2'] ?? '' }}</textarea>
            </div>
            <div class="space-y-3">
                <label class="text-xs font-semibold text-slate-700">Keuntungan / Poin yang Didapatkan (4 Poin)</label>
                @for ($i = 1; $i <= 4; $i++)
                    <input type="text" name="about_benefit_{{ $i }}" value="{{ $contents['about_benefit_' . $i] ?? '' }}" placeholder="Keuntungan {{ $i }}" class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5">
                @endfor
            </div>
            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-bpsBlue hover:bg-blue-900 text-white text-xs font-bold rounded-xl shadow transition">Simpan Perubahan</button>
            </div>
        </div>

        <!-- ================= 3. FAQ ================= -->
        <div x-show="activeTab === 'faq'" style="display: none;" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-bpsBlue uppercase tracking-wider">Bagian Pertanyaan Umum (FAQ)</h2>
            <div class="space-y-1">
                <label class="text-xs font-semibold text-slate-700">Judul FAQ</label>
                <input type="text" name="faq_title" value="{{ $contents['faq_title'] ?? '' }}" class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-semibold text-slate-700">Sub Judul FAQ</label>
                <input type="text" name="faq_subtitle" value="{{ $contents['faq_subtitle'] ?? '' }}" class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5">
            </div>

            <div class="space-y-4 pt-2">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                        <p class="text-xs font-bold text-slate-800">Pertanyaan FAQ #{{ $i }}</p>
                        <input type="text" name="faq_q_{{ $i }}" value="{{ $contents['faq_q_' . $i] ?? '' }}" placeholder="Pertanyaan {{ $i }}" class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5 bg-white">
                        <textarea name="faq_a_{{ $i }}" rows="2" placeholder="Jawaban {{ $i }}" class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5 bg-white">{{ $contents['faq_a_' . $i] ?? '' }}</textarea>
                    </div>
                @endfor
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-bpsBlue hover:bg-blue-900 text-white text-xs font-bold rounded-xl shadow transition">Simpan Perubahan</button>
            </div>
        </div>

    </form>
</div>
@endsection