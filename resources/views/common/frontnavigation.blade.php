<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm" id="mainNav">
    <div class="container px-5">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">Designsowl</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="bi-list"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto me-4 my-3 my-lg-0">
                <li class="nav-item"><a class="nav-link me-lg-3" href="{{ route('solutions') }}">Solutions</a></li>
		        <li class="nav-item"><a class="nav-link me-lg-3" href="{{ route('platform') }}">Our Platform</a></li>
		        <li class="nav-item"><a class="nav-link me-lg-3" href="{{ route('ourworks') }}">Our Works</a></li>
		        <li class="nav-item"><a class="nav-link me-lg-3" href="{{ route('resources') }}">Resources</a></li>
		        <li class="nav-item"><a class="nav-link me-lg-3" href="{{ route('plans') }}">Plans</a></li>
		        <li class="nav-item"><a class="nav-link me-lg-3" href="{{ route('login') }}"><i class="bi bi-person-circle me-2"></i>Login</a></li>
            </ul>
            <a class="btn btn-primary rounded-pill px-3 mb-2 mb-lg-0" href="{{ route('plans') }}">
                <span class="d-flex align-items-center">
                    <i class="bi bi-tag-fill me-2"></i>
                    <span class="small">Get Started</span>
                </span>
            </a>
        </div>
    </div>
</nav>