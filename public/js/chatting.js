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
