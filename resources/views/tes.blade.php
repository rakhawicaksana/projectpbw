@extends('layouts.app')

{{-- Variabel dari TesController@index: $total, $tingkat --}}
@section('judul', 'Tes - ' . config('kebodohan.nama'))

@section('isi')
<div class="tes" id="tes"
     data-url-soal="{{ route('tes.soal') }}"
     data-url-jawab="{{ route('tes.jawab') }}"
     data-url-hasil="{{ route('hasil') }}"
     data-url-ulang="{{ route('tes') }}"
     data-total="{{ $total }}">

    <noscript><p class="galat">Quiz ini butuh JavaScript. Aktifkan dulu, ya.</p></noscript>
    <p class="galat" id="galat" hidden></p>

    <div class="tes__atas">
        <span class="pk" id="penanda">MEMUAT...</span>
        <span class="yl" id="badge-level"></span>
        <span class="pn" id="timer" aria-label="Waktu">00:00</span>
    </div>
    <div class="progres" aria-hidden="true"><i id="isi-progres"></i></div>

    <div class="pd">
        <span>KEPERCAYAAN DIRIiii</span>
        <div class="progres" aria-hidden="true"><i id="isi-pd" style="width:100%"></i></div>
        <b id="nilai-pd">100%</b>
    </div>

    <div class="kotak soal-kotak" aria-live="polite">
        <h2 class="soal__tanya" id="tanya"></h2>
        <div class="grid-opsi" id="opsi" role="radiogroup" aria-labelledby="tanya"></div>

        <div class="umpan" id="umpan" hidden>
            <p class="umpan__judul" id="umpan-judul"></p>
            <p class="umpan__teks" id="umpan-teks"></p>
            <p class="umpan__pd" id="umpan-pd"></p>
        </div>
    </div>

    <div class="tes__aksi">
        <button type="button" class="tombol" id="tombol-cek" disabled>CEK JAWABAN ▶</button>
        <button type="button" class="tombol" id="tombol-lanjut" hidden>NEXT ▶</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* Quiz engine: ambil soal -> pilih -> cek -> benar/salah -> next -> hasil.
   Kunci jawaban dan penilaian ada di server; di sini hanya tampilan, timer, dan hitung ganti jawaban. */
(function () {
    var root = document.getElementById('tes');
    var token = document.querySelector('meta[name="csrf-token"]').content;
    var $ = function (id) { return document.getElementById(id); };
    var el = {
        penanda: $('penanda'), level: $('badge-level'), timer: $('timer'), progres: $('isi-progres'),
        pd: $('isi-pd'), nilaiPd: $('nilai-pd'), tanya: $('tanya'), opsi: $('opsi'), galat: $('galat'),
        umpan: $('umpan'), judul: $('umpan-judul'), teks: $('umpan-teks'), turun: $('umpan-pd'),
        cek: $('tombol-cek'), lanjut: $('tombol-lanjut')
    };
    var TINGKAT = {1: 'AMAN', 2: 'MULAI CURIGA', 3: 'JANGAN TERLALU PD'};
    var s = {pilihan: null, ganti: 0, mulai: 0, jam: null, selesai: false, nomor: 0, total: 1};

    function api(url, opsi) {
        opsi = opsi || {};
        opsi.credentials = 'same-origin';
        opsi.headers = {'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token};
        return fetch(url, opsi).then(function (r) {
            return r.json().then(function (d) {
                if (!r.ok) { throw {status: r.status, data: d}; }
                return d;
            });
        });
    }
    function gagal(e) {
        if (e && e.status === 409) { window.location.href = root.dataset.urlUlang; return; }
        el.galat.textContent = 'Ups, ada gangguan. Muat ulang halaman ini.';
        el.galat.hidden = false;
    }
    function format(d) { var m = Math.floor(d / 60), x = d % 60; return (m < 10 ? '0' : '') + m + ':' + (x < 10 ? '0' : '') + x; }
    function jalankanTimer() {
        s.mulai = Date.now();
        clearInterval(s.jam);
        s.jam = setInterval(function () { el.timer.textContent = format(Math.floor((Date.now() - s.mulai) / 1000)); }, 250);
    }
    function setPd(n) { el.pd.style.width = n + '%'; el.nilaiPd.textContent = n + '%'; }

    function muatSoal() {
        api(root.dataset.urlSoal).then(function (d) {
            if (d.selesai) { window.location.href = root.dataset.urlHasil; return; }
            s.pilihan = null; s.ganti = 0; s.nomor = d.nomor; s.total = d.total;
            el.penanda.textContent = 'LEVEL ' + (d.nomor < 10 ? '0' : '') + d.nomor + ' / ' + d.total;
            el.level.textContent = 'TINGKAT ' + d.level + ': ' + TINGKAT[d.level];
            el.progres.style.width = ((d.nomor - 1) / d.total * 100) + '%';
            setPd(d.pd);
            el.tanya.textContent = d.tanya;
            el.opsi.innerHTML = '';
            d.opsi.forEach(function (teks, i) {
                var label = document.createElement('label'); label.className = 'opsi';
                var input = document.createElement('input'); input.type = 'radio'; input.name = 'pilihan'; input.value = i;
                var span = document.createElement('span');
                var huruf = document.createElement('b'); huruf.textContent = String.fromCharCode(65 + i);
                span.appendChild(huruf); span.appendChild(document.createTextNode('  ' + teks));
                input.addEventListener('change', function () {
                    if (s.pilihan !== null && s.pilihan !== i) { s.ganti++; }
                    s.pilihan = i; el.cek.disabled = false;
                });
                label.appendChild(input); label.appendChild(span); el.opsi.appendChild(label);
            });
            el.umpan.hidden = true; el.lanjut.hidden = true; el.cek.hidden = false; el.cek.disabled = true;
            jalankanTimer();
        }).catch(gagal);
    }

    el.cek.addEventListener('click', function () {
        if (s.pilihan === null) { return; }
        el.cek.disabled = true; clearInterval(s.jam);
        api(root.dataset.urlJawab, {method: 'POST', body: JSON.stringify({pilihan: s.pilihan, ganti: s.ganti})})
            .then(function (d) {
                var labels = el.opsi.querySelectorAll('.opsi');
                labels.forEach(function (l, i) {
                    l.querySelector('input').disabled = true;
                    if (i === d.posisi_benar) { l.classList.add('opsi--benar'); }
                    else if (i === s.pilihan) { l.classList.add('opsi--salah'); }
                });
                el.umpan.hidden = false;
                el.umpan.className = 'umpan' + (d.benar ? '' : ' umpan--salah');
                el.judul.textContent = d.benar ? 'BENAR!' : 'SALAH!';
                el.teks.textContent = d.penjelasan;
                el.turun.textContent = d.turun > 0 ? 'KEPERCAYAAN DIRI TURUN ' + d.turun + '%' : '';
                setPd(d.pd);
                el.cek.hidden = true; el.lanjut.hidden = false;
                el.lanjut.textContent = d.selesai ? 'LIHAT HASIL ▶' : 'NEXT ▶';
                s.selesai = d.selesai;
                el.progres.style.width = (s.nomor / s.total * 100) + '%';
            }).catch(gagal);
    });
    el.lanjut.addEventListener('click', function () {
        if (s.selesai) { window.location.href = root.dataset.urlHasil; } else { muatSoal(); }
    });

    muatSoal();
})();
</script>
@endpush
