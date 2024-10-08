<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jude's Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background-image: url('https://via.placeholder.com/1920x600?text=Jude%27s+Boutique+%7C+Tienda+de+Moda');
            background-size: cover;
            background-position: center;
            height: 60vh;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 4rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }

        .category-card img {
            height: 200px;
            object-fit: cover;
        }

        .category-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Jude's Boutique</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div>
            <h1>Bienvenido a Jude's Boutique</h1>
            <p class="lead">Tu estilo, tu hogar, tu tienda.</p>
            @if (Route::has('login'))
                @auth
                    <!-- Si el usuario está autenticado, redirigir a Home -->
                    <a href="{{ url('/home') }}" class="btn btn-light btn-lg mt-3">Ir a Home</a>
                @else
                    <!-- Si el usuario no está autenticado, redirigir al login -->
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg mt-3">Inicia Sesión</a>
                @endauth
            @endif
        </div>
    </section>

    <div class="container mt-5">
        <!-- Categories Section -->
        <div class="text-center mb-5">
            <h2>Categorías Destacadas</h2>
            <p class="lead">Explora nuestras colecciones más populares.</p>
        </div>

        <div class="row">
            <!-- Ropa -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card category-card">
                    <img src="https://via.placeholder.com/400x400?text=Ropa" class="card-img-top" alt="Ropa">
                    <div class="card-body text-center">
                        <p class="category-title">Ropa</p>
                        <p class="card-text">Encuentra lo último en moda para cualquier ocasión.</p>
                        <a href="#" class="btn btn-primary">Ver más</a>
                    </div>
                </div>
            </div>

            <!-- Accesorios -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card category-card">
                    <img src="https://via.placeholder.com/400x400?text=Accesorios" class="card-img-top" alt="Accesorios">
                    <div class="card-body text-center">
                        <p class="category-title">Accesorios</p>
                        <p class="card-text">Completa tu look con los accesorios más chic.</p>
                        <a href="#" class="btn btn-primary">Ver más</a>
                    </div>
                </div>
            </div>

            <!-- Zapatos -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card category-card">
                    <img src="https://via.placeholder.com/400x400?text=Zapatos" class="card-img-top" alt="Zapatos">
                    <div class="card-body text-center">
                        <p class="category-title">Zapatos</p>
                        <p class="card-text">Calzado para toda la familia y cualquier ocasión.</p>
                        <a href="#" class="btn btn-primary">Ver más</a>
                    </div>
                </div>
            </div>

            <!-- Artículos del Hogar -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card category-card">
                    <img src="https://via.placeholder.com/400x400?text=Hogar" class="card-img-top" alt="Hogar">
                    <div class="card-body text-center">
                        <p class="category-title">Artículos del Hogar</p>
                        <p class="card-text">Haz de tu casa un hogar con nuestros productos exclusivos.</p>
                        <a href="#" class="btn btn-primary">Ver más</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start mt-auto py-3">
        <div class="text-center p-3">
            &copy; 2024 Jude's Boutique. Todos los derechos reservados.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
