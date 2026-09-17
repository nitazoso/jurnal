<aside class="sidebar">
    <div class="brand">
        <div class="brand-icon">
            <svg width="26" height="22" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6676 5.66667V22M12.6676 5.66667C12.6676 4.42899 12.1759 3.242 11.3007 2.36683C10.4254 1.49167 9.23834 1 8.00056 1H2.16676C1.85732 1 1.56055 1.12292 1.34174 1.34171C1.12293 1.5605 1 1.85725 1 2.16667V17.3333C1 17.6428 1.12293 17.9395 1.34174 18.1583C1.56055 18.3771 1.85732 18.5 2.16676 18.5H9.16732C10.0957 18.5 10.986 18.8687 11.6424 19.5251C12.2988 20.1815 12.6676 21.0717 12.6676 22M12.6676 5.66667C12.6676 4.42899 13.1593 3.242 14.0345 2.36683C14.9098 1.49167 16.0969 1 17.3346 1H23.1684C23.4779 1 23.7747 1.12292 23.9935 1.34171C24.2123 1.5605 24.3352 1.85725 24.3352 2.16667V17.3333C24.3352 17.6428 24.2123 17.9395 23.9935 18.1583C23.7747 18.3771 23.4779 18.5 23.1684 18.5H16.1679C15.2395 18.5 14.3492 18.8687 13.6928 19.5251C13.0364 20.1815 12.6676 21.0717 12.6676 22" stroke="#2D336B" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="brand-text">
            <h1>Jurnify</h1>
            <p>Kementerian Pendidikan</p>
        </div>
    </div>

    <nav class="nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="material-symbols-outlined">home</span>
            <span>Dashboard</span>
        </a>

        <a href="#" class="nav-item {{ request()->routeIs('admin.jurnal.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">menu_book</span>
            <span>Daftar Jurnal</span>
        </a>

        <a href="{{ route('admin.user.index') }}" class="nav-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">person_add</span>
            <span>User</span>
        </a>

        <a href="{{ route('admin.kelas.index') }}" class="nav-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">groups</span>
            <span>Kelas</span>
        </a>

        <a href="#" class="nav-item {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">menu_book</span>
            <span>Mapel</span>
        </a>

        <a href="#" class="nav-item {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">calendar_month</span>
            <span>Jadwal</span>
        </a>

        <a href="#" class="nav-item {{ request()->routeIs('admin.profil.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">person</span>
            <span>Profil</span>
        </a>
    </nav>
</aside>