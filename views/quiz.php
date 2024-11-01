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
    <header class="w-full">
      <div class="w-full h-20 py-4 flex justify-center items-center border-b-2 border-b-gray-900">
        <h1 class="text-3xl font-bold">SELAMAT DATANG DI PEMBELAJARAN ONLINE</h1>
      </div>
      <div class="w-full flex justify-end items-center bg-sky-950 p-5">
        <nav>
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
          <?php else: ?>
            <div>
              <a href="/login" class="py-3 px-6 font-bold text-white bg-indigo-500 rounded-xl">Login</a>
            </div>
          <?php endif; ?>
        </nav>
      </div>
    </header>
    <main class="container mx-auto">

    </main>


  </div>

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
      setup() {


        function showAlert() {
          const alertElement = document.getElementById('alertError');
          alertElement.classList.add('show');
          setTimeout(function () {
            alertElement.classList.remove('show');
          }, 3000);
        }


        return {
        }
      }
    }).mount('#app')
  </script>
</body>

</html>