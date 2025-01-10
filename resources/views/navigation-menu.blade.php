<!-- Produits -->
<li class="nav-item">
    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
        <i class="fas fa-box"></i>
        <span>{{ __('Produits') }}</span>
    </a>
</li>

<!-- Fournisseurs -->
<li class="nav-item">
    <a href="{{ route('fournisseurs.index') }}" class="nav-link {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}">
        <i class="fas fa-truck"></i>
        <span>{{ __('Fournisseurs') }}</span>
    </a>
</li>

<!-- Ventes -->
<li class="nav-item">
    <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i>
        <span>{{ __('Ventes') }}</span>
    </a>
</li> 