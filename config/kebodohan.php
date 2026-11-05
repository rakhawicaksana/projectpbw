<?php

/*
 | Satu-satunya tempat soal, tingkat, kategori, dan ambang penilaian.
 | Isinya mengikuti PPT Kelompok 6 (Pengukur Kebodohan).
 | Kunci jawaban ("benar") TIDAK pernah dikirim ke browser sebelum soal dijawab.
 */
return [

    'nama' => 'Pengukur Kebodohan',

    // PPT slide 8: tingkat kesulitan
    'tingkat' => [
        1 => ['nama' => 'AMAN',              'ket' => 'Soal polos. Sistem masih sopan.'],
        2 => ['nama' => 'MULAI CURIGA',      'ket' => 'Permainan kata mulai bekerja.'],
        3 => ['nama' => 'JANGAN TERLALU PD', 'ket' => 'Panik adalah bagian dari metode.'],
    ],

    // jumlah soal acak per level yang diambil dari bank soal (total 5; bank berisi 15 soal)
    'komposisi' => [1 => 2, 2 => 2, 3 => 1],

    // detik. Dipakai untuk menghitung kecepatan dan "yakin" (jawab cepat tanpa ganti jawaban)
    'batas_waktu' => 30,
    'batas_cepat' => 5,

    // ambang penentu kategori (lihat App\Http\Analisis)
    'ambang' => [
        'otak_batu_maks'    => 20,   // kecerdasan <= ini  -> Otak Batu
        'overthinker_ganti' => 1.0,  // rata-rata ganti jawaban per soal >= ini -> Overthinker
        'overthinker_waktu' => 20,   // rata-rata detik per soal >= ini -> Overthinker
        'pd_maks_cerdas'    => 60,   // cepat + kecerdasan < ini -> Terlalu Percaya Diri
        'korban_maks'       => 50,   // kecerdasan < ini -> Korban Keadaan
    ],

    // "KEPERCAYAAN DIRI TURUN N%" saat salah, per level (PPT slide 7)
    'penurunan_pd' => [1 => 15, 2 => 37, 3 => 45],

    // PPT slide 10: kategori pengguna
    'kategori' => [
        'analitis' => [
            'nama' => 'Si Paling Analitis', 'sprite' => 'otak',
            'deskripsi' => 'Baca soal 3×. Tetap curiga.',
            'penutup' => 'Teliti itu bagus. Asal soalnya tidak kamu curigai balik.',
        ],
        'terlalu-pd' => [
            'nama' => 'Terlalu Percaya Diri', 'sprite' => 'waras',
            'deskripsi' => 'Jawab 0,3 detik. Biasanya salah.',
            'penutup' => 'Tarik napas, baca soal, baru sok pintar.',
        ],
        'overthinker' => [
            'nama' => 'Overthinker', 'sprite' => 'ragu',
            'deskripsi' => 'Jawabannya: "tergantung konteks".',
            'penutup' => 'Jawaban pertamamu mungkin sudah benar. Sayang diubah.',
        ],
        'korban-keadaan' => [
            'nama' => 'Korban Keadaan', 'sprite' => 'siput',
            'deskripsi' => 'Sudah baca. Tetap salah.',
            'penutup' => 'Bukan salahmu. Soalnya yang memang jahat.',
        ],
        'otak-batu' => [
            'nama' => 'Otak Batu', 'sprite' => 'batu',
            'deskripsi' => 'Salah terus, keyakinan abadi.',
            'penutup' => 'Konsisten itu baik. Konsisten salah itu seni.',
        ],
    ],

    // Bank soal. "benar" = nomor opsi yang benar (mulai 0). Urutan opsi diacak per sesi.
    'soal' => [
        // ---- LEVEL 1: AMAN
        ['level' => 1, 'tanya' => 'Ayam punya 2 kaki. Berapa kaki 10 ayam?',
         'opsi' => ['12', '20', '2', '10'], 'benar' => 1,
         'penjelasan' => '10 ayam × 2 kaki = 20 kaki.'],
        ['level' => 1, 'tanya' => 'Berapa hasil 7 + 8?',
         'opsi' => ['14', '15', '16', '78'], 'benar' => 1,
         'penjelasan' => '7 + 8 = 15. Ya, memang segampang itu.'],
        ['level' => 1, 'tanya' => 'Hari apa yang datang setelah Rabu?',
         'opsi' => ['Selasa', 'Kamis', 'Jumat', 'Minggu'], 'benar' => 1,
         'penjelasan' => 'Urutannya Selasa, Rabu, Kamis.'],
        ['level' => 1, 'tanya' => 'Berapa jumlah huruf pada kata KUCING?',
         'opsi' => ['5', '6', '7', '8'], 'benar' => 1,
         'penjelasan' => 'K-U-C-I-N-G = 6 huruf.'],
        ['level' => 1, 'tanya' => 'Hari ini Senin. Dua hari lagi hari apa?',
         'opsi' => ['Selasa', 'Rabu', 'Kamis', 'Jumat'], 'benar' => 1,
         'penjelasan' => 'Senin + 2 hari = Rabu.'],

        // ---- LEVEL 2: MULAI CURIGA
        ['level' => 2, 'tanya' => 'Kamu punya 3 apel, mengambil 2. Berapa apel yang kamu punya?',
         'opsi' => ['1', '2', '3', '5'], 'benar' => 2,
         'penjelasan' => 'Kamu mengambil 2 dari 3 apel milikmu. Jawaban: 3 apel.'],
        ['level' => 2, 'tanya' => 'Berapa bulan dalam setahun yang punya 28 hari?',
         'opsi' => ['1', '2', '12', 'Tidak ada'], 'benar' => 2,
         'penjelasan' => 'Semua bulan punya setidaknya 28 hari, jadi jawabannya 12.'],
        ['level' => 2, 'tanya' => '1 kg besi atau 1 kg kapas, mana yang lebih berat?',
         'opsi' => ['Besi', 'Kapas', 'Sama berat', 'Tergantung warnanya'], 'benar' => 2,
         'penjelasan' => 'Keduanya sama-sama 1 kg.'],
        ['level' => 2, 'tanya' => 'Kamu menyalip orang di posisi kedua dalam lomba lari. Sekarang kamu posisi berapa?',
         'opsi' => ['Pertama', 'Kedua', 'Ketiga', 'Terakhir'], 'benar' => 1,
         'penjelasan' => 'Kamu menggantikan posisinya, jadi kamu di posisi kedua.'],
        ['level' => 2, 'tanya' => 'Ayah Budi punya 3 anak: Senin, Selasa, dan ...?',
         'opsi' => ['Rabu', 'Kamis', 'Budi', 'Minggu'], 'benar' => 2,
         'penjelasan' => 'Soalnya sudah bilang "Ayah Budi", jadi anaknya yang ketiga adalah Budi.'],

        // ---- LEVEL 3: JANGAN TERLALU PD
        ['level' => 3, 'tanya' => 'Berapa hasil 10 + 10 × 0 ?',
         'opsi' => ['0', '10', '20', '100'], 'benar' => 1,
         'penjelasan' => 'Perkalian dikerjakan lebih dulu: 10 × 0 = 0, lalu 10 + 0 = 10.'],
        ['level' => 3, 'tanya' => '3 kucing menangkap 3 tikus dalam 3 menit. Berapa menit 100 kucing menangkap 100 tikus?',
         'opsi' => ['3 menit', '33 menit', '100 menit', '300 menit'], 'benar' => 0,
         'penjelasan' => 'Tiap kucing butuh 3 menit untuk 1 tikus, jadi 100 kucing juga 3 menit.'],
        ['level' => 3, 'tanya' => 'Sebelum Gunung Everest ditemukan, gunung apa yang tertinggi di dunia?',
         'opsi' => ['K2', 'Fuji', 'Everest', 'Kilimanjaro'], 'benar' => 2,
         'penjelasan' => 'Everest sudah tertinggi sebelum ditemukan. Manusianya saja yang telat tahu.'],
        ['level' => 3, 'tanya' => 'Dokter memberi 3 pil dan menyuruh minum 1 pil tiap 30 menit. Berapa menit sampai pil habis?',
         'opsi' => ['30', '60', '90', '120'], 'benar' => 1,
         'penjelasan' => 'Pil pertama menit ke-0, kedua menit ke-30, ketiga menit ke-60.'],
        ['level' => 3, 'tanya' => 'Berapa banyak tanah dalam lubang berukuran 1 × 1 × 1 meter?',
         'opsi' => ['1 meter kubik', '1 ton', 'Tidak ada', 'Setengahnya'], 'benar' => 2,
         'penjelasan' => 'Namanya juga lubang. Tanahnya sudah digali.'],
    ],
];
