/**
 * Enhanced Product Cards JavaScript
 * Handles wishlist management, modal interactions, and WhatsApp integration
 */

(function() {
    'use strict';

    // Global constants
    const SITE_WHATSAPP_NUMBER = "+905555555555"; // Replaceable constant
    const WISHLIST_STORAGE_KEY = 'wishlist_items_v1';

    // Prevent multiple initializations
    if (window.__enhancedProductCardsBound) return;
    window.__enhancedProductCardsBound = true;

    /**
     * Wishlist Manager
     */
    class WishlistManager {
        constructor() {
            this.items = this.loadWishlist();
            this.init();
        }

        loadWishlist() {
            try {
                const stored = localStorage.getItem(WISHLIST_STORAGE_KEY);
                return stored ? JSON.parse(stored) : [];
            } catch (error) {
                console.error('Error loading wishlist:', error);
                return [];
            }
        }

        saveWishlist() {
            try {
                localStorage.setItem(WISHLIST_STORAGE_KEY, JSON.stringify(this.items));
            } catch (error) {
                console.error('Error saving wishlist:', error);
            }
        }

        addItem(product) {
            const existingIndex = this.items.findIndex(item => item.id === product.id);

            if (existingIndex === -1) {
                this.items.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    sale_price: product.sale_price,
                    image: product.image,
                    url: product.url,
                    brand: product.brand,
                    added_at: new Date().toISOString()
                });
                this.saveWishlist();
                return true; // Added successfully
            }
            return false; // Already exists
        }

        removeItem(productId) {
            const index = this.items.findIndex(item => item.id === productId);
            if (index !== -1) {
                this.items.splice(index, 1);
                this.saveWishlist();
                return true; // Removed successfully
            }
            return false; // Not found
        }

        isInWishlist(productId) {
            return this.items.some(item => item.id === productId);
        }

        getItemCount() {
            return this.items.length;
        }

        getAllItems() {
            return this.items;
        }

        init() {
            // Update wishlist button states on page load
            this.updateWishlistButtonStates();
        }

        updateWishlistButtonStates() {
            document.querySelectorAll('.wishlist-btn').forEach(btn => {
                const productId = parseInt(btn.dataset.productId);
                if (this.isInWishlist(productId)) {
                    btn.classList.add('in-wishlist');
                    btn.title = 'Remove from Wishlist';
                } else {
                    btn.classList.remove('in-wishlist');
                    btn.title = 'Add to Wishlist';
                }
            });
        }
    }

    /**
     * Toast Notification Manager
     */
    class ToastManager {
        constructor() {
            this.container = document.getElementById('toastContainer');
            if (!this.container) {
                this.container = document.createElement('div');
                this.container.id = 'toastContainer';
                this.container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(this.container);
            }
        }

        show(message, type = 'info', duration = 3000) {
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast toast-${type}" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">${this.getTypeTitle(type)}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="إغلاق"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;

            this.container.insertAdjacentHTML('beforeend', toastHtml);

            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: duration
            });

            toast.show();

            // Remove from DOM after hiding
            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });

            return toast;
        }

        getTypeTitle(type) {
            const titles = {
                'success': 'نجح',
                'error': 'خطأ',
                'info': 'معلومات',
                'warning': 'تحذير'
            };
            return titles[type] || 'إشعار';
        }
    }

    /**
     * Product Modal Manager
     */
    class ProductModalManager {
        constructor() {
            this.modal = document.getElementById('productDetailsModal');
            this.wishlistManager = window.wishlistManager;
            this.toastManager = window.toastManager;
            this.init();
        }

        init() {
            if (!this.modal) return;

            // Modal event listeners
            this.modal.addEventListener('show.bs.modal', (event) => {
                const button = event.relatedTarget;
                if (button && button.classList.contains('view-details-btn')) {
                    this.populateModal(button);
                }
            });

            // Modal button event listeners
            document.getElementById('modalCopyLinkBtn')?.addEventListener('click', () => {
                this.copyProductLink();
            });

            document.getElementById('modalWishlistBtn')?.addEventListener('click', () => {
                this.toggleWishlist();
            });

            document.getElementById('modalWhatsAppBtn')?.addEventListener('click', () => {
                this.shareViaWhatsApp();
            });
        }

        populateModal(button) {
            const productCard = button.closest('.product-card');
            if (!productCard) return;

            const productData = {
                id: productCard.dataset.productId,
                name: productCard.dataset.productName,
                price: parseFloat(productCard.dataset.productPrice),
                sale_price: productCard.dataset.productSalePrice ? parseFloat(productCard.dataset.productSalePrice) : null,
                image: productCard.dataset.productImage,
                description: productCard.dataset.productDescription,
                url: productCard.dataset.productUrl,
                brand: productCard.dataset.productBrand
            };

            // Populate modal content
            document.getElementById('modalProductImage').src = productData.image;
            document.getElementById('modalProductImage').alt = productData.name;
            document.getElementById('modalProductName').textContent = productData.name;
            document.getElementById('modalProductBrand').textContent = productData.brand || '';
            document.getElementById('modalProductDescription').textContent = productData.description || 'لا يوجد وصف متاح';
            document.getElementById('modalProductLink').href = productData.url;

            // Populate price
            const priceElement = document.getElementById('modalProductPrice');
            if (productData.sale_price && productData.sale_price < productData.price) {
                priceElement.innerHTML = `
                    <span class="original-price">$${productData.price.toFixed(2)}</span>
                    <span class="sale-price">$${productData.sale_price.toFixed(2)}</span>
                `;
            } else {
                priceElement.innerHTML = `<span class="sale-price">$${productData.price.toFixed(2)}</span>`;
            }

            // Update wishlist button state
            const wishlistBtn = document.getElementById('modalWishlistBtn');
            if (this.wishlistManager.isInWishlist(productData.id)) {
                wishlistBtn.classList.add('in-wishlist');
                wishlistBtn.innerHTML = '<i class="fas fa-heart me-2"></i>إزالة من القائمة';
            } else {
                wishlistBtn.classList.remove('in-wishlist');
                wishlistBtn.innerHTML = '<i class="fas fa-heart me-2"></i>إضافة لليست';
            }

            // Store current product data
            this.currentProduct = productData;
        }

        copyProductLink() {
            if (!this.currentProduct) return;

            navigator.clipboard.writeText(this.currentProduct.url).then(() => {
                this.toastManager.show('تم نسخ رابط المنتج بنجاح!', 'success');
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = this.currentProduct.url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                this.toastManager.show('تم نسخ رابط المنتج بنجاح!', 'success');
            });
        }

        toggleWishlist() {
            if (!this.currentProduct) return;

            const isInWishlist = this.wishlistManager.isInWishlist(this.currentProduct.id);

            if (isInWishlist) {
                this.wishlistManager.removeItem(this.currentProduct.id);
                this.toastManager.show('تم إزالة المنتج من قائمة الأمنيات', 'info');
            } else {
                this.wishlistManager.addItem(this.currentProduct);
                this.toastManager.show('تم إضافة المنتج لقائمة الأمنيات!', 'success');
            }

            // Update button state
            this.populateModal({ closest: () => document.querySelector(`[data-product-id="${this.currentProduct.id}"]`) });

            // Update all wishlist buttons
            this.wishlistManager.updateWishlistButtonStates();
        }

        shareViaWhatsApp() {
            if (!this.currentProduct) return;

            const message = this.buildWhatsAppMessage();
            const whatsappUrl = `https://wa.me/${SITE_WHATSAPP_NUMBER}/?text=${encodeURIComponent(message)}`;

            window.open(whatsappUrl, '_blank');
        }

        buildWhatsAppMessage() {
            const product = this.currentProduct;
            const price = product.sale_price && product.sale_price < product.price
                ? product.sale_price
                : product.price;

            return `مرحباً! أريد الاستفسار عن هذا المنتج:

🏷️ ${product.name}
${product.brand ? `🏢 ${product.brand}` : ''}
💰 السعر: $${price.toFixed(2)}
🔗 الرابط: ${product.url}

هل يمكنكم مساعدتي؟`;
        }
    }

    /**
     * Copy Link Manager
     */
    class CopyLinkManager {
        constructor() {
            this.toastManager = window.toastManager;
            this.init();
        }

        init() {
            document.addEventListener('click', (event) => {
                if (event.target.closest('.copy-link-btn')) {
                    event.preventDefault();
                    this.copyLink(event.target.closest('.copy-link-btn'));
                }
            });
        }

        copyLink(button) {
            const url = button.dataset.productUrl;
            if (!url) return;

            navigator.clipboard.writeText(url).then(() => {
                this.toastManager.show('تم نسخ رابط المنتج بنجاح!', 'success');
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                this.toastManager.show('تم نسخ رابط المنتج بنجاح!', 'success');
            });
        }
    }

    /**
     * Wishlist Button Manager
     */
    class WishlistButtonManager {
        constructor() {
            this.wishlistManager = window.wishlistManager;
            this.toastManager = window.toastManager;
            this.init();
        }

        init() {
            document.addEventListener('click', (event) => {
                if (event.target.closest('.wishlist-btn')) {
                    event.preventDefault();
                    this.toggleWishlist(event.target.closest('.wishlist-btn'));
                }
            });
        }

        toggleWishlist(button) {
            const productId = parseInt(button.dataset.productId);
            const productCard = button.closest('.product-card');

            if (!productCard) return;

            const productData = {
                id: productId,
                name: productCard.dataset.productName,
                price: parseFloat(productCard.dataset.productPrice),
                sale_price: productCard.dataset.productSalePrice ? parseFloat(productCard.dataset.productSalePrice) : null,
                image: productCard.dataset.productImage,
                url: productCard.dataset.productUrl,
                brand: productCard.dataset.productBrand
            };

            const isInWishlist = this.wishlistManager.isInWishlist(productId);

            if (isInWishlist) {
                this.wishlistManager.removeItem(productId);
                this.toastManager.show('تم إزالة المنتج من قائمة الأمنيات', 'info');
            } else {
                this.wishlistManager.addItem(productData);
                this.toastManager.show('تم إضافة المنتج لقائمة الأمنيات! <a href="/wishlist" style="color: white; text-decoration: underline;">عرض القائمة</a>', 'success');
            }

            // Update button state
            this.wishlistManager.updateWishlistButtonStates();
        }
    }

    /**
     * Initialize Enhanced Product Cards
     */
    function init() {
        // Initialize managers
        window.wishlistManager = new WishlistManager();
        window.toastManager = new ToastManager();
        window.productModalManager = new ProductModalManager();
        window.copyLinkManager = new CopyLinkManager();
        window.wishlistButtonManager = new WishlistButtonManager();

        // Add loading states to buttons
        document.addEventListener('click', (event) => {
            const button = event.target.closest('.product-action-btn');
            if (button) {
                button.classList.add('loading');
                setTimeout(() => {
                    button.classList.remove('loading');
                }, 1000);
            }
        });

        console.log('Enhanced Product Cards initialized successfully!');
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Export for external use
    window.EnhancedProductCards = {
        WishlistManager,
        ToastManager,
        ProductModalManager,
        CopyLinkManager,
        WishlistButtonManager,
        SITE_WHATSAPP_NUMBER,
        WISHLIST_STORAGE_KEY
    };

})();
