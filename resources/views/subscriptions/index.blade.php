@extends('layouts.app')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center px-8">
    <h1 class="text-xl font-semibold text-gray-800">Subscriptions</h1>
</header>

<div class="p-8 flex-1 overflow-y-auto">
    <div class="flex justify-end mb-6">
        <button onclick="openModal()" class="bg-[#334155] text-white px-4 py-2 rounded-xl hover:bg-gray-800 flex items-center text-sm font-medium transition-colors shadow-sm">
            <span class="mr-2">+</span> Add Data
        </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg pb-10 shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-gray-600 text-left">
                <tr>
                    <th class="px-6 py-4 font-medium">Customer Name</th>
                    <th class="px-6 py-4 font-medium">Services</th>
                    <th class="px-6 py-4 font-medium">Services Period</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Action</th>
                </tr>
            </thead>
            <tbody id="subscription-table-body" class="divide-y divide-gray-100 text-gray-700">
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">Loading data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="addSubscriptionModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-[100] flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-xl p-10 relative shadow-2xl">
        <h2 class="text-3xl font-bold text-center mb-8 text-gray-900">Add Subscription</h2>

        <form id="addSubscriptionForm" onsubmit="submitSubscription(event)">

            <div class="mb-5">
                <label class="block text-gray-900 font-semibold mb-2 text-lg">Customer</label>
                <div class="relative">
                    <select id="customer_id" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white text-gray-700 appearance-none" required>
                        <option value="" disabled selected>Select Customer</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-gray-900 font-semibold mb-2 text-lg">Service</label>
                <div class="relative">
                    <select id="service_id" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white text-gray-700 appearance-none" required>
                        <option value="" disabled selected>Select Service</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 mb-5">
                <div class="w-1/2">
                    <label class="block text-gray-900 font-semibold mb-2 text-lg">Start Date</label>
                    <input type="date" id="start_date" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white" required>
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-900 font-semibold mb-2 text-lg">End Date</label>
                    <input type="date" id="end_date" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white">
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-gray-900 font-semibold mb-2 text-lg">Status</label>
                <div class="relative">
                    <select id="status" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white text-gray-700 appearance-none" required>
                        <option value="Trial" selected>Trial</option>
                        <option value="Active">Active</option>
                        <option value="Isolir">Isolir</option>
                        <option value="Dismantle">Dismantle</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
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
@endsection

@push('scripts')
<script>
    let currentOpenDropdown = null;

    document.addEventListener('DOMContentLoaded', function() {
        fetchSubscriptions();
        loadDropdownData(); // Memuat data customer dan service untuk form Add
    });

    // Fungsi format tanggal (Contoh: 1 Jan 2026)
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        const options = {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        };
        return date.toLocaleDateString('en-GB', options);
    }

    // Load data Customer & Service untuk combobox di Modal
    function loadDropdownData() {
        // Fetch Customers
        fetch('/api/customers')
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const select = document.getElementById('customer_id');
                    result.data.forEach(c => {
                        select.insertAdjacentHTML('beforeend', `<option value="${c.id}">${c.name}</option>`);
                    });
                }
            });

        // Fetch Services
        fetch('/api/services')
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const select = document.getElementById('service_id');
                    result.data.forEach(s => {
                        select.insertAdjacentHTML('beforeend', `<option value="${s.id}">${s.name}</option>`);
                    });
                }
            });
    }

    function fetchSubscriptions() {
        fetch('/api/subscriptions')
            .then(response => response.json())
            .then(result => {
                const tbody = document.getElementById('subscription-table-body');
                tbody.innerHTML = '';

                if (result.success && result.data.length > 0) {
                    result.data.forEach(sub => {
                        const customerName = sub.customer ? sub.customer.name : 'Unknown';
                        const serviceName = sub.service ? sub.service.name : 'Unknown';
                        const period = `${formatDate(sub.start_date)} - ${formatDate(sub.end_date)}`;

                        let statusBadge = '';
                        if (sub.status === 'Active') {
                            statusBadge = `<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Active</span>`;
                        } else if (sub.status === 'Trial') {
                            statusBadge = `<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">Trial</span>`;
                        } else if (sub.status === 'Isolir') {
                            statusBadge = `<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">Isolir</span>`;
                        } else {
                            statusBadge = `<span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">Dismantle</span>`;
                        }

                        const allStatuses = ['Active', 'Trial', 'Isolir', 'Dismantle'];

                        let actionButtons = '';

                        // Kumpulan Icon
                        const statusIcons = {
                            'Active': `<svg class="mr-3 w-[18px] h-[18px] text-gray-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v-2H2v-4h4.257A6 6 0 0118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path></svg>`,
                            'Trial': `<svg class="mr-3 w-[18px] h-[18px] text-gray-700" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2v6l4 4-4 4v6h12v-6l-4-4 4-4V2H6zm10 18H8v-3.5l4-4 4 4V20zM8 4h8v3.5l-4 4-4-4V4z"/></svg>`,
                            'Isolir': `<svg class="mr-3 w-[18px] h-[18px] text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" /><rect x="9" y="9" width="6" height="6" fill="currentColor" stroke="none" /></svg>`,
                            'Dismantle': `<svg class="mr-3 w-[18px] h-[18px] text-gray-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>`
                        };

                        // LOGIKA BARU: Jika status sudah Dismantle, jangan tampilkan opsi ubah status lain
                        if (sub.status !== 'Dismantle') {
                            const allStatuses = ['Active', 'Trial', 'Isolir', 'Dismantle'];
                            allStatuses.forEach(statusOption => {
                                if (statusOption !== sub.status) {
                                    actionButtons += `
                                        <button onclick="changeStatus(${sub.id}, '${statusOption}')" class="w-full flex items-center px-4 py-2.5 text-[15px] text-[#334155] hover:bg-gray-50 transition-colors">
                                            ${statusIcons[statusOption]}
                                            <span class="font-medium">${statusOption}</span>
                                        </button>
                                    `;
                                }
                            });
                        }

                        // Tombol Delete berada DI LUAR pengecekan, jadi selalu muncul
                        actionButtons += `
                            <button onclick="deleteSubscription(${sub.id})" class="w-full flex items-center px-4 py-2.5 text-[15px] text-red-600 hover:bg-red-50 transition-colors border-t border-gray-100 mt-1 pt-2">
                                <svg class="mr-3 w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span class="font-medium">Delete</span>
                            </button>
                        `;

                        const row = `
                            <tr class="hover:bg-gray-50 relative">
                                <td class="px-6 py-4">${customerName}</td>
                                <td class="px-6 py-4">${serviceName}</td>
                                <td class="px-6 py-4">${period}</td>
                                <td class="px-6 py-4">${statusBadge}</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-gray-500 hover:text-gray-900 focus:outline-none" onclick="toggleMenu(event, ${sub.id})">
                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    </button>
                                    
                                    <div id="dropdown-${sub.id}" class="hidden absolute right-6 top-12 w-40 bg-white border border-gray-100 rounded-lg shadow-xl z-50 text-left py-2">
                                        ${actionButtons}
                                    </div>
                                </td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data subscription</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                document.getElementById('subscription-table-body').innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-red-500">Gagal mengambil data dari API.</td></tr>';
            });
    }

    // --- FUNGSI MODAL ADD ---
    function openModal() {
        document.getElementById('addSubscriptionModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('addSubscriptionModal').classList.add('hidden');
        document.getElementById('addSubscriptionForm').reset();
    }

    // --- FUNGSI SUBMIT ---
    function submitSubscription(event) {
        event.preventDefault();

        const data = {
            customer_id: parseInt(document.getElementById('customer_id').value),
            service_id: parseInt(document.getElementById('service_id').value),
            start_date: document.getElementById('start_date').value,
            end_date: document.getElementById('end_date').value || null,
            status: document.getElementById('status').value
        };

        fetch('/api/subscriptions', {
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
                    fetchSubscriptions();
                    alert('Success: Subscription berhasil ditambahkan!');
                } else {
                    alert('Gagal menambahkan data. Periksa inputan Anda.');
                }
            })
            .catch(error => {
                console.error('Error submitting data:', error);
                alert('Terjadi kesalahan pada server.');
            });
    }

    // --- FUNGSI UBAH STATUS DINAMIS ---
    function changeStatus(id, newStatus) {
        fetch(`/api/subscriptions/${id}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    fetchSubscriptions();
                } else {
                    alert('Gagal mengubah status');
                }
            });
    }

    // --- FUNGSI DELETE ---
    function deleteSubscription(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            fetch(`/api/subscriptions/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        fetchSubscriptions();
                    } else {
                        alert('Gagal menghapus data.');
                    }
                });
        }
    }

    // --- FUNGSI DROPDOWN MENU ---
    function toggleMenu(event, id) {
        event.stopPropagation();
        const dropdown = document.getElementById(`dropdown-${id}`);
        if (currentOpenDropdown && currentOpenDropdown !== dropdown) {
            currentOpenDropdown.classList.add('hidden');
        }
        dropdown.classList.toggle('hidden');
        currentOpenDropdown = !dropdown.classList.contains('hidden') ? dropdown : null;
    }

    document.addEventListener('click', function() {
        if (currentOpenDropdown) {
            currentOpenDropdown.classList.add('hidden');
            currentOpenDropdown = null;
        }
    });
</script>
@endpush