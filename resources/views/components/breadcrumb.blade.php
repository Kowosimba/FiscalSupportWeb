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
      padding: 30px 0;
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
      font-size: 28px;
      font-weight: 700;
      color: #343a40;
      position: relative;
      transition: color 0.3s ease;
    }

    .navigation__heading::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 0;
      width: 50px;
      height: 3px;
      background-color: #216115;
      transition: width 0.3s ease;
    }

    .navigation__heading:hover::after {
      width: 100%;
    }

    .navigation__heading:hover {
      color: #216115;
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
      margin-right: 10px;
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
      color: #1a4d10;
      opacity: 0.8;
    }

    .nav-item::after {
      content: '>';
      margin-left: 10px;
      color: #6c757d;
      font-weight: 500;
    }

    .nav-item:last-child::after {
      content: '';
    }

    .nav-item.current {
      color: #6c757d;
      font-weight: 500;
      cursor: default;
      pointer-events: none;
    }

    .nav-item:hover {
      transform: translateY(-2px);
    }

    /* Responsive Design - Mobile First Approach */
    @media (max-width: 768px) {
      .navigation__section {
        padding: 15px 0;
        background-color: #ffffff;
        border-bottom: 2px solid #216115;
      }

      .wrapper {
        padding: 0 15px;
      }

      .navigation__content {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
      }

      /* Hide the large page title on mobile */
      .navigation__heading {
        display: none;
      }

      .nav-links {
        justify-content: flex-start;
        margin: 0;
        flex-wrap: nowrap;
        background-color: #f8f9fa;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }

      .nav-item {
        margin-right: 12px;
        font-size: 15px;
        font-weight: 600;
        white-space: nowrap;
      }

      .nav-item a {
        color: #216115;
        font-weight: 600;
      }

      .nav-item.current {
        color: #343a40;
        font-weight: 700;
      }

      .nav-item::after {
        margin-left: 6px;
        margin-right: 2px;
        font-size: 14px;
        color: #216115;
        font-weight: 600;
      }

      /* Remove hover effects on mobile for better touch experience */
      .nav-item:hover {
        transform: none;
      }

      .nav-item a:hover {
        color: #216115;
        opacity: 1;
      }
    }

    /* Extra small devices */
    @media (max-width: 480px) {
      .navigation__section {
        padding: 12px 0;
        background-color: #ffffff;
      }

      .wrapper {
        padding: 0 10px;
      }

      .nav-links {
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 14px;
      }

      .nav-item {
        margin-right: 8px;
        font-size: 14px;
      }

      .nav-item::after {
        margin-left: 4px;
        margin-right: 1px;
        font-size: 12px;
      }
    }

    /* Very small devices - minimal design */
    @media (max-width: 320px) {
      .navigation__section {
        padding: 10px 0;
      }

      .wrapper {
        padding: 0 8px;
      }

      .nav-links {
        padding: 5px 8px;
        border-radius: 3px;
      }

      .nav-item {
        font-size: 13px;
        margin-right: 6px;
      }

      .nav-item::after {
        font-size: 11px;
        margin-left: 3px;
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