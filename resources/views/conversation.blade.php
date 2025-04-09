@extends('layouts.app-layout')
@section('content')

<div class="container text-center my-5">
    <div class="row justify-content-center">
      <!-- Left side: Speak Button -->
      <div class="col-md-4">
        <div class="button-wrapper">
          <div id="pulseRingUser" class="pulse-ring hidden"></div>
          <button id="recordButton" class="speak-button" >Start</button>
        </div>
        <div id="userWave" class="speak-animation hidden">
          <div></div>
          <div></div>
          <div></div>
        </div>
        <div class="mt-3 border p-3" id="outputText">Talk To AI</div>
      </div>

      <!-- Right side: AI Bubble -->
      <div class="col-md-4">
        <div class="alert alert-warning p-2 d-none" id="aiThink">Thinking, Please wait...</div>
        <div class="ai-wrapper">
          <div id="pulseRingAI" class="pulse-ring-ai hidden"></div>
          <div class="ai-bubble">AI</div>
        </div>
        <div id="aiWave" class="speak-animation hidden">
          <div></div>
          <div></div>
          <div></div>
        </div>
        <div class="mt-3 border p-3" id="aiOutputText">AI React</div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let isRecording = false;
        let finalTranscript = '';
        const recordBtn = document.getElementById('recordButton');
        const recognition = 'webkitSpeechRecognition' in window ? new webkitSpeechRecognition() : null;
    
        if (!recognition) {
            alert("Your browser does not support Speech Recognition.");
            return;
        }
    
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = 'en-US';
    
        recognition.onresult = (event) => {
            // const transcript = event.results[event.results.length - 1][0].transcript;
            // document.getElementById("outputText").innerText = transcript;
            // AIResponse(transcript);
            const last = event.results.length - 1;

            if (event.results[last].isFinal) {
                finalTranscript = event.results[last][0].transcript;
                document.getElementById("outputText").innerText = finalTranscript;
                AIResponse(finalTranscript);
                recognition.stop();
            }
        };
    
        recognition.onend = () => {
            recordBtn.innerText = "Start Talk";
            isRecording = false;
            document.getElementById('pulseRingUser')?.classList.add('hidden');
            document.getElementById('userWave')?.classList.add('hidden');
        };
    
        recognition.onerror = (event) => {
            console.error("Speech recognition error:", event);
        };
    
        recordBtn.addEventListener("click", () => {
            isRecording = !isRecording;
            recordBtn.innerText = isRecording ? "Stop Talk" : "Start Talk";
    
            if (isRecording) {
                recognition.start();
                document.getElementById('pulseRingUser')?.classList.remove('hidden');
                document.getElementById('userWave')?.classList.remove('hidden');
            } else {
                recognition.stop();
            }
        });
    });
</script>
<script>
    function AIResponse(text){
        document.getElementById('aiThink').classList.remove('d-none');
        $.ajax({
            url: '{{ route('ai-response') }}',
            method: 'POST',
            data: {
                message: text,
                _token: CSRF_TOKEN 
            },
            
            success: function(response){
                document.getElementById('aiThink').classList.add('d-none');
                speakText(response.message);
            },
            error: function(xhr, status, error){

            }
        });
    }
</script>
<script>
    function speakText(text) {
        const pulseRingAI = document.getElementById('pulseRingAI');
        const aiWave = document.getElementById('aiWave');

        if ('speechSynthesis' in window) {
            let speech = new SpeechSynthesisUtterance(text);
            speech.lang = 'en-US';
            speech.rate = 1;
            speech.pitch = 1;
            speech.volume = 1;

            // Ensure voices are loaded
            speech.onstart = function () {
                console.log("Speech started...");
                pulseRingAI.classList.remove('hidden');
                aiWave.classList.remove('hidden');
                document.getElementById("aiOutputText").innerText = text;
            };
            speech.onend = function () {
                console.log("Speech ended.");
                pulseRingAI.classList.add('hidden');
                aiWave.classList.add('hidden');
            };

            window.speechSynthesis.speak(speech);
        } else {
            alert("Text-to-Speech is not supported in this browser.");
        }
    }
</script>

@endpush