<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpustakaan Online</title>
  <link rel="stylesheet" href="/css/app.css">
  <link rel="icon" href="/img/Icon-Perpustakaan.png" type="image/png">
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
  <div id="alertSuccess" class="alert p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
    <span class="font-medium">Behasil!</span> Data Buku Baru Berhasil Ditambahkan.
  </div>
  <div id="alertError" class="alert p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
    <span class="font-medium">Gagal!</span> Data Buku Baru Gagal Ditambahkan.
  </div>
  <header class="w-full">
  <div class="w-full h-20 py-4 flex justify-center items-center border-b-2 border-b-gray-900">
        <h1 class="text-3xl font-bold">SELAMAT DATANG DI PERPUSTAKAAN ONLINE</h1>
      </div>
    <div class="w-full flex justify-start items-center bg-sky-950 p-3">
      <nav>
        <div>
          <button id="back-button" class="text-white flex flex-row bg-blue-700 items-center hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4" />
            </svg>
            <p class="text-xl">Back</p>
          </button>

        </div>
      </nav>
    </div>
  </header>
  <form class="max-w-md mx-auto mt-16 bg-slate-900 py-5 px-6 rounded-xl" id="bookForm">
    <h1 class="text-2xl font-extrabold text-white text-center my-8">Tambahkan Buku Baru</h1>
    <div class="relative z-0 w-full mb-5 group">
      <input type="text" name="title" id="title" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
      <label for="title" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Title</label>
    </div>
    <div class="relative z-0 w-full mb-5 group">
      <input type="text" name="author" id="author" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
      <label for="author" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Author</label>
    </div>
    <div class="relative z-0 w-full mb-5 group">
      <input type="number" name="published_year" id="published_year" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
      <label for="published_year" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Published Year</label>
    </div>
    <div class="mb-5 group">
      <label for="synopsis" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Synopsis</label>
      <textarea id="synopsis" name="synopsis" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Suatu har yang hujan...."></textarea>
    </div>
    <div class="mb-5 group">
      <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image">Sampul Buku</label>
      <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="image" id="image" type="file" name="image">

    </div>
    <div class="text-center my-3">
      <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambahkan Buku</button>

    </div>
  </form>
</body>
<script>
  let forms = document.getElementById('bookForm');
  forms.addEventListener('submit', async function(event) {
    event.preventDefault(); // Mencegah form dari submit default

    const form = event.target;
    const formData = new FormData(form);
    const response = await fetch('/api/book', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();
    const alertSuccess = document.getElementById('alertSuccess');
    const alertError = document.getElementById('alertError');

    if (result.status === 'success') {
      showAlert(alertSuccess);
      forms.reset();
    } else {
      showAlert(alertError);
    }

    function showAlert(alertElement) {
      alertElement.classList.add('show');
      setTimeout(function() {
        alertElement.classList.remove('show');
      }, 3000);
    }
  });
</script>
<script>
  let get_back = document.getElementById('back-button');
  get_back.addEventListener('click', function() {
    const currentDomain = window.location.hostname; // Mendapatkan domain saat ini
    const referrer = document.referrer;

    if (referrer) {
      // Cek apakah referrer dari domain yang sama
      const referrerDomain = (new URL(referrer)).hostname;
      if (referrerDomain === currentDomain) {
        window.history.back();
      } else {
        window.location.href = window.location.protocol + "//" + currentDomain; // Kembali ke halaman utama
      }
    } else {
      // Jika tidak ada history atau referrer
      window.location.href = window.location.protocol + "//" + currentDomain; // Kembali ke halaman utama
    }
  });
</script>

</html>