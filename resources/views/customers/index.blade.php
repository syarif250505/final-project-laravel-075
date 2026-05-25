@extends('layouts.app')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center px-8">
    <h1 class="text-xl font-semibold text-gray-800">Customers</h1>
</header>

<div class="p-8 flex-1 overflow-y-auto">
    <div class="flex justify-end mb-6">
        <button onclick="openModal()" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 flex items-center text-sm font-medium transition-colors">
            <span class="mr-2">+</span> Add Data
        </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg pb-10">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-gray-600 text-left">
                <tr>
                    <th class="px-6 py-4 font-medium">Customer ID</th>
                    <th class="px-6 py-4 font-medium">Customer Name</th>
                    <th class="px-6 py-4 font-medium">Email</th>
                    <th class="px-6 py-4 font-medium">Address</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Action</th>
                </tr>
            </thead>
            <tbody id="customer-table-body" class="divide-y divide-gray-200 text-gray-700">
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center">Loading data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="addCustomerModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-[100] flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-lg p-8 relative shadow-2xl">
        <h2 id="modalTitle" class="text-2xl font-bold text-center mb-6 text-gray-800">Add Customer</h2>

        <form id="addCustomerForm" onsubmit="submitCustomer(event)">
            <input type="hidden" id="edit_id">

            <div class="mb-4">
                <label class="block text-gray-800 font-bold mb-2 text-sm">Customer ID</label>
                <input type="text" id="customer_id" class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50" placeholder="Enter your ID" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-800 font-bold mb-2 text-sm">Customer Name</label>
                <input type="text" id="name" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-200 bg-gray-50 placeholder-gray-400" placeholder="Enter your name" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-800 font-bold mb-2 text-sm">Email</label>
                <input type="email" id="email" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-200 bg-gray-50 placeholder-gray-400" placeholder="Enter your email" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-800 font-bold mb-2 text-sm">Address</label>
                <input type="text" id="address" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-200 bg-gray-50 placeholder-gray-400" placeholder="Enter your address">
            </div>

            <div class="mb-8">
                <label class="block text-gray-800 font-bold mb-2 text-sm">Status</label>
                <div class="relative">
                    <select id="status" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-200 bg-gray-50 text-gray-600 appearance-none" required>
                        <option value="" disabled selected>Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800 font-medium transition-colors">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // --- VARIABEL GLOBAL ---
    let currentOpenDropdown = null;

    // --- INISIALISASI ---
    document.addEventListener('DOMContentLoaded', function() {
        fetchCustomers();
    });

    // --- FUNGSI MENGAMBIL & MENAMPILKAN DATA TABEL ---
    function fetchCustomers() {
        fetch('/api/customers')
            .then(response => response.json())
            .then(result => {
                const tbody = document.getElementById('customer-table-body');
                tbody.innerHTML = '';

                if (result.success && result.data.length > 0) {
                    result.data.forEach(customer => {
                        // 1. Tentukan status badge
                        const isStatusActive = (customer.status == 1 || customer.status == true || customer.status == 'active');
                        const statusBadge = isStatusActive ?
                            `<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Active</span>` :
                            `<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">Inactive</span>`;

                        // 2. Tentukan tombol status yang muncul (Logika Dinamis)
                        const statusButton = isStatusActive ?
                            `<button onclick="updateStatus(${customer.id}, 0)" class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                 <svg class="mr-3 w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z M3 3l18 18"></path></svg>
                                 Deactivate</button>` :
                            `<button onclick="updateStatus(${customer.id}, 1)" class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                 <svg class="mr-3 w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                 Active</button>`;

                        // 3. Gabungkan dalam Row
                        const row = `
                            <tr class="hover:bg-gray-50 relative">
                                <td class="px-6 py-4">${customer.customer_id}</td>
                                <td class="px-6 py-4">${customer.name}</td>
                                <td class="px-6 py-4">${customer.email}</td>
                                <td class="px-6 py-4 truncate max-w-xs">${customer.address || '-'}</td>
                                <td class="px-6 py-4">${statusBadge}</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-gray-500 hover:text-gray-900 focus:outline-none" onclick="toggleMenu(event, ${customer.id})">
                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    </button>
                                    <div id="dropdown-${customer.id}" class="hidden absolute right-6 top-12 w-36 bg-white border border-gray-100 rounded-lg shadow-xl z-50 text-left py-2">
                                        ${statusButton}
                                        <button onclick="actionEdit(event, ${customer.id})" class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg class="mr-3 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit
                                        </button>
                                        <button onclick="actionDelete(${customer.id})" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="mr-3 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data customer</td></tr>';
                }
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    // --- FUNGSI DROPDOWN MENU ---
    function toggleMenu(event, id) {
        event.stopPropagation();
        const dropdown = document.getElementById(`dropdown-${id}`);

        if (currentOpenDropdown && currentOpenDropdown !== dropdown) {
            currentOpenDropdown.classList.add('hidden');
        }

        dropdown.classList.toggle('hidden');

        if (!dropdown.classList.contains('hidden')) {
            currentOpenDropdown = dropdown;
        } else {
            currentOpenDropdown = null;
        }
    }

    document.addEventListener('click', function() {
        if (currentOpenDropdown) {
            currentOpenDropdown.classList.add('hidden');
            currentOpenDropdown = null;
        }
    });

    // --- FUNGSI MODAL ADD DATA ---
    function openModal() {
        document.getElementById('modalTitle').innerText = 'Add Customer';
        document.getElementById('customer_id').readOnly = false;
        document.getElementById('edit_id').value = '';
        document.getElementById('addCustomerModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('addCustomerModal').classList.add('hidden');
        document.getElementById('addCustomerForm').reset();
        document.getElementById('customer_id').readOnly = false;
        document.getElementById('status').selectedIndex = 0;
    }

    function submitCustomer(event) {
        event.preventDefault();

        const editId = document.getElementById('edit_id').value;
        const data = {
            customer_id: document.getElementById('customer_id').value,
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            address: document.getElementById('address').value,
            status: document.getElementById('status').value === '1' ? 1 : 0
        };

        const url = editId ? `/api/customers/${editId}` : '/api/customers';
        const method = editId ? 'PATCH' : 'POST';

        fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    closeModal();
                    fetchCustomers();
                    alert(editId ? 'Success: Data customer berhasil diupdate!' : 'Success: Data customer berhasil ditambahkan!');
                } else {
                    console.log(result.errors || result.message);
                    alert(editId ? 'Gagal mengupdate data. Periksa kembali inputan.' : 'Gagal menambahkan data. Periksa kembali inputan kamu.');
                }
            })
            .catch(error => {
                console.error('Error submitting data:', error);
                alert('Terjadi kesalahan pada server saat menyimpan data.');
            });
    }

    // Hapus semua fungsi updateStatus lama dan ganti dengan ini:
    function updateStatus(id, status) {
        const endpoint = status === 1 ? `/api/customers/${id}/activate` : `/api/customers/${id}/deactivate`;

        fetch(endpoint, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(result => {
                console.log("Respons:", result);
                // Refresh tabel agar status berubah di tampilan
                fetchCustomers();
            })
            .catch(err => {
                console.error("Error:", err);
                alert('Gagal mengubah status. Cek Console (F12).');
            });
    }

    function actionEdit(event, id) {
        event.stopPropagation();

        const row = event.target.closest('tr');
        if (!row) return;

        document.getElementById('modalTitle').innerText = 'Edit Customer';
        document.getElementById('edit_id').value = id;
        document.getElementById('customer_id').value = row.cells[0].innerText;
        document.getElementById('name').value = row.cells[1].innerText;
        document.getElementById('email').value = row.cells[2].innerText;
        document.getElementById('address').value = row.cells[3].innerText;

        const statusText = row.cells[4].innerText.trim().toLowerCase();
        document.getElementById('status').value = statusText === 'active' ? '1' : '0';
        document.getElementById('customer_id').readOnly = true;

        document.getElementById('addCustomerModal').classList.remove('hidden');
    }

    // --- FUNGSI DELETE (Untuk Customers) ---
    function actionDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            // Pastikan URL mengarah ke /api/customers
            fetch(`/api/customers/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(result => {
                    fetchCustomers(); // Panggil fetchCustomers (bukan fetchServices)
                })
                .catch(err => console.error(err));
        }
    }
</script>
@endpush