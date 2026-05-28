import DataTable from 'datatables.net-dt';
import Chart from 'chart.js/auto';
DataTable.ext.errMode = 'none';
import Swal from 'sweetalert2';

const uuid_outlet_value = document.getElementById('outlet-links-table')?.dataset.outletUuid ?? null;

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
}

if (document.getElementById('outlets-table')) {
    // Outlets datatable
    const outlets_table = new DataTable('#outlets-table', {
        ajax: '/api/tautan-outlet',
        columns: [
            {
                data: null,
                render: (data, type, row, meta) => meta.row + 1
            },
            {
                data: null,
                render: (data, type, row) => {
                    return `
                        <a href="/${row.slug}">outlet-linktree/${row.slug}</a>
                    `;
                }
            },
            {
                data: 'nama'
            },
            {
                data: 'alamat',
            },
            {
                data: 'no_hp',
            },
            {
                data: null,
                render: (data, type, row) => {
                    return `
                        <a href="/tautan-outlet/${row.id}" class="inline-block px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300">Kelola</a>
                    `;
                }
            }
        ]
    });

    // SYNC OUTLET
    const sync_outlet_btn = document.getElementById('sync-outlet-btn');

    if (sync_outlet_btn) {
        sync_outlet_btn.addEventListener('click', async function (e) {
            e.preventDefault();

            sync_outlet_btn.innerText = 'Memuat...';

            try {
                const response = await fetch('/api/tautan-outlet/sinkronisasi');

                const result = await response.json();

                Swal.fire({
                    icon: result.status,
                    title: result.message,
                    showConfirmButton: true
                });                

                sync_outlet_btn.innerText = 'Sinkronisasi Diagram';
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    showConfirmButton: true
                });

                sync_outlet_btn.innerText = 'Sinkronisasi Diagram';
            }

            outlets_table.ajax.reload();
        });
    }
}

if (document.getElementById('outlet-links-table')) {
    // Outlet links datatable
    const outlet_uuid = uuid_outlet_value;
    
    const outlet_links_table = new DataTable('#outlet-links-table', {
        ajax: '/api/tautan-outlet/' + outlet_uuid,
        columns: [
            {
                data: null,
                render: (data, type, row, meta) => meta.row + 1
            },
            {
                data: 'title'
            },
            {
                data: null,
                render: (data, type, row) => {
                    return `
                        <a href="${row.link}" target="_blank">${row.link}</a>
                    `;
                }
            },
            {
                data: 'alamat',
            },
            {
                data: null,
                render: (data, type, row) => {
                    return `
                        <div class="flex space-x-2">
                            <button
                                type="button"
                                class="inline-block px-3 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-300 edit-outlet-link-btn"
                                data-uuid="${row.uuid}"
                                data-title="${row.title}"
                                data-link="${row.link}"
                            >
                                Edit
                            </button>
    
                            <button 
                                data-uuid="${row.uuid}"
                                type="submit"
                                class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300 delete-outlet-link-btn"
                            >
                                Hapus
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });

    // Create outlet links
    const create_outlet_link_form = document.getElementById('create-outlet-link-form');
    const create_outlet_link_modal = document.getElementById('create-outlet-link-modal');

    if (create_outlet_link_form) {
        create_outlet_link_form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const outlet_link_form_data = new FormData(create_outlet_link_form);

            try {
                const response = await fetch('/api/tautan-outlet', {
                    method: 'POST',
                    body: outlet_link_form_data
                });

                const response_data = await response.json();

                Swal.fire({
                    icon: response_data.status,
                    title: response_data.message,
                    showConfirmButton: true
                });

                if (response_data.status === 'success') {
                    outlet_links_table.ajax.reload();
                    create_outlet_link_modal.classList.add('hidden');
                    create_outlet_link_form.reset(); 
                    refreshChart();
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    showConfirmButton: true
                });
            }
        });
    }

    // Edit outlet links
    document.addEventListener('click', function (e) {
        const edit_outlet_link_btn = e.target.closest('.edit-outlet-link-btn');

        if (edit_outlet_link_btn) {
            document.getElementById('edit-uuid').value = edit_outlet_link_btn.dataset.uuid;
            document.getElementById('edit-title').value = edit_outlet_link_btn.dataset.title;
            document.getElementById('edit-link').value = edit_outlet_link_btn.dataset.link;
            document.getElementById('edit-outlet-link-modal').classList.remove('hidden');
        }
    });

    const edit_outlet_link_form = document.getElementById('edit-outlet-link-form');
    const edit_outlet_link_modal = document.getElementById('edit-outlet-link-modal');

    if (edit_outlet_link_form) {
        edit_outlet_link_form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const outlet_link_form_data = new FormData(edit_outlet_link_form);

            try {
                const response = await fetch(`/api/tautan-outlet/${outlet_link_form_data.get('uuid')}`, {
                    method: 'PUT',
                    body: outlet_link_form_data
                });

                const response_data = await response.json();

                Swal.fire({
                    icon: response_data.status,
                    title: response_data.message,
                    showConfirmButton: true
                });

                if (response_data.status === 'success') {
                    outlet_links_table.ajax.reload();
                    edit_outlet_link_modal.classList.add('hidden');
                    edit_outlet_link_form.reset();
                    refreshChart();
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    showConfirmButton: true
                });
            }
        });
    }

    // Delete outlet links
    document.addEventListener('click', async function (e) {
        const delete_outlet_link_btn = e.target.closest('.delete-outlet-link-btn');

        if (!delete_outlet_link_btn) return;

        e.preventDefault();

        const uuid_outlet_link_value = delete_outlet_link_btn.dataset.uuid;

        const result_prompt = await Swal.fire({
            title: "Anda Yakin?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak"
        });

        if (!result_prompt.isConfirmed) return;

        try {
            const response = await fetch(`/api/tautan-outlet/${uuid_outlet_link_value}`, {
                method: 'DELETE',
                headers: {
                    'X-XSRF-TOKEN': getCookie('XSRF-TOKEN')
                }
            });

            const result = await response.json();

            Swal.fire({
                icon: result.status,
                title: result.message,
                showConfirmButton: true
            });

            outlet_links_table.ajax.reload();

            refreshChart();
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: error,
                showConfirmButton: true
            });
        }
    }); 
}















const store_click_array = document.querySelectorAll('.store-click');

store_click_array.forEach(store_click => {
    store_click.addEventListener('click', async function () {
        const uuid_outlet_link_value = store_click.dataset.uuid;

        try {
            const response = await fetch(`/api/tautan-outlet/store-click`, {
                method: 'POST',
                headers: {
                    'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    'uuid_outlet_link': uuid_outlet_link_value
                })
            });

            const result = await response.json();

            // console.log(result);
        } catch (error) {
            // console.log(error);
        }
    });
});

const sync_chart = document.getElementById('sync-chart');
const daily_click_chart = document.getElementById('daily-click-chart');
const top_click_chart = document.getElementById('top-click-chart');
const device_distribute_chart = document.getElementById('device-distribute-chart');

if (sync_chart) {
    sync_chart.addEventListener('click', function () {
        sync_chart.innerText = 'Memuat...';

        try {
            refreshChart();
            
            Swal.fire({
                icon: 'success',
                title: 'Diagram berhasil disinkronkan',
                showConfirmButton: true
            });

            sync_chart.innerText = 'Sinkronisasi';
        } catch (error) {
            sync_chart.innerText = 'Sinkronisasi';
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                showConfirmButton: true
            });
        }
    });
}

let daily_click_chart_instance;
let top_click_chart_instance;
let device_distribute_chart_instance;

async function dailyClickChart() {
    try {
        const response = await fetch(`/api/tautan-outlet/daily-click/${uuid_outlet_value}`);
        const data = await response.json(); 

        const days_data = Object.keys(data.data);
        const clicks_data = Object.values(data.data);

        if (daily_click_chart_instance) {
            daily_click_chart_instance.destroy();
        }
    
        daily_click_chart_instance = new Chart(daily_click_chart, {
            type: 'line',
            data: {
                labels: days_data,
                datasets: [{
                    label: 'Jumlah',
                    data: clicks_data,
                    borderColor: '#dc2626',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false                            
                    }
                }
            }
        });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            showConfirmButton: true
        });
    }
}

async function topClickChart() {
    try {
        const response = await fetch(`/api/tautan-outlet/top-click/${uuid_outlet_value}`);
        const data = await response.json(); 

        const links_data = Object.keys(data.data);
        const clicks_data = Object.values(data.data);

        if (top_click_chart_instance) {
            top_click_chart_instance.destroy();
        }
    
        top_click_chart_instance = new Chart(top_click_chart, {
            type: 'bar',
            data: {
                labels: links_data,
                datasets: [{
                    label: 'Jumlah',
                    data: clicks_data,
                    backgroundColor: '#dc2626',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            showConfirmButton: true
        });
    }
}

async function deviceDistributeChart() {
    try {
        const response = await fetch(`/api/tautan-outlet/distribute-device/${uuid_outlet_value}`);
        const data = await response.json(); 

        if (device_distribute_chart_instance) {
            device_distribute_chart_instance.destroy();
        }

        device_distribute_chart_instance = new Chart(device_distribute_chart, {
            type: 'doughnut',
            data: {
                labels: ['Desktop', 'Mobile', 'Tablet'],
                datasets: [{
                    label: 'Jumlah',
                    data: [data.data.desktop, data.data.mobile, data.data.tablet],
                    backgroundColor: [
                        '#dc2626',
                        '#1f2937',
                        '#f59e0b' 
                    ],
                    hoverOffset: 10,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 30
                        }
                    }
                },
                cutout: '60%'
            }
        });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            showConfirmButton: true
        });
    }
}

function refreshChart() {
    if (daily_click_chart && top_click_chart && device_distribute_chart) {
        dailyClickChart();
        topClickChart();
        deviceDistributeChart();
    }
}

refreshChart();