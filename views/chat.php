<?php
if (isset($_SESSION['display_name'])) {
  $logged_in = true;
  $id = $_SESSION['id'];
  $display_name = $_SESSION['display_name'];
  $username = $_SESSION['username'];
  $role = $_SESSION['role'] == 'admin';
} else {
  $logged_in = false;
  $role = false;
}

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$fullUrl = "$protocol://$host$uri";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Belajar Online</title>
  <link rel="stylesheet" href="/css/app.css">
  <link rel="stylesheet" href="/css/chatting.css">

  <link rel="icon" href="/img/Icon-Perpustakaan.png" type="image/png">

</head>
<script src="/js/vue.global.js"></script>
<!-- <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script> -->

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .hidden {
    display: none;
  }

  .rotate-90 {
    transform: rotate(90deg);
    transition: transform 0.3s;
  }

  .rotate-0 {
    transform: rotate(0deg);
    transition: transform 0.3s;
  }

  .alert {
    position: absolute;
    top: 10px;
    right: 10px;
    transform: translateY(-100%);
    animation: slideIn 0.3s ease forwards;
    display: none;
  }

  .alert.show {
    display: block;
  }

  @keyframes slideIn {
    from {
      transform: translateY(-100%);
    }

    to {
      transform: translateY(0);
    }
  }

  /* .line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
} */
</style>

<body>
  <div id="app">
    <div id="alertError"
      class="alert p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
      <span class="font-medium">Gagal!</span> Data Buku Baru Gagal Ditambahkan.
    </div>
    <header class="w-full h-[22vh] min-h-[180px]">
      <div class="w-full h-20 py-4 flex justify-center items-center border-b-2 border-b-gray-900">
        <h1 class="text-3xl font-bold">SELAMAT DATANG DI PEMBELAJARAN ONLINE</h1>
      </div>
      <div class="w-full flex justify-end items-center bg-sky-950 p-5">
        <nav class="flex justify-between items-center w-full">
          <button @click="backButton()"
            class="text-white flex flex-row bg-blue-700 items-center hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 12h14M5 12l4-4m-4 4 4 4" />
            </svg>
            <p class="text-xl">Back</p>
          </button>
          <?php if ($logged_in): ?>
            <div id="nav-button"
              class="flex flex-row justify-center items-center gap-3 bg-indigo-500 rounded-xl py-3 px-6 cursor-pointer">
              <p class="text-2xl text-white font-bold">Selamat Datang, <span class="italic"><?= $display_name ?></span>
              </p>
              <div class="relative flex justify-center items-center">

                <button id="dropdownButton" class="focus:outline-none bg-indigo-800 rounded-xl p-2">
                  <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m19 9-7 7-7-7" />
                  </svg>
                </button>
                <div id="dropdownMenu"
                  class="hidden absolute right-0 top-8 mt-2 w-48 bg-white rounded-md drop-shadow-lg py-2 z-50">
                  <a href="/logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Keluar</a>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </nav>
      </div>
    </header>
    <main class="h-[78vh] min-h-[300px] w-full flex flex-row pt-[10px] pb-[10px] pl-[20px] pr-[20px] gap-3">
      <div class="w-[20%] p-4 rounded-md bg-slate-600">
        <div v-if="users.length != 0" v-for="user in users">
          <div @click="() => getMessage(user.id)"
            class="pt-[10px] pb-[10px] pl-[20px] pr-[20px] mt-3 rounded-md bg-slate-500 text-white cursor-pointer transition-colors hover:bg-slate-300 hover:text-slate-500">
            {{user.display_name}}
          </div>
        </div>
      </div>
      <div class="w-[80%] bg-slate-500 h-full rounded-md flex flex-col p-[20px]">
        <div class="max-h-[92%] h-[92%] overflow-auto flex flex-col gap-3">
          <div v-for="message in messages" :key="message.id" class="max-w-[70%]" :class="message.receiver_id == '<?= $id ?>' ? 'self-start' : 'self-end'">
            <div v-if="message.receiver_id == '<?= $id ?>'" class="p-4 bg-white rounded-md w-full">
              {{message.message}}
            </div>
            <div v-else class="bg-green-200 p-4 rounded-md mr-2">
              {{message.message}}
            </div>
          </div>
        </div>

        <div class="max-h-[8%] h-[8%] min-h-[45px] w-full bg-slate-500 flex flex-row items-center gap-4" v-if="chooseUser != ''">
          <input type="text" v-model="textInput" class="w-[90%] h-[40px] rounded-md">
          <button class="w-[10%] bg-green-400 h-[40px] rounded-md" @click="sendMessage">Kirim</button>
        </div>
        <div v-else>
          <p class="text-white">Pilih user untuk melihat pesan</p>
        </div>
      </div>
    </main>
  </div>
</body>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    let navButton = document.getElementById('nav-button');
    var dropdownButton = document.getElementById('dropdownButton');
    var dropdownMenu = document.getElementById('dropdownMenu');

    navButton.addEventListener('click', function () {
      dropdownMenu.classList.toggle('hidden');
    });

    window.addEventListener('click', function (event) {
      if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.add('hidden');
      }
    });

    let addBook = document.getElementById('add-book');
    addBook.addEventListener('click', function () {
      window.location.href = '/add-book';
    });
  });
</script>
<script>
  const {
    createApp,
    ref,
    reactive,
    onMounted,
    onBeforeUnmount
  } = Vue

  createApp({
    setup(

    ) {
      const users = ref([])
      const textInput = ref("")
      const messages = ref([])
      const chooseUser = ref("")
      function backButton() {
        const currentDomain = window.location.hostname;
        const currentPort = window.location.port;
        const currentUrl = window.location.href;
        const referrer = document.referrer;
        // const previousPage = sessionStorage.getItem('previousPage');
        window.location.href = window.location.protocol + "//" + currentDomain + (currentPort ? ":" + currentPort : ""); // Kembali ke halaman utama
      }

      function getMessage(sender_id) {
        return fetch(`/api/message/<?= $id ?>/${sender_id}`)
          .then(response => response.json())
          .then(data => {
            if (data.status === "success") {
              messages.value = data.data;
              console.log(data.data);
            } else {
              console.error(data.message);
            }
            // Set value of chooseUser after the data has been retrieved
            chooseUser.value = sender_id;
          });
      }

      async function sendMessage() {
        // Make sure to check if chooseUser.value has been set properly
        if (!chooseUser.value) {
          console.error("chooseUser is undefined!");
          return;
        }

        const formData = new FormData();
        formData.append("message", textInput.value);
        formData.append("sender_id", "<?= isset($id) ? $id : null ?>");
        formData.append("receiver_id", chooseUser.value);

        await fetch("/api/message", {
          method: "POST", // Metode POST
          body: formData, // Mengirimkan FormData sebagai body
        })
          .then((response) => response.text()) // Mengambil response dalam bentuk teks
          .then((data) => {
            if (data.includes("status=success")) {
              textInput.value = "";
              // Reload messages after sending a message
              getMessage(chooseUser.value);
            } else {
              alert("Failed to send message");
            }
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }

      function getUser() {
        fetch('/api/message/user/<?= $id ?>')
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              users.value = data.data
              console.log(data.data);
            } else {
              console.error(data.message)
            }
          })
      }
      onMounted(() => {
        getUser()
      })
      return {
        backButton,
        users,
        textInput,
        getMessage,
        messages,
        sendMessage
      }
    }
  }).mount('#app')
</script>

</html>