import './user';
import './outlet_link';
import Swal from 'sweetalert2';

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

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
}

const sync_data = document.getElementById('sync-data');

if (sync_data) {
    sync_data.addEventListener('click', async () => {
        sync_data.innerText = 'Memuat...';
        
        try {
            const response = await fetch('/api/tautan-outlet/sinkronisasi');

            const result = await response.json();

            Swal.fire({
                icon: result.status,
                title: result.message,
                showConfirmButton: true
            });                

            sync_data.innerText = 'Sinkronisasi Data';
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                showConfirmButton: true
            });

            sync_data.innerText = 'Sinkronisasi Data';
        }

        
    });
}