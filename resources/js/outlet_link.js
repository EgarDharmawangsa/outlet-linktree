import DataTable from 'datatables.net-dt';
DataTable.ext.errMode = 'none';
import Swal from 'sweetalert2';

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
    const outlet_uuid = document.getElementById('outlet-links-table').dataset.outletUuid;
    
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
                data: 'link'
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