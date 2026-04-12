@extends('layouts.kantin-public')

@section('title', 'Kantin Online — Pesan Sekarang')

@push('style-page')
<style>
    /* Step Indicator untuk tema terang */
    .step-indicator {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 30px;
    }
    .step-dot {
        width: 40px; 
        height: 40px;
        border-radius: 50%;
        display: flex; 
        align-items: center; 
        justify-content: center;
        font-weight: 700; 
        font-size: 0.9rem;
        background: #e9ecef;
        color: #6c757d;
        border: 2px solid #dee2e6;
        transition: all 0.3s ease;
    }
    .step-dot.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .step-dot.done {
        background: #4caf50;
        color: #fff;
        border-color: #4caf50;
    }
    
    /* Card styling khusus kantin */
    .vendor-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .vendor-card:hover, .vendor-card.active {
        border-color: #667eea;
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15) !important;
    }
    .vendor-card.active {
        background: linear-gradient(135deg, rgba(102,126,234,0.05), rgba(118,75,162,0.05));
    }
    
    .menu-card {
        cursor: pointer;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .menu-card:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1) !important;
    }
    .menu-card .menu-img {
        height: 140px;
        object-fit: cover;
        width: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
    .menu-card .menu-img-placeholder {
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea, #764ba2);
        font-size: 3rem;
        color: rgba(255,255,255,0.8);
    }
    .badge-qty {
        width: 28px;
        height: 28px;
        line-height: 28px;
        text-align: center;
        border-radius: 50%;
        font-size: 0.8rem;
    }
    
    /* Fade animation */
    .fade-in { 
        animation: fadeIn 0.4s ease-out; 
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
    <div class="page-header mb-4">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-food-variant"></i>
            </span> 
            Kantin Online
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Pesan Menu</li>
            </ul>
        </nav>
    </div>

    {{-- Step Indicator --}}
    <div class="step-indicator">
        <div class="step-dot active" id="dot-1">1</div>
        <div class="step-dot" id="dot-2">2</div>
        <div class="step-dot" id="dot-3">3</div>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{-- STEP 1: Pilih Vendor                            --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div id="step-1" class="fade-in">
        <div class="text-center mb-4">
            <h4 class="font-weight-bold text-dark">Pilih Vendor Kantin</h4>
            <p class="text-muted">Pilih salah satu vendor untuk melihat menu</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($vendors as $vendor)
            <div class="col-md-4 col-sm-6">
                <div class="card vendor-card shadow-sm border-0 p-4 text-center"
                     onclick="selectVendor({{ $vendor->idvendor }}, '{{ addslashes($vendor->nama_vendor) }}', this)">
                    <div class="mb-3">
                        <div style="width:70px;height:70px;border-radius:50%;margin:0 auto;
                                    background:linear-gradient(135deg,#667eea,#764ba2);
                                    display:flex;align-items:center;justify-content:center;">
                            <i class="mdi mdi-store text-white" style="font-size:2rem;"></i>
                        </div>
                    </div>
                    <h5 class="font-weight-bold mb-1 text-dark">{{ $vendor->nama_vendor }}</h5>
                    <p class="text-muted small mb-0">
                        <i class="mdi mdi-food-variant text-primary"></i> {{ $vendor->menus_count }} menu tersedia
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{-- STEP 2: Pilih Menu                              --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div id="step-2" class="fade-in" style="display:none;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="font-weight-bold text-dark mb-0">
                    Menu <span id="vendor-name-display" class="text-primary"></span>
                </h4>
                <p class="text-muted mb-0">Klik menu untuk menambahkan ke keranjang</p>
            </div>
            <button class="btn btn-outline-secondary btn-sm" onclick="goToStep(1)">
                <i class="mdi mdi-arrow-left"></i> Ganti Vendor
            </button>
        </div>

        {{-- Select Berjenjang --}}
        <div class="card shadow-sm border-0 p-3 mb-4 bg-light">
            <div class="row align-items-center">
                <div class="col-md-6 mb-2 mb-md-0">
                    <label class="form-label font-weight-bold">Vendor</label>
                    <select id="select-vendor" class="form-select" onchange="onVendorSelectChange(this.value)">
                        <option value="">— Pilih Vendor —</option>
                        @foreach($vendors as $v)
                        <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label font-weight-bold">Menu</label>
                    <select id="select-menu" class="form-select" onchange="onMenuSelectChange(this.value)" disabled>
                        <option value="">— Pilih Menu —</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="menu-grid" class="row g-3">
            {{-- Menu cards akan di-render via JS --}}
        </div>

        <div class="text-center mt-4" id="btn-next-cart-wrap" style="display:none;">
            <button class="btn btn-primary btn-lg px-5" onclick="goToStep(3)">
                <i class="mdi mdi-cart-arrow-right"></i> Lihat Keranjang
                (<span id="cart-count-inline">0</span>)
            </button>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{-- STEP 3: Keranjang & Checkout                    --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div id="step-3" class="fade-in" style="display:none;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="font-weight-bold text-dark mb-0">
                <i class="mdi mdi-cart-check text-primary"></i> Keranjang Belanja
            </h4>
            <button class="btn btn-outline-secondary btn-sm" onclick="goToStep(2)">
                <i class="mdi mdi-arrow-left"></i> Tambah Lagi
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th>#</th>
                                <th class="text-start">Menu</th>
                                <th>Harga</th>
                                <th style="width:120px">Jumlah</th>
                                <th>Subtotal</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-cart">
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="mdi mdi-cart-outline" style="font-size:2rem;"></i><br>
                                    Keranjang masih kosong
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border-top pt-3 mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-dark">
                            Total: <span id="total-display" class="font-weight-bold text-primary">Rp 0</span>
                        </h4>
                        <button type="button" id="btn-checkout" class="btn btn-success btn-lg" disabled
                                onclick="prosesCheckout()">
                            <i class="mdi mdi-credit-card-check"></i> Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js-page')
{{-- Axios & SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Midtrans Snap.js (Sandbox) --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
// ═══════════════════════════════════════════════════
// STATE
// ═══════════════════════════════════════════════════
var cart = [];
var selectedVendorId = null;
var selectedVendorName = '';
var allMenus = [];   // menus loaded for current vendor

function formatRp(n) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(n);
}

// ═══════════════════════════════════════════════════
// STEP NAVIGATION
// ═══════════════════════════════════════════════════
function goToStep(n) {
    document.getElementById('step-1').style.display = (n === 1) ? '' : 'none';
    document.getElementById('step-2').style.display = (n === 2) ? '' : 'none';
    document.getElementById('step-3').style.display = (n === 3) ? '' : 'none';

    for (var i = 1; i <= 3; i++) {
        var dot = document.getElementById('dot-' + i);
        if (dot) {
            dot.classList.remove('active', 'done');
            if (i < n) dot.classList.add('done');
            if (i === n) dot.classList.add('active');
        }
    }

    if (n === 3) renderCart();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ═══════════════════════════════════════════════════
// STEP 1: Select Vendor (via card click)
// ═══════════════════════════════════════════════════
function selectVendor(id, name, el) {
    console.log('selectVendor called:', id, name);
    
    // Remove active from all
    document.querySelectorAll('.vendor-card').forEach(function(c) {
        c.classList.remove('active');
    });
    el.classList.add('active');

    selectedVendorId = id;
    selectedVendorName = name;

    // Sync dropdown
    var selectVendorEl = document.getElementById('select-vendor');
    if (selectVendorEl) {
        selectVendorEl.value = id;
    }

    loadMenus(id, function() {
        var vendorDisplay = document.getElementById('vendor-name-display');
        if (vendorDisplay) {
            vendorDisplay.textContent = name;
        }
        console.log('Menus loaded, going to step 2');
        goToStep(2);
    });
}

// ═══════════════════════════════════════════════════
// STEP 2: Select Berjenjang
// ═══════════════════════════════════════════════════
function onVendorSelectChange(val) {
    if (!val) return;
    selectedVendorId = val;
    var opt = document.querySelector('#select-vendor option[value="' + val + '"]');
    selectedVendorName = opt ? opt.textContent.trim() : '';
    document.getElementById('vendor-name-display').textContent = selectedVendorName;
    loadMenus(val);
}

function onMenuSelectChange(val) {
    if (!val) return;
    var menu = allMenus.find(function(m) { return m.idmenu == val; });
    if (menu) addToCart(menu);
    document.getElementById('select-menu').value = '';
}

// ═══════════════════════════════════════════════════
// Load menus via Axios (select berjenjang)
// ═══════════════════════════════════════════════════
function loadMenus(vendorId, callback) {
    console.log('Loading menus for vendor:', vendorId);
    
    var grid = document.getElementById('menu-grid');
    if (!grid) {
        console.error('menu-grid element not found!');
        return;
    }
    
    grid.innerHTML = '<div class="col-12 text-center py-4"><span class="spinner-border text-primary"></span></div>';

    // Reset menu select
    var selMenu = document.getElementById('select-menu');
    if (selMenu) {
        selMenu.innerHTML = '<option value="">— Pilih Menu —</option>';
        selMenu.disabled = true;
    }

    axios.get('/api/kantin/menu/' + vendorId)
        .then(function(response) {
            console.log('Menu loaded:', response.data);
            allMenus = response.data.data || [];
            
            console.log('Menus count:', allMenus.length);

            // Populate select-menu (select berjenjang)
            if (selMenu) {
                allMenus.forEach(function(m) {
                    var opt = document.createElement('option');
                    opt.value = m.idmenu;
                    opt.textContent = m.nama_menu + ' — ' + formatRp(m.harga);
                    selMenu.appendChild(opt);
                });
                selMenu.disabled = false;
            }

            // Render menu cards
            renderMenuGrid(allMenus);
            if (callback) callback();
        })
        .catch(function(error) {
            console.error('Failed to load menus:', error);
            if (grid) {
                grid.innerHTML = '<div class="col-12 text-center text-muted py-4">Gagal memuat menu. Silahkan coba lagi.</div>';
            }
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Gagal memuat menu: ' + (error.message || 'Unknown error'),
                showConfirmButton: false,
                timer: 3000,
            });
        });
}

function renderMenuGrid(menus) {
    var grid = document.getElementById('menu-grid');
    if (!grid) return;
    
    grid.innerHTML = '';

    if (!menus || menus.length === 0) {
        grid.innerHTML = '<div class="col-12 text-center text-muted py-4">Vendor ini belum punya menu.</div>';
        return;
    }

    menus.forEach(function(m) {
        var imgHtml = m.path_gambar
            ? '<img src="' + m.path_gambar + '" class="menu-img" alt="' + m.nama_menu + '">'
            : '<div class="menu-img-placeholder"><i class="mdi mdi-food"></i></div>';

        // Check if already in cart
        var inCart = cart.find(function(c) { return c.idmenu == m.idmenu; });
        var badgeHtml = inCart
            ? '<span class="badge bg-success badge-qty position-absolute" style="top:10px;right:10px;">' + inCart.jumlah + '</span>'
            : '';

        var col = document.createElement('div');
        col.className = 'col-md-3 col-6';
        col.innerHTML =
            '<div class="card menu-card shadow-sm border-0 position-relative" onclick="addToCart(' + JSON.stringify(m).replace(/"/g, '&quot;') + ')">' +
                badgeHtml +
                imgHtml +
                '<div class="p-3">' +
                    '<h6 class="font-weight-bold mb-1 text-dark" style="font-size:0.9rem;">' + m.nama_menu + '</h6>' +
                    '<span class="font-weight-bold text-primary">' + formatRp(m.harga) + '</span>' +
                '</div>' +
            '</div>';
        grid.appendChild(col);
    });
}

// ═══════════════════════════════════════════════════
// Add to Cart
// ═══════════════════════════════════════════════════
function addToCart(menu) {
    if (!menu || !menu.idmenu) {
        console.error('Invalid menu object:', menu);
        return;
    }
    
    var idx = cart.findIndex(function(c) { return c.idmenu == menu.idmenu; });

    if (idx >= 0) {
        cart[idx].jumlah++;
        cart[idx].subtotal = cart[idx].jumlah * cart[idx].harga;
    } else {
        cart.push({
            idmenu    : menu.idmenu,
            nama_menu : menu.nama_menu,
            harga     : menu.harga,
            jumlah    : 1,
            subtotal  : menu.harga,
            catatan   : '',
        });
    }

    updateCartBadges();

    // Little toast
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: menu.nama_menu + ' ditambahkan',
        showConfirmButton: false,
        timer: 1200,
    });

    // Re-render menu grid to show qty badges
    if (allMenus.length) renderMenuGrid(allMenus);
}

function updateCartBadges() {
    var totalItems = cart.reduce(function(s, c) { return s + c.jumlah; }, 0);
    
    var cartCountInline = document.getElementById('cart-count-inline');
    if (cartCountInline) cartCountInline.textContent = totalItems;
    
    var btnNextWrap = document.getElementById('btn-next-cart-wrap');
    if (btnNextWrap) btnNextWrap.style.display = totalItems > 0 ? '' : 'none';
}

// ═══════════════════════════════════════════════════
// Render Cart Table (Step 3)
// ═══════════════════════════════════════════════════
function renderCart() {
    var tbody = document.getElementById('tbody-cart');
    if (!tbody) return;
    
    tbody.innerHTML = '';

    if (cart.length === 0) {
        tbody.innerHTML =
            '<tr><td colspan="7" class="text-center text-muted py-4">' +
            '<i class="mdi mdi-cart-outline" style="font-size:2rem;"></i><br>Keranjang masih kosong' +
            '</td></tr>';
        document.getElementById('btn-checkout').disabled = true;
        document.getElementById('total-display').textContent = 'Rp 0';
        return;
    }

    var total = 0;
    cart.forEach(function(item, idx) {
        total += item.subtotal;
        var tr = document.createElement('tr');
        tr.className = 'text-center';
        tr.innerHTML =
            '<td>' + (idx + 1) + '</td>' +
            '<td class="text-start font-weight-semibold">' + item.nama_menu + '</td>' +
            '<td>' + formatRp(item.harga) + '</td>' +
            '<td><input type="number" class="form-control form-control-sm text-center" style="width:80px;margin:0 auto" ' +
                'min="1" value="' + item.jumlah + '" onchange="ubahJumlah(' + idx + ', this.value)"></td>' +
            '<td class="font-weight-bold text-primary">' + formatRp(item.subtotal) + '</td>' +
            '<td><input type="text" class="form-control form-control-sm" style="width:120px;margin:0 auto" ' +
                'placeholder="Catatan..." value="' + (item.catatan || '') + '" onchange="ubahCatatan(' + idx + ', this.value)"></td>' +
            '<td><button class="btn btn-danger btn-sm" onclick="hapusItem(' + idx + ')">' +
                '<i class="mdi mdi-delete"></i></button></td>';
        tbody.appendChild(tr);
    });

    document.getElementById('total-display').textContent = formatRp(total);
    document.getElementById('btn-checkout').disabled = false;
}

function ubahJumlah(idx, val) {
    var j = parseInt(val) || 1;
    cart[idx].jumlah   = j;
    cart[idx].subtotal = cart[idx].harga * j;
    renderCart();
    updateCartBadges();
}

function ubahCatatan(idx, val) {
    cart[idx].catatan = val;
}

function hapusItem(idx) {
    cart.splice(idx, 1);
    renderCart();
    updateCartBadges();
}

// ═══════════════════════════════════════════════════
// CHECKOUT → Midtrans Snap
// ═══════════════════════════════════════════════════
function prosesCheckout() {
    if (!cart.length) {
        Swal.fire('Keranjang Kosong!', 'Silahkan pilih menu terlebih dahulu.', 'warning');
        return;
    }

    var total = cart.reduce(function(s, c) { return s + c.subtotal; }, 0);
    
    // Check CSRF token
    var csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token meta tag not found!');
        Swal.fire('Error!', 'CSRF token tidak ditemukan. Refresh halaman.', 'error');
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        html: 'Total: <strong>' + formatRp(total) + '</strong><br>Lanjutkan ke halaman pembayaran?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Bayar!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#28a745',
    }).then(function(result) {
        if (!result.isConfirmed) return;

        var btn = document.getElementById('btn-checkout');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';
        btn.disabled = true;
        
        console.log('Sending checkout request...', { cart: cart, total: total });

        // POST checkout ke backend
        axios.post('/api/kantin/checkout', {
            cart  : cart,
            total : total,
        }, {
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(function(response) {
            console.log('Checkout response:', response.data);
            var res = response.data;
            
            // Check if snap_token exists
            if (!res.snap_token) {
                console.error('No snap_token in response:', res);
                Swal.fire('Error!', 'Token pembayaran tidak ditemukan.', 'error');
                btn.innerHTML = '<i class="mdi mdi-credit-card-check"></i> Bayar Sekarang';
                btn.disabled = false;
                return;
            }
            
            // Check if window.snap is loaded
            if (typeof window.snap === 'undefined' || !window.snap.pay) {
                console.error('Midtrans Snap not loaded!');
                Swal.fire('Error!', 'Midtrans Snap belum terload. Refresh halaman.', 'error');
                btn.innerHTML = '<i class="mdi mdi-credit-card-check"></i> Bayar Sekarang';
                btn.disabled = false;
                return;
            }

            console.log('Opening Snap with token:', res.snap_token.substring(0, 20) + '...');

            // Buka Midtrans Snap popup
            window.snap.pay(res.snap_token, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    Swal.fire({
                        title: '✅ Pembayaran Berhasil!',
                        html: 'Nama: <strong>' + res.guest_name + '</strong><br>' +
                              'Order: <strong>' + res.order_id + '</strong><br>' +
                              'Total: <strong>' + formatRp(total) + '</strong>',
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                    }).then(function() {
                        var status = result.transaction_status || 'capture';
                        var payment = result.payment_type || '';
                        window.location.href = '/kantin/success/' + res.order_id + '?transaction_status=' + encodeURIComponent(status) + '&payment_type=' + encodeURIComponent(payment);
                    });
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    var status = result.transaction_status || 'pending';
                    var payment = result.payment_type || '';
                    window.location.href = '/kantin/success/' + res.order_id + '?transaction_status=' + encodeURIComponent(status) + '&payment_type=' + encodeURIComponent(payment);
                },
                onError: function(result) {
                    console.error('Payment error:', result);
                    Swal.fire('Gagal!', 'Pembayaran gagal. Silahkan coba lagi.', 'error');
                    btn.innerHTML = '<i class="mdi mdi-credit-card-check"></i> Bayar Sekarang';
                    btn.disabled = false;
                },
                onClose: function() {
                    console.log('Snap closed');
                    btn.innerHTML = '<i class="mdi mdi-credit-card-check"></i> Bayar Sekarang';
                    btn.disabled = false;
                }
            });
        })
        .catch(function(error) {
            console.error('Checkout error:', error);
            var msg = 'Terjadi kesalahan server.';
            if (error.response && error.response.data && error.response.data.message) {
                msg = error.response.data.message;
            } else if (error.message) {
                msg = error.message;
            }
            Swal.fire('Gagal!', msg, 'error');
            btn.innerHTML = '<i class="mdi mdi-credit-card-check"></i> Bayar Sekarang';
            btn.disabled = false;
        });
    });
}
</script>
@endpush