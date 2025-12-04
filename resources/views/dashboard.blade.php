<x-app-layout>
    <x-slot name="header"></x-slot>

    <!-- CONTAINER PRINCIPAL -->
    <div class="relative w-full h-[calc(100vh-85px)] bg-renews-noir-impure overflow-hidden flex flex-col items-center pb-6 font-sans">

        <div class="absolute inset-0 z-0 pointer-events-none" 
             style="background-image: url('/images/bg-stars.png'); background-size: cover; background-position: center;">
        </div>

        <div class="z-10 mt-6 mb-4 text-center">
            <h1 class="text-4xl font-bold text-white tracking-tight">
                Actus du 
                <span class="relative inline-block text-renews-vert text-5xl font-accent italic pr-2">
                    jour
                    <span class="absolute bottom-1 left-0 w-full h-0.5 bg-white shadow-[0_0_8px_rgba(255,255,255,0.8)]"></span>
                    <span class="absolute -top-1 -right-0 text-xl">✦</span>
                </span>
            </h1>
        </div>

        <!-- STACK DE CARTES -->
        <div id="card-stack" class="relative w-full max-w-[340px] aspect-[340/400] z-10 mx-auto flex-1 mb-4">
            
            <div class="absolute inset-0 flex items-center justify-center text-center p-6 text-white z-0">
                <div>
                    <h3 class="text-2xl font-bold mb-2">C'est tout pour aujourd'hui !</h3>
                    <p class="text-gray-400 mb-6">Revenez demain pour de nouvelles actus.</p>
                    <button onclick="location.reload()" class="px-6 py-3 bg-renews-vert text-black font-bold rounded-xl shadow-lg hover:scale-105 transition-transform">
                        Actualiser
                    </button>
                </div>
            </div>

            @foreach($articles as $index => $article)
                <!-- CARTE -->
                <div class="card absolute inset-0 rounded-[35px] shadow-2xl overflow-hidden cursor-grab active:cursor-grabbing transform transition-transform duration-300 origin-bottom select-none "
                     style="z-index: {{ 100 - $index }}; display: {{ $index === 0 ? 'block' : 'block' }}; --rotate: {{ $index === 0 ? '0deg' : ($index % 2 == 0 ? '2deg' : '-2deg') }}"
                     data-id="{{ $article->id }}">
                    
                    <div class="absolute inset-0 bg-gray-800">
                         <img src="{{ $article->thumbnail }}" class="w-full h-full object-cover pointer-events-none">
                         <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-transparent"></div>
                    </div>

                    <div class="absolute bottom-0 left-0 w-full p-6 pb-8 text-left pointer-events-none">
                        <span class="inline-block text-renews-vert font-accent italic text-3xl mb-1 drop-shadow-md">
                            {{ $article->theme->name ?? 'Actu' }}
                        </span>
                        
                        <h2 class="text-white text-3xl font-bold leading-tight tracking-tight drop-shadow-lg">
                            {{ $article->title }}
                        </h2>
                    </div>
                </div>
                
                @if($index === 0)
                <div id="card-shadow" class="absolute inset-0 bg-gray-800/50 rounded-[35px] -z-10 transform scale-95 translate-y-3 blur-sm"></div>
                @endif
            @endforeach
        </div>

        <!-- BOUTONS D'ACTION -->
        <div class="w-full max-w-xs px-4 pb-2 z-20 flex items-center justify-between mb-2">
            
            <button id="btn-reject" class="group flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.3)] hover:scale-110 active:scale-95 transition-all duration-200">
                <svg class="w-10 h-10 text-[#F14D3F]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- OEIL (Voir / Lire) -->
            <button id="btn-accept" class="group flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.3)] hover:scale-110 active:scale-95 transition-all duration-200">
                <svg class="w-10 h-10 text-renews-vert" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>

        </div>

    </div>

    <!-- SCRIPT SWIPE -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.card');
            const shadow = document.getElementById('card-shadow');
            const btnReject = document.getElementById('btn-reject');
            const btnAccept = document.getElementById('btn-accept');
            
            let currentIndex = 0;
            let startX = 0, currentX = 0, isDragging = false;
            const threshold = 100;

            function initCards() {
                cards.forEach((card, index) => {
                    if(index < currentIndex) {
                        card.style.display = 'none';
                    } else if (index === currentIndex) {
                        card.style.display = 'block';
                        card.style.transform = 'scale(1) translate(0px, 0px) rotate(0deg)';
                        card.style.zIndex = 100;
                        card.style.opacity = '1';
                        attachEvents(card);
                    } else {
                        const rotation = index % 2 === 0 ? '2deg' : '-2deg';
                        card.style.display = 'block';
                        card.style.transform = `scale(${1 - (index - currentIndex) * 0.05}) translateY(${(index - currentIndex) * 8}px) rotate(${rotation})`;
                        card.style.zIndex = 100 - index;
                        card.style.opacity = '1';
                    }
                });

                if(currentIndex >= cards.length) {
                    if(shadow) shadow.style.display = 'none';
                }
            }

            function attachEvents(card) {
                // Nettoyage
                card.removeEventListener('touchstart', startDrag);
                card.removeEventListener('touchmove', drag);
                card.removeEventListener('touchend', endDrag);
                card.removeEventListener('mousedown', startDrag);
                
                // Ajout
                card.addEventListener('touchstart', startDrag);
                card.addEventListener('touchmove', drag);
                card.addEventListener('touchend', endDrag);
                card.addEventListener('mousedown', startDrag);
                window.addEventListener('mousemove', drag);
                window.addEventListener('mouseup', endDrag);
            }

            function startDrag(e) {
                if(currentIndex >= cards.length) return;
                isDragging = true;
                startX = getClientX(e);
                cards[currentIndex].style.transition = 'none';
            }

            function drag(e) {
                if (!isDragging) return;
                currentX = getClientX(e);
                const diffX = currentX - startX;
                const rotation = diffX / 15;
                cards[currentIndex].style.transform = `translateX(${diffX}px) rotate(${rotation}deg)`;
            }

            function endDrag(e) {
                if (!isDragging) return;
                isDragging = false;
                const card = cards[currentIndex];
                const diffX = currentX - startX;
                card.style.transition = 'transform 0.4s ease-out';

                if (Math.abs(diffX) > threshold) {
                    const direction = diffX > 0 ? 1 : -1;
                    swipeCard(direction);
                } else {
                    card.style.transform = 'scale(1) translate(0px, 0px) rotate(0deg)';
                }
                
                window.removeEventListener('mousemove', drag);
                window.removeEventListener('mouseup', endDrag);
            }

            function swipeCard(direction) {
                const card = cards[currentIndex];
                const contentId = card.dataset.id;
                const endX = window.innerWidth * direction * 1.5;
                
                // Animation de sortie
                card.style.transform = `translateX(${endX}px) rotate(${direction * 30}deg)`;
                
                // 1. ENREGISTRER L'INTERACTION (VU)
                fetch('/content/' + contentId + '/seen', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                // 2. ACTION SUIVANTE
                if(direction === 1) { 
                    // Swipe Droite (Like/Oeil) -> Redirection vers vidéo
                    setTimeout(() => {
                        window.location.href = '/content/' + contentId;
                    }, 200);
                } else {
                    // Swipe Gauche (Passer) -> Carte suivante
                    setTimeout(() => {
                        currentIndex++;
                        initCards();
                    }, 300);
                }
            }

            function getClientX(e) {
                return e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
            }

            btnReject.addEventListener('click', () => swipeManual(-1));
            btnAccept.addEventListener('click', () => swipeManual(1));

            function swipeManual(direction) {
                if(currentIndex >= cards.length) return;
                const card = cards[currentIndex];
                card.style.transition = 'transform 0.5s ease-out';
                swipeCard(direction);
            }

            initCards();
        });
    </script>
</x-app-layout>