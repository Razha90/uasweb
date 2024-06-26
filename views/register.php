<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpustakaan Online</title>
  <link rel="stylesheet" href="/css/app.css">
  <link rel="icon" href="/img/Icon-Perpustakaan.png" type="image/png">
</head>
<body>  
<header class="w-full">
      <div class="w-full h-20 py-4 flex justify-center items-center border-b-2 border-b-gray-900">
        <h1 class="text-3xl font-bold">SELAMAT DATANG DI PERPUSTAKAAN ONLINE</h1>
      </div>
      <div class="w-full flex justify-start items-center bg-sky-950 p-3">
        <nav>
            <div>
              <button id="back-button" class="text-white flex flex-row bg-blue-700 items-center hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
              <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
              </svg>
              <p class="text-xl">Back</p>
              </button>

            </div>
        </nav>
      </div>
    </header>
<main class="w-full min-h-[500px] h-full flex justify-center items-center">
<form class="min-w-[350px] w-[500px] max-w-[500px] border-2 border-gray-900 rounded-2xl py-8 px-5" action="/register" method="post">
  <h1 class="text-center text-2xl font-extrabold text-gray-900">Daftar Akun</h1>
  <div class="mb-5">
    <label for="display_name" class="block mb-2 text-sm font-medium text-gray-900">Your Name</label>
    <input type="text" id="display_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Name" name="display_name" required />
  </div>
  <div class="mb-5">
    <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
    <input type="text" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Username" name="username" required />
  </div>
  <div class="mb-5">
    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Your password</label>
    <input type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="password" required />
  </div>
  <button type="submit" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
</form>
</main>

</body>
<script>
  let get_back = document.getElementById('back-button');
  get_back.addEventListener('click', function() {
    const currentDomain = window.location.hostname;  // Mendapatkan domain saat ini
            const referrer = document.referrer;

            if (referrer) {
                // Cek apakah referrer dari domain yang sama
                const referrerDomain = (new URL(referrer)).hostname;
                if (referrerDomain === currentDomain) {
                    window.history.back();
                } else {
                    window.location.href = window.location.protocol + "//" + currentDomain;  // Kembali ke halaman utama
                }
            } else {
                // Jika tidak ada history atau referrer
                window.location.href = window.location.protocol + "//" + currentDomain;  // Kembali ke halaman utama
            }
  });
</script>
</html>