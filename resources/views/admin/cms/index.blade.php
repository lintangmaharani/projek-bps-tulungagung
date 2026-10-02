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
            <div
            class="space-y-3"
            x-data="{
                benefits: [
                    @for($i = 1; $i <= 10; $i++)
                        @if(!empty($contents['about_benefit_' . $i]))
                            {
                                id: {{ $i }},
                                value: @js($contents['about_benefit_' . $i])
                            },
                        @endif
                    @endfor
                ],

                nextId: 11,

                tambahPoin() {
                    if (this.benefits.length >= 10) {
                        alert('Maksimal 10 poin keuntungan.');
                        return;
                    }

                    this.benefits.push({
                        id: this.nextId++,
                        value: ''
                    });
                },

                hapusPoin(index) {
                    this.benefits.splice(index, 1);
                }
            }"
        >

            <div class="flex items-center justify-between gap-3">

                <label class="text-xs font-semibold text-slate-700">
                    Keuntungan / Poin yang Didapatkan
                </label>

                <button
                    type="button"
                    @click="tambahPoin()"
                    class="px-3 py-2 bg-bpsBlue hover:bg-blue-900 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5"
                >
                    <span class="text-base leading-none">+</span>
                    Tambah Poin
                </button>

            </div>


            <div class="space-y-2">

                <template
                    x-for="(benefit, index) in benefits"
                    :key="benefit.id"
                >

                    <div class="flex items-center gap-2">

                        <input
                            type="text"
                            :name="'about_benefit_' + (index + 1)"
                            x-model="benefit.value"
                            :placeholder="'Keuntungan ' + (index + 1)"
                            class="flex-1 text-xs border border-slate-300 rounded-xl px-3 py-2.5"
                        >

                        <button
                            type="button"
                            @click="hapusPoin(index)"
                            class="shrink-0 w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition"
                            title="Hapus poin"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>
                        </button>

                    </div>

                </template>

            </div>


            <p class="text-[11px] text-slate-400">
                Maksimal 10 poin keuntungan.
            </p>

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

            <div
                class="space-y-4 pt-2"
                x-data="{
                    faqs: [
                        @for($i = 1; $i <= 10; $i++)
                            @if(!empty($contents['faq_q_' . $i]) || !empty($contents['faq_a_' . $i]))
                                {
                                    id: {{ $i }},
                                    question: @js($contents['faq_q_' . $i] ?? ''),
                                    answer: @js($contents['faq_a_' . $i] ?? '')
                                },
                            @endif
                        @endfor
                    ],

                    nextId: 11,

                    tambahFaq() {
                        if (this.faqs.length >= 10) {
                            alert('Maksimal 10 FAQ.');
                            return;
                        }

                        this.faqs.push({
                            id: this.nextId++,
                            question: '',
                            answer: ''
                        });
                    },

                    hapusFaq(index) {
                        this.faqs.splice(index, 1);
                    }
                }"
            >

                <!-- Header FAQ -->
                <div class="flex items-center justify-between gap-3">

                    <label class="text-xs font-semibold text-slate-700">
                        Daftar Pertanyaan FAQ
                    </label>

                    <button
                        type="button"
                        @click="tambahFaq()"
                        class="px-3 py-2 bg-bpsBlue hover:bg-blue-900 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5"
                    >
                        <span class="text-base leading-none">+</span>
                        Tambah FAQ
                    </button>

                </div>


                <!-- Daftar FAQ -->
                <div class="space-y-4">

                    <template
                        x-for="(faq, index) in faqs"
                        :key="faq.id"
                    >

                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">

                            <!-- Judul FAQ + Hapus -->
                            <div class="flex items-center justify-between">

                                <p
                                    class="text-xs font-bold text-slate-800"
                                    x-text="'Pertanyaan FAQ #' + (index + 1)"
                                ></p>

                                <button
                                    type="button"
                                    @click="hapusFaq(index)"
                                    class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition"
                                    title="Hapus FAQ"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        />
                                    </svg>
                                </button>

                            </div>


                            <!-- Pertanyaan -->
                            <input
                                type="text"
                                :name="'faq_q_' + (index + 1)"
                                x-model="faq.question"
                                :placeholder="'Pertanyaan ' + (index + 1)"
                                class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5 bg-white"
                            >


                            <!-- Jawaban -->
                            <textarea
                                :name="'faq_a_' + (index + 1)"
                                x-model="faq.answer"
                                rows="2"
                                :placeholder="'Jawaban ' + (index + 1)"
                                class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2.5 bg-white"
                            ></textarea>

                        </div>

                    </template>

                </div>


                <p class="text-[11px] text-slate-400">
                    Maksimal 10 pertanyaan FAQ.
                </p>

            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-bpsBlue hover:bg-blue-900 text-white text-xs font-bold rounded-xl shadow transition">Simpan Perubahan</button>
            </div>
        </div>

    </form>
</div>
@endsection