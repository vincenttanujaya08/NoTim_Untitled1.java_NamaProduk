<div class="text-center">
    <h1>Petugas Lapangan Dashboard</h1>
    <p class="lead">Homepage untuk Petugas Lapangan</p>


    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-light">Logout</button>
    </form>
</div>