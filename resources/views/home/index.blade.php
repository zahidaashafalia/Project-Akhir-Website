@extends('app')

@section('title', 'MakanKu - Pesan Makanan Favoritmu')

@section('styles')
<style>
/* ===== HERO ===== */
.hero {
    background: var(--secondary);
    position: relative;
    overflow: hidden;
    padding: 60px 0;
}

.hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 20% 80%, rgba(255,107,53,0.25) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255,214,10,0.1) 0%, transparent 50%);
}

.hero-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    position: relative;
    z-index: 1;
}

.hero-title {
    font-family: var(--font-heading);
    font-size: 52px;
    font-weight: 800;
    color: #fff;
    line-height: 1.15;
    margin-bottom: 20px;
}

.hero-title span {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-subtitle {
    font-size: 17px;
    color: rgba(255,255,255,0.65);
    line-height: 1.7;
    margin-bottom: 32px;
}

.hero-search-wrap {
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: var(--radius-xl);
    padding: 6px 6px 6px 20px;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 28px;
}

.hero-search-input {
    flex: 1;
    background: none;
    border: none;
    outline: none;
    font-family: var(--font-body);
    font-size: 15px;
    color: #fff;
    padding: 8px 0;
}

.hero-search-input::placeholder { color: rgba(255,255,255,0.4); }

.hero-search-btn {
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: var(--radius-lg);
    padding: 14px 24px;
    font-size: 14px;
    font-weight: 700;
    font-family: var(--font-body);
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 8px;
}

.hero-search-btn:hover { background: var(--primary-dark); transform: scale(1.02); }

.hero-stats {
    display: flex;
    gap: 32px;
    margin-top: 4px;
}

.hero-stat { display: flex; align-items: center; gap: 8px; }

.hero-stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
}

.hero-stat-value {
    font-family: var(--font-heading);
    font-size: 18px;
    font-weight: 800;
    color: #fff;
}

.hero-stat-label { font-size: 11px; color: rgba(255,255,255,0.5); }

/* Hero image side */
.hero-visual {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-img-main {
    width: 340px;
    height: 340px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,0.1);
    box-shadow: 0 0 80px rgba(255,107,53,0.3);
}

.hero-float-card {
    position: absolute;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: var(--radius-lg);
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}

.hero-float-1 { top: 20px; right: -10px; animation-delay: 0s; }
.hero-float-2 { bottom: 40px; left: -20px; animation-delay: 1.5s; }

.hero-float-icon { font-size: 28px; }
.hero-float-text strong { display: block; font-size: 14px; font-weight: 700; color: #fff; }
.hero-float-text small { font-size: 11px; color: rgba(255,255,255,0.6); }

/* ===== PROMO BANNER SLIDER ===== */
.promo-slider {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    padding: 4px;
    scroll-snap-type: x mandatory;
}

.promo-slide {
    flex-shrink: 0;
    width: 420px;
    height: 160px;
    border-radius: var(--radius-xl);
    background: linear-gradient(135deg, var(--primary), #FF9A3C);
    position: relative;
    overflow: hidden;
    cursor: pointer;
    scroll-snap-align: start;
    transition: transform 0.2s;
}

.promo-slide:hover { transform: scale(1.02); }

.promo-slide:nth-child(2) { background: linear-gradient(135deg, #06D6A0, #0AB47B); }
.promo-slide:nth-child(3) { background: linear-gradient(135deg, #4F46E5, #7C3AED); }
.promo-slide:nth-child(4) { background: linear-gradient(135deg, #F59E0B, #D97706); }

.promo-slide-content {
    padding: 24px;
    position: relative;
    z-index: 1;
}

.promo-slide-tag {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: var(--radius-full);
    margin-bottom: 8px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.promo-slide h3 { font-family: var(--font-heading); font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 6px; }
.promo-slide p { font-size: 13px; color: rgba(255,255,255,0.8); margin-bottom: 12px; }

.promo-slide-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
    padding: 7px 16px;
    border-radius: var(--radius-full);
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: background 0.2s;
}

.promo-slide-btn:hover { background: rgba(255,255,255,0.3); }

.promo-slide-deco {
    position: absolute;
    right: -20px;
    bottom: -20px;
    font-size: 100px;
    opacity: 0.15;
    transform: rotate(-15deg);
}

/* ===== CATEGORIES ===== */
.categories-scroll {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding: 4px 0 12px;
    scrollbar-width: none;
}

.categories-scroll::-webkit-scrollbar { display: none; }

.category-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    text-decoration: none;
    color: var(--text-dark);
    flex-shrink: 0;
    transition: all 0.2s;
}

.category-item:hover { transform: translateY(-3px); }

.category-item-icon {
    width: 64px;
    height: 64px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    transition: all 0.2s;
    border: 2px solid var(--border);
    background: #fff;
}

.category-item:hover .category-item-icon {
    border-color: var(--primary);
    background: var(--primary-pale);
    box-shadow: 0 4px 16px rgba(255,107,53,0.2);
}

.category-item-name {
    font-size: 12px;
    font-weight: 700;
    text-align: center;
    max-width: 72px;
}

/* ===== FLASH SALE ===== */
.flash-sale-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.flash-sale-title-wrap { display: flex; align-items: center; gap: 16px; }

.flash-sale-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--primary);
    color: #fff;
    padding: 6px 16px;
    border-radius: var(--radius-full);
    font-weight: 800;
    font-family: var(--font-heading);
    font-size: 14px;
}

.countdown {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text-gray);
}

.countdown-unit {
    background: var(--secondary);
    color: #fff;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 14px;
    font-variant-numeric: tabular-nums;
    min-width: 32px;
    text-align: center;
}

/* ===== VOUCHER SECTION ===== */
.voucher-item {
    background: #fff;
    border-radius: var(--radius-lg);
    padding: 20px;
    border: 1.5px solid var(--border);
    display: flex;
    gap: 16px;
    align-items: center;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    overflow: hidden;
}

.voucher-item:hover { border-color: var(--primary); box-shadow: var(--shadow-md); }

.voucher-item::before {
    content: '';
    position: absolute;
    left: 80px;
    top: 0;
    width: 1px;
    height: 100%;
    background: repeating-linear-gradient(to bottom, transparent 0, transparent 6px, var(--border) 6px, var(--border) 12px);
}

.voucher-icon-wrap {
    width: 60px;
    height: 60px;
    border-radius: var(--radius-md);
    background: var(--primary-pale);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    flex-shrink: 0;
}

.voucher-body { flex: 1; }

.voucher-discount {
    font-family: var(--font-heading);
    font-size: 20px;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 2px;
}

.voucher-name { font-weight: 700; font-size: 14px; margin-bottom: 4px; }

.voucher-info { font-size: 12px; color: var(--text-gray); }

.voucher-code {
    background: var(--primary-pale);
    color: var(--primary);
    border: 1.5px dashed var(--primary);
    border-radius: var(--radius-md);
    padding: 8px 16px;
    font-weight: 800;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}

.voucher-code:hover { background: var(--primary); color: #fff; }

/* ===== CTA BANNER ===== */
.cta-banner {
    background: linear-gradient(135deg, var(--secondary) 0%, #2d2d44 100%);
    border-radius: var(--radius-xl);
    padding: 48px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 32px;
    position: relative;
    overflow: hidden;
}

.cta-banner::before {
    content: '🍕🍜🍔🍣';
    position: absolute;
    right: 200px;
    font-size: 80px;
    opacity: 0.08;
    letter-spacing: 20px;
}

.cta-content h2 { font-family: var(--font-heading); font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 10px; }
.cta-content p { color: rgba(255,255,255,0.6); font-size: 15px; }
.cta-actions { display: flex; gap: 12px; flex-shrink: 0; }

/* ===== HOW IT WORKS ===== */
.how-steps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.how-step {
    text-align: center;
    padding: 32px 20px;
    border-radius: var(--radius-xl);
    background: #fff;
    border: 1px solid var(--border);
    position: relative;
    transition: all 0.25s;
}

.how-step:hover { box-shadow: var(--shadow-md); transform: translateY(-4px); }

.how-step-num {
    position: absolute;
    top: -14px;
    left: 50%;
    transform: translateX(-50%);
    width: 28px;
    height: 28px;
    background: var(--primary);
    color: #fff;
    border-radius: 50%;
    font-size: 12px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #fff;
}

.how-step-icon {
    font-size: 48px;
    margin-bottom: 16px;
    display: block;
}

.how-step h4 { font-family: var(--font-heading); font-size: 15px; font-weight: 700; margin-bottom: 8px; }
.how-step p { font-size: 13px; color: var(--text-gray); line-height: 1.6; }

/* ===== TESTIMONIALS ===== */
.testimonial-card {
    background: #fff;
    border-radius: var(--radius-xl);
    padding: 24px;
    border: 1px solid var(--border);
    transition: all 0.25s;
}

.testimonial-card:hover { box-shadow: var(--shadow-md); transform: translateY(-3px); }

.testimonial-stars { color: #F59E0B; font-size: 14px; margin-bottom: 12px; }

.testimonial-text { font-size: 14px; color: var(--text-gray); line-height: 1.7; margin-bottom: 16px; font-style: italic; }

.testimonial-author { display: flex; align-items: center; gap: 10px; }

.testimonial-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    background: var(--primary-pale);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.testimonial-name { font-weight: 700; font-size: 14px; }
.testimonial-meta { font-size: 12px; color: var(--text-gray); }

@media (max-width: 768px) {
    .hero-inner { grid-template-columns: 1fr; gap: 32px; text-align: center; }
    .hero-title { font-size: 36px; }
    .hero-visual { display: none; }
    .hero-stats { justify-content: center; }
    .how-steps { grid-template-columns: repeat(2, 1fr); }
    .cta-banner { flex-direction: column; text-align: center; }
    .cta-banner::before { display: none; }
    .cta-actions { width: 100%; justify-content: center; }
    .promo-slide { width: 300px; }
}
</style>
@endsection

@section('content')

<!-- HERO -->
<section class="hero">
    <div class="hero-inner">
        <div class="hero-content">
            <h1 class="hero-title">
                Pesan Makanan<br>
                <span>Favoritmu</span><br>
                Kapan Aja!
            </h1>
            <p class="hero-subtitle">Ribuan menu lezat dari ratusan restoran terpilih siap diantarkan langsung ke pintumu. Cepat, mudah, dan selalu memuaskan.</p>

            <!-- Hero Search -->
            <div class="hero-search-wrap">
                <i class="fas fa-search" style="color:rgba(255,255,255,0.4);font-size:16px"></i>
                <input type="text" class="hero-search-input" placeholder="Mau makan apa hari ini?" id="heroSearch">
                <button class="hero-search-btn" onclick="doHeroSearch()">
                    <i class="fas fa-search"></i> Cari Sekarang
                </button>
            </div>

            <!-- Stats -->
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-icon">🏪</div>
                    <div>
                        <div class="hero-stat-value">500+</div>
                        <div class="hero-stat-label">Restoran</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-icon">🛵</div>
                    <div>
                        <div class="hero-stat-value">30 mnt</div>
                        <div class="hero-stat-label">Rata-rata antar</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-icon">⭐</div>
                    <div>
                        <div class="hero-stat-value">4.9/5</div>
                        <div class="hero-stat-label">Rating Layanan</div>
                </div>
        </div>

        <div class="hero-visual">
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=400&fit=crop" alt="Makanan Lezat" class="hero-img-main">
            <div class="hero-float-card hero-float-1">
                <span class="hero-float-icon">🛵</span>
                <div class="hero-float-text">
                    <strong>Pesanan Otw!</strong>
                    <small>Est. 15 menit lagi</small>
                </div>
            <div class="hero-float-card hero-float-2">
                <span class="hero-float-icon">⭐</span>
                <div class="hero-float-text">
                    <strong>Ayam Geprek Juara</strong>
                    <small>4.9 • 2.3rb ulasan</small>
                </div>
        </div>
</section>

<!-- PROMO BANNERS -->
<section class="section-sm" style="padding-top:32px">
    <div class="container">
        <div class="promo-slider" id="promoSlider">
            @foreach($activeVouchers as $voucher)
            <div class="promo-slide" onclick="window.location.href='{{ route('promo') }}'">
                <div class="promo-slide-content">
                    <span class="promo-slide-tag">🎉 Promo Terbatas</span>
                    <h3>{{ $voucher->title }}</h3>
                    <p>{{ $voucher->description ?? 'Min. order Rp ' . number_format($voucher->min_order, 0, ',', '.') }}</p>
                    <a href="{{ route('promo') }}" class="promo-slide-btn">Ambil Voucher <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="promo-slide-deco">🎁</div>
            @endforeach

            <!-- Default promo slides -->
            <div class="promo-slide" style="background:linear-gradient(135deg,#4F46E5,#7C3AED)">
                <div class="promo-slide-content">
                    <span class="promo-slide-tag">🆕 Member Baru</span>
                    <h3>Gratis Ongkir 5x</h3>
                    <p>Khusus pengguna baru MakanKu</p>
                    <a href="{{ route('register') }}" class="promo-slide-btn">Daftar Sekarang <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="promo-slide-deco">🎊</div>
            <div class="promo-slide" style="background:linear-gradient(135deg,#06D6A0,#0AB47B)">
                <div class="promo-slide-content">
                    <span class="promo-slide-tag">⚡ Flash Sale</span>
                    <h3>Diskon s.d 70%</h3>
                    <p>Setiap hari 12.00–13.00 & 18.00–19.00</p>
                    <a href="{{ route('promo') }}" class="promo-slide-btn">Lihat Flash Sale <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="promo-slide-deco">⚡</div>
            <div class="promo-slide" style="background:linear-gradient(135deg,#F59E0B,#D97706)">
                <div class="promo-slide-content">
                    <span class="promo-slide-tag">💰 Cashback</span>
                    <h3>Cashback 20%</h3>
                    <p>Bayar pakai MakanKu Pay</p>
                    <a href="{{ route('account.wallet') }}" class="promo-slide-btn">Top Up Sekarang <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="promo-slide-deco">💎</div>
        </div>
</section>

<!-- CATEGORIES -->
<section class="section-sm">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">🍽️ Kategori Makanan</h2>
                <p class="section-subtitle">Pilih sesuai selera kamu</p>
            </div>
        <div class="categories-scroll">
            <a href="{{ route('search') }}" class="category-item">
                <div class="category-item-icon" style="border-color:var(--primary);background:var(--primary-pale)">🍽️</div>
                <span class="category-item-name">Semua</span>
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('category', $cat->slug) }}" class="category-item">
                <div class="category-item-icon">{{ $cat->icon ?? '🍴' }}</div>
                <span class="category-item-name">{{ $cat->name }}</span>
            </a>
            @endforeach
            <!-- Default categories if empty -->
            @if($categories->isEmpty())
            @foreach([['🍚','Nasi'],['🍜','Mie'],['🍔','Burger'],['🍕','Pizza'],['🐔','Ayam'],['🐟','Seafood'],['🌮','Jajanan'],['🧁','Dessert'],['🥤','Minuman'],['🥗','Salad'],['🍣','Sushi'],['🌶️','Pedas']] as $c)
            <div class="category-item">
                <div class="category-item-icon">{{ $c[0] }}</div>
                <span class="category-item-name">{{ $c[1] }}</span>
            </div>
            @endforeach
            @endif
        </div>
</section>

<!-- FLASH SALE -->
@if($flashSaleMenus->isNotEmpty())
<section class="section-sm" style="background:#fff;padding:40px 0">
    <div class="container">
        <div class="flash-sale-header">
            <div class="flash-sale-title-wrap">
                <div class="flash-sale-badge">⚡ Flash Sale</div>
                <div class="countdown">
                    Berakhir dalam:
                    <span class="countdown-unit" id="ch">02</span>:
                    <span class="countdown-unit" id="cm">45</span>:
                    <span class="countdown-unit" id="cs">30</span>
                </div>
            <a href="{{ route('promo') }}" class="section-link">Lihat semua <i class="fas fa-chevron-right"></i></a>
        </div>

        <div class="scroll-row">
            @foreach($flashSaleMenus as $menu)
            <div class="menu-card" style="width:200px">
                <div class="menu-card-img-wrap">
                    <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" loading="lazy">
                    @if($menu->hasDiscount())
                    <div class="menu-discount-badge">-{{ $menu->discount_percentage }}%</div>
                    @endif
                </div>
                <div class="menu-card-body">
                    <div class="menu-name">{{ $menu->name }}</div>
                    <div class="menu-restaurant">{{ $menu->restaurant->name }}</div>
                    <div class="menu-price-wrap">
                        <span class="menu-price">{{ $menu->effective_price_formatted }}</span>
                        @if($menu->hasDiscount())
                        <span class="menu-price-original">{{ $menu->price_formatted }}</span>
                        @endif
                    </div>
                    <button class="menu-add-btn" onclick="addToCart({{ $menu->id }})">
                        + Keranjang
                    </button>
                </div>
            @endforeach
        </div>
</section>
@endif

<!-- FEATURED RESTAURANTS -->
@if($featuredRestaurants->isNotEmpty())
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">⭐ Restoran Pilihan</h2>
                <p class="section-subtitle">Restoran terbaik yang wajib kamu coba</p>
            </div>
            <a href="{{ route('search') }}" class="section-link">Lihat semua <i class="fas fa-chevron-right"></i></a>
        </div>

        <div class="grid-4">
            @foreach($featuredRestaurants as $restaurant)
            <a href="{{ route('restaurant.show', $restaurant->slug) }}" class="restaurant-card">
                <div class="restaurant-card-img-wrap">
                    <img src="{{ $restaurant->banner_url }}" alt="{{ $restaurant->name }}" loading="lazy">
                    @if($restaurant->is_featured)
                    <span class="restaurant-card-badge badge-popular">⭐ Pilihan</span>
                    @endif
                    <button class="restaurant-fav-btn {{ auth()->check() && auth()->user()->favorites->contains($restaurant->id) ? 'active' : '' }}"
                            onclick="event.preventDefault();toggleFavorite({{ $restaurant->id }}, this)">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <div class="restaurant-card-body">
                    <div class="restaurant-card-top">
                        <div class="restaurant-logo-wrap">
                            <img src="{{ $restaurant->logo_url }}" alt="{{ $restaurant->name }}" width="44" height="44" style="object-fit:cover">
                        </div>
                        <div class="restaurant-card-info">
                            <div class="restaurant-name">{{ $restaurant->name }}</div>
                            <div class="restaurant-category">{{ $restaurant->categories->pluck('name')->join(', ') }}</div>
                    </div>
                    <div class="restaurant-meta">
                        <div class="meta-item"><i class="fas fa-star rating-star"></i> <strong>{{ number_format($restaurant->rating, 1) }}</strong> ({{ $restaurant->total_reviews }})</div>
                        <div class="meta-item"><i class="far fa-clock" style="color:var(--text-light)"></i> {{ $restaurant->estimated_delivery_time }} mnt</div>
                        <div class="meta-item {{ $restaurant->delivery_fee == 0 ? 'free-delivery' : '' }}">
                            {{ $restaurant->delivery_fee_formatted }}
                        </div>
                </div>
            </a>
            @endforeach
        </div>
</section>
@endif

<!-- FREE DELIVERY -->
@if($freeDeliveryRestaurants->isNotEmpty())
<section class="section-sm" style="background: linear-gradient(135deg, #F0FFF4, #ECFDF5); padding:40px 0">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">🛵 Gratis Ongkir</h2>
                <p class="section-subtitle">Hemat lebih banyak, nikmati lebih banyak!</p>
            </div>
            <a href="{{ route('search', ['max_delivery_fee' => 0]) }}" class="section-link">Lihat semua <i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="scroll-row">
            @foreach($freeDeliveryRestaurants as $restaurant)
            <a href="{{ route('restaurant.show', $restaurant->slug) }}" class="restaurant-card" style="width:260px">
                <div class="restaurant-card-img-wrap" style="height:140px">
                    <img src="{{ $restaurant->banner_url }}" alt="{{ $restaurant->name }}" loading="lazy">
                    <span class="restaurant-card-badge badge-new">🛵 Gratis Ongkir</span>
                </div>
                <div class="restaurant-card-body" style="padding:12px">
                    <div class="restaurant-name" style="font-size:14px">{{ $restaurant->name }}</div>
                    <div class="restaurant-meta" style="margin-top:6px">
                        <div class="meta-item"><i class="fas fa-star rating-star"></i> {{ number_format($restaurant->rating, 1) }}</div>
                        <div class="meta-item"><i class="far fa-clock" style="color:var(--text-light)"></i> {{ $restaurant->estimated_delivery_time }} mnt</div>
                </div>
            </a>
            @endforeach
        </div>
</section>
@endif

<!-- POPULAR RESTAURANTS -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">🔥 Paling Populer</h2>
                <p class="section-subtitle">Favorit semua orang, jangan sampai ketinggalan!</p>
            </div>
            <a href="{{ route('search', ['sort' => 'popular']) }}" class="section-link">Lihat semua <i class="fas fa-chevron-right"></i></a>
        </div>

        <div class="grid-3">
            @foreach($popularRestaurants->take(6) as $i => $restaurant)
            <a href="{{ route('restaurant.show', $restaurant->slug) }}" class="restaurant-card" style="display:flex;flex-direction:row;gap:0">
                <div style="width:110px;flex-shrink:0;overflow:hidden">
                    <img src="{{ $restaurant->banner_url }}" alt="{{ $restaurant->name }}" style="width:110px;height:100%;object-fit:cover;transition:transform 0.4s">
                </div>
                <div style="flex:1;padding:14px;display:flex;flex-direction:column;justify-content:space-between">
                    <div>
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px">
                            <span style="background:var(--primary);color:#fff;width:20px;height:20px;border-radius:50%;font-size:10px;font-weight:800;display:flex;align-items:center;justify-content:center">#{{ $i+1 }}</span>
                            <div class="restaurant-name" style="font-size:14px">{{ $restaurant->name }}</div>
                        <div style="font-size:12px;color:var(--text-gray)">{{ $restaurant->categories->pluck('name')->join(', ') }}</div>
                    <div>
                        <div class="restaurant-meta">
                            <div class="meta-item"><i class="fas fa-star rating-star"></i> <strong>{{ number_format($restaurant->rating, 1) }}</strong></div>
                            <div class="meta-item"><i class="far fa-clock" style="color:var(--text-light)"></i> {{ $restaurant->estimated_delivery_time }} mnt</div>
                        <div style="font-size:12px;margin-top:4px;{{ $restaurant->delivery_fee == 0 ? 'color:var(--accent2);font-weight:700' : 'color:var(--text-gray)' }}">
                            {{ $restaurant->delivery_fee_formatted }}
                        </div>
                </div>
