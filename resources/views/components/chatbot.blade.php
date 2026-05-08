<div id="chatbot-container" style="position: fixed; bottom: 20px; right: 20px; width: 380px; height: 600px; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); display: flex; flex-direction: column; z-index: 9999; font-family: 'Segoe UI', sans-serif;">

    <!-- Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 16px; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin: 0; font-size: 16px;">🤖 Assistant IA Vocal</h3>
            <small style="opacity: 0.9;">Cliquez pour parler</small>
        </div>
        <button id="chatbot-close" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer; padding: 0;">✕</button>
    </div>

    <!-- Messages Container -->
    <div id="chatbot-messages" style="flex: 1; overflow-y: auto; padding: 16px; background: #f7f9fc;">
        <div style="text-align: center; color: #999; padding: 20px 0;">
            <p style="margin: 0; font-size: 14px;">👋 Bienvenue ! Posez-moi vos questions.</p>
            <small style="display: block; margin-top: 8px; font-size: 12px;">Tapez ou parlez</small>
        </div>
    </div>

    <!-- Input Area -->
    <div style="padding: 12px; border-top: 1px solid #eee; background: white; border-radius: 0 0 12px 12px;">
        <!-- Voice Controls -->
        <div style="display: flex; gap: 8px; margin-bottom: 12px; justify-content: center;">
            <button id="chatbot-mic" title="Parler" style="background: #667eea; color: white; border: none; border-radius: 50%; width: 44px; height: 44px; cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
                🎤
            </button>
            <button id="chatbot-speaker" title="Activer/Désactiver l'audio" style="background: #764ba2; color: white; border: none; border-radius: 50%; width: 44px; height: 44px; cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center;">
                🔊
            </button>
            <button id="chatbot-settings" title="Paramètres" style="background: #7f8c8d; color: white; border: none; border-radius: 50%; width: 44px; height: 44px; cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center;">
                ⚙️
            </button>
        </div>

        <!-- Text Input -->
        <div style="display: flex; gap: 8px;">
            <input type="text" id="chatbot-input" placeholder="Écrivez votre question..." style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            <button id="chatbot-send" style="background: #667eea; color: white; border: none; border-radius: 6px; padding: 10px 16px; cursor: pointer; font-weight: 600;">
                ↑
            </button>
        </div>
    </div>

    <!-- Status Indicator -->
    <div id="chatbot-status" style="position: absolute; bottom: 80px; right: 20px; background: rgba(0,0,0,0.7); color: white; padding: 6px 12px; border-radius: 4px; font-size: 12px; display: none; white-space: nowrap;">
        En écoute...
    </div>
</div>

<!-- Floating Button (when closed) -->
<button id="chatbot-toggle" style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; font-size: 28px; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.2); display: none; align-items: center; justify-content: center; z-index: 9998;">
    🤖
</button>

<script>
    // Configuration
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const speechSynthesis = window.speechSynthesis;
    let recognition;
    let isListening = false;
    let soundEnabled = true;

    if (SpeechRecognition) {
        recognition = new SpeechRecognition();
        recognition.lang = 'fr-FR';
        recognition.continuous = false;
        recognition.interimResults = true;
    }

    // Elements
    const container = document.getElementById('chatbot-container');
    const messagesDiv = document.getElementById('chatbot-messages');
    const input = document.getElementById('chatbot-input');
    const sendBtn = document.getElementById('chatbot-send');
    const micBtn = document.getElementById('chatbot-mic');
    const speakerBtn = document.getElementById('chatbot-speaker');
    const closeBtn = document.getElementById('chatbot-close');
    const toggleBtn = document.getElementById('chatbot-toggle');
    const settingsBtn = document.getElementById('chatbot-settings');
    const statusDiv = document.getElementById('chatbot-status');

    // Speech Recognition
    if (recognition) {
        recognition.onstart = () => {
            isListening = true;
            micBtn.style.background = '#e74c3c';
            statusDiv.style.display = 'block';
        };

        recognition.onend = () => {
            isListening = false;
            micBtn.style.background = '#667eea';
            statusDiv.style.display = 'none';
        };

        recognition.onresult = (event) => {
            let transcript = '';
            for (let i = event.resultIndex; i < event.results.length; i++) {
                transcript += event.results[i][0].transcript;
            }
            if (event.results[event.results.length - 1].isFinal) {
                input.value = transcript;
                sendMessage();
            }
        };

        recognition.onerror = (event) => {
            console.error('Speech recognition error:', event.error);
            addMessage('Erreur de reconnaissance vocale. Veuillez réessayer.', 'bot');
        };
    }

    // Send Message
    function sendMessage() {
        const message = input.value.trim();
        if (!message) return;

        input.value = '';
        addMessage(message, 'user');

        // Send to backend
        fetch('{{ route("chatbot.respond") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ message })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                addMessage(data.response, 'bot');
                if (soundEnabled && speechSynthesis) {
                    speak(data.response);
                }
            } else {
                addMessage(data.error || 'Erreur du serveur', 'bot');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            addMessage('Erreur de connexion. Veuillez réessayer.', 'bot');
        });
    }

    // Add Message to Chat
    function addMessage(text, sender) {
        const msgDiv = document.createElement('div');
        msgDiv.style.marginBottom = '12px';
        msgDiv.style.display = 'flex';
        msgDiv.style.justifyContent = sender === 'user' ? 'flex-end' : 'flex-start';

        const bubble = document.createElement('div');
        bubble.style.maxWidth = '80%';
        bubble.style.padding = '10px 14px';
        bubble.style.borderRadius = '10px';
        bubble.style.fontSize = '13px';
        bubble.style.lineHeight = '1.4';
        bubble.style.wordWrap = 'break-word';
        bubble.style.whiteSpace = 'pre-wrap';

        if (sender === 'user') {
            bubble.style.background = '#667eea';
            bubble.style.color = 'white';
        } else {
            bubble.style.background = '#e8eef7';
            bubble.style.color = '#2c3e50';
        }

        bubble.textContent = text;
        msgDiv.appendChild(bubble);
        messagesDiv.appendChild(msgDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    // Text-to-Speech
    function speak(text) {
        if (!speechSynthesis) return;

        speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'fr-FR';
        utterance.rate = 1;
        speechSynthesis.speak(utterance);
    }

    // Event Listeners
    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    if (recognition) {
        micBtn.addEventListener('click', () => {
            if (isListening) {
                recognition.abort();
            } else {
                recognition.start();
            }
        });
    } else {
        micBtn.style.opacity = '0.5';
        micBtn.style.cursor = 'not-allowed';
    }

    speakerBtn.addEventListener('click', () => {
        soundEnabled = !soundEnabled;
        speakerBtn.style.opacity = soundEnabled ? '1' : '0.5';
    });

    closeBtn.addEventListener('click', () => {
        container.style.display = 'none';
        toggleBtn.style.display = 'flex';
    });

    toggleBtn.addEventListener('click', () => {
        container.style.display = 'flex';
        toggleBtn.style.display = 'none';
        input.focus();
    });

    settingsBtn.addEventListener('click', () => {
        fetch('{{ route("chatbot.toggle") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.enabled) {
                addMessage('✓ ' + data.message, 'bot');
            } else {
                addMessage('✗ ' + data.message, 'bot');
                setTimeout(() => {
                    container.style.display = 'none';
                    toggleBtn.style.display = 'flex';
                }, 1500);
            }
        });
    });

    // Check if chatbot is enabled on load
    fetch('{{ route("chatbot.respond") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        },
        body: JSON.stringify({ message: 'test' })
    })
    .catch(() => {
        // Chatbot disabled or error
    });

    // Speech synthesis language fix
    if (speechSynthesis) {
        window.addEventListener('load', () => {
            speechSynthesis.getVoices();
        });
    }
</script>

<style>
    #chatbot-messages::-webkit-scrollbar {
        width: 6px;
    }
    #chatbot-messages::-webkit-scrollbar-track {
        background: transparent;
    }
    #chatbot-messages::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 3px;
    }
    #chatbot-messages::-webkit-scrollbar-thumb:hover {
        background: #bbb;
    }

    #chatbot-mic:hover, #chatbot-speaker:hover, #chatbot-settings:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    #chatbot-send:hover {
        background: #5568d3;
    }

    #chatbot-toggle:hover {
        transform: scale(1.1);
    }

    #chatbot-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
</style>
