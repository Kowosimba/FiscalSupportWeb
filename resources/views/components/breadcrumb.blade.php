<style>
    /* General Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #343a40;
    }

    /* Navigation Area */
    .navigation__section {
        padding: 30px 0; /* Reduced padding for a smaller area */
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .flex-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }

    .full-width {
        width: 100%;
    }

    /* Navigation Content */
    .navigation__content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .navigation__heading {
        margin: 0;
        font-size: 28px; /* Slightly smaller font size */
        font-weight: 700;
        color: #343a40;
        position: relative;
        transition: color 0.3s ease;
    }

    .navigation__heading::after {
        content: '';
        position: absolute;
        bottom: -8px; /* Adjusted position */
        left: 0;
        width: 50px;
        height: 3px;
        background-color: #216115;
        transition: width 0.3s ease;
    }

    .navigation__heading:hover::after {
        width: 100%; /* Animated underline on hover */
    }

    .navigation__heading:hover {
        color: #216115; /* Title color change on hover */
    }

    /* Navigation Links */
    .nav-links {
        display: flex;
        padding: 0;
        margin: 0;
        list-style: none;
        justify-content: flex-end;
        align-items: center;
    }

    .nav-item {
        margin-right: 10px; /* Reduced margin for tighter spacing */
        font-size: 16px;
        position: relative;
        transition: transform 0.3s ease;
    }

    .nav-item a {
        color: #216115;
        text-decoration: none;
        transition: color 0.3s ease, opacity 0.3s ease;
    }

    .nav-item a:hover {
        color: #1a4d10; /* Darker green on hover */
        opacity: 0.8;
    }

    .nav-item::after {
        content: '>';
        margin-left: 10px; /* Reduced spacing between items */
        color: #6c757d;
        font-weight: 500;
    }

    .nav-item:last-child::after {
        content: '';
    }

    .nav-item.current {
        color: #6c757d;
        font-weight: 500;
    }

    .nav-item:hover {
        transform: translateY(-2px); /* Slight lift effect on hover */
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .navigation__content {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .nav-links {
            justify-content: flex-start;
            margin-top: 10px;
        }

        .navigation__heading {
            font-size: 24px; /* Smaller font size for mobile */
        }
    }
</style>

<section class="navigation__section">
    <div class="wrapper">
        <div class="flex-row align-items-center">
            <div class="full-width">
                <div class="navigation__content">
                    <div>
                        <h1 class="navigation__heading">{{ $slot }}</h1>
                    </div>
                    <nav aria-label="navigation">
                        <ol class="nav-links">
                            <li class="nav-item"><a href="/">Home</a></li>
                            <li class="nav-item current" aria-current="page">{{ $slot }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>