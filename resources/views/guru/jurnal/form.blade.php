
@extends($isPiketEntry ? 'layouts.piket' : 'layouts.guru')

@section('title', 'Isi Jurnal Mengajar - Jurnify')
@section('page-title', 'Isi Jurnal Mengajar')
@section('page-subtitle', 'Catat kegiatan pembelajaran hari ini')
@section('tahun_ajaran', trim(($jadwal->semester ?? '').' '.($jadwal->tahun_ajaran ?? '')) ?: 'Tahun ajaran belum diatur')

@section('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0" rel="stylesheet">

<style>
    :root {
        --j-primary: #2D336B;
        --j-secondary: #7886C7;
        --j-tertiary: #A9B5DF;
        --j-soft-blue: #EEF2FF;
        --j-border: #E2E8F0;
        --j-text: #0F172A;
        --j-muted: #64748B;
    }


    .journal-form {
        --primary: #2D336B;
        --secondary: #7886C7;
        --tertiary: #A9B5DF;
        --soft-blue: #EEF2FF;
        --surface: #FBFBFB;
        --border: #E2E8F0;
        --text: #0F172A;
        --muted: #64748B;
    }

    .material-symbols-rounded {
        font-family: 'Material Symbols Rounded';
        font-weight: 400;
        font-style: normal;
        font-size: 22px;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        display: inline-block;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        -webkit-font-feature-settings: 'liga';
        -webkit-font-smoothing: antialiased;
        font-feature-settings: 'liga';
    }

    .journal-page { width: 100%; }

    /* ========================= HERO ========================= */
    .journal-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
        padding: 28px 32px;
        border-radius: 18px;
        background: linear-gradient(135deg, #2D336B 0%, #47539B 100%);
        color: #FFFFFF;
        box-shadow: 0 8px 24px rgba(45, 51, 107, .12);
    }

    .journal-hero::before {
        content: "";
        position: absolute;
        width: 200px;
        height: 200px;
        right: -40px;
        bottom: -70px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
        pointer-events: none;
    }

    .hero-content { position: relative; z-index: 1; max-width: 720px; }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        padding: 5px 12px;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        font-size: 11px;
        font-weight: 700;
    }

    .hero-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: #A9B5DF; }
    .hero-title { margin: 0; font-size: 26px; line-height: 1.3; font-weight: 800; letter-spacing: -.5px; }
    .hero-description { margin: 6px 0 0; color: rgba(255,255,255,.8); font-size: 13px; line-height: 1.6; }

    /* ========================= FORM CARD ========================= */
    .form-card {
        margin-bottom: 20px;
        padding: 24px;
        background: #FFFFFF;
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(15,23,42,.03);
    }

    .section-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }

    .section-icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: var(--soft-blue);
        color: var(--primary);
    }

    .section-icon .material-symbols-rounded { font-size: 21px; }
    .section-title { margin: 0; color: var(--text); font-size: 15px; font-weight: 800; }
    .section-subtitle { margin: 3px 0 0; color: var(--muted); font-size: 11px; line-height: 1.5; }
    .schedule-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .field-group { min-width: 0; }
    .field-label { display: block; margin-bottom: 7px; color: var(--muted); font-size: 11px; font-weight: 700; }

    .field-input, .field-select, .field-textarea {
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: #FFFFFF;
        color: var(--text);
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition: all .2s ease;
    }

    .field-input, .field-select { min-height: 42px; padding: 9px 12px; }
    .field-textarea { min-height: 100px; padding: 10px 12px; resize: vertical; line-height: 1.5; }

    .field-input:focus, .field-select:focus, .field-textarea:focus {
        border-color: var(--secondary);
        box-shadow: 0 0 0 3px rgba(120,134,199,.15);
    }

    .field-input[readonly] { background: #F8FAFC; color: #475569; cursor: default; }

    .readonly-field {
        display: flex;
        align-items: center;
        gap: 8px;
        min-height: 42px;
        padding: 9px 12px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: #F8FAFC;
        color: #334155;
        font-size: 12.5px;
        font-weight: 600;
    }

    .readonly-field .material-symbols-rounded { font-size: 18px; color: var(--secondary); }

    /* ========================= TEACHER STATUS ========================= */
    .teacher-status { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
    .teacher-status-info { display: flex; align-items: center; gap: 12px; }
    .status-buttons { display: flex; gap: 8px; }

    .status-btn {
        min-width: 80px;
        min-height: 38px;
        padding: 8px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: #F1F5F9;
        color: #475569;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s ease;
    }

    .status-btn:hover { border-color: var(--secondary); color: var(--primary); }

    .status-btn.active {
        border-color: var(--primary);
        background: var(--primary);
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(45,51,107,.15);
    }

    /* ========================= STUDENT HEADER ========================= */
    .student-desktop-header { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 20px; }
    .student-title-wrap { display: flex; align-items: center; gap: 12px; }
    .student-search { position: relative; width: 320px; flex-shrink: 0; }

    .student-search .material-symbols-rounded {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary);
        font-size: 20px;
        pointer-events: none;
    }

    .student-search .field-input { padding-left: 40px; }
    .student-mobile-header, .student-mobile-search { display: none; }

    /* ========================= SUMMARY ========================= */
    .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px; }
    .summary-card { padding: 12px 14px; border-radius: 12px; }
    .summary-top { display: flex; align-items: center; justify-content: space-between; }
    .summary-label { color: #64748B; font-size: 11px; font-weight: 700; }
    .summary-icon { font-size: 18px; }
    .summary-number { margin: 4px 0 0; font-size: 20px; font-weight: 800; line-height: 1; }

    .summary-hadir { background: #DCFCE7; }
    .summary-hadir .summary-icon, .summary-hadir .summary-number { color: #15803D; }
    .summary-sakit { background: #FEF3C7; }
    .summary-sakit .summary-icon, .summary-sakit .summary-number { color: #B45309; }
    .summary-izin { background: #E0F2FE; }
    .summary-izin .summary-icon, .summary-izin .summary-number { color: #0369A1; }
    .summary-alpha { background: #FEE2E2; }
    .summary-alpha .summary-icon, .summary-alpha .summary-number { color: #B91C1C; }

    /* ========================= DISPEN ========================= */
    .dispen-notice {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 14px;
        padding: 11px 14px;
        border: 1px solid #E9D5FF;
        border-radius: 11px;
        background: #FAF5FF;
        color: #6B21A8;
        font-size: 11px;
        line-height: 1.5;
    }

    .dispen-notice .material-symbols-rounded { flex-shrink: 0; color: #9333EA; font-size: 19px; }
    .dispen-notice strong { display: block; margin-bottom: 2px; font-weight: 800; }
    .dispen-notice span { color: #581C87; font-weight: 500; }

    /* ========================= ABSENT STUDENTS ========================= */
    .attendance-card { overflow:visible; }
    .attendance-heading { align-items:center; }
    .attendance-heading > div:nth-child(2) { flex:1; min-width:0; }
    .absence-total { padding:7px 11px; border:1px solid #dbeafe; border-radius:999px; background:#eff6ff; color:#2563eb; font-size:12px; font-weight:800; white-space:nowrap; }
    .absence-summary { display:grid; grid-template-columns:repeat(5,minmax(0,1fr)); gap:8px; margin:0 0 14px; }
    .absence-summary span { display:flex; align-items:center; justify-content:space-between; gap:6px; padding:8px 10px; border:1px solid #e8edf5; border-radius:10px; background:#f8fafc; color:#64748b; font-size:11px; font-weight:700; }
    .absence-summary strong { color:#1e293b; font-size:13px; }
    .absence-list { display:grid; gap:10px; }
    .absence-row { display:grid; grid-template-columns:38px minmax(0,1fr) minmax(135px,.72fr) 42px; align-items:center; gap:10px; padding:9px; border:1px solid #e1e7f2; border-radius:15px; background:linear-gradient(110deg,#fff 0%,#fbfcff 100%); }
    .absence-index { display:grid; width:32px; height:32px; place-items:center; border-radius:50%; background:#3b82f6; color:#fff; font-size:14px; font-weight:800; }
    .absence-student-picker { position:relative; min-width:0; }
    .absence-student-search,.absence-status { width:100%; min-height:42px; box-sizing:border-box; padding:9px 12px; border:1px solid #d5dced; border-radius:12px; background:#fff; color:#1e293b; font:inherit; font-size:13px; outline:none; transition:border-color .16s,box-shadow .16s; }
    .absence-student-search:focus,.absence-status:focus { border-color:#6396ff; box-shadow:0 0 0 3px #3b82f61a; }
    .absence-status { cursor:pointer; }
    .absence-status:disabled { appearance:none; background:#f1f5f9; color:#475569; font-weight:700; opacity:1; }
    .absence-options { position:absolute; z-index:15; top:calc(100% + 5px); left:0; right:0; display:none; max-height:220px; overflow:auto; padding:5px; border:1px solid #dbe3f0; border-radius:12px; background:#fff; box-shadow:0 14px 32px #0f172a1a; }
    .absence-options.open { display:grid; gap:3px; }
    .absence-option { display:flex; justify-content:space-between; gap:10px; padding:9px 10px; border:0; border-radius:8px; background:#fff; color:#1e293b; text-align:left; font:inherit; cursor:pointer; }
    .absence-option:hover,.absence-option:focus { background:#eff6ff; outline:none; }
    .absence-option small { color:#64748b; white-space:nowrap; }
    .absence-remove { display:grid; width:38px; height:38px; place-items:center; border:1px solid #fecaca; border-radius:11px; background:#fff1f2; color:#ef4444; cursor:pointer; transition:.16s; }
    .absence-remove:hover { background:#fee2e2; transform:translateY(-1px); }
    .absence-remove:disabled { border-color:#e2e8f0; background:#f1f5f9; color:#94a3b8; cursor:not-allowed; transform:none; }
    .absence-remove .material-symbols-rounded { font-size:20px; }
    .absence-row-error { grid-column:2/-1; color:#b91c1c; font-size:11px; }
    .absence-add { display:flex; width:100%; min-height:42px; align-items:center; justify-content:flex-start; gap:8px; margin-top:12px; padding:9px 13px; border:1px solid #3b82f6; border-radius:10px; background:#eff6ff; color:#2874e8; font:inherit; font-size:13px; font-weight:800; cursor:pointer; transition:.16s; }
    .absence-add:hover { background:#dbeafe; box-shadow:0 4px 12px #3b82f61a; }
    .absence-add .material-symbols-rounded { font-size:22px; }
    .absence-empty { display:flex; min-height:74px; align-items:center; justify-content:center; gap:8px; border:1px dashed #d5deec; border-radius:13px; color:#718096; font-size:12px; text-align:center; }
    .absence-empty .material-symbols-rounded { color:#22a06b; font-size:20px; }
    .absence-footnote { display:flex; align-items:center; gap:6px; margin:12px 0 0; color:#718096; font-size:11px; }
    .absence-footnote .material-symbols-rounded { color:#7886c7; font-size:16px; }
    .absence-search-empty { display:none; margin-top:9px; color:#b45309; font-size:12px; }
    .absence-search-empty.show { display:block; }

    /* ========================= STUDENT TABLE ========================= */
    .student-table-wrap { overflow: hidden; border: 1px solid var(--border); border-radius: 14px; }
    .student-table-scroll { max-height: 420px; overflow-y: auto; }
    .student-table { width: 100%; border-collapse: collapse; }
    .student-list-controls { display: flex; justify-content: center; padding-top: 12px; }
    .student-list-toggle { min-height: 38px; padding: 8px 14px; border: 1px solid var(--border); border-radius: 9px; background: #FFFFFF; color: var(--primary); font: inherit; font-size: 12px; font-weight: 800; cursor: pointer; }
    .student-list-toggle:hover { border-color: var(--secondary); background: var(--soft-blue); }

    .student-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        padding: 12px 16px;
        background: #F8FAFC;
        border-bottom: 1px solid var(--border);
        color: #64748B;
        font-size: 11px;
        font-weight: 800;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .student-table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #F1F5F9;
        color: var(--text);
        font-size: 12.5px;
        vertical-align: middle;
    }

    .student-table tbody tr:last-child td { border-bottom: 0; }
    .student-number { width: 60px; color: #94A3B8 !important; font-weight: 700; }
    .student-nis { width: 130px; color: #64748B !important; font-weight: 600; }
    .student-name { font-weight: 700; }
    .attendance-options { display: flex; gap: 6px; }

    .attendance-btn {
        min-width: 60px;
        padding: 6px 10px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: #FFFFFF;
        color: #64748B;
        font-family: inherit;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all .15s ease;
    }

    .attendance-btn:hover {
        border-color: var(--secondary);
        transform: translateY(-1px);
    }
    .attendance-locked {
        display: inline-flex;
        align-items: center;
        min-height: 34px;
        padding: 7px 10px;
        border: 1px solid #99D7B3;
        border-radius: 8px;
        background: #EEF8F1;
        color: #17633F;
        font-size: 11px;
        font-weight: 700;
    }
    .attendance-locked.sick {
        border-color: #f2d38a;
        background: #fff8e8;
        color: #8b5a12;
    }
    .sick-letter-link {
        color: #2563eb;
        font-size: 12px;
        font-weight: 700;
        text-decoration: underline;
        white-space: nowrap;
    }
    .student-table .attendance-btn[data-status="Hadir"].active {
        border-color: #245C49;
        background: #245C49;
        color: white;
        box-shadow: 0 3px 8px rgba(36, 92, 73, .18);
    }
    .attendance-btn:hover { border-color: var(--secondary); }
    .attendance-btn[data-status="Sakit"].active { border-color: #D97706; background: #D97706; color: #FFFFFF; }
    .attendance-btn[data-status="Izin"].active { border-color: #0284C7; background: #0284C7; color: #FFFFFF; }
    .attendance-btn[data-status="Alpha"].active { border-color: #DC2626; background: #DC2626; color: #FFFFFF; }
    .attendance-value { display: none; }

    .student-row { display: none; }
    .student-row.visible-row { display: table-row !important; }
    .student-search-empty { display: none; padding: 30px 20px; text-align: center; color: var(--muted); }
    .student-search-empty.show { display: block; }

    /* ========================= TASK ========================= */
    .task-grid { display: grid; grid-template-columns: 200px 1fr; gap: 16px; }

    /* ========================= ACTION ========================= */
    .action-buttons { display: flex; align-items: center; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-bottom: 12px; }

    .btn {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 22px;
        border-radius: 12px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .btn .material-symbols-rounded { font-size: 18px; }
    .btn-secondary { border: 1px solid var(--border); background: #FFFFFF; color: #475569; }
    .btn-secondary:hover { background: #F8FAFC; }

    .btn-primary {
        border: 1px solid #2D336B;
        background: #2D336B;
        color: #FFFFFF !important;
        box-shadow: 0 4px 14px rgba(45,51,107,.2);
    }

    .btn-primary:hover { background: #1E234A; border-color: #1E234A; color: #FFFFFF !important; }

    /* ========================= ERROR ========================= */
    .form-error { margin-bottom: 20px; padding: 14px 16px; border: 1px solid #FECACA; border-radius: 12px; background: #FEF2F2; color: #B91C1C; font-size: 12px; }
    .form-error ul { margin: 6px 0 0; padding-left: 18px; }

    /* ========================= REVIEW MODAL ========================= */
    .review-overlay {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: rgba(15,23,42,.58);
        backdrop-filter: blur(5px);
    }

    .review-overlay.show { display: flex; }

    .review-modal {
        width: min(820px, 100%);
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        border-radius: 20px;
        background: #FFFFFF;
        box-shadow: 0 24px 70px rgba(15,23,42,.22);
    }

    .review-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 20px 24px; border-bottom: 1px solid var(--border); }
    .review-header-left { display: flex; align-items: center; gap: 12px; min-width: 0; }

    .review-header-icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: var(--soft-blue);
        color: var(--primary);
    }

    .review-header h2 { margin: 0; color: var(--text); font-size: 17px; font-weight: 800; }
    .review-header p { margin: 3px 0 0; color: var(--muted); font-size: 11px; }

    .review-close {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
        border-radius: 9px;
        background: #FFFFFF;
        color: #64748B;
        cursor: pointer;
    }

    .review-body { padding: 22px 24px; overflow-y: auto; }
    .review-section { margin-bottom: 18px; }
    .review-section:last-child { margin-bottom: 0; }
    .review-section-title { margin: 0 0 10px; color: var(--text); font-size: 12px; font-weight: 800; }
    .review-info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
    .review-info-item { padding: 11px 12px; border: 1px solid var(--border); border-radius: 11px; background: #F8FAFC; }
    .review-info-label { margin-bottom: 4px; color: #94A3B8; font-size: 9.5px; font-weight: 700; }
    .review-info-value { color: var(--text); font-size: 11.5px; font-weight: 800; line-height: 1.35; }

    .review-text-box {
        padding: 12px 14px;
        border: 1px solid var(--border);
        border-radius: 11px;
        background: #F8FAFC;
        color: #334155;
        font-size: 12px;
        line-height: 1.55;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .review-summary { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 8px; }
    .review-summary-item { padding: 10px; border-radius: 10px; text-align: center; }
    .review-summary-item span { display: block; color: #64748B; font-size: 9.5px; font-weight: 700; }
    .review-summary-item strong { display: block; margin-top: 3px; font-size: 18px; line-height: 1; }

    .review-summary-item.hadir { background: #DCFCE7; }
    .review-summary-item.hadir strong { color: #15803D; }
    .review-summary-item.sakit { background: #FEF3C7; }
    .review-summary-item.sakit strong { color: #B45309; }
    .review-summary-item.izin { background: #E0F2FE; }
    .review-summary-item.izin strong { color: #0369A1; }
    .review-summary-item.alpha { background: #FEE2E2; }
    .review-summary-item.alpha strong { color: #B91C1C; }
    .review-summary-item.dispen { background: #FAF5FF; }
    .review-summary-item.dispen strong { color: #7E22CE; }

    .review-student-list { display: flex; flex-direction: column; gap: 7px; }
    .review-student-item { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px 12px; border: 1px solid var(--border); border-radius: 10px; background: #FFFFFF; }
    .review-student-info { min-width: 0; }
    .review-student-name { color: var(--text); font-size: 11.5px; font-weight: 800; }
    .review-student-nis { margin-top: 2px; color: var(--muted); font-size: 9.5px; font-weight: 600; }
    .review-status { flex-shrink: 0; padding: 5px 9px; border-radius: 999px; font-size: 9.5px; font-weight: 800; }

    .review-status.sakit { background: #FEF3C7; color: #B45309; }
    .review-status.izin { background: #E0F2FE; color: #0369A1; }
    .review-status.alpha { background: #FEE2E2; color: #B91C1C; }
    .review-status.dispen { background: #F3E8FF; color: #7E22CE; }

    .review-empty-attendance { padding: 13px; border: 1px dashed var(--border); border-radius: 10px; background: #F8FAFC; color: var(--muted); font-size: 11px; text-align: center; }
    .review-footer { position: relative; z-index: 2; display: flex; justify-content: flex-end; gap: 10px; padding: 16px 24px; border-top: 1px solid var(--border); background: #FFFFFF; }

    .review-footer .review-confirm {
        display: inline-flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        border: 1px solid #2D336B !important;
        background: #2D336B !important;
        color: #FFFFFF !important;
        box-shadow: 0 4px 14px rgba(45,51,107,.2);
    }

    .review-footer .review-confirm:hover { background: #1E234A !important; border-color: #1E234A !important; color: #FFFFFF !important; }

    /* ========================= RESPONSIVE ========================= */
    @media (max-width: 1000px) {
        .schedule-grid { grid-template-columns: repeat(2, 1fr); }
        .review-info-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 767px) {
        .journal-hero { margin-bottom: 14px; padding: 20px 18px; border-radius: 14px; }
        .hero-title { font-size: 20px; }
        .hero-description { font-size: 11.5px; }
        .form-card { margin-bottom: 14px; padding: 16px; border-radius: 14px; }
        .schedule-grid { grid-template-columns: 1fr !important; gap: 12px; }

        .teacher-status { align-items: flex-start; flex-direction: column; gap: 14px; }
        .teacher-status-info { width: 100%; }
        .status-buttons { width: 100%; display: grid; grid-template-columns: 1fr 1fr; }
        .status-btn { width: 100%; }

        .student-desktop-header { display: none; }
        .student-mobile-header { display: block; margin-bottom: 14px; }
        .student-mobile-title { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
        .student-mobile-title h2 { margin: 0; color: #20275F; font-size: 17px; line-height: 1.3; font-weight: 800; }
        .student-mobile-title p { margin: 4px 0 0; color: var(--muted); font-size: 10.5px; }
        .student-total-badge { flex-shrink: 0; padding: 6px 9px; border-radius: 999px; background: var(--primary); color: #FFFFFF; font-size: 9px; font-weight: 800; white-space: nowrap; }

        .student-mobile-search { position: relative; display: block; margin-bottom: 12px; }
        .student-mobile-search .material-symbols-rounded { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 18px; pointer-events: none; }
        .student-mobile-search .field-input { min-height: 42px; padding-left: 38px; }

        .summary-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 6px; margin-bottom: 14px; }
        .summary-card { padding: 9px 4px; text-align: center; }
        .summary-top { justify-content: center; }
        .summary-icon { display: none; }
        .summary-label { font-size: 9px; }
        .summary-number { font-size: 17px; }

        .absence-summary { grid-template-columns:repeat(3,minmax(0,1fr)); gap:5px; }
        .absence-summary span { padding:7px; font-size:10px; }
        .absence-row { grid-template-columns:32px minmax(0,1fr) minmax(95px,.72fr) 38px; gap:7px; padding:7px; }
        .absence-index { width:28px; height:28px; font-size:12px; }
        .absence-student-search,.absence-status { min-height:40px; padding:8px 9px; font-size:12px; }
        .absence-remove { width:35px; height:35px; }
        .absence-total { font-size:10px; padding:6px 8px; }
        .absence-footnote { align-items:flex-start; line-height:1.5; }
        .student-table-wrap { overflow: visible; border: 0; }
        .student-table-scroll { max-height: none; overflow: visible; }
        .student-table, .student-table tbody, .student-table tr, .student-table td { display: block; width: 100%; }
        .student-table { border-collapse: separate; }
        .student-table thead { display: none; }

        .student-table tbody tr.student-row {
            display: none;
            margin-bottom: 10px;
            padding: 13px;
            border: 1px solid var(--border);
            border-radius: 13px;
            background: #FFFFFF;
            box-shadow: 0 2px 8px rgba(15,23,42,.035);
        }

        .student-table tbody tr.student-row.visible-row { display: block !important; }
        .student-table td.student-number, .student-table td.student-nis { display: none !important; }
        .student-table td.student-name { display: block; width: 100%; padding: 0; border: 0; }
        .student-name-text { color: var(--text); font-size: 13px; font-weight: 800; }

        .student-table td.attendance-cell { display: block; width: 100%; padding: 10px 0 0; border: 0; }
        .attendance-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 7px; width: 100%; }
        .attendance-btn { width: 100%; min-width: 0; min-height: 38px; border-radius: 99px; font-size: 10.5px; }

        .task-grid { grid-template-columns: 1fr; }

        .action-buttons {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 90;
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 8px;
            margin: 0;
            padding: 10px 12px;
            padding-bottom: calc(10px + env(safe-area-inset-bottom));
            background: rgba(255,255,255,.97);
            border-top: 1px solid var(--border);
            box-shadow: 0 -4px 18px rgba(15,23,42,.08);
        }

        .action-buttons .btn { width: 100%; min-height: 42px; padding: 9px 12px; border-radius: 10px; font-size: 11px; }
        .action-buttons .btn .material-symbols-rounded { font-size: 18px; }

        .journal-form { padding-bottom: 72px; }

        .review-overlay { align-items: flex-end; padding: 0; }
        .review-modal { width: 100%; max-height: 94vh; border-radius: 20px 20px 0 0; }
        .review-header { padding: 16px; }
        .review-header h2 { font-size: 15px; }
        .review-header p { font-size: 10px; }
        .review-body { padding: 16px; }
        .review-info-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
        .review-summary { grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 5px; }
        .review-summary-item { padding: 8px 4px; }
        .review-summary-item span { font-size: 8px; }
        .review-summary-item strong { font-size: 16px; }
        .review-footer { display: grid; grid-template-columns: 1fr 1.5fr; padding: 12px 16px; }
        .review-footer .btn { width: 100%; min-height: 42px; padding: 9px 10px; font-size: 10.5px; }
        .review-footer .btn .material-symbols-rounded { font-size: 17px; }
    }
</style>
@endsection

@section('content')
<div class="journal-form">
    <div class="journal-page">
        {{-- HERO --}}
        <section class="journal-hero">
            <div class="hero-content">
                <div class="hero-badge"><span class="hero-badge-dot"></span><span>Jurnal Pembelajaran</span></div>
                <h1 class="hero-title">{{ !empty($jurnal) ? 'Perbarui Jurnal Mengajar' : 'Isi Jurnal Mengajar' }}</h1>
                <p class="hero-description">{{ !empty($jurnal) ? 'Perbarui materi atau kehadiran siswa selama jadwal mengajar masih berlangsung.' : ($isPiketEntry ? 'Lengkapi jurnal untuk guru yang berhalangan hadir.' : 'Lengkapi data pembelajaran, materi, dan kehadiran siswa untuk mencatat kegiatan belajar mengajar hari ini.') }}</p>
            </div>
        </section>

        {{-- ERROR --}}
        @if($errors->any())
            <div class="form-error">
                <strong>Periksa kembali data yang diisi.</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="form-success" role="status">
                ✓ {{ session('success') }}
            </div>
        @endif
        <form action="{{ !empty($jurnal) ? route('guru.jurnal.update', $jurnal) : route($isPiketEntry ? 'piket.jurnal.store' : 'guru.jurnal.store') }}" method="POST" id="journalForm">
            @csrf
            @if(!empty($jurnal))
                @method('PUT')
            @endif
            <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">

            {{-- JADWAL --}}
            <section class="form-card">
                <div class="section-header">
                    <div class="section-icon"><span class="material-symbols-rounded">calendar_month</span></div>
                    <div>
                        <h3 class="section-title">Jadwal Pembelajaran</h3>
                        <p class="section-subtitle">Informasi kelas dan mata pelajaran</p>
                    </div>
                </div>

                <div class="schedule-grid">
                    <div class="field-group">
                        <label class="field-label">Tahun Ajaran</label>
                        <div class="readonly-field"><span class="material-symbols-rounded">school</span>{{ $jadwal->tahun_ajaran ?? '-' }}</div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $jurnal?->tanggal?->format('Y-m-d') ?? date('Y-m-d') }}" class="field-input" readonly required>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Kelas</label>
                        <div class="readonly-field"><span class="material-symbols-rounded">groups</span>{{ $jadwal->kelas->nama_kelas ?? '-' }}</div>
                    </div>
                    @if($isPiketEntry)
                        <div class="field-group">
                            <label class="field-label">Guru Pengajar</label>
                            <div class="readonly-field"><span class="material-symbols-rounded">person</span>{{ $jadwal->guru->nama_guru ?? '-' }}</div>
                        </div>
                    @endif
                    <div class="field-group">
                        <label class="field-label">Jam Pelajaran</label>
                        <div class="readonly-field"><span class="material-symbols-rounded">schedule</span>Jam Ke-{{ $jadwal->jamMulai?->jam_ke ?? '-' }} - {{ $jadwal->jamSelesai?->jam_ke ?? '-' }} <span>({{ substr($jamMulaiDisplay?->jam_mulai ?? '', 0, 5) }}–{{ substr($jamSelesaiDisplay?->jam_selesai ?? '', 0, 5) }})</span></div>
                    </div>
                </div>

                <div style="margin-top:16px;">
                    <div class="field-group">
                        <label class="field-label">Mata Pelajaran</label>
                        <div class="readonly-field"><span class="material-symbols-rounded">menu_book</span>{{ $jadwal->mapel->nama_mapel ?? '-' }}</div>
                    </div>
                </div>
            </section>

            @if($isPiketEntry)
                <section class="form-card">
                    <div class="section-header">
                        <div class="section-icon"><span class="material-symbols-rounded">person_alert</span></div>
                        <div>
                            <h3 class="section-title">Kehadiran Guru</h3>
                            <p class="section-subtitle">Pilih keterangan kehadiran guru yang jurnalnya sedang diisikan.</p>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="statusGuru">Status Kehadiran Guru</label>
                        <select id="statusGuru" name="status_guru" class="field-select" required>
                            <option value="">Pilih status</option>
                            <option value="Sakit" {{ old('status_guru') === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Izin" {{ old('status_guru') === 'Izin' ? 'selected' : '' }}>Izin</option>
                        </select>
                        @error('status_guru')<small style="color:#b91c1c;font-size:12px">{{ $message }}</small>@enderror
                    </div>
                </section>
            @endif

            {{-- MATERI --}}
            <section class="form-card">
                <div class="section-header">
                    <div class="section-icon"><span class="material-symbols-rounded">menu_book</span></div>
                    <div>
                        <h3 class="section-title">Materi Pembelajaran</h3>
                        <p class="section-subtitle">Tuliskan materi yang disampaikan</p>
                    </div>
                </div>
                <textarea name="materi" class="field-textarea" placeholder="Contoh: Pengenalan HTML dan struktur dasar halaman web" required>{{ old('materi', $jurnal?->materi ?? '') }}</textarea>
            </section>

            {{-- KETERANGAN --}}
            <section class="form-card">
                <div class="section-header">
                    <div class="section-icon"><span class="material-symbols-rounded">notes</span></div>
                    <div>
                        <h3 class="section-title">Keterangan</h3>
                        <p class="section-subtitle">Tambahkan keterangan pembelajaran</p>
                    </div>
                </div>
                <textarea name="keterangan" class="field-textarea" placeholder="Contoh: Kelas kondusif, ulangan harian, dan lain-lain." required>{{ old('keterangan', $jurnal?->keterangan ?? '') }}</textarea>
            </section>

            {{-- KEHADIRAN SISWA --}}
            @php
                $submittedAbsences = old('absensi', $savedAbsences ?? []);
                $initialAttendanceAbsences = [];
                $journalStudentRoster = $siswa->map(fn ($student) => [
                    'id' => $student->id_siswa,
                    'name' => $student->nama_siswa,
                    'nis' => $student->nis,
                ])->values()->all();

                foreach ($siswa as $student) {
                    $studentId = (string) $student->id_siswa;
                    $sickReport = $activeSickReports->get($student->id_siswa);
                    $hasDispen = $activeDispenSiswa->contains($student->id_siswa);
                    $savedStatus = $submittedAbsences[$studentId] ?? null;
                    $lockedStatus = $hasDispen ? 'Dispen' : ($sickReport ? ($sickReport->jenis === 'izin' ? 'Izin' : 'Sakit') : null);
                    $status = $lockedStatus ?? ($savedStatus && $savedStatus !== 'Hadir' ? $savedStatus : null);

                    if ($status) {
                        $initialAttendanceAbsences[] = [
                            'id' => $student->id_siswa,
                            'name' => $student->nama_siswa,
                            'nis' => $student->nis,
                            'status' => $status,
                            'locked' => (bool) $lockedStatus,
                        ];
                    }
                }
            @endphp
            <section class="form-card attendance-card">
                <div class="section-header attendance-heading">
                    <div class="section-icon"><span class="material-symbols-rounded">person_alert</span></div>
                    <div>
                        <h3 class="section-title">Siswa Tidak Hadir</h3>
                        <p class="section-subtitle">{{ $jadwal->kelas->nama_kelas ?? '-' }} · {{ $siswa->count() }} siswa terdaftar. Tambahkan siswa yang tidak hadir.</p>
                    </div>
                    <span class="absence-total" id="absenceTotal">0 siswa</span>
                </div>

                <div class="absence-summary" aria-label="Ringkasan kehadiran kelas">
                    <span>Hadir <strong id="hadirCount">{{ $siswa->count() - count($initialAttendanceAbsences) }}</strong></span>
                    <span>Sakit <strong id="sakitCount">{{ collect($initialAttendanceAbsences)->where('status', 'Sakit')->count() }}</strong></span>
                    <span>Izin <strong id="izinCount">{{ collect($initialAttendanceAbsences)->where('status', 'Izin')->count() }}</strong></span>
                    <span>Alpa <strong id="alpaCount">{{ collect($initialAttendanceAbsences)->where('status', 'Alpha')->count() }}</strong></span>
                    <span>Dispen <strong id="dispenCount">{{ collect($initialAttendanceAbsences)->where('status', 'Dispen')->count() }}</strong></span>
                </div>
                <div class="absence-list" id="absenceRows" data-roster-count="{{ $siswa->count() }}"></div>
                <div class="absence-empty" id="absenceEmpty">
                    <span class="material-symbols-rounded">task_alt</span>
                    <span>Belum ada siswa tidak hadir. Semua siswa dianggap hadir.</span>
                </div>
                <button type="button" class="absence-add" id="addAbsenceRow">
                    <span class="material-symbols-rounded">add</span>Tambah Siswa Tidak Hadir
                </button>
                <p class="absence-footnote"><span class="material-symbols-rounded">info</span>Data izin, sakit, atau dispen yang dicatat Piket hari ini akan terisi otomatis.</p>
                <div class="absence-search-empty" id="absenceSearchEmpty">Siswa tidak ditemukan di kelas ini.</div>
            </section>
            <script>
                window.journalStudentRoster = @json($journalStudentRoster);
                window.initialJournalAbsences = @json($initialAttendanceAbsences);
            </script>

            {{-- TUGAS & CATATAN --}}
            <section class="form-card" id="taskNotesSection">
                <div class="section-header">
                    <div class="section-icon"><span class="material-symbols-rounded">assignment</span></div>
                    <div>
                        <h3 class="section-title">Tugas & Catatan</h3>
                        <p class="section-subtitle">Tambahkan tugas atau catatan pembelajaran jika diperlukan</p>
                    </div>
                </div>

                <div class="task-grid">
                    <div class="field-group">
                        <label class="field-label">Ada Tugas?</label>
                        <select name="ada_tugas" id="adaTugas" class="field-select">
                            <option value="Tidak" {{ old('ada_tugas', $jurnal?->ada_tugas ?? 'Tidak') === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                            <option value="Ya" {{ old('ada_tugas', $jurnal?->ada_tugas) === 'Ya' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Deskripsi Tugas</label>
                        <textarea name="deskripsi_tugas" class="field-textarea" placeholder="Tuliskan deskripsi tugas jika ada...">{{ old('deskripsi_tugas', $jurnal?->deskripsi_tugas ?? '') }}</textarea>
                    </div>
                </div>

            </section>

            @if($isPiketEntry)
                <section class="form-card">
                    <div class="section-header">
                        <div class="section-icon"><span class="material-symbols-rounded">badge</span></div>
                        <div>
                            <h3 class="section-title">Data Pengisi Jurnal</h3>
                            <p class="section-subtitle">Identitas petugas piket akan tersimpan pada jurnal ini.</p>
                        </div>
                    </div>
                    <div class="schedule-grid">
                        <div class="field-group"><span class="field-label">Nama</span><div class="readonly-field">{{ auth()->user()->nama_user }}</div></div>
                        <div class="field-group"><span class="field-label">Username</span><div class="readonly-field">{{ auth()->user()->username }}</div></div>
                        <div class="field-group"><span class="field-label">Nomor WhatsApp</span><div class="readonly-field">{{ auth()->user()->no_wa ?: '-' }}</div></div>
                    </div>
                </section>
            @endif

            {{-- ACTION --}}
            <div class="action-buttons">
                <a href="{{ !empty($jurnal) ? route('guru.jurnal.show', $jurnal) : route($isPiketEntry ? 'piket.jurnal.create' : 'guru.jurnal.create', $isPiketEntry ? ['id_kelas' => $jadwal->id_kelas] : []) }}" class="btn btn-secondary">
                    <span class="material-symbols-rounded">arrow_back</span> Batal
                </a>
                <button type="submit" class="btn btn-primary" id="reviewButton">
                    <span class="material-symbols-rounded">visibility</span> {{ !empty($jurnal) ? 'Review Perubahan' : 'Review Jurnal' }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- REVIEW MODAL --}}
<div class="review-overlay" id="reviewOverlay" aria-hidden="true">
    <div class="review-modal" role="dialog" aria-modal="true" aria-labelledby="reviewTitle">
        <div class="review-header">
            <div class="review-header-left">
                <div class="review-header-icon"><span class="material-symbols-rounded">fact_check</span></div>
                <div>
                    <h2 id="reviewTitle">Review Jurnal</h2>
                    <p>Periksa kembali data sebelum jurnal disimpan.</p>
                </div>
            </div>
            <button type="button" class="review-close" onclick="closeReview()"><span class="material-symbols-rounded">close</span></button>
        </div>

        <div class="review-body">
            <div class="review-section">
                <h3 class="review-section-title">Informasi Pembelajaran</h3>
                <div class="review-info-grid">
                    <div class="review-info-item">
                        <div class="review-info-label">Tanggal</div>
                        <div class="review-info-value" id="reviewTanggal">-</div>
                    </div>
                    <div class="review-info-item">
                        <div class="review-info-label">Kelas</div>
                        <div class="review-info-value" id="reviewKelas">{{ $jadwal->kelas->nama_kelas ?? '-' }}</div>
                    </div>
                    <div class="review-info-item">
                        <div class="review-info-label">Mata Pelajaran</div>
                        <div class="review-info-value" id="reviewMapel">{{ $jadwal->mapel->nama_mapel ?? '-' }}</div>
                    </div>
                    <div class="review-info-item">
                        <div class="review-info-label">Jam Pelajaran</div>
                        <div class="review-info-value" id="reviewJam">Jam Ke-{{ $jadwal->jamMulai?->jam_ke ?? '-' }} - {{ $jadwal->jamSelesai?->jam_ke ?? '-' }} ({{ substr($jamMulaiDisplay?->jam_mulai ?? '', 0, 5) }}–{{ substr($jamSelesaiDisplay?->jam_selesai ?? '', 0, 5) }})</div>
                    </div>
                </div>
            </div>

            <div class="review-section">
                <h3 class="review-section-title">Materi Pembelajaran</h3>
                <div class="review-text-box" id="reviewMateri">-</div>
            </div>

            <div class="review-section">
                <h3 class="review-section-title">Keterangan</h3>
                <div class="review-text-box" id="reviewKeterangan">-</div>
            </div>

            <div class="review-section">
                <h3 class="review-section-title">Ringkasan Kehadiran Siswa</h3>
                <div class="review-summary">
                    <div class="review-summary-item hadir"><span>Hadir</span><strong id="reviewHadir">0</strong></div>
                    <div class="review-summary-item sakit"><span>Sakit</span><strong id="reviewSakit">0</strong></div>
                    <div class="review-summary-item izin"><span>Izin</span><strong id="reviewIzin">0</strong></div>
                    <div class="review-summary-item alpha"><span>Alpa</span><strong id="reviewAlpha">0</strong></div>
                    <div class="review-summary-item dispen"><span>Dispen</span><strong id="reviewDispen">0</strong></div>
                </div>
            </div>

            <div class="review-section">
                <h3 class="review-section-title">Siswa yang Tidak Hadir</h3>
                <div class="review-student-list" id="reviewStudentList"></div>
            </div>

            <div class="review-section" id="reviewTaskSection" style="display:none;">
                <h3 class="review-section-title">Tugas</h3>
                <div class="review-text-box" id="reviewTugas">-</div>
            </div>
        </div>

        <div class="review-footer">
            <button type="button" class="btn btn-secondary" onclick="closeReview()"><span class="material-symbols-rounded">edit</span> Kembali Edit</button>
            <button type="button" class="btn review-confirm" id="confirmSubmitButton" onclick="confirmJournalSubmit()"><span class="material-symbols-rounded">check_circle</span> Konfirmasi & Simpan</button>
        </div>
    </div>
</div>
@endsection


<script>
  /* =========================================================
     ABSENT STUDENTS
  ========================================================= */
  const absenceStatuses = ['Sakit', 'Izin', 'Alpha'];
  function getAbsenceRoster() { return window.journalStudentRoster || []; }

  function escapeHtml(value) {
    if (value === null || value === undefined) return '';
    return String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
  }

  function selectedStudentIds(exceptRow = null) {
    return new Set([...document.querySelectorAll('.absence-row')]
      .filter(row => row !== exceptRow && row.dataset.studentId)
      .map(row => String(row.dataset.studentId)));
  }

  function renderStudentOptions(row, keyword = '') {
    const list = row.querySelector('.absence-options');
    const query = keyword.trim().toLocaleLowerCase('id');
    const selected = selectedStudentIds(row);
    const matches = getAbsenceRoster().filter(student => {
      if (selected.has(String(student.id))) return false;
      return !query || student.name.toLocaleLowerCase('id').includes(query);
    });

    list.innerHTML = matches.length
      ? matches.map(student => `<button type="button" class="absence-option" data-student-id="${escapeHtml(student.id)}"><span>${escapeHtml(student.name)}</span></button>`).join('')
      : '<span class="absence-option" aria-disabled="true">Siswa tidak ditemukan</span>';
    list.classList.add('open');
    list.querySelectorAll('[data-student-id]').forEach(option => {
      option.addEventListener('mousedown', event => event.preventDefault());
      option.addEventListener('click', () => chooseAbsenceStudent(row, option.dataset.studentId));
    });
  }

  function chooseAbsenceStudent(row, id) {
    const student = getAbsenceRoster().find(item => String(item.id) === String(id));
    if (!student || selectedStudentIds(row).has(String(id))) return;

    row.dataset.studentId = String(student.id);
    row.dataset.studentName = student.name;
    row.dataset.studentNis = student.nis || '';
    row.querySelector('.absence-student-search').value = student.name;
    row.querySelector('.absence-student-search').setCustomValidity('');
    row.querySelector('.absence-options').classList.remove('open');
    syncAbsenceInput(row);
    refreshAbsenceOptions();
    updateSummary();
  }

  function syncAbsenceInput(row) {
    let hidden = row.querySelector('.absence-value');
    if (!hidden) {
      hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.className = 'absence-value';
      row.appendChild(hidden);
    }
    if (row.dataset.studentId) {
      hidden.name = `absensi[${row.dataset.studentId}]`;
      hidden.value = row.querySelector('.absence-status').value;
    } else {
      hidden.removeAttribute('name');
      hidden.value = '';
    }
  }

  function addAbsenceRow(student = null) {
    const list = document.getElementById('absenceRows');
    if (!list || (student && selectedStudentIds().has(String(student.id)))) return;

    const row = document.createElement('div');
    row.className = 'absence-row';
    row.dataset.studentId = student ? String(student.id) : '';
    row.dataset.studentName = student?.name || '';
    row.dataset.studentNis = student?.nis || '';
    const locked = Boolean(student?.locked);
    const status = student?.status || '';
    const statusOptions = [
      `<option value="">Pilih status</option>`,
      ...absenceStatuses.map(item => `<option value="${item}" ${status === item ? 'selected' : ''}>${item === 'Alpha' ? 'Alpa' : item}</option>`),
      ...(status === 'Dispen' ? ['<option value="Dispen" selected>Dispen</option>'] : []),
    ].join('');

    row.innerHTML = `
      <span class="absence-index"></span>
      <div class="absence-student-picker">
        <input type="search" class="absence-student-search" placeholder="Ketik nama siswa..." autocomplete="off" aria-label="Cari nama siswa" required>
        <div class="absence-options" role="listbox"></div>
      </div>
      <select class="absence-status" aria-label="Status ketidakhadiran" required ${locked ? 'disabled' : ''}>${statusOptions}</select>
      <button type="button" class="absence-remove" aria-label="Hapus siswa tidak hadir" ${locked ? 'disabled title="Status dari Piket"' : ''}><span class="material-symbols-rounded">delete</span></button>
      ${locked ? '<span class="absence-row-error" style="color:#64748b">Status tercatat otomatis oleh Piket</span>' : ''}
    `;

    const search = row.querySelector('.absence-student-search');
    const options = row.querySelector('.absence-options');
    const select = row.querySelector('.absence-status');
    search.addEventListener('focus', () => renderStudentOptions(row, search.value));
    search.addEventListener('input', () => {
      row.dataset.studentId = '';
      row.dataset.studentName = '';
      row.dataset.studentNis = '';
      syncAbsenceInput(row);
      search.setCustomValidity('Pilih nama siswa dari daftar kelas.');
      renderStudentOptions(row, search.value);
      refreshAbsenceOptions();
      updateSummary();
    });
    search.addEventListener('change', () => {
      if (!row.dataset.studentId) search.setCustomValidity('Pilih nama siswa dari daftar kelas.');
    });
    select.addEventListener('change', () => { syncAbsenceInput(row); updateSummary(); });
    row.querySelector('.absence-remove').addEventListener('click', () => {
      row.remove();
      refreshAbsenceOptions();
      updateSummary();
    });
    document.addEventListener('click', event => {
      if (!row.contains(event.target)) options.classList.remove('open');
    });

    list.appendChild(row);
    if (student) {
      search.value = student.name;
      search.setCustomValidity('');
      syncAbsenceInput(row);
    } else {
      search.focus();
      renderStudentOptions(row, '');
    }
    updateAbsenceRows();
    refreshAbsenceOptions();
    updateSummary();
  }

  function updateAbsenceRows() {
    const rows = [...document.querySelectorAll('.absence-row')];
    rows.forEach((row, index) => { row.querySelector('.absence-index').textContent = index + 1; });
    const empty = document.getElementById('absenceEmpty');
    if (empty) empty.style.display = rows.length ? 'none' : 'flex';
    const total = document.getElementById('absenceTotal');
    if (total) total.textContent = `${rows.filter(row => row.dataset.studentId).length} siswa`;
  }

  function refreshAbsenceOptions() {
    document.querySelectorAll('.absence-row').forEach(row => {
      const search = row.querySelector('.absence-student-search');
      if (document.activeElement === search && row.querySelector('.absence-options').classList.contains('open')) {
        renderStudentOptions(row, search.value);
      }
    });
  }

  function validateAbsenceRows() {
    let valid = true;
    document.querySelectorAll('.absence-row').forEach(row => {
      const search = row.querySelector('.absence-student-search');
      if (!row.dataset.studentId) {
        search.setCustomValidity('Pilih nama siswa dari daftar kelas.');
        valid = false;
      } else {
        search.setCustomValidity('');
      }
      syncAbsenceInput(row);
    });
    return valid;
  }

  function updateSummary() {
    const counts = { Sakit: 0, Izin: 0, Alpha: 0, Dispen: 0 };
    document.querySelectorAll('.absence-row[data-student-id]').forEach(row => {
      const status = row.querySelector('.absence-status').value;
      if (Object.prototype.hasOwnProperty.call(counts, status)) counts[status]++;
    });
    const absent = Object.values(counts).reduce((sum, count) => sum + count, 0);
    const classTotal = Number(document.getElementById('absenceRows')?.dataset.rosterCount || 0);
    const values = { hadir: Math.max(0, classTotal - absent), sakit: counts.Sakit, izin: counts.Izin, alpa: counts.Alpha, dispen: counts.Dispen };
    Object.entries(values).forEach(([key, value]) => {
      const element = document.getElementById(`${key}Count`);
      if (element) element.textContent = value;
    });
    updateAbsenceRows();
  }

  function getStatusClass(status) {
    switch (status) {
      case 'Sakit': return 'sakit';
      case 'Izin': return 'izin';
      case 'Alpha': return 'alpha';
      case 'Dispen': return 'dispen';
      default: return '';
    }
  }

  function buildReviewStudentList() {
    const container = document.getElementById('reviewStudentList');
    if (!container) return;
    container.innerHTML = '';
    let count = 0;
    document.querySelectorAll('.absence-row[data-student-id]').forEach(row => {
      const status = row.querySelector('.absence-status').value;
      if (!status) return;
      const student = getAbsenceRoster().find(item => String(item.id) === row.dataset.studentId);
      const displayStatus = status === 'Alpha' ? 'Alpa' : status === 'Dispen' ? 'Dispensasi' : status;
      const item = document.createElement('div');
      item.className = 'review-student-item';
      item.innerHTML = `<div class="review-student-info"><div class="review-student-name">${escapeHtml(student?.name || row.dataset.studentName)}</div></div><span class="review-status ${getStatusClass(status)}">${escapeHtml(displayStatus)}</span>`;
      container.appendChild(item);
      count++;
    });
    if (!count) container.innerHTML = '<div class="review-empty-attendance">Semua siswa hadir.</div>';
  }

  /* =========================================================
     REVIEW MODAL
  ========================================================= */
  function prepareReview() {
    const tanggalInput = document.querySelector('input[name="tanggal"]');
    const materiInput = document.querySelector('textarea[name="materi"]');
    const keteranganInput = document.querySelector('textarea[name="keterangan"]');

    const tanggal = tanggalInput ? tanggalInput.value : '-';
    const materi = materiInput ? materiInput.value.trim() : '';
    const keterangan = keteranganInput ? keteranganInput.value.trim() : '';

    const reviewTanggal = document.getElementById('reviewTanggal');
    const reviewMateri = document.getElementById('reviewMateri');
    const reviewKeterangan = document.getElementById('reviewKeterangan');
    if (reviewTanggal) reviewTanggal.textContent = tanggal || '-';
    if (reviewMateri) reviewMateri.textContent = materi || '-';
    if (reviewKeterangan) reviewKeterangan.textContent = keterangan || '-';

    const hadirCount = document.getElementById('hadirCount');
    const sakitCount = document.getElementById('sakitCount');
    const izinCount = document.getElementById('izinCount');
    const alphaCount = document.getElementById('alpaCount');
    const dispenCount = document.getElementById('dispenCount');

    document.getElementById('reviewHadir').textContent = hadirCount?.textContent || '0';
    document.getElementById('reviewSakit').textContent = sakitCount?.textContent || '0';
    document.getElementById('reviewIzin').textContent = izinCount?.textContent || '0';
    document.getElementById('reviewAlpha').textContent = alphaCount?.textContent || '0';
    document.getElementById('reviewDispen').textContent = dispenCount?.textContent || '0';

    buildReviewStudentList();

    const taskSection = document.getElementById('reviewTaskSection');
    const taskSelect = document.getElementById('adaTugas');
    const taskTextarea = document.querySelector('textarea[name="deskripsi_tugas"]');
    const taskReview = document.getElementById('reviewTugas');

    if (taskSection) taskSection.style.display = '';
    const adaTugas = taskSelect ? taskSelect.value : 'Tidak';
    const deskripsi = taskTextarea ? taskTextarea.value.trim() : '';
    if (taskReview) {
      taskReview.textContent = adaTugas === 'Ya' ? (deskripsi || 'Tugas belum diisi.') : 'Tidak ada tugas.';
    }

  }

  function openReview() {
    prepareReview();
    const overlay = document.getElementById('reviewOverlay');
    if (!overlay) return;

    overlay.classList.add('show');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeReview() {
    const overlay = document.getElementById('reviewOverlay');
    if (!overlay) return;

    overlay.classList.remove('show');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  function confirmJournalSubmit() {
    const form = document.getElementById('journalForm');
    const button = document.getElementById('confirmSubmitButton');
    if (!form) return;

    if (button) {
      button.disabled = true;
      button.style.opacity = '.7';
      button.style.cursor = 'not-allowed';
      button.innerHTML = `<span class="material-symbols-rounded">hourglass_top</span> Menyimpan...`;
    }
    form.submit();
  }

  document.addEventListener('DOMContentLoaded', function () {
    // Initial state: otomatis isi izin/sakit/dispen dari laporan Piket hari ini.
    (window.initialJournalAbsences || []).forEach(addAbsenceRow);
    document.getElementById('addAbsenceRow')?.addEventListener('click', () => addAbsenceRow());
    updateSummary();

    // Dispen yang sudah disetujui pada tanggal ini selalu dicatat otomatis.

    // Intercept Form Submit -> Open Review Modal
    const form = document.getElementById('journalForm');
    if (form) {
      form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (!validateAbsenceRows() || !form.checkValidity()) {
          form.reportValidity();
          return;
        }
        openReview();
      });
    }

    // Modal Overlay Events
    const overlay = document.getElementById('reviewOverlay');
    if (overlay) {
      overlay.addEventListener('click', function (event) {
        if (event.target === overlay) closeReview();
      });
    }

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && overlay && overlay.classList.contains('show')) {
        closeReview();
      }
    });
  });
</script>