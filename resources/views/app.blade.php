<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MakanKu') - Pesan Makanan Online</title>
    <meta name="description" content="@yield('description', 'Pesan makanan favorit kamu dari restoran terbaik di kotamu. Cepat, mudah, dan lezat!')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <style>
        :root {
            --primary: #FF6B35;
            --primary-dark: #E55A28;
            --primary-light: #FF8C5A;
            --primary-pale: #FFF3EE;
            --secondary: #1A1A2E;
            --accent: #FFD60A;
            --accent2: #06D6A0;
            --text-dark: #1A1A2E;
            --text-gray: #6B7280;
            --text-light: #9CA3AF;
            --bg-light: #F9FAFB;
            --bg-card: #FFFFFF;
            --border: #E5E7EB;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.10);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.14);
            --shadow-xl: 0 20px 60px rgba(0,0,0,0.18);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-full: 9999px;
            --font-heading: 'Sora', sans-serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 3px; }

        /* ===== NAVBAR ===== */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0;
        }

        .navbar-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            height: 70px;
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-logo {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .brand-name {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 22px;
            color: var(--text-dark);
        }

        .brand-name span { color: var(--primary); }

        /* Location picker */
        .location-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: var(--primary-pale);
            border: 1.5px solid #FFD5C0;
            border-radius: var(--radius-full);
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-dark);
            transition: all 0.2s;
            white-space: nowrap;
        }

        .location-btn:hover { background: #FFE4D6; }

        /* Search */
        .search-wrap {
            flex: 1;
            max-width: 480px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 11px 20px 11px 48px;
            background: var(--bg-light);
            border: 2px solid var(--border);
            border-radius: var(--radius-full);
            font-family: var(--font-body);
            font-size: 14px;
            color: var(--text-dark);
            transition: all 0.2s;
            outline: none;
        }

        .search-input:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(255,107,53,0.1); }
        .search-input::placeholder { color: var(--text-light); }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 16px;
            pointer-events: none;
        }

        .search-submit {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-full);
            padding: 7px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .search-submit:hover { background: var(--primary-dark); }

        /* Nav right */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .nav-icon-btn {
            position: relative;
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-gray);
            font-size: 18px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .nav-icon-btn:hover { background: var(--primary-pale); color: var(--primary); }

        .badge {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 18px;
            height: 18px;
            background: var(--primary);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
        }

        /* User menu */
        .user-menu-wrap { position: relative; }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 6px;
            background: transparent;
            border: 2px solid var(--border);
            border-radius: var(--radius-full);
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-btn:hover { border-color: var(--primary); background: var(--primary-pale); }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            width: 220px;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s;
            z-index: 999;
        }

        .user-menu-wrap:hover .user-dropdown,
        .user-dropdown.show { opacity: 1; visibility: visible; transform: translateY(0); }

        .dropdown-header {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            background: var(--primary-pale);
        }

        .dropdown-header strong { display: block; font-size: 14px; color: var(--text-dark); }
        .dropdown-header small { color: var(--text-gray); font-size: 12px; }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            font-size: 13px;
            color: var(--text-dark);
            text-decoration: none;
            transition: background 0.15s;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
        }

        .dropdown-item:hover { background: var(--bg-light); }
        .dropdown-item i { width: 18px; color: var(--text-gray); }

        .dropdown-divider { height: 1px; background: var(--border); margin: 4px 0; }

        /* Auth buttons */
        .btn-login {
            padding: 9px 20px;
            background: var(--primary-pale);
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: var(--radius-full);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-login:hover { background: var(--primary); color: #fff; }

        .btn-register {
            padding: 9px 20px;
            background: var(--primary);
            color: #fff;
            border: 2px solid var(--primary);
            border-radius: var(--radius-full);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-register:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            min-height: calc(100vh - 70px - 340px);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--secondary);
            color: #fff;
            padding: 60px 0 0;
            margin-top: 80px;
        }

        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            padding-bottom: 48px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .footer-brand p {
            color: rgba(255,255,255,0.55);
            font-size: 14px;
            line-height: 1.8;
            margin-top: 16px;
        }

        .footer-apps {
            display: flex;
            gap: 8px;
            margin-top: 20px;
        }

        .app-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: var(--radius-md);
            text-decoration: none;
            color: #fff;
            font-size: 12px;
            transition: all 0.2s;
        }

        .app-btn:hover { background: rgba(255,255,255,0.2); }
        .app-btn i { font-size: 20px; }
        .app-btn span { display: block; font-weight: 600; font-size: 13px; }

        .footer-col h4 {
            font-family: var(--font-heading);
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #fff;
        }

        .footer-links { list-style: none; }

        .footer-links li { margin-bottom: 10px; }

        .footer-links a {
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }

        .footer-links a:hover { color: var(--primary); }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 0;
            font-size: 13px;
            color: rgba(255,255,255,0.4);
        }

        .footer-social { display: flex; gap: 12px; }

        .social-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-decoration: none;
            font-size: 15px;
            transition: all 0.2s;
        }

        .social-btn:hover { background: var(--primary); transform: translateY(-2px); }

        /* ===== TOAST NOTIFICATIONS ===== */
        .toast-container {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            background: #fff;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-xl);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            max-width: 380px;
            border-left: 4px solid var(--primary);
            animation: slideIn 0.3s ease;
        }

        .toast.success { border-left-color: var(--accent2); }
        .toast.error { border-left-color: #EF4444; }
        .toast.warning { border-left-color: var(--accent); }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* ===== CART SIDEBAR ===== */
        .cart-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1100;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .cart-overlay.open { opacity: 1; visibility: visible; }

        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -420px;
            width: 420px;
            height: 100%;
            background: #fff;
            z-index: 1101;
            display: flex;
            flex-direction: column;
            transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: -10px 0 60px rgba(0,0,0,0.15);
        }

        .cart-sidebar.open { right: 0; }

        .cart-sidebar-header {
            padding: 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-sidebar-header h3 {
            font-family: var(--font-heading);
            font-size: 18px;
            font-weight: 700;
        }

        .cart-close {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--border);
            background: none;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-gray);
            transition: all 0.2s;
        }

        .cart-close:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-pale); }

        .cart-items-list {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .cart-footer {
            padding: 20px 24px;
            border-top: 1px solid var(--border);
            background: var(--bg-light);
        }

        .cart-total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 14px;
            color: var(--text-gray);
        }

        .cart-total-row.total {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--border);
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: var(--radius-full);
            font-family: var(--font-body);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
            white-space: nowrap;
        }

        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 16px rgba(255,107,53,0.35); }

        .btn-outline { background: transparent; color: var(--primary); border: 2px solid var(--primary); }
        .btn-outline:hover { background: var(--primary); color: #fff; }

        .btn-dark { background: var(--secondary); color: #fff; }
        .btn-dark:hover { background: #2d2d44; }

        .btn-sm { padding: 8px 16px; font-size: 13px; }
        .btn-lg { padding: 16px 32px; font-size: 16px; }
        .btn-block { width: 100%; }

        /* ===== CONTAINERS ===== */
        .container { max-width: 1280px; margin: 0 auto; padding: 0 24px; }
        .section { padding: 48px 0; }
        .section-sm { padding: 32px 0; }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 28px;
        }

        .section-title {
            font-family: var(--font-heading);
            font-size: 22px;
            font-weight: 800;
            color: var(--text-dark);
        }

        .section-subtitle {
            font-size: 14px;
            color: var(--text-gray);
            margin-top: 4px;
        }

        .section-link {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .section-link:hover { text-decoration: underline; }

        /* ===== CARD ===== */
        .card {
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            overflow: hidden;
            transition: all 0.25s;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        /* ===== RESTAURANT CARD ===== */
        .restaurant-card {
            background: #fff;
            border-radius: var(--radius-lg);
            overflow: hidden;
            cursor: pointer;
            transition: all 0.25s;
            border: 1px solid var(--border);
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .restaurant-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .restaurant-card-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
            position: relative;
            background: #f3f4f6;
        }

        .restaurant-card-img-wrap { position: relative; overflow: hidden; height: 180px; }

        .restaurant-card-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .restaurant-card:hover .restaurant-card-img-wrap img { transform: scale(1.06); }

        .restaurant-card-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 4px 10px;
            border-radius: var(--radius-full);
            font-size: 11px;
            font-weight: 700;
            backdrop-filter: blur(10px);
        }

        .badge-promo { background: var(--primary); color: #fff; }
        .badge-new { background: var(--accent2); color: #fff; }
        .badge-popular { background: var(--accent); color: var(--text-dark); }

        .restaurant-fav-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(8px);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: #ddd;
            transition: all 0.2s;
        }

        .restaurant-fav-btn.active,
        .restaurant-fav-btn:hover { color: #EF4444; transform: scale(1.1); }

        .restaurant-card-body { padding: 14px 16px 16px; }

        .restaurant-logo-wrap {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 2px solid #fff;
            box-shadow: var(--shadow-sm);
            flex-shrink: 0;
        }

        .restaurant-card-top {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .restaurant-card-info { flex: 1; }

        .restaurant-name {
            font-family: var(--font-heading);
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 3px;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .restaurant-category {
            font-size: 12px;
            color: var(--text-gray);
        }

        .restaurant-meta {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 12px;
            color: var(--text-gray);
        }

        .meta-item { display: flex; align-items: center; gap: 4px; }

        .rating-star { color: #F59E0B; font-size: 12px; }

        .free-delivery { color: var(--accent2); font-weight: 700; }

        /* ===== MENU CARD ===== */
        .menu-card {
            background: #fff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            overflow: hidden;
            transition: all 0.25s;
            position: relative;
        }

        .menu-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-3px); }

        .menu-card-img-wrap {
            height: 160px;
            overflow: hidden;
            position: relative;
            background: #f3f4f6;
        }

        .menu-card-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .menu-card:hover .menu-card-img-wrap img { transform: scale(1.08); }

        .menu-discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--primary);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: var(--radius-full);
        }

        .menu-card-body { padding: 12px 14px 14px; }

        .menu-name {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .menu-restaurant {
            font-size: 11px;
            color: var(--text-gray);
            margin-bottom: 8px;
        }

        .menu-price-wrap { display: flex; align-items: center; gap: 6px; }

        .menu-price { font-size: 15px; font-weight: 800; color: var(--primary); }

        .menu-price-original { font-size: 12px; color: var(--text-light); text-decoration: line-through; }

        .menu-add-btn {
            margin-top: 10px;
            width: 100%;
            padding: 8px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .menu-add-btn:hover { background: var(--primary-dark); }

        /* ===== GRID LAYOUTS ===== */
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .grid-6 { display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; }

        /* ===== HORIZONTAL SCROLL ===== */
        .scroll-row {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 12px;
            scrollbar-width: thin;
            scrollbar-color: var(--primary) transparent;
        }

        .scroll-row::-webkit-scrollbar { height: 4px; }
        .scroll-row::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 2px; }

        .scroll-row > * { flex-shrink: 0; }

        /* ===== MISC UTILITIES ===== */
        .text-primary { color: var(--primary); }
        .text-gray { color: var(--text-gray); }
        .text-small { font-size: 12px; }
        .font-bold { font-weight: 700; }
        .font-heading { font-family: var(--font-heading); }
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .gap-8 { gap: 8px; }
        .gap-12 { gap: 12px; }
        .mt-8 { margin-top: 8px; }
        .mt-16 { margin-top: 16px; }
        .mb-0 { margin-bottom: 0; }
        .hidden { display: none; }

        /* ===== CATEGORY PILLS ===== */
        .category-pill {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: #fff;
            border: 2px solid var(--border);
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: var(--text-dark);
            width: 80px;
        }

        .category-pill:hover, .category-pill.active {
            border-color: var(--primary);
            background: var(--primary-pale);
            color: var(--primary);
        }

        .category-pill-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .category-pill-name { font-size: 11px; font-weight: 700; text-align: center; }

        /* ===== PROMO CARD ===== */
        .promo-card {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius-xl);
            padding: 24px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .promo-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .promo-card::after {
            content: '';
            position: absolute;
            bottom: -30px;
            right: 60px;
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }

        .voucher-card {
            background: #fff;
            border: 2px dashed var(--primary);
            border-radius: var(--radius-lg);
            padding: 20px;
            display: flex;
            gap: 16px;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .voucher-card::before {
            content: '';
            width: 2px;
            height: 100%;
            background: repeating-linear-gradient(to bottom, #fff 0, #fff 6px, transparent 6px, transparent 12px);
            position: absolute;
            left: 80px;
            top: 0;
        }

        /* ===== ALERT / FLASH ===== */
        .alert { padding: 14px 20px; border-radius: var(--radius-md); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 14px; }
        .alert-success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEF2F2; color: #991B1B; border: 1px solid #FCA5A5; }
        .alert-warning { background: #FFFBEB; color: #92400E; border: 1px solid #FCD34D; }
        .alert-info { background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; }

        /* ===== FORM ELEMENTS ===== */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; }
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-family: var(--font-body);
            font-size: 14px;
            color: var(--text-dark);
            transition: border-color 0.2s;
            outline: none;
            background: #fff;
        }
        .form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255,107,53,0.1); }
        .form-error { font-size: 12px; color: #EF4444; margin-top: 6px; }

        /* ===== SKELETON LOADING ===== */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.4s ease infinite;
            border-radius: 8px;
        }

        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .grid-4 { grid-template-columns: repeat(3, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; }
        }

        @media (max-width: 768px) {
            .navbar-inner { padding: 0 16px; gap: 12px; }
            .location-btn { display: none; }
            .search-wrap { max-width: none; flex: 1; }
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-3 { grid-template-columns: repeat(2, 1fr); }
            .grid-6 { grid-template-columns: repeat(4, 1fr); }
            .footer-grid { grid-template-columns: 1fr; gap: 24px; }
            .cart-sidebar { width: 100%; right: -100%; }
            .btn-login { display: none; }
        }

        @media (max-width: 480px) {
            .grid-4, .grid-3 { grid-template-columns: 1fr; }
            .grid-6 { grid-template-columns: repeat(3, 1fr); }
            .container { padding: 0 16px; }
            .section { padding: 32px 0; }
        }
    </style>

    @yield('styles')
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-inner">
        <!-- Brand -->
        <a href="{{ route('home') }}" class="navbar-brand">
            <div class="brand-logo">🍜</div>
            <div class="brand-name">Makan<span>Ku</span></div>
        </a>

        <!-- Location -->
        <button class="location-btn" id="locationBtn">
            <i class="fas fa-map-marker-alt"></i>
            <span id="locationText">Sragen, Jawa Tengah</span>
            <i class="fas fa-chevron-down" style="font-size:10px; margin-left:2px;"></i>
        </button>

        <!-- Search -->
        <div class="search-wrap">
            <form action="{{ route('search') }}" method="GET">
                <i class="fas fa-search search-icon"></i>
                <input
                    type="text"
                    name="q"
                    class="search-input"
                    placeholder="Cari makanan atau restoran..."
                    value="{{ request('q') }}"
                    autocomplete="off"
                >
                <button type="submit" class="search-submit">Cari</button>
            </form>
        </div>

        <!-- Right side -->
        <div class="navbar-right">
            <!-- Notifications -->
            @auth
            <a href="{{ route('account.notifications') }}" class="nav-icon-btn">
                <i class="far fa-bell"></i>
                @php $notifCount = auth()->user()->unreadNotifications->count() @endphp
                @if($notifCount > 0)
                <span class="badge">{{ $notifCount > 9 ? '9+' : $notifCount }}</span>
                @endif
            </a>
            @endauth

            <!-- Cart -->
            <button class="nav-icon-btn" id="cartBtn" onclick="toggleCart()">
                <i class="fas fa-shopping-bag"></i>
                @auth
                @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') @endphp
                @if($cartCount > 0)
                <span class="badge" id="cartBadge">{{ $cartCount }}</span>
                @endif
                @endauth
            </button>

            <!-- User Menu -->
            @auth
            <div class="user-menu-wrap">
                <button class="user-btn">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-avatar">
                    <span class="user-name">{{ Str::limit(auth()->user()->name, 10) }}</span>
                    <i class="fas fa-chevron-down" style="font-size:10px; color:var(--text-gray)"></i>
                </button>
                <div class="user-dropdown">
                    <div class="dropdown-header">
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>{{ auth()->user()->email }}</small>
                    </div>
                    <a href="{{ route('account.index') }}" class="dropdown-item"><i class="far fa-user"></i> Profil Saya</a>
                    <a href="{{ route('orders.index') }}" class="dropdown-item"><i class="fas fa-box"></i> Pesanan Saya</a>
                    <a href="{{ route('account.wallet') }}" class="dropdown-item">
                        <i class="fas fa-wallet"></i> Dompet
                        <span style="margin-left:auto;font-size:12px;color:var(--primary);font-weight:700;">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span>
                    </a>
                    <a href="{{ route('account.points') }}" class="dropdown-item"><i class="fas fa-star"></i> Poin: <strong style="color:var(--primary);margin-left:auto;">{{ auth()->user()->points }}</strong></a>
                    <a href="{{ route('account.favorites') }}" class="dropdown-item"><i class="far fa-heart"></i> Favorit</a>
                    <a href="{{ route('account.vouchers') }}" class="dropdown-item"><i class="fas fa-ticket-alt"></i> Voucher</a>
                    <div class="dropdown-divider"></div>
                    @if(auth()->user()->isRestaurantOwner())
                    <a href="{{ route('partner.dashboard') }}" class="dropdown-item"><i class="fas fa-store"></i> Kelola Toko</a>
                    @endif
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item"><i class="fas fa-cog"></i> Admin Panel</a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="color:#EF4444">
                            <i class="fas fa-sign-out-alt" style="color:#EF4444"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn-login">Masuk</a>
            <a href="{{ route('register') }}" class="btn-register">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

<!-- TOAST CONTAINER -->
<div class="toast-container" id="toastContainer"></div>

<!-- CART SIDEBAR -->
<div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-sidebar-header">
        <h3>🛒 Keranjang Kamu</h3>
        <button class="cart-close" onclick="toggleCart()"><i class="fas fa-times"></i></button>
    </div>
    <div class="cart-items-list" id="cartItemsList">
        <div style="text-align:center;padding:40px 20px;color:var(--text-gray)">
            <div style="font-size:48px;margin-bottom:12px;">🛒</div>
            <p style="font-weight:600;margin-bottom:6px;">Keranjang masih kosong</p>
            <p style="font-size:13px;">Yuk, pilih makanan favoritmu!</p>
        </div>
    </div>
    <div class="cart-footer" id="cartFooter" style="display:none">
        <div class="cart-total-row"><span>Subtotal</span><span id="cartSubtotal">Rp 0</span></div>
        <div class="cart-total-row"><span>Ongkos kirim</span><span id="cartDelivery">-</span></div>
        <div class="cart-total-row"><span>Biaya layanan</span><span id="cartService">-</span></div>
        <div class="cart-total-row total"><span>Total</span><span id="cartTotal">Rp 0</span></div>
        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-block" style="margin-top:16px">
            <i class="fas fa-lock"></i> Checkout Sekarang
        </a>
        <a href="{{ route('cart.index') }}" class="btn btn-outline btn-block" style="margin-top:8px">Lihat Keranjang Lengkap</a>
    </div>
</div>

<!-- FLASH MESSAGES -->
@if(session('success'))
<script>document.addEventListener('DOMContentLoaded', () => showToast('{{ session('success') }}', 'success'))</script>
@endif
@if(session('error'))
<script>document.addEventListener('DOMContentLoaded', () => showToast('{{ session('error') }}', 'error'))</script>
@endif

<!-- MAIN CONTENT -->
<main class="main-content">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="navbar-brand" style="display:inline-flex">
                    <div class="brand-logo">🍜</div>
                    <div class="brand-name" style="color:#fff">Makan<span>Ku</span></div>
                </a>
                <p>Platform pesan makanan online terpercaya. Nikmati kemudahan memesan makanan favoritmu dari ratusan restoran pilihan yang ada di kotamu.</p>
                <div class="footer-apps">
                    <a href="#" class="app-btn"><i class="fab fa-google-play"></i><div><small style="font-size:10px;opacity:.7;">Dapatkan di</small><span>Google Play</span></div></a>
                    <a href="#" class="app-btn"><i class="fab fa-apple"></i><div><small style="font-size:10px;opacity:.7;">Download di</small><span>App Store</span></div></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Perusahaan</h4>
                <ul class="footer-links">
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Karir</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Press</a></li>
                    <li><a href="#">Investor</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Bantuan</h4>
                <ul class="footer-links">
                    <li><a href="#">Pusat Bantuan</a></li>
                    <li><a href="#">Cara Memesan</a></li>
                    <li><a href="#">Kebijakan Pengembalian</a></li>
                    <li><a href="#">Hubungi Kami</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Mitra</h4>
                <ul class="footer-links">
                    <li><a href="#">Daftar Restoran</a></li>
                    <li><a href="#">Daftar Kurir</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Hubungi Sales</a></li>
                </ul>
                <div class="footer-social" style="margin-top:20px">
                    <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} MakanKu. Dibuat dengan ❤️ di Indonesia</span>
            <div style="display:flex;gap:20px">
                <a href="#" style="color:rgba(255,255,255,0.4);text-decoration:none;font-size:12px">Kebijakan Privasi</a>
                <a href="#" style="color:rgba(255,255,255,0.4);text-decoration:none;font-size:12px">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>

<script>
// ===== CART SIDEBAR =====
function toggleCart() {
    document.getElementById('cartOverlay').classList.toggle('open');
    document.getElementById('cartSidebar').classList.toggle('open');
    document.body.style.overflow = document.getElementById('cartSidebar').classList.contains('open') ? 'hidden' : '';
}

// ===== TOAST NOTIFICATIONS =====
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const icons = { success: 'fa-check-circle', error: 'fa-times-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
    const colors = { success: '#06D6A0', error: '#EF4444', warning: '#F59E0B', info: '#3B82F6' };

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="fas ${icons[type] || icons.info}" style="color:${colors[type]};font-size:20px;flex-shrink:0"></i>
        <span style="font-size:14px;flex:1">${message}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#9CA3AF;font-size:16px;padding:0 0 0 8px">×</button>
    `;
    container.appendChild(toast);
    setTimeout(() => toast.style.opacity = '0', 3500);
    setTimeout(() => toast.remove(), 3800);
}

// ===== ADD TO CART =====
function addToCart(menuId, quantity = 1, options = {}, notes = '') {
    fetch('{{ route('cart.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        },
        body: JSON.stringify({ menu_id: menuId, quantity, selected_options: options, notes })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            const badge = document.getElementById('cartBadge');
            if (badge) badge.textContent = data.cart_count;
            else {
                const btn = document.getElementById('cartBtn');
                btn.insertAdjacentHTML('beforeend', `<span class="badge" id="cartBadge">${data.cart_count}</span>`);
            }
        } else {
            if (data.conflict) {
                if (confirm(data.message + '\n\nKosongkan keranjang dan tambahkan item ini?')) {
                    clearCartThenAdd(menuId, quantity, options, notes);
                }
            } else {
                showToast(data.message, 'error');
            }
        }
    });
}

function clearCartThenAdd(menuId, quantity, options, notes) {
    fetch('{{ route('cart.clear') }}', {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    }).then(() => addToCart(menuId, quantity, options, notes));
}

// ===== FAVORITE TOGGLE =====
function toggleFavorite(restaurantId, btn) {
    @auth
    fetch(`/account/favorites/${restaurantId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        }
    })
    .then(r => r.json())
    .then(data => {
        btn.classList.toggle('active', data.favorited);
        showToast(data.favorited ? 'Ditambahkan ke favorit' : 'Dihapus dari favorit', 'success');
    });
    @else
    window.location.href = '{{ route('login') }}';
    @endauth
}

// ===== LOCATION =====
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(pos => {
        // Here you'd reverse geocode via Google Maps API
    });
}

// ===== SEARCH SUGGESTIONS =====
const searchInput = document.querySelector('.search-input');
if (searchInput) {
    let timeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            // Implement search suggestions via AJAX
        }, 300);
    });
}
</script>

@yield('scripts')
</body>
</html>