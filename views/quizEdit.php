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
      <span class="font-medium">Gagal!</span> Data Quiz Baru Gagal Ditambahkan.
    </div>
    <header class="w-full">
      <div class="w-full h-20 py-4 flex justify-center items-center border-b-2 border-b-gray-900">
        <h1 class="text-3xl font-bold">SELAMAT DATANG DI PEMBELAJARAN ONLINE</h1>
      </div>
      <div class="bg-sky-950 p-5">
        <nav class="w-full flex justify-between items-center">
          <button id="back-button"
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
    <main class="container mx-auto">
      <div class="max-w-[90%] mx-auto mt-16 bg-slate-900 py-5 px-6 rounded-xl">
        <h2 class="text-center text-2xl font-bold text-white border-b-4  pb-1 mb-6">
          Data Score
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
          <div v-for="user in userScore" :key="user.id"
            class="bg-white shadow-md rounded-lg p-6 border border-gray-200 transition-transform transform hover:scale-105">
            <h3 class="text-xl font-semibold text-indigo-700">{{ user.display_name }}</h3>
            <p class="text-gray-600">Score: <span class="font-bold">{{ user.score }}</span></p>
            <!-- <p class="text-gray-600">Rank: <span class="font-semibold">{{ user.rank }}</span></p> -->
            <p class="text-gray-500 text-sm">Date: {{ formatDate(user.created_at) }}</p>

          </div>
        </div>

      </div>
      <div class="max-w-[90%] mx-auto mt-16 bg-slate-900 py-5 px-6 rounded-xl" id="quizForm">
        <h1 class="text-2xl font-extrabold text-white text-center my-8">Tambahkan Quiz Baru</h1>
        <div class="relative z-0 w-full mb-5 group">
          <label for="title" class="text-white">Judul Quiz</label>
          <input type="text" id="title" name="title" class="w-full bg-white text-slate-800 p-3 rounded-lg mt-1"
            v-model="title">
        </div>
        <div v-for="data in dataQuiz" :key="data.order_number">
          <h3 class="text-white font-bold text-4xl">Quiz {{data.order_number}}</h3>
          <div class="relative z-0 w-full mb-5 group">
            <label :for="`question-${data.id}`" class="text-white">Pertanyaan Quiz</label>
            <textarea :id="`question-${data.id}`" :name="`question-${data.id}`"
              class="w-full bg-white text-slate-800 p-3 rounded-lg mt-1" v-model="data.question"></textarea>
          </div>
          <div class="relative z-0 w-full mb-5 group">
            <label :for="`option1-${data.id}`" class="text-white">Option 1</label>
            <input type="text" :id="`option1-${data.id}`" :name="`option1-${data.id}`"
              class="w-full bg-white text-slate-800 p-3 rounded-lg mt-1" v-model="data.option1">
          </div>
          <div class="relative z-0 w-full mb-5 group">
            <label :for="`option2-${data.id}`" class="text-white">Option 2</label>
            <input type="text" :id="`option2-${data.id}`" :name="`option2-${data.id}`"
              class="w-full bg-white text-slate-800 p-3 rounded-lg mt-1" v-model="data.option2">
          </div>
          <div class="relative z-0 w-full mb-5 group">
            <label :for="`option3-${data.id}`" class="text-white">Option 3</label>
            <input type="text" :id="`option3-${data.id}`" :name="`option3-${data.id}`"
              class="w-full bg-white text-slate-800 p-3 rounded-lg mt-1" v-model="data.option3">
          </div>
          <div class="relative z-0 w-full mb-5 group">
            <label :for="`option4-${data.id}`" class="text-white">Option 4</label>
            <input type="text" :id="`option4-${data.id}`" :name="`option4-${data.id}`"
              class="w-full bg-white text-slate-800 p-3 rounded-lg mt-1" v-model="data.option4">
          </div>
          <div class="relative z-0 w-full mb-5 group">
            <label :for="`answer-${data.id}`" class="text-white">Jawaban</label>
            <select :id="`answer-${data.id}`" :name="`answer-${data.id}`"
              class="w-full bg-white p-3 rounded-lg mt-1 text-slate-800" v-model="data.answer">
              <option value="" disabled selected>Pilih Jawaban</option>
              <option value="option1">Option 1</option>
              <option value="option2">Option 2</option>
              <option value="option3">Option 3</option>
              <option value="option4">Option 4</option>
            </select>
          </div>
          <div>
            <button type="button"
              class="w-full bg-red-500 text-white p-3 rounded-lg mt-1 hover:bg-red-600 focus:outline-none mb-12"
              v-on:click="deleteQuestion(data.id)">Hapus Pertanyaan</button>
          </div>
        </div>

        <button type="button"
          class="w-full bg-indigo-500 text-white p-3 rounded-lg mt-1 hover:bg-indigo-600 focus:outline-none"
          v-on:click="addQuiz">Tambahkan
          Quiz</button>
        <button type="button"
          class="w-full bg-green-500 text-white p-3 rounded-lg mt-1 hover:bg-green-600 focus:outline-none"
          v-on:click="saveQuiz">Save
          Quiz</button>
        <button type="button"
          class="w-full bg-red-500 text-white p-3 rounded-lg mt-1 hover:bg-red-600 focus:outline-none"
          v-on:click="deleteQuiz(dataId)">Hapus Permanen Quiz</button>
      </div>
    </main>
    <div class="h-6"></div>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      let get_back = document.getElementById('back-button');
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

      get_back.addEventListener('click', function () {
        const currentDomain = window.location.hostname;
        const referrer = document.referrer;

        if (referrer) {
          const referrerDomain = (new URL(referrer)).hostname;
          if (referrerDomain === currentDomain) {
            window.history.back();
          } else {
            window.location.href = window.location.protocol + "//" + currentDomain;
          }
        } else {
          window.location.href = window.location.protocol + "//" + currentDomain;
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
      onBeforeUnmount,
      toRaw
    } = Vue

    createApp({
      setup() {
        const dataQuiz = reactive([]);
        const dataId = ref('');
        const title = ref('');
        const userScore = reactive([]);
        const modelQuiz = {
          question: '',
          option1: '',
          option2: '',
          option3: '',
          option4: '',
          answer: '',
        }

        const questionId = ref(1);

        const addQuiz = () => {
          const newQuestion = {
            type: 'new',
            order_number: questionId.value,
            ...modelQuiz,
          };
          dataQuiz.push(newQuestion);
          questionId.value++;
        };

        async function getQuizById() {
          const path = window.location.pathname;
          const segments = path.split('/');
          const id = segments[2];

          fetch(`/api/quiz/${id}`)
            .then((response) => response.json())
            .then((data) => {
              if (data.status === 'success') {
                title.value = '';
                dataId.value = '';
                dataQuiz.splice(0, dataQuiz.length);

                // Isi nilai title dan dataId setelah mendapatkan data dari API
                title.value = data.data.title;
                dataId.value = data.data.id;
                questionId.value = data.data.questions.length + 1;

                data.data.questions.forEach((quiz) => {
                  dataQuiz.push(quiz);
                });

                // Panggil getAllUserScore setelah dataId memiliki nilai
                getAllUserScore();
              } else {
                showAlert();
              }
            })
            .catch((error) => {
              console.error('Error:', error);
              showAlert();
            });
        }

        onMounted(async () => {
          await getQuizById();
        });

        // Fungsi untuk mendapatkan skor user berdasarkan quiz ID
        async function getAllUserScore() {
          fetch(`/api/quiz/score/${dataId.value}`)
            .then((response) => response.json())
            .then((data) => {
              if (data.status === 'success') {
                console.log(data);
                userScore.splice(0, userScore.length);
                data.data.forEach((score) => {
                  userScore.push(score);
                });
              } else {
                showAlert();
              }
            })
            .catch((error) => {
              console.error('Error:', error);
              showAlert();
            });
        }

        function showAlert() {
          const alertElement = document.getElementById('alertError');
          alertElement.classList.add('show');
          setTimeout(function () {
            alertElement.classList.remove('show');
          }, 3000);
        }

        function checkData() {
          if (title.value === '') {
            alert('Judul Quiz tidak boleh kosong');
            return false;
          }

          for (let i = 0; i < dataQuiz.length; i++) {
            const quiz = dataQuiz[i];
            if (quiz.question === '' || quiz.option1 === '' || quiz.option2 === '' || quiz.option3 === '' || quiz.option4 === '' || quiz.answer === '') {
              alert('Data Quiz tidak boleh kosong');
              return false;
            }
          }

          return true;
        }

        async function saveQuiz() {
          console.log(
            {
              title: title.value,
              id: questionId.value,
              data: toRaw(dataQuiz),
            }
          );
          if (checkData()) {
            fetch('/api/quiz/update/' + dataId.value, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify(
                {
                  title: title.value,
                  id: questionId.value,
                  data: toRaw(dataQuiz),
                }
              ),
            })
              .then((response) => response.json())
              .then((data) => {
                if (data.status === 'success') {
                  getQuizById()
                } else {
                  showAlert();
                }
              })
              .catch((error) => {
                console.error('Error:', error);
                showAlert();
              });
          }
        }

        async function deleteQuestion(id) {
          try {
            const response = await fetch(`/api/question/del/${id}`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({})
            });

            if (response.ok) {
              const result = await response.json();
              if (result.status === 'success') {
                dataQuiz.forEach((quiz, idx) => {
                  quiz.id = idx + 1;
                  if (idx === dataQuiz.length - 1) {
                    questionId.value = idx + 2;
                  }
                });
                saveQuiz();
              } else {
                console.error('Failed to delete question. Message:', result.message);
              }
            } else {
              console.error('Failed to delete question. Status:', response.status);
            }
          } catch (error) {
            console.error('Error deleting question:', error);
          }
        }

        async function deleteQuiz(id) {
          if (confirm('Apakah anda yakin menghapus Quiz ini!')) {
            try {
              const url = `/api/quiz/delete/${id}`;
              const response = await fetch(url, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                  id: id
                })
              });

              if (response.ok) {
                const result = await response.json();
                if (result.status === 'success') {
                  window.location.href = '/';
                } else {
                  console.error('Failed to delete question. Message:', result.message);
                }
              } else {
                console.error('Failed to delete question. Status:', response.status);
              }
            } catch (error) {
              console.error('Error deleting question:', error);
            }
          }
        }

        function formatDate(dateString) {
          const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };
          return new Date(dateString).toLocaleString('en-US', options);
        }

        return {
          dataQuiz,
          addQuiz,
          saveQuiz,
          title,
          deleteQuestion,
          deleteQuiz,
          dataId,
          userScore,
          formatDate
        }
      }
    }).mount('#app')
  </script>
</body>

</html>