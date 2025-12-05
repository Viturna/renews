<x-app-layout>
    <div class="min-h-screen bg-renews-noir-impure text-white pb-24 font-sans">

        <a href="{{ route('dashboard') }}" class="fixed top-6 left-6 z-50 w-12 h-12 bg-white rounded-full flex items-center justify-center text-black shadow-lg hover:scale-110 transition duration-300">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>

        <div class="sticky top-0 z-40 w-full h-[85vh] bg-black flex items-center justify-center">
            <iframe 
                src="{{ $embedUrl }}" 
                class="w-full h-full" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>

        <div class="px-6 py-8 relative z-10 bg-renews-noir-impure mt-4">
            
            <span class="inline-block px-3 py-1 bg-renews-vert text-black font-bold font-accent italic text-sm rounded-full mb-4">
                {{ $dailyContent->theme->name }}
            </span>

            <h1 class="text-3xl font-bold leading-tight mb-4 tracking-tight text-white">
                {{ $dailyContent->title }}
            </h1>

            <div class="flex items-center text-gray-400 text-sm mb-8 border-b border-white/10 pb-6">
                <span class="mr-4 flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-white"></i> 
                    {{ \Carbon\Carbon::parse($dailyContent->publish_date)->format('d M Y') }}
                </span>
            </div>

            <div class="prose prose-invert max-w-none text-gray-300 leading-relaxed mb-12">
                <p>{{ $dailyContent->description }}</p>
            </div>

            <div class="border-t border-white/10 pt-8">
                <h3 class="font-bold text-xl mb-6 flex items-center gap-2 text-white">
                    Commentaires
                    <span id="comments-count" class="px-2 py-0.5 bg-white/10 text-white text-xs rounded-full font-normal">
                        {{-- CORRECTION : On compte simplement le total des commentaires récupérés --}}
                        {{ $dailyContent->comments->count() }}
                    </span>
                </h3>

                <form action="{{ route('content.comment', $dailyContent->id) }}" method="POST" class="ajax-comment-form mb-8 flex gap-3">
                    @csrf
                    <div class="flex-1 relative">
                        <textarea name="body" rows="1" placeholder="Ajouter un commentaire..." required
                                  class="w-full bg-[#1a1a1a] border border-white/5 rounded-2xl py-3 px-4 text-white placeholder-gray-500 focus:ring-2 focus:ring-renews-vert focus:border-transparent resize-none overflow-hidden transition-all shadow-inner"
                                  oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                    </div>
                    <button type="submit" class="w-12 h-10 rounded-full bg-renews-vert text-black flex items-center justify-center hover:scale-110 hover:brightness-110 transition shrink-0 self-end mb-1">
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                    </button>
                </form>

                <div id="comments-list" class="space-y-6">
                    {{-- CORRECTION : On filtre pour ne boucler que sur les Parents (parent_id == null) --}}
                    @forelse($dailyContent->comments->where('parent_id', null) as $comment)
                        
                        <div x-data="{ showReply: false, replyContent: '' }">
                            
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 border border-white/10 flex items-center justify-center font-bold text-xs shrink-0 text-white shadow-md">
                                    {{ substr($comment->user->username, 0, 1) }}
                                </div>
                                
                                <div class="flex-1">
                                    <div class="bg-[#1a1a1a] rounded-2xl rounded-tl-none px-4 py-3 border border-white/5 shadow-sm">
                                        <div class="flex justify-between items-baseline mb-1">
                                            <span class="font-bold text-sm text-white">{{ $comment->user->username }}</span>
                                            <span class="text-[10px] text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-300 leading-relaxed">{{ $comment->body }}</p>
                                    </div>
                                    <button @click="showReply = !showReply; replyContent = '@' + '{{ $comment->user->username }} ';" 
                                            class="text-xs text-gray-500 mt-2 ml-2 hover:text-white transition flex items-center gap-1 group">
                                        <i class="fa-solid fa-reply text-[10px] group-hover:-rotate-12 transition-transform"></i> Répondre
                                    </button>
                                </div>
                            </div>

                            {{-- Les réponses sont affichées ici via la relation 'replies', donc pas besoin de les avoir dans la boucle principale --}}
                            @if($comment->replies->count() > 0)
                                <div class="ml-11 mt-3 space-y-3 pl-3 border-l-2 border-white/5">
                                    @foreach($comment->replies as $reply)
                                        
                                        <div x-data="{ showSubReply: false, subReplyContent: '' }">
                                            <div class="flex gap-3">
                                                <div class="w-6 h-6 rounded-full bg-gray-800 border border-white/5 flex items-center justify-center font-bold text-[10px] shrink-0 text-gray-400">
                                                    {{ substr($reply->user->username, 0, 1) }}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="bg-[#111] rounded-xl rounded-tl-none px-3 py-2 border border-white/5">
                                                        <div class="flex justify-between items-baseline mb-0.5">
                                                            <span class="font-bold text-xs text-gray-300">{{ $reply->user->username }}</span>
                                                            <span class="text-[9px] text-gray-600">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs text-gray-400">{{ $reply->body }}</p>
                                                    </div>
                                                    
                                                    <button @click="showSubReply = !showSubReply; subReplyContent = '@' + '{{ $reply->user->username }} ';" 
                                                            class="text-[10px] text-gray-600 mt-1 ml-2 hover:text-gray-300 transition">
                                                        Répondre
                                                    </button>
                                                </div>
                                            </div>

                                            <div x-show="showSubReply" style="display: none;" class="mt-2 ml-9" x-transition>
                                                <form action="{{ route('content.comment', $dailyContent->id) }}" method="POST" class="ajax-comment-form flex gap-2">
                                                    @csrf
                                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                    
                                                    <input type="text" name="reply_body" x-model="subReplyContent" placeholder="Répondre..." required
                                                           class="flex-1 bg-[#111] border border-white/10 rounded-xl py-1.5 px-3 text-xs text-white focus:ring-1 focus:ring-renews-vert placeholder-gray-600">
                                                    
                                                    <button type="submit" class="text-renews-vert hover:text-white text-[10px] font-bold px-2 uppercase tracking-wide">
                                                        Envoyer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                            @endif

                            <div x-show="showReply" style="display: none;" class="ml-11 mt-3" x-transition>
                                <form action="{{ route('content.comment', $dailyContent->id) }}" method="POST" class="ajax-comment-form flex gap-2">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    
                                    <input type="text" name="reply_body" x-model="replyContent" placeholder="Répondre..." required
                                           class="flex-1 bg-[#111] border border-white/10 rounded-xl py-2 px-3 text-sm text-white focus:ring-1 focus:ring-renews-vert placeholder-gray-600">
                                    <button type="submit" class="text-renews-vert hover:text-white text-xs font-bold px-2 uppercase tracking-wide">
                                        Envoyer
                                    </button>
                                </form>
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-500">
                                <i class="fa-regular fa-comments"></i>
                            </div>
                            <p class="text-gray-500 text-sm italic">Soyez le premier à lancer la discussion !</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const handleCommentSubmit = (e) => {
                if (!e.target.matches('.ajax-comment-form')) return;

                e.preventDefault();
                const form = e.target;
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnHtml = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newCommentsList = doc.querySelector('#comments-list');
                    const currentCommentsList = document.querySelector('#comments-list');
                    if (newCommentsList && currentCommentsList) {
                        currentCommentsList.innerHTML = newCommentsList.innerHTML;
                    }

                    const newCount = doc.querySelector('#comments-count');
                    const currentCount = document.querySelector('#comments-count');
                    if (newCount && currentCount) {
                        currentCount.innerHTML = newCount.innerHTML;
                    }

                    form.reset();
                    const textarea = form.querySelector('textarea');
                    if (textarea) textarea.style.height = '';
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de l\'envoi du commentaire.');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;
                });
            };

            document.addEventListener('submit', handleCommentSubmit);
        });
    </script>
</x-app-layout>