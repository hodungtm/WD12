// ===== CẢI THIỆN SIDEBAR ADMIN =====

document.addEventListener('DOMContentLoaded', function() {
    // Cải thiện focus state cho sidebar
    const sidebarItems = document.querySelectorAll('.section-menu-left .menu-item a');
    
    sidebarItems.forEach(item => {
        // Thêm focus event listener
        item.addEventListener('focus', function() {
            this.closest('.menu-item').classList.add('focused');
        });
        
        // Thêm blur event listener
        item.addEventListener('blur', function() {
            this.closest('.menu-item').classList.remove('focused');
        });
        
        // Thêm keyboard navigation
        item.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
    
    // Cải thiện active state cho current page (không override logic cũ)
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('.section-menu-left .menu-item');
    
    menuItems.forEach(item => {
        const link = item.querySelector('a');
        if (link && link.href) {
            const linkPath = new URL(link.href).pathname;
            
            // Kiểm tra nếu current path match với menu item
            if (currentPath === linkPath || 
                (currentPath.startsWith(linkPath) && linkPath !== '/') ||
                (linkPath.includes('dashboard') && currentPath.includes('dashboard'))) {
                
                // Chỉ thêm class nếu chưa có
                if (!item.classList.contains('active')) {
                    item.classList.add('active');
                }
                
                // Nếu là submenu item, active parent menu
                if (item.classList.contains('sub-menu-item')) {
                    const parentMenu = item.closest('.menu-item.has-children');
                    if (parentMenu && !parentMenu.classList.contains('active')) {
                        parentMenu.classList.add('active');
                    }
                }
            }
        }
    });
    
    // Cải thiện hover effects (không override click events)
    menuItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateX(4px)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
    
    // KHÔNG override submenu toggle - để main.js xử lý
    // Chỉ thêm accessibility improvements
    
    // Cải thiện accessibility
    const allMenuLinks = document.querySelectorAll('.section-menu-left a');
    
    allMenuLinks.forEach(link => {
        // Thêm aria-label nếu chưa có
        if (!link.getAttribute('aria-label')) {
            const text = link.querySelector('.text');
            if (text) {
                link.setAttribute('aria-label', text.textContent.trim());
            }
        }
        
        // Thêm role cho menu items
        if (link.closest('.menu-item')) {
            link.setAttribute('role', 'menuitem');
        }
    });
    
    // Thêm role cho menu container
    const menuContainer = document.querySelector('.section-menu-left .center-item ul');
    if (menuContainer) {
        menuContainer.setAttribute('role', 'menu');
    }
    
    // Cải thiện keyboard navigation
    let currentFocusIndex = -1;
    
    document.addEventListener('keydown', function(e) {
        const focusableItems = Array.from(sidebarItems);
        
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();
            
            if (currentFocusIndex === -1) {
                currentFocusIndex = 0;
            } else if (e.key === 'ArrowDown') {
                currentFocusIndex = (currentFocusIndex + 1) % focusableItems.length;
            } else {
                currentFocusIndex = (currentFocusIndex - 1 + focusableItems.length) % focusableItems.length;
            }
            
            focusableItems[currentFocusIndex].focus();
        }
    });
    
    // Cải thiện visual feedback
    sidebarItems.forEach(item => {
        item.addEventListener('focus', function() {
            // Thêm visual indicator
            this.style.outline = '2px solid #1abc9c';
            this.style.outlineOffset = '2px';
        });
        
        item.addEventListener('blur', function() {
            this.style.outline = '';
            this.style.outlineOffset = '';
        });
    });
    
    // Cải thiện responsive behavior
    function handleResponsiveSidebar() {
        const sidebar = document.querySelector('.section-menu-left');
        const content = document.querySelector('.section-content-right');
        
        if (window.innerWidth <= 768) {
            // Mobile behavior - chỉ thêm auto-close cho submenu items
            const submenuItems = document.querySelectorAll('.sub-menu-item a');
            submenuItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Auto-close sidebar on mobile after click submenu item
                    setTimeout(() => {
                        const toggleBtn = document.querySelector('.button-show-hide');
                        if (toggleBtn) {
                            toggleBtn.click();
                        }
                    }, 300);
                });
            });
        }
    }
    
    // Call on load and resize
    handleResponsiveSidebar();
    window.addEventListener('resize', handleResponsiveSidebar);
    
    // Cải thiện loading states
    const loadingStates = document.querySelectorAll('[data-loading]');
    
    loadingStates.forEach(element => {
        element.addEventListener('click', function() {
            if (!this.classList.contains('loading')) {
                this.classList.add('loading');
                this.setAttribute('disabled', 'disabled');
                
                // Remove loading state after operation completes
                setTimeout(() => {
                    this.classList.remove('loading');
                    this.removeAttribute('disabled');
                }, 2000);
            }
        });
    });
    
    // Cải thiện smooth scrolling cho submenu
    const submenus = document.querySelectorAll('.sub-menu');
    
    submenus.forEach(submenu => {
        if (submenu.scrollHeight > submenu.clientHeight) {
            submenu.style.overflowY = 'auto';
            submenu.style.scrollbarWidth = 'thin';
            submenu.style.scrollbarColor = '#1abc9c #f8f9fa';
        }
    });
    
    // Cải thiện performance với debouncing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Debounced resize handler
    const debouncedResize = debounce(handleResponsiveSidebar, 250);
    window.addEventListener('resize', debouncedResize);
    
    // Cải thiện error handling
    window.addEventListener('error', function(e) {
        console.error('Sidebar error:', e.error);
        // Fallback behavior
        const fallbackMenu = document.querySelector('.section-menu-left');
        if (fallbackMenu) {
            fallbackMenu.style.display = 'block';
        }
    });
});

// ===== CẢI THIỆN ALERT SYSTEM =====

// Cải thiện alert positioning và stacking
function improveAlertSystem() {
    const alertStack = document.getElementById('alert-stack');
    
    if (alertStack) {
        // Cải thiện z-index management
        alertStack.style.zIndex = '9999';
        
        // Cải thiện stacking order
        const alerts = alertStack.querySelectorAll('.custom-alert');
        alerts.forEach((alert, index) => {
            alert.style.zIndex = 9999 + index;
        });
        
        // Cải thiện responsive behavior
        if (window.innerWidth <= 768) {
            alertStack.style.top = '16px';
            alertStack.style.right = '16px';
            alertStack.style.left = '16px';
            alertStack.style.minWidth = 'auto';
            alertStack.style.maxWidth = 'none';
        } else {
            alertStack.style.top = '32px';
            alertStack.style.right = '32px';
            alertStack.style.left = 'auto';
            alertStack.style.minWidth = '320px';
            alertStack.style.maxWidth = '420px';
        }
    }
}

// Call on load and resize
document.addEventListener('DOMContentLoaded', improveAlertSystem);
window.addEventListener('resize', improveAlertSystem);

// Cải thiện alert animation
function enhanceAlertAnimation() {
    const alerts = document.querySelectorAll('.custom-alert');
    
    alerts.forEach(alert => {
        // Thêm entrance animation
        alert.style.animation = 'slideInDown 0.7s cubic-bezier(.68, -0.55, .27, 1.55)';
        
        // Thêm exit animation
        alert.addEventListener('click', function(e) {
            if (e.target.classList.contains('close')) {
                this.style.animation = 'slideOutUp 0.5s ease-in-out';
                setTimeout(() => {
                    this.remove();
                }, 500);
            }
        });
    });
}

// Call on DOM changes
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.type === 'childList') {
            enhanceAlertAnimation();
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const alertStack = document.getElementById('alert-stack');
    if (alertStack) {
        observer.observe(alertStack, { childList: true });
    }
}); 