<?php
if (isset($_SESSION['display_name'])) {
  $logged_in = true;
  $display_name = $_SESSION['display_name'];
  $username = $_SESSION['username'];
  $role = $_SESSION['role'] == 'admin';
  $userId = $_SESSION['id'];
} else {
  $logged_in = false;
  $role = false;
  $userId = '';
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
  <title>Perpustakaan Online</title>
  <link rel="stylesheet" href="/css/app.css">
  <link rel="stylesheet" href="/css/chatting.css">

  <link rel="icon" href="/img/Icon-Perpustakaan.png" type="image/png">
  <script src="/js/vue.global.js"></script>

</head>
<style>
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
</style>

<body>
  <div id="app">
    <div :class="isAlertSuccess"
      class="alert p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
      role="alert">
      <span class="font-medium">Behasil!</span> Data Berhasil Didapatkan.
    </div>
    <div :class="isAlertError"
      class="alert p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
      <span class="font-medium">Gagal!</span> Data Pembelajaran Gagal Didapatkan.
    </div>
    <header class="w-full h-[20vh]">
      <div class="w-full h-20 py-4 flex justify-center items-center border-b-2 border-b-gray-900">
        <h1 class="text-3xl font-bold">SELAMAT DATANG DI PEMBELAJARAN ONLINE</h1>
      </div>
      <div class="w-full flex justify-between items-center bg-sky-950 p-3">
        <div>
          <button @click="backButton()"
            class="text-white flex flex-row bg-blue-700 items-center hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 12h14M5 12l4-4m-4 4 4 4" />
            </svg>
            <p class="text-xl">Back</p>
          </button>

        </div>
        <?php if ($logged_in): ?>
          <div id="nav-button" @click="toggleDropdown"
            class="flex flex-row justify-center items-center gap-3 bg-indigo-500 rounded-xl py-3 px-6 cursor-pointer">
            <p class="text-2xl text-white font-bold">Selamat Datang, <span class="italic"><?= $display_name ?></span></p>
            <div class="relative flex justify-center items-center">
              <button id="dropdownButton" class="focus:outline-none bg-indigo-800 rounded-xl p-2">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                  width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m19 9-7 7-7-7" />
                </svg>
              </button>
              <div id="dropdownMenu" :class="{ hidden: isDropdownHidden }"
                class="absolute right-0 top-8 mt-2 w-48 bg-white rounded-md drop-shadow-lg py-2 z-50">
                <a href="/logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Keluar</a>
              </div>
            </div>
          </div>
        <?php else: ?>
          <div>
            <a href="/login" class="py-3 px-6 font-bold text-white bg-indigo-500 rounded-xl">Login</a>
          </div>
        <?php endif; ?>
      </div>
    </header>
    <main class="w-full h-canvas mt-10 flex justify-center">
      <div
        class="w-11/12 h-full flex flex-col overflow-y-auto items-center bg-white border-gray-200 rounded-lg shadow-2xl p-5 gap-3">
        <div>
          <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 ">{{searchs.title}}</h5>
        </div>
        <img v-if="isImage"
          class="object-cover w-full rounded-t-lg max-h-[500px] md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
          :src="'/img/'+searchs.image_url" :alt="searchs.title">
        <!-- <img v-else class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
          src="/img/question-mark.png" :alt="searchs.title"> -->
        <div class="flex flex-col justify-between items-start leading-normal">
          <div class="flex justify-center items-center w-full gap-4">
            <div class="flex flex-row justify-center items-center gap-1">
              <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                  d="M7 2a2 2 0 0 0-2 2v1a1 1 0 0 0 0 2v1a1 1 0 0 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H7Zm3 8a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm-1 7a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3 1 1 0 0 1-1 1h-6a1 1 0 0 1-1-1Z"
                  clip-rule="evenodd" />
              </svg>
              <p class="font-normal text-gray-700 ">{{searchs.author}}</p>
            </div>

            <div class="flex flex-row justify-start items-center gap-1">
              <svg class="w-6 h-6 text-gray-800 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                  d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"
                  clip-rule="evenodd" />
              </svg>
              <p class="font-normal text-gray-700 ">{{searchs.published_year}}</p>
            </div>
          </div>
          <div class="myArticle" v-html="searchs.synopsis"></div>
          <!-- <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{searchs.synopsis}}</p> -->
        </div>
        <!-- <div class="flex justify-center items-center flex-row">
          <button v-if="isBookLoan" type="button"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
            @click="addBookLoan">Pinjam</button>
          <button v-else @click="deleteBookLoan" type="button"
            class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Kembalikan</button> </d
        </div> -->
        <?php if ($logged_in): ?>
          <?php if ($role): ?>
            <button type="button"
              class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800"
              @click="deleteBook">Hapus</button>
            <button type="button"
              class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 focus:outline-none dark:focus:ring-yellow-800"
              @click="updateIsUpdate">Perbaharui</button>
          <?php endif; ?>
        <?php endif; ?>

      </div>
    </main>
    <form id="delete-book" action="/api/book-delete" method="post" class="hidden">
      <input type="text" name="id" :value="searchs.id">
    </form>
    <form class="hidden" id="formAddBookLoan" action="/api/book-loan" method="post">
      <input type="text" name="book_id" :value="searchs.id">
      <input type="text" name="user_id" value="<?= $userId ?>">
    </form>
    <form action="/api/delete-book-loan" method="post" id="delete-book-loan" class="hidden">
      <input type="text" name="id" v-model="dataBookLoan.id">
    </form>
    <div v-if="isUpdate" class="fixed inset-0 bg-white bg-opacity-50 backdrop-blur-sm z-10"></div>
    <div v-if="isUpdate"
      class="min-w-[350px] w-[500px] z-20 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl drop-shadow-lg p-4">
      <div class="text-right">
        <button @click="updateIsUpdate" type="button"
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
          <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M6 18 17.94 6M18 18 6.06 6" />
          </svg>
        </button>
      </div>
      <form class="max-w-md mx-auto bg-slate-900 py-5 px-6 rounded-xl" id="bookForm">
        <input type="text" name="id" :value="dataUpdate.id" hidden>
        <h1 class="text-2xl font-extrabold text-white text-center my-8">Perbaharui Buku</h1>
        <div class="relative z-0 w-full mb-5 group">
          <input type="text" name="title" id="title"
            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            placeholder=" " required v-model="dataUpdate.title" />
          <label for="title"
            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Title</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
          <input type="text" name="author" id="author"
            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            placeholder=" " required v-model="dataUpdate.author" />
          <label for="author"
            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Author</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
          <input type="number" name="published_year" id="published_year"
            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            placeholder=" " required v-model="dataUpdate.published_year" />
          <label for="published_year"
            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Published
            Year</label>
        </div>
        <div class="mb-5 group">
          <label for="synopsis" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Synopsis</label>
          <textarea id="synopsis" name="synopsis" rows="4"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Suatu har yang hujan...." v-model="dataUpdate.synopsis"></textarea>
        </div>
        <div class="mb-5 group">
          <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image">Sampul Buku</label>
          <input
            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
            aria-describedby="image" id="image" type="file" name="image">
        </div>
        <div class="text-center my-3">
          <button @click="formUpdate"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Perbaharui
            Buku</button>
        </div>
      </form>

    </div>

  </div>

</body>
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
      const searchs = ref([]);
      const dataUpdate = ref([]);
      const isBookLoan = ref(true);
      const isImage = ref(false);
      const isUpdate = ref(false);
      const isAlertSuccess = ref('');
      const isAlertError = ref('');
      const dataBookLoan = ref({});
      async function searchBook(id) {
        fetch(`/api/book/${id}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              console.log(data);
              isImage.value = data.data.image_url.length > 0;
              searchs.value = data.data;
              dataUpdate.value = data.data;
              showAlert(true);
            } else {
              showAlert(false);
              searchs.value = [];
              dataUpdate.value = [];
            }
          });
      };

      const currentUrl = window.location.href;
      const urls = new URL(currentUrl);
      const detailValue = urls.searchParams.get('detail');

      function activeLoad() {
        searchBook(detailValue);
        <?php if ($logged_in): ?>
          getBookLoan();
        <?php endif; ?>
      }
      activeLoad();

      function backButton() {
        const currentDomain = window.location.hostname;
        const currentPort = window.location.port;
        const currentUrl = window.location.href;
        const referrer = document.referrer;
        // const previousPage = sessionStorage.getItem('previousPage');
        window.location.href = window.location.protocol + "//" + currentDomain + (currentPort ? ":" + currentPort : ""); // Kembali ke halaman utama

        // if (referrer) {
        //   // Cek apakah referrer dari domain yang sama dengan port yang sama
        //   const referrerUrl = new URL(referrer);
        //   const referrerDomain = referrerUrl.hostname;
        //   const referrerPort = referrerUrl.port;

        //   if (referrerDomain === currentDomain && referrerPort === currentPort && referrer !== previousPage) {
        //     sessionStorage.setItem('previousPage', currentUrl); // Simpan halaman saat ini
        //     window.history.back();
        //   } else {
        //     sessionStorage.setItem('previousPage', currentUrl); // Simpan halaman saat ini
        //     window.location.href = window.location.protocol + "//" + currentDomain + (currentPort ? ":" + currentPort : ""); // Kembali ke halaman utama
        //   }
        // } else {
        //   // Jika tidak ada history atau referrer
        //   sessionStorage.setItem('previousPage', currentUrl); // Simpan halaman saat ini
        //   window.location.href = window.location.protocol + "//" + currentDomain + (currentPort ? ":" + currentPort : ""); // Kembali ke halaman utama
        // }
      }




      function deleteBook() {
        const form = document.getElementById('delete-book');
        if (confirm('Anda yakin ingin menghapus buku ini?')) {
          form.submit();
        }
      }


      function updateIsUpdate() {
        isUpdate.value = !isUpdate.value;
      }

      function formUpdate() {
        let forms = document.getElementById('bookForm');
        forms.addEventListener('submit', async function (event) {
          event.preventDefault();

          const form = event.target;
          const formData = new FormData(form);
          const response = await fetch('/api/book-update', {
            method: 'POST',
            body: formData
          });

          const result = await response.json();

          if (result.status === 'success') {
            showAlert(true);
            isUpdate.value = false;
            searchBook(detailValue);
          } else {
            showAlert(false);
          }
        });
      }

      function showAlert(info = true) {
        if (info) {
          isAlertSuccess.value = 'show';
        } else {
          isAlertError.value = 'show';
        }
        setTimeout(function () {
          isAlertSuccess.value = '';
          isAlertError.value = '';
        }, 3000);
      }

      function getBookLoan() {
        fetch(`/api/book-loan/<?= $userId ?>`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              const filter = data.data.find(item => item.book_id == searchs.value.id)
              console.log(data.data);
              console.log(filter);
              if (filter) {
                dataBookLoan.value = filter;
                isBookLoan.value = false;
                showAlert(true);
              } else {
                dataBookLoan.value = [];
                isBookLoan.value = true;
                showAlert(false);
              }

            } else {
              showAlert(false);
              isBookLoan.value = true;
              dataBookLoan.value = [];
            }
          });
      }

      function addBookLoan() {
        <?php if ($logged_in): ?>
          const form = document.getElementById('formAddBookLoan');
          form.submit();
        <?php else: ?>
          window.location.href = '/login';
        <?php endif; ?>

      }

      const goToAddBook = () => {
        window.location.href = '/add-book'
      }

      function deleteBookLoan() {
        const form = document.getElementById('delete-book-loan');
        form.submit();
      }

      const handleClickOutside = (event) => {
        const dropdownButton = document.getElementById('nav-button')
        const dropdownMenu = document.getElementById('dropdownMenu')
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
          isDropdownHidden.value = true
        }
      }

      const isDropdownHidden = ref(true)

      const toggleDropdown = () => {
        isDropdownHidden.value = !isDropdownHidden.value
      }

      onMounted(() => {
        window.addEventListener('click', handleClickOutside)

      })

      onBeforeUnmount(() => {
        window.removeEventListener('click', handleClickOutside)

      })

      return {
        searchs,
        isImage,
        backButton,
        deleteBook,
        isUpdate,
        updateIsUpdate,
        dataUpdate,
        formUpdate,
        isAlertSuccess,
        isAlertError,
        addBookLoan,
        isDropdownHidden,
        toggleDropdown,
        goToAddBook,
        dataBookLoan,
        isBookLoan,
        deleteBookLoan
      }
    }
  }).mount('#app')
</script>

</html>