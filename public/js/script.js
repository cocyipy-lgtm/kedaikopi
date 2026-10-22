
"use strict";

// =====================================
// KONFIGURASI COFFEE BREAK
// =====================================

// Ganti dengan nomor WhatsApp pemilik kedai.
// Format: 628xxxxxxxxxx, tanpa tanda + atau angka 0 di depan.
const WHATSAPP_NUMBER = "62881025153169";

const products = window.coffeeProducts ?? [];

// =====================================
// ELEMENT WEBSITE
// =====================================

const productList = document.getElementById("product-list");
const cartCount = document.getElementById("cart-count");
const cartItems = document.getElementById("cart-items");
const cartTotal = document.getElementById("cart-total");

const cartDrawer = document.getElementById("cart-drawer");
const cartOverlay = document.getElementById("cart-overlay");

const toast = document.getElementById("toast");
const cart = new Map();

let toastTimeout;

// =====================================
// FORMAT HARGA
// =====================================

function formatRupiah(amount) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0
    }).format(amount);
}

// =====================================
// NOTIFIKASI
// =====================================

function showToast(message) {
    toast.textContent = message;
    toast.classList.add("show");

    clearTimeout(toastTimeout);

    toastTimeout = setTimeout(() => {
        toast.classList.remove("show");
    }, 2500);
}

// =====================================
// TAMPILKAN MENU
// =====================================

function renderProducts(category = "Semua") {
    productList.replaceChildren();

    const filteredProducts = category === "Semua"
        ? products
        : products.filter(product => product.category === category);

    if (filteredProducts.length === 0) {
        const message = document.createElement("p");
        message.textContent = "Menu belum tersedia.";
        productList.append(message);
        return;
    }

    filteredProducts.forEach(product => {
        const article = document.createElement("article");
        article.className = "product-card";

        const imageWrapper = document.createElement("div");
        imageWrapper.className = "product-image";

        const image = document.createElement("img");
        image.src = product.image;
        image.alt = product.name;
        image.loading = "lazy";

        image.onerror = () => {
            image.onerror = null;
            image.alt = `Foto ${product.name} belum tersedia`;
        };

        const categoryBadge = document.createElement("span");
        categoryBadge.className = "product-category";
        categoryBadge.textContent = product.category;

        imageWrapper.append(image, categoryBadge);

        const info = document.createElement("div");
        info.className = "product-info";

        const name = document.createElement("h3");
        name.textContent = product.name;
        if (product.category === "Kopi") {
            name.classList.add("coffee-name");
        }

        const description = document.createElement("p");
        description.textContent = product.description;

        const bottom = document.createElement("div");
        bottom.className = "product-bottom";

        const price = document.createElement("span");
        price.className = "product-price";
        price.textContent = formatRupiah(product.price);

        const addButton = document.createElement("button");
        addButton.type = "button";
        addButton.className = "add-cart";
        addButton.textContent = "+";
        addButton.setAttribute(
            "aria-label",
            `Tambah ${product.name} ke keranjang`
        );

        addButton.addEventListener("click", () => {
            addToCart(product.id);
        });

        bottom.append(price, addButton);
        info.append(name, description, bottom);
        article.append(imageWrapper, info);

        productList.append(article);
    });
}

// =====================================
// FILTER KATEGORI
// =====================================

document.querySelectorAll(".filter-btn").forEach(button => {
    button.addEventListener("click", () => {
        document.querySelectorAll(".filter-btn").forEach(item => {
            item.classList.remove("active");
        });

        button.classList.add("active");

        renderProducts(button.dataset.category);
    });
});

// =====================================
// TAMBAH KE KERANJANG
// =====================================

function addToCart(productId) {
    const product = products.find(item => item.id === productId);

    if (!product) return;

    const quantity = cart.get(productId) || 0;
    cart.set(productId, quantity + 1);

    renderCart();
    showToast(`${product.name} ditambahkan ke keranjang.`);
}

// =====================================
// UBAH JUMLAH PRODUK
// =====================================

function changeQuantity(productId, amount) {
    const quantity = cart.get(productId) || 0;
    const newQuantity = quantity + amount;

    if (newQuantity <= 0) {
        cart.delete(productId);
    } else {
        cart.set(productId, newQuantity);
    }

    renderCart();
}

// =====================================
// HAPUS PRODUK
// =====================================

function removeFromCart(productId) {
    cart.delete(productId);
    renderCart();
    showToast("Produk dihapus dari keranjang.");
}

// =====================================
// TAMPILKAN KERANJANG
// =====================================

function renderCart() {
    cartItems.replaceChildren();

    let totalQuantity = 0;
    let totalPrice = 0;

    cart.forEach((quantity, productId) => {
        const product = products.find(item => item.id === productId);

        if (!product) return;

        totalQuantity += quantity;
        totalPrice += product.price * quantity;

        const item = document.createElement("div");
        item.className = "cart-item";

        const details = document.createElement("div");

        const name = document.createElement("h3");
        name.textContent = product.name;
        if (product.category === "Kopi") {
            name.classList.add("coffee-name");
        }

        const subtotal = document.createElement("p");
        subtotal.textContent = formatRupiah(
            product.price * quantity
        );

        const controls = document.createElement("div");
        controls.className = "quantity-controls";

        const decrease = document.createElement("button");
        decrease.type = "button";
        decrease.textContent = "−";
        decrease.setAttribute("aria-label", "Kurangi jumlah");

        decrease.addEventListener("click", () => {
            changeQuantity(productId, -1);
        });

        const quantityText = document.createElement("span");
        quantityText.textContent = quantity;

        const increase = document.createElement("button");
        increase.type = "button";
        increase.textContent = "+";
        increase.setAttribute("aria-label", "Tambah jumlah");

        increase.addEventListener("click", () => {
            changeQuantity(productId, 1);
        });

        controls.append(decrease, quantityText, increase);
        details.append(name, subtotal, controls);

        const removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.className = "remove-item";
        removeButton.textContent = "Hapus";

        removeButton.addEventListener("click", () => {
            removeFromCart(productId);
        });

        item.append(details, removeButton);
        cartItems.append(item);
    });

    if (cart.size === 0) {
        const emptyMessage = document.createElement("p");
        emptyMessage.className = "empty-cart";
        emptyMessage.textContent = "Keranjang kamu masih kosong ☕";
        cartItems.append(emptyMessage);
    }

    cartCount.textContent = totalQuantity;
    cartTotal.textContent = formatRupiah(totalPrice);
}

// =====================================
// BUKA DAN TUTUP KERANJANG
// =====================================

function openCart() {
    cartDrawer.classList.add("show");
    cartOverlay.classList.add("show");

    document.body.classList.add("cart-open");
    cartDrawer.setAttribute("aria-hidden", "false");
}

function closeCart() {
    cartDrawer.classList.remove("show");
    cartOverlay.classList.remove("show");

    document.body.classList.remove("cart-open");
    cartDrawer.setAttribute("aria-hidden", "true");
}

document.getElementById("open-cart")
    .addEventListener("click", openCart);

document.getElementById("close-cart")
    .addEventListener("click", closeCart);

cartOverlay.addEventListener("click", closeCart);

document.addEventListener("keydown", event => {
    if (event.key === "Escape") closeCart();
});

// =====================================
// CHECKOUT WHATSAPP
// =====================================

document.getElementById("checkout-button")
    .addEventListener("click", () => {
        if (cart.size === 0) {
            showToast("Keranjang kamu masih kosong.");
            return;
        }

        const nameInput = document.getElementById("customer-name");
        const noteInput = document.getElementById("customer-note");

        const customerName = nameInput.value.trim();
        const customerNote = noteInput.value.trim();

        if (!customerName) {
            showToast("Masukkan nama pemesan terlebih dahulu.");
            nameInput.focus();
            return;
        }

        if (!/^\d{10,15}$/.test(WHATSAPP_NUMBER)) {
            showToast("Nomor WhatsApp belum dikonfigurasi.");
            return;
        }

        let total = 0;

        const lines = [
            "*PESANAN COFFEE BREAK*",
            "",
            `Nama: ${customerName}`,
            "",
            "*Detail pesanan:*"
        ];

        cart.forEach((quantity, productId) => {
            const product = products.find(
                item => item.id === productId
            );

            if (!product) return;

            const subtotal = product.price * quantity;
            total += subtotal;

            lines.push(
                `- ${product.name} x${quantity} = ${formatRupiah(subtotal)}`
            );
        });

        lines.push("");
        lines.push(`*TOTAL: ${formatRupiah(total)}*`);

        if (customerNote) {
            lines.push("");
            lines.push(`Catatan: ${customerNote}`);
        }

        lines.push("");
        lines.push("Mohon konfirmasi pesanan saya.");

        const url =
            `https://wa.me/${WHATSAPP_NUMBER}?text=` +
            encodeURIComponent(lines.join("\n"));

        window.open(url, "_blank", "noopener,noreferrer");
    });

// =====================================
// KONTAK WHATSAPP
// =====================================

const contactWhatsapp = document.getElementById("contact-whatsapp");

contactWhatsapp.href = `https://wa.me/${WHATSAPP_NUMBER}`;
contactWhatsapp.target = "_blank";
contactWhatsapp.rel = "noopener noreferrer";

// =====================================
// NAVBAR MOBILE
// =====================================

const menuToggle = document.getElementById("menu-toggle");
const navLinks = document.getElementById("nav-links");

menuToggle.addEventListener("click", () => {
    const isOpen = navLinks.classList.toggle("open");

    menuToggle.setAttribute("aria-expanded", String(isOpen));
    menuToggle.setAttribute(
        "aria-label",
        isOpen ? "Tutup menu navigasi" : "Buka menu navigasi"
    );
});

navLinks.querySelectorAll("a").forEach(link => {
    link.addEventListener("click", () => {
        navLinks.classList.remove("open");
        menuToggle.setAttribute("aria-expanded", "false");
        menuToggle.setAttribute("aria-label", "Buka menu navigasi");
    });
});

// =====================================
// TAHUN OTOMATIS
// =====================================

document.getElementById("year").textContent =
    new Date().getFullYear();

// =====================================
// JALANKAN WEBSITE
// =====================================

renderProducts();
renderCart();
