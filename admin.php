<?php
// admin.php
$guests_file = 'guests.txt';
$guests_data = [];
if (file_exists($guests_file)) {
    $lines = file($guests_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $base_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php";
    foreach ($lines as $line) {
        list($id, $name, $status) = explode(',', $line, 3);
        $guests_data[] = [
            'id' => trim($id),
            'name' => trim($name),
            'status' => trim($status),
            'link' => $base_url . '?id=' . trim($id)
        ];
    }
}

// Contadores
$accepted = count(array_filter($guests_data, fn($g) => $g['status'] === 'aceptado'));
$rejected = count(array_filter($guests_data, fn($g) => $g['status'] === 'rechazado'));
$pending = count(array_filter($guests_data, fn($g) => $g['status'] === 'N/A'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Confirmaciones XV Giuliana Aguilar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8fafc;
        }
        .status-aceptado { background-color: #dcfce7; color: #166534; }
        .status-rechazado { background-color: #fee2e2; color: #991b1b; }
        .status-na { background-color: #f1f5f9; color: #475569; }
    </style>
</head>
<body class="p-8">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Panel de Confirmaciones</h1>
        <h2 class="text-xl font-semibold text-gray-600 mb-8">XV Años de Giuliana Aguilar</h2>

        <!-- Resumen de estados -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-center">
            <div class="p-6 bg-green-100 rounded-lg">
                <div class="text-4xl font-bold text-green-700"><?php echo $accepted; ?></div>
                <div class="text-lg font-semibold text-green-800">Aceptaron</div>
            </div>
            <div class="p-6 bg-red-100 rounded-lg">
                <div class="text-4xl font-bold text-red-700"><?php echo $rejected; ?></div>
                <div class="text-lg font-semibold text-red-800">Rechazaron</div>
            </div>
            <div class="p-6 bg-gray-100 rounded-lg">
                <div class="text-4xl font-bold text-gray-700"><?php echo $pending; ?></div>
                <div class="text-lg font-semibold text-gray-800">Pendientes</div>
            </div>
        </div>

        <!-- Tabla de invitados -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invitado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enlace Personalizado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($guests_data)): ?>
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">No se encontraron invitados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($guests_data as $guest): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($guest['name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full status-<?php echo strtolower(htmlspecialchars($guest['status'])); ?>">
                                        <?php echo htmlspecialchars($guest['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="flex items-center space-x-2">
                                        <input type="text" readonly value="<?php echo htmlspecialchars($guest['link']); ?>" class="w-full bg-gray-100 border-none rounded-md p-1 text-xs">
                                        <button onclick="copyLink('<?php echo htmlspecialchars($guest['link']); ?>', this)" class="bg-blue-500 text-white px-2 py-1 rounded-md text-xs hover:bg-blue-600">Copiar</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function copyLink(link, button) {
            navigator.clipboard.writeText(link).then(() => {
                const originalText = button.textContent;
                button.textContent = '¡Copiado!';
                setTimeout(() => {
                    button.textContent = originalText;
                }, 2000);
            });
        }
    </script>
</body>
</html>
