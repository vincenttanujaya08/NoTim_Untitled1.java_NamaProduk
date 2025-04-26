@extends('base.layout')
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body class="bg-gray-100 text-gray-900">
    <main class="" id="home">
        <section class="bg-gray-800 text-white text-center py-20">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">PanenHub</h1>
            <p class="text-xl mb-6">Welcome to PanenHub</p>
        </section>

        <section id="about" class="py-16 px-4 text-center bg-white">
            <h2 class="text-3xl font-semibold mb-4">About Us</h2>
            <p class="text-xl max-w-3xl mx-auto">A digital platfrom where you can buy...</p>
        </section>

        <section id="service" class="py-16 px-4 bg-gray-100">
            <h2 class="text-3xl font-semibold text-center mb-8">Our Services</h2>
            <div class="flex flex-wrap justify-center gap-8">

                <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-1/3">
                    <h3 class="text-2xl font-semibold mb-4">desc1</h3>
                    <p>blabla..</p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-1/3">
                    <h3 class="text-2xl font-semibold mb-4">desc2</h3>
                    <p>blabla..</p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-1/3">
                    <h3 class="text-2xl font-semibold mb-4">desc3</h3>
                    <p>blabla..</p>
                </div>
            </div>
        </section>

    </main>
</body>

</html>
@endsection