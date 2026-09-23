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

    .journal-form {
        --primary: #2D336B;
        --secondary: #7886C7;
        --tertiary: #A9B5DF;
        --soft-blue: #DCE4FF;
        --surface: #FBFBFB;
        --border: #E5E7EB;
        --text: #1E293B;
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

    .journal-page {
        width: 100%;
    }

    .journal-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
        padding: 30px 32px;
        border-radius: 18px;
        background: linear-gradient(135deg, #2D336B 0%, #7886C7 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(45, 51, 107, .10);
    }

    .journal-hero::before,
    .journal-hero::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
        pointer-events: none;
    }

    .journal-hero::before {
        width: 220px;
        height: 220px;
        right: -70px;
        top: -100px;
    }

    .journal-hero::after {
        width: 130px;
        height: 130px;
        right: 110px;
        bottom: -90px;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 720px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        padding: 7px 12px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 999px;
        background: rgba(255,255,255,.10);
        font-size: 11px;
        font-weight: 700;
    }

    .hero-badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: white;
    }

    .hero-title {
        margin: 0;
        font-size: 28px;
        line-height: 1.3;
        font-weight: 800;
        letter-spacing: -.6px;
    }

    .hero-description {
        margin: 8px 0 0;
        color: rgba(255,255,255,.76);
        font-size: 13px;
        line-height: 1.7;
    }

    .form-card {
        margin-bottom: 20px;
        padding: 24px;
        background: white;
        border: 1px solid #EDF0F7;
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(45, 51, 107, .05);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

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

    .section-icon .material-symbols-rounded {
        font-size: 21px;
    }

    .section-title {
        margin: 0;
        color: var(--text);
        font-size: 15px;
        font-weight: 800;
    }

    .section-subtitle {
        margin: 3px 0 0;
        color: #94A3B8;
        font-size: 11px;
        line-height: 1.5;
    }

    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .field-group {
        min-width: 0;
    }

    .field-label {
        display: block;
        margin-bottom: 8px;
        color: #64748B;
        font-size: 11px;
        font-weight: 700;
    }

    .field-input,
    .field-select,
    .field-textarea {
        width: 100%;
        border: 1px solid #DFE3EF;
        border-radius: 10px;
        background: white;
        color: #334155;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .field-input,
    .field-select {
        min-height: 44px;
        padding: 10px 13px;
    }

    .field-textarea {
        min-height: 110px;
        padding: 12px 13px;
        resize: vertical;
        line-height: 1.6;
    }

    .field-input:focus,
    .field-select:focus,
    .field-textarea:focus {
        border-color: var(--secondary);
        box-shadow: 0 0 0 3px rgba(120, 134, 199, .14);
    }

    .readonly-field {
        display: flex;
        align-items: center;
        gap: 9px;
        min-height: 44px;
        padding: 10px 13px;
        border: 1px solid #DFE3EF;
        border-radius: 10px;
        background: #F8FAFC;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
    }

    .readonly-field .material-symbols-rounded {
        font-size: 19px;
        flex-shrink: 0;
        color: var(--secondary);
    }

    .divider {
        height: 1px;
        margin: 24px 0;
        background: #EEF0F5;
    }

    .teacher-status {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .teacher-status-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .status-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .status-btn {
        min-width: 82px;
        min-height: 38px;
        padding: 8px 14px;
        border: 1px solid #DFE3EF;
        border-radius: 9px;
        background: white;
        color: #64748B;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all .18s ease;
    }

    .status-btn:hover {
        border-color: var(--secondary);
        transform: translateY(-1px);
    }

    .status-btn.active {
        border-color: var(--secondary);
        background: var(--secondary);
        color: white;
        box-shadow: 0 4px 10px rgba(120, 134, 199, .22);
    }

    .student-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .student-title-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .student-search {
        position: relative;
        width: 260px;
        flex-shrink: 0;
    }

    .student-search .material-symbols-rounded {
        position: absolute;
        left: 13px;
        top: 50%;
        font-size: 17px;
        color: #94A3B8;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .student-search .field-input {
        padding-left: 40px;
    }

    .student-count {
        margin-top: 4px;
        color: #94A3B8;
        font-size: 11px;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .summary-card {
        padding: 14px 16px;
        border-radius: 12px;
        transition: transform .2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
    }

    .summary-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .summary-label {
        color: #64748B;
        font-size: 11px;
        font-weight: 700;
    }

    .summary-icon {
        font-family: 'Material Symbols Rounded';
        font-size: 18px;
        line-height: 1;
    }

    .summary-number {
        margin: 4px 0 0;
        font-size: 22px;
        font-weight: 800;
        line-height: 1;
    }

    .summary-hadir {
        background: #EEF8F1;
    }

    .summary-hadir .summary-icon,
    .summary-hadir .summary-number {
        color: #16A34A;
    }

    .summary-sakit {
        background: #FFF8E8;
    }

    .summary-sakit .summary-icon,
    .summary-sakit .summary-number {
        color: #F59E0B;
    }

    .summary-izin {
        background: #EEF5FF;
    }

    .summary-izin .summary-icon,
    .summary-izin .summary-number {
        color: #3B82F6;
    }

    .summary-alpha {
        background: #FFF0F0;
    }

    .summary-alpha .summary-icon,
    .summary-alpha .summary-number {
        color: #EF4444;
    }

    .student-table-wrap {
        overflow: hidden;
        border: 1px solid #EEF0F5;
        border-radius: 12px;
    }

    .student-table-scroll {
        max-height: 390px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #CBD2E8 transparent;
    }

    .student-table-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .student-table-scroll::-webkit-scrollbar-thumb {
        background: #CBD2E8;
        border-radius: 10px;
    }

    .student-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 680px;
    }

    .student-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        padding: 12px 14px;
        background: #F8FAFC;
        border-bottom: 1px solid #EEF0F5;
        color: #94A3B8;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .05em;
        text-align: left;
        text-transform: uppercase;
    }

    .student-table tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid #F0F2F6;
        color: #475569;
        font-size: 12px;
        vertical-align: middle;
    }

    .student-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .student-table tbody tr {
        transition: background .2s ease;
    }

    .student-table tbody tr:hover {
        background: #F8F9FD;
    }

    .student-number {
        width: 55px;
        color: #94A3B8 !important;
        font-weight: 700;
    }

    .student-nis {
        width: 120px;
        color: #64748B !important;
        font-weight: 600;
    }

    .student-name {
        color: #334155 !important;
        font-weight: 700;
    }

    .attendance-options {
        display: flex;
        gap: 7px;
        flex-wrap: wrap;
    }

    .attendance-btn {
        min-width: 65px;
        min-height: 34px;
        padding: 7px 10px;
        border: 1px solid #DFE3EF;
        border-radius: 8px;
        background: white;
        color: #64748B;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: all .18s ease;
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

    .student-table .attendance-btn[data-status="Sakit"].active {
        border-color: #8A5700;
        background: #8A5700;
        color: white;
        box-shadow: 0 3px 8px rgba(138, 87, 0, .18);
    }

    .student-table .attendance-btn[data-status="Izin"].active {
        border-color: #17649A;
        background: #17649A;
        color: white;
        box-shadow: 0 3px 8px rgba(23, 100, 154, .18);
    }

    .student-table .attendance-btn[data-status="Alpha"].active {
        border-color: #B42323;
        background: #B42323;
        color: white;
        box-shadow: 0 3px 8px rgba(180, 35, 35, .18);
    }

    .empty-students {
        padding: 40px 20px;
        color: #94A3B8;
        font-size: 12px;
        text-align: center;
    }

    .task-grid {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 16px;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding-bottom: 12px;
    }

    .btn {
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .btn .material-symbols-rounded {
        font-size: 17px;
    }

    .btn-secondary {
        border: 1px solid #DFE3EF;
        background: white;
        color: #475569;
    }

    .btn-secondary:hover {
        background: #F7F8FC;
        transform: translateY(-1px);
    }

    .btn-primary {
        border: 1px solid var(--primary);
        background: var(--primary);
        color: white;
        box-shadow: 0 5px 14px rgba(45, 51, 107, .16);
    }

    .btn-primary:hover {
        background: #232857;
        transform: translateY(-1px);
        box-shadow: 0 7px 18px rgba(45, 51, 107, .20);
    }

    .hidden-row {
        display: none !important;
    }

    .form-error {
        margin-bottom: 20px;
        padding: 14px 16px;
        border: 1px solid #FECACA;
        border-radius: 12px;
        background: #FEF2F2;
        color: #B91C1C;
        font-size: 12px;
    }

    .form-error ul {
        margin: 7px 0 0;
        padding-left: 18px;
    }

    @media (max-width: 1100px) {

        .schedule-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .task-grid {
            grid-template-columns: 1fr;
        }

    }

    @media (max-width: 767px) {

        .journal-hero {
            padding: 24px 20px;
            border-radius: 14px;
        }

        .hero-title {
            font-size: 22px;
        }

        .hero-description {
            font-size: 12px;
        }

        .form-card {
            padding: 18px;
            border-radius: 14px;
        }

        .schedule-grid {
            grid-template-columns: 1fr;
        }

        .teacher-status {
            align-items: flex-start;
            flex-direction: column;
        }

        .student-header {
            flex-direction: column;
            gap: 14px;
        }

        .student-search {
            width: 100%;
        }

        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .student-table-wrap {
            overflow-x: auto;
        }

        .action-buttons {
            flex-direction: column-reverse;
        }

        .action-buttons .btn {
            width: 100%;
        }

    }

    @media (max-width: 420px) {

        .summary-grid {
            gap: 8px;
        }

        .summary-card {
            padding: 12px;
        }

        .status-buttons {
            width: 100%;
        }

        .status-btn {
            flex: 1;
        }

    }

    /* =========================================
       MOBILE ATTENDANCE - MATCH REFERENCE
       ========================================= */

    .student-mobile-header,
    .student-mobile-search {
        display: none;
    }

    .mobile-status-badge {
        display: none;
    }

    @media (max-width: 767px) {

        .journal-form {
            width: 100%;
            margin: 0;
            background: #F5F7FC;
        }

        .journal-page {
            width: 100%;
        }

        .form-card:has(.student-table-wrap) {
            display: block;
            width: 100%;
            margin: 0;
            padding: 15px 12px 86px;
            border: 0;
            border-radius: 0;
            background: #F5F7FC;
            box-shadow: none;
        }

        .student-desktop-header {
            display: none;
        }

        .student-mobile-header {
            display: block;
            margin-bottom: 14px;
        }

        .student-mobile-title {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .student-mobile-title h2 {
            margin: 0;
            color: #20275F;
            font-size: 20px;
            line-height: 1.25;
            font-weight: 800;
            letter-spacing: -.3px;
        }

        .student-mobile-title p {
            margin: 5px 0 0;
            color: #596070;
            font-size: 12px;
            line-height: 1.4;
        }

        .student-total-badge {
            flex-shrink: 0;
            margin-top: 3px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #30366F;
            color: white;
            font-size: 9px;
            font-weight: 800;
            white-space: nowrap;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }

        .summary-card {
            padding: 8px 4px;
            border-radius: 8px;
            text-align: center;
            box-shadow: none;
        }

        .summary-top {
            display: block;
        }

        .summary-label {
            display: block;
            font-size: 9px;
            font-weight: 700;
            line-height: 1.2;
        }

        .summary-icon {
            display: none;
        }

        .summary-number {
            margin: 3px 0 0;
            font-size: 19px;
            line-height: 1;
        }

        .student-mobile-search {
            position: relative;
            display: block;
            margin-bottom: 12px;
        }

        .student-mobile-search .material-symbols-rounded {
            position: absolute;
            z-index: 1;
            left: 12px;
            top: 50%;
            font-size: 16px;
            color: #8A8F9E;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .student-mobile-search .field-input {
            min-height: 41px;
            padding: 9px 12px 9px 37px;
            border: 1px solid #C8CBD6;
            border-radius: 9px;
            background: white;
            font-size: 12px;
        }

        .student-table-wrap {
            overflow: visible;
            border: 0;
            border-radius: 0;
        }

        .student-table-scroll {
            max-height: none;
            overflow: visible;
        }

        .student-table {
            width: 100%;
            min-width: 0;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .student-table thead {
            display: none;
        }

        .student-table tbody,
        .student-table tr,
        .student-table td {
            display: block;
            width: 100%;
        }

        .student-table tbody tr.student-row {
            position: relative;
            margin: 0;
            padding: 11px 11px 12px;
            border: 1px solid #D8DBE3;
            border-radius: 13px;
            background: white;
            box-sizing: border-box;
            box-shadow: 0 1px 2px rgba(30, 41, 59, .03);
        }

        .student-table tbody tr.student-row:hover {
            background: white;
        }

        .student-table tbody tr.student-row td {
            padding: 0;
            border: 0;
        }

        .student-number {
            display: none !important;
        }

        .student-table .student-name {
            position: relative;
            width: calc(100% - 105px);
            min-height: 39px;
            margin-left: 47px;
            padding: 2px 0 0 !important;
            color: #172033 !important;
            font-size: 12px;
            font-weight: 800;
            line-height: 1.35;
        }

        .student-table .student-name::before {
            content: attr(data-initial);
            position: absolute;
            left: -47px;
            top: -1px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #30366F;
            color: white;
            font-size: 11px;
            font-weight: 700;
        }

        .student-table .student-nis {
            position: absolute;
            left: 58px;
            top: 34px;
            width: auto;
            color: #596070 !important;
            font-size: 10px;
            font-weight: 400;
        }

        .student-table .student-name::after {
            content: "· " attr(data-gender);
            position: absolute;
            left: 90px;
            top: 31px;
            color: #30366F;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .student-table .attendance-cell {
            position: static;
        }

        .mobile-status-badge {
            position: absolute;
            top: 13px;
            right: 12px;
            display: block;
            padding: 4px 9px;
            border-radius: 999px;
            background: #E6F4EB;
            color: #17633F;
            font-size: 9px;
            font-weight: 800;
            line-height: 1.2;
        }

        .student-table .attendance-options {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
            width: 100%;
            margin-top: 9px;
            padding: 4px;
            border-radius: 8px;
            background: #EDF2FF;
            box-sizing: border-box;
        }

        .student-table .attendance-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 0;
            min-height: 29px;
            padding: 5px 2px;
            border: 0;
            border-radius: 6px;
            background: white;
            color: #667085;
            font-size: 10px;
            font-weight: 500;
            box-shadow: none;
            transform: none;
        }

        .student-table .attendance-btn:hover {
            border: 0;
            transform: none;
        }

        .student-table .attendance-btn[data-status="Hadir"].active {
            border: 0;
            background: #245C49;
            color: white;
            box-shadow: none;
        }

        .student-table .attendance-btn[data-status="Sakit"].active {
            border: 0;
            background: #8A5700;
            color: white;
            box-shadow: none;
        }

        .student-table .attendance-btn[data-status="Izin"].active {
            border: 0;
            background: #17649A;
            color: white;
            box-shadow: none;
        }

        .student-table .attendance-btn[data-status="Alpha"].active {
            border: 0;
            background: #B42323;
            color: white;
            box-shadow: none;
        }

        /* Dispen bukan pilihan Guru */

        .student-table .attendance-btn[data-status="Dispen"] {
            display: none;
        }

        .student-table .attendance-value {
            display: none;
        }

        .student-table tr:has(.attendance-btn[data-status="Sakit"].active) {
            border-color: #F1C47B;
            background: #FFFCF3;
        }

        .student-table tr:has(.attendance-btn[data-status="Izin"].active) {
            border-color: #A8D5F0;
            background: #F6FBFF;
        }

        .student-table tr:has(.attendance-btn[data-status="Alpha"].active) {
            border-color: #F1AAAA;
            background: #FFF8F8;
        }

        .student-table tr:has(.attendance-btn[data-status="Sakit"].active) .mobile-status-badge {
            background: #FFF0BE;
            color: #8A5700;
        }

        .student-table tr:has(.attendance-btn[data-status="Izin"].active) .mobile-status-badge {
            background: #DDF0FF;
            color: #17649A;
        }

        .student-table tr:has(.attendance-btn[data-status="Alpha"].active) .mobile-status-badge {
            background: #FFE0E0;
            color: #B42323;
        }

        .action-buttons {
            position: fixed;
            z-index: 100;
            left: 0;
            right: 0;
            bottom: 0;
            display: grid;
            grid-template-columns: 66px 1fr;
            gap: 10px;
            margin: 0;
            padding: 12px;
            background: white;
            border-top: 1px solid #E4E6EC;
            box-shadow: 0 -5px 18px rgba(45, 51, 107, .08);
        }

        .action-buttons .btn {
            width: 100%;
            min-height: 48px;
            padding: 8px;
            border-radius: 9px;
        }

        .action-buttons .btn .material-symbols-rounded {
            display: none;
        }

        .action-buttons .btn-secondary {
            border: 0;
            background: #E5EDFF;
            color: #30366F;
        }

        .action-buttons .btn-primary {
            border-color: #30366F;
            background: #30366F;
            color: white;
        }

        .action-buttons .btn-primary {
            font-size: 0;
        }

        .action-buttons .btn-primary::after {
            content: "Simpan Kehadiran ({{ $siswa->count() }} Siswa)";
            font-size: 12px;
        }

        .action-buttons .btn-secondary {
            font-size: 12px;
        }

    }

    @media (max-width: 420px) {

        .form-card:has(.student-table-wrap) {
            padding-left: 12px;
            padding-right: 12px;
        }

        .student-mobile-title h2 {
            font-size: 20px;
        }

        .student-total-badge {
            font-size: 8px;
            padding: 6px 8px;
        }

        .summary-grid {
            gap: 8px;
        }

        .summary-card {
            padding: 8px 3px;
        }

        .summary-number {
            font-size: 18px;
        }

    }

</style>

@endsection

@section('content')

<div class="journal-form">

    <div class="journal-page">

        {{-- HERO --}}

        <section class="journal-hero">

            <div class="hero-content">

                <div class="hero-badge">

                    <span class="hero-badge-dot"></span>

                    <span>Jurnal Pembelajaran</span>

                </div>

                <h1 class="hero-title">
                    Isi Jurnal Mengajar
                </h1>

                <p class="hero-description">
                    Lengkapi data pembelajaran, materi, dan kehadiran siswa
                    untuk mencatat kegiatan belajar mengajar hari ini.
                </p>

            </div>

        </section>

        {{-- VALIDATION ERROR --}}

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

            <section class="form-card mobile-hide-section">

                <div class="section-header">

                    <div class="section-icon">

                        <span class="material-symbols-rounded">calendar_month</span>

                    </div>

                    <div>

                        <h3 class="section-title">Jadwal Pembelajaran</h3>

                        <p class="section-subtitle">
                            Informasi kelas dan mata pelajaran
                        </p>

                    </div>

                </div>

                <div class="schedule-grid">

                    <div class="field-group">

                        <label class="field-label">Tahun Ajaran</label>

                        <div class="readonly-field">

                            <span class="material-symbols-rounded">school</span>

                            {{ $jadwal->tahun_ajaran ?? '-' }}

                        </div>

                    </div>

                    <div class="field-group">

                        <label class="field-label">Tanggal</label>

                        <input
                            type="date"
                            name="tanggal"
                            value="{{ old('tanggal', date('Y-m-d')) }}"
                            class="field-input"
                            required
                        >

                    </div>

                    <div class="field-group">

                        <label class="field-label">Kelas</label>

                        <div class="readonly-field">

                            <span class="material-symbols-rounded">groups</span>

                            {{ $jadwal->kelas->nama_kelas ?? '-' }}

                        </div>

                    </div>

                    <div class="field-group">

                        <label class="field-label">Jam Pelajaran</label>

                        <div class="readonly-field">

                            <span class="material-symbols-rounded">schedule</span>

                            Jam Ke {{ $jadwal->jamMulai->jam_ke ?? '-' }}
                            -
                            {{ $jadwal->jamSelesai->jam_ke ?? '-' }}

                        </div>

                    </div>

                </div>

                <div style="margin-top: 16px;">

                    <div class="field-group">

                        <label class="field-label">Mata Pelajaran</label>

                        <div class="readonly-field">

                            <span class="material-symbols-rounded">menu_book</span>

                            {{ $jadwal->mapel->nama_mapel ?? '-' }}

                        </div>

                    </div>

                </div>

            </section>

            {{-- STATUS GURU --}}

            <section class="form-card mobile-hide-section">

                <div class="teacher-status">

                    <div class="teacher-status-info">

                        <div class="section-icon">

                            <span class="material-symbols-rounded">person_check</span>

                        </div>

                        <div>

                            <h3 class="section-title">Kehadiran Guru</h3>

                            <p class="section-subtitle">
                                Status kehadiran Anda pada jam pelajaran ini
                            </p>

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

                <input
                    type="hidden"
                    name="status_guru"
                    id="statusGuru"
                    value="{{ old('status_guru', 'Hadir') }}"
                >

            </section>

            {{-- MATERI --}}

            <section class="form-card mobile-hide-section">

                <div class="section-header">

                    <div class="section-icon">

                        <span class="material-symbols-rounded">menu_book</span>

                    </div>

                    <div>

                        <h3 class="section-title">Materi Pembelajaran</h3>

                        <p class="section-subtitle">
                            Tuliskan materi yang disampaikan
                        </p>

                    </div>

                </div>

                <textarea
                    name="materi"
                    class="field-textarea"
                    placeholder="Contoh: Pengenalan HTML dan struktur dasar halaman web"
                    required
                >{{ old('materi') }}</textarea>

            </section>

            {{-- KEHADIRAN SISWA --}}

            <section class="form-card">

                <div class="student-desktop-header">

                    <div class="student-title-wrap">

                        <div class="section-icon">

                            <span class="material-symbols-rounded">groups</span>

                        </div>

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

                        <input
                            type="text"
                            id="studentSearchDesktop"
                            class="field-input"
                            placeholder="Cari nama siswa..."
                            oninput="searchStudents(this.value)"
                        >

                    </div>

                </div>

                <div class="student-mobile-header">

                    <div class="student-mobile-title">

                        <div>

                            <h2>
                                Kehadiran Siswa -
                                {{ $jadwal->kelas->nama_kelas ?? '-' }}
                            </h2>

                            <p>
                                Materi:
                                {{ $jadwal->mapel->nama_mapel ?? '-' }}
                            </p>

                        </div>

                        <span class="student-total-badge">
                            Total: {{ $siswa->count() }} Siswa
                        </span>

                    </div>

                </div>

                {{-- SUMMARY --}}

                <div class="summary-grid">

                    <div class="summary-card summary-hadir">

                        <div class="summary-top">

                            <span class="summary-label">Hadir</span>

                            <span class="material-symbols-rounded summary-icon">check_circle</span>

                        </div>

                        <p id="hadirCount" class="summary-number">0</p>

                    </div>

                    <div class="summary-card summary-sakit">

                        <div class="summary-top">

                            <span class="summary-label">Sakit</span>

                            <span class="material-symbols-rounded summary-icon">medical_services</span>

                        </div>

                        <p id="sakitCount" class="summary-number">0</p>

                    </div>

                    <div class="summary-card summary-izin">

                        <div class="summary-top">

                            <span class="summary-label">Izin</span>

                            <span class="material-symbols-rounded summary-icon">info</span>

                        </div>

                        <p id="izinCount" class="summary-number">0</p>

                    </div>

                    <div class="summary-card summary-alpha">

                        <div class="summary-top">

                            <span class="summary-label">Alpha</span>

                            <span class="material-symbols-rounded summary-icon">cancel</span>

                        </div>

                        <p id="alpaCount" class="summary-number">0</p>

                    </div>

                </div>

                <div class="student-mobile-search">

                    <span class="material-symbols-rounded">search</span>

                    <input
                        type="text"
                        id="studentSearchMobile"
                        class="field-input"
                        placeholder="Cari nama siswa"
                        oninput="searchStudents(this.value)"
                    >

                </div>

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
                                    <tr class="student-row" data-name="{{ strtolower($item->nama_siswa) }}"
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
                                            {{ $item->nama_siswa }}
                                        </td>

                                        <td class="attendance-cell">
                                            <span class="mobile-status-badge">{{ $isDispenAktif ? 'Dispen' : 'Hadir' }}</span>
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

            </section>

            {{-- TUGAS & CATATAN --}}

            <section class="form-card mobile-hide-section">

                <div class="section-header">

                    <div class="section-icon">

                        <span class="material-symbols-rounded">assignment</span>

                    </div>

                    <div>

                        <h3 class="section-title">Tugas & Catatan</h3>

                        <p class="section-subtitle">
                            Tambahkan tugas atau catatan untuk jurnal
                        </p>

                    </div>

                </div>

                <div class="task-grid">

                    <div class="field-group">

                        <label class="field-label">Ada Tugas?</label>

                        <select name="ada_tugas" class="field-select" id="adaTugas" required>

                            <option
                                value="Tidak"
                                {{ old('ada_tugas', 'Tidak') === 'Tidak' ? 'selected' : '' }}
                            >
                                Tidak
                            </option>

                            <option
                                value="Ya"
                                {{ old('ada_tugas') === 'Ya' ? 'selected' : '' }}
                            >
                                Ya
                            </option>

                        </select>

                    </div>

                    <div class="field-group">

                        <label class="field-label">Deskripsi Tugas</label>

                        <textarea
                            name="deskripsi_tugas"
                            class="field-textarea"
                            placeholder="Tuliskan deskripsi tugas jika ada..."
                        >{{ old('deskripsi_tugas') }}</textarea>

                    </div>

                </div>

            </section>

            {{-- ACTION --}}

            <div class="action-buttons">

                <a
                    href="{{ route('guru.jurnal.create') }}"
                    class="btn btn-secondary"
                >

                    <span class="material-symbols-rounded">arrow_back</span>

                    Batal

                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <span class="material-symbols-rounded">save</span>

                    Simpan Jurnal

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>

    function setTeacherStatus(button, status) {

        document.querySelectorAll('.status-btn').forEach(item => {

            item.classList.remove('active');

        });

        button.classList.add('active');

        document.getElementById('statusGuru').value = status;

    }

    function setStudentStatus(button) {

        const row = button.closest('.student-row');

        if (!row) {
            return;
        }

        row.querySelectorAll('.attendance-btn').forEach(item => {

            item.classList.remove('active');

        });

        button.classList.add('active');

        const hiddenInput = row.querySelector('.attendance-value');

        if (hiddenInput) {
            hiddenInput.value = button.dataset.status;
        }

        const badge = row.querySelector('.mobile-status-badge');

        if (badge) {
            badge.textContent = button.dataset.status;
        }

        updateSummary();

    }

    function updateSummary() {

        const counts = {
            Hadir: 0,
            Sakit: 0,
            Izin: 0,
            Alpha: 0,
            Dispen: 0
        };

        document.querySelectorAll('.student-row').forEach(row => {

            const activeButton = row.querySelector('.attendance-btn.active');

            if (
                activeButton &&
                Object.prototype.hasOwnProperty.call(
                    counts,
                    activeButton.dataset.status
                )
            ) {
                counts[activeButton.dataset.status]++;
            }

        });

        document.getElementById('hadirCount').textContent = counts.Hadir;
        document.getElementById('sakitCount').textContent = counts.Sakit;
        document.getElementById('izinCount').textContent = counts.Izin;
        document.getElementById('alpaCount').textContent = counts.Alpha;

    }

    document.querySelectorAll('.student-row').forEach(row => {

        const activeButton = row.querySelector('.attendance-btn.active');
        const badge = row.querySelector('.mobile-status-badge');

        if (activeButton && badge) {
            badge.textContent = activeButton.dataset.status;
        }

    });

    function searchStudents(value) {

        const keyword = (value || '').toLowerCase().trim();

        const desktopInput = document.getElementById('studentSearchDesktop');
        const mobileInput = document.getElementById('studentSearchMobile');

        if (desktopInput && desktopInput.value !== value) {
            desktopInput.value = value;
        }

        if (mobileInput && mobileInput.value !== value) {
            mobileInput.value = value;
        }

        document.querySelectorAll('.student-row').forEach(row => {

            const name = row.dataset.name || '';

            if (name.includes(keyword)) {

                row.classList.remove('hidden-row');

            } else {

                row.classList.add('hidden-row');

            }

        });

    }

    document.addEventListener('DOMContentLoaded', function () {
        const openScannerButton = document.getElementById('openQrScanner');
        const closeScannerButton = document.getElementById('closeQrScanner');
        const scannerElement = document.getElementById('qrScanner');
        const scanMessage = document.getElementById('qrScanMessage');
        const statusElement = document.getElementById('qrVerificationStatus');
        let qrScanner;
        let qrVerificationInProgress = false;

        async function stopQrScanner() {
            if (qrScanner) {
                const scanner = qrScanner;
                qrScanner = null;
                await scanner.stop().catch(() => {});
                await scanner.clear().catch(() => {});
            }
            scannerElement.classList.remove('open');
            closeScannerButton.style.display = 'none';
        }

        async function verifyQr(decodedText) {
            if (qrVerificationInProgress) {
                return;
            }

            qrVerificationInProgress = true;
            openScannerButton.disabled = true;
            scanMessage.textContent = 'Memverifikasi QR kelas...';
            try {
                const response = await fetch('{{ route('guru.jurnal.verify-qr', $jadwal) }}', {
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

                stopQrScanner();
                statusElement.classList.remove('pending');
                statusElement.classList.add('success');
                statusElement.innerHTML = '<span>✓</span><span>Sudah scan - Hadir</span>';
                openScannerButton.disabled = true;
                openScannerButton.textContent = 'Sudah Scan';
                scanMessage.textContent = `${result.message} (${result.kelas})`;
            } catch (error) {
                const detail = error && error.message ? error.message : 'QR tidak cocok dengan kelas ini atau scanner gagal membaca data.';
                scanMessage.textContent = `QR terbaca, tetapi belum berhasil diverifikasi: ${detail}`;
                qrVerificationInProgress = false;
                openScannerButton.disabled = false;
                if (!qrScanner) {
                    startQrScanner();
                }
            }
        }

        function startQrScanner() {
            if (typeof Html5Qrcode === 'undefined') {
                scanMessage.textContent = 'Scanner QR khusus belum siap. Gunakan pemindai QR di browser ini dan pastikan kamera aktif.';
                return;
            }
            if (qrScanner) {
                return;
            }
            scannerElement.classList.add('open');
            closeScannerButton.style.display = 'inline-block';
            scannerElement.innerHTML = '<div class="qr-scanner-status">Menyiapkan kamera...</div>';
            scanMessage.textContent = 'Izinkan kamera, lalu arahkan ke QR kelas yang sesuai dengan jadwal Anda.';
            qrScanner = new Html5Qrcode('qrScanner');

            const timeout = new Promise((_, reject) => {
                window.setTimeout(() => reject(new Error('Permintaan kamera terlalu lama. Periksa izin kamera browser.')), 8000);
            });

            Promise.race([
                Html5Qrcode.getCameras().catch(() => []),
                timeout,
            ])
                .then(cameras => {
                    if (!cameras.length) {
                        throw new Error('Tidak ada kamera yang ditemukan pada perangkat ini.');
                    }

                    const camera = cameras.find(item => /back|rear|environment|belakang/i.test(item.label)) || cameras[0];
                    scannerElement.innerHTML = '';
                    scanMessage.textContent = 'Kamera aktif. Arahkan QR kelas ke kotak pemindai.';

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
                        : error.message || 'Kamera tidak dapat dibuka.';
                    scanMessage.textContent = message;
                    scannerElement.innerHTML = `<div class="qr-scanner-status">${message}</div>`;
                    if (qrScanner) {
                        qrScanner.clear().catch(() => {});
                        qrScanner = null;
                    }
                });
        }

        openScannerButton?.addEventListener('click', startQrScanner);

        closeScannerButton?.addEventListener('click', stopQrScanner);

        // Scanner langsung terbuka saat Guru masuk ke form jurnal.
        if (!@json($qrVerified)) {
            let attempts = 0;
            const autoStart = window.setInterval(() => {
                attempts++;
                if (typeof Html5Qrcode !== 'undefined' || attempts >= 20) {
                    window.clearInterval(autoStart);
                    startQrScanner();
                }
            }, 250);
        }

        updateSummary();
        const activeDispenBerakhir = @json($activeDispenBerakhir);
        if (activeDispenBerakhir) {
            const [hour, minute, second] = activeDispenBerakhir.split(':').map(Number);
            const waktuSelesai = new Date();
            waktuSelesai.setHours(hour, minute, second || 0, 0);
            const delay = waktuSelesai.getTime() - Date.now();

            if (delay > 0) {
                // Saat jam dispen berakhir, muat ulang agar siswa dapat dipilih
                // kembali sesuai status kehadiran yang sebenarnya.
                window.setTimeout(() => window.location.reload(), delay + 1000);
            }
        }
        const form = document.getElementById('journalForm');

        if (form) {

            form.addEventListener('submit', function () {

                const submitButton = form.querySelector('button[type="submit"]');

                if (submitButton) {

                    submitButton.disabled = true;
                    submitButton.style.opacity = '.7';
                    submitButton.style.cursor = 'not-allowed';

                }

            });

        }

        const currentTeacherStatus =
            document.getElementById('statusGuru')?.value;

        if (currentTeacherStatus) {

            document.querySelectorAll('.status-btn').forEach(button => {

                button.classList.toggle(
                    'active',
                    button.dataset.teacherStatus === currentTeacherStatus
                );

            });

        }

    });

</script>
@endsection
