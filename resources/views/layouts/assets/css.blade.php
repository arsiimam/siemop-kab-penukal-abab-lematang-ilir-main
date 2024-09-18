<link rel="stylesheet" href="{{ asset('font/iconsmind-s/css/iconsminds.css') }}" />
<link rel="stylesheet" href="{{ asset('font/simple-line-icons/css/simple-line-icons.css') }}" />

<link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.rtl.only.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/component-custom-switch.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/perfect-scrollbar.css') }}" />

<link rel="stylesheet" href="{{ asset('css/vendor/fullcalendar.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/dataTables.bootstrap4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/datatables.responsive.bootstrap4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/glide.core.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-stars.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/nouislider.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-datepicker3.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/dropzone.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vendor/component-custom-switch.min.css') }}" />

<link rel="stylesheet" href="{{ asset('css/main.css') }}" />

<link rel="stylesheet" href="{{ asset('vendor/notice/notice.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('vendor') }}/daterangepicker/daterangepicker.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css"
    integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    div.dataTables_filter,
    div.dataTables_length {
        display: inline-block;
        margin-left: 1em;
        float: right;
    }

    td.font_italic {
        font-style: italic;
    }

    td.font_mail {
        font-style: italic;
        text-decoration: underline;
    }

    .tooltip-custom {
        position: relative;
        display: inline-block;
        font-weight: normal;
    }

    .tooltip-custom .tooltiptext {
        visibility: hidden;
        width: auto;
        max-width: 120px;
        background-color: #fff;
        color: #000;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        border: 1px gray solid;

        /* Position the tooltip */
        position: absolute;
        z-index: 100;
        top: 120%;
        left: 50%;
        transform: translate(-50%, 0%);
        /* margin-left: -60px; */
    }

    .tooltip-custom .tooltiptext-top {
        visibility: hidden;
        width: auto;
        background-color: #fff;
        color: #000;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        border: 1px gray solid;
        position: absolute;
        z-index: 100;
        top: -120%;
        left: 50%;
        transform: translate(-50%, 0%);
    }

    .tooltip-custom .tooltiptext:before {
        content: '';
        display: block;
        width: 0;
        height: 0;
        position: absolute;

        margin-top: -17px;
        border: 6px solid transparent;
        border-bottom-color: gray;
        left: calc(50% - 6px);
    }

    .tooltip-custom .tooltiptext-top:before {
        content: '';
        display: block;
        width: 0;
        height: 0;
        position: absolute;
        margin-top: -17px;
        border: 6px solid transparent;
        border-top-color: gray;
        left: calc(50% - 6px);
        top: 47px;
    }

    .tooltip-custom:hover .tooltiptext,
    .tooltip-custom:hover .tooltiptext-top {
        visibility: visible;
    }

    .blank-bottom {
        /* padding: 0; */
        border: none;
        background: none;
    }

    .icon-text-size {
        font-size: 20px;
    }

    .btn-delete {
        padding: 0px;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: var(--separator-color);
        opacity: .8;
    }

    .select2-container--bootstrap .select2-selection--multiple .select2-search--inline .select2-search__field {
        min-width: 10em;
    }

    table.dataTable {
        clear: both;
        margin-top: 6px !important;
        margin-bottom: 6px !important;
        max-width: none !important;
        border-collapse: collapse !important;
    }

    table.dataTable td {
        padding-top: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f3f3f3;
        outline: initial !important;
    }

    div.dataTables_wrapper div.dataTables_paginate {
        margin-top: 10px;
    }

    .modal .modal-content {
        border: initial;
        border-radius: 0.5rem;
    }

    .modal.fade .modal-dialog {
        transition: transform 0.15s ease-out;
        transform: translateY(-100px) scale(0.8);
    }

    @media (prefers-reduced-motion: reduce) {
        .modal.fade .modal-dialog {
            transition: none;
        }
    }

    .modal.show .modal-dialog {
        transform: translateY(0) scale(1);
    }

    .modal.modal-static .modal-dialog {
        transform: scale(1.02);
    }

    .opensright {
        top: 180px !important;
    }

    .dashboard-layout {
        border: 2.5px #6fb327 solid;
        padding: 10px;
        border-radius: 100%;
        width: 60px;
        height: 60px;
        position: relative;
    }

    .dashboard-heading {
        margin-bottom: 0px !important;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .dashboard-icon-size {
        font-size: 20px !important;
    }

    .dashboard-icon-big-size {
        font-size: 40px !important;
    }

    table.dataTable thead tr th {
        vertical-align: middle !important;
    }

    table.dataTable tbody tr td {
        vertical-align: middle !important;
    }

    #activity-table td:nth-child(1),
    td:nth-child(2),
    td:nth-child(3) {
        white-space: nowrap !important;
    }

    #activity-table {
        white-space: nowrap !important;
    }

    .dataTables_scrollBody {
        overflow-y: hidden !important;
    }

    .text-underline {
        text-decoration: underline;
    }

    .table-hover tbody tr:hover {
        color: var(--primary-color) !important;
    }

    div.dt-center-in-div {
        float: left;
    }

    table thead.thead-light tr th {
        border-color: var(--separator-color) !important;
    }

    table.table-bordered.dataTable th,
    table.table-bordered.dataTable td {
        border-left-width: 1px !important;
    }

    .card.card-bg {
        background-image: linear-gradient(to right top, #6c9e37, #76a543, #8ebb4e);
        color: #fff !important;
    }

    .card-bg .dashboard-layout {
        border: 2.5px #d7d7d7 solid;
    }
</style>
