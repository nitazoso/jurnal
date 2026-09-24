
@extends('layouts.guru')

@section('title', 'Isi Jurnal Mengajar - Jurnify')
@section('page-title', 'Isi Jurnal Mengajar')
@section('page-subtitle', 'Catat kegiatan pembelajaran hari ini')
@section('tahun_ajaran', $jadwal->tahun_ajaran ?? 'Ganjil 2026/2027')

@section('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0" rel="stylesheet">

<style>
.qr-verification-card { border: 1px solid #dbe4f0; border-radius: 16px; background: #fff; margin-bottom: 20px; padding: 20px; }
    .qr-verification-head { align-items: center; display: flex; gap: 12px; justify-content: space-between; }
    .qr-verification-status { align-items: center; border-radius: 999px; display: inline-flex; font-size: 12px; font-weight: 800; gap: 6px; padding: 8px 12px; }
    .qr-verification-status.pending { background: #fff7ed; color: #9a3412; }
    .qr-verification-status.success { background: #dcfce7; color: #166534; }
    .qr-verification-success { background: #f0fdf4; border: 1px solid #86efac; border-radius: 10px; color: #166534; font-size: 13px; font-weight: 700; margin-top: 14px; padding: 12px 14px; }
    .qr-scan-button { background: #1d4ed8; border: 0; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 700; padding: 10px 14px; }
    .qr-scan-button:disabled { cursor: not-allowed; opacity: .65; }
    .qr-scanner { background: #0f172a; border-radius: 12px; display: none; margin-top: 16px; max-width: 420px; min-height: 280px; overflow: hidden; width: 100%; }
    .qr-scanner.open { display: block; }
    .qr-scanner video { display: block; min-height: 280px; object-fit: cover; width: 100% !important; }
    .qr-scanner-status { color: #e2e8f0; font-size: 13px; padding: 16px; text-align: center; }
    .qr-scan-message { color: #b45309; font-size: 13px; font-weight: 700; margin-top: 8px; }
    .qr-close-button { background: #e2e8f0; border: 0; border-radius: 8px; cursor: pointer; margin-top: 10px; padding: 8px 12px; }

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

    /* ========================= STUDENT TABLE ========================= */
    .student-table-wrap { overflow: hidden; border: 1px solid var(--border); border-radius: 14px; }
    .student-table-scroll { max-height: 420px; overflow-y: auto; }
    .student-table { width: 100%; border-collapse: collapse; }

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

    .review-summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
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
        .review-summary { grid-template-columns: repeat(4, 1fr); gap: 5px; }
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
                <h1 class="hero-title">Isi Jurnal Mengajar</h1>
                <p class="hero-description">Lengkapi data pembelajaran, materi, dan kehadiran siswa untuk mencatat kegiatan belajar mengajar hari ini.</p>
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
        <section class="qr-verification-card">
            <div class="qr-verification-head">
                <div>
                    <h3 class="section-title">Verifikasi Kelas</h3>
                    <p class="section-subtitle">Scan QR kelas sebelum mengisi dan menyimpan jurnal.</p>
                </div>
                <span id="qrVerificationStatus" class="qr-verification-status {{ $qrVerified ? 'success' : 'pending' }}">
                    <span>{{ $qrVerified ? '✓' : '!' }}</span>
                    <span>{{ $qrVerified ? 'Sudah scan - Hadir' : 'Belum scan QR' }}</span>
                </span>
            </div>
            <button type="button" id="openQrScanner" class="qr-scan-button" {{ $qrVerified ? 'disabled' : '' }}>
                {{ $qrVerified ? 'Sudah Scan' : 'Scan QR Kelas' }}
            </button>
            <div id="qrScanner" class="qr-scanner"></div>
            <p id="qrScanMessage" class="qr-scan-message"></p>
            <button type="button" id="closeQrScanner" class="qr-close-button" style="display: none">Tutup Kamera</button>
            @if (request('scan') === 'success')
                <div class="qr-verification-success" role="status">
                    ✓ Scan QR berhasil. Kehadiran guru sudah tersimpan untuk kelas {{ $jadwal->kelas->nama_kelas ?? '-' }}.
                </div>
            @endif
            @if (session('success'))
                <div class="qr-verification-success" role="status">
                    ✓ {{ session('success') }}
                </div>
            @endif
        </section>
        <form action="{{ route('guru.jurnal.store') }}" method="POST" id="journalForm">
            @csrf
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
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="field-input" readonly required>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Kelas</label>
                        <div class="readonly-field"><span class="material-symbols-rounded">groups</span>{{ $jadwal->kelas->nama_kelas ?? '-' }}</div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Jam Pelajaran</label>
                        <div class="readonly-field"><span class="material-symbols-rounded">schedule</span>Jam Ke-{{ $jadwal->jamMulai->jam_ke ?? '-' }} - {{ $jadwal->jamSelesai->jam_ke ?? '-' }}</div>
                    </div>
                </div>

                <div style="margin-top:16px;">
                    <div class="field-group">
                        <label class="field-label">Mata Pelajaran</label>
                        <div class="readonly-field"><span class="material-symbols-rounded">menu_book</span>{{ $jadwal->mapel->nama_mapel ?? '-' }}</div>
                    </div>
                </div>
            </section>

            {{-- KEHADIRAN GURU --}}
            <section class="form-card">
                <div class="teacher-status">
                    <div class="teacher-status-info">
                        <div class="section-icon"><span class="material-symbols-rounded">person_check</span></div>
                        <div>
                            <h3 class="section-title">Kehadiran Guru</h3>
                            <p class="section-subtitle">Status kehadiran Anda pada jam pelajaran ini</p>
                        </div>
                    </div>

                    <div class="status-buttons">
                        <button
                            type="button"
                            class="status-btn active"
                            data-teacher-status="Hadir"
                            onclick="setTeacherStatus(this, 'Hadir')"
                        >
                            Hadir
                        </button>

                        <button
                            type="button"
                            class="status-btn"
                            data-teacher-status="Izin"
                            onclick="setTeacherStatus(this, 'Izin')"
                        >
                            Izin
                        </button>

                        <button
                            type="button"
                            class="status-btn"
                            data-teacher-status="Sakit"
                            onclick="setTeacherStatus(this, 'Sakit')"
                        >
                            Sakit
                        </button>
                    </div>
                </div>
                <input type="hidden" name="status_guru" id="statusGuru" value="{{ old('status_guru', 'Hadir') }}">
            </section>

            {{-- MATERI --}}
            <section class="form-card">
                <div class="section-header">
                    <div class="section-icon"><span class="material-symbols-rounded">menu_book</span></div>
                    <div>
                        <h3 class="section-title">Materi Pembelajaran</h3>
                        <p class="section-subtitle">Tuliskan materi yang disampaikan</p>
                    </div>
                </div>
                <textarea name="materi" class="field-textarea" placeholder="Contoh: Pengenalan HTML dan struktur dasar halaman web" required>{{ old('materi') }}</textarea>
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
                <textarea name="keterangan" class="field-textarea" placeholder="Contoh: Kelas kondusif, ulangan harian, dan lain-lain." required>{{ old('keterangan') }}</textarea>
            </section>

            {{-- KEHADIRAN SISWA --}}
            <section class="form-card">
                {{-- DESKTOP --}}
                <div class="student-desktop-header">
                    <div class="student-title-wrap">
                        <div class="section-icon"><span class="material-symbols-rounded">groups</span></div>
                        <div>
                            <h3 class="section-title">Kehadiran Siswa</h3>
                            <p class="section-subtitle">
                                {{ $jadwal->kelas->nama_kelas ?? '-' }}
                                ·
                                <span>{{ $siswa->count() }}</span> siswa
                                @if($activeDispenSiswa->isNotEmpty())
                                    · <span class="text-emerald-600">{{ $activeDispenSiswa->count() }} dispen aktif</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="student-search">
                        <span class="material-symbols-rounded">search</span>
                        <input type="text" id="studentSearchDesktop" class="field-input" placeholder="Cari siswa yang tidak masuk..." autocomplete="off" oninput="searchStudents(this.value)">
                    </div>
                </div>

                {{-- MOBILE --}}
                <div class="student-mobile-header">
                    <div class="student-mobile-title">
                        <div>
                            <h2>Kehadiran Siswa - {{ $jadwal->kelas->nama_kelas ?? '-' }}</h2>
                            <p>Materi: {{ $jadwal->mapel->nama_mapel ?? '-' }}</p>
                        </div>
                        <span class="student-total-badge">{{ $siswa->count() }} Siswa</span>
                    </div>
                </div>

                {{-- SUMMARY --}}
                <div class="summary-grid">
                    <div class="summary-card summary-hadir">
                        <div class="summary-top"><span class="summary-label">Hadir</span><span class="material-symbols-rounded summary-icon">check_circle</span></div>
                        <p id="hadirCount" class="summary-number">0</p>
                    </div>
                    <div class="summary-card summary-sakit">
                        <div class="summary-top"><span class="summary-label">Sakit</span><span class="material-symbols-rounded summary-icon">medical_services</span></div>
                        <p id="sakitCount" class="summary-number">0</p>
                    </div>
                    <div class="summary-card summary-izin">
                        <div class="summary-top"><span class="summary-label">Izin</span><span class="material-symbols-rounded summary-icon">info</span></div>
                        <p id="izinCount" class="summary-number">0</p>
                    </div>
                    <div class="summary-card summary-alpha">
                        <div class="summary-top"><span class="summary-label">Alpa</span><span class="material-symbols-rounded summary-icon">cancel</span></div>
                        <p id="alpaCount" class="summary-number">0</p>
                    </div>
                </div>

                {{-- MOBILE SEARCH --}}
                <div class="student-mobile-search">
                    <span class="material-symbols-rounded">search</span>
                    <input type="text" id="studentSearchMobile" class="field-input" placeholder="Cari siswa yang tidak masuk..." autocomplete="off" oninput="searchStudents(this.value)">
                </div>

                {{-- DISPEN NOTICE --}}
                @if(isset($siswaDispen) && $siswaDispen->count())
                    <div class="dispen-notice">
                        <span class="material-symbols-rounded">event_available</span>
                        <div>
                            <strong>Siswa Dispensasi</strong>
                            <span>{{ $siswaDispen->pluck('nama_siswa')->join(', ') }}</span>
                        </div>
                    </div>
                @endif

                {{-- STUDENT TABLE --}}
                <div class="student-table-wrap">
                    <div class="student-table-scroll">
                        <table class="student-table">
                            <thead>
                                <tr>
                                    <th class="student-number">No</th>
                                    <th class="student-nis">NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswa as $item)
                                    @php
                                        $isDispenAktif = $activeDispenSiswa->contains($item->id_siswa);
                                    @endphp
                                    <tr
                                        class="student-row"
                                        data-name="{{ strtolower($item->nama_siswa) }}"
                                        data-nis="{{ strtolower($item->nis ?? '') }}"
                                    >
                                        <td class="student-number">
                                            {{ sprintf('%02d', $loop->iteration) }}
                                        </td>

                                        <td class="student-nis">
                                            {{ $item->nis }}
                                        </td>

                                        <td
                                            class="student-name"
                                            data-initial="{{ strtoupper(substr($item->nama_siswa, 0, 1)) }}{{ strtoupper(substr(explode(' ', trim($item->nama_siswa))[1] ?? '', 0, 1)) }}"
                                            data-gender="{{ $item->jenis_kelamin ?? $item->jk ?? '' }}"
                                        >
                                            <div class="student-name-text">{{ $item->nama_siswa }}</div>
                                        </td>

                                        <td class="attendance-cell">
                                            @if($isDispenAktif)
                                                <div class="attendance-locked">Dispen · Terkonfirmasi</div>
                                                <input type="hidden" name="absensi[{{ $item->id_siswa }}]"
                                                    value="Dispen"
                                                    class="attendance-value"
                                                >
                                            @else
                                                <div class="attendance-options">
                                                    <button type="button" class="attendance-btn active" data-status="Hadir" onclick="setStudentStatus(this)">
                                                        Hadir
                                                    </button>
                                                    <button type="button" class="attendance-btn" data-status="Sakit" onclick="setStudentStatus(this)">
                                                        Sakit
                                                    </button>
                                                    <button type="button" class="attendance-btn" data-status="Izin" onclick="setStudentStatus(this)">
                                                        Izin
                                                    </button>
                                                    <button type="button" class="attendance-btn" data-status="Alpha" onclick="setStudentStatus(this)">
                                                        Alpa
                                                    </button>
                                                    <input type="hidden" name="absensi[{{ $item->id_siswa }}]"
                                                        value="Hadir"
                                                        class="attendance-value"
                                                    >
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-students">
                                                Belum ada siswa di kelas ini.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- SEARCH EMPTY --}}
                <div id="studentSearchEmpty" class="student-search-empty">
                    <span class="material-symbols-rounded">person_search</span>
                    <strong>Siswa tidak ditemukan</strong>
                    <span>Coba cari dengan nama atau NIS siswa.</span>
                </div>
            </section>

            {{-- TUGAS & CATATAN --}}
            <section class="form-card" id="taskNotesSection" style="display:none;">
                <div class="section-header">
                    <div class="section-icon"><span class="material-symbols-rounded">assignment</span></div>
                    <div>
                        <h3 class="section-title">Tugas & Catatan</h3>
                        <p class="section-subtitle">Tambahkan tugas atau catatan jika Anda berhalangan hadir</p>
                    </div>
                </div>

                <div class="task-grid">
                    <div class="field-group">
                        <label class="field-label">Ada Tugas?</label>
                        <select name="ada_tugas" id="adaTugas" class="field-select">
                            <option value="Tidak" {{ old('ada_tugas', 'Tidak') === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                            <option value="Ya" {{ old('ada_tugas') === 'Ya' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Deskripsi Tugas</label>
                        <textarea name="deskripsi_tugas" class="field-textarea" placeholder="Tuliskan deskripsi tugas jika ada...">{{ old('deskripsi_tugas') }}</textarea>
                    </div>
                </div>

                <div style="margin-top:16px;">
                    <div class="field-group">
                        <label class="field-label">Catatan Umum</label>
                        <textarea name="catatan_umum" class="field-textarea" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan_umum') }}</textarea>
                    </div>
                </div>

            </section>

            {{-- ACTION --}}
            <div class="action-buttons">
                <a href="{{ route('guru.jurnal.create') }}" class="btn btn-secondary">
                    <span class="material-symbols-rounded">arrow_back</span> Batal
                </a>
                <button type="submit" class="btn btn-primary" id="reviewButton">
                    <span class="material-symbols-rounded">visibility</span> Review Jurnal
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
                        <div class="review-info-value" id="reviewJam">Jam Ke-{{ $jadwal->jamMulai->jam_ke ?? '-' }} - {{ $jadwal->jamSelesai->jam_ke ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="review-section">
                <h3 class="review-section-title">Kehadiran Guru</h3>
                <div class="review-text-box" id="reviewStatusGuru">Hadir</div>
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
     TEACHER STATUS
  ========================================================= */
  function setTeacherStatus(button, status) {
    const statusInput = document.getElementById('statusGuru');
    if (!statusInput) return;

    document.querySelectorAll('.status-btn').forEach(item => item.classList.remove('active'));
    button.classList.add('active');
    statusInput.value = status;
    updateTaskNotesVisibility(status);
  }

  function updateTaskNotesVisibility(status) {
    const section = document.getElementById('taskNotesSection');
    if (!section) return;

    section.style.display = (status === 'Izin' || status === 'Sakit') ? '' : 'none';
  }

  /* =========================================================
     STUDENT ATTENDANCE
  ========================================================= */
  function setStudentStatus(button) {
    const row = button.closest('.student-row');
    if (!row) return;

    row.querySelectorAll('.attendance-btn').forEach(item => item.classList.remove('active'));
    button.classList.add('active');

    const status = button.dataset.status;
    const hiddenInput = row.querySelector('.attendance-value');
    if (hiddenInput) {
      hiddenInput.value = status;
    }

    row.classList.add('visible-row');
    updateMobileStatusBadge(row, status);
    updateSummary();

    const searchDesktop = document.getElementById('studentSearchDesktop');
    const searchMobile = document.getElementById('studentSearchMobile');
    const currentSearch = searchDesktop?.value || searchMobile?.value || '';
    searchStudents(currentSearch);
  }

  function updateMobileStatusBadge(row, status) {
    const badge = row.querySelector('.mobile-status-badge');
    if (!badge) return;

    if (status === 'Alpha') {
      badge.textContent = 'Alpa';
    } else if (status === 'Dispen') {
      badge.textContent = 'Dispensasi';
    } else {
      badge.textContent = status;
    }
  }

  /* =========================================================
     SUMMARY
  ========================================================= */
  function updateSummary() {
    const counts = { Hadir: 0, Sakit: 0, Izin: 0, Alpha: 0, Dispen: 0 };

    document.querySelectorAll('.student-row').forEach(row => {
      const input = row.querySelector('.attendance-value');
      if (!input) return;

      const status = input.value;
      if (Object.prototype.hasOwnProperty.call(counts, status)) {
        counts[status]++;
      }

      // Secara default hanya siswa yang tidak hadir dan dispen yang ditampilkan
      if (status === 'Hadir') {
        row.classList.remove('visible-row');
      } else {
        row.classList.add('visible-row');
      }
    });

    const hadir = document.getElementById('hadirCount');
    const sakit = document.getElementById('sakitCount');
    const izin = document.getElementById('izinCount');
    const alpha = document.getElementById('alpaCount');

    if (hadir) hadir.textContent = counts.Hadir;
    if (sakit) sakit.textContent = counts.Sakit;
    if (izin) izin.textContent = counts.Izin;
    if (alpha) alpha.textContent = counts.Alpha;
  }

  /* =========================================================
     SEARCH STUDENTS
  ========================================================= */
  function matchesSearch(row, keyword) {
    const name = row.dataset.name || '';
    const nis = row.dataset.nis || '';
    return name.includes(keyword) || nis.includes(keyword);
  }

  function searchStudents(value) {
    const keyword = (value || '').toLowerCase().trim();
    const desktopInput = document.getElementById('studentSearchDesktop');
    const mobileInput = document.getElementById('studentSearchMobile');
    const emptyState = document.getElementById('studentSearchEmpty');

    if (desktopInput && desktopInput.value !== value) desktopInput.value = value;
    if (mobileInput && mobileInput.value !== value) mobileInput.value = value;

    let visibleCount = 0;

    document.querySelectorAll('.student-row').forEach(row => {
      const input = row.querySelector('.attendance-value');
      if (!input) return;

      const status = input.value;

      // Saat tidak ada pencarian, hanya tampilkan siswa tidak hadir/dispen
      if (keyword === '') {
        if (status === 'Hadir') {
          row.classList.remove('visible-row');
        } else {
          row.classList.add('visible-row');
          visibleCount++;
        }
        return;
      }

      // Saat melakukan pencarian, tampilkan siswa yang cocok walaupun hadir
      if (matchesSearch(row, keyword)) {
        row.classList.add('visible-row');
        visibleCount++;
      } else {
        row.classList.remove('visible-row');
      }
    });

    if (emptyState) {
      emptyState.classList.toggle('show', keyword !== '' && visibleCount === 0);
    }
  }

  function clearSearch() {
    searchStudents('');
  }

  /* =========================================================
     HTML ESCAPE
  ========================================================= */
  function escapeHtml(value) {
    if (value === null || value === undefined) return '';
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  /* =========================================================
     REVIEW MODAL
  ========================================================= */
  function getTeacherStatusText() {
    const input = document.getElementById('statusGuru');
    return input ? input.value || 'Hadir' : 'Hadir';
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

    document.querySelectorAll('.student-row').forEach(row => {
      const input = row.querySelector('.attendance-value');
      if (!input) return;

      const status = input.value;
      if (status === 'Hadir') return;

      const nameElement = row.querySelector('.student-name-text');
      const nisElement = row.querySelector('.student-nis');
      const actualName = nameElement ? nameElement.textContent.trim() : row.dataset.name || '-';
      const actualNis = nisElement ? nisElement.textContent.trim() : row.dataset.nis || '-';
      const statusClass = getStatusClass(status);
      const displayStatus = status === 'Alpha' ? 'Alpa' : status === 'Dispen' ? 'Dispensasi' : status;

      const item = document.createElement('div');
      item.className = 'review-student-item';
      item.innerHTML = `
        <div class="review-student-info">
          <div class="review-student-name">${escapeHtml(actualName)}</div>
          <div class="review-student-nis">NIS: ${escapeHtml(actualNis)}</div>
        </div>
        <span class="review-status ${statusClass}">${escapeHtml(displayStatus)}</span>
      `;
      container.appendChild(item);
      count++;
    });

    if (count === 0) {
      container.innerHTML = `<div class="review-empty-attendance">Semua siswa hadir.</div>`;
    }
  }

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
    const reviewStatusGuru = document.getElementById('reviewStatusGuru');

    if (reviewTanggal) reviewTanggal.textContent = tanggal || '-';
    if (reviewMateri) reviewMateri.textContent = materi || '-';
    if (reviewKeterangan) reviewKeterangan.textContent = keterangan || '-';
    if (reviewStatusGuru) reviewStatusGuru.textContent = getTeacherStatusText();

    const hadirCount = document.getElementById('hadirCount');
    const sakitCount = document.getElementById('sakitCount');
    const izinCount = document.getElementById('izinCount');
    const alphaCount = document.getElementById('alpaCount');

    document.getElementById('reviewHadir').textContent = hadirCount?.textContent || '0';
    document.getElementById('reviewSakit').textContent = sakitCount?.textContent || '0';
    document.getElementById('reviewIzin').textContent = izinCount?.textContent || '0';
    document.getElementById('reviewAlpha').textContent = alphaCount?.textContent || '0';

    buildReviewStudentList();

    const teacherStatus = getTeacherStatusText();
    const taskSection = document.getElementById('reviewTaskSection');
    const taskSelect = document.getElementById('adaTugas');
    const taskTextarea = document.querySelector('textarea[name="deskripsi_tugas"]');
    const taskReview = document.getElementById('reviewTugas');

    const catatanSection = document.getElementById('reviewCatatanSection');
    const catatanReview = document.getElementById('reviewCatatan');
    const catatanInput = document.querySelector('textarea[name="catatan_umum"]');

    if (teacherStatus === 'Izin' || teacherStatus === 'Sakit') {
      if (taskSection) taskSection.style.display = '';
      const adaTugas = taskSelect ? taskSelect.value : 'Tidak';
      const deskripsi = taskTextarea ? taskTextarea.value.trim() : '';
      if (taskReview) {
        taskReview.textContent = adaTugas === 'Ya' ? (deskripsi || 'Tugas belum diisi.') : 'Tidak ada tugas.';
      }
    } else {
      if (taskSection) taskSection.style.display = 'none';
    }

    const catatan = catatanInput ? catatanInput.value.trim() : '';
    if (catatan) {
      if (catatanSection) catatanSection.style.display = '';
      if (catatanReview) catatanReview.textContent = catatan;
    } else {
      if (catatanSection) catatanSection.style.display = 'none';
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

  /* =========================================================
     QR SCANNER & DOM INITIALIZATION
  ========================================================= */
  document.addEventListener('DOMContentLoaded', function () {
    const openScannerButton = document.getElementById('openQrScanner');
    const closeScannerButton = document.getElementById('closeQrScanner');
    const scannerElement = document.getElementById('qrScanner');
    const scanMessage = document.getElementById('qrScanMessage');
    const statusElement = document.getElementById('qrVerificationStatus');

    let qrScanner = null;
    let qrVerificationInProgress = false;

    async function stopQrScanner() {
      if (qrScanner) {
        const scanner = qrScanner;
        qrScanner = null;
        try { await scanner.stop(); } catch (error) {}
        try { await scanner.clear(); } catch (error) {}
      }
      if (scannerElement) scannerElement.classList.remove('open');
      if (closeScannerButton) closeScannerButton.style.display = 'none';
    }

    async function verifyQr(decodedText) {
      if (qrVerificationInProgress) return;
      qrVerificationInProgress = true;

      if (openScannerButton) openScannerButton.disabled = true;
      if (scanMessage) scanMessage.textContent = 'Memverifikasi QR kelas...';

      try {
        const response = await fetch("{{ route('guru.jurnal.verify-qr', $jadwal) }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
          },
          body: JSON.stringify({ qr_token: decodedText.trim() }),
        });

        const result = await response.json();
        if (!response.ok) {
          throw new Error(result.message || 'QR bukan milik kelas ini.');
        }

        await stopQrScanner();

        if (statusElement) {
          statusElement.classList.remove('pending');
          statusElement.classList.add('success');
          statusElement.innerHTML = `<span>✓</span> <span>Sudah scan - Hadir</span>`;
        }

        if (openScannerButton) {
          openScannerButton.disabled = true;
          openScannerButton.textContent = 'Sudah Scan';
        }

        if (scanMessage) {
          scanMessage.textContent = `${result.message || 'Scan QR berhasil.'}` + (result.kelas ? ` (${result.kelas})` : '');
        }

        qrVerificationInProgress = false;
      } catch (error) {
        const detail = error && error.message ? error.message : 'QR tidak cocok dengan kelas ini atau scanner gagal membaca data.';
        if (scanMessage) {
          scanMessage.textContent = `QR terbaca, tetapi belum berhasil diverifikasi: ${detail}`;
        }
        qrVerificationInProgress = false;
        if (openScannerButton) openScannerButton.disabled = false;
        if (!qrScanner) startQrScanner();
      }
    }

    function startQrScanner() {
      if (typeof Html5Qrcode === 'undefined') {
        if (scanMessage) {
          scanMessage.textContent = 'Scanner QR belum siap. Refresh halaman dan pastikan koneksi internet tersedia.';
        }
        return;
      }

      if (qrScanner || !scannerElement) return;

      scannerElement.classList.add('open');
      if (closeScannerButton) closeScannerButton.style.display = 'inline-block';
      scannerElement.innerHTML = `<div class="qr-scanner-status">Menyiapkan kamera...</div>`;

      if (scanMessage) {
        scanMessage.textContent = 'Izinkan kamera, lalu arahkan ke QR kelas yang sesuai dengan jadwal Anda.';
      }

      qrScanner = new Html5Qrcode('qrScanner');

      const timeout = new Promise((_, reject) => {
        window.setTimeout(() => reject(new Error('Permintaan kamera terlalu lama. Periksa izin kamera browser.')), 8000);
      });

      Promise.race([
        Html5Qrcode.getCameras().catch(() => []),
        timeout
      ])
      .then(cameras => {
        if (!cameras.length) {
          throw new Error('Tidak ada kamera yang ditemukan pada perangkat ini.');
        }

        const camera = cameras.find(item => /back|rear|environment|belakang/i.test(item.label)) || cameras[0];
        scannerElement.innerHTML = '';

        if (scanMessage) {
          scanMessage.textContent = 'Kamera aktif. Arahkan QR kelas ke kotak pemindai.';
        }

        return qrScanner.start(
          camera.id,
          { fps: 10, qrbox: { width: 220, height: 220 }, aspectRatio: 1 },
          decodedText => verifyQr(decodedText),
          () => {}
        );
      })
      .catch(error => {
        const message = error.name === 'NotAllowedError'
          ? 'Akses kamera ditolak. Klik ikon kamera di address bar, izinkan kamera, lalu refresh halaman.'
          : (error.message || 'Kamera tidak dapat dibuka.');

        if (scanMessage) scanMessage.textContent = message;

        scannerElement.innerHTML = `<div class="qr-scanner-status">${escapeHtml(message)}</div>`;

        if (qrScanner) {
          qrScanner.clear().catch(() => {});
          qrScanner = null;
        }

        if (closeScannerButton) closeScannerButton.style.display = 'none';
      });
    }

    if (openScannerButton) openScannerButton.addEventListener('click', startQrScanner);
    if (closeScannerButton) closeScannerButton.addEventListener('click', stopQrScanner);

    // Scanner otomatis dibuka jika QR belum diverifikasi
    @if(!$qrVerified)
      let attempts = 0;
      const autoStart = window.setInterval(() => {
        attempts++;
        if (typeof Html5Qrcode !== 'undefined' || attempts >= 20) {
          window.clearInterval(autoStart);
          startQrScanner();
        }
      }, 250);
    @endif

    // Initial State Setup
    updateSummary();
    searchStudents('');

    const activeDispenBerakhir = @json($activeDispenBerakhir ?? null);
    if (activeDispenBerakhir) {
      const parts = activeDispenBerakhir.split(':').map(Number);
      const hour = parts[0] || 0;
      const minute = parts[1] || 0;
      const second = parts[2] || 0;

      const waktuSelesai = new Date();
      waktuSelesai.setHours(hour, minute, second, 0);

      const delay = waktuSelesai.getTime() - Date.now();
      if (delay > 0) {
        // Reload halaman saat masa dispensasi berakhir
        window.setTimeout(() => window.location.reload(), delay + 1000);
      }
    }

    // Intercept Form Submit -> Open Review Modal
    const form = document.getElementById('journalForm');
    if (form) {
      form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }
        openReview();
      });
    }

    // Set Initial Teacher Status UI
    const currentTeacherStatus = document.getElementById('statusGuru')?.value || 'Hadir';
    document.querySelectorAll('.status-btn').forEach(button => {
      button.classList.toggle('active', button.dataset.teacherStatus === currentTeacherStatus);
    });
    updateTaskNotesVisibility(currentTeacherStatus);

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