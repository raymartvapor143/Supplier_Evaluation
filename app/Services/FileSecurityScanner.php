<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileSecurityScanner
{
    /**
     * Whitelisted extensions and corresponding MIME types.
     */
    protected static $allowedMimeTypes = [
        'jpg'  => ['image/jpeg', 'image/pjpeg'],
        'jpeg' => ['image/jpeg', 'image/pjpeg'],
        'png'  => ['image/png'],
        'webp' => ['image/webp'],
        'pdf'  => ['application/pdf', 'application/x-pdf'],
        'doc'  => ['application/msword'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'xls'  => ['application/vnd.ms-excel'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
    ];

    /**
     * Dangerous shell signature patterns.
     */
    protected static $dangerousSignatures = [
        '/<\?php/i',
        '/<\?=/',
        '/<script\s+language\s*=\s*["\']?php/i',
        '/\beval\s*\(/i',
        '/\bsystem\s*\(/i',
        '/\bexec\s*\(/i',
        '/\bshell_exec\s*\(/i',
        '/\bpassthru\s*\(/i',
        '/\bpopen\s*\(/i',
        '/\bproc_open\s*\(/i',
        '/\bbase64_decode\s*\(/i',
        '/\bgzinflate\s*\(/i',
        '/\bassert\s*\(/i',
        '/\bpreg_replace\s*\(.*\/e/i',
    ];

    /**
     * Scan an uploaded file before saving.
     */
    public function scanUploadedFile(UploadedFile $file): array
    {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());

        // 1. Check double extensions (e.g. payload.php.jpg)
        if (preg_match('/\.(php|phtml|php3|php4|php5|phps|phar|exe|pl|py|cgi|asp|aspx|sh|bat|cmd|jsp)\./i', $originalName)) {
            return [
                'safe' => false,
                'reason' => 'Double extension attack detected (suspicious filename).'
            ];
        }

        // 2. Extension Whitelist Check
        if (!array_key_exists($extension, static::$allowedMimeTypes)) {
            return [
                'safe' => false,
                'reason' => "File extension '.{$extension}' is not permitted."
            ];
        }

        // 3. Magic Bytes / Real MIME Inspection
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $realMime = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        $allowedForExt = static::$allowedMimeTypes[$extension] ?? [];
        if (!in_array($realMime, $allowedForExt, true)) {
            return [
                'safe' => false,
                'reason' => "MIME type mismatch: File claims to be '.{$extension}' but actual content is '{$realMime}'."
            ];
        }

        // 4. Payload Content Inspection (Web Shell & Malicious Code)
        $content = file_get_contents($file->getRealPath(), false, null, 0, 500000); // Read up to 500KB
        if ($content !== false) {
            foreach (static::$dangerousSignatures as $pattern) {
                if (preg_match($pattern, $content)) {
                    return [
                        'safe' => false,
                        'reason' => 'Malicious executable code / web shell signature detected inside file content.'
                    ];
                }
            }
        }

        return ['safe' => true, 'reason' => 'File passed security scanning cleanly.'];
    }

    /**
     * Scan all files in storage for threats (Used by Administrator Security Scanner).
     */
    public function scanDirectory(string $directoryPath): array
    {
        $results = [];

        if (!file_exists($directoryPath) || !is_dir($directoryPath)) {
            return $results;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directoryPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                $filePath = $fileInfo->getRealPath();
                $filename = $fileInfo->getFilename();
                $size = $fileInfo->getSize();
                $extension = strtolower($fileInfo->getExtension());

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $realMime = finfo_file($finfo, $filePath);
                finfo_close($finfo);

                $status = 'CLEAN';
                $reasons = [];

                // Check double extensions
                if (preg_match('/\.(php|phtml|php3|php4|php5|phar|exe|pl|py|cgi|asp|aspx|sh|bat|cmd|jsp)\./i', $filename)) {
                    $status = 'THREAT';
                    $reasons[] = 'Double extension threat detected';
                }

                // Check mime type consistency
                if (array_key_exists($extension, static::$allowedMimeTypes)) {
                    if (!in_array($realMime, static::$allowedMimeTypes[$extension], true)) {
                        $status = 'THREAT';
                        $reasons[] = "MIME mismatch (real: {$realMime})";
                    }
                } else if (in_array($extension, ['php', 'phtml', 'exe', 'sh', 'asp', 'aspx', 'jsp'])) {
                    $status = 'THREAT';
                    $reasons[] = "Forbidden executable extension '.{$extension}'";
                }

                // Scan content for php/shell payloads
                $content = @file_get_contents($filePath, false, null, 0, 500000);
                if ($content !== false) {
                    foreach (static::$dangerousSignatures as $pattern) {
                        if (preg_match($pattern, $content)) {
                            $status = 'THREAT';
                            $reasons[] = 'Web Shell / PHP Payload detected';
                            break;
                        }
                    }
                }

                $results[] = [
                    'filename'     => $filename,
                    'path'         => str_replace(base_path() . DIRECTORY_SEPARATOR, '', $filePath),
                    'full_path'    => $filePath,
                    'size_formatted' => round($size / 1024, 2) . ' KB',
                    'extension'    => $extension,
                    'mime'         => $realMime,
                    'status'       => $status,
                    'details'      => empty($reasons) ? 'Clean & Safe' : implode(', ', $reasons),
                    'modified_at'  => date('Y-m-d H:i:s', $fileInfo->getMTime())
                ];
            }
        }

        return $results;
    }
}
