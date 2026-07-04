
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('viewer', {
    open: false,
    index: 0,
    images: [],
    touchStartX: 0,
    get current() { return this.images[this.index] || {}; },
    show(index) {
        this.index = index;
        this.open = true;
        document.body.style.overflow = 'hidden';
    },
    close() {
        this.open = false;
        document.body.style.overflow = '';
    },
    prev() { if (this.index > 0) this.index--; },
    next() { if (this.index < this.images.length - 1) this.index++; },
});

Alpine.start();
