@php
    $accountRows = [
        'Nama Lengkap' => $profileName ?: '-',
        'Username' => $user->username ?: '-',
        'Role' => ($profileRole ?? $user->role) ?: '-',
    ];

    $phoneNumber = $user->no_wa ?? $user->no_hp ?? $user->guru?->no_hp ?? $user->no_telepon ?? null;

    if (in_array($user->role, ['Admin', 'Staff Piket'], true) && $phoneNumber) {
        $accountRows['Nomor Telepon'] = $phoneNumber;
    }

    if ($user->role === 'Guru' && $user->guru?->no_hp) {
        $accountRows['Nomor Telepon'] = $user->guru->no_hp;
    }

    if ($user->role === 'Kesiswaan') {
        $accountRows['Nomor WhatsApp'] = $user->no_wa ?: '-';
    }

    if ($user->role === 'Sekretaris') {
        $accountRows['Kelas'] = $user->kelas?->nama_kelas ?? 'Belum ditetapkan';
    }
@endphp

<section class="shared-account-card" aria-labelledby="shared-account-heading">
    <div class="shared-account-heading">
        <span class="shared-account-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="4" y="3" width="16" height="18" rx="2" />
                <circle cx="12" cy="9" r="2.5" />
                <path d="M8 17c.8-1.7 2.1-2.5 4-2.5s3.2.8 4 2.5" stroke-linecap="round" />
            </svg>
        </span>
        <h3 id="shared-account-heading">Informasi Akun</h3>
    </div>

    <dl class="shared-account-list">
        @foreach($accountRows as $label => $value)
            <div class="shared-account-row">
                <dt>{{ $label }}</dt>
                <dd>{{ $value ?: '-' }}</dd>
            </div>
        @endforeach
    </dl>
</section>

@once
    <style>
        .shared-account-card {
            padding: 22px 24px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(30, 41, 59, .04);
        }

        .shared-account-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 15px;
            border-bottom: 1px solid #edf0f6;
        }

        .shared-account-icon {
            display: grid;
            width: 32px;
            height: 32px;
            place-items: center;
            border-radius: 8px;
            background: #eef2ff;
            color: #4757b2;
        }

        .shared-account-icon svg {
            width: 19px;
            height: 19px;
        }

        .shared-account-heading h3 {
            margin: 0;
            color: #202b61;
            font-size: 15px;
            font-weight: 800;
        }

        .shared-account-list {
            margin: 0;
        }

        .shared-account-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 15px 0;
            border-bottom: 1px solid #f0f2f7;
        }

        .shared-account-row:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .shared-account-row dt {
            color: #71809b;
            font-size: 12px;
            font-weight: 600;
        }

        .shared-account-row dd {
            margin: 0;
            color: #172554;
            font-size: 13px;
            font-weight: 700;
            text-align: right;
            overflow-wrap: anywhere;
        }

        @media (max-width: 600px) {
            .shared-account-card {
                padding: 18px 16px;
            }

            .shared-account-row {
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
@endonce