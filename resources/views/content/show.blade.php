<x-app-layout>
    <div class="min-h-screen bg-renews-noir-impure text-white pb-24 font-sans">

        <div class="fixed top-0 left-0 w-full z-50 p-4 flex justify-between items-start bg-gradient-to-b from-black/80 to-transparent pointer-events-none">
            <a href="{{ route('dashboard') }}" class="pointer-events-auto w-10 h-10 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white/20 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

        <div class="sticky top-0 z-40 w-full aspect-video bg-black shadow-2xl">
            <iframe 
                src="{{ $embedUrl }}" 
                class="w-full h-full" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>

        <div class="px-6 py-8 relative z-10">
            
            <span class="inline-block px-3 py-1 bg-renews-vert text-black font-bold font-accent italic text-sm rounded-full mb-4">
                {{ $dailyContent->theme->name }}
            </span>

            <h1 class="text-3xl font-bold leading-tight mb-4 tracking-tight">
                {{ $dailyContent->title }}
            </h1>

            <div class="flex items-center text-gray-400 text-sm mb-8 border-b border-gray-800 pb-6">
                <span class="mr-4"><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($dailyContent->publish_date)->format('d M Y') }}</span>
            </div>

            <div class="prose prose-invert max-w-none text-gray-300 leading-relaxed mb-12">
                <p>{{ $dailyContent->description }}</p>
            </div>

            <div class="border-t border-gray-800 pt-8">
                <h3 class="font-bold text-xl mb-6 flex items-center gap-2">
                    Commentaires
                    <span class="text-gray-500 text-sm font-normal">({{ $dailyContent->comments->count() + $dailyContent->comments->sum(fn($c) => $c->replies->count()) }})</span>
                </h3>

                <form action="{{ route('content.comment', $dailyContent->id) }}" method="POST" class="mb-8 flex gap-3">
                    @csrf
                    <div class="flex-1">
                        <textarea name="body" rows="1" placeholder="Ajouter un commentaire..." required
                                  class="w-full bg-[#2a2a2a] border-none rounded-2xl py-3 px-4 text-white placeholder-gray-500 focus:ring-2 focus:ring-renews-vert resize-none overflow-hidden"
                                  oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                    </div>
                    <button type="submit" class="w-10 h-10 rounded-full bg-renews-vert text-black flex items-center justify-center hover:scale-110 transition shrink-0 self-end mb-1">
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                    </button>
                </form>

                <div class="space-y-6">
                    @forelse($dailyContent->comments as $comment)
                        
                        <div x-data="{ showReply: false, replyContent: '' }">
                            
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ substr($comment->user->username, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="bg-[#232323] rounded-2xl rounded-tl-none px-4 py-3 border border-gray-800">
                                        <div class="flex justify-between items-baseline mb-1">
                                            <span class="font-bold text-sm text-renews-electric">{{ $comment->user->username }}</span>
                                            <span class="text-[10px] text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-300 leading-relaxed">{{ $comment->body }}</p>
                                    </div>
                                    <button @click="showReply = !showReply; replyContent = '@' + '{{ $comment->user->username }} ';" 
                                            class="text-xs text-gray-500 mt-1 ml-2 hover:text-white transition flex items-center gap-1">
                                        <i class="fa-solid fa-reply"></i> Répondre
                                    </button>
                                </div>
                            </div>

                            @if($comment->replies->count() > 0)
                                <div class="ml-11 mt-3 space-y-3 pl-3 border-l-2 border-gray-700">
                                    @foreach($comment->replies as $reply)
                                        
                                        <div x-data="{ showSubReply: false, subReplyContent: '' }">
                                            <div class="flex gap-3">
                                                <div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center font-bold text-[10px] shrink-0 text-gray-300">
                                                    {{ substr($reply->user->username, 0, 1) }}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="bg-[#1a1a1a] rounded-xl rounded-tl-none px-3 py-2 border border-gray-800">
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
                                                <form action="{{ route('content.comment', $dailyContent->id) }}" method="POST" class="flex gap-2">
                                                    @csrf
                                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                    
                                                    <input type="text" name="reply_body" x-model="subReplyContent" placeholder="Répondre..." required
                                                           class="flex-1 bg-[#1a1a1a] border border-gray-700 rounded-xl py-1.5 px-3 text-xs text-white focus:ring-1 focus:ring-renews-vert placeholder-gray-600">
                                                    
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
                                <form action="{{ route('content.comment', $dailyContent->id) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    
                                    <input type="text" name="reply_body" x-model="replyContent" placeholder="Répondre..." required
                                           class="flex-1 bg-[#1a1a1a] border border-gray-700 rounded-xl py-2 px-3 text-sm text-white focus:ring-1 focus:ring-renews-vert placeholder-gray-600">
                                    <button type="submit" class="text-renews-vert hover:text-white text-xs font-bold px-2 uppercase tracking-wide">
                                        Envoyer
                                    </button>
                                </form>
                            </div>

                        </div>
                    @empty
                        <p class="text-gray-500 text-center text-sm py-4 italic">Soyez le premier à commenter !</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>