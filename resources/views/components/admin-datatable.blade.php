@props([
    'data',
    'exclude' => 'id, created_at, updated_at, deleted_at, created_by, updated_by, deleted_by, raw_description',
    'pageSize' => 15,
    'actionIcons' => [],
    'columnAliases' => [],
    'searchable' => false,
    'messageAsEmptyState' => true,
    'noDataMessage' => 'No records found.',
    'emptyHeading' => 'No Data Available',
    'buttonLabel' => '',
    'onclick' => '',
])

@php
    if (
        $data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ||
        $data instanceof \Illuminate\Pagination\Paginator
    ) {
        $items = $data->items();
    } elseif ($data instanceof \Illuminate\Support\Collection) {
        $items = $data->all();
    } else {
        $items = (array) $data;
    }

    $records = array_map(function ($item) {
        if (is_object($item)) {
            if (method_exists($item, 'toArray')) {
                return $item->toArray();
            }
            return (array) $item;
        }
        return $item;
    }, $items);
@endphp

<x-bladewind::table :data="$records" :exclude_columns="$exclude" :action_icons="$actionIcons" :column_aliases="$columnAliases" :searchable="$searchable"
    :message_as_empty_state="$messageAsEmptyState" :no_data_message="$noDataMessage" :heading="$emptyHeading" :button_label="$buttonLabel" :onclick="$onclick" paginated="true"
    :page_size="$pageSize" show_row_numbers="true" show_total_pages="true" total_label="Records :a - :b" {{ $attributes }} />
