@extends('layouts.frontend')

@section('content')
<div class="container">
  <div class="form-container">
    <h3 class="text-center mb-4">Animation Generator</h3>
    <form id="videoGeneratorForm">
      <!-- Select Input Method -->
      <div class="form-group">
        <label class="form-label">Input Method</label>
        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
          <label class="btn btn-outline-secondary active">
            <input type="radio" name="inputMethod" id="textToSpeechOption" value="textToSpeech" autocomplete="off" checked> Text-to-Speech
          </label>
          <label class="btn btn-outline-secondary">
            <input type="radio" name="inputMethod" id="recordVoiceOption" value="recordVoice" autocomplete="off"> Record Voice
          </label>
        </div>
        <div id="inputMethodError" class="error-message" style="display: none;">Please select an input method.</div>
      </div>
      <!-- Prompt Field -->
      <div class="form-group mt-4" id="nameGroup">
        <label for="name" class="form-label">Enter the characters' name</label>
        <input type="text" class="form-control bg-dark text-white" id="name" placeholder="Enter the character name" required>
        <div id="nameError" class="error-message" style="display: none;">Please enter a prompt description.</div>
      </div>

      <!-- Prompt Field -->
      <div class="form-group mt-4" id="promptGroup">
        <label for="prompt" class="form-label">Describe the Character and Scene</label>
        <textarea class="form-control bg-dark text-white" id="prompt" rows="3" placeholder="Enter details for the character and scene..." required></textarea>
        <div id="promptError" class="error-message" style="display: none;">Please enter a prompt description.</div>
      </div>

      <!-- Script or Voice Recording Option -->
      <div class="form-group mt-4" id="scriptOrRecordContainer">
        <!-- Text-to-Speech Container -->
        <div id="textToSpeechContainer">
          <div class="form-group">
            <label class="form-label">Voice Selection</label>
            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
              <label class="btn btn-outline-secondary active">
                <input type="radio" name="voiceOption" id="maleVoice" value="male" autocomplete="off" checked> Male Voice
              </label>
              <label class="btn btn-outline-secondary">
                <input type="radio" name="voiceOption" id="femaleVoice" value="female" autocomplete="off"> Female Voice
              </label>
            </div>
            <div id="voiceOptionError" class="error-message" style="display: none;">Please select a voice option.</div>
          </div>
          <div class="form-group">
            <label for="script" class="form-label">Script</label>
            <textarea class="form-control bg-dark text-white" id="script" rows="3" placeholder="What do you want the character to say..." maxlength="255"></textarea>
            <div class="counter" id="scriptCounter">0/255 characters</div>
            <div id="scriptError" class="error-message" style="display: none;">Please enter a script (up to 255 characters).</div>
          </div>
        </div>

        <!-- Record Voice Container -->
        <div id="recordVoiceContainer" style="display: none;">
          <div class="form-group text-center">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="recordButton">
              <i class="fas fa-microphone record-icon"></i> Start Recording
            </button>
            <div class="voice-feedback mt-3" id="voiceFeedback"></div>
            <div class="progress mt-3" id="uploadProgress" style="display: none;">
              <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-3 w-100" id="playButton" style="display: none;"><i class="fa fa-play"></i> Play Recording</button>
            <div id="recordError" class="error-message" style="display: none;">Please record your voice before submitting.</div>
          </div>
        </div>
      </div>

      <!-- Generate Button -->
      <button type="submit" class="btn btn-primary mt-4" id="generateVideo">Generate Video</button>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
let mediaRecorder;
let audioChunks = [];
let audioBinary;
let audioUrl;
let audio = new Audio();

$(document).ready(function() {
    // Toggle between input methods
    $('input[name="inputMethod"]').on('change', function() {
        if ($('#textToSpeechOption').is(':checked')) {
            $('#textToSpeechContainer').show();
            $('#recordVoiceContainer').hide();
        } else {
            $('#textToSpeechContainer').hide();
            $('#recordVoiceContainer').show();
        }
    });

    // Character counter for script textarea
    $('#script').on('input', function() {
        const charCount = $(this).val().length;
        $('#scriptCounter').text(charCount + '/255 characters');
    });

    // Record voice functionality
    $('#recordButton').on('click', function() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
            $('#voiceFeedback').text('Voice recording complete.');
            $('#recordButton').html('<i class="fas fa-microphone"></i> Start Recording');
        } else {
            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(function(stream) {
                    mediaRecorder = new MediaRecorder(stream);
                    audioChunks = []; // Reset audioChunks before starting a new recording session
                    mediaRecorder.start();
                    $('#voiceFeedback').text('Recording in progress...');
                    $('#recordButton').html('<i class="fas fa-stop"></i> Stop Recording');

                    mediaRecorder.ondataavailable = function(event) {
                        audioChunks.push(event.data);
                    };

                    mediaRecorder.onstop = function() {
                        audioBinary = new Blob(audioChunks, { type: 'audio/mp3' });
                        audioUrl = URL.createObjectURL(audioBinary);
                        audio.src = audioUrl;
                        $('#playButton').show();
                    };
                })
                .catch(function(err) {
                    console.error('Microphone error:', err);
                    $('#voiceFeedback').text('Unable to access microphone. Please check browser permissions.');
                });
        }
    });

    // Play recorded voice
    $('#playButton').on('click', function() {
        if (audio.paused) {
            audio.play();
            $('#playButton').html('<i class="fas fa-stop"></i> Stop Playback');
        } else {
            audio.pause();
            $('#playButton').html('<i class="fas fa-play"></i> Play Recording');
        }
    });

    // Form submission via AJAX
    $('#videoGeneratorForm').on('submit', function(event) {
        event.preventDefault();

        let isValid = true;
        const inputMethod = $('input[name="inputMethod"]:checked').val();
        const name = $('#name').val().trim();
        const prompt = $('#prompt').val().trim();
        const script = $('#script').val().trim();

        // Clear previous error messages
        $('.error-message').hide();

        // Validate input method
        if (!inputMethod) {
            $('#inputMethodError').show();
            isValid = false;
        }

        // Validate prompt field
        if (name === '') {
            $('#nameError').show();
            isValid = false;
        }

        // Validate prompt field
        if (prompt === '') {
            $('#promptError').show();
            isValid = false;
        }

        // Validate based on selected input method
        if (inputMethod === 'textToSpeech' && script === '') {
            $('#scriptError').show();
            isValid = false;
        } else if (inputMethod === 'recordVoice' && !audioBinary) {
            $('#recordError').show();
            isValid = false;
        }

        if (!isValid) return;

        // Prepare form data
        const formData = new FormData();
        formData.append('name', $('#name').val());
        formData.append('inputMethod', inputMethod);
        formData.append('prompt', prompt);
        formData.append('user_id', '{{ Auth::id() }}');
        formData.append('status', 'new');
        formData.append('_token', '{{ csrf_token() }}');


        if (inputMethod == 'textToSpeech') {
            formData.append('script', script);
            formData.append('gender', $('input[name="voiceOption"]:checked').val());           
        } else if(inputMethod == 'recordVoice') {
            formData.append('audio', audioBinary, 'recording.mp3');   
            formData.append('script', 'none');
            formData.append('gender', 'none');           
        }

        /* Submit form*/
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        $.ajax({
            url: "{{ route('frontend.projects.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#generateVideo').prop('disabled', true);
            },
            success: function(response) {
                $('#generateVideo').prop('disabled', false);
                location.href = "{{ route('frontend.projects.index') }}";
            },
            error: function(e) {
                $('#generateVideo').prop('disabled', false);
                alert('Failed to submit video generation request. Please try again.'+ e.responseText);
            }
        });
    });
});
</script>
@endsection
