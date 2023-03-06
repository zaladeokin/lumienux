<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse text-center" id="menu">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">Products</a>
            <ul class="dropdown-menu bg-dark">
                <li><a class="dropdown-item" href="product.php">All Products</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="product.php?category=1">Batteries</a></li>
                <li><a class="dropdown-item" href="product.php?category=2">Inverter</a></li>
                <li><a class="dropdown-item" href="product.php?category=3">Solar Panel</a></li>
                <li><a class="dropdown-item" href="product.php?category=4">Charge Controller</a></li>
                <li><a class="dropdown-item" href="product.php?category=5">Light</a></li>
                <li><a class="dropdown-item" href="product.php?category=6">Accessories</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="service.php">Services</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="cart.php">Cart<i class="fa-solid fa-cart-shopping"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="about.php">About</a>
        </li>
    </ul>
    <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" id="search" placeholder="Search" aria-label="Search">
    </form>
</div>