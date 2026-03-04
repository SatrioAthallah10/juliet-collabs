@extends('layouts.master')

@section('title')
    {{ __('Complaints') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage') . ' ' . __('Complaints') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('list') . ' ' . __('Complaints') }}
                        </h4>

                        {{-- Filter Row --}}
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <select id="status_filter" class="form-control form-control-sm" style="width: auto; min-width: 150px;">
                                    <option value="">{{ __('All Status') }}</option>
                                    <option value="new">{{ __('New') }}</option>
                                    <option value="in_progress">{{ __('In Progress') }}</option>
                                    <option value="resolved">{{ __('Resolved') }}</option>
                                </select>
                                <select id="category_filter" class="form-control form-control-sm ml-2" style="width: auto; min-width: 150px;">
                                    <option value="">{{ __('All Categories') }}</option>
                                    <option value="general">{{ __('General') }}</option>
                                    <option value="billing">{{ __('Billing') }}</option>
                                    <option value="technical">{{ __('Technical') }}</option>
                                    <option value="other">{{ __('Other') }}</option>
                                </select>
                            </div>
                            <div>
                                <b><a href="#" class="table-list-type active mr-2" data-id="0">{{ __('all') }}</a></b> |
                                <a href="#" class="ml-2 table-list-type" data-id="1">{{ __('Trashed') }}</a>
                            </div>
                        </div>

                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                            data-url="{{ route('complaints.show') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true" data-page-size="10"
                            data-page-list="[5, 10, 20, 50, 100]" data-search="true" data-show-columns="true"
                            data-show-refresh="true" data-fixed-columns="false" data-fixed-number="2"
                            data-fixed-right-number="1" data-trim-on-search="false" data-mobile-responsive="true"
                            data-sort-name="id" data-sort-order="desc" data-maintain-selected="true"
                            data-export-data-type='all' data-export-options='{ "fileName": "complaints-list-<?= date('d-m-y') ?>"
                                    ,"ignoreColumn":["operate"]}' data-show-export="true"
                            data-query-params="complaintQueryParams" data-escape="true">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-visible="false">{{ __('id') }}</th>
                                    <th scope="col" data-field="no">{{ __('no.') }}</th>
                                    <th scope="col" data-field="user_name">{{ __('name') }}</th>
                                    <th scope="col" data-field="contact_info">{{ __('Contact Info') }}</th>
                                    <th scope="col" data-field="contact_type_display" data-escape="false">{{ __('Contact Type') }}</th>
                                    <th scope="col" data-field="message">{{ __('message') }}</th>
                                    <th scope="col" data-field="category_display" data-escape="false">{{ __('Category') }}</th>
                                    <th scope="col" data-field="status_display" data-escape="false">{{ __('Status') }}</th>
                                    <th scope="col" data-field="created_at">{{ __('Date') }}</th>
                                    <th scope="col" data-field="operate" data-escape="false">{{ __('action') }}</th>
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
        // RTL support for filter toggles
        document.querySelectorAll('.table-list-type').forEach(el => {
            if (document.dir === 'rtl') {
                if (el.classList.contains('ml-2')) {
                    el.classList.replace('ml-2', 'mr-2');
                } else if (el.classList.contains('mr-2')) {
                    el.classList.replace('mr-2', 'ml-2');
                }
            }
        });

        function complaintQueryParams(params) {
            let selected = $('.table-list-type.active').data('id') || 0;
            return {
                offset: params.offset,
                limit: params.limit,
                search: params.search,
                sort: params.sort,
                order: params.order,
                show_deleted: selected,
                status: $('#status_filter').val(),
                category: $('#category_filter').val()
            };
        }

        // Refresh table when filters change
        $('#status_filter, #category_filter').on('change', function() {
            $('#table_list').bootstrapTable('refresh');
        });

        // Handle status change clicks
        $(document).on('click', '.change-complaint-status', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var newStatus = $(this).data('status');

            $.ajax({
                type: 'PUT',
                url: url,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: newStatus
                },
                success: function(response) {
                    $('#table_list').bootstrapTable('refresh');
                    if (response.message) {
                        $.toast({
                            text: response.message,
                            showHideTransition: 'slide',
                            icon: 'success',
                            loaderBg: '#f96868',
                            position: 'top-right'
                        });
                    }
                },
                error: function(xhr) {
                    $.toast({
                        text: 'Error updating status',
                        showHideTransition: 'slide',
                        icon: 'error',
                        loaderBg: '#f2a654',
                        position: 'top-right'
                    });
                }
            });
        });
    </script>
@endsection
