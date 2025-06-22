@props(['title' => null, 'tableFields' => [], 'searchFields' => [], 'routeName' => null, 'isTree' => false, 'pageDisplay' => 10, 'showActionColumn' => true])

<!-- Datatable -->
<div class="card card-action mb-12">
    <div class="card-datatable table-responsive pt-0">
        <x-master.table-search :searchFields="$searchFields" />
        <table class="table table-hover" style="width: 100%;" id="datatable">
        <thead>
            <tr>
                <th>No</th>
                @foreach ($tableFields as $item)
                    @if (isset($item['name']))
                        @if (isset($item['isTable']) && $item['isTable'] === 'invisible')
                            <th style="display:none">{{ $item['name'] }}</th>
                        @else
                            <th>{{ $item['name'] }}</th>
                        @endif
                    @endif
                @endforeach
                @if ($showActionColumn)
                <th style="min-width: 95px">Aksi</th>
                @endif
            </tr>
        </thead>
        </table>
    </div>
</div>
<!--/ Datatable -->


@push('vendor-style')
@vite([
])
@endpush

@push('vendor-script')
@vite([
])
@endpush
@push('page-script')
    <x-master.table-script
        :routeName="$routeName"
        :tableFields="$tableFields"
        :title="$title"
        :isTree="$isTree"
        :pageDisplay="$pageDisplay"
        :showActionColumn="$showActionColumn" />
@endpush
