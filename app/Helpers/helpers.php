<?php

if (!function_exists('encode_param')) {
    function encode_param($param)
    {
        return Crypt::encryptString($param);
    }
}

if (!function_exists('decode_param')) {
    function decode_param($param)
    {
        return Crypt::decryptString($param);
    }
}

if (!function_exists('getMax')) {
    function getMax($table_name, $field_name)
    {
        return DB::table($table_name)->max($field_name) + 1;
    }
}

if (!function_exists('getSlug')) {
    function getSlug($table_name, $field_name, $title, $id = 0, $id_name = 'id')
    {
        $slug_name = Str::slug($title);
        $slug_name = ($slug_name) ? $slug_name : time();
        $ras = DB::table($table_name)->where($id_name, '<>', $id)->where($field_name, $slug_name)->first();
        $slug = ($ras) ? $slug_name . "-" . time() : $slug_name;
        return $slug;
    }
}

if (!function_exists('generateOrderID')) {
    function generateOrderID()
    {
        $date = date_create();
        $order_id = "QLS-" . date_timestamp_get($date);
        return $order_id;
    }
}

if (!function_exists('getItemStatusLabel')) {
    function getItemStatusLabel($status_name)
    {
        $label = '';
        if($status_name == 'Processing') {
            $label = '<h4><span class="badge bg-primary">New Order</span></h4>';
        }
        if($status_name == 'New') {
            $label = '<h4><span class="badge bg-primary">New Order</span></h4>';
        }
        if($status_name == 'Confirmed') {
            $label = '<h4><span class="badge bg-info">Confirmed</span></h4>';
        }
        if($status_name == 'Ready') {
            $label = '<h4><span class="badge bg-pink">Ready for Delivery</span></h4>';
        }
        if($status_name == 'Shipped') {
            $label = '<h4><span class="badge bg-secondary">Shipped</span></h4>';
        }
        if($status_name == 'Completed') {
            $label = '<h4><span class="badge bg-success">Completed</span></h4>';
        }
        if($status_name == 'Cancelled') {
            $label = '<h4><span class="badge bg-danger">Cancelled</span></h4>';
        }
        if($status_name == 'Exchange') {
            $label = '<h4><span class="badge bg-warning">Exchange</span></h4>';
        }
        if($status_name == 'Return') {
            $label = '<h4><span class="badge bg-warning">Return</span></h4>';
        }
        return $label;
    }
}