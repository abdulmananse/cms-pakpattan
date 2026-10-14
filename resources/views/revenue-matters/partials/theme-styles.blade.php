<style>
    /* ==========================================================================
       LAND REVENUE MATTERS — CENTRALIZED TYPOGRAPHY & DESIGN SYSTEM
       ========================================================================== */

    /* 5. Navigation & Page Header */
    .page-header .page-header-title h5,
    .revenue-page-heading {
        font-size: 23px !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        line-height: 1.3 !important;
        letter-spacing: -0.01em;
    }

    .breadcrumb,
    .breadcrumb .breadcrumb-item,
    .breadcrumb .breadcrumb-item a {
        font-size: 14px !important;
        color: #64748b !important;
    }

    .breadcrumb .breadcrumb-item.active {
        color: #334155 !important;
        font-weight: 500 !important;
    }

    /* Sidebar Navigation Font Sizes */
    .pcoded-navbar .pcoded-inner-navbar > li > a {
        font-size: 15.5px !important;
        font-weight: 500 !important;
    }

    .pcoded-navbar .pcoded-inner-navbar > li > a .pcoded-mtext {
        font-size: 15.5px !important;
    }

    .pcoded-navbar .pcoded-submenu > li > a {
        font-size: 15px !important;
        font-weight: 400 !important;
    }

    /* 1. Dashboard Metric Cards */
    .stat-card {
        border-radius: 0.75rem;
        border: none;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        overflow: hidden;
        position: relative;
        min-height: 148px;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.18) !important;
    }

    .stat-card-blue {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    }

    .stat-card-amber {
        background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
    }

    .stat-card-emerald {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    }

    .stat-card-indigo {
        background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
    }

    .stat-card-title {
        font-size: 20px !important;
        font-weight: 700 !important;
        color: #ffffff !important;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-bottom: 0.2rem;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .stat-card-value {
        font-size: 46px !important;
        font-weight: 800 !important;
        color: #ffffff !important;
        line-height: 1.05;
        margin-bottom: 0.2rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
    }

    .stat-card-desc {
        font-size: 16.5px !important;
        color: rgba(255, 255, 255, 0.95) !important;
        font-weight: 500 !important;
        line-height: 1.35;
    }

    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(6px);
        flex-shrink: 0;
    }

    .stat-icon-wrapper i {
        font-size: 24px !important;
        color: #ffffff !important;
    }

    .stat-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 16.5px !important;
        font-weight: 600 !important;
        color: #ffffff !important;
        text-decoration: none !important;
        background: rgba(255, 255, 255, 0.22);
        padding: 0.35rem 0.95rem;
        border-radius: 9999px;
        transition: all 0.15s ease-in-out;
    }

    .stat-link:hover {
        background: rgba(255, 255, 255, 0.38);
        color: #ffffff !important;
        transform: translateX(4px);
    }

    .btn-create-stat {
        background: #ffffff !important;
        color: #4338ca !important;
        font-weight: 700 !important;
        font-size: 16.5px !important;
        padding: 0.35rem 1rem !important;
        border-radius: 9999px !important;
        border: none !important;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.14);
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        transition: all 0.15s ease-in-out;
    }

    .btn-create-stat:hover {
        background: #f8fafc !important;
        color: #3730a3 !important;
        transform: translateY(-1px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.18);
    }

    /* 2. All Data-Entry Forms */
    .revenue-filter-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        background: #ffffff;
    }

    .revenue-filter-card .input-group-text {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: #64748b;
        font-size: 15px !important;
        border-radius: 0.45rem 0 0 0.45rem;
    }

    .revenue-form-card .form-label,
    .revenue-filter-card .form-label,
    .modal-content .form-label,
    .form-label {
        font-size: 15.5px !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        margin-bottom: 0.45rem;
    }

    .revenue-form-card .form-control,
    .revenue-form-card .form-select,
    .revenue-filter-card .form-control,
    .revenue-filter-card .form-select,
    .filter-card .form-control,
    .filter-card .form-select,
    .modal-content .form-control,
    .modal-content .form-select {
        font-size: 16px !important;
        color: #0f172a !important;
        padding: 0.55rem 0.85rem !important;
        min-height: 44px;
        border-radius: 0.45rem;
        border-color: #cbd5e1;
    }

    .revenue-form-card .form-control:focus,
    .revenue-form-card .form-select:focus,
    .revenue-filter-card .form-control:focus,
    .revenue-filter-card .form-select:focus,
    .filter-card .form-control:focus,
    .filter-card .form-select:focus,
    .modal-content .form-control:focus,
    .modal-content .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.18);
    }

    .form-select option {
        font-size: 15.5px !important;
        padding: 0.45rem 0.65rem;
    }

    .form-control::placeholder,
    .form-select::placeholder {
        font-size: 14.5px !important;
        color: #94a3b8 !important;
    }

    .form-section-heading {
        font-size: 19px !important;
        font-weight: 700 !important;
        color: #1e40af !important;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1.25rem;
        margin-top: 0.5rem;
    }

    .revenue-form-btn,
    .btn-revenue-primary {
        font-size: 16px !important;
        font-weight: 600 !important;
        padding: 0.6rem 1.35rem !important;
        border-radius: 0.45rem !important;
    }

    .invalid-feedback,
    .form-error-msg {
        font-size: 14px !important;
        font-weight: 500 !important;
        margin-top: 0.35rem;
    }

    /* 3. Complaint Details and Audit Trail */
    .complaint-main-heading {
        font-size: 25px !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        line-height: 1.3;
    }

    .details-table th,
    .card-body .table th {
        font-size: 17px !important;
        font-weight: 600 !important;
        color: #334155 !important;
        padding: 0.95rem 1rem !important;
        vertical-align: middle;
    }

    .details-table td,
    .card-body .table td {
        font-size: 17px !important;
        color: #0f172a !important;
        padding: 0.95rem 1rem !important;
        vertical-align: middle;
    }

    .audit-officer-name {
        font-size: 17px !important;
        font-weight: 700 !important;
        color: #0f172a !important;
    }

    .audit-comment-box {
        font-size: 16.5px !important;
        line-height: 1.6 !important;
        color: #1e293b !important;
    }

    .audit-datetime,
    .date-time-text {
        font-size: 14.5px !important;
        color: #64748b !important;
    }

    .workflow-btn {
        font-size: 16.5px !important;
        font-weight: 700 !important;
        padding: 0.65rem 1.45rem !important;
        border-radius: 0.5rem !important;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        transition: all 0.15s ease-in-out;
    }

    .workflow-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
    }

    /* Audit Trail Action Badges (High Contrast, Clear Non-White Font) */
    .audit-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 15px !important;
        font-weight: 700 !important;
        padding: 0.35rem 0.8rem !important;
        border-radius: 9999px !important;
        line-height: 1.25 !important;
    }

    .audit-badge-created {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe !important;
    }

    .audit-badge-forwarded {
        background-color: #f0fdfa !important;
        color: #0f766e !important;
        border: 1px solid #99f6e4 !important;
    }

    .audit-badge-sent-back {
        background-color: #fffbeb !important;
        color: #b45309 !important;
        border: 1px solid #fde68a !important;
    }

    .audit-badge-comment {
        background-color: #f8fafc !important;
        color: #334155 !important;
        border: 1px solid #cbd5e1 !important;
    }

    .audit-badge-closed {
        background-color: #f0fdf4 !important;
        color: #15803d !important;
        border: 1px solid #bbf7d0 !important;
    }

    .audit-badge-reopened {
        background-color: #faf5ff !important;
        color: #7e22ce !important;
        border: 1px solid #e9d5ff !important;
    }

    .audit-badge-default {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
        border: 1px solid #cbd5e1 !important;
    }

    .custody-badge-active {
        background-color: #dcfce7 !important;
        color: #166534 !important;
        border: 1px solid #86efac !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        padding: 0.35rem 0.8rem !important;
        border-radius: 9999px !important;
        display: inline-flex;
        align-items: center;
    }

    /* 4. Complaint Lists and Reports (Tables, Badges, Filters) */
    .report-table-card {
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        overflow: hidden;
    }

    .report-table-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 1.25rem;
    }

    .table-responsive-custom {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 0 0 0.5rem 0.5rem;
    }

    .report-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .report-table thead th {
        font-size: 15px !important;
        font-weight: 700 !important;
        background-color: #f8fafc !important;
        color: #334155 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.035em !important;
        padding: 0.95rem 0.85rem !important;
        border-bottom: 2px solid #cbd5e1 !important;
        border-top: none !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }

    .report-table tbody td {
        font-size: 15.5px !important;
        line-height: 1.5 !important;
        padding: 0.95rem 0.85rem !important;
        vertical-align: middle !important;
        border-top: 1px solid rgba(0, 0, 0, 0.04);
        color: #1e293b;
    }

    /* Officer-wise Row Backgrounds & Borders */
    .table-officer-row {
        border-left: 4px solid var(--row-officer-border, #9ca3af) !important;
        transition: background-color 0.15s ease-in-out, filter 0.15s ease-in-out;
    }

    .table-officer-row > td {
        background-color: var(--row-officer-bg, #ffffff) !important;
        color: #1e293b !important;
        padding: 0.95rem 0.85rem !important;
        vertical-align: middle !important;
        border-top: 1px solid rgba(0, 0, 0, 0.04) !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04) !important;
        font-size: 15.5px !important;
        line-height: 1.5 !important;
    }

    .table-officer-row > td:first-child {
        border-left: 4px solid var(--row-officer-border, #9ca3af) !important;
    }

    .table-officer-row:hover > td {
        filter: brightness(0.96) saturate(1.1) !important;
    }

    .complaint-no-link {
        font-size: 16px !important;
        font-weight: 600 !important;
        color: #1d4ed8 !important;
        text-decoration: none !important;
        letter-spacing: 0.01em;
        display: inline-block;
    }

    .complaint-no-link:hover {
        color: #1e40af !important;
        text-decoration: underline !important;
    }

    .officer-holder-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 15px !important;
        font-weight: 600 !important;
        padding: 0.35rem 0.75rem 0.35rem 0.4rem !important;
        border-radius: 9999px;
        max-width: 100%;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .officer-holder-label {
        font-size: 15px !important;
        font-weight: 600 !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .officer-holder-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 25px !important;
        height: 25px !important;
        border-radius: 50%;
        font-size: 0.75rem !important;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .status-badge-open {
        background-color: #fef3c7 !important;
        color: #92400e !important;
        border: 1.5px solid #fcd34d !important;
        padding: 0.38rem 0.85rem !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        border-radius: 9999px !important;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(245, 158, 11, 0.15);
        line-height: 1.25;
    }

    .status-badge-in-process {
        background-color: #dbeafe !important;
        color: #1e40af !important;
        border: 1.5px solid #93c5fd !important;
        padding: 0.38rem 0.85rem !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        border-radius: 9999px !important;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(37, 99, 235, 0.15);
        line-height: 1.25;
    }

    .status-badge-resolved {
        background-color: #dcfce7 !important;
        color: #166534 !important;
        border: 1.5px solid #86efac !important;
        padding: 0.38rem 0.85rem !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        border-radius: 9999px !important;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(16, 185, 129, 0.15);
        line-height: 1.25;
    }

    .status-badge-reopened {
        background-color: #f3e8ff !important;
        color: #6b21a8 !important;
        border: 1.5px solid #d8b4fe !important;
        padding: 0.38rem 0.85rem !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        border-radius: 9999px !important;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(139, 92, 246, 0.15);
        line-height: 1.25;
    }

    .status-badge-overdue {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
        border: 1.5px solid #fca5a5 !important;
        padding: 0.38rem 0.85rem !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        border-radius: 9999px !important;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(239, 68, 68, 0.15);
        line-height: 1.25;
    }

    .last-activity-desc,
    .table-officer-row .last-activity-desc,
    .table-officer-row:hover .last-activity-desc {
        color: #475569 !important;
        font-weight: 500 !important;
        font-size: 14.5px !important;
        line-height: 1.4 !important;
        display: inline-block !important;
    }

    .last-activity-time,
    .table-officer-row .last-activity-time,
    .table-officer-row:hover .last-activity-time {
        color: #1e293b !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        line-height: 1.35 !important;
    }

    .pagination .page-link,
    .pagination .page-item,
    .card-footer .text-muted {
        font-size: 14.5px !important;
    }

    /* Officer Legend Typography */
    .officer-legend-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
    }

    .officer-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border-radius: 9999px;
        text-decoration: none !important;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        line-height: 1.3;
        font-size: 14.5px !important;
        padding: 0.32rem 0.75rem 0.32rem 0.4rem !important;
    }

    .officer-legend-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    .officer-legend-item.active-legend {
        box-shadow: 0 0 0 2px #2563eb;
        font-weight: 700 !important;
    }

    .officer-legend-name {
        font-size: 14.5px !important;
        white-space: nowrap;
    }

    .officer-legend-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 25px !important;
        height: 25px !important;
        border-radius: 50%;
        font-size: 0.75rem !important;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
</style>
