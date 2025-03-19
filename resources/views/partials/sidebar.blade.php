<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand mt-3">
            <img src="{{ URL::asset($pengaturan->logo) ?? 'https://via.placeholder.com/300' }}" alt="" style="width: 50px">
            <a href="">{{ $pengaturan->name ?? config('app.name') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="#">{{ strtoupper(substr(config('app.name'), 0, 2)) }}</a>
        </div>
        <ul class="sidebar-menu">
            @if (Auth::check() && Auth::user()->roles == 'admin')
            <li class="{{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-columns"></i> <span>Dashboard</span></a></li>
            <li class="menu-header">Master Data</li>

            <li class="{{ request()->routeIs('mapel.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.proposal.list') }}"><i class="fas fa-book"></i> <span>Histori Proposal</span></a></li>

            <li class="{{ request()->routeIs('guru.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.aturDana') }}">
        <i class="fas fa-money-bill-wave"></i> <span>Atur Dana Proposal</span>
    </a>

            <li class="{{ request()->routeIs('kelas.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('kelas.index') }}"><i class="far fa-building"></i> <span>Kelas</span></a></li>

            <li class="{{ request()->routeIs('siswa.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('siswa.index') }}"><i class="fas fa-users"></i> <span>Siswa</span></a></li>

            <li class="{{ request()->routeIs('jadwal.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('jadwal.index') }}"><i class="fas fa-calendar"></i> <span>Jadwal</span></a></li>

            <li class="{{ request()->routeIs('user.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('user.index') }}"><i class="fas fa-user"></i> <span>User</span></a></li>

            <li class="{{ request()->routeIs('pengumuman-sekolah.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('pengumuman-sekolah.index') }}"><i class="fas fa-bullhorn"></i> <span>Pengumuman</span></a></li>

            <li class="{{ request()->routeIs('pengaturan.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('pengaturan.index') }}"><i class="fas fa-cog"></i> <span>Pengaturan</span></a></li>

            @elseif (auth()->user()->roles == 'pembina')
<li class="{{ request()->routeIs('pembina.proposal.*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('pembina.proposal.index') }}">
        <i class="fas fa-folder-open"></i> <span>Proposal Masuk</span>
    </a>
</li>

            @elseif (Auth::check() && Auth::user()->roles == 'siswa')
            <li class="{{ request()->routeIs('siswa.dashboard.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('siswa.dashboard') }}"><i class="fas fa-columns"></i> <span>Dashboard</span></a></li>
            <li class="{{ request()->routeIs('materi.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('proposal.form') }}"><i class="fas fa-book"></i> <span>Pengajuan</span></a></li>
            <li class="{{ request()->routeIs('tugas.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('siswa.proposal') }}"><i class="fas fa-list"></i> <span>Status</span></a></li>

            @else
            <li class="{{ request()->routeIs('orangtua.dashboard.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('orangtua.dashboard') }}"><i class="fas fa-columns"></i> <span>Dashboard</span></a></li>
            <li class="{{ request()->routeIs('orangtua.tugas.siswa') ? 'active' : '' }}"><a class="nav-link" href="{{ route('orangtua.tugas') }}"><i class="fas fa-list"></i> <span>Tugas</span></a></li>
            @endif

        </ul>
    </aside>
</div>
