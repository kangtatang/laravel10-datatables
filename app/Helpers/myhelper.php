<?php

if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($phoneNumber)
    {
        // Validasi input
        if (empty($phoneNumber)) {
            return '';
        }

        // Format nomor telepon
        $formattedNumber = chunk_split($phoneNumber, 4, '-');
        $formattedNumber = rtrim($formattedNumber, '-');

        return $formattedNumber;
    }
}
