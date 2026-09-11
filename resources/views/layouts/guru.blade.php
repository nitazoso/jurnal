<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Guru - Jurnify')</title>
</head>

<body>

    <aside>
        <h2>Jurnify</h2>

        <p>Menu Guru</p>

        <ul>
            <li>
                <a href="{{ route('guru.dashboard') }}">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('guru.jurnal.index') }}">
                    Isi Jurnal
                </a>
            </li>

            <li>
                <a href="{{ route('guru.jurnal.index') }}">
                    Daftar Jurnal
                </a>
            </li>

            <li>
                <a href="{{ route('guru.profil') }}">
                    Profil
                </a>
            </li>

            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <main>

        @yield('content')

    </main>

</body>
</html>