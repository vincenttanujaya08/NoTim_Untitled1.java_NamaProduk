<div class="text-center">
    <h1>Dashboard Petani</h1>
    <p class="lead">Homepage untuk Petani</p>


    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-light">Logout</button>
    </form>
</div>