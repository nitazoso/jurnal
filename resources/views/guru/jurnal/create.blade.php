 @extends('layouts.guru')

@section('title', 'Isi Jurnal Mengajar - Jurnify')
@section('page-title', 'Isi Jurnal Mengajar')
@section('page-subtitle', 'Lengkapi data aktivitas pembelajaran di kelas secara berkala.')

@section('content')

<style>//
    ========================= */
    .journal-hero {
        width: 100%;
        min-height: 132px;
        padding: 24px 26px;
        margin-bottom: 20px;
        border: 1px solid #D8E2F4;
        border-radius: 16px;
        background: linear-gradient(110deg, #F2F6FF 0%, #DDEAFF 100%);
        position: relative;
        overflow: hidden;
        animation: fadeUp .45s ease both;
        transition: box-shadow .3s ease, transform .3s ease;
    }

    .journal-hero:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(45, 51, 107, .08);
    }

    .journal-hero::before {
        content: "";
        position: absolute;
        width: 90px;
        height: 90px;
        right: 100px;
        bottom: -55px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .25);
        pointer-events: none;
        transition: transform .5s ease;
    }

    .journal-hero::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -80px;
        top: -110px;
        border-radius: 50%;
        background: rgba(120, 134, 199, .08);
        pointer-events: none;
        transition: transform .5s ease;
    }

    .journal-hero:hover::before {
        transform: translate(-8px, -5px);
    }

    .journal-hero:hover::after {
        transform: scale(1.08) rotate(8deg);
    }

    .journal-hero-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        margin-bottom: 10px;
        border: 1px solid #C7D4EE;
        border-radius: 999px;
        background: rgba(255, 255, 255, .5);
        color: #4B578B;
        font-size: 10px;
        font-weight: 700;
        position: relative;
        z-index: 1;
        transition: background .2s ease, transform .2s ease;
    }

    .journal-hero:hover .journal-hero-badge {
        background: rgba(255, 255, 255, .72);
        transform: translateY(-1px);
    }

    .journal-hero-title {
        margin: 0;
        color: #202754;
        font-size: 24px;
        line-height: 1.3;
        font-weight: 800;
        letter-spacing: -.5px;
        position: relative;
        z-index: 1;
    }

    .journal-hero-text {
        max-width: 850px;
        margin: 6px 0 0;
        color: #63708D;
        font-size: 11px;
        line-height: 1.6;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    /* =========================
       SCHEDULE CARD
    ========================= */
    .schedule-card {
        width: 100%;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        box-shadow: 0 3px 12px rgba(45, 51, 107, .035);
        overflow: hidden;
        animation: fadeUp .5s ease .08s both;
        transition: box-shadow .3s ease, border-color .3s ease;
    }

    .schedule-card:hover {
        border-color: #D5DDF0;
        box-shadow: 0 10px 26px rgba(45, 51, 107, .06);
    }

    .schedule-card-header {
        min-height: 62px;
        padding: 0 20px;
        border-bottom: 1px solid #EDF0F5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .schedule-card-title {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .schedule-icon {
        width: 27px;
        height: 27px;
        border-radius: 7px;
        background: #EEF2FF;
        color: #2D336B;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform .25s ease, background .25s ease;
    }

    .schedule-card:hover .schedule-icon {
        transform: rotate(-4deg) scale(1.05);
        background: #E6ECFC;
    }

    .schedule-icon svg {
        width: 15px;
        height: 15px;
    }

    .schedule-card-title h2 {
        margin: 0;
        color: #27304F;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .1px;
    }

    .schedule-card-note {
        color: #9AA5B8;
        font-size: 9px;
        font-weight: 600;
        transition: color .2s ease;
    }

    .schedule-card:hover .schedule-card-note {
        color: #7886C7;
    }

    /* =========================
       TABLE
    ========================= */
    .schedule-content {
        padding: 6px 14px 14px;
    }

    .schedule-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 7px;
    }

    .schedule-table thead th {
        padding: 8px 12px 5px;
        color: #8A95A8;
        font-size: 9px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .schedule-table thead th:first-child {
        width: 58px;
        text-align: center;
    }

    .schedule-table thead th:last-child {
        width: 135px;
        text-align: center;
    }

    .schedule-table tbody td {
        padding: 12px;
        background: #FFFFFF;
        border-top: 1px solid #E4E9F1;
        border-bottom: 1px solid #E4E9F1;
        color: #34415D;
        font-size: 10px;
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }

    .schedule-table tbody td:first-child {
        border-left: 1px solid #E4E9F1;
        border-radius: 11px 0 0 11px;
        text-align: center;
    }

    .schedule-table tbody td:last-child {
        border-right: 1px solid #E4E9F1;
        border-radius: 0 11px 11px 0;
        text-align: center;
    }

    .schedule-table tbody tr {
        transition: transform .2s ease;
    }

    .schedule-table tbody tr:hover {
        transform: translateX(3px);
    }

    .schedule-table tbody tr:hover td {
        background: #FBFCFF;
        border-color: #CCD7F3;
    }

    /* =========================
       NUMBER
    ========================= */
    .schedule-number {
        width: 31px;
        height: 31px;
        margin: 0 auto;
        border-radius: 9px;
        background: #E9EEFF;
        color: #2D336B;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 800;
        transition: transform .2s ease, background .2s ease;
    }

    .schedule-table tbody tr:hover .schedule-number {
        transform: scale(1.07);
        background: #DDE5FF;
    }

    /* =========================
       DAY
    ========================= */
    .schedule-day {
        color: #28334F;
        font-weight: 700;
    }

    /* =========================
       CLASS
    ========================= */
    .schedule-class {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 7px;
        background: #F0F3FF;
        color: #3F4C87;
        font-size: 9px;
        font-weight: 800;
        transition: background .2s ease, transform .2s ease;
    }

    .schedule-table tbody tr:hover .schedule-class {
        background: #E7ECFF;
        transform: translateY(-1px);
    }

    /* =========================
       SUBJECT
    ========================= */
    .schedule-subject {
        color: #28334F;
        font-weight: 700;
    }

    /* =========================
       TIME
    ========================= */
    .schedule-time {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #64748B;
        font-size: 9px;
        font-weight: 700;
    }

    .schedule-time svg {
        width: 14px;
        height: 14px;
        color: #7886C7;
        flex-shrink: 0;
        transition: transform .25s ease;
    }

    .schedule-table tbody tr:hover .schedule-time svg {
        transform: rotate(8deg);
    }

    /* =========================
       ACTION BUTTON
    ========================= */
    .journal-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 108px;
        padding: 8px 12px;
        border-radius: 8px;
        background: #2D336B;
        color: #FFFFFF;
        text-decoration: none;
        font-size: 9px;
        font-weight: 700;
        box-shadow: 0 3px 7px rgba(45, 51, 107, .12);
        transition:
            background .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .journal-action:hover {
        background: #242A5A;
        color: #FFFFFF;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(45, 51, 107, .2);
    }

    .journal-action:active {
        transform: translateY(0) scale(.98);
    }

    .journal-action svg {
        width: 13px;
        height: 13px;
        transition: transform .2s ease;
    }

    .journal-action:hover svg {
        transform: rotate(90deg);
    }

    /* =========================
       EMPTY STATE
    ========================= */
    .empty-row td {
        padding: 0 !important;
        border: 0 !important;
        background: transparent !important;
    }

    .empty-state {
        padding: 45px 20px;
        margin: 7px 0;
        border: 1px dashed #D8E0ED;
        border-radius: 12px;
        background: #FAFBFE;
        text-align: center;
    }

    .empty-icon {
        width: 42px;
        height: 42px;
        margin: 0 auto 10px;
        border-radius: 11px;
        background: #EEF2FF;
        color: #7886C7;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon svg {
        width: 21px;
        height: 21px;
    }

    .empty-title {
        margin: 0;
        color: #374151;
        font-size: 12px;
        font-weight: 700;
    }

    .empty-text {
        margin: 4px 0 0;
        color: #9AA5B8;
        font-size: 9px;
        font-weight: 500;
    }

    /* =========================
       ANIMATION
    ========================= */
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =========================
       TABLET
    ========================= */
    @media (min-width: 768px) and (max-width: 1100px) {
        .create-journal-page {
            padding: 24px 28px 36px;
        }

        .journal-hero {
            padding: 23px;
        }

        .schedule-content {
            overflow-x: auto;
        }

        .schedule-table {
            min-width: 720px;
        }
    }

    /* =========================
       MOBILE
    ========================= */
    @media (max-width: 767px) {
        .create-journal-page {
            padding: 0 0 32px;
        }

        .journal-hero {
            min-height: 155px;
            padding: 22px 18px;
            margin-bottom: 16px;
            border-radius: 16px;
        }

        .journal-hero-title {
            font-size: 20px;
        }

        .journal-hero-text {
            font-size: 10px;
        }

        .schedule-card {
            border-radius: 15px;
        }

        .schedule-card-header {
            min-height: 58px;
            padding: 0 16px;
        }

        .schedule-card-note {
            display: none;
        }

        .schedule-content {
            padding: 4px 10px 12px;
            overflow-x: auto;
        }

        .schedule-table {
            min-width: 700px;
        }

        .schedule-table tbody td {
            padding: 11px 10px;
        }
    }

    @media (max-width: 420px) {
        .journal-hero {
            padding: 20px 16px;
        }

        .journal-hero-title {
            font-size: 19px;
        }

        .schedule-card-header {
            padding: 0 13px;
        }

        .schedule-content {
            padding-left: 8px;
            padding-right: 8px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .journal-hero,
        .schedule-card,
        .schedule-table tbody tr,
        .schedule-number,
        .schedule-class,
        .schedule-time svg,
        .journal-action,
        .journal-action svg {
            animation: none;
            transition: none;
        }
    }
</style>

<div class="create-journal-page">

    {{-- HERO --}}
    <section class="journal-hero">

        <span class="journal-hero-badge">
            SEMESTER GANJIL
        </span>

        <h1 class="journal-hero-title">
            Isi Jurnal Mengajar
        </h1>

        <p class="journal-hero-text">
            Lengkapi data aktivitas pembelajaran di kelas secara berkala untuk mempermudah monitoring kurikulum dan presensi siswa.
        </p>

    </section>

    {{-- JADWAL --}}
    <section class="schedule-card">

        <div class="schedule-card-header">

            <div class="schedule-card-title">

                <div class="schedule-icon">
                    <svg
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <rect
                            width="18"
                            height="18"
                            x="3"
                            y="4"
                            rx="2"
                        />
                        <path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                </div>

                <h2>
                    JADWAL & KELAS
                </h2>

            </div>

            <span class="schedule-card-note">
                Sesuai Jadwal Resmi
            </span>

        </div>

        <div class="schedule-content">

            <table class="schedule-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Hari</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Jam Pelajaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($jadwals as $jadwal)

                        <tr>

                            {{-- NO --}}
                            <td>
                                <div class="schedule-number">
                                    {{ $loop->iteration }}
                                </div>
                            </td>

                            {{-- HARI --}}
                            <td>
                                <span class="schedule-day">
                                    {{ $jadwal->hari }}
                                </span>
                            </td>

                            {{-- KELAS --}}
                            <td>
                                <span class="schedule-class">
                                    {{ $jadwal->kelas->nama_kelas ?? '-' }}
                                </span>
                            </td>

                            {{-- MATA PELAJARAN --}}
                            <td>
                                <span class="schedule-subject">
                                    {{ $jadwal->mapel->nama_mapel ?? '-' }}
                                </span>
                            </td>

                            {{-- JAM --}}
                            <td>
                                <span class="schedule-time">

                                    <svg
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9"
                                        />

                                        <path
                                            d="M12 7v5l3 2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>

                                    Jam Ke
                                    {{ $jadwal->jamMulai->jam_ke ?? '-' }}
                                    -
                                    {{ $jadwal->jamSelesai->jam_ke ?? '-' }}

                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td>

                                <a
                                    href="{{ route('guru.jurnal.form', $jadwal->id_jadwal) }}"
                                    class="journal-action"
                                >

                                    <svg
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 5v14M5 12h14"
                                            stroke-linecap="round"
                                        />
                                    </svg>

                                    Isi Jurnal

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr class="empty-row">

                            <td colspan="6">

                                <div class="empty-state">

                                    <div class="empty-icon">

                                        <svg
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            viewBox="0 0 24 24"
                                        >
                                            <rect
                                                width="18"
                                                height="18"
                                                x="3"
                                                y="3"
                                                rx="2"
                                            />

                                            <path
                                                d="M8 8h8M8 12h8M8 16h5"
                                                stroke-linecap="round"
                                            />
                                        </svg>

                                    </div>

                                    <p class="empty-title">
                                        Belum ada jadwal mengajar
                                    </p>

                                    <p class="empty-text">
                                        Jadwal mengajar Anda belum tersedia.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection