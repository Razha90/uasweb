// Mengambil elemen yang diperlukan
const floatingButton = document.getElementById('floating-button');
const chatWindow = document.getElementById('chat-window');
const closeChatButton = document.getElementById('close-chat');

// Fungsi untuk membuka jendela chat
floatingButton.addEventListener('click', () => {
    chatWindow.classList.toggle('hidden');
});

// Fungsi untuk menutup jendela chat
closeChatButton.addEventListener('click', () => {
    chatWindow.classList.add('hidden');
});

const socket = new WebSocket('ws://localhost:8082');

socket.onopen = function() {
    console.log('Connected to WebSocket server');
};

socket.onmessage = function(event) {
    console.log(event.data);// Scroll ke bawah
};