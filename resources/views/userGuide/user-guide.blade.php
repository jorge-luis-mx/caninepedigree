<x-app-layout>

    <h1 class="is-size-4 mb-5 ml-1">Tutorials</h1>
    <p>In this section you will find textual and multimedia material to help you understand how our platform works.</p>
    
    <x-card-info-progress
        :description="''"
        :title="'How it Works'"
        :instructions="$tutorials['instructions']"
    />
    

    @if(!empty($tutorials['stepTutorials']))

        @foreach($tutorials['stepTutorials'] as $key => $tutorials)

            <div class="columns user-guide">
                <div class="column">
                    <h3>{{$key + 1}}. {{$tutorials['title']}}</h3>
                    @foreach($tutorials['description'] as $key => $step)
                        <p>{{$step}}</p>
                    @endforeach
                    
                    <div class="item-link is-flex is-align-items-center">
                        <a href="/{{$tutorials['url']}}">
                            <span>{{$tutorials['tag']}}</span>
                        </a>
                        <svg xmlns="http://www.w3.org/2000/svg"  width="24" height="24" viewBox="0 0 448 512"><path fill="#FBA53E" d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h306.7L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
                    </div>

                </div>
                <div class="column">
                    <video id="videoPlayer" controls >
                        <source src="{{ asset('assets/videos/how-to-create-a-map-airport-transportation.mp4') }}" type="video/mp4">
                        Tu navegador no soporta el elemento de video.
                    </video>
                </div>
            </div>

        @endforeach

    @endif

    
</x-app-layout>