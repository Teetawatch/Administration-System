import './bootstrap';

import Alpine from 'alpinejs';

// ตั้งค่า Alpine Store สำหรับ Sidebar
Alpine.store('sidebar', {
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    }
});

window.Alpine = Alpine;

Alpine.start();
