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
            background-color: #fdf2f8; /* Fallback background */
            margin: 0;
            padding: 0;
        }

        .hero-section {
            position: relative;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            overflow: hidden;
            padding-top: 152px; /* Espacio para el reproductor de Spotify */
            box-sizing: border-box;
        }

        .hero-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            z-index: -1;
        }

        .hero-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.5rem;
            font-weight: 600;
            text-shadow: 0 0 15px rgba(0,0,0,0.7);
            -webkit-text-stroke: 0.5px rgba(0,0,0,0.2);
        }

        .hero-name {
            font-family: 'Dancing Script', cursive;
            font-size: 6rem;
            margin: -1rem 0;
            text-shadow: 0 0 15px rgba(0,0,0,0.7);
            -webkit-text-stroke: 0.5px rgba(0,0,0,0.2);
        }

        .hero-subtitle {
            font-family: 'Dancing Script', cursive;
            font-size: 4rem;
            margin-top: -1.5rem;
            text-shadow: 0 0 15px rgba(0,0,0,0.7);
            -webkit-text-stroke: 0.5px rgba(0,0,0,0.2);
        }

        .spotify-player {
            position: absolute;
            top: 2rem;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 400px;
            z-index: 10;
        }
        
        #countdown {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .countdown-item {
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .countdown-item div:first-child {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
        }

        .countdown-item div:last-child {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        .details-section {
            padding: 3rem 1.5rem;
            background-color: #fff;
            max-width: 500px;
            margin: 2rem auto;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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

        .action-button:hover { transform: translateY(-2px); }

        .accept-button {
            background: linear-gradient(45deg, #ec4899, #d946ef);
            box-shadow: 0 4px 15px rgba(216, 92, 157, 0.4);
            color: white;
        }

        .accept-button:hover { box-shadow: 0 6px 20px rgba(216, 92, 157, 0.5); }

        .reject-button { background-color: #f8fafc; color: #9ca3af; border: 1px solid #e5e7eb; }
        .reject-button:hover { background-color: #e2e8f0; }
        .hidden { display: none; }

        @media (max-width: 480px) {
            #countdown {
                gap: 0.5rem;
            }
            .countdown-item {
                padding: 0.8rem 0.5rem;
            }
            .countdown-item div:first-child {
                font-size: 2rem;
            }
            .countdown-item div:last-child {
                font-size: 0.6rem;
            }
            .hero-name {
                font-size: 4.5rem;
            }
            .hero-subtitle {
                font-size: 3rem;
            }
            .details-section {
                margin: 1rem;
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <video autoplay muted loop class="hero-video">
            <source src="vid152.mp4" type="video/mp4">
        </video>
        <div class="spotify-player">
        <iframe data-testid="embed-iframe" style="border-radius:12px" src="https://open.spotify.com/embed/track/26CCGV25A8MAy0L6Z6rtKO?utm_source=generator" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
        </div>

        <h1 class="hero-title">XV Años</h1>
        <h2 class="hero-name">Giuliana</h2>
        <h3 class="hero-subtitle">Quinceañera</h3>
        
        <div id="countdown">
            <div class="countdown-item">
                <div id="days">00</div>
                <div>DÍAS</div>
            </div>
            <div class="countdown-item">
                <div id="hours">00</div>
                <div>HORAS</div>
            </div>
            <div class="countdown-item">
                <div id="minutes">00</div>
                <div>MINUTOS</div>
            </div>
            <div class="countdown-item">
                <div id="seconds">00</div>
                <div>SEGUNDOS</div>
            </div>
        </div>
    </div>

    <div class="details-section">
        <p class="text-gray-600 mb-6 leading-relaxed text-center">
            Porque eres parte de mi historia, quiero que estés en el comienzo de un nuevo capítulo. Te invito a celebrar la noche de mis sueños.
        </p>

        <div class="space-y-4 text-left my-8">
            <div class="flex items-center space-x-4"><i data-lucide="calendar-heart" class="details-icon"></i><span class="font-semibold text-gray-700">Sábado, 11 de Octubre, 2025</span></div>
            <div class="flex items-center space-x-4"><i data-lucide="clock" class="details-icon"></i><span class="font-semibold text-gray-700">5:00 PM</span></div>
            <div class="flex items-center space-x-4">
                <i data-lucide="map-pin" class="details-icon"></i>
                <a href="https://maps.app.goo.gl/SdTWZm1bMUBuBeev5" target="_blank" rel="noopener noreferrer" class="font-semibold text-gray-700 hover:text-pink-600 transition-colors">
                    Sala de Eventos Nila salón TRAVENTINO
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <i data-lucide="shirt" class="details-icon"></i>
                <span class="font-semibold text-gray-700">Código de Vestimenta: Formal</span>
            </div>
            
        </div>


        <?php if ($current_guest_name): ?>
            <div class="mb-6 text-center">
                <p class="text-xl text-gray-700">Invitación especial para</p>
                <p class="text-3xl font-semibold title-script text-pink-500"><?php echo htmlspecialchars($current_guest_name); ?></p>
            </div>
        <?php endif; ?>
        
        <div id="rsvp-section" class="<?php echo ($guest_status === 'N/A') ? '' : 'hidden'; ?>">
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
            const countdownDate = new Date('October 11, 2025 17:00:00').getTime();

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
