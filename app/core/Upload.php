<?php

namespace App\Core;

class Upload
{
    public static function save($file, string $directory): ?string
    {
        if (!isset($file) || !is_array($file) || $file['error'] !== UPLOAD_ERR_OK) {
            error_log('Erreur upload: fichier non valide ou erreur. Détails: ' . print_r($file ?? 'null', true));
            return null;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file['type'], $allowedTypes)) {
            error_log('Type de fichier non autorisé: ' . $file['type']);
            return null;
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/' . $directory;
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                error_log('Erreur création répertoire: ' . $uploadDir);
                return null;
            }
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $destination = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            error_log('Erreur déplacement fichier vers: ' . $destination);
            return null;
        }

        chmod($destination, 0644);
        return $directory . '/' . $filename;
    }
}