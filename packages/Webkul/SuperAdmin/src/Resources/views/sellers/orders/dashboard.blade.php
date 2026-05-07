<x-superadmin::layouts>
    <x-slot:title>
        Seller Orders Dashboard
    </x-slot>

    <div id="seller-orders-pro-app" class="sod-app"></div>

    @push('styles')
        <style>
            .sod-app {
                --sod-accent1: #6c3ce1;
                --sod-accent2: #e13c6c;
                --sod-accent3: #3cbbe1;
                --sod-accent4: #e1a33c;
                --sod-surface: #0d0f1a;
                --sod-surface2: #151929;
                --sod-surface3: #1c2035;
                --sod-border: rgba(255, 255, 255, 0.07);
                --sod-border2: rgba(255, 255, 255, 0.13);
                --sod-text1: #f0f2ff;
                --sod-text2: #9aa0c0;
                --sod-text3: #5a6080;
                box-sizing: border-box;
                font-family: "DM Sans", sans-serif;
                background: var(--sod-surface);
                color: var(--sod-text1);
                min-height: 100vh;
                position: relative;
                overflow: hidden;
                border-radius: 12px;
            }

            .sod-app *, .sod-app *::before, .sod-app *::after { box-sizing: border-box; }
            .sod-wrap { position: relative; z-index: 1; padding: 24px; }
            .sod-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
            .sod-header-title { font-size: 28px; font-weight: 800; letter-spacing: -0.5px; line-height: 1.1; }
            .sod-header-sub { font-size: 13px; color: var(--sod-text2); margin-top: 4px; }
            .sod-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
            .sod-btn { font-size: 13px; font-weight: 500; padding: 9px 14px; border-radius: 10px; border: 1px solid var(--sod-border2); cursor: pointer; transition: all .18s; background: var(--sod-surface3); color: var(--sod-text2); }
            .sod-btn:hover { background: var(--sod-surface2); color: #fff; }
            .sod-btn-accent { background: linear-gradient(135deg, var(--sod-accent1), #9b5cf6); color: #fff; border-color: transparent; }
            .sod-btn-accent:hover { opacity: .9; }
            .sod-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 24px; }
            .sod-stat { background: var(--sod-surface2); border: 1px solid var(--sod-border); border-radius: 14px; padding: 16px 18px; }
            .sod-stat--total { border-color: rgba(108,60,225,.45); box-shadow: inset 0 3px 0 #6c3ce1; }
            .sod-stat--completed { border-color: rgba(22,163,74,.45); box-shadow: inset 0 3px 0 #16a34a; }
            .sod-stat--processing { border-color: rgba(56,189,248,.45); box-shadow: inset 0 3px 0 #38bdf8; }
            .sod-stat--pending { border-color: rgba(234,179,8,.45); box-shadow: inset 0 3px 0 #eab308; }
            .sod-stat--rejected { border-color: rgba(225,60,60,.45); box-shadow: inset 0 3px 0 #e13c3c; }
            .sod-stat--revenue { border-color: rgba(236,72,153,.45); box-shadow: inset 0 3px 0 #ec4899; }
            .sod-stat-label { font-size: 12px; color: var(--sod-text3); margin-bottom: 8px; text-transform: uppercase; }
            .sod-stat-value { font-size: 24px; font-weight: 700; }
            .sod-toolbar { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; }
            .sod-input, .sod-select { background: var(--sod-surface2); border: 1px solid var(--sod-border); border-radius: 10px; color: var(--sod-text1); font-size: 14px; padding: 9px 12px; outline: none; }
            .sod-input { min-width: 260px; }
            .sod-select:focus, .sod-input:focus { border-color: var(--sod-accent3); }
            .sod-select:hover, .sod-input:hover, .sod-select:hover option { color: #fff; }
            .sod-table-wrap { background: var(--sod-surface2); border: 1px solid var(--sod-border); border-radius: 16px; overflow: hidden; }
            .sod-table-scroll { overflow-x: auto; }
            .sod-table { width: 100%; border-collapse: collapse; min-width: 980px; }
            .sod-table thead { background: var(--sod-surface3); }
            .sod-table th { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .7px; color: var(--sod-text3); padding: 14px; text-align: left; border-bottom: 1px solid var(--sod-border); }
            .sod-table td { padding: 14px; border-bottom: 1px solid var(--sod-border); font-size: 13px; }
            .sod-thumb { width: 42px; height: 42px; border-radius: 8px; object-fit: cover; border: 1px solid var(--sod-border); background: #0f1322; }
            .sod-table tr:last-child td { border-bottom: none; }
            .sod-table tr:hover { background: rgba(255,255,255,.03); }
            .sod-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; }
            .sod-badge--completed { background: rgba(22,163,74,.15); color: #4ade80; }
            .sod-badge--processing { background: rgba(56,189,248,.15); color: #38bdf8; }
            .sod-badge--pending { background: rgba(234,179,8,.15); color: #facc15; }
            .sod-badge--rejected { background: rgba(225,60,60,.15); color: #f87171; }
            .sod-actions-cell { display: flex; gap: 6px; align-items: center; }
            .sod-action { border: 1px solid var(--sod-border); background: var(--sod-surface3); color: var(--sod-text2); border-radius: 8px; height: 30px; padding: 0 10px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
            .sod-action:hover { color: #fff; border-color: var(--sod-accent3); }
            .sod-action--view { border-color: rgba(56,189,248,.45); color: #7dd3fc; }
            .sod-action--view:hover { background: rgba(56,189,248,.16); color: #e0f2fe; border-color: rgba(56,189,248,.65); }
            .sod-action--complete { border-color: rgba(22,163,74,.45); color: #86efac; }
            .sod-action--complete:hover { background: rgba(22,163,74,.18); color: #bbf7d0; border-color: rgba(22,163,74,.65); }
            .sod-action--reject { border-color: rgba(225,60,60,.45); color: #fca5a5; }
            .sod-action--reject:hover { background: rgba(225,60,60,.18); color: #fecaca; border-color: rgba(225,60,60,.65); }
            .sod-pagination { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-top: 1px solid var(--sod-border); background: var(--sod-surface3); }
            .sod-page-btn { border: 1px solid var(--sod-border); background: var(--sod-surface2); color: var(--sod-text2); border-radius: 8px; height: 32px; min-width: 32px; cursor: pointer; margin-left: 6px; }
            .sod-page-btn[disabled] { opacity: .4; cursor: not-allowed; }
            .sod-page-btn.is-active { background: var(--sod-accent1); color: #fff; border-color: var(--sod-accent1); }
            .sod-page-btn:hover:not([disabled]) { color: #fff; border-color: var(--sod-accent3); }
            .sod-bulk { display: none; align-items: center; gap: 10px; background: var(--sod-surface3); border: 1px solid var(--sod-accent1); border-radius: 12px; padding: 10px 16px; margin-bottom: 14px; }
            .sod-bulk.is-visible { display: flex; }
            .sod-bulk-chip {
                display: inline-flex;
                align-items: center;
                border-radius: 999px;
                padding: 4px 10px;
                font-size: 12px;
                border: 1px solid transparent;
                white-space: nowrap;
            }
            .sod-bulk-chip b { margin-left: 4px; }
            .sod-chip-selected { background: rgba(108,60,225,.18); color: #c4b5fd; border-color: rgba(108,60,225,.35); }
            .sod-chip-orders { background: rgba(56,189,248,.16); color: #7dd3fc; border-color: rgba(56,189,248,.35); }
            .sod-chip-commission { background: rgba(236,72,153,.16); color: #f9a8d4; border-color: rgba(236,72,153,.35); }
            .sod-chip-total { background: rgba(22,163,74,.16); color: #86efac; border-color: rgba(22,163,74,.35); }
            .sod-bulk .sod-btn[data-bulk="completed"] { border-color: rgba(22,163,74,.45); color: #86efac; }
            .sod-bulk .sod-btn[data-bulk="processing"] { border-color: rgba(56,189,248,.45); color: #7dd3fc; }
            .sod-bulk .sod-btn[data-bulk="pending"] { border-color: rgba(234,179,8,.45); color: #fde047; }
            .sod-bulk .sod-btn[data-bulk="rejected"] { border-color: rgba(225,60,60,.45); color: #fca5a5; }
            .sod-bulk .sod-btn[data-bulk="completed"]:hover { background: rgba(22,163,74,.18); color: #bbf7d0; }
            .sod-bulk .sod-btn[data-bulk="processing"]:hover { background: rgba(56,189,248,.16); color: #e0f2fe; }
            .sod-bulk .sod-btn[data-bulk="pending"]:hover { background: rgba(234,179,8,.18); color: #fef08a; }
            .sod-bulk .sod-btn[data-bulk="rejected"]:hover { background: rgba(225,60,60,.18); color: #fecaca; }
            .sod-toast-wrap { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
            .sod-toast { background: var(--sod-surface3); border: 1px solid var(--sod-border2); border-radius: 12px; padding: 10px 14px; font-size: 13px; color: var(--sod-text1); }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const mount = document.getElementById('seller-orders-pro-app');
                if (! mount) return;

                const initialOrders = @json($dashboardOrders);
                const stats = @json($dashboardStats);
                const bulkUrl = @json($bulkUrl);
                const approveUrlTemplate = @json($approveUrlTemplate);
                const rejectUrlTemplate = @json($rejectUrlTemplate);
                const viewUrlTemplate = @json($viewUrlTemplate);
                const placeholderImage = @json($placeholderImage);
                const currencySymbol = @json($currencySymbol);
                const csrf = @json(csrf_token());

                let rows = Array.isArray(initialOrders) ? initialOrders : [];
                let filtered = [...rows];
                let selected = new Set();
                let page = 1;
                const perPage = 10;

                function badgeClass(status) {
                    if (status === 'Completed') return 'sod-badge sod-badge--completed';
                    if (status === 'Processing') return 'sod-badge sod-badge--processing';
                    if (status === 'Rejected') return 'sod-badge sod-badge--rejected';
                    return 'sod-badge sod-badge--pending';
                }

                function toast(message) {
                    const wrap = document.getElementById('sod-toast-wrap');
                    if (! wrap) return;
                    const el = document.createElement('div');
                    el.className = 'sod-toast';
                    el.textContent = message;
                    wrap.appendChild(el);
                    setTimeout(() => el.remove(), 2800);
                }

                function money(value) {
                    const amount = Number(value || 0);
                    return `${currencySymbol || ''}${amount.toFixed(2)}`;
                }

                function applyFilters() {
                    const q = (document.getElementById('sod-search').value || '').toLowerCase();
                    const status = document.getElementById('sod-status').value;
                    const seller = document.getElementById('sod-seller').value;
                    const channel = document.getElementById('sod-channel').value;
                    const store = document.getElementById('sod-store').value;
                    filtered = rows.filter(function (r) {
                        const matchQ = ! q || Object.values(r || {})
                            .map(function (value) { return value === null || value === undefined ? '' : String(value); })
                            .join(' ')
                            .toLowerCase()
                            .includes(q);
                        const matchStatus = ! status || r.status === status;
                        const matchSeller = ! seller || r.seller_store === seller;
                        const matchChannel = ! channel || String(r.channel) === channel;
                        const matchStore = ! store || r.seller_store === store;
                        return matchQ && matchStatus && matchSeller && matchChannel && matchStore;
                    });
                    page = 1;
                    renderTable();
                }

                function renderTable() {
                    const tbody = document.getElementById('sod-body');
                    const start = (page - 1) * perPage;
                    const pageRows = filtered.slice(start, start + perPage);

                    if (! pageRows.length) {
                        tbody.innerHTML = '<tr><td colspan="17">No orders found.</td></tr>';
                    } else {
                        tbody.innerHTML = pageRows.map(function (r) {
                            const approveUrl = approveUrlTemplate.replace('__id__', String(r.id));
                            const rejectUrl = rejectUrlTemplate.replace('__id__', String(r.id));
                            const viewUrl = viewUrlTemplate.replace('__id__', String(r.id));
                            const checked = selected.has(r.id) ? 'checked' : '';
                            return `<tr>
                                <td><input type="checkbox" data-id="${r.id}" ${checked}></td>
                                <td><img class="sod-thumb" src="${r.image || placeholderImage}" alt="Order item"></td>
                                <td>#${r.increment_id}</td>
                                <td>${r.seller_store}<br><small>${r.seller_email}</small></td>
                                <td><span class="${badgeClass(r.status)}">${r.status}</span></td>
                                <td>${r.amount_formatted}</td>
                                <td>${r.pay}</td>
                                <td>${r.channel}</td>
                                <td>${r.store_name}</td>
                                <td>${r.seller_name}<br><small>Level: ${r.seller_level || 'Beginner'}</small></td>
                                <td>${r.customer}</td>
                                <td>${r.customer_email}</td>
                                <td>${r.buyer}<br><small>${r.buyer_email}</small></td>
                                <td>${r.location}</td>
                                <td>${r.items}</td>
                                <td>${r.date || ''}</td>
                                <td class="sod-actions-cell">
                                    <a class="sod-action sod-action--view" href="${viewUrl}" data-action="view">View</a>
                                    <button class="sod-action sod-action--complete" data-action="approve" data-url="${approveUrl}" data-id="${r.id}">Complete</button>
                                    <button class="sod-action sod-action--reject" data-action="reject" data-url="${rejectUrl}" data-id="${r.id}">Reject</button>
                                </td>
                            </tr>`;
                        }).join('');
                    }

                    renderPagination();
                    syncBulkState();
                }

                function renderPagination() {
                    const total = filtered.length;
                    const totalPages = Math.ceil(total / perPage) || 1;
                    const info = document.getElementById('sod-page-info');
                    info.textContent = `Showing ${total ? ((page - 1) * perPage) + 1 : 0}-${Math.min(page * perPage, total)} of ${total}`;
                    const wrap = document.getElementById('sod-page-btns');
                    let html = `<button class="sod-page-btn" ${page === 1 ? 'disabled' : ''} data-page="${page - 1}">&lt;</button>`;
                    for (let i = 1; i <= totalPages; i++) {
                        html += `<button class="sod-page-btn ${i === page ? 'is-active' : ''}" data-page="${i}">${i}</button>`;
                    }
                    html += `<button class="sod-page-btn" ${page >= totalPages ? 'disabled' : ''} data-page="${page + 1}">&gt;</button>`;
                    wrap.innerHTML = html;
                }

                function syncBulkState() {
                    const bar = document.getElementById('sod-bulk');
                    const count = document.getElementById('sod-bulk-count');
                    const amountEl = document.getElementById('sod-bulk-orders-amount');
                    const commissionEl = document.getElementById('sod-bulk-commission');
                    const totalEl = document.getElementById('sod-bulk-total');

                    const selectedRows = rows.filter(function (r) { return selected.has(r.id); });
                    const ordersAmount = selectedRows.reduce(function (sum, r) { return sum + Number(r.amount || 0); }, 0);
                    const commissionAmount = selectedRows.reduce(function (sum, r) { return sum + Number(r.commission || 0); }, 0);
                    const totalAmount = ordersAmount + commissionAmount;

                    if (selected.size > 0) {
                        bar.classList.add('is-visible');
                        count.textContent = String(selected.size);
                    } else {
                        bar.classList.remove('is-visible');
                    }

                    if (amountEl) amountEl.textContent = money(ordersAmount);
                    if (commissionEl) commissionEl.textContent = money(commissionAmount);
                    if (totalEl) totalEl.textContent = money(totalAmount);
                }

                async function postAction(url, payload) {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrf,
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(payload || {}),
                    });
                    const data = await response.json().catch(() => ({}));

                    if (! response.ok) {
                        const message = (data && data.message) ? data.message : 'Request failed.';
                        throw new Error(message);
                    }

                    return data;
                }

                mount.innerHTML = `
                    <div class="sod-wrap">
                        <div class="sod-header">
                            <div>
                                <div class="sod-header-title">Seller Orders Dashboard</div>
                                <div class="sod-header-sub">Dynamic seller/customer/orders data view</div>
                            </div>
                            <div class="sod-actions">
                                <button class="sod-btn" id="sod-export-csv">Export CSV</button>
                                <button class="sod-btn sod-btn-accent" id="sod-export-xls">Export XLS</button>
                            </div>
                        </div>
                        <div class="sod-stats">
                            <div class="sod-stat sod-stat--total"><div class="sod-stat-label">Total Orders</div><div class="sod-stat-value">${stats.total}</div></div>
                            <div class="sod-stat sod-stat--completed"><div class="sod-stat-label">Completed</div><div class="sod-stat-value">${stats.completed}</div></div>
                            <div class="sod-stat sod-stat--processing"><div class="sod-stat-label">Processing</div><div class="sod-stat-value">${stats.processing}</div></div>
                            <div class="sod-stat sod-stat--pending"><div class="sod-stat-label">Pending</div><div class="sod-stat-value">${stats.pending}</div></div>
                            <div class="sod-stat sod-stat--rejected"><div class="sod-stat-label">Rejected</div><div class="sod-stat-value">${stats.rejected}</div></div>
                            <div class="sod-stat sod-stat--revenue"><div class="sod-stat-label">Completed Revenue</div><div class="sod-stat-value">${stats.revenue_formatted}</div></div>
                        </div>
                        <div class="sod-toolbar">
                            <input id="sod-search" class="sod-input" placeholder="Search orders, sellers, customers">
                            <select id="sod-status" class="sod-select">
                                <option value="">All Status</option>
                                <option value="Completed">Completed</option>
                                <option value="Processing">Processing</option>
                                <option value="Pending">Pending</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                            <select id="sod-seller" class="sod-select">
                                <option value="">All Sellers</option>
                                ${Array.from(new Set(rows.map(r => r.seller_store))).map(name => `<option value="${name}">${name}</option>`).join('')}
                            </select>
                            <select id="sod-channel" class="sod-select">
                                <option value="">All Channels</option>
                                ${Array.from(new Set(rows.map(r => String(r.channel)).filter(Boolean))).map(channel => `<option value="${channel}">${channel}</option>`).join('')}
                            </select>
                            <select id="sod-store" class="sod-select">
                                <option value="">All Stores</option>
                                ${Array.from(new Set(rows.map(r => r.seller_store).filter(Boolean))).map(store => `<option value="${store}">${store}</option>`).join('')}
                            </select>
                            <button class="sod-btn" id="sod-clear">Clear</button>
                        </div>
                        <div class="sod-bulk" id="sod-bulk">
                            <span class="sod-bulk-chip sod-chip-selected"><span id="sod-bulk-count">0</span> selected</span>
                            <span class="sod-bulk-chip sod-chip-orders">Orders Amount:<b id="sod-bulk-orders-amount">0.00</b></span>
                            <span class="sod-bulk-chip sod-chip-commission">Commission:<b id="sod-bulk-commission">0.00</b></span>
                            <span class="sod-bulk-chip sod-chip-total">Total Amount:<b id="sod-bulk-total">0.00</b></span>
                            <button class="sod-btn" data-bulk="completed">Complete</button>
                            <button class="sod-btn" data-bulk="processing">Processing</button>
                            <button class="sod-btn" data-bulk="pending">Pending</button>
                            <button class="sod-btn" data-bulk="rejected">Reject</button>
                        </div>
                        <div class="sod-table-wrap">
                            <div class="sod-table-scroll">
                                <table class="sod-table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="sod-select-all"></th>
                                            <th>Image</th>
                                            <th>Order #</th>
                                            <th>Seller</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Payment</th>
                                            <th>Channel</th>
                                            <th>Store Name</th>
                                            <th>Seller Name</th>
                                            <th>Customer</th>
                                            <th>Email</th>
                                            <th>Buyer</th>
                                            <th>Location</th>
                                            <th>Items</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sod-body"></tbody>
                                </table>
                            </div>
                            <div class="sod-pagination">
                                <span id="sod-page-info"></span>
                                <div id="sod-page-btns"></div>
                            </div>
                        </div>
                    </div>
                    <div id="sod-toast-wrap" class="sod-toast-wrap"></div>
                `;

                renderTable();

                document.getElementById('sod-search').addEventListener('input', applyFilters);
                document.getElementById('sod-status').addEventListener('change', applyFilters);
                document.getElementById('sod-seller').addEventListener('change', applyFilters);
                document.getElementById('sod-channel').addEventListener('change', applyFilters);
                document.getElementById('sod-store').addEventListener('change', applyFilters);
                document.getElementById('sod-clear').addEventListener('click', function () {
                    document.getElementById('sod-search').value = '';
                    document.getElementById('sod-status').value = '';
                    document.getElementById('sod-seller').value = '';
                    document.getElementById('sod-channel').value = '';
                    document.getElementById('sod-store').value = '';
                    applyFilters();
                });

                mount.addEventListener('click', async function (event) {
                    const pageBtn = event.target.closest('[data-page]');
                    if (pageBtn) {
                        const next = Number(pageBtn.getAttribute('data-page'));
                        const maxPage = Math.ceil(filtered.length / perPage) || 1;
                        if (next >= 1 && next <= maxPage) {
                            page = next;
                            renderTable();
                        }
                        return;
                    }

                    const actionBtn = event.target.closest('[data-action]');
                    if (actionBtn) {
                        if (actionBtn.getAttribute('data-action') === 'view') {
                            return;
                        }

                        const url = actionBtn.getAttribute('data-url');
                        const action = actionBtn.getAttribute('data-action');
                        try {
                            const data = await postAction(url, {});
                            toast(data.message || (action === 'approve' ? 'Order completed.' : 'Order rejected.'));
                            window.setTimeout(() => window.location.reload(), 350);
                        } catch (error) {
                            toast(error.message || 'Action failed.');
                        }
                        return;
                    }

                    const bulkBtn = event.target.closest('[data-bulk]');
                    if (bulkBtn) {
                        if (! selected.size) {
                            toast('Select at least one order.');
                            return;
                        }

                        const value = bulkBtn.getAttribute('data-bulk');
                        try {
                            const data = await postAction(bulkUrl, { indices: Array.from(selected), value: value });
                            toast(data.message || 'Bulk status updated.');
                            window.setTimeout(() => window.location.reload(), 350);
                        } catch (error) {
                            toast(error.message || 'Bulk status update failed.');
                        }
                        return;
                    }

                    if (event.target.id === 'sod-export-csv' || event.target.id === 'sod-export-xls') {
                        const headers = ['Order #', 'Seller Store', 'Status', 'Grand Total', 'Pay Via', 'Channel', 'Store Name', 'Seller Name', 'Customer', 'Email', 'Buyer', 'Location', 'Items', 'Date'];
                        const lines = filtered.map(r => [
                            r.increment_id, `"${r.seller_store}"`, r.status, r.amount, `"${r.pay}"`, `"${r.channel}"`, `"${r.store_name}"`, `"${r.seller_name}"`, `"${r.customer}"`, `"${r.customer_email}"`, `"${r.buyer}"`, `"${r.location}"`, r.items, `"${r.date || ''}"`
                        ].join(','));
                        const csv = [headers.join(','), ...lines].join('\n');
                        const name = event.target.id === 'sod-export-xls' ? 'seller-orders.xls' : 'seller-orders.csv';
                        const blob = new Blob([csv], { type: 'text/csv' });
                        const a = document.createElement('a');
                        a.href = URL.createObjectURL(blob);
                        a.download = name;
                        a.click();
                    }
                });

                mount.addEventListener('change', function (event) {
                    if (event.target.id === 'sod-select-all') {
                        const pageRows = filtered.slice((page - 1) * perPage, (page - 1) * perPage + perPage);
                        pageRows.forEach(r => {
                            if (event.target.checked) selected.add(r.id);
                            else selected.delete(r.id);
                        });
                        renderTable();
                        return;
                    }

                    if (event.target.matches('input[type="checkbox"][data-id]')) {
                        const id = Number(event.target.getAttribute('data-id'));
                        if (event.target.checked) selected.add(id);
                        else selected.delete(id);
                        syncBulkState();
                    }
                });
            });
        </script>
    @endpush
</x-superadmin::layouts>
