<?php

if (! function_exists('freshness_status')) {
    function freshness_status($entryDate, $shelfLifeDays)
    {
        $today = strtotime(date('Y-m-d'));
        $entry = strtotime($entryDate);

        if (!$entry) {
            return [
                'age' => 0,
                'percentage' => 0,
                'status' => 'Tidak Segar',
                'badge' => 'badge-danger',
                'class' => 'status-tidak',
                'recommendation' => 'Tanggal masuk produk tidak valid.',
            ];
        }

        $age = floor(($today - $entry) / 86400);

        if ($age < 0) {
            $age = 0;
        }

        $shelfLifeDays = (int) $shelfLifeDays;

        if ($shelfLifeDays <= 0) {
            $shelfLifeDays = 1;
        }

        $percentage = ($age / $shelfLifeDays) * 100;

        if ($percentage <= 50) {
            $status = 'Segar';
        } elseif ($percentage <= 100) {
            $status = 'Kurang Segar';
        } else {
            $status = 'Tidak Segar';
        }

        $detail = freshness_status_detail($status);
        $detail['age'] = $age;
        $detail['percentage'] = round($percentage);

        return $detail;
    }
}

if (! function_exists('freshness_status_detail')) {
    function freshness_status_detail($status)
    {
        $status = trim((string) $status);

        if ($status === 'Segar') {
            return [
                'status' => 'Segar',
                'badge' => 'badge-success',
                'class' => 'status-segar',
                'recommendation' => 'Produk masih dalam kondisi baik dan aman untuk dijual.',
            ];
        }

        if ($status === 'Tidak Segar') {
            return [
                'status' => 'Tidak Segar',
                'badge' => 'badge-danger',
                'class' => 'status-tidak',
                'recommendation' => 'Produk perlu dipisahkan dari stok jual dan segera dicek ulang.',
            ];
        }

        return [
            'status' => 'Kurang Segar',
            'badge' => 'badge-warning',
            'class' => 'status-kurang',
            'recommendation' => 'Produk perlu diprioritaskan untuk dijual atau diperiksa kembali.',
        ];
    }
}