<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jude's Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
            color: #000;
        }

        .navbar {
            background-color: #000;
        }

        .navbar-brand img {
            height: 50px;
            width: 50px;
            border-radius: 50%;
        }

        .navbar .nav-link {
            color: #fff !important;
        }

        .hero-section {
            background: url('vendor/adminlte/dist/img/background.jpg');
            background-size: cover;
            background-position: center;
            height: 50vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
        }

        .hero-section h1 {
            font-size: 4rem;
            font-weight: bold;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }

        .category-card {
            border: none;
        }

        .category-card img {
            height: 250px;
            object-fit: cover;
        }

        .category-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #000;
        }

        .btn-gold {
            background-color: #FFD700;
            color: #000;
            font-weight: bold;
        }

        footer {
            background-color: #000;
            color: #fff;
        }

        .steps-section {
            padding: 60px 0;
        }

        .step-card {
            background: RGB(255, 215, 0);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 15px 15px 0px black;
            text-align: center;
            transition: transform 0.3s ease-in-out;
            color: black;
        }

        .step-card:hover {
            transform: translateY(-10px);
        }

        .step-card h3 {
            font-size: 2rem;
            font-weight: bold;
            text-align: left;
            padding-left: 10px;
        }

        .step-card h4 {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .step-card p {
            font-size: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('vendor/adminlte/dist/img/logo.jpg') }}" alt="Logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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

        <section class="hero-section">
            <div>
                <h1>Bienvenido a Jude's Boutique</h1>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-gold btn-lg mt-3">Ir a Home</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-gold btn-lg mt-3">Inicia Sesión</a>
                    @endauth
                @endif
            </div>
        </section>

        <div class="container mt-5">
            <div class="text-center mb-5">
                <h2>Categorías Destacadas</h2>
                <p class="lead">Explora nuestras colecciones más exclusivas.</p>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card category-card">
                        <img src="{{ asset('vendor/adminlte/dist/img/ropa.jpg') }}" class="card-img-top" alt="Ropa">
                        <div class="card-body text-center">
                            <p class="category-title">Ropa</p>
                            <a href="#" class="btn btn-gold">Ver más</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card category-card">
                        <img src="vendor/adminlte/dist/img/accesorios.jpg" class="card-img-top" alt="Accesorios">
                        <div class="card-body text-center">
                            <p class="category-title">Accesorios</p>
                            <a href="#" class="btn btn-gold">Ver más</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card category-card">
                        <img src="vendor/adminlte/dist/img/zapatos.jpg" class="card-img-top" alt="Zapatos">
                        <div class="card-body text-center">
                            <p class="category-title">Zapatos</p>
                            <a href="#" class="btn btn-gold">Ver más</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card category-card">
                        <img src="vendor/adminlte/dist/img/images.png" class="card-img-top" alt="Hogar">
                        <div class="card-body text-center">
                            <p class="category-title">Hogar</p>
                            <a href="#" class="btn btn-gold">Ver más</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="steps-section">
            <div class="container text-center">
                <h2>¿Cómo funciona Jude's Boutique?</h2>
                <div class="row mt-5 justify-content-center">
                    <div class="col-md-3">
                        <div class="step-card">
                            <h3>1</h3>
                            <h4>Encuentra tu producto</h4>
                            <p>Explora Shein, Amazon, Ebay u otras tiendas en línea en todo el mundo y elige los productos que te gusten.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="step-card">
                            <h3>2</h3>
                            <h4>Envíanos los enlaces</h4>
                            <p>Compártenos los enlaces de los productos que deseas, este paso es importante para evitar confusiones.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="step-card">
                            <h3>3</h3>
                            <h4>Realizamos tu cotización</h4>
                            <p>Te enviamos una cotización y esperamos tu confirmación a través del pago de tu anticipo.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="step-card">
                            <h3>4</h3>
                            <h4>Recibe tus productos</h4>
                            <p>Una vez hecha la confirmación, recibirás tu producto en 15-30 días (dependiendo del proveedor).</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="text-center py-3">
            <div>
                <p>&copy; <?php echo date("Y"); ?> Jude's Boutique. Todos los derechos reservados.</p>
            </div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>

</html>
