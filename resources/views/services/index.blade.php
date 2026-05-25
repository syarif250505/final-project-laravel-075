@extends('layouts.app')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center px-8">
    <h1 class="text-xl font-semibold text-gray-800">Services</h1>
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
                    <th class="px-6 py-4 font-medium">Service Name</th>
                    <th class="px-6 py-4 font-medium">Price</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Action</th>
                </tr>
            </thead>
            <tbody id="service-table-body" class="divide-y divide-gray-200 text-gray-700">
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center">Loading data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="addServiceModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-[100] flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-xl p-10 relative shadow-2xl">
        <h2 class="text-3xl font-bold text-center mb-8 text-gray-900">Add Services</h2>
        
        <form id="addServiceForm" onsubmit="submitService(event)">
            
            <div class="mb-5">
                <label class="block text-gray-900 font-semibold mb-2 text-lg">Service Name</label>
                <input type="text" id="name" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white placeholder-gray-400" placeholder="Enter your name" required>
            </div>

            <div class="mb-5">
                <label class="block text-gray-900 font-semibold mb-2 text-lg">Price</label>
                <input type="number" id="price" min="0" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white placeholder-gray-400" placeholder="Enter your price" required>
            </div>

            <div class="mb-5">
                <label class="block text-gray-900 font-semibold mb-2 text-lg">Description</label>
                <textarea id="description" rows="3" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white placeholder-gray-400" placeholder="Enter your description"></textarea>
            </div>

            <div class="mb-8">
                <label class="block text-gray-900 font-semibold mb-2 text-lg">Status</label>
                <div class="relative">
                    <select id="status" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white text-gray-500 appearance-none" required>
                        <option value="" disabled selected>Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-4">
                <button type="button" onclick="closeModal()" class="px-6 py-3 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-[#334155] text-white rounded-xl hover:bg-gray-800 font-medium transition-colors shadow-sm">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editServiceModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-[100] flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-xl p-10 relative shadow-2xl">
        <h2 class="text-3xl font-bold text-center mb-8 text-gray-900">Edit Service</h2>
        
        <form id="editServiceForm" onsubmit="submitEditService(event)">
            <input type="hidden" id="edit_id">
            
            <div class="mb-5">
                <label class="block text-gray-900 font-semibold mb-2 text-lg">Service Name</label>
                <input type="text" id="edit_name" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white placeholder-gray-400" required>
            </div>

            <div class="mb-5">
                <label class="block text-gray-900 font-semibold mb-2 text-lg">Price</label>
                <input type="number" id="edit_price" min="0" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white placeholder-gray-400" required>
            </div>

            <div class="mb-5">
                <label class="block text-gray-900 font-semibold mb-2 text-lg">Description</label>
                <textarea id="edit_description" rows="3" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white placeholder-gray-400"></textarea>
            </div>

            <div class="mb-8">
                <label class="block text-gray-900 font-semibold mb-2 text-lg">Status</label>
                <div class="relative">
                    <select id="edit_status" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white text-gray-500 appearance-none" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-4">
                <button type="button" onclick="closeEditModal()" class="px-6 py-3 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-[#334155] text-white rounded-xl hover:bg-gray-800 font-medium transition-colors shadow-sm">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentOpenDropdown = null;

    document.addEventListener('DOMContentLoaded', function() {
        fetchServices();
    });

    function fetchServices() {
        fetch('/api/services')
            .then(response => response.json())
            .then(result => {
                const tbody = document.getElementById('service-table-body');
                tbody.innerHTML = '';

                if (result.success && result.data.length > 0) {
                    result.data.forEach(service => {
                        const isActive = service.status == 1 || service.status == true || service.status == 'active';

                        const statusBadge = isActive ?
                            `<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Active</span>` :
                            `<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">Inactive</span>`;

                        const formattedPrice = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 2
                        }).format(service.price);

                        let actionButtons = '';

                        if (isActive) {
                            actionButtons += `
                                <button onclick="actionDeactivate(${service.id})" class="w-full flex items-center px-4 py-2 text-sm text-[#334155] hover:bg-gray-50 transition-colors">
                                    <svg class="mr-3 w-4 h-4 text-[#334155]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z M3 3l18 18"></path>
                                    </svg>
                                    Deactivate
                                </button>
                            `;
                        } else {
                            actionButtons += `
                                <button onclick="actionActivate(${service.id})" class="w-full flex items-center px-4 py-2 text-sm text-[#334155] hover:bg-gray-50 transition-colors">
                                    <svg class="mr-3 w-4 h-4 text-[#334155]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                    Active
                                </button>
                            `;
                        }

                        actionButtons += `
                            <button onclick="actionEdit(${service.id})" class="w-full flex items-center px-4 py-2 text-sm text-[#334155] hover:bg-gray-50 transition-colors">
                                <svg class="mr-3 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </button>
                            <button onclick="actionDelete(${service.id})" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="mr-3 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Delete
                            </button>
                        `;

                        const row = `
                            <tr class="hover:bg-gray-50 relative">
                                <td class="px-6 py-4">${service.name}</td>
                                <td class="px-6 py-4">${formattedPrice}</td>
                                <td class="px-6 py-4">${statusBadge}</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-gray-500 hover:text-gray-900 focus:outline-none" onclick="toggleMenu(event, ${service.id})">
                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    </button>
                                    
                                    <div id="dropdown-${service.id}" class="hidden absolute right-6 top-12 w-36 bg-white border border-gray-100 rounded-lg shadow-xl z-50 text-left py-2">
                                        ${actionButtons}
                                    </div>
                                </td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data service</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                document.getElementById('service-table-body').innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-red-500">Gagal mengambil data dari API.</td></tr>';
            });
    }

    // --- FUNGSI ADD MODAL ---
    function openModal() {
        document.getElementById('addServiceModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('addServiceModal').classList.add('hidden');
        document.getElementById('addServiceForm').reset();
    }

    function submitService(event) {
        event.preventDefault();

        const data = {
            name: document.getElementById('name').value,
            price: parseInt(document.getElementById('price').value), 
            description: document.getElementById('description').value,
            status: document.getElementById('status').value === '1' ? 1 : 0
        };

        fetch('/api/services', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success || result.data) { 
                    closeModal();
                    fetchServices();
                    alert('Success: Data service berhasil ditambahkan!');
                } else {
                    console.log(result);
                    alert('Gagal menambahkan data. Periksa console untuk detailnya.');
                }
            })
            .catch(error => {
                console.error('Error submitting data:', error);
                alert('Terjadi kesalahan pada server saat menyimpan data.');
            });
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

   // --- FUNGSI AKSI ---
    function actionActivate(id) {
        fetch(`/api/services/${id}/activate`, {
            method: 'PATCH',
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(result => {
            if(result.success) {
                fetchServices(); 
            } else {
                alert('Gagal mengaktifkan service.');
            }
        });
    }

    function actionDeactivate(id) {
        fetch(`/api/services/${id}/deactivate`, {
            method: 'PATCH',
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(result => {
            if(result.success) {
                fetchServices(); 
            } else {
                alert('Gagal menonaktifkan service.');
            }
        });
    }

    // --- FUNGSI EDIT MODAL ---
    function closeEditModal() {
        document.getElementById('editServiceModal').classList.add('hidden');
        document.getElementById('editServiceForm').reset();
    }

    function actionEdit(id) {
        fetch(`/api/services/${id}`)
            .then(response => response.json())
            .then(result => {
                if(result.success) {
                    const data = result.data;
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_price').value = data.price;
                    document.getElementById('edit_description').value = data.description || '';
                    document.getElementById('edit_status').value = (data.status == 1 || data.status == true) ? '1' : '0';
                    
                    document.getElementById('editServiceModal').classList.remove('hidden');
                } else {
                    alert('Gagal mengambil data service');
                }
            });
    }

    function submitEditService(event) {
        event.preventDefault(); 

        const id = document.getElementById('edit_id').value;
        const data = {
            name: document.getElementById('edit_name').value,
            price: parseInt(document.getElementById('edit_price').value),
            description: document.getElementById('edit_description').value,
            status: document.getElementById('edit_status').value === '1' ? 1 : 0 
        };

        fetch(`/api/services/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if(result.success || result.data) {
                closeEditModal();
                fetchServices(); 
                alert('Data berhasil diupdate!');
            } else {
                alert('Gagal mengupdate data.');
            }
        })
        .catch(error => console.error('Error updating data:', error));
    }

    // --- FUNGSI DELETE ---
    function actionDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data service ini?')) {
            fetch(`/api/services/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(result => {
                if(result.success) {
                    alert('Data berhasil dihapus!');
                    fetchServices(); 
                } else {
                    alert('Gagal menghapus: ' + result.message); 
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data.');
            });
        }
    }
</script>
@endpush