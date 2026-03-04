@extends('layouts.master')

@section('title')
    DOKU {{ __('payment') }} {{ __('receipts') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                DOKU {{ __('payment') }} {{ __('receipts') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card search-container">
                <div class="card">
                    <div class="card-body">
                        <div id="toolbar" class="row">
                            <div class="col col-md-4">
                                <label for="filter_payment_status" style="font-size: 0.86rem;width: 110px">
                                    {{ __('Payment Status') }}
                                </label>
                                <select name="filter_payment_status" id="filter_payment_status" class="form-control">
                                    <option value="">{{ __('all') }}</option>
                                    <option value="success">{{ __('succeed') }}</option>
                                    <option value="pending">{{ __('pending') }}</option>
                                    <option value="failed">{{ __('failed') }}</option>
                                </select>
                            </div>
                        </div>
                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                            data-url="{{ url('subscriptions/doku-receipts/list') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"
                            data-search="true" data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                            data-fixed-columns="false" data-fixed-number="2" data-fixed-right-number="1"
                            data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                            data-sort-order="desc" data-maintain-selected="true" data-export-types='all'
                            data-export-options='{ "fileName": "doku-receipts-<?= date('d-m-y') ?>" ,"ignoreColumn":["operate"]}'
                            data-show-export="true" data-query-params="dokuReceiptQueryParams"
                            data-escape="true">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true" data-visible="false">{{ __('id') }}</th>
                                    <th scope="col" data-field="no">{{ __('no.') }}</th>
                                    <th scope="col" data-field="date" data-sortable="true">{{ __('date') }}</th>
                                    <th scope="col" data-field="school_name" data-align="center">{{ __('school') }}</th>
                                    <th scope="col" data-field="school_email" data-align="center" data-visible="false">{{ __('email') }}</th>
                                    <th scope="col" data-field="invoice_number" data-align="center">{{ __('invoice') }}</th>
                                    <th scope="col" data-field="package_name" data-align="center">{{ __('package') }}</th>
                                    <th scope="col" data-field="amount" data-align="center">{{ __('amount') }}</th>
                                    <th scope="col" data-field="payment_status" data-align="center" data-formatter="dokuPaymentStatusFormatter">{{ __('status') }}</th>
                                    <th scope="col" data-field="doku_transaction_id" data-align="center" data-visible="false">{{ __('transaction') }} ID</th>
                                    <th scope="col" data-field="operate" data-align="center" data-escape="false">{{ __('action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function dokuReceiptQueryParams(params) {
            return {
                limit: params.limit,
                sort: params.sort,
                order: params.order,
                offset: params.offset,
                search: params.search,
                payment_status: $('#filter_payment_status').val(),
            };
        }

        function dokuPaymentStatusFormatter(value) {
            if (value === 'success') {
                return '<span class="badge badge-success">Success</span>';
            } else if (value === 'pending') {
                return '<span class="badge badge-warning">Pending</span>';
            } else if (value === 'failed') {
                return '<span class="badge badge-danger">Failed</span>';
            }
            return value;
        }

        $(document).ready(function () {
            $('#filter_payment_status').change(function () {
                $('#table_list').bootstrapTable('refresh');
            });
        });
    </script>
@endsection
