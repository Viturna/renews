<x-app-layout>
    <div x-data="quizGame()" x-init="initGame()" class="min-h-screen bg-renews-noir-impure text-white relative font-sans overflow-hidden pb-24">

        <div class="absolute inset-0 z-0 pointer-events-none" 
             style="background-image: url('/images/bg-stars.png'); background-size: cover; background-position: center;">
        </div>

        <div x-show="showLoader" 
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-renews-noir-impure h-full w-full">
            
            <div class="relative text-center transform scale-110">
                <div class="absolute -top-10 -right-6 text-renews-vert text-6xl animate-pulse" style="transform: rotate(15deg);">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                
                <h1 class="text-8xl font-black tracking-tighter leading-none text-white drop-shadow-lg relative z-10">
                    Quiz
                </h1>
                
                <h1 class="text-8xl font-accent italic font-medium tracking-tighter leading-none text-renews-vert -mt-4 relative z-10">
                    Time
                </h1>
                
                <div class="w-[110%] h-1.5 bg-white mt-2 -ml-[5%] rounded-full"></div>
                
                <div class="absolute -bottom-10 -right-10 text-renews-vert text-6xl animate-spin-slow">
                    <i class="fa-solid fa-star-of-life"></i>
                </div>
            </div>
        </div>

        <div x-show="!showLoader && !showResults" 
             x-transition:enter="transition ease-out duration-500 delay-300"
             x-transition:enter-start="opacity-0 translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display: none;" 
             class="relative z-10 px-6 pt-8 h-full flex flex-col max-w-md mx-auto">
            
            <div class="flex justify-between items-start mb-6">
                <div class="leading-tight">
                    <span class="block text-4xl font-bold text-white tracking-tight">Quiz de</span>
                    <span class="block text-4xl font-bold tracking-tight">
                        la <span class="text-renews-vert font-accent italic border-b-2 border-white pb-1">veille</span>
                        <i class="fa-solid fa-star text-renews-vert text-sm align-top ml-1"></i>
                    </span>
                </div>
            </div>

            <div class="mb-8 flex flex-col">
                <div class="w-full h-3 bg-[#3a3a3a] overflow-hidden">
                    <div class="h-full bg-renews-vert transition-all duration-500 ease-out shadow-[0_0_15px_#74FD08]"
                         :style="'width: ' + ((currentStep + 1) / totalQuestions * 100) + '%'"></div>
                </div>
                 <div class="text-renews-fonce font-bold text-2xl">
                    <span x-text="currentStep + 1" class="text-renews-vert">1</span><span class="text-gray-600 text-lg">/<span x-text="totalQuestions">5</span></span>
                </div>
            </div>

            <div class="flex justify-center mb-6">
                <div class="text-7xl filter drop-shadow-xl transform transition-transform duration-500 hover:scale-110" :key="currentStep">
                    🧑‍⚖️
                </div>
            </div>

            <h3 class="text-2xl font-black text-center mb-8 leading-tight min-h-[80px] flex items-center justify-center tracking-tight" x-text="currentQuestion.question_text">
                Chargement...
            </h3>

            <div class="space-y-3 flex-1">
                <template x-for="answer in currentQuestion.answers" :key="answer.id">
                    <button @click="selectAnswer(answer.id)"
                            :class="selectedAnswer === answer.id 
                                ? 'bg-renews-vert ring-4 ring-renews-vert scale-[1.02]' 
                                : 'bg-white hover:bg-gray-100'"
                            class="w-full py-3 px-6 rounded-xl font-medium text-black text-lg shadow-lg transition-all duration-200 text-center relative">
                        <span x-text="answer.answer_text"></span>
                    </button>
                </template>
            </div>

            <div class="mt-6 mb-2">
                <button @click="validateAnswer()"
                        :disabled="!selectedAnswer"
                        :class="!selectedAnswer ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:scale-[1.02] active:scale-95'"
                        class="w-full py-4 bg-renews-vert text-white font-black text-2xl rounded-xl transition-all uppercase tracking-wide">
                    Je valide
                </button>
            </div>
        </div>

        <div x-show="showResults" 
             x-transition:enter="transition ease-out duration-700"
             x-transition:enter-start="opacity-0 translate-y-20"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display: none;" 
             class="absolute inset-0 z-50 flex flex-col items-center pt-12 px-6 bg-renews-noir-impure w-full h-full">
            
            <div class="text-center mb-8 relative">
                <h1 class="text-4xl font-bold text-white tracking-tight">
                    Tes <span class="relative inline-block text-renews-vert font-accent italic">
                        résultats
                        <span class="absolute bottom-1 left-0 w-full h-0.5 bg-white"></span>
                        <span class="absolute -top-1 -right-3 text-xl">✦</span>
                    </span>
                </h1>
                <div class="mt-4 inline-block px-4 py-1 bg-white text-renews-fonce rounded-full font-bold font-accent italic text-lg border border-renews-vert">
                    {{ $content->theme->name }}
                </div>
            </div>

            <div class="relative w-64 h-64 mb-8 flex items-center justify-center">
                <div class="absolute inset-0 border-[1px] border-white rounded-full"></div>
                
                <div class="text-center z-10 flex flex-col items-center leading-none">
                    <span class="text-[10rem] font-black italic text-white drop-shadow-xl" 
                          style="font-family: 'Instrument Sans', sans-serif; -webkit-text-stroke: 3px white; color: transparent;"
                          x-text="score">0</span>
                    <span class="text-3xl font-medium text-white mt-[-20px]">sur <span x-text="totalQuestions">5</span></span>
                </div>

                <div class="absolute bottom-4 right-0 w-24 h-24 bg-renews-vert flex items-center justify-center rotate-12 shadow-lg animate-bounce-slow" 
                     style="clip-path: polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%);">
                    <div class="text-center leading-tight">
                        <span class="block text-2xl font-black text-white drop-shadow-md" x-text="'+' + (score * 10)">+0</span>
                        <span class="block text-sm font-bold text-white">XP</span>
                    </div>
                </div>
            </div>

            <div class="text-center max-w-xs mx-auto mb-8">
                <h2 class="text-5xl font-black text-white mb-4 tracking-tighter">
                    <span x-show="score >= totalQuestions">Parfait !</span>
                    <span x-show="score < totalQuestions && score > 0">Pas mal !</span>
                    <span x-show="score == 0">Oups...</span>
                </h2>
                <p class="text-gray-400 text-lg leading-snug">
                    <span x-show="score > 0">Tu as gagné de l'expérience pour monter de niveau !</span>
                    <span x-show="score == 0">Retente ta chance demain pour gagner des XP.</span>
                </p>
            </div>

            <div class="w-full max-w-md">
                <button @click="$refs.quizForm.submit()"
                        class="w-full py-4 bg-renews-vert text-white font-black text-2xl rounded-xl hover:scale-105 transition-transform uppercase">
                    Réclamer mes XP <i class="fa-solid fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <form x-ref="quizForm" method="POST" action="{{ route('quiz.store') }}" class="hidden">
            @csrf
            <template x-for="(answerId, questionId) in userAnswers">
                <input type="hidden" :name="'answers[' + questionId + ']'" :value="answerId">
            </template>
        </form>

    </div>

    <script>
        function quizGame() {
            return {
                showLoader: true,
                showResults: false,
                currentStep: 0,
                selectedAnswer: null,
                score: 0,
                
                // Stockage des réponses : { id_question: id_reponse }
                userAnswers: {}, 
                
                // Chargement des questions depuis Laravel
                questions: @json($content->questions->load('answers')),
                
                get currentQuestion() {
                    return this.questions[this.currentStep];
                },

                get totalQuestions() {
                    return this.questions.length;
                },

                initGame() {
                    setTimeout(() => {
                        this.showLoader = false;
                    }, 1500); // Petit délai pour voir l'anim d'intro
                },

                selectAnswer(id) {
                    this.selectedAnswer = id;
                },

                validateAnswer() {
                    if (!this.selectedAnswer) return;

                    // 1. Sauvegarde visuelle du score (Feedback immédiat)
                    const answer = this.currentQuestion.answers.find(a => a.id === this.selectedAnswer);
                    if (answer && answer.is_correct) {
                        this.score++;
                    }

                    // 2. Sauvegarde technique pour l'envoi au serveur
                    // On stocke la réponse associée à l'ID de la question
                    this.userAnswers[this.currentQuestion.id] = this.selectedAnswer;

                    // 3. Passage à la suite
                    setTimeout(() => {
                        if (this.currentStep < this.questions.length - 1) {
                            this.currentStep++;
                            this.selectedAnswer = null;
                        } else {
                            this.finishGame();
                        }
                    }, 300);
                },

                finishGame() {
                    this.showResults = true;
                }
            }
        }
    </script>

    <style>
        .animate-spin-slow { animation: spin 6s linear infinite; }
        .animate-bounce-slow { animation: bounce 3s infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</x-app-layout>