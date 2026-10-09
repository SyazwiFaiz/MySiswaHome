<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>MySiswa Home | Website</title>
</head>

<body>

    <input type="checkbox" id="nav-toggle" class="peer sr-only" />

    <header class="bg-white">

        {{-- Navbar blade component  --}}
        <x-navbar></x-navbar>


        <section class="overflow-hidden bg-gray-50 sm:grid sm:grid-cols-2">
            <div class="p-8 md:p-12 lg:px-16 lg:py-24">
                <div class="mx-auto max-w-xl text-center ltr:sm:text-left rtl:sm:text-right">
                    <h1 class="text-4xl font-bold text-gray-900 md:text-5xl">
                        Carian Sewa Rumah Di
                        <span class="text-indigo-600 text-5xl md:text-6xl">
                            MySiswaHome
                        </span>
                    </h1>

                    <p class="hidden text-gray-500 md:mt-4 md:block">
                        MySiswaHome makes it easier for students to find suitable rental homes in one place, so you can
                        spend less time searching and more time focusing on your studies.

                    </p>

                    <div class="mt-4 md:mt-8">
                        <a href="{{ route('register') }}"
                            class="inline-block rounded-sm bg-indigo-600 px-12 py-3 text-sm font-medium text-white transition hover:bg-indigo-700 focus:ring-2 focus:ring-yellow-400 focus:outline-hidden">
                            Mula Mencari Rumah
                        </a>
                    </div>
                </div>
            </div>

            <img alt="" src="{{ asset('img/12.png') }}" class="w-full h-auto" />
        </section>

        <section>
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">

                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                        Memudahkan Pelajar Mencari Rumah
                    </h2>

                    <p class="mt-4 text-gray-500 sm:text-xl">
                        MySiswaHome membantu pelajar mencari rumah sewa yang sesuai
                        dengan lokasi, bajet dan keperluan mereka dengan lebih mudah.
                    </p>
                </div>

                <dl class="mt-6 grid grid-cols-2 gap-4 sm:mt-8 lg:grid-cols-4">

                    <!-- Rumah Tersedia -->
                    <div class="flex flex-col rounded-lg border border-gray-100 px-4 py-8 text-center">
                        <dt class="order-last mt-2 text-lg font-medium text-gray-500">
                            Rumah Tersedia
                        </dt>

                        <dd class="text-4xl font-extrabold text-indigo-600 md:text-5xl">
                            500+
                        </dd>
                    </div>

                    <!-- Pelajar -->
                    <div class="flex flex-col rounded-lg border border-gray-100 px-4 py-8 text-center">
                        <dt class="order-last mt-2 text-lg font-medium text-gray-500">
                            Pelajar Berdaftar
                        </dt>

                        <dd class="text-4xl font-extrabold text-indigo-600 md:text-5xl">
                            1K+
                        </dd>
                    </div>

                    <!-- Lokasi -->
                    <div class="flex flex-col rounded-lg border border-gray-100 px-4 py-8 text-center">
                        <dt class="order-last mt-2 text-lg font-medium text-gray-500">
                            Lokasi Disediakan
                        </dt>

                        <dd class="text-4xl font-extrabold text-indigo-600 md:text-5xl">
                            20+
                        </dd>
                    </div>

                    <!-- Pemilik -->
                    <div class="flex flex-col rounded-lg border border-gray-100 px-4 py-8 text-center">
                        <dt class="order-last mt-2 text-lg font-medium text-gray-500">
                            Pemilik Rumah
                        </dt>

                        <dd class="text-4xl font-extrabold text-indigo-600 md:text-5xl">
                            100+
                        </dd>
                    </div>

                </dl>
            </div>
        </section>



        <section>
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

                <!-- Section Heading -->
                <div class="mx-auto max-w-lg text-center">
                    <h2 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl">
                        Semua yang Pelajar Perlukan
                    </h2>

                    <p class="mt-4 text-lg text-pretty text-gray-700">
                        MySiswaHome memudahkan pelajar mencari rumah sewa yang sesuai
                        dengan lokasi, bajet dan keperluan mereka.
                    </p>
                </div>

                <!-- Features -->
                <div class="mt-8 grid grid-cols-1 gap-8 md:grid-cols-3">

                    <!-- Feature 1 -->
                    <div class="rounded-lg border border-gray-200 p-6">
                        <div class="inline-flex rounded-lg bg-indigo-50 p-3 text-indigo-600">

                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.197 5.197a7.5 7.5 0 0 0 10.606 10.606Z" />
                            </svg>

                        </div>

                        <h3 class="mt-4 text-lg font-semibold text-gray-900">
                            Cari Rumah Dengan Mudah
                        </h3>

                        <p class="mt-2 text-pretty text-gray-700">
                            Cari rumah sewa berdasarkan lokasi, harga dan jenis
                            kediaman yang sesuai dengan keperluan anda.
                        </p>
                    </div>


                    <!-- Feature 2 -->
                    <div class="rounded-lg border border-gray-200 p-6">
                        <div class="inline-flex rounded-lg bg-indigo-50 p-3 text-indigo-600">

                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 6.75V15m6-8.25V15m-9.75-4.5h13.5M5.25 19.5h13.5A2.25 2.25 0 0 0 21 17.25v-10.5a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>

                        </div>

                        <h3 class="mt-4 text-lg font-semibold text-gray-900">
                            Tapis Mengikut Keperluan
                        </h3>

                        <p class="mt-2 text-pretty text-gray-700">
                            Gunakan pilihan penapisan untuk mendapatkan rumah yang
                            menepati bajet, lokasi dan keperluan anda.
                        </p>
                    </div>


                    <!-- Feature 3 -->
                    <div class="rounded-lg border border-gray-200 p-6">
                        <div class="inline-flex rounded-lg bg-indigo-50 p-3 text-indigo-600">

                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 8.511c.884.284 1.5 1.131 1.5 2.059v7.68a2.25 2.25 0 0 1-2.25 2.25H6.75a2.25 2.25 0 0 1-2.25-2.25v-7.68c0-.928.616-1.775 1.5-2.059m14.25 0-9.75 3.139-9.75-3.139m19.5 0a2.25 2.25 0 0 0-.75-.137H4.5c-.268 0-.524.048-.75.137m16.5 0v-2.25a2.25 2.25 0 0 0-2.25-2.25H6a2.25 2.25 0 0 0-2.25 2.25v2.25" />
                            </svg>

                        </div>

                        <h3 class="mt-4 text-lg font-semibold text-gray-900">
                            Hubungi Pemilik
                        </h3>

                        <p class="mt-2 text-pretty text-gray-700">
                            Berhubung terus dengan pemilik rumah untuk mendapatkan
                            maklumat lanjut dan membuat pertanyaan.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="bg-gray-50 py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <!-- Heading -->
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Apa Kata Pelajar
                    </h2>

                    <p class="mt-4 text-gray-500">
                        Pengalaman pelajar menggunakan MySiswaHome untuk mencari
                        rumah sewa yang sesuai.
                    </p>
                </div>

                <!-- Testimonials -->
                <ul class="mt-12 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    <!-- Testimonial 1 -->
                    <li class="flex flex-col rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex gap-1 text-yellow-400">
                            ★★★★★
                        </div>

                        <blockquote class="flex-1 text-sm leading-6 text-gray-700">
                            &ldquo;MySiswaHome sangat membantu saya mencari rumah
                            sewa yang dekat dengan universiti. Tak perlu lagi
                            cari satu-satu di banyak tempat.&rdquo;
                        </blockquote>

                        <div class="mt-6 flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?auto=format&fit=crop&q=80&w=200"
                                alt="Ahmad Hakimi" class="size-10 rounded-full object-cover">

                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    Ahmad Hakimi
                                </p>
                                <p class="text-xs text-gray-500">
                                    Pelajar Universiti
                                </p>
                            </div>
                        </div>
                    </li>

                    <!-- Testimonial 2 -->
                    <li class="flex flex-col rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex gap-1 text-yellow-400">
                            ★★★★★
                        </div>

                        <blockquote class="flex-1 text-sm leading-6 text-gray-700">
                            &ldquo;Saya suka sebab boleh tapis rumah mengikut
                            bajet dan lokasi. Proses mencari rumah jadi jauh
                            lebih mudah dan cepat.&rdquo;
                        </blockquote>

                        <div class="mt-6 flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1601412436009-d964bd02edbc?auto=format&fit=crop&q=80&w=200"
                                alt="Nur Aisyah" class="size-10 rounded-full object-cover">

                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    Nur Aisyah
                                </p>
                                <p class="text-xs text-gray-500">
                                    Pelajar IPT
                                </p>
                            </div>
                        </div>
                    </li>

                    <!-- Testimonial 3 -->
                    <li class="flex flex-col rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex gap-1 text-yellow-400">
                            ★★★★★
                        </div>

                        <blockquote class="flex-1 text-sm leading-6 text-gray-700">
                            &ldquo;Maklumat rumah lebih tersusun dan senang
                            untuk dibandingkan. Saya boleh tengok harga dan
                            lokasi sebelum hubungi pemilik.&rdquo;
                        </blockquote>

                        <div class="mt-6 flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=200"
                                alt="Siti Nur Amirah" class="size-10 rounded-full object-cover">

                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    Siti Nur Amirah
                                </p>
                                <p class="text-xs text-gray-500">
                                    Pelajar Universiti
                                </p>
                            </div>
                        </div>
                    </li>

                    <!-- Testimonial 4 -->
                    <li class="flex flex-col rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex gap-1 text-yellow-400">
                            ★★★★★
                        </div>

                        <blockquote class="flex-1 text-sm leading-6 text-gray-700">
                            &ldquo;Sangat memudahkan saya yang baru pertama kali
                            mencari rumah sewa. Semua maklumat yang diperlukan
                            boleh didapati dalam satu platform.&rdquo;
                        </blockquote>

                        <div class="mt-6 flex items-center gap-3">
                            <div
                                class="flex size-10 items-center justify-center rounded-full bg-indigo-100 font-semibold text-indigo-600">
                                MF
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    Muhammad Firdaus
                                </p>
                                <p class="text-xs text-gray-500">
                                    Pelajar Kolej
                                </p>
                            </div>
                        </div>
                    </li>

                    <!-- Testimonial 5 -->
                    <li class="flex flex-col rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex gap-1 text-yellow-400">
                            ★★★★★
                        </div>

                        <blockquote class="flex-1 text-sm leading-6 text-gray-700">
                            &ldquo;Saya boleh mencari rumah berdasarkan kawasan
                            yang saya mahu tanpa perlu membuang banyak masa.
                            Interface pun mudah difahami.&rdquo;
                        </blockquote>

                        <div class="mt-6 flex items-center gap-3">
                            <div
                                class="flex size-10 items-center justify-center rounded-full bg-indigo-100 font-semibold text-indigo-600">
                                NA
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    Nur Alia
                                </p>
                                <p class="text-xs text-gray-500">
                                    Pelajar Universiti
                                </p>
                            </div>
                        </div>
                    </li>

                    <!-- Testimonial 6 -->
                    <li class="flex flex-col rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex gap-1 text-yellow-400">
                            ★★★★★
                        </div>

                        <blockquote class="flex-1 text-sm leading-6 text-gray-700">
                            &ldquo;MySiswaHome menjadikan pencarian rumah sewa
                            lebih teratur. Saya boleh lihat beberapa pilihan
                            sebelum membuat keputusan.&rdquo;
                        </blockquote>

                        <div class="mt-6 flex items-center gap-3">
                            <div
                                class="flex size-10 items-center justify-center rounded-full bg-indigo-100 font-semibold text-indigo-600">
                                AR
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    Amirul Rahman
                                </p>
                                <p class="text-xs text-gray-500">
                                    Pelajar IPT
                                </p>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </section>

        <section>
            <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">

                <div class="mx-auto max-w-lg text-center">
                    <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                        Pilihan Untuk Semua
                    </h2>

                    <p class="mt-4 text-lg text-gray-700">
                        Sama ada anda sedang mencari rumah atau ingin menyewakan
                        kediaman, MySiswaHome memudahkan proses untuk anda.
                    </p>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:items-center md:gap-8">

                    <!-- Pelajar -->
                    <div
                        class="rounded-2xl border border-indigo-600 p-6 shadow-xs ring-1 ring-indigo-600 sm:order-last sm:px-8 lg:p-12">

                        <div class="text-center">
                            <h3 class="text-lg font-medium text-gray-900">
                                Untuk Pelajar
                            </h3>

                            <p class="mt-2 text-sm text-gray-500 sm:mt-4">
                                Cari rumah sewa yang sesuai dengan keperluan anda.
                            </p>
                        </div>

                        <ul class="mt-6 space-y-3">

                            <li class="flex items-center gap-2">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-5 shrink-0 text-indigo-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>

                                <span class="text-gray-700">
                                    Cari rumah mengikut lokasi
                                </span>
                            </li>

                            <li class="flex items-center gap-2">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-5 shrink-0 text-indigo-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>

                                <span class="text-gray-700">
                                    Tapis mengikut bajet
                                </span>
                            </li>

                            <li class="flex items-center gap-2">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-5 shrink-0 text-indigo-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>

                                <span class="text-gray-700">
                                    Lihat maklumat rumah
                                </span>
                            </li>

                            <li class="flex items-center gap-2">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-5 shrink-0 text-indigo-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>

                                <span class="text-gray-700">
                                    Hubungi pemilik rumah
                                </span>
                            </li>

                            <li class="flex items-center gap-2">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-5 shrink-0 text-indigo-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>

                                <span class="text-gray-700">
                                    Simpan rumah pilihan
                                </span>
                            </li>

                        </ul>

                        <a href="#"
                            class="mt-8 block rounded-full border border-indigo-600 bg-indigo-600 px-12 py-3 text-center text-sm font-medium text-white hover:bg-indigo-700 hover:ring-1 hover:ring-indigo-700">
                            Cari Rumah
                        </a>
                    </div>


                    <!-- Pemilik Rumah -->
                    <div class="rounded-2xl border border-gray-200 p-6 shadow-xs sm:px-8 lg:p-12">

                        <div class="text-center">
                            <h3 class="text-lg font-medium text-gray-900">
                                Untuk Pemilik Rumah
                            </h3>

                            <p class="mt-2 text-sm text-gray-500 sm:mt-4">
                                Paparkan rumah anda kepada pelajar yang sedang mencari.
                            </p>
                        </div>

                        <ul class="mt-6 space-y-3">

                            <li class="flex items-center gap-2">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-5 shrink-0 text-indigo-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>

                                <span class="text-gray-700">
                                    Tambah iklan rumah
                                </span>
                            </li>

                            <li class="flex items-center gap-2">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-5 shrink-0 text-indigo-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>

                                <span class="text-gray-700">
                                    Paparkan maklumat rumah
                                </span>
                            </li>

                            <li class="flex items-center gap-2">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-5 shrink-0 text-indigo-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>

                                <span class="text-gray-700">
                                    Tetapkan harga sewa
                                </span>
                            </li>

                            <li class="flex items-center gap-2">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-5 shrink-0 text-indigo-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>

                                <span class="text-gray-700">
                                    Terima pertanyaan daripada pelajar
                                </span>
                            </li>

                        </ul>

                        <a href="#"
                            class="mt-8 block rounded-full border border-indigo-600 bg-white px-12 py-3 text-center text-sm font-medium text-indigo-600 hover:ring-1 hover:ring-indigo-600">
                            Iklankan Rumah
                        </a>
                    </div>

                </div>
            </div>
        </section>
        

        <x-footer></x-footer>

        <script>
            const navToggleCheckbox = document.getElementById('nav-toggle')
            const navToggleLabel = document.getElementById('nav-toggle-label')

            navToggleCheckbox.addEventListener('change', () => {
                navToggleLabel.setAttribute('aria-expanded', String(navToggleCheckbox.checked))
            })
        </script>

        <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
