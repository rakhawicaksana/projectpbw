{{-- Komponen leaderboard (milik D). Variabel: $daftar (koleksi Skor), opsional $sorot (kode skor yang disorot) --}}
@once
@push('styles')
<style>
.papan{list-style:none;padding:10px;max-width:760px;margin:28px auto 0;text-align:left}
.papan__baris{display:grid;grid-template-columns:48px 1fr auto 78px 32px;align-items:center;gap:12px;
  padding:11px 16px;font-weight:700;font-size:clamp(.95rem,2.8vw,1.35rem);border-bottom:2px dashed #3b2f7a}
.papan__baris:last-child{border-bottom:0}
.papan__baris--sorot{background:#fde04722;color:var(--kuning)}
.papan__no{color:var(--redup)}
.papan__nama{display:flex;align-items:center;gap:10px;min-width:0}
.papan__nama img{width:26px;height:26px;image-rendering:pixelated;flex:none}
.papan__kat{font-size:.7em;font-weight:600;color:var(--redup);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.papan__skor{font-family:var(--judul)}
.papan__waktu{color:var(--redup);font-size:.8em;text-align:right}
.papan__kosong{padding:24px;text-align:center;color:var(--redup)}
@media (max-width:600px){.papan__kat{display:none}.papan__baris{grid-template-columns:36px 1fr auto 62px 24px;gap:8px;padding:10px 8px}}
</style>
@endpush
@endonce

<ol class="papan kotak">
    @forelse ($daftar as $i => $skor)
        <li class="papan__baris {{ ($sorot ?? null) === $skor->kode ? 'papan__baris--sorot' : '' }}">
            <span class="papan__no">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <span class="papan__nama">
                <img src="{{ asset('img/sprite-' . $skor->sprite . '.svg') }}" alt="">
                {{ $skor->inisial }}
                <span class="papan__kat">{{ $skor->info_kategori['nama'] }}</span>
            </span>
            <span class="papan__skor">{{ str_pad($skor->persen, 3, '0', STR_PAD_LEFT) }}%</span>
            <span class="papan__waktu">{{ $skor->waktu_format }}</span>
            <span aria-hidden="true">{{ $i === 0 ? '🏆' : '' }}</span>
        </li>
    @empty
        <li class="papan__kosong">Belum ada pemain. Jadilah yang pertama dihakimi!</li>
    @endforelse
</ol>
