import './user';
import './outlet_link';

const sidebar_toggle = document.getElementById('sidebar-toggle');const close_sidebar = document.getElementById('close-sidebar');
const sidebar = document.getElementById('sidebar');
const toast = document.getElementById('success-toast') || document.getElementById('error-toast');

if (sidebar_toggle && close_sidebar) {
    [sidebar_toggle, close_sidebar].forEach(button => {
        button.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    });
}

if (toast) {
    setTimeout(() => {
        toast.classList.toggle('hidden');
    }, 5000);
}