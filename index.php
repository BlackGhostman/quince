<?php
// index.php
$guests_file = 'guests.txt';
$guest_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$current_guest_name = null;
$guest_status = null;

if ($guest_id > 0 && file_exists($guests_file)) {
    $lines = file($guests_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($id, $name, $status) = explode(',', $line, 3);
        if ((int)$id === $guest_id) {
            $current_guest_name = trim($name);
            $guest_status = trim($status);
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis XV Años - Giuliana Aguilar</title>
    <!-- Inclusión de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Inclusión de la librería de íconos Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Fuentes de Google para un estilo elegante -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* Estilos personalizados para la invitación */
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #fce3f3 0%, #fde4e4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .invitation-card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 100%;
            text-align: center;
            padding: 2.5rem;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .title-script {
            font-family: 'Dancing Script', cursive;
            color: #d85c9d;
        }
        
        .details-icon {
            color: #d85c9d;
            width: 20px;
            height: 20px;
            stroke-width: 2;
        }

        .action-button {
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .action-button:hover {
            transform: translateY(-2px);
        }

        .accept-button {
            background: linear-gradient(45deg, #f472b6, #db2777);
            box-shadow: 0 4px 15px rgba(216, 92, 157, 0.4);
        }

        .accept-button:hover {
             box-shadow: 0 6px 20px rgba(216, 92, 157, 0.5);
        }

        .reject-button {
            background-color: #f1f5f9;
            color: #64748b;
        }

        .reject-button:hover {
            background-color: #e2e8f0;
        }

        .hidden {
            display: none;
        }

        @keyframes confetti-fall {
            0% { transform: translateY(-100%) rotateZ(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotateZ(360deg); opacity: 0; }
        }

        .confetti {
            position: absolute; width: 8px; height: 16px; background-color: #f472b6; top: -20px; opacity: 0;
            animation: confetti-fall 5s linear infinite;
        }
        .confetti:nth-child(2) { background-color: #ec4899; animation-delay: 0.5s; left: 10%; }
        .confetti:nth-child(3) { background-color: #fbcfe8; animation-delay: 1s; left: 20%; }
        .confetti:nth-child(4) { background-color: #f9a8d4; animation-delay: 1.5s; left: 30%; }
        .confetti:nth-child(5) { background-color: #f472b6; animation-delay: 2s; left: 40%; }
        .confetti:nth-child(6) { background-color: #ec4899; animation-delay: 2.5s; left: 50%; }
        .confetti:nth-child(7) { background-color: #fbcfe8; animation-delay: 3s; left: 60%; }
        .confetti:nth-child(8) { background-color: #f9a8d4; animation-delay: 3.5s; left: 70%; }
        .confetti:nth-child(9) { background-color: #f472b6; animation-delay: 4s; left: 80%; }
        .confetti:nth-child(10) { background-color: #ec4899; animation-delay: 4.5s; left: 90%; }
        
        .confetti-container.active .confetti { opacity: 1; }
    </style>
</head>
<body>
    
    <div class="confetti-container" id="confetti-container">
        <div class="confetti"></div> <div class="confetti"></div> <div class="confetti"></div> <div class="confetti"></div> <div class="confetti"></div>
        <div class="confetti"></div> <div class="confetti"></div> <div class="confetti"></div> <div class="confetti"></div> <div class="confetti"></div>
    </div>

    
    <div class="invitation-card">
        <h1 class="title-script text-6xl mb-2">Mis Quince Años</h1>
        <h2 class="title-script text-5xl mb-6">Giuliana Aguilar</h2>

        <p class="text-gray-600 mb-6 leading-relaxed">
            Porque eres parte de mi historia, quiero que estés en el comienzo de un nuevo capítulo. Te invito a celebrar la noche de mis sueños.
        </p>

        <div id="countdown" class="grid grid-cols-4 gap-2 text-center my-8">
            <div>
                <div id="days" class="text-4xl font-bold text-pink-500">00</div>
                <div class="text-xs text-gray-500">Días</div>
            </div>
            <div>
                <div id="hours" class="text-4xl font-bold text-pink-500">00</div>
                <div class="text-xs text-gray-500">Horas</div>
            </div>
            <div>
                <div id="minutes" class="text-4xl font-bold text-pink-500">00</div>
                <div class="text-xs text-gray-500">Minutos</div>
            </div>
            <div>
                <div id="seconds" class="text-4xl font-bold text-pink-500">00</div>
                <div class="text-xs text-gray-500">Segundos</div>
            </div>
        </div>

        <div class="space-y-4 text-left my-8">
            <div class="flex items-center space-x-4"><i data-lucide="calendar-heart" class="details-icon"></i><span class="font-semibold text-gray-700">Sábado, 11 de Octubre, 2025</span></div>
            <div class="flex items-center space-x-4"><i data-lucide="clock" class="details-icon"></i><span class="font-semibold text-gray-700">7:00 PM</span></div>
            <div class="flex items-center space-x-4">
                <i data-lucide="map-pin" class="details-icon"></i>
                <a href="https://maps.app.goo.gl/SdTWZm1bMUBuBeev5" target="_blank" rel="noopener noreferrer" class="font-semibold text-gray-700 hover:text-pink-600 transition-colors">
                    Salón de Eventos "La Esmeralda", Cartago
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <i data-lucide="shirt" class="details-icon"></i>
                <span class="font-semibold text-gray-700">Código de Vestimenta: Formal</span>
            </div>
            <div class="flex items-center space-x-4">
                <i data-lucide="baby" class="details-icon"></i>
                <span class="font-semibold text-gray-700">No niños</span>
            </div>
            <div class="flex items-center space-x-4">
                <i data-lucide="gift" class="details-icon"></i>
                <span class="font-semibold text-gray-700">Regalo: Lluvia de sobres</span>
            </div>
        </div>

        <div class="my-8">
            <p class="text-center text-gray-600 mb-4">Una canción para crear el ambiente perfecto:</p>
            <iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/2qxmye6gA8ecCSn5nB1j7d?utm_source=generator" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
        </div>

        
        <?php if ($current_guest_name): ?>
            <div class="mb-6 text-center">
                <p class="text-xl text-gray-700">Invitación especial para</p>
                <p class="text-3xl font-semibold title-script text-pink-500"><?php echo htmlspecialchars($current_guest_name); ?></p>
            </div>
        <?php endif; ?>

        
        
        <div id="rsvp-section" class="<?php echo ($guest_status !== 'N/A') ? 'hidden' : ''; ?>">
            <?php if ($current_guest_name): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button id="reject-button" class="action-button reject-button w-full py-3 px-4 text-lg font-semibold rounded-lg transition-all">No Podré Asistir</button>
                    <button id="accept-button" class="action-button accept-button w-full py-3 px-4 text-lg font-semibold rounded-lg text-white transition-all">Sí, Asistiré</button>
                </div>
            <?php else: ?>
                <p class="text-center text-red-500 font-semibold">Enlace de invitación no válido.</p>
            <?php endif; ?>
        </div>

        <div id="confirmation-message" class="<?php echo ($guest_status === 'N/A') ? 'hidden' : ''; ?> mt-6 text-center">
            <i data-lucide="party-popper" class="mx-auto text-yellow-500 w-12 h-12 mb-2"></i>
            <p id="response-text" class="text-xl font-semibold text-pink-600">
                <?php 
                if ($guest_status === 'aceptado') echo '¡Gracias por confirmar!';
                if ($guest_status === 'rechazado') echo 'Lamentamos que no puedas venir.';
                ?>
            </p>
            <p class="text-gray-600">¡Tu respuesta ya ha sido guardada!</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            const acceptButton = document.getElementById('accept-button');
            const rejectButton = document.getElementById('reject-button');
            const rsvpSection = document.getElementById('rsvp-section');
            const confirmationMessage = document.getElementById('confirmation-message');
            const responseText = document.getElementById('response-text');
            const confettiContainer = document.getElementById('confetti-container');
            const guestId = <?php echo $guest_id; ?>;

            const handleRsvp = (status) => {
                if (!guestId) return;

                fetch('rsvp.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: guestId, status: status })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        rsvpSection.classList.add('hidden');
                        confirmationMessage.classList.remove('hidden');
                        
                        if (status === 'aceptado') {
                            responseText.textContent = '¡Gracias por confirmar!';
                            confettiContainer.classList.add('active');
                            setTimeout(() => confettiContainer.classList.remove('active'), 5000);
                        } else {
                            responseText.textContent = 'Lamentamos que no puedas venir.';
                        }
                    }
                });
            };

            acceptButton.addEventListener('click', () => handleRsvp('aceptado'));
            rejectButton.addEventListener('click', () => handleRsvp('rechazado'));

            // Lógica del contador
            const countdownDate = new Date('October 11, 2025 19:00:00').getTime();

            const updateCountdown = setInterval(() => {
                const now = new Date().getTime();
                const distance = countdownDate - now;

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('days').innerText = String(days).padStart(2, '0');
                document.getElementById('hours').innerText = String(hours).padStart(2, '0');
                document.getElementById('minutes').innerText = String(minutes).padStart(2, '0');
                document.getElementById('seconds').innerText = String(seconds).padStart(2, '0');

                if (distance < 0) {
                    clearInterval(updateCountdown);
                    document.getElementById('countdown').innerHTML = "<p class=\"col-span-4 text-2xl font-bold text-pink-600\">¡El gran día ha llegado!</p>";
                }
            }, 1000);
        });
    </script>
</body>
</html>
