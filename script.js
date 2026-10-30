/**
 * NUCOMU CAFE — Client-Side Engine (Vanilla JS)
 * Stack: Vanilla JavaScript (Zero external libraries)
 * Features:
 *  - Scroll-spy & sticky navbar with backdrop-blur
 *  - Category tab filtering with smooth fade/scale animations
 *  - Hover & mobile tap card expansion with 2-column options
 *  - Add to cart with real-time feedback & bump animation
 *  - Centered floating modal with two views: View 1 (Cart) -> View 2 (Checkout)
 *  - Kasir vs Kurir Zendo dynamic transaction handling
 *  - Monospace pricing breakdown in checkout
 *  - WhatsApp order integration
 *  - Backend API order submission to SQLite PDO
 */

// ==========================================================================
// 1. STATE & STORAGE MANAGEMENT
// ==========================================================================
const CART_STORAGE_KEY = 'nucomu_cart_items_v3';
const WA_PHONE_NUMBER = '6281234567890'; // Official Nucomu Cafe WhatsApp

let cart = [];
let currentTransactionMethod = 'kasir'; // 'kasir' or 'zendo'
let activeCategory = 'all';

function loadCartFromStorage() {
  try {
    const raw = localStorage.getItem(CART_STORAGE_KEY);
    cart = raw ? JSON.parse(raw) : [];
    if (!Array.isArray(cart)) cart = [];
  } catch (e) {
    cart = [];
  }
}

function saveCartToStorage() {
  try {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
  } catch (e) {}
}

function formatRupiah(number) {
  const num = parseInt(number, 10) || 0;
  return 'Rp ' + num.toLocaleString('id-ID');
}

// ==========================================================================
// 2. NAVBAR STICKY BLUR & SCROLL-SPY
// ==========================================================================
function initNavbarAndScrollSpy() {
  const navbar = document.querySelector('.navbar-wrapper');
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-link');

  window.addEventListener('scroll', () => {
    const scrollY = window.pageYOffset || document.documentElement.scrollTop;

    // Sticky navbar backdrop blur
    if (scrollY > 30) {
      navbar?.classList.add('navbar-scrolled');
    } else {
      navbar?.classList.remove('navbar-scrolled');
    }

    // Scroll-Spy active section indicator
    let currentSectionId = '';
    sections.forEach(sec => {
      const top = sec.offsetTop - 120;
      const height = sec.offsetHeight;
      if (scrollY >= top && scrollY < top + height) {
        currentSectionId = sec.getAttribute('id');
      }
    });

    navLinks.forEach(link => {
      link.classList.remove('active');
      if (link.getAttribute('href') === `#${currentSectionId}`) {
        link.classList.add('active');
      }
    });
  }, { passive: true });
}

// ==========================================================================
// 3. CATEGORY FILTER (FADE & SCALE ANIMATION, ROBUST GRID)
// ==========================================================================
function initCategoryFilter() {
  const tabButtons = document.querySelectorAll('.category-tab-btn');
  const cardSlots = document.querySelectorAll('.menu-card-slot');

  tabButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const category = btn.dataset.category;

      // If clicking already active tab, toggle back to 'all'
      if (activeCategory === category && category !== 'all') {
        filterMenuByCategory('all');
      } else {
        filterMenuByCategory(category);
      }
    });
  });

  function filterMenuByCategory(cat) {
    activeCategory = cat;

    // Update tab visual indicator
    tabButtons.forEach(btn => {
      const isMatch = (btn.dataset.category === cat && cat !== 'all');
      btn.classList.toggle('active', isMatch);
    });

    // Animate menu card slots
    cardSlots.forEach(slot => {
      const cardCat = slot.dataset.category || '';
      const isChefSpecial = slot.dataset.chefSpecial === '1';

      let show = false;
      if (cat === 'all') {
        show = true;
      } else if (cat === 'chef-specials') {
        show = isChefSpecial;
      } else {
        show = (cardCat === cat);
      }

      if (show) {
        slot.classList.remove('is-filtered-out');
        slot.style.opacity = '0';
        slot.style.transform = 'scale(0.95)';
        setTimeout(() => {
          slot.style.opacity = '1';
          slot.style.transform = 'scale(1)';
        }, 30);
      } else {
        slot.classList.add('is-filtered-out');
      }
    });
  }
}

// ==========================================================================
// 4. TOUCH / TAP EXPANSION FOR MOBILE
// ==========================================================================
function initCardTapExpansion() {
  const isTouchDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
  if (!isTouchDevice) return;

  const cards = document.querySelectorAll('.menu-card');

  cards.forEach(card => {
    card.addEventListener('click', (e) => {
      if (e.target.closest('.menu-card-options') || e.target.closest('.btn-add-to-cart')) {
        return;
      }
      const wasExpanded = card.classList.contains('is-expanded');
      cards.forEach(c => c.classList.remove('is-expanded'));
      if (!wasExpanded) {
        card.classList.add('is-expanded');
      }
    });
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('.menu-card')) {
      cards.forEach(c => c.classList.remove('is-expanded'));
    }
  });
}

// ==========================================================================
// 5. ADD TO CART WITH BUTTON FEEDBACK & OPEN TO CART VIEW FIRST
// ==========================================================================
function initAddToCartButtons() {
  document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const slot = btn.closest('.menu-card-slot');
      if (!slot) return;

      const id = slot.dataset.id;
      const name = slot.dataset.name;
      const price = parseInt(slot.dataset.price, 10);
      const unit = slot.dataset.unit || '/porsi';
      const image = slot.dataset.image;

      // Extract chosen radio options (e.g. Sunny Side Up, Mild)
      const chosenOptions = [];
      const checkedRadios = slot.querySelectorAll('input[type="radio"]:checked');
      checkedRadios.forEach(r => {
        if (r.value) chosenOptions.push(r.value);
      });

      // Add to state and storage
      addItemToCart({
        id,
        name,
        price,
        unit,
        image,
        options: chosenOptions
      });

      // Temporary button feedback
      const originalHtml = btn.innerHTML;
      btn.classList.add('is-added');
      btn.innerHTML = `
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        <span>Masuk Keranjang!</span>
      `;

      setTimeout(() => {
        btn.classList.remove('is-added');
        btn.innerHTML = originalHtml;
      }, 1200);
    });
  });
}

function addItemToCart(item) {
  const optionsKey = (item.options || []).slice().sort().join('|');
  const key = `${item.id}__${optionsKey}`;

  const existingIdx = cart.findIndex(c => c.key === key);
  if (existingIdx > -1) {
    cart[existingIdx].qty += 1;
  } else {
    cart.push({
      key: key,
      id: item.id,
      name: item.name,
      price: item.price,
      unit: item.unit,
      image: item.image,
      options: item.options || [],
      qty: 1
    });
  }

  saveCartToStorage();
  updateCartUI();
  triggerBadgeBump();
}

function updateItemQty(key, delta) {
  const idx = cart.findIndex(c => c.key === key);
  if (idx === -1) return;

  cart[idx].qty += delta;
  if (cart[idx].qty <= 0) {
    cart.splice(idx, 1);
  }

  saveCartToStorage();
  updateCartUI();
}

function removeItemFromCart(key) {
  cart = cart.filter(c => c.key !== key);
  saveCartToStorage();
  updateCartUI();
}

function triggerBadgeBump() {
  const badge = document.getElementById('floatingCartBadge');
  if (badge) {
    badge.classList.remove('bump');
    void badge.offsetWidth; // Force reflow
    badge.classList.add('bump');
  }
}

// ==========================================================================
// 6. CENTERED MODAL CONTROLS (CART VIEW <-> CHECKOUT VIEW)
// ==========================================================================
function openModalCart() {
  const backdrop = document.getElementById('nucomuModalBackdrop');
  if (!backdrop) return;

  // Always default to View 1: Keranjang Pesanan
  switchModalView('cart');
  updateCartUI();

  backdrop.classList.add('is-open');
  document.body.style.overflow = 'hidden';
}

function closeCenteredModal() {
  const backdrop = document.getElementById('nucomuModalBackdrop');
  if (backdrop) {
    backdrop.classList.remove('is-open');
  }
  document.body.style.overflow = '';
}

function handleBackdropClick(event) {
  const backdrop = document.getElementById('nucomuModalBackdrop');
  if (event.target === backdrop) {
    closeCenteredModal();
  }
}

function switchModalView(viewName) {
  const headerCart = document.getElementById('headerCartView');
  const headerCheckout = document.getElementById('headerCheckoutView');
  const viewCart = document.getElementById('modalCartView');
  const viewCheckout = document.getElementById('modalCheckoutView');
  const footerCart = document.getElementById('modalCartFooter');

  if (viewName === 'checkout') {
    if (cart.length === 0) {
      alert('Keranjang belanja masih kosong! Silakan pilih menu terlebih dahulu.');
      return;
    }
    // Switch to Checkout View
    headerCart?.classList.add('hidden');
    headerCheckout?.classList.remove('hidden');
    viewCart?.classList.add('hidden');
    viewCheckout?.classList.remove('hidden');
    footerCart?.classList.add('hidden');

    updateCartUI();
  } else {
    // Default to Cart View
    headerCart?.classList.remove('hidden');
    headerCheckout?.classList.add('hidden');
    viewCart?.classList.remove('hidden');
    viewCheckout?.classList.add('hidden');
    footerCart?.classList.remove('hidden');

    updateCartUI();
  }
}

// ==========================================================================
// 7. TRANSACTION METHOD (KASIR VS KURIR ZENDO)
// ==========================================================================
function setTransactionMethod(method) {
  currentTransactionMethod = (method === 'zendo') ? 'zendo' : 'kasir';

  const cardKasir = document.getElementById('methodCardKasir');
  const cardZendo = document.getElementById('methodCardZendo');
  const fieldsKasir = document.getElementById('fieldsKasirSpecific');
  const fieldsZendo = document.getElementById('fieldsZendoSpecific');
  const addressInput = document.getElementById('inputDeliveryAddress');

  if (currentTransactionMethod === 'kasir') {
    cardKasir?.classList.add('active');
    cardZendo?.classList.remove('active');
    if (fieldsKasir) fieldsKasir.style.display = 'block';
    if (fieldsZendo) fieldsZendo.style.display = 'none';
    if (addressInput) addressInput.required = false;
  } else {
    cardKasir?.classList.remove('active');
    cardZendo?.classList.add('active');
    if (fieldsKasir) fieldsKasir.style.display = 'none';
    if (fieldsZendo) fieldsZendo.style.display = 'block';
    if (addressInput) addressInput.required = true;
  }

  updateCartUI();
}

// ==========================================================================
// 8. UPDATE CART UI (FLOATING CAPSULE, VIEW 1 CART & VIEW 2 CHECKOUT)
// ==========================================================================
function updateCartUI() {
  let totalQty = 0;
  let subtotal = 0;

  cart.forEach(item => {
    totalQty += item.qty;
    subtotal += (item.price * item.qty);
  });

  // 1. Floating Capsule & Navbar Badge
  const floatingBadge = document.getElementById('floatingCartBadge');
  const floatingTotal = document.getElementById('floatingCartTotal');
  const navBadge = document.getElementById('navCartBadge');
  const modalHeaderBadge = document.getElementById('modalHeaderBadgeCount');

  if (floatingBadge) floatingBadge.textContent = totalQty;
  if (navBadge) navBadge.textContent = totalQty;
  if (modalHeaderBadge) modalHeaderBadge.textContent = `${totalQty} item`;
  if (floatingTotal) floatingTotal.textContent = `My cart: ${formatRupiah(subtotal)}`;

  // 2. View 1: Keranjang Pesanan List
  const cartList = document.getElementById('modalCartItemsList');
  const emptyState = document.getElementById('modalEmptyState');
  const cartSubtotalEl = document.getElementById('cartSubtotalAmount');
  const cartFinalTotalEl = document.getElementById('cartFinalTotalAmount');

  if (cartSubtotalEl) cartSubtotalEl.textContent = formatRupiah(subtotal);
  if (cartFinalTotalEl) cartFinalTotalEl.textContent = formatRupiah(subtotal);

  if (cart.length === 0) {
    if (emptyState) emptyState.style.display = 'block';
    if (cartList) cartList.innerHTML = '';
  } else {
    if (emptyState) emptyState.style.display = 'none';
    if (cartList) {
      cartList.innerHTML = cart.map(item => `
        <div class="cart-item">
          <div class="cart-item-thumb">
            <img src="${item.image}" alt="${item.name}">
          </div>
          <div class="cart-item-info">
            <div class="cart-item-title">${item.qty}× ${item.name}</div>
            ${item.options && item.options.length ? `
              <div class="cart-item-chips">
                ${item.options.map(opt => `<span class="cart-chip">${opt}</span>`).join('')}
              </div>
            ` : ''}
            <div class="cart-item-controls">
              <div class="qty-stepper">
                <button type="button" class="qty-btn" onclick="updateItemQty('${item.key}', -1)" aria-label="Kurangi jumlah">−</button>
                <span class="qty-val">${item.qty}</span>
                <button type="button" class="qty-btn" onclick="updateItemQty('${item.key}', 1)" aria-label="Tambah jumlah">+</button>
              </div>
              <span class="cart-item-price">${formatRupiah(item.price * item.qty)}</span>
              <button type="button" class="btn-remove-item" onclick="removeItemFromCart('${item.key}')" aria-label="Hapus item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
      `).join('');
    }
  }

  // 3. View 2: Checkout Breakdown (Monospace Rincian & Calculation)
  const checkoutCountTag = document.getElementById('checkoutItemCountTag');
  const checkoutMonoList = document.getElementById('checkoutMonospaceList');
  const checkoutSubtotal = document.getElementById('checkoutSubtotalAmount');
  const checkoutShippingRow = document.getElementById('checkoutShippingRow');
  const checkoutShippingFee = document.getElementById('checkoutShippingFeeAmount');
  const checkoutTotal = document.getElementById('checkoutTotalAmount');
  const checkoutHint = document.getElementById('checkoutPaymentHint');
  const checkoutBtn = document.getElementById('btnSubmitOrderForm');

  if (checkoutCountTag) checkoutCountTag.textContent = `${totalQty} item`;
  if (checkoutSubtotal) checkoutSubtotal.textContent = formatRupiah(subtotal);

  const deliveryFee = (currentTransactionMethod === 'zendo') ? 10000 : 0;
  const grandTotal = subtotal + deliveryFee;

  if (checkoutShippingRow) {
    if (currentTransactionMethod === 'zendo') {
      checkoutShippingRow.style.display = 'flex';
      if (checkoutShippingFee) checkoutShippingFee.textContent = formatRupiah(deliveryFee);
    } else {
      checkoutShippingRow.style.display = 'none';
    }
  }

  if (checkoutTotal) checkoutTotal.textContent = formatRupiah(grandTotal);

  if (checkoutHint) {
    checkoutHint.textContent = (currentTransactionMethod === 'zendo')
      ? '*Pesanan akan diantarkan ke alamat tujuan oleh Kurir Zendo (Ongkir Rp 10.000).'
      : '*Bayar di kasir setelah menunjukkan slip/QR.';
  }

  if (checkoutBtn) {
    checkoutBtn.textContent = (currentTransactionMethod === 'zendo')
      ? 'Kirim Pesanan ke Kurir Zendo'
      : 'Konfirmasi & Buat Pesanan (Kasir)';
  }

  if (checkoutMonoList) {
    if (cart.length === 0) {
      checkoutMonoList.innerHTML = '<div style="font-size:0.75rem; color:#888;">Belum ada item dipilih.</div>';
    } else {
      checkoutMonoList.innerHTML = cart.map(item => `
        <div class="checkout-line-item">
          <div>
            <span class="checkout-item-name">${item.name} (${item.qty}×)</span>
            ${item.options && item.options.length ? `
              <div style="font-size: 0.68rem; color: #78726b; margin-top: 1px;">
                ${item.options.join(', ')}
              </div>
            ` : ''}
          </div>
          <span class="checkout-mono-price">${formatRupiah(item.price)} × ${item.qty} = ${formatRupiah(item.price * item.qty)}</span>
        </div>
      `).join('');
    }
  }
}

// ==========================================================================
// 9. WHATSAPP DIRECT ORDER GENERATOR
// ==========================================================================
function orderViaWhatsAppDirect() {
  if (cart.length === 0) {
    alert('Keranjang belanja masih kosong! Silakan pilih menu terlebih dahulu.');
    return;
  }

  let text = `Halo Nucomu Cafe, saya ingin memesan menu:\n\n`;
  let subtotal = 0;

  cart.forEach((item, idx) => {
    const lineTotal = item.price * item.qty;
    subtotal += lineTotal;
    const opts = (item.options && item.options.length) ? ` (${item.options.join(', ')})` : '';
    text += `${idx + 1}. ${item.name}${opts} — ${item.qty}x @ ${formatRupiah(item.price)} = ${formatRupiah(lineTotal)}\n`;
  });

  text += `\n*Subtotal Menu:* ${formatRupiah(subtotal)}`;
  text += `\n*Metode:* ${currentTransactionMethod === 'zendo' ? 'Kurir Zendo' : 'Proses Kasir'}`;
  text += `\n\nMohon konfirmasi ketersediaan dan proses pesanan saya. Terima kasih!`;

  const waUrl = `https://wa.me/${WA_PHONE_NUMBER}?text=${encodeURIComponent(text)}`;
  window.open(waUrl, '_blank');
}

// ==========================================================================
// 10. CHECKOUT SUBMISSION & BACKEND POST INTEGRATION
// ==========================================================================
function submitCenteredOrder(event) {
  event.preventDefault();

  if (cart.length === 0) {
    alert('Keranjang belanja kosong! Silakan pilih menu terlebih dahulu.');
    return;
  }

  const nameInput = document.getElementById('inputCustomerName');
  const phoneInput = document.getElementById('inputCustomerPhone');
  const tableInput = document.getElementById('inputTableNo');
  const addressInput = document.getElementById('inputDeliveryAddress');
  const courierNotesInput = document.getElementById('inputCourierNotes');
  const orderNotesInput = document.getElementById('inputOrderNotes');

  const customerName = nameInput ? nameInput.value.trim() : '';
  const customerPhone = phoneInput ? phoneInput.value.trim() : '';
  const tableNo = tableInput ? tableInput.value.trim() : '';
  const deliveryAddress = addressInput ? addressInput.value.trim() : '';
  const courierNotes = courierNotesInput ? courierNotesInput.value.trim() : '';
  const orderNotes = orderNotesInput ? orderNotesInput.value.trim() : '';

  if (!customerName || !customerPhone) {
    alert('Harap isi Nama Pemesan dan No. WhatsApp.');
    return;
  }

  if (currentTransactionMethod === 'zendo' && !deliveryAddress) {
    alert('Harap isi Alamat Lengkap Pengiriman untuk Kurir Zendo.');
    addressInput?.focus();
    return;
  }

  const submitBtn = document.getElementById('btnSubmitOrderForm');
  if (submitBtn) {
    submitBtn.disabled = true;
    submitBtn.textContent = 'Memproses Pesanan...';
  }

  // Combined notes
  let combinedNotes = orderNotes;
  if (currentTransactionMethod === 'zendo' && courierNotes) {
    combinedNotes = combinedNotes ? `${combinedNotes} | Patokan: ${courierNotes}` : `Patokan: ${courierNotes}`;
  }

  const payload = {
    customer_name: customerName,
    customer_phone: customerPhone,
    process_method: currentTransactionMethod,
    delivery_address: deliveryAddress,
    table_no: tableNo,
    notes: combinedNotes,
    items: cart.map(item => ({
      id: item.id,
      name: item.name,
      price: item.price,
      qty: item.qty,
      options: item.options ? item.options.join(', ') : ''
    }))
  };

  fetch('index.php?api=order', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify(payload)
  })
  .then(async res => {
    const data = await res.json().catch(() => null);
    if (!res.ok || !data) {
      throw new Error(data && data.message ? data.message : 'Gagal memproses pesanan ke server');
    }
    return data;
  })
  .then(data => {
    // Show receipt success modal
    showReceiptModal(data, customerName);

    // Empty local cart
    cart = [];
    saveCartToStorage();
    updateCartUI();

    // Close centered modal
    closeCenteredModal();
  })
  .catch(err => {
    alert(err.message || 'Terjadi gangguan jaringan saat memproses pesanan.');
  })
  .finally(() => {
    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.textContent = (currentTransactionMethod === 'zendo')
        ? 'Kirim Pesanan ke Kurir Zendo'
        : 'Konfirmasi & Buat Pesanan (Kasir)';
    }
  });
}

function showReceiptModal(orderData, customerName) {
  const modal = document.getElementById('receiptModal');
  const codeEl = document.getElementById('receiptOrderCode');
  const nameEl = document.getElementById('receiptCustomerName');
  const methodEl = document.getElementById('receiptMethod');
  const totalEl = document.getElementById('receiptTotal');
  const noteEl = document.getElementById('receiptNote');

  if (codeEl) codeEl.textContent = orderData.order_code || 'NCM-ORDER';
  if (nameEl) nameEl.textContent = customerName;
  if (methodEl) {
    methodEl.textContent = (orderData.process_method === 'zendo')
      ? 'Kurir Zendo (Kirim Otomatis)'
      : 'Proses Kasir (Slip QR)';
  }
  if (totalEl) totalEl.textContent = formatRupiah(orderData.grand_total);
  if (noteEl) {
    noteEl.textContent = (orderData.process_method === 'zendo')
      ? 'Pesanan diteruskan ke Kurir Zendo. Kurir siaga akan segera menghubungi via WhatsApp.'
      : 'Silakan tunjukkan Kode Pesanan ke kasir untuk proses pembayaran & cetak slip.';
  }

  if (modal) modal.classList.add('is-open');
}

function closeReceiptModal() {
  const modal = document.getElementById('receiptModal');
  if (modal) modal.classList.remove('is-open');
}

// ==========================================================================
// 11. INITIALIZATION ON DOM READY
// ==========================================================================
document.addEventListener('DOMContentLoaded', () => {
  loadCartFromStorage();
  initNavbarAndScrollSpy();
  initCategoryFilter();
  initCardTapExpansion();
  initAddToCartButtons();
  updateCartUI();

  // Method Cards click listeners
  const methodKasir = document.getElementById('methodCardKasir');
  const methodZendo = document.getElementById('methodCardZendo');
  if (methodKasir) methodKasir.addEventListener('click', () => setTransactionMethod('kasir'));
  if (methodZendo) methodZendo.addEventListener('click', () => setTransactionMethod('zendo'));

  // ESC key listener to close modals
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeCenteredModal();
      closeReceiptModal();
    }
  });
});
