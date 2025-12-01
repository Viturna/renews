<x-app-layout>
    <div class="min-h-screen bg-gray-100 pb-12">
        <div class="bg-renews-noir-impure text-white shadow-lg border-b border-renews-vert">
            <div class="max-w-7xl mx-auto py-6 px-4 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-xl font-gaming uppercase tracking-widest text-renews-electric">
                        Édition Quiz
                    </h2>
                    <p class="text-sm text-gray-300">Pour : {{ $dailyContent->title }}</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="text-sm hover:text-renews-vert underline">Retour</a>
            </div>
        </div>

        <div class="max-w-4xl mx-auto mt-8 px-4">
            
            <form action="{{ route('admin.quiz.update', $dailyContent->id) }}" method="POST">
                @csrf
                
                <!-- Gestionnaire Alpine.js -->
                <div x-data="quizForm()" class="space-y-6">
                    
                    <!-- Liste des Questions -->
                    <template x-for="(question, qIndex) in questions" :key="qIndex">
                        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                            
                            <!-- Header Question -->
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-lg text-gray-800">Question <span x-text="qIndex + 1"></span></h3>
                                <button type="button" @click="removeQuestion(qIndex)" class="text-red-500 text-sm hover:underline">Supprimer</button>
                            </div>

                            <!-- Input Question -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Intitulé de la question</label>
                                <input type="text" :name="'questions['+qIndex+'][text]'" x-model="question.text" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Ex: Qui a gagné les élections ?">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Points</label>
                                <input type="number" :name="'questions['+qIndex+'][points]'" x-model="question.points" class="mt-1 w-24 rounded-md border-gray-300 shadow-sm" value="10">
                            </div>

                            <!-- Réponses -->
                            <div class="space-y-2 pl-4 border-l-2 border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Réponses (Cochez la bonne réponse)</label>
                                
                                <template x-for="(answer, aIndex) in question.answers" :key="aIndex">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" :name="'questions['+qIndex+'][correct_index]'" :value="aIndex" class="text-renews-vert focus:ring-renews-vert w-5 h-5 cursor-pointer" required :checked="question.correct_index == aIndex">
                                        <input type="text" :name="'questions['+qIndex+'][answers]['+aIndex+'][text]'" x-model="answer.text" class="flex-1 rounded-md border-gray-300 shadow-sm text-sm" :placeholder="'Réponse ' + (aIndex + 1)" required>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </template>

                    <!-- Boutons Actions -->
                    <div class="flex justify-between items-center pt-4 pb-10">
                        <button type="button" @click="addQuestion()" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition font-medium">
                            + Ajouter une question
                        </button>

                        <button type="submit" class="bg-renews-vert text-black font-bold px-6 py-3 rounded hover:bg-renews-electric transition shadow-lg transform hover:scale-105">
                            Sauvegarder le Quiz
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    @php
        // Préparation des données en PHP pour éviter les erreurs de parsing Blade dans @json
        $initialQuestions = $dailyContent->questions->count() 
            ? $dailyContent->questions->map(function($q) {
                return [
                    'text' => $q->question_text,
                    'points' => $q->points_value,
                    // Trouve l'index de la réponse correcte
                    'correct_index' => $q->answers->search(fn($a) => $a->is_correct) !== false 
                        ? $q->answers->search(fn($a) => $a->is_correct) 
                        : 0,
                    'answers' => $q->answers->map(fn($a) => ['text' => $a->answer_text])->values()->toArray()
                ];
            })->values()->toArray()
            : [[
                'text' => '', 
                'points' => 10, 
                'correct_index' => 0, 
                'answers' => [['text' => ''], ['text' => ''], ['text' => ''], ['text' => '']]
            ]];
    @endphp

    <!-- Données existantes injectées depuis PHP -->
    <script>
        function quizForm() {
            return {
                // On récupère les questions existantes, ou on en met une vide par défaut
                questions: @json($initialQuestions),
                
                addQuestion() {
                    this.questions.push({
                        text: '',
                        points: 10,
                        correct_index: 0,
                        answers: [{text: ''}, {text: ''}, {text: ''}, {text: ''}]
                    });
                },
                
                removeQuestion(index) {
                    if (confirm('Voulez-vous vraiment supprimer cette question ?')) {
                        this.questions.splice(index, 1);
                    }
                }
            }
        }
    </script>
</x-app-layout>