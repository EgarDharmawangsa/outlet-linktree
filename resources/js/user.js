import DataTable from 'datatables.net-dt';
DataTable.ext.errMode = 'none';
import Swal from 'sweetalert2';

if (document.getElementById('users-table')) {
    // DATATABLE USER
    const users_table = new DataTable('#users-table', {
        ajax: '/api/pengguna',
        columns: [
            {
                data: null,
                render: (data, type, row, meta) => meta.row + 1
            },
            {
                data: 'name'
            },
            {
                data: 'email'
            },
            {
                data: 'is_super_admin',
                render: (data) => data == 1 ? 'Super Admin' : 'Admin'
            },
            {
                data: null,
                render: (data, type, row) => {
                    let deleteButton = '';

                    if (row.is_super_admin != 1) {
                        deleteButton = `
                            <button 
                                data-uuid="${row.uuid}"
                                type="submit"
                                class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300 delete-user-btn"
                            >
                                Hapus
                            </button>
                        `;
                    }

                    return `
                        <div class="flex space-x-2">
                            <button
                                type="button"
                                class="inline-block px-3 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-300 edit-user-btn"
                                data-uuid="${row.uuid}"
                                data-name="${row.name}"
                                data-email="${row.email}"
                            >
                                Edit
                            </button>

                            ${deleteButton}
                        </div>
                    `;
                }
            }
        ]
    });  

    // SYNC USER
    const sync_user_btn = document.getElementById('sync-user-btn');

    if (sync_user_btn) {
        sync_user_btn.addEventListener('click', async function (e) {
            e.preventDefault();

            try {
                const response = await fetch('/api/pengguna/sinkronisasi');

                const result = await response.json();

                console.log(result);

                Swal.fire({
                    icon: result.status,
                    title: result.message,
                    showConfirmButton: true
                });

                users_table.ajax.reload();

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    showConfirmButton: true
                });
            }
        });
    }

    // CREATE USER
    const create_user_from = document.getElementById('create-user-form');
    const create_user_modal = document.getElementById('create-user-modal');

    if (create_user_from) {
        create_user_from.addEventListener('submit', async function(e) {
            e.preventDefault();

            const user_form_data = new FormData(create_user_from);

            try {
                const response = await fetch('/api/pengguna', {
                    method: 'POST',
                    body: user_form_data
                });

                const response_data = await response.json();

                console.log(response_data);

                Swal.fire({
                    icon: response_data.status,
                    title: response_data.message,
                    showConfirmButton: true
                });

                users_table.ajax.reload();

                create_user_modal.classList.toggle('hidden');
                create_user_from.reset(); 
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    showConfirmButton: true
                });
            }
        });
    }

    // EDIT USER
    document.addEventListener('click', function (e) {
        const edit_button = e.target.closest('.edit-user-btn');

        if (edit_button) {
            document.getElementById('edit-uuid').value = edit_button.dataset.uuid;
            document.getElementById('edit-name').value = edit_button.dataset.name;
            document.getElementById('edit-email').value = edit_button.dataset.email;
            document.getElementById('edit-user-modal').classList.remove('hidden');
        }
    });

    const edit_user_form = document.getElementById('edit-user-form');
    const edit_user_modal = document.getElementById('edit-user-modal');
    
    if (edit_user_form) {
        edit_user_form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const user_form_data = new FormData(edit_user_form);

            try {
                const response = await fetch(`/api/pengguna/${user_form_data.get('uuid')}`, {
                    method: 'PUT',
                    body: user_form_data
                });

                const response_data = await response.json();

                Swal.fire({
                    icon: response_data.status,
                    title: response_data.message,
                    showConfirmButton: true
                });

                users_table.ajax.reload();

                edit_user_modal.classList.add('hidden');
                edit_user_form.reset();
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    showConfirmButton: true
                });
            }

        });
    }

    // DELETE USER
    document.addEventListener('click', async function (e) {
        const delete_user_btn = e.target.closest('.delete-user-btn');

        if (!delete_user_btn) return;

        e.preventDefault();

        const uuid = delete_user_btn.dataset.uuid;

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
            const response = await fetch(`/api/pengguna/${uuid}`, {
                method: 'DELETE'
            });

            const result = await response.json();

            Swal.fire({
                icon: result.status,
                title: result.message,
                showConfirmButton: true
            });

            users_table.ajax.reload();
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                showConfirmButton: true
            });
        }
    });  
}