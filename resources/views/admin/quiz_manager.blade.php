<x-admin-layout>
    <div class="min-h-screen pb-12">
        <!-- Sub-header -->
        <div class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
            <div class="max-w-7xl mx-auto py-4 px-6 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-renews-vert">
                        <i class="fa-solid fa-gamepad text-xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-gray-900 tracking-tight leading-tight">
                            Édition du Quiz
                        </h2>
                        <p class="text-sm text-gray-500">Pour : <span class="font-medium text-gray-800">{{ $dailyContent->title }}</span></p>
                    </div>
                </div>
                <a href="{{ route('admin.contents.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Retour
                </a>
            </div>
        </div>

        <div class="max-w-4xl mx-auto mt-8 px-4">
            
            <form action="{{ route('admin.quiz.update', $dailyContent->id) }}" method="POST">
                @csrf
                
                <div x-data="quizForm()" class="space-y-6">
                    
                    <!-- Liste des Questions -->
                    <template x-for="(question, qIndex) in questions" :key="qIndex">
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm relative group transition-all hover:shadow-md">
                            
                            <!-- Indicateur visuel -->
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-renews-vert rounded-l-xl opacity-50 group-hover:opacity-100 transition-opacity"></div>

                            <!-- Header Question -->
                            <div class="flex justify-between items-center mb-6 pl-2">
                                <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                                    <span class="bg-gray-100 text-xs px-2 py-1 rounded text-gray-600 font-bold uppercase tracking-wider">Question <span x-text="qIndex + 1"></span></span>
                                </h3>
                                <button type="button" @click="removeQuestion(qIndex)" class="text-red-500 text-sm hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition-colors flex items-center gap-1 font-medium">
                                    <i class="fa-solid fa-trash-can"></i> Supprimer
                                </button>
                            </div>

                            <!-- Input Question -->
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Intitulé de la question</label>
                                <input type="text" :name="'questions['+qIndex+'][text]'" x-model="question.text" 
                                       class="w-full bg-gray-50 border-gray-300 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert placeholder-gray-400" 
                                       required placeholder="Ex: Quel est le sujet principal de l'article ?">
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Points (XP)</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" :name="'questions['+qIndex+'][points]'" x-model="question.points" 
                                           class="w-24 bg-gray-50 border-gray-300 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert font-mono" 
                                           value="10">
                                    <span class="text-sm text-gray-500">XP</span>
                                </div>
                            </div>

                            <!-- Réponses -->
                            <div class="space-y-3 bg-gray-50 p-5 rounded-xl border border-gray-100">
                                <label class="block text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-list-check text-gray-400"></i> Réponses
                                    <span class="text-xs font-normal text-gray-500 bg-white px-2 py-0.5 rounded border border-gray-200">Cochez la bonne réponse</span>
                                </label>
                                
                                <template x-for="(answer, aIndex) in question.answers" :key="aIndex">
                                    <div class="flex items-center gap-3 group/answer">
                                        <div class="relative flex items-center justify-center shrink-0">
                                            <input type="radio" :name="'questions['+qIndex+'][correct_index]'" :value="aIndex" 
                                                   class="peer appearance-none w-6 h-6 border-2 border-gray-300 rounded-full checked:border-renews-vert checked:bg-renews-vert cursor-pointer transition-all hover:border-gray-400" 
                                                   required :checked="question.correct_index == aIndex">
                                            <i class="fa-solid fa-check text-black text-[10px] absolute opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></i>
                                        </div>
                                        
                                        <input type="text" :name="'questions['+qIndex+'][answers]['+aIndex+'][text]'" x-model="answer.text" 
                                               class="flex-1 bg-white border-gray-300 rounded-lg text-gray-900 text-sm focus:border-renews-vert focus:ring-renews-vert placeholder-gray-400 shadow-sm" 
                                               :placeholder="'Réponse ' + (aIndex + 1)" required>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </template>

                    <!-- Boutons Actions -->
                    <div class="flex flex-col sm:flex-row justify-between items-center pt-6 pb-20 gap-4">
                        <button type="button" @click="addQuestion()" class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3 bg-white text-gray-700 rounded-xl hover:bg-gray-50 border border-gray-300 font-bold transition-all shadow-sm">
                            <i class="fa-solid fa-plus text-renews-vert"></i> Ajouter une question
                        </button>

                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-renews-vert text-black font-black uppercase tracking-wide rounded-xl hover:bg-[#66e007] shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            Sauvegarder le Quiz
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    @php
        $initialQuestions = $dailyContent->questions->count() 
            ? $dailyContent->questions->map(function($q) {
                return [
                    'text' => $q->question_text,
                    'points' => $q->points_value,
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

    <script>
        function quizForm() {
            return {
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
</x-admin-layout>