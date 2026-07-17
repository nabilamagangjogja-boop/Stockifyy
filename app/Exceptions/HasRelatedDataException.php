<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Dilempar saat mencoba hapus data yang masih dipakai/direferensikan
 * oleh data aktif lain (mis. kategori yang masih punya produk aktif).
 */
class HasRelatedDataException extends RuntimeException {}
