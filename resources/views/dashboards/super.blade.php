<div class="text-center">
    <h1>Super Admin Dashboard</h1>
    <p class="lead">Homepage untuk Super Admin</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-light">Logout</button>
    </form>

</div>