// ===== DEBUG SIDEBAR FUNCTIONALITY =====

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Debug: Sidebar improvements loaded');
    
    // Kiểm tra main.js đã load chưa
    if (typeof $ !== 'undefined') {
        console.log('✅ jQuery loaded');
    } else {
        console.log('❌ jQuery not loaded');
    }
    
    // Kiểm tra menu items
    const menuItems = document.querySelectorAll('.section-menu-left .menu-item');
    console.log(`📊 Found ${menuItems.length} menu items`);
    
    // Kiểm tra submenu items
    const submenuItems = document.querySelectorAll('.section-menu-left .sub-menu-item');
    console.log(`📊 Found ${submenuItems.length} submenu items`);
    
    // Kiểm tra click events
    const hasChildrenItems = document.querySelectorAll('.section-menu-left .menu-item.has-children');
    hasChildrenItems.forEach((item, index) => {
        const link = item.querySelector('a');
        if (link) {
            console.log(`🔗 Menu item ${index + 1}: ${link.textContent.trim()}`);
            
            // Kiểm tra event listeners
            const events = getEventListeners(link);
            console.log(`📝 Event listeners for item ${index + 1}:`, events);
        }
    });
    
    // Test submenu toggle
    setTimeout(() => {
        console.log('🧪 Testing submenu functionality...');
        
        // Kiểm tra submenu visibility
        const submenus = document.querySelectorAll('.section-menu-left .sub-menu');
        submenus.forEach((submenu, index) => {
            const isVisible = submenu.style.display !== 'none' && submenu.offsetHeight > 0;
            console.log(`📋 Submenu ${index + 1} visible: ${isVisible}`);
        });
    }, 1000);
});

// Helper function để kiểm tra event listeners (nếu có)
function getEventListeners(element) {
    if (typeof $ !== 'undefined' && $.fn.jquery) {
        return $._data(element, 'events');
    }
    return 'jQuery not available for event inspection';
}

// Monitor DOM changes
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.type === 'childList') {
            console.log('🔄 DOM changed:', mutation);
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.section-menu-left');
    if (sidebar) {
        observer.observe(sidebar, { 
            childList: true, 
            subtree: true,
            attributes: true,
            attributeFilter: ['class', 'style']
        });
        console.log('👀 Monitoring sidebar changes...');
    }
}); 