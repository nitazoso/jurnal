@unless($hideTrigger ?? false)
    <div class="profile-modal-actions">
        <button type="button" class="profile-modal-trigger" data-profile-edit-open>✎ Edit Profil</button>
    </div>
@endunless

<dialog class="profile-modal" data-profile-edit-dialog aria-labelledby="profile-edit-title">
    <form action="{{ $action }}" method="POST" class="profile-modal-form">
        @csrf
        @method('PUT')
        <div class="profile-modal-heading">
            <div>
                <p class="profile-modal-eyebrow">PENGATURAN AKUN</p>
                <h2 id="profile-edit-title">Edit Profil</h2>
            </div>
            <button type="button" class="profile-modal-close" data-profile-edit-close aria-label="Tutup">&times;</button>
        </div>
        <label for="profile-username">Username</label>
        <input id="profile-username" name="username" type="text" value="{{ old('username', $user->username ?? '') }}" required autocomplete="username">
        @error('username')<small class="profile-modal-error">{{ $message }}</small>@enderror

        <label for="profile-password">Password Baru</label>
        <input id="profile-password" name="password" type="password" placeholder="Kosongkan jika tidak ingin mengubah password" autocomplete="new-password">
        @error('password')<small class="profile-modal-error">{{ $message }}</small>@enderror

        <label for="profile-password-confirmation">Konfirmasi Password</label>
        <input id="profile-password-confirmation" name="password_confirmation" type="password" placeholder="Ulangi password baru" autocomplete="new-password">

        <div class="profile-modal-footer">
            <button type="button" class="profile-modal-cancel" data-profile-edit-close>Batal</button>
            <button type="submit" class="profile-modal-save">Simpan Perubahan</button>
        </div>
    </form>
</dialog>

@if (session('success'))
    <dialog class="profile-modal profile-success-modal" data-profile-success-dialog aria-labelledby="profile-success-title">
        <div class="profile-success-icon" aria-hidden="true">✓</div>
        <h2 id="profile-success-title">Profil berhasil disimpan</h2>
        <p>{{ session('success') }}</p>
        <button type="button" class="profile-modal-save" data-profile-success-close>Tutup</button>
    </dialog>
@endif

<style>
    .profile-modal-actions { display:flex; justify-content:flex-end; margin:0 0 18px; }
    .profile-modal-trigger,.profile-modal-save,.profile-modal-cancel { border:0; border-radius:10px; padding:11px 17px; font:inherit; font-weight:700; cursor:pointer; }
    .profile-modal-trigger,.profile-modal-save { background:#4f46e5; color:#fff; box-shadow:0 5px 14px #4f46e533; }
    .profile-modal-trigger:hover,.profile-modal-save:hover { background:#4338ca; }
    .profile-modal { position:fixed; inset:0; margin:auto; width:min(460px,calc(100% - 32px)); max-height:calc(100% - 32px); padding:0; border:1px solid #e2e8f0; border-radius:20px; color:#172033; box-shadow:0 24px 80px #0f172a33; }
    .profile-modal::backdrop { background:#0f172a80; backdrop-filter:blur(3px); }
    .profile-modal-form { display:flex; flex-direction:column; gap:10px; padding:24px; }
    .profile-modal-heading { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:10px; }
    .profile-modal-eyebrow { margin:0 0 5px; color:#6366f1; font-size:10px; font-weight:800; letter-spacing:.14em; }
    .profile-modal-heading h2,.profile-success-modal h2 { margin:0; color:#172033; font-size:21px; font-weight:800; }
    .profile-modal-close { border:0; background:transparent; color:#64748b; font-size:28px; line-height:1; cursor:pointer; }
    .profile-modal-form label { margin-top:5px; color:#344054; font-size:13px; font-weight:700; }
    .profile-modal-form input { width:100%; box-sizing:border-box; padding:11px 12px; border:1px solid #d8deea; border-radius:10px; background:#f8fafc; color:#172033; font:inherit; }
    .profile-modal-form input:focus { outline:3px solid #4f46e526; border-color:#6366f1; }
    .profile-modal-error { color:#b42318; font-size:12px; }
    .profile-modal-footer { display:flex; justify-content:flex-end; gap:9px; margin-top:12px; }
    .profile-modal-cancel { background:#f1f5f9; color:#475569; }
    .profile-success-modal { padding:30px 26px 24px; text-align:center; }
    .profile-success-icon { display:grid; place-items:center; width:52px; height:52px; margin:0 auto 14px; border-radius:50%; background:#dcfce7; color:#15803d; font-size:30px; font-weight:800; }
    .profile-success-modal p { margin:9px 0 22px; color:#64748b; }
    .profile-success-modal .profile-modal-save { min-width:110px; }
    @media(max-width:520px) { .profile-modal-form { padding:20px; } .profile-modal-footer { flex-direction:column-reverse; } .profile-modal-footer button { width:100%; } }
</style>
<script>
    (() => {
        const editDialog = document.querySelector('[data-profile-edit-dialog]');
        if (!editDialog || editDialog.dataset.bound) return;
        editDialog.dataset.bound = 'true';
        document.querySelector('[data-profile-edit-open]')?.addEventListener('click', () => editDialog.showModal());
        editDialog.querySelectorAll('[data-profile-edit-close]').forEach(button => button.addEventListener('click', () => editDialog.close()));
        editDialog.addEventListener('click', event => { if (event.target === editDialog) editDialog.close(); });
        @if ($errors->any())
            editDialog.showModal();
        @endif
        const successDialog = document.querySelector('[data-profile-success-dialog]');
        if (successDialog) {
            successDialog.showModal();
            successDialog.querySelector('[data-profile-success-close]')?.addEventListener('click', () => successDialog.close());
            successDialog.addEventListener('click', event => { if (event.target === successDialog) successDialog.close(); });
        }
    })();
</script>
