<?php
// rsvp.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id']) && isset($data['status'])) {
        $guestId = (int)$data['id'];
        $newStatus = $data['status'];
        $filePath = 'guests.txt';

        if (file_exists($filePath)) {
            $guests = file($filePath, FILE_IGNORE_NEW_LINES);
            $updated = false;

            foreach ($guests as $i => $guest) {
                list($id, $name, $status) = explode(',', $guest, 3);
                if ((int)$id === $guestId) {
                    $guests[$i] = "$id,$name,$newStatus";
                    $updated = true;
                    break;
                }
            }

            if ($updated) {
                file_put_contents($filePath, implode("\n", $guests));
                echo json_encode(['success' => true, 'message' => 'Respuesta guardada.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'ID de invitado no encontrado.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Archivo de invitados no encontrado.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
