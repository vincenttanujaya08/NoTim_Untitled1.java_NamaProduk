<div class="text-center">
    <h1>Admin Koperasi Dashboard</h1>
    <p class="lead">Homepage untuk Admin Koperasi</p>


    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-light">Logout</button>
    </form>
</div>