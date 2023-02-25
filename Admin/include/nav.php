<?php if(_CURRENT_FILE_ != 'index.php'){ ?>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse text-center" id="menu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="index.html">Home</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">Products</a>
                <ul class="dropdown-menu bg-dark">
                  <li><a class="dropdown-item" href="product.html">All Products</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#">Batteries</a></li>
                  <li><a class="dropdown-item" href="#">Inverter</a></li>
                  <li><a class="dropdown-item" href="#">Solar Panel</a></li>
                  <li><a class="dropdown-item" href="#">Charge Controller</a></li>
                  <li><a class="dropdown-item" href="#">Accessories</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">Services</a>
                <ul class="dropdown-menu bg-dark">
                  <li><a class="dropdown-item" href="service.html">Installation</a></li>
                  <li><a class="dropdown-item" href="#">Maintenance</a></li>
                  <li><a class="dropdown-item" href="#">Troubleshooting</a></li>
                  <li><a class="dropdown-item" href="#">Repair</a></li>
                  <li><a class="dropdown-item" href="#">Consultation</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="cart.html">Cart<i class="fa-solid fa-cart-shopping"></i></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">About</a>
              </li>
            </ul>
            <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn" type="submit">Search</button>
            </form>
          </div>
<?php } ?>