@extends('layouts.app')

@section('content')
    @guest
        <div class="alert alert-danger mb-4">
            <i class="fas fa-info-circle me-2"></i>
            Connectez-vous pour sauvegarder vos scores et participer au classement. Car sans cela, vos points (scores) ne seront
            jamais envoyés ni reconnus à la direction !
        </div>
    @endguest
    <div class="container py-5">
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Chronomètre sticky -->
        <div class="fixed top-20 right-4 z-[1000]" id="countdown-container">
            <div class="bg-black text-white font-bold px-6 py-4 rounded-lg shadow-2xl transition-all duration-300 flex items-center justify-center"
                id="countdown-box" style="min-width: 140px;">
                <span id="time-display" class="text-7xl leading-none">10</span>
                <span class="text-6xl ml-1 self-end">S</span>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-primary text-white position-relative">
                <h2 class="mb-0">Quiz des Capitales</h2>
            </div>

            <div class="card-body">
                @if ($countries->isEmpty())
                    <div class="alert alert-warning">
                        Aucune question disponible pour le moment
                    </div>
                @else
                    <form id="quiz-form" action="{{ route('game.check') }}" method="POST">
                        @csrf
                        <input type="hidden" name="time_used" id="time-used" value="0">

                        @foreach ($countries as $index => $country)
                            <div class="mb-4 question-container">
                                <h4 class="question">
                                    <span class="badge bg-secondary me-2">{{ $index + 1 }}/10</span>
                                    Quelle est la capitale de : <strong>{{ $country->name }}</strong> ?
                                </h4>
                                <input type="text" name="answers[{{ $country->id }}]"
                                    class="form-control form-control-lg answer-input" autocomplete="off"
                                    placeholder="Entrez la capitale">
                            </div>
                        @endforeach

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                                <i class="fas fa-check-circle me-2"></i> Valider les réponses
                            </button>
                        </div>
                    </form>

                    <!-- Audio pour l'alarme -->
                    <audio id="alarm" preload="auto">
                        <source src="{{ asset('sounds/alarm.mp3') }}" type="audio/mpeg">
                        Votre navigateur ne supporte pas l'élément audio.
                    </audio>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Style pour le chronomètre sticky */
        #countdown-container {
            position: fixed;
            top: 6rem;
            right: 1rem;
            z-index: 1000;
        }

        #countdown-box {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            min-width: 140px !important;
            transition: all 0.3s ease;
        }

        #time-display {
            font-size: 4.5rem !important;
            line-height: 1 !important;
        }

        /* Animations */
        .animate-pulse {
            animation: pulse 1s infinite;
        }

        .animate-danger {
            animation: danger 0.5s;
        }

        .time-expired {
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.7);
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 20px rgba(255, 0, 0, 0.8);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
            }
        }

        @keyframes danger {
            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-8px);
            }

            50% {
                transform: translateX(8px);
            }

            75% {
                transform: translateX(-8px);
            }

            100% {
                transform: translateX(0);
            }
        }

        /* Adaptation mobile */
        @media (max-width: 768px) {
            #countdown-container {
                top: 5rem;
                right: 0.5rem;
            }

            #countdown-box {
                padding: 0.5rem;
                min-width: 100px !important;
            }

            #time-display {
                font-size: 3.5rem !important;
            }
        }
    </style>

    <script>
        // Configuration
        const TOTAL_TIME = 60;
        let timeLeft = TOTAL_TIME;
        let timerActive = true;
        const startTime = new Date().getTime();3

        // Éléments DOM
        const countdownElement = document.getElementById('time-display');
        const timeUsedInput = document.getElementById('time-used');
        const answerInputs = document.querySelectorAll('.answer-input');
        const submitBtn = document.getElementById('submit-btn');
        const alarmSound = document.getElementById('alarm');
        const quizForm = document.getElementById('quiz-form');
        const countdownBox = document.getElementById('countdown-box');

        // Fonction de désactivation
        function disableQuiz() {
            // Désactive tous les champs
            answerInputs.forEach(input => {
                input.disabled = true;
                input.classList.add('bg-gray-100', 'border-2', 'border-danger');
                input.placeholder = "Desolé, temps expiré !";
            });

            // Désactive et modifie le bouton
            submitBtn.disabled = true;
            submitBtn.classList.replace('btn-primary', 'btn-danger');
            submitBtn.innerHTML = '<i class="fas fa-ban me-2"></i>DESOLÉ, TEMPS EXPIRÉ, VOUS NE POURREZ NI REJOUER NI VALIDER !';

            // Change radicalement le style du chrono
            countdownBox.classList.remove('bg-black', 'animate-pulse');
            countdownBox.classList.add('bg-danger-700', 'border-2', 'text-white', 'border-danger', 'time-expired');
            countdownBox.style.textShadow = '0 0 15px rgba(255,255,255,0.9)';

            // Force la taille du texte
            countdownElement.classList.add('text-7xl');
            countdownElement.classList.remove('text-sm', 'text-md', 'text-lg');
            countdownElement.textContent = '0';

            // Joue l'alarme et animation
            alarmSound.play();
            dangerAnimation();

            // Bloque la soumission
            quizForm.onsubmit = function(e) {
                e.preventDefault();
                return false;
            };
        }

        // Animation de danger renforcée
        function dangerAnimation() {
            let count = 0;
            const interval = setInterval(() => {
                countdownBox.classList.toggle('animate-danger');
                if (++count === 6) clearInterval(interval);
            }, 500);
        }

        // Fonction de mise à jour du chrono
        function updateTimer() {
            if (!timerActive) return;

            const elapsedSeconds = Math.floor((new Date().getTime() - startTime) / 1000);
            timeLeft = Math.max(0, TOTAL_TIME - elapsedSeconds);

            // Force la taille du texte à chaque mise à jour
            countdownElement.classList.add('text-7xl');
            countdownElement.classList.remove('text-sm', 'text-md', 'text-lg');
            countdownElement.textContent = timeLeft;

            timeUsedInput.value = Math.min(elapsedSeconds, TOTAL_TIME);

            // Alerte visuelle
            if (timeLeft <= 5 && timeLeft > 0) {
                countdownBox.classList.add('animate-pulse');
            } else {
                countdownBox.classList.remove('animate-pulse');
            }

            // Quand le temps est écoulé
            if (timeLeft <= 0) {
                timerActive = false;
                clearInterval(timerInterval);
                disableQuiz();
            }
        }

        // Lance le timer
        const timerInterval = setInterval(updateTimer, 200);

        // Gestion du rafraîchissement de la page
        window.addEventListener('beforeunload', function(e) {
            if (timerActive && timeLeft > 0) {
                e.preventDefault();
                e.returnValue = 'Vous avez un quiz en cours. Êtes-vous sûr de vouloir quitter ?';
            }
        });
    </script>
@endsection
