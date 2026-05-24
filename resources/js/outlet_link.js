import DataTable from 'datatables.net-dt';
import Chart from 'chart.js/auto';
DataTable.ext.errMode = 'none';
import Swal from 'sweetalert2';

const outlet_uuid_value = document.getElementById('outlet-links-table')?.dataset.outletUuid ?? null;

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

            try {
                const response = await fetch('/api/tautan-outlet/sinkronisasi');

                const result = await response.json();

                Swal.fire({
                    icon: result.status,
                    title: result.message,
                    showConfirmButton: true
                });                
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    showConfirmButton: true
                });
            }

            outlets_table.ajax.reload();
        });
    }
}

if (document.getElementById('outlet-links-table')) {
    // Outlet links datatable
    const outlet_uuid = outlet_uuid_value;
    
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
                                data-id="${row.id}"
                                data-title="${row.title}"
                                data-link="${row.link}"
                            >
                                Edit
                            </button>
    
                            <button 
                                data-id="${row.id}"
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

                create_outlet_link_modal.classList.add('hidden');
                create_outlet_link_form.reset(); 
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    showConfirmButton: true
                });
            }

            outlet_links_table.ajax.reload();
        });
    }

    // Edit outlet links
    document.addEventListener('click', function (e) {
        const edit_outlet_link_btn = e.target.closest('.edit-outlet-link-btn');

        if (edit_outlet_link_btn) {
            document.getElementById('edit-id').value = edit_outlet_link_btn.dataset.id;
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
                const response = await fetch(`/api/tautan-outlet/${outlet_link_form_data.get('id')}`, {
                    method: 'PUT',
                    body: outlet_link_form_data
                });

                const response_data = await response.json();

                Swal.fire({
                    icon: response_data.status,
                    title: response_data.message,
                    showConfirmButton: true
                });

                outlet_links_table.ajax.reload();

                edit_outlet_link_modal.classList.add('hidden');
                edit_outlet_link_form.reset();
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

        const id = delete_outlet_link_btn.dataset.id;

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
            const response = await fetch(`/api/tautan-outlet/${id}`, {
                method: 'DELETE'
            });

            const result = await response.json();

            Swal.fire({
                icon: result.status,
                title: result.message,
                showConfirmButton: true
            });

            outlet_links_table.ajax.reload();
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                showConfirmButton: true
            });
        }
    }); 
}

const daily_click_chart = document.getElementById('daily-click-chart');
const top_click_chart = document.getElementById('top-click-chart');
const device_distribute_chart = document.getElementById('device-distribute-chart');

if (daily_click_chart && top_click_chart && device_distribute_chart) {
    function dailyClickChart() {
        const labels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
        const dataValues = [12, 19, 3, 5, 2, 15];

        let daily_click_chart_instance;

        if (daily_click_chart_instance) {
            daily_click_chart_instance.destroy();
        }
    
        daily_click_chart_instance = new Chart(daily_click_chart, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Unit Terjual',
                    data: dataValues,
                    borderColor: '#dc2626', // Blue-500
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Izinkan chart berubah proporsi sesuai tinggi container
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    }

    function topClickChart() {
        const topLabels = ['WhatsApp', 'Instagram', 'Catalog', 'Website', 'Location'];
        const topDataValues = [120, 95, 70, 45, 30];

        let top_click_chart_instance;

        if (top_click_chart_instance) {
            top_click_chart_instance.destroy();
        }
    
        top_click_chart_instance = new Chart(top_click_chart, {
            type: 'bar',
            data: {
                labels: topLabels,
                datasets: [{
                    label: 'Jumlah Klik',
                    data: topDataValues,
                    backgroundColor: '#dc2626',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', // KUNCI: Membuat bar menjadi horizontal
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan legenda jika hanya satu dataset
                    }
                }
            }
        });
    }

    async function deviceDistributeChart() {
        try {
            const response = await fetch(`/api/tautan-outlet/distribute-device/${outlet_uuid_value}`);
            const data = await response.json(); 

            console.log(data);    

            let device_distribute_chart_instance;

            if (device_distribute_chart_instance) {
                device_distribute_chart_instance.destroy();
            }

            device_distribute_chart_instance = new Chart(device_distribute_chart, {
                type: 'doughnut',
                data: {
                    labels: ['Desktop', 'Mobile', 'Tablet'],
                    datasets: [{
                        label: 'Distribusi Perangkat',
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
        dailyClickChart();
        topClickChart();
        deviceDistributeChart();
    }

    refreshChart();
}